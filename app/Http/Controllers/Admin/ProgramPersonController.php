<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;

class ProgramPersonController extends Controller
{
    public function create(Program $program)
    {
        return view('admin.program-people.create', compact('program'));
    }

    public function store(Request $request, Program $program)
    {
        $data = $request->validate([
            'role'     => ['required', 'in:head,coordinator,instructor'],
            'name'     => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'photo'    => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $data['program_id'] = $program->id;
        $data['order']      = $program->people()->max('order') + 1;

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $this->storePhoto($request->file('photo'));
        }

        unset($data['photo']);
        ProgramPerson::create($data);

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Person added.');
    }

    public function edit(Program $program, ProgramPerson $person)
    {
        abort_if($person->program_id !== $program->id, 404);
        return view('admin.program-people.edit', compact('program', 'person'));
    }

    public function update(Request $request, Program $program, ProgramPerson $person)
    {
        abort_if($person->program_id !== $program->id, 404);

        $data = $request->validate([
            'role'         => ['required', 'in:head,coordinator,instructor'],
            'name'         => ['required', 'string', 'max:255'],
            'position'     => ['nullable', 'string', 'max:255'],
            'photo'        => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('remove_photo') && $person->photo_path) {
            Storage::disk('public')->delete($person->photo_path);
            $data['photo_path'] = null;
        }

        if ($request->hasFile('photo')) {
            if ($person->photo_path) {
                Storage::disk('public')->delete($person->photo_path);
            }
            $data['photo_path'] = $this->storePhoto($request->file('photo'));
        }

        unset($data['photo'], $data['remove_photo']);
        $person->update($data);

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Person updated.');
    }

    public function destroy(Program $program, ProgramPerson $person)
    {
        abort_if($person->program_id !== $program->id, 404);
        if ($person->photo_path) {
            Storage::disk('public')->delete($person->photo_path);
        }
        $person->delete();

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Person removed.');
    }

    public function reorder(Request $request, Program $program)
    {
        $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($request->order as $pos => $id) {
            ProgramPerson::where('id', $id)
                ->where('program_id', $program->id)
                ->update(['order' => $pos + 1]);
        }

        return response()->json(['ok' => true]);
    }

    // ── Private helper ────────────────────────────────────────────────────

    private function storePhoto($file): string
    {
        $filename  = Str::random(40) . '.jpg';
        $directory = storage_path('app/public/program-people');

        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $manager = new ImageManager(new Driver());

        $manager->decode($file->getRealPath())
            ->cover(400, 400)
            ->encodeUsingFormat(Format::JPEG, 85)
            ->save($directory . '/' . $filename);

        return 'program-people/' . $filename;
    }
}

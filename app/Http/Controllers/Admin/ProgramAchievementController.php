<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramAchievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramAchievementController extends Controller
{
    public function create(Program $program)
    {
        return view('admin.program-achievements.create', compact('program'));
    }

    public function store(Request $request, Program $program)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'year'        => ['nullable', 'string', 'max:50'],
            'photo'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $data['program_id'] = $program->id;
        $data['order']      = $program->achievements()->max('order') + 1;

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('program-achievements', 'public');
        }

        unset($data['photo']);
        ProgramAchievement::create($data);

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Achievement added.');
    }

    public function edit(Program $program, ProgramAchievement $achievement)
    {
        abort_if($achievement->program_id !== $program->id, 404);
        return view('admin.program-achievements.edit', compact('program', 'achievement'));
    }

    public function update(Request $request, Program $program, ProgramAchievement $achievement)
    {
        abort_if($achievement->program_id !== $program->id, 404);

        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'year'         => ['nullable', 'string', 'max:50'],
            'photo'        => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('remove_photo') && $achievement->photo_path) {
            Storage::disk('public')->delete($achievement->photo_path);
            $data['photo_path'] = null;
        }

        if ($request->hasFile('photo')) {
            if ($achievement->photo_path) Storage::disk('public')->delete($achievement->photo_path);
            $data['photo_path'] = $request->file('photo')->store('program-achievements', 'public');
        }

        unset($data['photo'], $data['remove_photo']);
        $achievement->update($data);

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Achievement updated.');
    }

    public function destroy(Program $program, ProgramAchievement $achievement)
    {
        abort_if($achievement->program_id !== $program->id, 404);
        if ($achievement->photo_path) Storage::disk('public')->delete($achievement->photo_path);
        $achievement->delete();

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Achievement deleted.');
    }

    public function reorder(Request $request, Program $program)
    {
        $request->validate(['order' => ['required', 'array'], 'order.*' => ['integer']]);

        foreach ($request->order as $pos => $id) {
            ProgramAchievement::where('id', $id)->where('program_id', $program->id)
                ->update(['order' => $pos + 1]);
        }

        return response()->json(['ok' => true]);
    }
}

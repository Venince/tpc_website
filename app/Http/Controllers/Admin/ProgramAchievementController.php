<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramAchievement;
use App\Models\ProgramAchievementImage;
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
            'photos'      => ['nullable', 'array', 'max:10'],
            'photos.*'    => ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $data['program_id'] = $program->id;
        $data['order']      = $program->achievements()->max('order') + 1;

        unset($data['photos']);

        $achievement = ProgramAchievement::create($data);

        // Store each uploaded photo as a related image
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $file) {
                $path = $file->store('program-achievement-images', 'public');
                $achievement->images()->create(['path' => $path, 'order' => $i + 1]);
            }
        }

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Achievement added.');
    }

    public function edit(Program $program, ProgramAchievement $achievement)
    {
        abort_if($achievement->program_id !== $program->id, 404);
        $achievement->load('images');
        return view('admin.program-achievements.edit', compact('program', 'achievement'));
    }

    public function update(Request $request, Program $program, ProgramAchievement $achievement)
    {
        abort_if($achievement->program_id !== $program->id, 404);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'year'        => ['nullable', 'string', 'max:50'],
            'photos'      => ['nullable', 'array', 'max:10'],
            'photos.*'    => ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        unset($data['photos']);
        $achievement->update($data);

        // Append newly uploaded photos
        if ($request->hasFile('photos')) {
            $nextOrder = $achievement->images()->max('order') + 1;
            foreach ($request->file('photos') as $i => $file) {
                $path = $file->store('program-achievement-images', 'public');
                $achievement->images()->create(['path' => $path, 'order' => $nextOrder + $i]);
            }
        }

        return redirect()->route('admin.programs.achievements.edit', [$program, $achievement])
            ->with('success', 'Achievement updated.');
    }

    public function destroyImage(Program $program, ProgramAchievement $achievement, ProgramAchievementImage $image)
    {
        abort_if($achievement->program_id !== $program->id, 404);
        abort_if($image->program_achievement_id !== $achievement->id, 404);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('success', 'Image removed.');
    }

    public function destroy(Program $program, ProgramAchievement $achievement)
    {
        abort_if($achievement->program_id !== $program->id, 404);

        // Delete all related images from storage
        foreach ($achievement->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        // Legacy single photo_path
        if ($achievement->photo_path) {
            Storage::disk('public')->delete($achievement->photo_path);
        }

        $achievement->delete();

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Achievement deleted.');
    }

    public function reorder(Request $request, Program $program)
    {
        $request->validate(['order' => ['required', 'array'], 'order.*' => ['integer']]);

        foreach ($request->order as $pos => $id) {
            ProgramAchievement::where('id', $id)
                ->where('program_id', $program->id)
                ->update(['order' => $pos + 1]);
        }

        return response()->json(['ok' => true]);
    }
}

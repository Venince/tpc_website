<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::orderBy('code')->paginate(10);
        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.programs.create');
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'code' => ['required','string','max:30','unique:programs,code'],
            'name' => ['required','string','max:255'],
            'department' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
            'is_active' => ['nullable','boolean'],

            'logo' => ['nullable','file','mimes:png,jpg,jpeg,webp','max:5120'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['slug'] = $this->uniqueSlug($data['name']);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('program-logos', 'public');
        }

        Program::create($data);

        return redirect()->route('admin.programs.index')->with('success', 'Program created!');
    }

    public function edit(Program $program)
    {
        return view('admin.programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $data = $request->validate([
            'code' => ['required','string','max:30', Rule::unique('programs','code')->ignore($program->id)],
            'name' => ['required','string','max:255'],
            'department' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
            'is_active' => ['nullable','boolean'],

            'remove_logo' => ['nullable','boolean'],

            'logo' => ['nullable','file','mimes:png,jpg,jpeg,webp','max:5120'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($program->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug($data['name'], $program->id);
        }

        if ($request->boolean('remove_logo') && $program->logo_path) {
            Storage::disk('public')->delete($program->logo_path);
            $data['logo_path'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($program->logo_path) {
                Storage::disk('public')->delete($program->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('program-logos', 'public');
        }

        $program->update($data);

        return redirect()->route('admin.programs.index')->with('success', 'Program updated!');
    }

    public function destroy(Program $program)
    {
        if ($program->logo_path) {
            Storage::disk('public')->delete($program->logo_path);
        }

        $program->delete();
        return redirect()->route('admin.programs.index')->with('success', 'Program deleted!');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base ?: 'program';

        $i = 1;
        while (
            Program::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}

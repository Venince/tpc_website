<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutSlideController extends Controller
{
    public function index()
    {
        $slides = AboutSlide::orderBy('sort_order')->get();
        return view('admin.about-slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.about-slides.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'     => 'nullable|string|max:255',
            'image'     => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'is_active' => 'nullable|boolean',
        ]);

        $data['image_path'] = $request->file('image')->store('about-slides', 'public');
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = AboutSlide::max('sort_order') + 1;
        unset($data['image']);

        AboutSlide::create($data);

        return redirect()->route('admin.about-slides.index')
                         ->with('success', 'Slide added successfully.');
    }

    public function edit(AboutSlide $aboutSlide)
    {
        return view('admin.about-slides.edit', compact('aboutSlide'));
    }

    public function update(Request $request, AboutSlide $aboutSlide)
    {
        $data = $request->validate([
            'title'     => 'nullable|string|max:255',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($aboutSlide->image_path);
            $data['image_path'] = $request->file('image')->store('about-slides', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', false);
        unset($data['image']);

        $aboutSlide->update($data);

        return redirect()->route('admin.about-slides.index')
                         ->with('success', 'Slide updated successfully.');
    }

    public function destroy(AboutSlide $aboutSlide)
    {
        Storage::disk('public')->delete($aboutSlide->image_path);
        $aboutSlide->delete();

        return redirect()->route('admin.about-slides.index')
                         ->with('success', 'Slide deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class NewsPostController extends Controller
{
    public function index()
    {
        $posts = \App\Models\NewsPost::orderByDesc('created_at')->paginate(10);

        return view('admin.news-posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.news-posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'category'     => ['required', 'string', 'max:60'],
            'excerpt'      => ['nullable', 'string', 'max:255'],
            'body'         => ['required', 'string'],
            'is_published' => ['nullable', 'boolean'],

            // ✅ Image upload (5MB = 5120 KB)
            'image'        => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
        ]);

        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['is_published'] = $request->boolean('is_published');

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('news-images', 'public');
        }

        NewsPost::create($data);

        return redirect()->route('admin.news-posts.index')->with('success', 'News post created!');
    }

    public function edit(NewsPost $newsPost)
    {
        return view('admin.news-posts.edit', compact('newsPost'));
    }

    public function update(Request $request, NewsPost $newsPost)
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'category'     => ['required', 'string', 'max:60'],
            'excerpt'      => ['nullable', 'string', 'max:255'],
            'body'         => ['required', 'string'],
            'is_published' => ['nullable', 'boolean'],

            // ✅ Image controls
            'remove_image' => ['nullable', 'boolean'],
            'image'        => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
        ]);

        $data['is_published'] = $request->boolean('is_published');

        // optional: regenerate slug if title changed
        if ($newsPost->title !== $data['title']) {
            $data['slug'] = $this->uniqueSlug($data['title'], $newsPost->id);
        }

        // publish time handling
        if ($data['is_published'] && !$newsPost->published_at) {
            $data['published_at'] = now();
        }
        if (!$data['is_published']) {
            // keep your preference:
            // If you want to require published_at for public display, it's safer to null it when unpublishing:
            $data['published_at'] = null;
        }

        // ✅ remove current image
        if ($request->boolean('remove_image') && $newsPost->image_path) {
            Storage::disk('public')->delete($newsPost->image_path);
            $data['image_path'] = null;
        }

        // ✅ replace image
        if ($request->hasFile('image')) {
            if ($newsPost->image_path) {
                Storage::disk('public')->delete($newsPost->image_path);
            }
            $data['image_path'] = $request->file('image')->store('news-images', 'public');
        }

        $newsPost->update($data);

        return redirect()->route('admin.news-posts.index')->with('success', 'News post updated!');
    }

    public function destroy(NewsPost $newsPost)
    {
        if ($newsPost->image_path) {
            Storage::disk('public')->delete($newsPost->image_path);
        }

        $newsPost->delete();

        return redirect()->route('admin.news-posts.index')->with('success', 'News post deleted!');
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base ?: 'news';

        $i = 1;
        while (
            NewsPost::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}

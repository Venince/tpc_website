<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsPostController extends Controller
{
    public function index()
    {
        $posts = NewsPost::orderByDesc('created_at')->paginate(10);

        return view('admin.news-posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.news-posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'    => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:60'],
            'excerpt'  => ['nullable', 'string', 'max:255'],
            'body'     => ['required', 'string'],
            'image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:5120', 'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
        ]);

        $data['slug'] = $this->uniqueSlug($data['title']);

        // Superadmin publishes directly; regular admin goes to pending
        if (Auth::user()->is_super_admin) {
            $data['status']       = NewsPost::STATUS_APPROVED;
            $data['is_published'] = true;
            $data['published_at'] = now();
            $data['reviewed_at']  = now();
            $data['reviewed_by']  = Auth::id();
            $data['review_note']  = 'Published directly by superadmin.';
        } else {
            $data['status']       = NewsPost::STATUS_PENDING;
            $data['is_published'] = false;
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('news-images', 'public');
        }

        NewsPost::create($data);

        $message = Auth::user()->is_super_admin
            ? 'News post published successfully.'
            : 'News post submitted for superadmin review.';

        return redirect()
            ->route('admin.news-posts.index')
            ->with('success', $message);
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
            'remove_image' => ['nullable', 'boolean'],
            'image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:5120', 'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
        ]);

        if ($newsPost->title !== $data['title']) {
            $data['slug'] = $this->uniqueSlug($data['title'], $newsPost->id);
        }

        // Superadmin edits stay published; regular admin edits reset to pending
        if (Auth::user()->is_super_admin) {
            $data['status']       = NewsPost::STATUS_APPROVED;
            $data['is_published'] = true;
            $data['published_at'] = $newsPost->published_at ?? now();
            $data['reviewed_at']  = now();
            $data['reviewed_by']  = Auth::id();
            $data['review_note']  = 'Updated and published directly by superadmin.';
        } else {
            $data['status']       = NewsPost::STATUS_PENDING;
            $data['is_published'] = false;
            $data['published_at'] = null;
            $data['reviewed_at']  = null;
            $data['reviewed_by']  = null;
            $data['review_note']  = null;
        }

        if ($request->boolean('remove_image') && $newsPost->image_path) {
            Storage::disk('public')->delete($newsPost->image_path);
            $data['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            if ($newsPost->image_path) {
                Storage::disk('public')->delete($newsPost->image_path);
            }
            $data['image_path'] = $request->file('image')->store('news-images', 'public');
        }

        $newsPost->update($data);

        $message = Auth::user()->is_super_admin
            ? 'News post updated and published.'
            : 'News post updated and re-submitted for review.';

        return redirect()
            ->route('admin.news-posts.index')
            ->with('success', $message);
    }

    public function destroy(NewsPost $newsPost)
    {
        if ($newsPost->image_path) {
            Storage::disk('public')->delete($newsPost->image_path);
        }

        $newsPost->delete();

        return redirect()
            ->route('admin.news-posts.index')
            ->with('success', 'News post deleted.');
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base ?: 'news';
        $i    = 1;

        while (
            NewsPost::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ]);

        $posts = NewsPost::whereIn('id', $request->ids)->get();

        foreach ($posts as $post) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $post->delete();
        }

        return redirect()
            ->route('admin.news-posts.index')
            ->with('success', count($request->ids) . ' post(s) deleted.');
    }

    public function repost(NewsPost $newsPost)
    {
        // Copy the image so each post owns its own file
        $newImagePath = null;
        if ($newsPost->image_path && Storage::disk('public')->exists($newsPost->image_path)) {
            $extension    = pathinfo($newsPost->image_path, PATHINFO_EXTENSION);
            $newImagePath = 'news-images/' . \Illuminate\Support\Str::random(40) . '.' . $extension;
            Storage::disk('public')->copy($newsPost->image_path, $newImagePath);
        }

        $newPost = NewsPost::create([
            'title'        => $newsPost->title,
            'slug'         => $this->uniqueSlug($newsPost->title),
            'category'     => $newsPost->category,
            'excerpt'      => $newsPost->excerpt,
            'body'         => $newsPost->body,
            'image_path'   => $newImagePath,
            'is_published' => Auth::user()->is_super_admin,
            'status'       => Auth::user()->is_super_admin
                                ? NewsPost::STATUS_APPROVED
                                : NewsPost::STATUS_PENDING,
            'published_at' => Auth::user()->is_super_admin ? now() : null,
            'reviewed_at'  => Auth::user()->is_super_admin ? now() : null,
            'reviewed_by'  => Auth::user()->is_super_admin ? Auth::id() : null,
            'review_note'  => Auth::user()->is_super_admin ? 'Reposted by superadmin.' : null,
        ]);

        $message = Auth::user()->is_super_admin
            ? 'Post reposted and published.'
            : 'Post reposted and submitted for review.';

        return redirect()
            ->route('admin.news-posts.index')
            ->with('success', $message);
    }
}

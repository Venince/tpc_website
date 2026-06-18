<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use App\Models\NewsPostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsPostController extends Controller
{
    public function index()
    {
        $posts = NewsPost::orderByDesc('created_at')->with('galleryImages')->paginate(10);
        return view('admin.news-posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.news-posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'category'    => ['required', 'string', 'max:60'],
            'excerpt'     => ['nullable', 'string', 'max:255'],
            'body'        => ['required', 'string'],
            'photos'      => ['nullable', 'array', 'max:20'],
            'photos.*'    => ['file', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
        ]);

        $data['slug'] = $this->uniqueSlug($data['title']);

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

        unset($data['photos']);

        $post = NewsPost::create($data);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $file) {
                $post->galleryImages()->create([
                    'image_path' => $file->store('news-gallery', 'public'),
                    'order'      => $i,
                ]);
            }
        }

        $message = Auth::user()->is_super_admin
            ? 'News post published successfully.'
            : 'News post submitted for superadmin review.';

        return redirect()->route('admin.news-posts.index')->with('success', $message);
    }

    public function edit(NewsPost $newsPost)
    {
        $newsPost->load('galleryImages');
        return view('admin.news-posts.edit', compact('newsPost'));
    }

    public function update(Request $request, NewsPost $newsPost)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'category'    => ['required', 'string', 'max:60'],
            'excerpt'     => ['nullable', 'string', 'max:255'],
            'body'        => ['required', 'string'],
            'photos'      => ['nullable', 'array', 'max:20'],
            'photos.*'    => ['file', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
        ]);

        if ($newsPost->title !== $data['title']) {
            $data['slug'] = $this->uniqueSlug($data['title'], $newsPost->id);
        }

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

        unset($data['photos']);
        $newsPost->update($data);

        if ($request->hasFile('photos')) {
            $nextOrder = ($newsPost->galleryImages()->max('order') ?? -1) + 1;
            foreach ($request->file('photos') as $i => $file) {
                $newsPost->galleryImages()->create([
                    'image_path' => $file->store('news-gallery', 'public'),
                    'order'      => $nextOrder + $i,
                ]);
            }
        }

        $message = Auth::user()->is_super_admin
            ? 'News post updated and published.'
            : 'News post updated and re-submitted for review.';

        return redirect()->route('admin.news-posts.index')->with('success', $message);
    }

    public function destroyGalleryImage(NewsPost $newsPost, NewsPostImage $image)
    {
        abort_unless($image->news_post_id === $newsPost->id, 404);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return back()->with('success', 'Photo removed.');
    }

    public function destroy(NewsPost $newsPost)
    {
        if ($newsPost->image_path) {
            Storage::disk('public')->delete($newsPost->image_path);
        }

        foreach ($newsPost->galleryImages as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
        $newsPost->galleryImages()->delete();
        $newsPost->delete();

        return redirect()->route('admin.news-posts.index')->with('success', 'News post deleted.');
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

        $posts = NewsPost::with('galleryImages')->whereIn('id', $request->ids)->get();

        foreach ($posts as $post) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            foreach ($post->galleryImages as $img) {
                Storage::disk('public')->delete($img->image_path);
            }
            $post->galleryImages()->delete();
            $post->delete();
        }

        return redirect()->route('admin.news-posts.index')
            ->with('success', count($request->ids) . ' post(s) deleted.');
    }

    public function repost(NewsPost $newsPost)
    {
        // Legacy single image_path (kept for backward compatibility)
        $newImagePath = null;
        if ($newsPost->image_path && Storage::disk('public')->exists($newsPost->image_path)) {
            $extension    = pathinfo($newsPost->image_path, PATHINFO_EXTENSION);
            $newImagePath = 'news-images/' . Str::random(40) . '.' . $extension;
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
            'status'       => Auth::user()->is_super_admin ? NewsPost::STATUS_APPROVED : NewsPost::STATUS_PENDING,
            'published_at' => Auth::user()->is_super_admin ? now() : null,
            'reviewed_at'  => Auth::user()->is_super_admin ? now() : null,
            'reviewed_by'  => Auth::user()->is_super_admin ? Auth::id() : null,
            'review_note'  => Auth::user()->is_super_admin ? 'Reposted by superadmin.' : null,
        ]);

        // Copy gallery images (the actual photo source now) to the new post
        foreach ($newsPost->galleryImages as $i => $img) {
            if (Storage::disk('public')->exists($img->image_path)) {
                $ext     = pathinfo($img->image_path, PATHINFO_EXTENSION);
                $newPath = 'news-gallery/' . Str::random(40) . '.' . $ext;
                Storage::disk('public')->copy($img->image_path, $newPath);
                $newPost->galleryImages()->create([
                    'image_path' => $newPath,
                    'caption'    => $img->caption,
                    'order'      => $i,
                ]);
            }
        }

        $message = Auth::user()->is_super_admin
            ? 'Post reposted and published.'
            : 'Post reposted and submitted for review.';

        return redirect()->route('admin.news-posts.index')->with('success', $message);
    }
}

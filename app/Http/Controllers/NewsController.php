<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $q        = $request->query('q');
        $category = $request->query('category');

        // A shared link (/news?post=slug) pins that one post at the top of the feed
        $focusPost = null;
        $slug      = $request->query('post');
        if (is_string($slug) && $slug !== '') {
            $focusPost = NewsPost::query()
                ->published()
                ->with('galleryImages')
                ->where('slug', $slug)
                ->first();
        }

        $posts = NewsPost::query()
            ->published()
            ->with('galleryImages')
            ->when($focusPost, fn ($query) => $query->whereKeyNot($focusPost->getKey()))
            ->when($q, fn($query) => $query->where(function ($qq) use ($q) {
                $qq->where('title',   'like', "%{$q}%")
                ->orWhere('excerpt', 'like', "%{$q}%")
                ->orWhere('body',    'like', "%{$q}%");
            }))
            ->when($category, fn($query) => $query->where('category', $category))
            ->orderByDesc('published_at')
            ->paginate(10)
            ->appends($request->except(['post', 'page']));

        return view('public.news.index', compact('posts', 'focusPost'));
    }

    /**
     * The separate article page was retired: a post is read in the feed itself.
     * Old /news/{slug} links (home page, links people already shared) forward to
     * the feed with that post pinned at the top.
     */
    public function show(NewsPost $newsPost)
    {
        abort_unless(
            $newsPost->isApproved() && $newsPost->is_published && $newsPost->published_at,
            404
        );

        return redirect()->route('news.index', ['post' => $newsPost->slug], 301);
    }

    public function like(NewsPost $newsPost)
    {
        abort_unless(
            $newsPost->isApproved() && $newsPost->is_published && $newsPost->published_at,
            404
        );

        $newsPost->increment('likes_count');

        return response()->json(['likes_count' => $newsPost->likes_count]);
    }

    public function unlike(NewsPost $newsPost)
    {
        abort_unless(
            $newsPost->isApproved() && $newsPost->is_published && $newsPost->published_at,
            404
        );

        $newsPost->decrement('likes_count');

        return response()->json(['likes_count' => $newsPost->likes_count]);
    }
}

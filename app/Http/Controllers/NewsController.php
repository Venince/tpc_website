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

        $posts = NewsPost::query()
            ->published()   // scope: status=approved, is_published=true, published_at not null
            ->when($q, fn($query) => $query->where(function ($qq) use ($q) {
                $qq->where('title',   'like', "%{$q}%")
                   ->orWhere('excerpt', 'like', "%{$q}%")
                   ->orWhere('body',    'like', "%{$q}%");
            }))
            ->when($category, fn($query) => $query->where('category', $category))
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('public.news.index', compact('posts'));
    }

    public function show(NewsPost $newsPost)
    {
        // Only approved + published posts are visible to the public
        abort_unless(
            $newsPost->isApproved() && $newsPost->is_published && $newsPost->published_at,
            404
        );

        return view('public.news.show', ['post' => $newsPost]);
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

<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $category = $request->query('category');

        $posts = NewsPost::query()
            ->where('is_published', 1)
            ->whereNotNull('published_at')
            ->when($q, fn($query) => $query->where(function ($qq) use ($q) {
                $qq->where('title', 'like', "%{$q}%")
                   ->orWhere('excerpt', 'like', "%{$q}%")
                   ->orWhere('body', 'like', "%{$q}%");
            }))
            ->when($category, fn($query) => $query->where('category', $category))
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('public.news.index', compact('posts'));
    }

    public function show(NewsPost $newsPost)
    {
        abort_unless($newsPost->is_published && $newsPost->published_at, 404);

        return view('public.news.show', [
            'post' => $newsPost,
        ]);
    }
}

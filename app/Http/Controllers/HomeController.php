<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use App\Models\Program;

class HomeController extends Controller
{
    public function index()
    {
        return view('public.home', [

            'programs' => Program::where('is_active', 1)
                ->orderBy('code')
                ->get(),

            'latestNews' => NewsPost::where('is_published', 1)
                ->whereNotNull('published_at')
                ->orderByDesc('published_at')
                ->take(3)
                ->get(),
        ]);
    }
}

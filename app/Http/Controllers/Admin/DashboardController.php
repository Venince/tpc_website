<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use App\Models\Program;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'programCount' => Program::count(),
            'activeProgramCount' => Program::where('is_active', true)->count(),
            'newsCount' => NewsPost::count(),
            'publishedNewsCount' => NewsPost::where('is_published', true)->count(),
            'recentNews' => NewsPost::orderByDesc('created_at')->take(5)->get(),
        ]);
    }
}

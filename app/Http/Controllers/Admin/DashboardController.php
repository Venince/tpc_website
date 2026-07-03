<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use App\Models\Program;
use App\Models\ContactMessage;
use App\Models\Feedback;

class DashboardController extends Controller
{
    public function index()
    {
        $ratingCounts = Feedback::selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        return view('admin.dashboard', [
            'programCount'       => Program::count(),
            'activeProgramCount' => Program::where('is_active', true)->count(),
            'newsCount'          => NewsPost::count(),
            'publishedNewsCount' => NewsPost::where('is_published', true)->count(),
            'pendingNewsCount'   => NewsPost::pending()->count(),
            'messageCount'       => ContactMessage::count(),
            'unreadMessageCount' => ContactMessage::where('is_read', false)->count(),
            'recentNews'         => NewsPost::orderByDesc('created_at')->take(5)->get(),

            'feedbackCount'       => Feedback::count(),
            'unreadFeedbackCount' => Feedback::where('is_read', false)->count(),
            'avgFeedbackRating'   => round((float) Feedback::avg('rating'), 1),
            'ratingCounts'        => $ratingCounts, // [5 => 12, 4 => 8, ...]
            'recentFeedback'      => Feedback::orderByDesc('created_at')->take(5)->get(),
        ]);
    }
}

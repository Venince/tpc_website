<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use App\Models\Program;
use App\Models\ContactMessage;
use App\Models\Feedback;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $ratingCounts = Feedback::selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        $activityChart = $this->buildActivityChart(14);

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

            'activityChart'       => $activityChart,
        ]);
    }

    /**
     * Build daily counts for the last $days days for Messages, Feedback, and News posts.
     * Returns arrays already aligned by index so the chart can zip them directly.
     */
    private function buildActivityChart(int $days): array
    {
        $start = Carbon::today()->subDays($days - 1);

        $dates = collect(range(0, $days - 1))
            ->map(fn ($i) => $start->copy()->addDays($i)->toDateString());

        $messagesByDay = ContactMessage::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', $start)
            ->groupBy('d')
            ->pluck('c', 'd');

        $feedbackByDay = Feedback::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', $start)
            ->groupBy('d')
            ->pluck('c', 'd');

        $newsByDay = NewsPost::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', $start)
            ->groupBy('d')
            ->pluck('c', 'd');

        return [
            'labels'   => $dates->map(fn ($d) => Carbon::parse($d)->format('M j'))->values()->all(),
            'messages' => $dates->map(fn ($d) => (int) ($messagesByDay[$d] ?? 0))->values()->all(),
            'feedback' => $dates->map(fn ($d) => (int) ($feedbackByDay[$d] ?? 0))->values()->all(),
            'news'     => $dates->map(fn ($d) => (int) ($newsByDay[$d] ?? 0))->values()->all(),
        ];
    }
}

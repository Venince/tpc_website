<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsReviewController extends Controller
{
    /** Inbox: all posts awaiting review (+ filter by status) */
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $posts = NewsPost::query()
            ->when(
                in_array($status, ['pending', 'approved', 'declined']),
                fn($q) => $q->where('status', $status),
            )
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'pending'  => NewsPost::pending()->count(),
            'approved' => NewsPost::approved()->count(),
            'declined' => NewsPost::declined()->count(),
        ];

        return view('admin.news-review.index', compact('posts', 'status', 'counts'));
    }

    /** Preview a single post before deciding */
    public function show(NewsPost $newsPost)
    {
        return view('admin.news-review.show', compact('newsPost'));
    }

    /** Approve -> publish immediately with a fixed system note */
    public function approve(Request $request, NewsPost $newsPost)
    {
        $newsPost->update([
            'status'       => NewsPost::STATUS_APPROVED,
            'is_published' => true,
            'published_at' => now(),
            'reviewed_at'  => now(),
            'reviewed_by'  => Auth::id(),
            'review_note'  => 'Approved and published by superadmin on ' . now()->format('F d, Y \a\t g:i A') . '.',
        ]);

        return redirect()
            ->route('admin.news-review.index')
            ->with('success', '"' . $newsPost->title . '" approved and published.');
    }

    /** Decline with a required custom note */
    public function decline(Request $request, NewsPost $newsPost)
    {
        $request->validate([
            'review_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $newsPost->update([
            'status'       => NewsPost::STATUS_DECLINED,
            'is_published' => false,
            'published_at' => null,
            'reviewed_at'  => now(),
            'reviewed_by'  => Auth::id(),
            'review_note'  => $request->input('review_note'),
        ]);

        return redirect()
            ->route('admin.news-review.index')
            ->with('success', '"' . $newsPost->title . '" has been declined.');
    }

    /** Set back to pending (undo approve/decline) */
    public function pending(NewsPost $newsPost)
    {
        $newsPost->update([
            'status'       => NewsPost::STATUS_PENDING,
            'is_published' => false,
            'published_at' => null,
            'reviewed_at'  => null,
            'reviewed_by'  => null,
            'review_note'  => null,
        ]);

        return redirect()
            ->back()
            ->with('success', '"' . $newsPost->title . '" moved back to pending.');
    }
}

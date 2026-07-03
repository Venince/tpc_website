<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string) $request->get('q', ''));
        $status   = $request->get('status', 'unread');   // unread | read | all
        $rating   = $request->get('rating', 'all');      // 1-5 | all
        $category = $request->get('category', 'all');    // key | all

        $feedbacks = Feedback::query()
            ->when($status === 'unread', fn($qq) => $qq->where('is_read', false))
            ->when($status === 'read', fn($qq) => $qq->where('is_read', true))
            ->when($rating !== 'all', fn($qq) => $qq->where('rating', (int) $rating))
            ->when($category !== 'all', fn($qq) => $qq->where('category', $category))
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('message', 'like', "%{$q}%");
                });
            })
            ->orderBy('is_read')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $unreadCount  = Feedback::where('is_read', false)->count();
        $avgRating    = round((float) Feedback::avg('rating'), 1);
        $categories   = Feedback::CATEGORIES;

        return view('admin.feedback.index', compact(
            'feedbacks', 'q', 'status', 'rating', 'category', 'unreadCount', 'avgRating', 'categories'
        ));
    }

    public function show(Request $request, Feedback $feedback)
    {
        $skipAutoRead = (bool) $request->session()->pull('skip_auto_read', false);

        if (!$skipAutoRead) {
            $feedback->markAsRead();
        }

        return view('admin.feedback.show', compact('feedback'));
    }

    public function respond(Request $request, Feedback $feedback)
    {
        $data = $request->validate([
            'admin_response' => ['required', 'string', 'max:2000'],
        ]);

        $feedback->forceFill([
            'admin_response' => $data['admin_response'],
            'responded_at'   => now(),
        ])->save();

        return redirect()
            ->route('admin.feedback.show', $feedback)
            ->with('success', 'Response saved.');
    }

    public function markRead(Feedback $feedback)
    {
        $feedback->markAsRead();

        return redirect()
            ->route('admin.feedback.show', $feedback)
            ->with('success', 'Marked as read.');
    }

    public function markUnread(Request $request, Feedback $feedback)
    {
        $feedback->markAsUnread();
        $request->session()->flash('skip_auto_read', true);

        return redirect()
            ->route('admin.feedback.show', $feedback)
            ->with('success', 'Marked as unread.');
    }

    public function unreadCount()
    {
        $count = Feedback::where('is_read', false)->count();

        return response()
            ->json(['count' => $count])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return redirect()
            ->route('admin.feedback.index')
            ->with('success', 'Feedback deleted.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ]);

        Feedback::whereIn('id', $request->ids)->delete();

        return redirect()
            ->route('admin.feedback.index')
            ->with('success', count($request->ids) . ' feedback(s) deleted.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $status = $request->get('status', 'unread'); // unread | read | all

        $messages = ContactMessage::query()
            ->when($status === 'unread', fn($qq) => $qq->where('is_read', false))
            ->when($status === 'read', fn($qq) => $qq->where('is_read', true))
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('subject', 'like', "%{$q}%")
                        ->orWhere('message', 'like', "%{$q}%");
                });
            })
            ->orderBy('is_read')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $unreadCount = ContactMessage::where('is_read', false)->count();

        return view('admin.messages.index', compact('messages', 'q', 'status', 'unreadCount'));
    }

    public function show(Request $request, ContactMessage $message)
    {
        // ✅ if we just marked it unread, don't instantly mark read again
        $skipAutoRead = (bool) $request->session()->pull('skip_auto_read', false);

        if (!$skipAutoRead) {
            $message->markAsRead();
        }

        return view('admin.messages.show', compact('message'));
    }

    public function markRead(ContactMessage $message)
    {
        $message->markAsRead();

        // go back to show
        return redirect()
            ->route('admin.messages.show', $message)
            ->with('success', 'Marked as read.');
    }

    public function markUnread(Request $request, ContactMessage $message)
    {
        $message->markAsUnread();

        // ✅ prevent show() from re-marking it as read immediately
        $request->session()->flash('skip_auto_read', true);

        return redirect()
            ->route('admin.messages.show', $message)
            ->with('success', 'Marked as unread.');
    }

    public function unreadCount()
    {
        $count = ContactMessage::where('is_read', false)->count();

        return response()
            ->json(['count' => $count])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('public.feedback');
    }

    public function store(Request $request)
    {
        $request->merge([
            'category' => $request->category ?: null,
            'email'    => $request->email ?: null,
            'name'     => $request->name ?: null,
        ]);

        $data = $request->validate([
            'name'     => ['nullable', 'string', 'max:120'],
            'email'    => ['nullable', 'email', 'max:255'],
            'rating'   => ['required', 'integer', 'min:1', 'max:5'],
            'category' => ['nullable', 'string', 'in:' . implode(',', array_keys(Feedback::CATEGORIES))],
            'message'  => ['nullable', 'string', 'max:2000'],
            'page_url' => ['nullable', 'string', 'max:500'],

            // honeypot anti-spam
            'website'  => ['nullable', 'size:0'],
        ]);

        $feedback = Feedback::create([
            'name'       => $data['name']     ?? null,
            'email'      => $data['email']    ?? null,
            'rating'     => $data['rating'],
            'category'   => $data['category'] ?? null,
            'message'    => $data['message']  ?? null,
            'page_url'   => $data['page_url'] ?? $request->headers->get('referer'),
            'ip'         => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your feedback!',
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Thank you for your feedback!');
    }
}

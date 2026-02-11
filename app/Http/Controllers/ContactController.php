<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Support\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('public.contact');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required','string','max:120'],
            'email'   => ['required','email','max:255'],
            'subject' => ['required','string','max:160'],
            'message' => ['required','string','max:5000'],

            // honeypot anti-spam
            'website' => ['nullable','size:0'],
        ]);

        $msg = ContactMessage::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'subject'    => $data['subject'],
            'message'    => $data['message'],
            'ip'         => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);

        // Email admin (from Site Settings)
        $adminEmail = Settings::get('email', null);

        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new ContactMessageReceived($msg));
            } catch (\Throwable $e) {
                // Don’t break UX if mail fails; still saved in DB.
                report($e);
            }
        }

        return redirect()
            ->route('contact')
            ->with('success', 'Message sent! Thank you — we’ll get back to you soon.');
    }
}

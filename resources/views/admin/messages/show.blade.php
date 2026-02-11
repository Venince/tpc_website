@extends('layouts.site')

@section('title', 'Admin - Message')

@section('content')
<section class="bg-transparent">
    <div class="max-w-4xl mx-auto px-4 py-10">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('admin.messages.index') }}"
               class="text-sm font-medium text-tpc-primary hover:text-tpc-secondary">
                ← Back to Inbox
            </a>

            <div class="flex items-center gap-2">
                {{-- Always render both, JS will toggle instantly --}}
                <form
                    id="mark-read-form"
                    data-ajax="true"
                    method="POST"
                    action="{{ route('admin.messages.read', $message) }}"
                    class="{{ $message->is_read ? 'hidden' : '' }}"
                >
                    @csrf @method('PATCH')
                    <button class="rounded-lg bg-tpc-primary px-3 py-2 text-sm font-medium text-white hover:bg-tpc-secondary">
                        Mark Read
                    </button>
                </form>

                <form
                    id="mark-unread-form"
                    data-ajax="true"
                    method="POST"
                    action="{{ route('admin.messages.unread', $message) }}"
                    class="{{ $message->is_read ? '' : 'hidden' }}"
                >
                    @csrf @method('PATCH')
                    <button class="rounded-lg border border-tpc-primary/30 bg-white px-3 py-2 text-sm font-medium text-tpc-primary hover:bg-tpc-primary/5">
                        Mark Unread
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-6 rounded-2xl border border-tpc-primary/15 bg-white/80 p-6 shadow-sm backdrop-blur">
            <h1 class="text-2xl font-semibold text-tpc-ink">{{ $message->subject }}</h1>

            <div class="mt-3 flex flex-wrap gap-3 text-sm text-tpc-ink/70">
                <span><span class="font-medium text-tpc-ink">From:</span> {{ $message->name }} ({{ $message->email }})</span>
                <span>•</span>
                <span>{{ $message->created_at->format('M d, Y h:i A') }}</span>
            </div>

            <div class="mt-6 whitespace-pre-wrap rounded-xl border border-tpc-primary/10 bg-white p-5 text-sm text-tpc-ink/80">
                {{ $message->message }}
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                @php
                    $to = $message->email;
                    $subject = 'Re: ' . $message->subject;

                    $sentAt = $message->created_at?->format('M d, Y h:i A') ?? '';
                    $original = trim((string) $message->message);

                    // Gmail-friendly quote style
                    $body =
                        "Hi {$message->name},\n\n" .
                        "(Write your reply above.)\n\n" .
                        "----- Original Message -----\n" .
                        "From: {$message->name} <{$message->email}>\n" .
                        ($sentAt ? "Date: {$sentAt}\n" : "") .
                        "Subject: {$message->subject}\n\n" .
                        "> " . str_replace("\n", "\n> ", $original) . "\n";
                @endphp

                <a
                href="https://mail.google.com/mail/?view=cm&fs=1&to={{ urlencode($to) }}&su={{ urlencode($subject) }}&body={{ urlencode($body) }}"
                target="_blank"
                rel="noopener"
                class="inline-flex items-center justify-center rounded-lg bg-tpc-primary px-5 py-3 text-sm font-medium text-white hover:bg-tpc-secondary"
                >
                Reply via Gmail
                </a>

                {{-- <a href="{{ route('admin.messages.index') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-tpc-primary/30 bg-white px-5 py-3 text-sm font-medium text-tpc-primary hover:bg-tpc-primary/5">
                    Back to Inbox
                </a> --}}
            </div>
        </div>
    </div>
</section>
@endsection

@extends('admin.layout', ['title' => 'Message'])

@section('content')

    {{-- Back link + header --}}
    <div class="mb-5">
        <a href="{{ route('admin.messages.index') }}"
           class="inline-flex items-center gap-1.5 text-[11px] font-bold text-neo-ink/40 hover:text-tpc-primary uppercase tracking-wide transition mb-4">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Inbox
        </a>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0">
                <span class="inline-block {{ $message->is_read ? 'bg-neo-bg text-neo-ink/40' : 'bg-tpc-primary text-white' }} text-[9px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full mb-2.5">
                    {{ $message->is_read ? 'Read' : 'Unread' }}
                </span>
                <h2 class="text-lg sm:text-xl font-semibold text-neo-ink leading-tight">
                    {{ $message->subject }}
                </h2>
                <div class="mt-2 flex flex-wrap items-center gap-2.5 text-xs text-neo-ink/45">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $message->name }}
                    </span>
                    <span>·</span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $message->email }}
                    </span>
                    <span>·</span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                        {{ $message->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}
                    </span>
                </div>
            </div>

            {{-- Mark Read/Unread --}}
            <div class="flex items-center gap-2 shrink-0">
                <form id="mark-read-form" data-ajax="true" method="POST"
                      action="{{ route('admin.messages.read', $message) }}"
                      class="{{ $message->is_read ? 'hidden' : '' }}">
                    @csrf @method('PATCH')
                    <button class="rounded-xl bg-tpc-primary px-4 py-2 text-xs font-semibold text-white shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                        Mark Read
                    </button>
                </form>
                <form id="mark-unread-form" data-ajax="true" method="POST"
                      action="{{ route('admin.messages.unread', $message) }}"
                      class="{{ $message->is_read ? '' : 'hidden' }}">
                    @csrf @method('PATCH')
                    <button class="rounded-xl bg-neo-surface shadow-neo-sm px-4 py-2 text-xs font-semibold text-neo-ink/60 hover:shadow-neo-hover transition">
                        Mark Unread
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Message card --}}
    <div class="rounded-2xl bg-neo-bg shadow-neo-inset-sm overflow-hidden">

        {{-- Sender info --}}
        <div class="px-5 py-4 border-b border-black/[0.06] flex items-center gap-4">
            <div>
                <p class="font-semibold text-neo-ink text-sm">{{ $message->name }}</p>
                <p class="text-xs text-neo-ink/45">{{ $message->email }}</p>
            </div>
            <div class="ml-auto text-right">
                <p class="text-[11px] font-semibold text-neo-ink/50">{{ $message->created_at->format('M d, Y') }}</p>
                <p class="text-[10px] text-neo-ink/35">{{ $message->created_at->format('h:i A') }}</p>
            </div>
        </div>

        {{-- Message body --}}
        <div class="px-5 py-6">
            <div class="whitespace-pre-wrap text-sm text-neo-ink/80 leading-relaxed">{{ $message->message }}</div>
        </div>

        {{-- Actions --}}
        <div class="px-5 py-4 border-t border-black/[0.06]">
            @php
                $to      = $message->email;
                $subject = 'Re: ' . $message->subject;
                $sentAt  = $message->created_at?->format('M d, Y h:i A') ?? '';
                $original = trim((string) $message->message);
                $body =
                    "Hi {$message->name},\n\n" .
                    "(Write your reply above.)\n\n" .
                    "----- Original Message -----\n" .
                    "From: {$message->name} <{$message->email}>\n" .
                    ($sentAt ? "Date: {$sentAt}\n" : "") .
                    "Subject: {$message->subject}\n\n" .
                    "> " . str_replace("\n", "\n> ", $original) . "\n";
            @endphp

            <div class="flex flex-wrap gap-2.5">
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ urlencode($to) }}&su={{ urlencode($subject) }}&body={{ urlencode($body) }}"
                   target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 rounded-xl bg-tpc-primary px-4 py-2 text-xs font-semibold text-white shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Reply via Gmail
                </a>
                <a href="{{ route('admin.messages.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-neo-surface shadow-neo-sm px-4 py-2 text-xs font-semibold text-neo-ink/60 hover:shadow-neo-hover transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Inbox
                </a>
            </div>
        </div>
    </div>

@endsection

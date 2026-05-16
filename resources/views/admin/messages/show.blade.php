@extends('layouts.site')

@section('title', 'Admin - Message')

@section('content')

    {{-- PAGE HEADER --}}
    <section class="relative overflow-hidden bg-tpc-primary">
        <div class="max-w-4xl mx-auto px-4 py-10">
            <a href="{{ route('admin.messages.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-white/70 hover:text-white uppercase tracking-wide transition mb-6">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Inbox
            </a>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <span class="inline-block bg-white/20 text-white text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full backdrop-blur-sm mb-3">
                        {{ $message->is_read ? 'Read' : 'Unread' }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white leading-tight">
                        {{ $message->subject }}
                    </h1>
                    <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-white/60">
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
                        <button class="rounded-full border-2 border-white bg-white px-4 py-2 text-sm font-bold text-tpc-primary hover:bg-tpc-accent hover:border-tpc-accent transition">
                            Mark Read
                        </button>
                    </form>
                    <form id="mark-unread-form" data-ajax="true" method="POST"
                          action="{{ route('admin.messages.unread', $message) }}"
                          class="{{ $message->is_read ? '' : 'hidden' }}">
                        @csrf @method('PATCH')
                        <button class="rounded-full border-2 border-white/60 px-4 py-2 text-sm font-bold text-white hover:bg-white hover:text-tpc-primary transition">
                            Mark Unread
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-8 sm:h-10">
                <path d="M0 40 C360 0 1080 0 1440 40 L1440 40 L0 40 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="bg-gray-50 min-h-[60vh]">
        <div class="max-w-4xl mx-auto px-4 py-14">

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="h-1.5 bg-tpc-primary"></div>

                {{-- Sender info --}}
                <div class="px-7 py-5 border-b border-gray-100 flex items-center gap-4">
                    <span class="shrink-0 flex h-12 w-12 items-center justify-center rounded-full bg-tpc-primary/10 text-tpc-primary text-lg font-bold">
                        {{ strtoupper(substr($message->name, 0, 1)) }}
                    </span>
                    <div>
                        <p class="font-bold text-gray-800">{{ $message->name }}</p>
                        <p class="text-sm text-gray-500">{{ $message->email }}</p>
                    </div>
                    <div class="ml-auto text-right">
                        <p class="text-xs font-semibold text-gray-500">{{ $message->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-400">{{ $message->created_at->format('h:i A') }}</p>
                    </div>
                </div>

                {{-- Message body --}}
                <div class="px-7 py-7">
                    <div class="whitespace-pre-wrap text-sm text-gray-700 leading-relaxed">{{ $message->message }}</div>
                </div>

                {{-- Actions --}}
                <div class="px-7 py-5 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
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

                    <div class="flex flex-wrap gap-3">
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ urlencode($to) }}&su={{ urlencode($subject) }}&body={{ urlencode($body) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 rounded-full bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Reply via Gmail
                        </a>
                        <a href="{{ route('admin.messages.index') }}"
                           class="inline-flex items-center gap-2 rounded-full border-2 border-gray-200 px-5 py-2.5 text-sm font-bold text-gray-500 hover:border-tpc-primary hover:text-tpc-primary transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back to Inbox
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

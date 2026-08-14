@extends('admin.layout')

@section('title', 'Feedback')

@section('page_actions')
    <div class="flex flex-col gap-4">
        <a href="{{ route('admin.feedback.index') }}"
           class="inline-flex items-center gap-1.5 self-start text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Feedback
        </a>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="inline-block bg-neo-bg shadow-neo-inset-sm text-neo-ink/50 text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full">
                        {{ $feedback->is_read ? 'Read' : 'Unread' }}
                    </span>
                    @if($feedback->category)
                        <span class="inline-block bg-neo-bg shadow-neo-inset-sm text-tpc-primary text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full">
                            {{ $feedback->categoryLabel() }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-1 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="h-6 w-6 {{ $i <= $feedback->rating ? 'text-amber-400' : 'text-neo-ink/15' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/>
                        </svg>
                    @endfor
                    <span class="ml-1 text-lg font-bold text-neo-ink">{{ $feedback->rating }}/5</span>
                </div>

                <div class="mt-1 flex flex-wrap items-center gap-3 text-sm text-neo-ink/50">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $feedback->name ?: 'Anonymous' }}
                    </span>
                    @if($feedback->email)
                        <span>·</span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $feedback->email }}
                        </span>
                    @endif
                    <span>·</span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                        {{ $feedback->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <form id="mark-read-form" data-ajax="true" method="POST"
                      action="{{ route('admin.feedback.read', $feedback) }}"
                      class="{{ $feedback->is_read ? 'hidden' : '' }}">
                    @csrf @method('PATCH')
                    <button class="rounded-full bg-tpc-primary px-4 py-2 text-sm font-semibold text-white shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                        Mark Read
                    </button>
                </form>
                <form id="mark-unread-form" data-ajax="true" method="POST"
                      action="{{ route('admin.feedback.unread', $feedback) }}"
                      class="{{ $feedback->is_read ? '' : 'hidden' }}">
                    @csrf @method('PATCH')
                    <button class="rounded-full bg-neo-surface shadow-neo-sm px-4 py-2 text-sm font-semibold text-neo-ink/60 transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                        Mark Unread
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')

    {{-- Comment --}}
    <div class="rounded-2xl bg-neo-surface shadow-neo overflow-hidden mb-6">
        <div class="h-1.5 bg-tpc-primary"></div>

        <div class="px-7 py-5 border-b border-black/[0.06] flex items-center gap-4">
            <span class="shrink-0 flex h-12 w-12 items-center justify-center rounded-full bg-neo-bg shadow-neo-inset-sm text-tpc-primary text-lg font-bold">
                {{ $feedback->name ? strtoupper(substr($feedback->name, 0, 1)) : '?' }}
            </span>
            <div>
                <p class="font-semibold text-neo-ink">{{ $feedback->name ?: 'Anonymous' }}</p>
                <p class="text-sm text-neo-ink/50">{{ $feedback->email ?: 'No email provided' }}</p>
            </div>
            <div class="ml-auto text-right">
                <p class="text-xs font-semibold text-neo-ink/50">{{ $feedback->created_at->format('M d, Y') }}</p>
                <p class="text-xs text-neo-ink/35">{{ $feedback->created_at->format('h:i A') }}</p>
            </div>
        </div>

        <div class="px-7 py-7">
            @if($feedback->message)
                <div class="whitespace-pre-wrap text-sm text-neo-ink/80 leading-relaxed">{{ $feedback->message }}</div>
            @else
                <p class="text-sm text-neo-ink/35 italic">No comment was left with this rating.</p>
            @endif
        </div>

        @if($feedback->page_url)
            <div class="px-7 pb-5">
                <p class="text-[10px] font-bold text-neo-ink/40 uppercase tracking-wide mb-1">Submitted from</p>
                <p class="text-xs text-neo-ink/50 break-all">{{ $feedback->page_url }}</p>
            </div>
        @endif

        @if($feedback->email)
            <div class="px-7 py-5 border-t border-black/[0.06] bg-neo-bg/40">
                @php
                    $to = $feedback->email;
                    $subject = 'Re: Your feedback to TPC';
                    $sentAt  = $feedback->created_at?->format('M d, Y h:i A') ?? '';
                    $original = trim((string) $feedback->message);
                    $body =
                        "Hi " . ($feedback->name ?: 'there') . ",\n\n" .
                        "(Write your reply above.)\n\n" .
                        "----- Original Feedback -----\n" .
                        "Rating: {$feedback->rating}/5\n" .
                        ($sentAt ? "Date: {$sentAt}\n" : "") .
                        ($original ? "\n> " . str_replace("\n", "\n> ", $original) . "\n" : "");
                @endphp
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ urlencode($to) }}&su={{ urlencode($subject) }}&body={{ urlencode($body) }}"
                   target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 rounded-full bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Reply via Gmail
                </a>
            </div>
        @endif
    </div>

    {{-- Internal response / notes --}}
    <div class="rounded-2xl bg-neo-surface shadow-neo overflow-hidden">
        <div class="px-7 py-5 border-b border-black/[0.06] flex items-center justify-between">
            <p class="text-xs font-bold text-tpc-primary uppercase tracking-widest">Internal Response / Notes</p>
            @if($feedback->responded_at)
                <span class="text-[11px] text-neo-ink/40">
                    Last updated {{ $feedback->responded_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}
                </span>
            @endif
        </div>
        <form method="POST" action="{{ route('admin.feedback.respond', $feedback) }}" class="p-7">
            @csrf
            <textarea name="admin_response" rows="4" placeholder="Log how this was handled, or draft a response here (visible to admins only)..."
                class="w-full rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-3 py-2.5 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition resize-none">{{ old('admin_response', $feedback->admin_response) }}</textarea>
            <div class="mt-4 flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-full bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                    Save Note
                </button>
            </div>
        </form>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.feedback.index') }}"
           class="inline-flex items-center gap-2 rounded-full bg-neo-surface shadow-neo-sm px-5 py-2.5 text-sm font-semibold text-neo-ink/60 transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Feedback
        </a>
    </div>

@endsection

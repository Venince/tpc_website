@extends('admin.layout', ['title' => 'Review Post'])

@section('content')

    <div class="flex items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl font-semibold text-tpc-ink">Review Post</h1>
            <p class="mt-1 text-sm text-tpc-ink/70">Preview the post and take action.</p>
        </div>
        <a href="{{ route('admin.news-review.index') }}"
           class="text-sm font-medium text-tpc-primary hover:text-tpc-secondary">← Back to queue</a>
    </div>

    {{-- Current status pill --}}
    <div class="mt-4">
        @include('admin.news-posts._status-badge', ['post' => $newsPost])
        @if($newsPost->reviewed_at)
            <span class="ml-2 text-xs text-tpc-ink/50">
                Reviewed {{ $newsPost->reviewed_at->format('M d, Y g:i A') }}
                @if($newsPost->reviewer) by {{ $newsPost->reviewer->name }} @endif
            </span>
        @endif
    </div>

    {{-- ── Two-column layout: preview left, actions right ── --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_320px]">

        {{-- Post preview --}}
        <div class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm overflow-hidden">
            @if($newsPost->image_path)
                <img src="{{ asset('storage/' . $newsPost->image_path) }}"
                     alt="{{ $newsPost->title }}"
                     class="w-full max-h-72 object-cover border-b border-tpc-primary/10" />
            @endif

            <div class="p-6">
                {{-- Meta --}}
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1 bg-tpc-primary text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                        {{ $newsPost->category }}
                    </span>
                    <span class="text-xs text-tpc-ink/50">Submitted {{ $newsPost->created_at->format('F d, Y') }}</span>
                </div>

                <h2 class="text-xl font-bold text-tpc-ink leading-snug mb-2">{{ $newsPost->title }}</h2>

                @if($newsPost->excerpt)
                    <p class="text-sm text-tpc-ink/60 mb-4 italic">{{ $newsPost->excerpt }}</p>
                @endif

                <div class="prose prose-sm max-w-none text-tpc-ink/80 leading-relaxed border-t border-tpc-primary/10 pt-4">
                    {!! nl2br(e($newsPost->body)) !!}
                </div>
            </div>
        </div>

        {{-- Actions panel --}}
        <div class="space-y-4">

            {{-- Approve --}}
            @if(!$newsPost->isApproved())
                <div class="rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
                    <h3 class="text-sm font-semibold text-tpc-ink mb-1">Approve &amp; Publish</h3>
                    <p class="text-xs text-tpc-ink/60 mb-4">
                        This post will be immediately visible to the public.
                    </p>
                    <form method="POST" action="{{ route('admin.news-review.approve', $newsPost) }}"
                          onsubmit="return confirm('Approve and publish this post?');">
                        @csrf
                        <button class="w-full rounded-xl bg-tpc-primary px-4 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary transition">
                            ✓ Approve &amp; Publish
                        </button>
                    </form>
                </div>
            @endif

            {{-- Decline --}}
            @if(!$newsPost->isDeclined())
                <div class="rounded-2xl border border-red-100 bg-white p-5 shadow-sm">
                    <h3 class="text-sm font-semibold text-tpc-ink mb-1">Decline</h3>
                    <p class="text-xs text-tpc-ink/60 mb-3">
                        Optionally add a note so the admin knows what to fix.
                    </p>
                    <form method="POST" action="{{ route('admin.news-review.decline', $newsPost) }}"
                          onsubmit="return confirm('Decline this post?');">
                        @csrf
                        <textarea
                            name="review_note"
                            rows="3"
                            placeholder="Reason for declining (optional)…"
                            class="w-full rounded-lg border border-red-200 px-3 py-2 text-sm focus:border-red-400 focus:ring-red-200 focus:outline-none resize-none mb-3"
                        >{{ old('review_note', $newsPost->review_note) }}</textarea>
                        @error('review_note') <p class="mb-2 text-xs text-red-600">{{ $message }}</p> @enderror
                        <button class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-red-700 transition">
                            ✕ Decline
                        </button>
                    </form>
                </div>
            @endif

            {{-- Reset to pending --}}
            @if(!$newsPost->isPending())
                <div class="rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
                    <h3 class="text-sm font-semibold text-tpc-ink mb-1">Reset to Pending</h3>
                    <p class="text-xs text-tpc-ink/60 mb-4">
                        Move this post back to the review queue without a decision.
                    </p>
                    <form method="POST" action="{{ route('admin.news-review.pending', $newsPost) }}"
                          onsubmit="return confirm('Reset this post to pending?');">
                        @csrf
                        <button class="w-full rounded-xl border border-yellow-300 bg-yellow-50 px-4 py-2.5 text-sm font-bold text-yellow-800 hover:bg-yellow-100 transition">
                            ↺ Reset to Pending
                        </button>
                    </form>
                </div>
            @endif

            {{-- Post metadata --}}
            <div class="rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm text-xs text-tpc-ink/60 space-y-1">
                <div><span class="font-medium text-tpc-ink/80">Slug:</span> {{ $newsPost->slug }}</div>
                <div><span class="font-medium text-tpc-ink/80">Created:</span> {{ $newsPost->created_at->format('M d, Y g:i A') }}</div>
                <div><span class="font-medium text-tpc-ink/80">Last updated:</span> {{ $newsPost->updated_at->format('M d, Y g:i A') }}</div>
                @if($newsPost->published_at)
                    <div><span class="font-medium text-tpc-ink/80">Published:</span> {{ $newsPost->published_at->format('M d, Y g:i A') }}</div>
                @endif
            </div>
        </div>

    </div>

@endsection

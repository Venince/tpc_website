@extends('admin.layout', ['title' => 'Review Post'])

@section('content')

    {{-- ── Page header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <a href="{{ route('admin.news-review.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition mb-2">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Review Queue
            </a>
            <h1 class="text-lg font-bold text-tpc-ink">Review Post</h1>
        </div>
        <div class="flex items-center gap-2 self-start sm:self-auto">
            @include('admin.news-posts._status-badge', ['post' => $newsPost])
            @if($newsPost->reviewed_at)
                <span class="text-xs text-tpc-ink/40">
                    {{ $newsPost->reviewed_at->format('M d, Y') }}
                    @if($newsPost->reviewer) · {{ $newsPost->reviewer->name }} @endif
                </span>
            @endif
        </div>
    </div>

    {{-- ── Two-column layout ── --}}
    <div class="flex flex-col lg:flex-row gap-5 items-start">

        {{-- ── Post preview (left, scrollable) ── --}}
        <div class="w-full lg:flex-1 min-w-0 rounded-2xl border border-tpc-primary/10 bg-white shadow-sm overflow-hidden">

            {{-- Image: full width, natural height, no crop --}}
            @if($newsPost->image_path)
                <div class="w-full bg-gray-50 border-b border-tpc-primary/10">
                    <img src="{{ asset('storage/' . $newsPost->image_path) }}"
                         alt="{{ $newsPost->title }}"
                         class="w-full h-auto block" />
                </div>
            @endif

            <div class="p-5 sm:p-6">
                {{-- Category + date --}}
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="inline-flex items-center rounded-lg bg-tpc-primary px-2.5 py-1 text-xs font-bold text-white uppercase tracking-wider">
                        {{ $newsPost->category }}
                    </span>
                    <span class="text-xs text-tpc-ink/40">Submitted {{ $newsPost->created_at->format('F d, Y') }}</span>
                </div>

                {{-- Title --}}
                <h2 class="text-xl font-bold text-tpc-ink leading-snug mb-2">{{ $newsPost->title }}</h2>

                {{-- Excerpt --}}
                @if($newsPost->excerpt)
                    <p class="text-sm text-tpc-ink/60 mb-4 italic border-l-2 border-tpc-primary/20 pl-3">{{ $newsPost->excerpt }}</p>
                @endif

                {{-- Body --}}
                <div class="prose prose-sm max-w-none text-tpc-ink/80 leading-relaxed border-t border-tpc-primary/8 pt-4">
                    {!! nl2br(e($newsPost->body)) !!}
                </div>
            </div>
        </div>

        {{-- ── Actions panel (right, sticky on desktop) ── --}}
        <div class="w-full lg:w-[300px] shrink-0 space-y-4 lg:sticky lg:top-6">

            {{-- Approve --}}
            @if(!$newsPost->isApproved())
                <div class="rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-tpc-primary/10">
                            <svg class="h-3.5 w-3.5 text-tpc-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <h3 class="text-sm font-bold text-tpc-ink">Approve & Publish</h3>
                    </div>
                    <p class="text-xs text-tpc-ink/50 mb-4 ml-8">This post will be immediately visible to the public.</p>
                    <form method="POST" action="{{ route('admin.news-review.approve', $newsPost) }}"
                          onsubmit="return confirm('Approve and publish this post?');">
                        @csrf
                        <button class="w-full rounded-xl bg-tpc-primary px-4 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary transition focus:outline-none focus:ring-2 focus:ring-tpc-primary/30">
                            Approve & Publish
                        </button>
                    </form>
                </div>
            @endif

            {{-- Decline --}}
            @if(!$newsPost->isDeclined())
                <div class="rounded-2xl border border-red-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-red-100">
                            <svg class="h-3.5 w-3.5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </span>
                        <h3 class="text-sm font-bold text-tpc-ink">Decline</h3>
                    </div>
                    <p class="text-xs text-tpc-ink/50 mb-3 ml-8">Optionally add a note so the admin knows what to fix.</p>
                    <form method="POST" action="{{ route('admin.news-review.decline', $newsPost) }}"
                          onsubmit="return confirm('Decline this post?');">
                        @csrf
                        <textarea name="review_note" rows="3"
                                  placeholder="Reason for declining (optional)…"
                                  class="w-full rounded-xl border border-red-200 px-3 py-2.5 text-sm focus:border-red-400 focus:ring-2 focus:ring-red-200/60 outline-none transition resize-none mb-3 placeholder:text-tpc-ink/30">{{ old('review_note', $newsPost->review_note) }}</textarea>
                        @error('review_note') <p class="mb-2 text-xs text-red-600">{{ $message }}</p> @enderror
                        <button class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-300">
                            Decline Post
                        </button>
                    </form>
                </div>
            @endif

            {{-- Reset to pending --}}
            @if(!$newsPost->isPending())
                <div class="rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-yellow-100">
                            <svg class="h-3.5 w-3.5 text-yellow-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                            </svg>
                        </span>
                        <h3 class="text-sm font-bold text-tpc-ink">Reset to Pending</h3>
                    </div>
                    <p class="text-xs text-tpc-ink/50 mb-4 ml-8">Move this post back to the review queue.</p>
                    <form method="POST" action="{{ route('admin.news-review.pending', $newsPost) }}"
                          onsubmit="return confirm('Reset this post to pending?');">
                        @csrf
                        <button class="w-full rounded-xl border border-yellow-300 bg-yellow-50 px-4 py-2.5 text-sm font-bold text-yellow-800 hover:bg-yellow-100 transition">
                            Reset to Pending
                        </button>
                    </form>
                </div>
            @endif

            {{-- Post metadata --}}
            <div class="rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
                <h3 class="text-xs font-bold uppercase tracking-widest text-tpc-ink/50 mb-3">Post Details</h3>
                <dl class="space-y-2 text-xs">
                    <div class="flex justify-between gap-2">
                        <dt class="font-semibold text-tpc-ink/60">Slug</dt>
                        <dd class="text-tpc-ink/80 font-mono truncate max-w-[160px]">{{ $newsPost->slug }}</dd>
                    </div>
                    <div class="flex justify-between gap-2">
                        <dt class="font-semibold text-tpc-ink/60">Created</dt>
                        <dd class="text-tpc-ink/80">{{ $newsPost->created_at->format('M d, Y g:i A') }}</dd>
                    </div>
                    <div class="flex justify-between gap-2">
                        <dt class="font-semibold text-tpc-ink/60">Updated</dt>
                        <dd class="text-tpc-ink/80">{{ $newsPost->updated_at->format('M d, Y g:i A') }}</dd>
                    </div>
                    @if($newsPost->published_at)
                        <div class="flex justify-between gap-2">
                            <dt class="font-semibold text-tpc-ink/60">Published</dt>
                            <dd class="text-tpc-ink/80">{{ $newsPost->published_at->format('M d, Y g:i A') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

        </div>{{-- end actions panel --}}

    </div>{{-- end two-column --}}

@endsection

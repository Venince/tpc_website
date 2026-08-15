@extends('admin.layout', ['title' => 'Dashboard'])

@section('content')


{{-- ── Stat Cards ─────────────────────────────────────────────────── --}}
<div class="grid gap-3 grid-cols-2 lg:grid-cols-5">

    {{-- Programs --}}
    <a href="{{ route('admin.programs.index') }}"
       class="group relative overflow-hidden rounded-[24px] bg-neo-surface p-4 shadow-neo hover:shadow-neo-hover hover:-translate-y-0.5 transition-all duration-200">
        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-tpc-primary/5 transition-all duration-300 group-hover:scale-150 group-hover:bg-tpc-primary/8"></div>
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0 relative">
                <p class="text-[10px] text-neo-ink/50 font-medium uppercase tracking-wider">Programs</p>
                <p class="mt-1.5 text-2xl font-semibold text-neo-ink leading-none tabular-nums">{{ $programCount }}</p>
                <p class="mt-1 text-[10px] text-neo-ink/50">
                    <span class="font-semibold text-tpc-primary">{{ $activeProgramCount }}</span> active
                </p>
            </div>
            <div class="relative shrink-0 rounded-xl bg-tpc-primary/10 shadow-neo-sm p-2 text-tpc-primary transition-transform duration-200 group-hover:scale-110">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                </svg>
            </div>
        </div>
    </a>

    {{-- News Posts --}}
    <a href="{{ route('admin.news-posts.index') }}"
       class="group relative overflow-hidden rounded-[24px] bg-neo-surface p-4 shadow-neo hover:shadow-neo-hover hover:-translate-y-0.5 transition-all duration-200">
        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-emerald-50 transition-all duration-300 group-hover:scale-150 group-hover:bg-emerald-100/60"></div>
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0 relative">
                <p class="text-[10px] text-neo-ink/50 font-medium uppercase tracking-wider">News Posts</p>
                <p class="mt-1.5 text-2xl font-semibold text-neo-ink leading-none tabular-nums">{{ $newsCount }}</p>
                <p class="mt-1 text-[10px] text-neo-ink/50">
                    <span class="font-semibold text-emerald-600">{{ $publishedNewsCount }}</span> published
                </p>
            </div>
            <div class="relative shrink-0 rounded-xl bg-emerald-50 shadow-neo-sm p-2 text-emerald-500 transition-transform duration-200 group-hover:scale-110">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z"/>
                </svg>
            </div>
        </div>
    </a>

    {{-- Messages --}}
    <a href="{{ route('admin.messages.index') }}"
       class="group relative overflow-hidden rounded-[24px] bg-neo-surface p-4 shadow-neo hover:shadow-neo-hover hover:-translate-y-0.5 transition-all duration-200">
        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-yellow-50 transition-all duration-300 group-hover:scale-150 group-hover:bg-yellow-100/60"></div>
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0 relative">
                <p class="text-[10px] text-neo-ink/50 font-medium uppercase tracking-wider">Messages</p>
                <p class="mt-1.5 text-2xl font-semibold text-neo-ink leading-none tabular-nums">{{ $messageCount }}</p>
                <p class="mt-1 text-[10px] text-neo-ink/50">
                    @if($unreadMessageCount > 0)
                        <span class="font-semibold text-yellow-600">{{ $unreadMessageCount }}</span> unread
                    @else
                        all read
                    @endif
                </p>
            </div>
            <div class="relative shrink-0 rounded-xl bg-yellow-50 shadow-neo-sm p-2 text-yellow-500 transition-transform duration-200 group-hover:scale-110">
                @if($unreadMessageCount > 0)
                    <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-yellow-500 text-[8px] font-bold text-white ring-2 ring-white">
                        {{ $unreadMessageCount > 9 ? '9+' : $unreadMessageCount }}
                    </span>
                @endif
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
            </div>
        </div>
    </a>

    {{-- Feedback --}}
    <a href="{{ route('admin.feedback.index') }}"
       class="group relative overflow-hidden rounded-[24px] bg-neo-surface p-4 shadow-neo hover:shadow-neo-hover hover:-translate-y-0.5 transition-all duration-200">
        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-amber-50 transition-all duration-300 group-hover:scale-150 group-hover:bg-amber-100/60"></div>
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0 relative">
                <p class="text-[10px] text-neo-ink/50 font-medium uppercase tracking-wider">Feedback</p>
                <div class="mt-1.5 flex items-baseline gap-1.5">
                    <p class="text-2xl font-semibold text-neo-ink leading-none tabular-nums">{{ $feedbackCount }}</p>
                    @if($feedbackCount > 0)
                        <span class="flex items-center gap-0.5 text-xs font-semibold text-amber-500">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/>
                            </svg>
                            {{ $avgFeedbackRating }}
                        </span>
                    @endif
                </div>
                <p class="mt-1 text-[10px] text-neo-ink/50">
                    @if($unreadFeedbackCount > 0)
                        <span class="font-semibold text-amber-600">{{ $unreadFeedbackCount }}</span> unread
                    @else
                        all read
                    @endif
                </p>
            </div>
            <div class="relative shrink-0 rounded-xl bg-amber-50 shadow-neo-sm p-2 text-amber-500 transition-transform duration-200 group-hover:scale-110">
                @if($unreadFeedbackCount > 0)
                    <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-amber-500 text-[8px] font-bold text-white ring-2 ring-white">
                        {{ $unreadFeedbackCount > 9 ? '9+' : $unreadFeedbackCount }}
                    </span>
                @endif
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.914c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.539 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.075 9.101c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.95-.69l1.52-4.674z"/>
                </svg>
            </div>
        </div>
    </a>

    {{-- Pending Review --}}
    <a href="{{ route('admin.news-review.index') }}"
       class="group relative overflow-hidden rounded-[24px] bg-neo-surface p-4 shadow-neo hover:shadow-neo-hover hover:-translate-y-0.5 transition-all duration-200 {{ $pendingNewsCount > 0 ? 'shadow-neo-hover' : '' }}">
        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-amber-50 transition-all duration-300 group-hover:scale-150 group-hover:bg-amber-100/60"></div>
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0 relative">
                <p class="text-[10px] text-neo-ink/50 font-medium uppercase tracking-wider">Pending Review</p>
                <p class="mt-1.5 text-2xl font-semibold {{ $pendingNewsCount > 0 ? 'text-amber-600' : 'text-neo-ink' }} leading-none tabular-nums">{{ $pendingNewsCount }}</p>
                <p class="mt-1 text-[10px] text-neo-ink/50">
                    {{ $pendingNewsCount > 0 ? 'awaiting approval' : 'all clear ✓' }}
                </p>
            </div>
            <div class="relative shrink-0 rounded-xl bg-amber-50 shadow-neo-sm p-2 text-amber-500 transition-transform duration-200 group-hover:scale-110 {{ $pendingNewsCount > 0 ? 'animate-pulse' : '' }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <circle cx="12" cy="12" r="10"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                </svg>
            </div>
        </div>
    </a>
</div>

{{-- ── Charts Row ──────────────────────────────────────────────────── --}}
<div class="mt-3 grid gap-3 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">

    {{-- Programs donut --}}
    <div class="rounded-[24px] bg-neo-surface p-4 shadow-neo">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-xs font-semibold text-neo-ink">Programs Breakdown</h2>
            <a href="{{ route('admin.programs.index') }}"
               class="text-[10px] font-medium text-tpc-primary hover:text-tpc-secondary transition-colors">
                Manage →
            </a>
        </div>
        <div class="flex items-center gap-4">
            <div class="relative shrink-0">
                <svg width="80" height="80" viewBox="0 0 96 96" class="-rotate-90">
                    <circle cx="48" cy="48" r="36" fill="none" stroke="#E7ECF2" stroke-width="13"/>
                    <circle
                        cx="48" cy="48" r="36" fill="none"
                        stroke="rgb(0,128,0)"
                        stroke-width="13"
                        stroke-linecap="round"
                        stroke-dasharray="226.2"
                        @php $progPct = $programCount > 0 ? ($activeProgramCount / $programCount) : 0; @endphp
                        stroke-dashoffset="{{ 226.2 - (226.2 * $progPct) }}"
                        style="transition: stroke-dashoffset 1s cubic-bezier(0.22,1,0.36,1)"
                    />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-base font-semibold text-neo-ink leading-none">{{ $programCount > 0 ? round($progPct * 100) : 0 }}%</span>
                    <span class="text-[9px] text-neo-ink/50 mt-0.5">active</span>
                </div>
            </div>
            <div class="space-y-2.5 text-xs flex-1">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-tpc-primary shrink-0"></span>
                    <span class="text-neo-ink/70">Active</span>
                    <span class="ml-auto font-semibold text-neo-ink tabular-nums">{{ $activeProgramCount }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-tpc-primary/15 border border-tpc-primary/20 shrink-0"></span>
                    <span class="text-neo-ink/70">Inactive</span>
                    <span class="ml-auto font-semibold text-neo-ink tabular-nums">{{ $programCount - $activeProgramCount }}</span>
                </div>
                <div class="pt-2 border-t border-black/[0.06] flex items-center gap-2">
                    <span class="text-neo-ink/50 text-[10px]">Total</span>
                    <span class="ml-auto font-semibold text-neo-ink tabular-nums">{{ $programCount }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- News donut --}}
    <div class="rounded-[24px] bg-neo-surface p-4 shadow-neo">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-xs font-semibold text-neo-ink">News by Status</h2>
            <a href="{{ route('admin.news-posts.index') }}"
               class="text-[10px] font-medium text-tpc-primary hover:text-tpc-secondary transition-colors">
                Manage →
            </a>
        </div>
        <div class="flex items-center gap-4">
            <div class="relative shrink-0">
                <svg width="80" height="80" viewBox="0 0 96 96" class="-rotate-90">
                    <circle cx="48" cy="48" r="36" fill="none" stroke="#E7ECF2" stroke-width="13"/>
                    <circle
                        cx="48" cy="48" r="36" fill="none"
                        stroke="#3b82f6"
                        stroke-width="13"
                        stroke-linecap="round"
                        stroke-dasharray="226.2"
                        @php $newsPct = $newsCount > 0 ? ($publishedNewsCount / $newsCount) : 0; @endphp
                        stroke-dashoffset="{{ 226.2 - (226.2 * $newsPct) }}"
                        style="transition: stroke-dashoffset 1s cubic-bezier(0.22,1,0.36,1)"
                    />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-base font-semibold text-neo-ink leading-none">{{ $newsCount > 0 ? round($newsPct * 100) : 0 }}%</span>
                    <span class="text-[9px] text-neo-ink/50 mt-0.5">published</span>
                </div>
            </div>
            <div class="space-y-2.5 text-xs flex-1">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 shrink-0"></span>
                    <span class="text-neo-ink/70">Published</span>
                    <span class="ml-auto font-semibold text-neo-ink tabular-nums">{{ $publishedNewsCount }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-amber-400 shrink-0"></span>
                    <span class="text-neo-ink/70">Pending</span>
                    <span class="ml-auto font-semibold text-neo-ink tabular-nums">{{ $pendingNewsCount }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-neo-ink/15 border border-neo-ink/20 shrink-0"></span>
                    <span class="text-neo-ink/70">Draft</span>
                    <span class="ml-auto font-semibold text-neo-ink tabular-nums">{{ $newsCount - $publishedNewsCount - $pendingNewsCount }}</span>
                </div>
                <div class="pt-2 border-t border-black/[0.06] flex items-center gap-2">
                    <span class="text-neo-ink/50 text-[10px]">Total</span>
                    <span class="ml-auto font-semibold text-neo-ink tabular-nums">{{ $newsCount }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Feedback ratings breakdown --}}
    <div class="rounded-[24px] bg-neo-surface p-4 shadow-neo">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-xs font-semibold text-neo-ink">Ratings Breakdown</h2>
            <a href="{{ route('admin.feedback.index') }}"
               class="text-[10px] font-medium text-tpc-primary hover:text-tpc-secondary transition-colors">
                Manage →
            </a>
        </div>

        @if($feedbackCount > 0)
            <div class="flex items-center gap-4 mb-1">
                <div class="shrink-0 text-center">
                    <p class="text-2xl font-semibold text-neo-ink leading-none tabular-nums">{{ $avgFeedbackRating }}</p>
                    <div class="flex items-center gap-0.5 mt-1.5 justify-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="h-3 w-3 {{ $i <= round($avgFeedbackRating) ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="mt-1 text-[10px] text-neo-ink/40">{{ $feedbackCount }} total</p>
                </div>

                <div class="space-y-1.5 flex-1">
                    @for($r = 5; $r >= 1; $r--)
                        @php
                            $c = $ratingCounts[$r] ?? 0;
                            $pct = $feedbackCount > 0 ? round(($c / $feedbackCount) * 100) : 0;
                        @endphp
                        <div class="flex items-center gap-2 text-[10px]">
                            <span class="w-2.5 text-neo-ink/60 tabular-nums">{{ $r }}</span>
                            <svg class="h-3 w-3 text-amber-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/>
                            </svg>
                            <div class="h-1.5 flex-1 rounded-full bg-neo-bg shadow-neo-inset-sm overflow-hidden">
                                <div class="h-full rounded-full bg-amber-400 transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="w-5 text-right font-semibold text-neo-ink tabular-nums">{{ $c }}</span>
                        </div>
                    @endfor
                </div>
            </div>
        @else
            <div class="flex flex-col items-center gap-2 py-6">
                <div class="rounded-2xl bg-amber-50 p-3 text-amber-400/70">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.914c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.539 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.075 9.101c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.95-.69l1.52-4.674z"/>
                    </svg>
                </div>
                <p class="text-xs text-neo-ink/40">No feedback yet.</p>
            </div>
        @endif
    </div>
</div>

{{-- ── Bottom Row ──────────────────────────────────────────────────── --}}
<div class="mt-3 grid gap-3 grid-cols-1 lg:grid-cols-3">

    {{-- Recent news (2/3 width) --}}
    <div class="lg:col-span-2 rounded-[24px] bg-neo-surface p-4 shadow-neo">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-xs font-semibold text-neo-ink">Recent News Posts</h2>
            <a href="{{ route('admin.news-posts.create') }}"
               class="inline-flex items-center gap-1 rounded-xl bg-neo-surface shadow-neo-sm px-2.5 py-1 text-[10px] font-semibold text-tpc-primary hover:shadow-neo-hover transition-all">
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                </svg>
                New post
            </a>
        </div>

        <div class="divide-y divide-black/[0.05]">
            @forelse ($recentNews as $post)
                <div class="group flex items-center gap-2 py-2.5">
                    {{-- Status indicator --}}
                    <div class="shrink-0">
                        @if($post->is_published)
                            <span class="flex h-2 w-2 rounded-full bg-tpc-primary"></span>
                        @elseif(isset($post->status) && $post->status === 'pending')
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-400"></span>
                            </span>
                        @else
                            <span class="flex h-2 w-2 rounded-full bg-neo-ink/20"></span>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs font-medium text-neo-ink group-hover:text-tpc-primary transition-colors">{{ $post->title }}</p>
                        <p class="text-[10px] text-neo-ink/50 mt-0.5">
                            {{ $post->category }}
                            <span class="mx-1 opacity-50">·</span>
                            {{ $post->created_at->diffForHumans() }}
                        </p>
                    </div>

                    @if($post->is_published)
                        <span class="shrink-0 rounded-full bg-tpc-primary/10 px-2 py-0.5 text-[10px] font-medium text-tpc-primary ring-1 ring-tpc-primary/15">
                            Published
                        </span>
                    @elseif(isset($post->status) && $post->status === 'pending')
                        <span class="shrink-0 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-medium text-amber-700 ring-1 ring-amber-200/60">
                            Pending
                        </span>
                    @else
                        <span class="shrink-0 rounded-full bg-neo-ink/8 px-2 py-0.5 text-[10px] font-medium text-neo-ink/50">
                            Draft
                        </span>
                    @endif

                    <a href="{{ route('admin.news-posts.edit', $post) }}"
                       class="shrink-0 rounded-lg bg-neo-surface shadow-neo-sm px-2 py-0.5 text-[10px] font-medium text-tpc-primary opacity-0 group-hover:opacity-100 transition-all hover:shadow-neo-hover touch-manipulation">
                        Edit
                    </a>
                </div>
            @empty
                <div class="flex flex-col items-center gap-2 py-8">
                    <div class="rounded-2xl bg-tpc-primary/8 p-3 text-tpc-primary/50">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z"/>
                        </svg>
                    </div>
                    <p class="text-xs text-neo-ink/40">No news posts yet.</p>
                    <a href="{{ route('admin.news-posts.create') }}"
                       class="mt-1 rounded-xl bg-tpc-primary px-3 py-1.5 text-xs font-semibold text-white hover:bg-tpc-primary/90 transition">
                        Create first post
                    </a>
                </div>
            @endforelse
        </div>

        @if($recentNews->count() > 0)
            <div class="mt-3 pt-3 border-t border-black/[0.06]">
                <a href="{{ route('admin.news-posts.index') }}"
                   class="flex items-center justify-center gap-1.5 rounded-xl py-2 text-xs font-medium text-neo-ink/50 hover:bg-tpc-primary/5 hover:text-tpc-primary transition-colors">
                    View all {{ $newsCount }} posts
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>

    {{-- Sidebar panel: Content overview + Quick actions --}}
    <div class="space-y-3">

        {{-- Activity overview line chart --}}
        <div class="rounded-[24px] bg-neo-surface p-4 shadow-neo">
            <div class="flex items-center justify-between mb-1">
                <h2 class="text-xs font-semibold text-neo-ink">Activity Overview</h2>
                <span class="text-[10px] text-neo-ink/40">Last 14 days</span>
            </div>

            {{-- Legend --}}
            <div class="flex items-center gap-3 mb-3">
                <span class="inline-flex items-center gap-1.5 text-[10px] text-neo-ink/55">
                    <span class="h-1.5 w-1.5 rounded-full" style="background:#eab308"></span> Messages
                </span>
                <span class="inline-flex items-center gap-1.5 text-[10px] text-neo-ink/55">
                    <span class="h-1.5 w-1.5 rounded-full" style="background:#fbbf24"></span> Feedback
                </span>
                <span class="inline-flex items-center gap-1.5 text-[10px] text-neo-ink/55">
                    <span class="h-1.5 w-1.5 rounded-full bg-tpc-primary"></span> News
                </span>
            </div>

            <div class="relative rounded-xl bg-neo-bg shadow-neo-inset-sm p-3">
                <canvas id="activityChart" height="150"></canvas>

                {{-- Auto-cycling day card: fades out, moves, fades in with the next day's numbers --}}
                <div id="activityCard"
                     class="absolute z-10 pointer-events-none opacity-0 transition-opacity duration-300 ease-in-out">
                    <div class="rounded-lg bg-neo-surface shadow-neo px-3 py-2 min-w-[128px]">
                        <p id="activityCardDate" class="text-[11px] font-bold text-neo-ink mb-1"></p>
                        <div class="space-y-1 text-[10px] text-neo-ink/70">
                            <p class="flex items-center gap-1.5">
                                <span class="inline-block h-2.5 w-2.5 rounded-[2px] border-2 shrink-0" style="border-color:#eab308"></span>
                                Messages: <span id="activityCardMessages" class="font-semibold text-neo-ink"></span>
                            </p>
                            <p class="flex items-center gap-1.5">
                                <span class="inline-block h-2.5 w-2.5 rounded-[2px] border-2 shrink-0" style="border-color:#fbbf24"></span>
                                Feedback: <span id="activityCardFeedback" class="font-semibold text-neo-ink"></span>
                            </p>
                            <p class="flex items-center gap-1.5">
                                <span class="inline-block h-2.5 w-2.5 rounded-[2px] shrink-0" style="background:#008000"></span>
                                News: <span id="activityCardNews" class="font-semibold text-neo-ink"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
        <script>
        (function () {
            let chart      = null;
            let cycleTimer = null;

            function whenChartReady(callback, attempts) {
                attempts = attempts || 0;
                if (typeof Chart !== 'undefined') {
                    callback();
                    return;
                }
                if (attempts >= 50) {
                    console.error('[Activity Overview] Chart.js failed to load after 5s — check network/ad-blocker/CDN access.');
                    return;
                }
                setTimeout(function () { whenChartReady(callback, attempts + 1); }, 100);
            }

            function init() {
                whenChartReady(function () {
                    try {
                        buildChart();
                    } catch (err) {
                        console.error('[Activity Overview] failed to initialize:', err);
                    }
                });
            }

            function buildChart() {
                // Always re-query — PJAX swaps innerHTML, so any canvas
                // reference captured earlier points at a detached node.
                const ctx = document.getElementById('activityChart');
                if (!ctx) return;


                // Tear down any previous instance/timer before rebuilding —
                // this can run again on re-navigation/bfcache, so avoid double charts/loops.
                if (chart) { chart.destroy(); chart = null; }
                if (cycleTimer) { clearTimeout(cycleTimer); cycleTimer = null; }

                const labels    = @json($activityChart['labels']);
                const fullDates = @json($activityChart['labels']); // "Aug 6" style, already formatted
                const messages  = @json($activityChart['messages']);
                const feedback  = @json($activityChart['feedback']);
                const news      = @json($activityChart['news']);
                const total     = labels.length;

                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Messages',
                                data: messages,
                                borderColor: '#eab308',
                                backgroundColor: 'rgba(234,179,8,0.10)',
                                tension: 0.35,
                                fill: true,
                                pointRadius: 0,
                                pointHoverRadius: 4,
                                borderWidth: 2,
                            },
                            {
                                label: 'Feedback',
                                data: feedback,
                                borderColor: '#fbbf24',
                                backgroundColor: 'rgba(251,191,36,0.10)',
                                tension: 0.35,
                                fill: true,
                                pointRadius: 0,
                                pointHoverRadius: 4,
                                borderWidth: 2,
                            },
                            {
                                label: 'News',
                                data: news,
                                borderColor: '#008000',
                                backgroundColor: 'rgba(0,128,0,0.10)',
                                tension: 0.35,
                                fill: true,
                                pointRadius: 0,
                                pointHoverRadius: 4,
                                borderWidth: 2,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#F4F7FB',
                                titleColor: '#2B3648',
                                bodyColor: '#2B3648',
                                borderColor: 'rgba(0,0,0,0.06)',
                                borderWidth: 1,
                                padding: 8,
                                boxPadding: 4,
                                titleFont: { size: 11, weight: '600' },
                                bodyFont: { size: 11 },
                            },
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                border: { display: false },
                                ticks: { color: 'rgba(43,54,72,0.4)', font: { size: 9 }, maxRotation: 0, autoSkip: true, maxTicksLimit: 7 },
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.05)' },
                                border: { display: false },
                                ticks: { color: 'rgba(43,54,72,0.4)', font: { size: 9 }, precision: 0 },
                            },
                        },
                    },
                });

                // ── Auto-cycling day card ──────────────────────────────────────
                const wrap   = ctx.closest('.relative');
                const card   = document.getElementById('activityCard');
                const dateEl = document.getElementById('activityCardDate');
                const msgEl  = document.getElementById('activityCardMessages');
                const fbEl   = document.getElementById('activityCardFeedback');
                const newsEl = document.getElementById('activityCardNews');

                if (!wrap || !card || !dateEl || !msgEl || !fbEl || !newsEl) {
                    return; // chart itself is fine; card overlay elements missing, skip it silently
                }

                // Reset any leftover fade state from a previous run.
                card.classList.add('opacity-0');

                const FADE_MS = 600;
                const HOLD_MS = 1400;

                function positionCard(i) {
                    const meta  = chart.getDatasetMeta(0);
                    const point = meta.data[i];
                    if (!point) return;

                    const yValue = Math.max(messages[i] ?? 0, feedback[i] ?? 0, news[i] ?? 0, 1);
                    const yPixel = chart.scales.y.getPixelForValue(yValue);

                    const canvasRect = ctx.getBoundingClientRect();
                    const wrapRect   = wrap.getBoundingClientRect();
                    const offsetX    = canvasRect.left - wrapRect.left;
                    const offsetY    = canvasRect.top  - wrapRect.top;

                    let left = offsetX + point.x - (card.offsetWidth / 2);
                    let top  = offsetY + yPixel - card.offsetHeight - 14;

                    left = Math.max(4, Math.min(left, wrapRect.width - card.offsetWidth - 4));
                    top  = Math.max(4, top);

                    card.style.left = left + 'px';
                    card.style.top  = top + 'px';
                }

                function showDay(i) {
                    dateEl.textContent = fullDates[i];
                    msgEl.textContent  = messages[i] ?? 0;
                    fbEl.textContent   = feedback[i] ?? 0;
                    newsEl.textContent = news[i] ?? 0;
                    positionCard(i);
                    card.classList.remove('opacity-0');
                }

                let idx = 0;

                function cycle() {
                    showDay(idx);

                    cycleTimer = setTimeout(function () {
                        card.classList.add('opacity-0'); // crossfade out
                        idx = (idx + 1) % total;
                        cycleTimer = setTimeout(cycle, FADE_MS); // crossfade in with the next day's data
                    }, HOLD_MS);
                }

                cycleTimer = setTimeout(cycle, 500);
            }

            // Exposed globally so app.js's initPageComponents() can call this
            // again after every PJAX content swap — this script itself lives
            // outside #tpc-admin-main (in @stack('scripts')) and only runs
            // once on a true hard page load, so re-invoking on every swap is
            // what actually makes the chart reappear after in-app navigation.
            window.initActivityChart = init;

            // Initial hard-load run.
            init();
        })();
        </script>
        @endpush

        {{-- Quick actions --}}
        <div class="rounded-[24px] bg-neo-surface p-4 shadow-neo">
            <h2 class="text-xs font-semibold text-neo-ink mb-3">Quick Actions</h2>
            <div class="space-y-1.5">
                <a href="{{ route('admin.news-posts.create') }}"
                   class="flex items-center gap-2.5 rounded-xl bg-neo-surface shadow-neo-sm px-3 py-2.5 text-xs font-medium text-tpc-primary hover:bg-tpc-primary hover:text-white hover:shadow-neo-hover transition-all duration-150 group">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                    </svg>
                    Write a news post
                    <svg class="h-3 w-3 ml-auto opacity-40 group-hover:opacity-100 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
                    </svg>
                </a>

                @if(auth()->check() && auth()->user()->is_super_admin)
                <a href="{{ route('admin.news-review.index') }}"
                   class="flex items-center gap-2.5 rounded-xl bg-neo-surface shadow-neo-sm px-3 py-2.5 text-xs font-medium text-amber-700 hover:bg-amber-500 hover:text-white hover:shadow-neo-hover transition-all duration-150 group">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                    </svg>
                    Review pending
                    @if($pendingNewsCount > 0)
                        <span class="ml-auto shrink-0 rounded-full bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold text-amber-800 group-hover:bg-amber-600 group-hover:text-white transition">
                            {{ $pendingNewsCount }}
                        </span>
                    @else
                        <svg class="h-3 w-3 ml-auto opacity-40 group-hover:opacity-100 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
                        </svg>
                    @endif
                </a>
                @endif

                <a href="{{ route('admin.messages.index') }}"
                   class="flex items-center gap-2.5 rounded-xl bg-neo-surface shadow-neo-sm px-3 py-2.5 text-xs font-medium text-yellow-700 hover:bg-yellow-500 hover:text-white hover:shadow-neo-hover transition-all duration-150 group">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    View messages
                    @if($unreadMessageCount > 0)
                        <span class="ml-auto shrink-0 rounded-full bg-yellow-100 px-1.5 py-0.5 text-[10px] font-bold text-yellow-700 group-hover:bg-yellow-600 group-hover:text-white transition">
                            {{ $unreadMessageCount }}
                        </span>
                    @else
                        <svg class="h-3 w-3 ml-auto opacity-40 group-hover:opacity-100 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
                        </svg>
                    @endif
                </a>

                <a href="{{ route('admin.feedback.index') }}"
                   class="flex items-center gap-2.5 rounded-xl bg-neo-surface shadow-neo-sm px-3 py-2.5 text-xs font-medium text-amber-700 hover:bg-amber-500 hover:text-white hover:shadow-neo-hover transition-all duration-150 group">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.914c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.539 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.075 9.101c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.95-.69l1.52-4.674z"/>
                    </svg>
                    View feedback
                    @if($unreadFeedbackCount > 0)
                        <span class="ml-auto shrink-0 rounded-full bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold text-amber-700 group-hover:bg-amber-600 group-hover:text-white transition">
                            {{ $unreadFeedbackCount }}
                        </span>
                    @else
                        <svg class="h-3 w-3 ml-auto opacity-40 group-hover:opacity-100 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
                        </svg>
                    @endif
                </a>

                <a href="{{ route('admin.programs.create') }}"
                   class="flex items-center gap-2.5 rounded-xl bg-neo-surface shadow-neo-sm px-3 py-2.5 text-xs font-medium text-neo-ink/60 hover:shadow-neo-hover hover:text-neo-ink transition-all duration-150 group">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                    </svg>
                    Add a program
                    <svg class="h-3 w-3 ml-auto opacity-40 group-hover:opacity-100 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
                    </svg>
                </a>
            </div>
        </div>

    </div>
</div>

@endsection

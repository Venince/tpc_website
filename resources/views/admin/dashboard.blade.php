@extends('admin.layout', ['title' => 'Dashboard'])

@section('content')


{{-- ── Stat Cards ─────────────────────────────────────────────────── --}}
<div class="grid gap-3 grid-cols-2 lg:grid-cols-4">

    {{-- Programs --}}
    <a href="{{ route('admin.programs.index') }}"
       class="group relative overflow-hidden rounded-2xl border border-tpc-primary/20 bg-tpc-primary/5 p-4 sm:p-5 shadow-sm
                hover:shadow-md hover:border-tpc-primary/30 hover:-translate-y-0.5 transition-all duration-200">
        <div class="absolute left-0 inset-y-0 w-[3px] rounded-r-full bg-tpc-primary/60"></div>
        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-tpc-primary/5 transition-all duration-300 group-hover:scale-150 group-hover:bg-tpc-primary/8"></div>
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0 relative">
                <p class="text-[10px] sm:text-xs text-tpc-ink/50 font-medium uppercase tracking-wider">Programs</p>
                <p class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-semibold text-tpc-ink leading-none tabular-nums">{{ $programCount }}</p>
                <p class="mt-1 sm:mt-1.5 text-[10px] sm:text-xs text-tpc-ink/50">
                    <span class="font-semibold text-tpc-primary">{{ $activeProgramCount }}</span> active
                </p>
            </div>
            <div class="relative shrink-0 rounded-xl bg-tpc-primary/8 border border-tpc-primary/12 p-2 sm:p-2.5 text-tpc-primary
                        transition-transform duration-200 group-hover:scale-110">
                <svg class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                </svg>
            </div>
        </div>
    </a>

    {{-- News Posts --}}
    <a href="{{ route('admin.news-posts.index') }}"
       class="group relative overflow-hidden rounded-2xl border border-blue-100 bg-white p-4 sm:p-5 shadow-sm
              hover:shadow-md hover:border-blue-200 hover:-translate-y-0.5 transition-all duration-200">
        <div class="absolute left-0 inset-y-0 w-[3px] rounded-r-full bg-blue-500/70"></div>
        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-blue-50 transition-all duration-300 group-hover:scale-150 group-hover:bg-blue-100/60"></div>
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0 relative">
                <p class="text-[10px] sm:text-xs text-tpc-ink/50 font-medium uppercase tracking-wider">News Posts</p>
                <p class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-semibold text-tpc-ink leading-none tabular-nums">{{ $newsCount }}</p>
                <p class="mt-1 sm:mt-1.5 text-[10px] sm:text-xs text-tpc-ink/50">
                    <span class="font-semibold text-blue-600">{{ $publishedNewsCount }}</span> published
                </p>
            </div>
            <div class="relative shrink-0 rounded-xl bg-blue-50 border border-blue-100 p-2 sm:p-2.5 text-blue-500
                        transition-transform duration-200 group-hover:scale-110">
                <svg class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z"/>
                </svg>
            </div>
        </div>
    </a>

    {{-- Messages --}}
    <a href="{{ route('admin.messages.index') }}"
       class="group relative overflow-hidden rounded-2xl border border-pink-100 bg-white p-4 sm:p-5 shadow-sm
              hover:shadow-md hover:border-pink-200 hover:-translate-y-0.5 transition-all duration-200">
        <div class="absolute left-0 inset-y-0 w-[3px] rounded-r-full bg-pink-500/70"></div>
        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-pink-50 transition-all duration-300 group-hover:scale-150 group-hover:bg-pink-100/60"></div>
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0 relative">
                <p class="text-[10px] sm:text-xs text-tpc-ink/50 font-medium uppercase tracking-wider">Messages</p>
                <p class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-semibold text-tpc-ink leading-none tabular-nums">{{ $messageCount }}</p>
                <p class="mt-1 sm:mt-1.5 text-[10px] sm:text-xs text-tpc-ink/50">
                    @if($unreadMessageCount > 0)
                        <span class="font-semibold text-pink-600">{{ $unreadMessageCount }}</span> unread
                    @else
                        all read
                    @endif
                </p>
            </div>
            <div class="relative shrink-0 rounded-xl bg-pink-50 border border-pink-100 p-2 sm:p-2.5 text-pink-500
                        transition-transform duration-200 group-hover:scale-110">
                @if($unreadMessageCount > 0)
                    <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-pink-500 text-[8px] font-bold text-white ring-2 ring-white">
                        {{ $unreadMessageCount > 9 ? '9+' : $unreadMessageCount }}
                    </span>
                @endif
                <svg class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
            </div>
        </div>
    </a>

    {{-- Pending Review --}}
    <a href="{{ route('admin.news-review.index') }}"
       class="group relative overflow-hidden rounded-2xl border border-amber-100 bg-white p-4 sm:p-5 shadow-sm
              hover:shadow-md hover:border-amber-200 hover:-translate-y-0.5 transition-all duration-200
              {{ $pendingNewsCount > 0 ? 'ring-1 ring-amber-200/60' : '' }}">
        <div class="absolute left-0 inset-y-0 w-[3px] rounded-r-full bg-amber-400/80"></div>
        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-amber-50 transition-all duration-300 group-hover:scale-150 group-hover:bg-amber-100/60"></div>
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0 relative">
                <p class="text-[10px] sm:text-xs text-tpc-ink/50 font-medium uppercase tracking-wider">Pending Review</p>
                <p class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-semibold {{ $pendingNewsCount > 0 ? 'text-amber-600' : 'text-tpc-ink' }} leading-none tabular-nums">{{ $pendingNewsCount }}</p>
                <p class="mt-1 sm:mt-1.5 text-[10px] sm:text-xs text-tpc-ink/50">
                    {{ $pendingNewsCount > 0 ? 'awaiting approval' : 'all clear ✓' }}
                </p>
            </div>
            <div class="relative shrink-0 rounded-xl bg-amber-50 border border-amber-100 p-2 sm:p-2.5 text-amber-500
                        transition-transform duration-200 group-hover:scale-110
                        {{ $pendingNewsCount > 0 ? 'animate-pulse' : '' }}">
                <svg class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <circle cx="12" cy="12" r="10"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                </svg>
            </div>
        </div>
    </a>
</div>

{{-- ── Charts Row ──────────────────────────────────────────────────── --}}
<div class="mt-3 sm:mt-4 grid gap-3 sm:gap-4 grid-cols-1 sm:grid-cols-2">

    {{-- Programs donut --}}
    <div class="rounded-2xl border border-tpc-primary/15 bg-white p-4 sm:p-5 shadow-sm ring-1 ring-tpc-primary/5">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <h2 class="text-xs sm:text-sm font-semibold text-tpc-ink">Programs Breakdown</h2>
            <a href="{{ route('admin.programs.index') }}"
               class="text-[10px] sm:text-xs font-medium text-tpc-primary hover:text-tpc-secondary transition-colors">
                Manage →
            </a>
        </div>
        <div class="flex items-center gap-4 sm:gap-6">
            <div class="relative shrink-0">
                <svg width="80" height="80" viewBox="0 0 96 96" class="-rotate-90 sm:w-24 sm:h-24">
                    <circle cx="48" cy="48" r="36" fill="none" stroke="#f0fdf4" stroke-width="13"/>
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
                    <span class="text-base sm:text-lg font-semibold text-tpc-ink leading-none">{{ $programCount > 0 ? round($progPct * 100) : 0 }}%</span>
                    <span class="text-[9px] sm:text-[10px] text-tpc-ink/50 mt-0.5">active</span>
                </div>
            </div>
            <div class="space-y-2.5 sm:space-y-3 text-xs sm:text-sm flex-1">
                <div class="flex items-center gap-2 sm:gap-2.5">
                    <span class="h-2 w-2 sm:h-2.5 sm:w-2.5 rounded-full bg-tpc-primary shrink-0"></span>
                    <span class="text-tpc-ink/70">Active</span>
                    <span class="ml-auto font-semibold text-tpc-ink tabular-nums">{{ $activeProgramCount }}</span>
                </div>
                <div class="flex items-center gap-2 sm:gap-2.5">
                    <span class="h-2 w-2 sm:h-2.5 sm:w-2.5 rounded-full bg-tpc-primary/15 border border-tpc-primary/20 shrink-0"></span>
                    <span class="text-tpc-ink/70">Inactive</span>
                    <span class="ml-auto font-semibold text-tpc-ink tabular-nums">{{ $programCount - $activeProgramCount }}</span>
                </div>
                <div class="pt-2 border-t border-tpc-primary/10 flex items-center gap-2 sm:gap-2.5">
                    <span class="text-tpc-ink/50 text-[10px] sm:text-xs">Total</span>
                    <span class="ml-auto font-semibold text-tpc-ink tabular-nums">{{ $programCount }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- News donut --}}
    <div class="rounded-2xl border border-tpc-primary/10 bg-white p-4 sm:p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <h2 class="text-xs sm:text-sm font-semibold text-tpc-ink">News by Status</h2>
            <a href="{{ route('admin.news-posts.index') }}"
               class="text-[10px] sm:text-xs font-medium text-tpc-primary hover:text-tpc-secondary transition-colors">
                Manage →
            </a>
        </div>
        <div class="flex items-center gap-4 sm:gap-6">
            <div class="relative shrink-0">
                <svg width="80" height="80" viewBox="0 0 96 96" class="-rotate-90 sm:w-24 sm:h-24">
                    <circle cx="48" cy="48" r="36" fill="none" stroke="#eff6ff" stroke-width="13"/>
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
                    <span class="text-base sm:text-lg font-semibold text-tpc-ink leading-none">{{ $newsCount > 0 ? round($newsPct * 100) : 0 }}%</span>
                    <span class="text-[9px] sm:text-[10px] text-tpc-ink/50 mt-0.5">published</span>
                </div>
            </div>
            <div class="space-y-2.5 sm:space-y-3 text-xs sm:text-sm flex-1">
                <div class="flex items-center gap-2 sm:gap-2.5">
                    <span class="h-2 w-2 sm:h-2.5 sm:w-2.5 rounded-full bg-blue-500 shrink-0"></span>
                    <span class="text-tpc-ink/70">Published</span>
                    <span class="ml-auto font-semibold text-tpc-ink tabular-nums">{{ $publishedNewsCount }}</span>
                </div>
                <div class="flex items-center gap-2 sm:gap-2.5">
                    <span class="h-2 w-2 sm:h-2.5 sm:w-2.5 rounded-full bg-amber-400 shrink-0"></span>
                    <span class="text-tpc-ink/70">Pending</span>
                    <span class="ml-auto font-semibold text-tpc-ink tabular-nums">{{ $pendingNewsCount }}</span>
                </div>
                <div class="flex items-center gap-2 sm:gap-2.5">
                    <span class="h-2 w-2 sm:h-2.5 sm:w-2.5 rounded-full bg-tpc-ink/15 border border-tpc-ink/20 shrink-0"></span>
                    <span class="text-tpc-ink/70">Draft</span>
                    <span class="ml-auto font-semibold text-tpc-ink tabular-nums">{{ $newsCount - $publishedNewsCount - $pendingNewsCount }}</span>
                </div>
                <div class="pt-2 border-t border-tpc-primary/10 flex items-center gap-2 sm:gap-2.5">
                    <span class="text-tpc-ink/50 text-[10px] sm:text-xs">Total</span>
                    <span class="ml-auto font-semibold text-tpc-ink tabular-nums">{{ $newsCount }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Bottom Row ──────────────────────────────────────────────────── --}}
<div class="mt-3 sm:mt-4 grid gap-3 sm:gap-4 grid-cols-1 lg:grid-cols-3">

    {{-- Recent news (2/3 width) --}}
    <div class="lg:col-span-2 rounded-2xl border border-tpc-primary/10 bg-white p-4 sm:p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <h2 class="text-xs sm:text-sm font-semibold text-tpc-ink">Recent News Posts</h2>
            <a href="{{ route('admin.news-posts.create') }}"
               class="inline-flex items-center gap-1 rounded-xl border border-tpc-primary/20 bg-tpc-primary/8
                      px-2.5 py-1 text-[10px] sm:text-xs font-semibold text-tpc-primary
                      hover:bg-tpc-primary/12 transition-colors">
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                </svg>
                New post
            </a>
        </div>

        <div class="divide-y divide-tpc-primary/8">
            @forelse ($recentNews as $post)
                <div class="group flex items-center gap-2 sm:gap-3 py-2.5 sm:py-3">
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
                            <span class="flex h-2 w-2 rounded-full bg-tpc-ink/20"></span>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs sm:text-sm font-medium text-tpc-ink group-hover:text-tpc-primary transition-colors">{{ $post->title }}</p>
                        <p class="text-[10px] sm:text-xs text-tpc-ink/50 mt-0.5">
                            {{ $post->category }}
                            <span class="mx-1 opacity-50">·</span>
                            {{ $post->created_at->diffForHumans() }}
                        </p>
                    </div>

                    @if($post->is_published)
                        <span class="shrink-0 rounded-full bg-tpc-primary/10 px-2 sm:px-2.5 py-0.5 text-[10px] sm:text-[11px] font-medium text-tpc-primary ring-1 ring-tpc-primary/15">
                            Published
                        </span>
                    @elseif(isset($post->status) && $post->status === 'pending')
                        <span class="shrink-0 rounded-full bg-amber-100 px-2 sm:px-2.5 py-0.5 text-[10px] sm:text-[11px] font-medium text-amber-700 ring-1 ring-amber-200/60">
                            Pending
                        </span>
                    @else
                        <span class="shrink-0 rounded-full bg-tpc-ink/8 px-2 sm:px-2.5 py-0.5 text-[10px] sm:text-[11px] font-medium text-tpc-ink/50">
                            Draft
                        </span>
                    @endif

                    <a href="{{ route('admin.news-posts.edit', $post) }}"
                       class="shrink-0 rounded-lg border border-tpc-primary/15 bg-white px-2 py-0.5 text-[10px] sm:text-xs
                              font-medium text-tpc-primary opacity-0 group-hover:opacity-100 transition-all
                              hover:bg-tpc-primary/5 touch-manipulation">
                        Edit
                    </a>
                </div>
            @empty
                <div class="flex flex-col items-center gap-2 py-8 sm:py-10">
                    <div class="rounded-2xl bg-tpc-primary/8 p-3 text-tpc-primary/50">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z"/>
                        </svg>
                    </div>
                    <p class="text-xs sm:text-sm text-tpc-ink/40">No news posts yet.</p>
                    <a href="{{ route('admin.news-posts.create') }}"
                       class="mt-1 rounded-xl bg-tpc-primary px-3 py-1.5 text-xs font-semibold text-white hover:bg-tpc-primary/90 transition">
                        Create first post
                    </a>
                </div>
            @endforelse
        </div>

        @if($recentNews->count() > 0)
            <div class="mt-3 pt-3 border-t border-tpc-primary/8">
                <a href="{{ route('admin.news-posts.index') }}"
                   class="flex items-center justify-center gap-1.5 rounded-xl py-2 text-xs font-medium text-tpc-ink/50
                          hover:bg-tpc-primary/5 hover:text-tpc-primary transition-colors">
                    View all {{ $newsCount }} posts
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>

    {{-- Sidebar panel: Content overview + Quick actions --}}
    <div class="space-y-3 sm:space-y-4">

        {{-- Content overview bar chart --}}
        <div class="rounded-2xl border border-tpc-primary/10 bg-white p-4 sm:p-5 shadow-sm">
            <h2 class="text-xs sm:text-sm font-semibold text-tpc-ink mb-3 sm:mb-4">Content Overview</h2>

            @php
                $barItems = [
                    ['label' => 'Programs',   'val' => $programCount,    'max' => max($programCount, 10),  'color' => 'bg-tpc-primary'],
                    ['label' => 'News',       'val' => $newsCount,       'max' => max($newsCount, 10),     'color' => 'bg-blue-500'],
                    ['label' => 'Messages',   'val' => $messageCount,    'max' => max($messageCount, 40),  'color' => 'bg-pink-500'],
                    ['label' => 'Pending',    'val' => $pendingNewsCount,'max' => max($newsCount, 10),    'color' => 'bg-amber-400'],
                ];
            @endphp

            <div class="space-y-3 sm:space-y-3.5">
                @foreach($barItems as $item)
                    @php $pct = $item['max'] > 0 ? round(($item['val'] / $item['max']) * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-[10px] sm:text-xs mb-1 sm:mb-1.5">
                            <span class="text-tpc-ink/60">{{ $item['label'] }}</span>
                            <span class="font-semibold text-tpc-ink tabular-nums">{{ $item['val'] }}</span>
                        </div>
                        <div class="h-1.5 w-full rounded-full bg-tpc-ink/8 overflow-hidden">
                            <div class="h-full rounded-full {{ $item['color'] }} transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="rounded-2xl border border-tpc-primary/10 bg-white p-4 sm:p-5 shadow-sm">
            <h2 class="text-xs sm:text-sm font-semibold text-tpc-ink mb-3">Quick Actions</h2>
            <div class="space-y-1.5">
                <a href="{{ route('admin.news-posts.create') }}"
                   class="flex items-center gap-2.5 rounded-xl border border-tpc-primary/15 px-3 py-2.5 text-xs font-medium
                          text-tpc-primary hover:bg-tpc-primary hover:text-white hover:border-tpc-primary transition-all duration-150 group">
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
                   class="flex items-center gap-2.5 rounded-xl border border-amber-200 px-3 py-2.5 text-xs font-medium
                          text-amber-700 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-150 group">
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
                   class="flex items-center gap-2.5 rounded-xl border border-pink-100 px-3 py-2.5 text-xs font-medium
                          text-pink-700 hover:bg-pink-500 hover:text-white hover:border-pink-500 transition-all duration-150 group">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    View messages
                    @if($unreadMessageCount > 0)
                        <span class="ml-auto shrink-0 rounded-full bg-pink-100 px-1.5 py-0.5 text-[10px] font-bold text-pink-700 group-hover:bg-pink-600 group-hover:text-white transition">
                            {{ $unreadMessageCount }}
                        </span>
                    @else
                        <svg class="h-3 w-3 ml-auto opacity-40 group-hover:opacity-100 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
                        </svg>
                    @endif
                </a>

                <a href="{{ route('admin.programs.create') }}"
                   class="flex items-center gap-2.5 rounded-xl border border-tpc-primary/15 px-3 py-2.5 text-xs font-medium
                          text-tpc-ink/60 hover:bg-tpc-ink/5 hover:text-tpc-ink transition-all duration-150 group">
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

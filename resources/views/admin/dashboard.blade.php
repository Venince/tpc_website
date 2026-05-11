@extends('admin.layout', ['title' => 'Dashboard'])

@section('content')

{{-- ── Stat Cards ─────────────────────────────────────────────────── --}}
<div class="grid gap-3 grid-cols-2 lg:grid-cols-4">

    {{-- Programs --}}
    <div class="relative overflow-hidden rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
        <div class="absolute left-0 inset-y-0 w-[3px] rounded-l-full bg-tpc-primary/70"></div>
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
                <p class="text-xs text-tpc-ink/50 font-medium uppercase tracking-wide">Programs</p>
                <p class="mt-2 text-3xl font-semibold text-tpc-ink leading-none">{{ $programCount }}</p>
                <p class="mt-1.5 text-xs text-tpc-ink/50">
                    <span class="font-medium text-tpc-primary">{{ $activeProgramCount }}</span> active
                </p>
            </div>
            <div class="shrink-0 rounded-xl bg-tpc-primary/8 border border-tpc-primary/10 p-2.5 text-tpc-primary">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10M4 18h10"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- News Posts --}}
    <div class="relative overflow-hidden rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
        <div class="absolute left-0 inset-y-0 w-[3px] rounded-l-full bg-blue-500/70"></div>
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
                <p class="text-xs text-tpc-ink/50 font-medium uppercase tracking-wide">News Posts</p>
                <p class="mt-2 text-3xl font-semibold text-tpc-ink leading-none">{{ $newsCount }}</p>
                <p class="mt-1.5 text-xs text-tpc-ink/50">
                    <span class="font-medium text-blue-600">{{ $publishedNewsCount }}</span> published
                </p>
            </div>
            <div class="shrink-0 rounded-xl bg-blue-50 border border-blue-100 p-2.5 text-blue-500">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 4h12v16H6z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h6M9 16h4"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Messages --}}
    <div class="relative overflow-hidden rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
        <div class="absolute left-0 inset-y-0 w-[3px] rounded-l-full bg-pink-500/70"></div>
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
                <p class="text-xs text-tpc-ink/50 font-medium uppercase tracking-wide">Messages</p>
                <p class="mt-2 text-3xl font-semibold text-tpc-ink leading-none">{{ $messageCount }}</p>
                <p class="mt-1.5 text-xs text-tpc-ink/50">
                    <span class="font-medium text-pink-600">{{ $unreadMessageCount }}</span> unread
                </p>
            </div>
            <div class="shrink-0 rounded-xl bg-pink-50 border border-pink-100 p-2.5 text-pink-500">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Pending Review --}}
    <div class="relative overflow-hidden rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
        <div class="absolute left-0 inset-y-0 w-[3px] rounded-l-full bg-amber-400/70"></div>
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
                <p class="text-xs text-tpc-ink/50 font-medium uppercase tracking-wide">Pending Review</p>
                <p class="mt-2 text-3xl font-semibold text-tpc-ink leading-none">{{ $pendingNewsCount }}</p>
                <p class="mt-1.5 text-xs text-tpc-ink/50">awaiting approval</p>
            </div>
            <div class="shrink-0 rounded-xl bg-amber-50 border border-amber-100 p-2.5 text-amber-500">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <circle cx="12" cy="12" r="10"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- ── Charts Row ──────────────────────────────────────────────────── --}}
<div class="mt-4 grid gap-4 grid-cols-1 sm:grid-cols-2">

    {{-- Programs donut --}}
    <div class="rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
        <h2 class="text-sm font-semibold text-tpc-ink">Programs Breakdown</h2>
        <div class="mt-4 flex items-center gap-6">
            <div class="relative shrink-0">
                <svg width="96" height="96" viewBox="0 0 96 96" class="-rotate-90">
                    <circle cx="48" cy="48" r="36" fill="none" stroke="#f0fdf4" stroke-width="13"/>
                    <circle
                        cx="48" cy="48" r="36" fill="none"
                        stroke="rgb(0 128 0)"
                        stroke-width="13"
                        stroke-linecap="round"
                        stroke-dasharray="226.2"
                        @php
                            $progPct = $programCount > 0 ? ($activeProgramCount / $programCount) : 0;
                        @endphp
                        stroke-dashoffset="{{ 226.2 - (226.2 * $progPct) }}"
                    />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-lg font-semibold text-tpc-ink leading-none">{{ $programCount > 0 ? round($progPct * 100) : 0 }}%</span>
                    <span class="text-[10px] text-tpc-ink/50 mt-0.5">active</span>
                </div>
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex items-center gap-2.5">
                    <span class="h-2.5 w-2.5 rounded-full bg-tpc-primary shrink-0"></span>
                    <span class="text-tpc-ink/70">Active</span>
                    <span class="ml-auto font-semibold text-tpc-ink">{{ $activeProgramCount }}</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <span class="h-2.5 w-2.5 rounded-full bg-tpc-primary/15 border border-tpc-primary/20 shrink-0"></span>
                    <span class="text-tpc-ink/70">Inactive</span>
                    <span class="ml-auto font-semibold text-tpc-ink">{{ $programCount - $activeProgramCount }}</span>
                </div>
                <div class="pt-1 border-t border-tpc-primary/10 flex items-center gap-2.5">
                    <span class="text-tpc-ink/50 text-xs">Total</span>
                    <span class="ml-auto font-semibold text-tpc-ink">{{ $programCount }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- News donut --}}
    <div class="rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
        <h2 class="text-sm font-semibold text-tpc-ink">News by Status</h2>
        <div class="mt-4 flex items-center gap-6">
            <div class="relative shrink-0">
                <svg width="96" height="96" viewBox="0 0 96 96" class="-rotate-90">
                    <circle cx="48" cy="48" r="36" fill="none" stroke="#eff6ff" stroke-width="13"/>
                    <circle
                        cx="48" cy="48" r="36" fill="none"
                        stroke="#3b82f6"
                        stroke-width="13"
                        stroke-linecap="round"
                        stroke-dasharray="226.2"
                        @php
                            $newsPct = $newsCount > 0 ? ($publishedNewsCount / $newsCount) : 0;
                        @endphp
                        stroke-dashoffset="{{ 226.2 - (226.2 * $newsPct) }}"
                    />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-lg font-semibold text-tpc-ink leading-none">{{ $newsCount > 0 ? round($newsPct * 100) : 0 }}%</span>
                    <span class="text-[10px] text-tpc-ink/50 mt-0.5">published</span>
                </div>
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex items-center gap-2.5">
                    <span class="h-2.5 w-2.5 rounded-full bg-blue-500 shrink-0"></span>
                    <span class="text-tpc-ink/70">Published</span>
                    <span class="ml-auto font-semibold text-tpc-ink">{{ $publishedNewsCount }}</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <span class="h-2.5 w-2.5 rounded-full bg-amber-400 shrink-0"></span>
                    <span class="text-tpc-ink/70">Pending</span>
                    <span class="ml-auto font-semibold text-tpc-ink">{{ $pendingNewsCount }}</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <span class="h-2.5 w-2.5 rounded-full bg-tpc-ink/15 border border-tpc-ink/20 shrink-0"></span>
                    <span class="text-tpc-ink/70">Draft</span>
                    <span class="ml-auto font-semibold text-tpc-ink">{{ $newsCount - $publishedNewsCount - $pendingNewsCount }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Bottom Row ──────────────────────────────────────────────────── --}}
<div class="mt-4 grid gap-4 grid-cols-1 lg:grid-cols-3">

    {{-- Recent news (2/3 width) --}}
    <div class="lg:col-span-2 rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-tpc-ink">Recent News Posts</h2>
            <a href="{{ route('admin.news-posts.index') }}"
               class="text-xs font-medium text-tpc-primary hover:text-tpc-secondary transition-colors">
                View all →
            </a>
        </div>

        <div class="divide-y divide-tpc-primary/8">
            @forelse ($recentNews as $post)
                <div class="flex items-center gap-3 py-2.5">
                    {{-- Status dot --}}
                    @if($post->is_published)
                        <span class="h-2 w-2 shrink-0 rounded-full bg-tpc-primary"></span>
                    @elseif($post->status === 'pending')
                        <span class="h-2 w-2 shrink-0 rounded-full bg-amber-400"></span>
                    @else
                        <span class="h-2 w-2 shrink-0 rounded-full bg-tpc-ink/20"></span>
                    @endif

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-tpc-ink">{{ $post->title }}</p>
                        <p class="text-xs text-tpc-ink/50 mt-0.5">
                            {{ $post->category }}
                            <span class="mx-1">·</span>
                            {{ $post->created_at->format('M d, Y') }}
                        </p>
                    </div>

                    @if($post->is_published)
                        <span class="shrink-0 rounded-full bg-tpc-primary/10 px-2.5 py-0.5 text-[11px] font-medium text-tpc-primary">
                            Published
                        </span>
                    @elseif(isset($post->status) && $post->status === 'pending')
                        <span class="shrink-0 rounded-full bg-amber-100 px-2.5 py-0.5 text-[11px] font-medium text-amber-700">
                            Pending
                        </span>
                    @else
                        <span class="shrink-0 rounded-full bg-tpc-ink/8 px-2.5 py-0.5 text-[11px] font-medium text-tpc-ink/50">
                            Draft
                        </span>
                    @endif

                    <a href="{{ route('admin.news-posts.edit', $post) }}"
                       class="shrink-0 text-xs text-tpc-primary font-medium hover:text-tpc-secondary transition-colors">
                        Edit
                    </a>
                </div>
            @empty
                <p class="py-6 text-sm text-center text-tpc-ink/40">No news posts yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Content overview bar chart (1/3 width) --}}
    <div class="rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
        <h2 class="text-sm font-semibold text-tpc-ink mb-4">Content Overview</h2>

        @php
            $barItems = [
                ['label' => 'Programs',   'val' => $programCount,   'max' => max($programCount, 10),  'color' => 'bg-tpc-primary'],
                ['label' => 'News posts', 'val' => $newsCount,      'max' => max($newsCount, 10),     'color' => 'bg-blue-500'],
                ['label' => 'Messages',   'val' => $messageCount,   'max' => max($messageCount, 40),  'color' => 'bg-pink-500'],
                ['label' => 'Pending',    'val' => $pendingNewsCount,'max' => max($newsCount, 10),    'color' => 'bg-amber-400'],
            ];
        @endphp

        <div class="space-y-3.5">
            @foreach($barItems as $item)
                @php $pct = $item['max'] > 0 ? round(($item['val'] / $item['max']) * 100) : 0; @endphp
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-tpc-ink/60">{{ $item['label'] }}</span>
                        <span class="font-semibold text-tpc-ink">{{ $item['val'] }}</span>
                    </div>
                    <div class="h-1.5 w-full rounded-full bg-tpc-ink/8 overflow-hidden">
                        <div class="h-full rounded-full {{ $item['color'] }}" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Quick links --}}
        <div class="mt-6 pt-4 border-t border-tpc-primary/10 space-y-2">
            <p class="text-[11px] font-semibold uppercase tracking-wide text-tpc-ink/40 mb-3">Quick links</p>
            <a href="{{ route('admin.programs.index') }}"
               class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-medium text-tpc-ink/70
                      hover:bg-tpc-primary/5 hover:text-tpc-primary transition-colors group">
                Manage programs
                <svg class="h-3.5 w-3.5 opacity-40 group-hover:opacity-100 transition-opacity" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
                </svg>
            </a>
            <a href="{{ route('admin.news-posts.index') }}"
               class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-medium text-tpc-ink/70
                      hover:bg-tpc-primary/5 hover:text-tpc-primary transition-colors group">
                Manage news
                <svg class="h-3.5 w-3.5 opacity-40 group-hover:opacity-100 transition-opacity" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
                </svg>
            </a>
            @if(auth()->check() && auth()->user()->is_super_admin)
            <a href="{{ route('admin.news-review.index') }}"
               class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-medium text-tpc-ink/70
                      hover:bg-amber-50 hover:text-amber-700 transition-colors group">
                Review pending
                @if($pendingNewsCount > 0)
                    <span class="rounded-full bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold text-amber-700">
                        {{ $pendingNewsCount }}
                    </span>
                @else
                    <svg class="h-3.5 w-3.5 opacity-40 group-hover:opacity-100 transition-opacity" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
                    </svg>
                @endif
            </a>
            @endif
        </div>
    </div>

</div>

@endsection

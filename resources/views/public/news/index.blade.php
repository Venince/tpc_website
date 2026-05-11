{{-- resources/views/public/news/index.blade.php --}}
@extends('layouts.site')

@section('title', 'News')

@section('content')

    {{-- ══════════════════════════════════════
         PAGE HEADER
    ══════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <p class="text-xs font-bold tracking-widest text-tpc-accent uppercase mb-1">Talibon Polytechnic College</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-white leading-tight">News &amp; Announcements</h1>
            <p class="mt-2 max-w-2xl text-sm text-white/75 leading-relaxed">
                Read the latest campus announcements, events, advisories, and scholarship updates.
            </p>

            {{-- Filters --}}
            <form method="GET" action="{{ route('news.index') }}" class="mt-6 flex flex-wrap gap-3">
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-white/50"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/>
                    </svg>
                    <input name="q" value="{{ request('q') }}" placeholder="Search news…"
                           class="rounded-full border-2 border-white/30 bg-white/10 text-white placeholder-white/50
                                  pl-9 pr-4 py-2 text-sm focus:border-white focus:outline-none backdrop-blur-sm w-60" />
                </div>

                <select name="category"
                        class="rounded-full border-2 border-white/30 bg-white/10 text-white px-4 py-2 text-sm focus:border-white focus:outline-none">
                    <option value="" class="text-tpc-ink">All categories</option>
                    @foreach(['Announcement','Event','Advisory','Scholarship'] as $cat)
                        <option value="{{ $cat }}" class="text-tpc-ink" @selected(request('category') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>

                <button class="rounded-full border-2 border-white bg-white px-5 py-2 text-sm font-bold text-tpc-primary hover:bg-tpc-accent hover:border-tpc-accent transition">
                    Filter
                </button>

                @if(request('q') || request('category'))
                    <a href="{{ route('news.index') }}"
                       class="inline-flex items-center gap-1 rounded-lg border-2 border-white/40 px-5 py-2 text-sm font-bold text-white/80 hover:text-white hover:border-white transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear
                    </a>
                @endif
            </form>

            {{-- Category quick-filters --}}
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach(['Announcement','Event','Advisory','Scholarship'] as $cat)
                    <a href="{{ route('news.index', ['category' => $cat]) }}"
                       class="inline-block px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide border transition
                              {{ request('category') === $cat
                                 ? 'bg-white text-tpc-primary border-white'
                                 : 'border-white/30 text-white/70 hover:border-white hover:text-white' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-8 sm:h-10">
                <path d="M0 40 C360 0 1080 0 1440 40 L1440 40 L0 40 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         POSTS
    ══════════════════════════════════════ --}}
    <section class="bg-gray-50 min-h-[40vh]">
        <div class="max-w-7xl mx-auto px-4 py-14">

            {{-- Section meta row --}}
            <div class="flex items-center gap-4 mb-10">
                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">
                    {{ request('q') || request('category') ? 'Search Results' : 'All Posts' }}
                </h2>
                <div class="flex-1 h-px bg-gray-200"></div>
                @if($posts->total() > 0)
                    <span class="text-xs text-gray-400 font-medium tabular-nums">
                        {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}
                    </span>
                @endif
            </div>

            @if($posts->isNotEmpty())

                {{-- ── FEATURED (first post on page 1) ── --}}
                @php $featured = $posts->first(); @endphp
                @if($posts->currentPage() === 1)
                <a href="{{ route('news.show', $featured) }}"
                   class="news-featured-card group relative block bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-8
                          hover:shadow-lg hover:border-tpc-primary/30 transition-all duration-300">

                    {{-- Accent top bar --}}
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-tpc-primary via-tpc-primary to-tpc-accent z-10"></div>

                    <div class="sm:flex min-h-[280px]">
                        {{-- Image --}}
                        @if($featured->image_path)
                            <div class="relative sm:w-[42%] bg-gray-100 overflow-hidden shrink-0">
                                <img src="{{ asset('storage/' . $featured->image_path) }}"
                                     alt="{{ $featured->title }}"
                                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500"
                                     loading="lazy" />
                                <div class="sm:hidden h-56"></div>
                            </div>
                        @endif

                        {{-- Content --}}
                        <div class="flex-1 flex flex-col justify-between p-6 sm:p-8">
                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-4">
                                    <span class="inline-flex items-center gap-1 bg-tpc-primary text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                                        <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                                        {{ $featured->category ?? 'Announcement' }}
                                    </span>
                                    <span class="inline-block bg-tpc-accent/20 text-tpc-primary text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                                        Latest
                                    </span>
                                    @if($featured->published_at)
                                        <time class="ml-auto text-xs text-gray-400 font-medium" datetime="{{ $featured->published_at->toDateString() }}">
                                            {{ $featured->published_at->format('F d, Y') }}
                                        </time>
                                    @endif
                                </div>

                                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 group-hover:text-tpc-primary transition-colors duration-200 leading-snug mb-3">
                                    {{ $featured->title }}
                                </h2>
                                <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">
                                    {{ $featured->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($featured->body), 200) }}
                                </p>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 text-sm font-bold text-tpc-primary group-hover:gap-3 transition-all duration-200">
                                    Read Article
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                                <span class="hidden sm:inline-flex items-center gap-1 text-xs text-gray-300 font-medium">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v8a2 2 0 01-2 2z"/>
                                    </svg>
                                    Featured story
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                @endif

                {{-- ── REMAINING POSTS GRID ── --}}
                @php $remaining = $posts->currentPage() === 1 ? $posts->skip(1) : $posts; @endphp
                @if($remaining->isNotEmpty())
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($remaining as $post)
                        <article class="news-card group bg-white rounded-2xl border border-gray-200 shadow-sm
                                        hover:shadow-md hover:border-tpc-primary/30 hover:-translate-y-0.5
                                        transition-all duration-300 overflow-hidden flex flex-col">

                            {{-- Image --}}
                            @if($post->image_path)
                                <a href="{{ route('news.show', $post) }}"
                                   class="block shrink-0 relative overflow-hidden bg-gray-100"
                                   style="height: 176px;">
                                    <img src="{{ asset('storage/' . $post->image_path) }}"
                                         alt="{{ $post->title }}"
                                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500"
                                         loading="lazy" />
                                    {{-- Category pill overlaid on image --}}
                                    <span class="absolute top-3 left-3 inline-flex items-center gap-1 bg-tpc-primary/90 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full">
                                        {{ $post->category ?? 'Announcement' }}
                                    </span>
                                </a>
                            @else
                                {{-- No-image state --}}
                                <div class="shrink-0 relative flex items-center justify-center bg-tpc-primary/5 overflow-hidden"
                                     style="height: 72px;">
                                    <svg class="absolute inset-0 w-full h-full opacity-[0.04]" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <pattern id="dots-ni-{{ $loop->index }}" x="0" y="0" width="16" height="16" patternUnits="userSpaceOnUse">
                                                <circle cx="2" cy="2" r="1.5" fill="#166534"/>
                                            </pattern>
                                        </defs>
                                        <rect width="100%" height="100%" fill="url(#dots-ni-{{ $loop->index }})"/>
                                    </svg>
                                    <span class="relative inline-flex items-center gap-1 bg-tpc-primary/90 text-white text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full">
                                        {{ $post->category ?? 'Announcement' }}
                                    </span>
                                </div>
                            @endif

                            {{-- Body --}}
                            <div class="p-5 flex flex-col flex-1">
                                @if($post->published_at)
                                    <time class="text-[11px] text-gray-400 font-medium mb-2 block tabular-nums"
                                          datetime="{{ $post->published_at->toDateString() }}">
                                        {{ $post->published_at->format('M d, Y') }}
                                    </time>
                                @endif

                                <h2 class="text-sm font-bold text-gray-800 group-hover:text-tpc-primary transition-colors duration-200 leading-snug flex-1 line-clamp-3">
                                    <a href="{{ route('news.show', $post) }}">{{ $post->title }}</a>
                                </h2>

                                @if($post->excerpt || $post->body)
                                    <p class="mt-2 text-xs text-gray-500 leading-relaxed line-clamp-2">
                                        {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 100) }}
                                    </p>
                                @endif

                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <a href="{{ route('news.show', $post) }}"
                                       class="inline-flex items-center gap-1 text-xs font-bold text-tpc-primary group-hover:gap-2 transition-all duration-200">
                                        Read Article
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                @endif

                {{-- Pagination --}}
                <div class="mt-10">
                    {{ $posts->links() }}
                </div>

            @else
                <div class="py-24 flex flex-col items-center justify-center text-center
                            border border-dashed border-gray-300 rounded-2xl bg-white">
                    <div class="h-14 w-14 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v8a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="text-base font-semibold text-gray-300 mb-1">No posts found</p>
                    <p class="text-sm text-gray-400">
                        {{ request('q') ? 'Try a different search term or category.' : 'Check back soon for updates.' }}
                    </p>
                    @if(request('q') || request('category'))
                        <a href="{{ route('news.index') }}"
                           class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-tpc-primary hover:underline">
                            ← Clear filters
                        </a>
                    @endif
                </div>
            @endif

        </div>
    </section>

@endsection

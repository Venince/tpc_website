{{-- resources/views/public/news/index.blade.php --}}
@extends('layouts.site')

@section('title', 'News')

@section('content')

    {{-- ══════════════════════════════════════
         PAGE HEADER
    ══════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-tpc-secondary">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0"
             style="background: radial-gradient(ellipse at 70% 50%, rgba(255,255,255,0.06) 0%, transparent 60%),
                                radial-gradient(ellipse at 20% 80%, rgba(0,0,0,0.15) 0%, transparent 50%)"></div>
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-[0.04]"
             style="background-image: linear-gradient(rgba(255,255,255,0.8) 1px, transparent 1px),
                                      linear-gradient(90deg, rgba(255,255,255,0.8) 1px, transparent 1px);
                    background-size: 40px 40px;"></div>

        <div class="relative mx-auto max-w-2xl px-4 pt-10 pb-16 sm:pt-14 sm:pb-20">
            <div class="flex flex-col items-center text-center">
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    News &amp; Announcements
                </h1>
                <p class="mt-3 max-w-lg text-sm text-white/60 leading-relaxed">
                    Read the latest campus announcements, events, advisories, and scholarship updates.
                </p>
            </div>

            {{-- Search + Filter --}}
            <form method="GET" action="{{ route('news.index') }}"
                  class="mt-5 sm:mt-6 flex flex-wrap justify-center gap-2 sm:gap-3">
                <div class="relative w-full sm:w-auto">
                    <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-white/50"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/>
                    </svg>
                    <input name="q" value="{{ request('q') }}" placeholder="Search news…"
                           class="w-full sm:w-60 rounded-full border-2 border-white/30 bg-white/10 text-white
                                  placeholder-white/50 pl-9 pr-4 py-2 text-xs sm:text-sm
                                  focus:border-white focus:outline-none backdrop-blur-sm" />
                </div>

                <div class="flex gap-2 sm:gap-3 flex-wrap">
                    <select name="category"
                            class="rounded-full border-2 border-white/30 bg-white/10 text-white
                                   px-3 sm:px-4 py-2 text-xs sm:text-sm focus:border-white focus:outline-none">
                        <option value="" class="text-tpc-ink">All categories</option>
                        @foreach(['Announcement','Event','Advisory','Scholarship'] as $cat)
                            <option value="{{ $cat }}" class="text-tpc-ink"
                                    @selected(request('category') === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>

                    <button class="rounded-full border-2 border-white bg-white px-4 sm:px-5 py-2
                                   text-xs sm:text-sm font-bold text-tpc-primary
                                   hover:bg-tpc-secondary hover:border-tpc-secondary hover:text-white transition">
                        Filter
                    </button>

                    @if(request('q') || request('category'))
                        <a href="{{ route('news.index') }}"
                           class="inline-flex items-center gap-1 rounded-full border-2 border-white/40
                                  px-4 sm:px-5 py-2 text-xs sm:text-sm font-bold text-white/80
                                  hover:text-white hover:border-white transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            {{-- Category quick-filters --}}
            <div class="mt-3 sm:mt-4 flex flex-wrap justify-center gap-2">
                @foreach(['Announcement','Event','Advisory','Scholarship'] as $cat)
                    <a href="{{ route('news.index', ['category' => $cat]) }}"
                       class="inline-block px-3 py-1 rounded-full text-[10px] sm:text-[11px] font-bold
                              uppercase tracking-wide border transition touch-manipulation
                              {{ request('category') === $cat
                                 ? 'bg-white text-tpc-primary border-white'
                                 : 'border-white/30 text-white/70 hover:border-white hover:text-white' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg"
                 preserveAspectRatio="none" class="w-full h-8 sm:h-12">
                <path d="M0 48 C480 0 960 0 1440 48 L1440 48 L0 48 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         NEWSFEED
    ══════════════════════════════════════ --}}
    <section class="bg-gray-50 min-h-[40vh]">
        <div class="mx-auto max-w-2xl px-4 py-8 sm:py-12">

            {{-- Meta row --}}
            <div class="flex items-center gap-3 mb-6">
                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                <h2 class="text-[10px] sm:text-xs font-bold tracking-widest text-tpc-primary uppercase">
                    {{ request('q') || request('category') ? 'Search Results' : 'Latest Posts' }}
                </h2>
                <div class="flex-1 h-px bg-gray-200"></div>
                @if($posts->total() > 0)
                    <span class="text-[10px] sm:text-xs text-gray-400 font-medium tabular-nums shrink-0">
                        {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}
                    </span>
                @endif
            </div>

            @if($posts->isNotEmpty())

                {{-- ── FEED ── --}}
                <div class="space-y-5">
                    @foreach($posts as $post)

                        {{-- Feed card --}}
                        <article class="bg-white rounded-2xl border border-gray-200 shadow-sm
                                        hover:shadow-md hover:border-tpc-primary/20
                                        transition-all duration-300 overflow-hidden">

                            {{-- Card header: school identity + timestamp --}}
                            <div class="flex flex-row items-center gap-3 px-4 pt-4 pb-3">
                                <img src="{{ asset('images/TPC-Logo.png') }}" alt="TPC"
                                    class="h-10 w-10 shrink-0 object-contain" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-gray-900 leading-tight">
                                        Talibon Polytechnic College
                                    </p>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="inline-flex items-center gap-1 bg-tpc-primary/10 text-tpc-primary
                                                    text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full">
                                            {{ $post->category }}
                                        </span>
                                        @if($post->published_at)
                                            <span class="text-[10px] text-gray-400">·</span>
                                            <time class="text-[10px] text-gray-400 font-medium"
                                                datetime="{{ $post->published_at->toDateString() }}"
                                                title="{{ $post->published_at->format('F d, Y g:i A') }}">
                                                {{ $post->published_at->diffForHumans() }}
                                            </time>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Post title + excerpt --}}
                            <div class="px-4 pb-3 text-left">
                                <h2 class="text-sm sm:text-base font-bold text-gray-900 leading-snug mb-1">
                                    <a href="{{ route('news.show', $post) }}"
                                       class="hover:text-tpc-primary transition-colors duration-200">
                                        {{ $post->title }}
                                    </a>
                                </h2>
                                @if($post->excerpt || $post->body)
                                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed line-clamp-3">
                                        {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 180) }}
                                    </p>
                                @endif
                            </div>

                            {{-- Post image --}}
                            @if($post->image_path)
                                <a href="{{ route('news.show', $post) }}" class="block">
                                    <div class="relative overflow-hidden bg-gray-100" style="max-height: 420px;">
                                        <img src="{{ asset('storage/' . $post->image_path) }}"
                                             alt="{{ $post->title }}"
                                             class="w-full object-cover hover:scale-[1.02] transition-transform duration-500"
                                             loading="lazy" />
                                    </div>
                                </a>
                            @endif

                            {{-- Card footer: Like + Read Article --}}
                            <div class="px-4 py-3 border-t border-gray-100 flex flex-row items-center justify-between">

                                <div class="flex items-center gap-3">
                                    {{-- Like button --}}
                                    <button
                                        type="button"
                                        data-post-id="{{ $post->id }}"
                                        data-likes="{{ $post->likes_count }}"
                                        onclick="handleLike(this)"
                                        class="like-btn inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold
                                                border border-gray-200 text-gray-500 hover:border-tpc-primary hover:text-tpc-primary
                                                hover:bg-tpc-primary/10 transition-all duration-200 select-none">
                                        <svg class="like-icon h-3.5 w-3.5" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/>
                                            <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                                        </svg>
                                        <span class="like-count">{{ $post->likes_count }}</span>
                                    </button>

                                    {{-- Photo count badge --}}
                                    @if($post->galleryImages->isNotEmpty())
                                        <span class="inline-flex items-center gap-1 rounded-full px-3 py-1.5 text-xs font-bold
                                                    border border-gray-200 text-gray-500">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                            </svg>
                                            {{ $post->galleryImages->count() }} {{ Str::plural('photo', $post->galleryImages->count()) }}
                                        </span>
                                    @endif

                                    {{-- Read Article --}}
                                    <a href="{{ route('news.show', $post) }}"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-tpc-primary
                                            hover:gap-2.5 transition-all duration-200">
                                        Read Article
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>

                                @if($post->published_at)
                                    <time class="text-[10px] text-gray-400 hidden sm:block"
                                        datetime="{{ $post->published_at->toDateString() }}">
                                        {{ $post->published_at->format('M d, Y') }}
                                    </time>
                                @endif

                            </div>

                        </article>

                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>

            @else

                <div class="py-16 sm:py-24 flex flex-col items-center justify-center text-center
                            border border-dashed border-gray-300 rounded-2xl bg-white">
                    <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-full bg-gray-100
                                flex items-center justify-center mb-4">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-300" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v8a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm sm:text-base font-semibold text-gray-300 mb-1">No posts found</p>
                    <p class="text-xs sm:text-sm text-gray-400">
                        {{ request('q') ? 'Try a different search term or category.' : 'Check back soon for updates.' }}
                    </p>
                    @if(request('q') || request('category'))
                        <a href="{{ route('news.index') }}"
                           class="mt-4 inline-flex items-center gap-1 text-xs sm:text-sm font-bold
                                  text-tpc-primary hover:underline">
                            ← Clear filters
                        </a>
                    @endif
                </div>

            @endif

        </div>
    </section>

@endsection

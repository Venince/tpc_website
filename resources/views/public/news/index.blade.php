@extends('layouts.site')

@section('title', isset($focusPost) && $focusPost ? $focusPost->title : 'News')

@section('content')

    @php
        // A shared link (/news?post=slug) pins that post at the top of the feed
        $focusPost = $focusPost ?? null;
        $feed      = $focusPost ? collect([$focusPost])->concat($posts->getCollection()) : $posts->getCollection();
        $feedTotal = $posts->total() + ($focusPost ? 1 : 0);
    @endphp

    @if($focusPost)
        @php
            $ogPhoto = optional($focusPost->galleryImages->first())->image_path ?: $focusPost->image_path;
            $ogText  = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', (string) ($focusPost->body ?: $focusPost->excerpt))), 200);
        @endphp
        {{-- Share preview (Facebook / Messenger / etc. read these when the link is pasted) --}}
        @push('styles')
            <meta name="description" content="{{ $ogText }}">
            <meta property="og:type" content="article">
            <meta property="og:site_name" content="Talibon Polytechnic College">
            <meta property="og:title" content="{{ $focusPost->title }}">
            <meta property="og:description" content="{{ $ogText }}">
            <meta property="og:url" content="{{ route('news.index', ['post' => $focusPost->slug]) }}">
            <meta property="og:image" content="{{ $ogPhoto ? asset('storage/' . $ogPhoto) : asset('images/TPC-Logo.png') }}">
            <meta name="twitter:card" content="summary_large_image">
        @endpush
    @endif

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
                           class="w-full sm:w-80 rounded-full border-2 border-white/30 bg-white/10 text-white
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
                @if($feedTotal > 0)
                    <span class="text-[10px] sm:text-xs text-gray-400 font-medium tabular-nums shrink-0">
                        {{ $feedTotal }} {{ Str::plural('post', $feedTotal) }}
                    </span>
                @endif
            </div>

            @if(request('q'))
                <p class="text-xs text-gray-500 mb-4 -mt-2">
                    Showing results for
                    <span class="font-semibold text-tpc-ink">"{{ request('q') }}"</span>
                    — {{ $posts->total() }} {{ Str::plural('result', $posts->total()) }} found
                    @if(request('category'))
                        in <span class="font-semibold text-tpc-ink">{{ request('category') }}</span>
                    @endif
                </p>
            @endif

            @if($feed->isNotEmpty())

                {{-- ── FEED (Facebook-style) ── --}}
                @once
                    <style>
                        /* Caption collapsed to 3 lines; .nf-open lifts the limit ("See more") */
                        .nf-clamp {
                            display: -webkit-box;
                            -webkit-box-orient: vertical;
                            -webkit-line-clamp: 3;
                            overflow: hidden;
                        }
                        .nf-clamp.nf-open {
                            display: block;
                            -webkit-line-clamp: unset;
                            overflow: visible;
                        }
                        .nf-wrap { overflow-wrap: anywhere; word-break: break-word; }
                        .nf-thumbs { scrollbar-width: thin; }
                    </style>
                @endonce

                @if($focusPost)
                    <div class="mb-4 flex items-center justify-between gap-3 rounded-xl border border-tpc-primary/20 bg-tpc-primary/5 px-4 py-2.5 text-xs text-gray-600">
                        <span>Showing a shared post</span>
                        <a href="{{ route('news.index') }}" class="shrink-0 font-bold text-tpc-primary hover:underline">View all news</a>
                    </div>
                @endif

                <div class="space-y-4">
                    @foreach($feed as $post)
                        @php
                            // Every photo (with its optional caption); fall back to the legacy single image_path
                            $photos = $post->galleryImages->map(fn ($img) => [
                                'src'     => asset('storage/' . $img->image_path),
                                'caption' => $img->caption,
                            ])->values()->all();

                            if (empty($photos) && $post->image_path) {
                                $photos = [['src' => asset('storage/' . $post->image_path), 'caption' => null]];
                            }
                            $imgCount = count($photos);

                            // The caption is the full post text; the card collapses it with "See more"
                            $caption = trim((string) ($post->body ?: $post->excerpt));

                            // The post's own link (opens the feed with this post pinned at the top)
                            $shareUrl = route('news.index', ['post' => $post->slug]);
                            $isFocus  = $focusPost && $focusPost->id === $post->id;
                        @endphp

                        <article
                            x-data="{
                                open: false,
                                current: 0,
                                touchX: null,
                                expanded: {{ $isFocus ? 'true' : 'false' }},
                                truncated: false,
                                photos: @js($photos),
                                likes: {{ (int) $post->likes_count }},
                                liked: false,

                                // Show 'See more' only when the collapsed caption is actually cut off
                                measure() {
                                    if (this.expanded || !this.$refs.cap) return;
                                    const el = this.$refs.cap;
                                    this.truncated = el.scrollHeight - el.clientHeight > 1;
                                },

                                openAt(i) {
                                    this.current = i;
                                    this.open = true;
                                    this.syncLike();
                                    document.body.style.overflow = 'hidden';
                                },
                                close() { this.open = false; document.body.style.overflow = ''; },
                                prev()  { this.current = (this.current - 1 + this.photos.length) % this.photos.length; },
                                next()  { this.current = (this.current + 1) % this.photos.length; },
                                swipe(e) {
                                    if (this.touchX === null) return;
                                    const dx = e.changedTouches[0].clientX - this.touchX;
                                    this.touchX = null;
                                    if (Math.abs(dx) < 50 || this.photos.length < 2) return;
                                    dx < 0 ? this.next() : this.prev();
                                },

                                // The feed card's own Like button stays the single source of truth;
                                // the viewer mirrors it so both always agree.
                                likeButton() { return document.getElementById('like-btn-{{ $post->id }}'); },
                                syncLike() {
                                    const b = this.likeButton();
                                    if (!b) return;
                                    this.likes = parseInt(b.querySelector('.like-count')?.textContent || '0', 10);
                                    this.liked = b.classList.contains('text-tpc-primary');
                                },
                                toggleLike() {
                                    const b = this.likeButton();
                                    if (!b || typeof window.handleLike !== 'function') return;
                                    const p = window.handleLike(b);
                                    this.syncLike();
                                    if (p && p.then) p.then(() => this.syncLike());
                                }
                            }"
                            x-init="$nextTick(() => measure()); if (document.fonts && document.fonts.ready) document.fonts.ready.then(() => measure());
                                    @if($isFocus) $nextTick(() => $el.scrollIntoView({ behavior: 'smooth', block: 'start' })); @endif"
                            @resize.window.debounce.150ms="measure()"
                            @popstate.window="if (open) close()"
                            @keydown.escape.window="if (open && !window.__nfShareOpen) close()"
                            @keydown.arrow-left.window="if (open) prev()"
                            @keydown.arrow-right.window="if (open) next()"
                            id="post-{{ $post->id }}"
                            class="scroll-mt-24 overflow-hidden rounded-2xl border bg-white shadow-sm {{ $isFocus ? 'border-tpc-primary/40 ring-2 ring-tpc-primary/30' : 'border-gray-200/80' }}">

                            {{-- Header: page identity + time --}}
                            <div class="flex items-center gap-3 px-4 pb-2 pt-3.5">
                                <img src="{{ asset('images/TPC-Logo.png') }}" alt="TPC"
                                     class="h-10 w-10 shrink-0 rounded-full bg-white object-contain p-0.5 ring-1 ring-gray-200">
                                <div class="min-w-0 flex-1">
                                    <p class="flex items-center gap-1 text-[15px] font-bold leading-tight text-gray-900">
                                        <span class="truncate">Talibon Polytechnic College</span>
                                        <span class="inline-flex h-3.5 w-3.5 shrink-0 items-center justify-center rounded-full bg-tpc-primary" title="Official page">
                                            <svg class="h-2.5 w-2.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </span>
                                    </p>
                                    <div class="mt-0.5 flex flex-wrap items-center gap-x-1.5 gap-y-0.5 text-xs text-gray-500">
                                        @if($post->published_at)
                                            <time datetime="{{ $post->published_at->toDateString() }}"
                                                  title="{{ $post->published_at->format('F d, Y g:i A') }}">
                                                {{ $post->published_at->diffForHumans() }}
                                            </time>
                                            <span aria-hidden="true">·</span>
                                        @endif
                                        <span class="rounded-full bg-tpc-primary/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-tpc-primary">
                                            {{ $post->category }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Title + caption --}}
                            <div class="px-4 pb-3">
                                <h2 class="nf-wrap text-[15px] font-bold leading-snug text-gray-900 sm:text-base">
                                    @if($imgCount > 0)
                                        {{-- Clicking the post opens its photos --}}
                                        <button type="button" @click="openAt(0)" class="text-left hover:underline">
                                            {{ $post->title }}
                                        </button>
                                    @else
                                        {{ $post->title }}
                                    @endif
                                </h2>

                                @if($caption !== '')
                                    <div class="mt-1.5">
                                        <p x-ref="cap"
                                           :class="expanded ? 'nf-open' : ''"
                                           class="nf-clamp nf-wrap whitespace-pre-line text-[15px] leading-relaxed text-gray-700">{{ $caption }}</p>

                                        <button type="button"
                                                x-show="truncated"
                                                style="display:none"
                                                @click="expanded = !expanded"
                                                :aria-expanded="expanded"
                                                class="mt-0.5 text-sm font-semibold text-gray-500 hover:text-tpc-primary hover:underline">
                                            <span x-text="expanded ? 'See less' : 'See more'"></span>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            {{-- Photo grid (edge-to-edge, tap any photo to open the viewer) --}}
                            @if($imgCount === 1)
                                <button type="button" @click="openAt(0)"
                                        class="group relative block w-full overflow-hidden bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-tpc-primary"
                                        aria-label="View photo of {{ $post->title }}">
                                    <img src="{{ $photos[0]['src'] }}" alt="{{ $post->title }}" loading="lazy"
                                         class="max-h-[32rem] w-full object-cover transition duration-300 group-hover:scale-[1.02]">
                                </button>

                            @elseif($imgCount === 2)
                                <div class="grid grid-cols-2 gap-0.5">
                                    @foreach($photos as $i => $p)
                                        @include('public.news._photo-cell', ['i' => $i, 'src' => $p['src'], 'class' => 'aspect-square', 'label' => 'View photo ' . ($i + 1) . ' of ' . $imgCount])
                                    @endforeach
                                </div>

                            @elseif($imgCount === 3)
                                <div class="grid aspect-[4/3] grid-cols-2 grid-rows-2 gap-0.5">
                                    @include('public.news._photo-cell', ['i' => 0, 'src' => $photos[0]['src'], 'class' => 'row-span-2 h-full w-full', 'label' => 'View photo 1 of 3'])
                                    @include('public.news._photo-cell', ['i' => 1, 'src' => $photos[1]['src'], 'class' => 'h-full w-full', 'label' => 'View photo 2 of 3'])
                                    @include('public.news._photo-cell', ['i' => 2, 'src' => $photos[2]['src'], 'class' => 'h-full w-full', 'label' => 'View photo 3 of 3'])
                                </div>

                            @elseif($imgCount === 4)
                                <div class="grid grid-cols-2 gap-0.5">
                                    @foreach($photos as $i => $p)
                                        @include('public.news._photo-cell', ['i' => $i, 'src' => $p['src'], 'class' => 'aspect-[4/3]', 'label' => 'View photo ' . ($i + 1) . ' of 4'])
                                    @endforeach
                                </div>

                            @elseif($imgCount >= 5)
                                <div class="grid grid-cols-6 gap-0.5">
                                    @foreach(array_slice($photos, 0, 5) as $i => $p)
                                        @include('public.news._photo-cell', [
                                            'i'     => $i,
                                            'src'   => $p['src'],
                                            'class' => $i < 2 ? 'col-span-3 aspect-[4/3]' : 'col-span-2 aspect-square',
                                            'label' => 'View photo ' . ($i + 1) . ' of ' . $imgCount,
                                            'more'  => ($i === 4 && $imgCount > 5) ? $imgCount - 5 : 0,
                                        ])
                                    @endforeach
                                </div>
                            @endif

                            {{-- Action bar --}}
                            <div class="mx-4 mt-1 flex items-stretch gap-1 border-t border-gray-200 py-1">

                                {{-- Like (handleLike in app.js toggles .like-btn state + .like-count) --}}
                                <button type="button"
                                        id="like-btn-{{ $post->id }}"
                                        data-post-id="{{ $post->id }}"
                                        data-likes="{{ $post->likes_count }}"
                                        onclick="handleLike(this)"
                                        class="like-btn flex h-11 flex-1 select-none items-center justify-center gap-2 rounded-lg text-sm font-semibold text-gray-500 transition hover:bg-gray-100 sm:h-10">
                                    <svg class="like-icon h-[18px] w-[18px]" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/>
                                        <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                                    </svg>
                                    <span>Like</span>
                                    <span class="like-count min-w-[1.25rem] rounded-full bg-gray-100 px-1.5 text-center text-[11px] font-bold leading-5 text-gray-600">{{ $post->likes_count }}</span>
                                </button>

                                {{-- Photos --}}
                                @if($imgCount > 0)
                                    <button type="button" @click="openAt(0)"
                                            class="flex h-11 flex-1 items-center justify-center gap-2 rounded-lg text-sm font-semibold text-gray-500 transition hover:bg-gray-100 hover:text-tpc-primary sm:h-10">
                                        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                        </svg>
                                        <span>{{ $imgCount }} <span class="hidden sm:inline">{{ Str::plural('Photo', $imgCount) }}</span></span>
                                    </button>
                                @endif

                                {{-- Share (opens the page-level share dialog below) --}}
                                <button type="button"
                                        @click="$dispatch('share-post', { title: @js($post->title), url: @js($shareUrl) })"
                                        class="flex h-11 flex-1 items-center justify-center gap-2 rounded-lg text-sm font-semibold text-gray-500 transition hover:bg-gray-100 hover:text-tpc-primary sm:h-10">
                                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                                    <span>Share</span>
                                </button>
                            </div>

                            {{-- ── Photo viewer (moved to <body> so nothing can clip it) ── --}}
                            @if($imgCount > 0)
                                <template x-teleport="body">
                                    <div x-show="open"
                                         x-transition.opacity.duration.150ms
                                         style="display:none"
                                         class="fixed inset-0 z-[999] flex flex-col bg-black lg:flex-row"
                                         role="dialog" aria-modal="true" aria-label="{{ $post->title }}">

                                        {{-- Photo stage --}}
                                        <div class="relative flex min-h-0 flex-1 flex-col">

                                            <button type="button" @click="close()" aria-label="Close viewer"
                                                    class="absolute left-3 top-3 z-20 rounded-full bg-black/55 p-2.5 text-white transition hover:bg-black/80">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>

                                            <div class="relative min-h-0 flex-1"
                                                 @touchstart.passive="touchX = $event.changedTouches[0].clientX"
                                                 @touchend.passive="swipe($event)">
                                                <img :src="photos[current].src"
                                                     :alt="photos[current].caption || ''"
                                                     draggable="false"
                                                     class="h-full w-full select-none object-contain">

                                                <button type="button" x-show="photos.length > 1" @click="prev()" aria-label="Previous photo"
                                                        class="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-2.5 text-white transition hover:bg-black/75 sm:left-4 sm:p-3">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                                    </svg>
                                                </button>

                                                <button type="button" x-show="photos.length > 1" @click="next()" aria-label="Next photo"
                                                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-2.5 text-white transition hover:bg-black/75 sm:right-4 sm:p-3">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </button>

                                                <div x-show="photos.length > 1"
                                                     class="absolute left-1/2 top-3 -translate-x-1/2 rounded-full bg-black/60 px-3 py-1 text-xs font-semibold tabular-nums text-white">
                                                    <span x-text="current + 1"></span> / <span x-text="photos.length"></span>
                                                </div>

                                                <p x-show="photos[current].caption"
                                                   x-text="photos[current].caption"
                                                   class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 to-transparent px-4 pb-3 pt-10 text-center text-sm text-white"></p>
                                            </div>

                                            @if($imgCount > 1)
                                                <div class="nf-thumbs flex shrink-0 gap-2 overflow-x-auto bg-black p-2.5">
                                                    @foreach($photos as $i => $p)
                                                        <button type="button"
                                                                id="nf-{{ $post->id }}-thumb-{{ $i }}"
                                                                @click="current = {{ $i }}"
                                                                :class="current === {{ $i }} ? 'opacity-100 ring-2 ring-white' : 'opacity-50 hover:opacity-80'"
                                                                class="h-14 w-14 shrink-0 overflow-hidden rounded-lg transition sm:h-16 sm:w-16"
                                                                aria-label="Show photo {{ $i + 1 }} of {{ $imgCount }}">
                                                            <img src="{{ $p['src'] }}" alt="" loading="lazy" class="h-full w-full object-cover">
                                                        </button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Post details panel (right on desktop, below the photo on phones) --}}
                                        <aside class="flex max-h-[42vh] shrink-0 flex-col bg-white lg:h-full lg:max-h-none lg:w-[380px]">

                                            <div class="flex shrink-0 items-center gap-3 border-b border-gray-100 px-4 py-3">
                                                <img src="{{ asset('images/TPC-Logo.png') }}" alt="TPC"
                                                     class="h-9 w-9 shrink-0 rounded-full bg-white object-contain p-0.5 ring-1 ring-gray-200">
                                                <div class="min-w-0 flex-1">
                                                    <p class="truncate text-sm font-bold text-gray-900">Talibon Polytechnic College</p>
                                                    <p class="truncate text-xs text-gray-500">
                                                        @if($post->published_at){{ $post->published_at->format('F d, Y') }} · @endif{{ $post->category }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="min-h-0 flex-1 overflow-y-auto px-4 py-3">
                                                <h3 class="nf-wrap text-base font-bold leading-snug text-gray-900">{{ $post->title }}</h3>
                                                @if($caption !== '')
                                                    <p class="nf-wrap mt-2 whitespace-pre-line text-[15px] leading-relaxed text-gray-700">{{ $caption }}</p>
                                                @endif
                                            </div>

                                            <div class="flex shrink-0 items-center gap-2 border-t border-gray-100 p-3">
                                                <button type="button" @click="toggleLike()"
                                                        :class="liked ? 'bg-tpc-primary/10 text-tpc-primary' : 'text-gray-600 hover:bg-gray-100'"
                                                        class="flex h-11 flex-1 select-none items-center justify-center gap-2 rounded-lg text-sm font-semibold transition">
                                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24"
                                                         :fill="liked ? 'currentColor' : 'none'"
                                                         :stroke="liked ? 'none' : 'currentColor'"
                                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/>
                                                        <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                                                    </svg>
                                                    <span>Like</span>
                                                    <span x-text="likes" class="min-w-[1.25rem] rounded-full bg-gray-100 px-1.5 text-center text-[11px] font-bold leading-5 text-gray-600"></span>
                                                </button>

                                                <button type="button"
                                                        @click="$dispatch('share-post', { title: @js($post->title), url: @js($shareUrl) })"
                                                        class="flex h-11 flex-1 items-center justify-center gap-2 rounded-lg bg-tpc-primary text-sm font-bold text-white transition hover:bg-tpc-secondary">
                                                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                                                    <span>Share</span>
                                                </button>
                                            </div>
                                        </aside>
                                    </div>
                                </template>
                            @endif

                        </article>

                    @endforeach
                </div>

                {{-- ── Share dialog (one for the whole feed; every post's Share button opens it) ── --}}
                <div x-data="{
                        open: false,
                        title: '',
                        url: '',
                        copied: false,
                        hint: '',
                        prevOverflow: '',
                        canNative: typeof navigator !== 'undefined' && !!navigator.share,

                        openShare(d) {
                            this.title = d.title;
                            this.url = d.url;
                            this.copied = false;
                            this.hint = '';
                            this.prevOverflow = document.body.style.overflow;
                            document.body.style.overflow = 'hidden';
                            window.__nfShareOpen = true;
                            this.open = true;
                        },
                        close() {
                            if (!this.open) return;
                            this.open = false;
                            window.__nfShareOpen = false;
                            document.body.style.overflow = this.prevOverflow;
                        },
                        enc(s) { return encodeURIComponent(s); },

                        async copy() {
                            try {
                                await navigator.clipboard.writeText(this.url);
                            } catch (_) {
                                const t = document.createElement('textarea');
                                t.value = this.url;
                                t.style.position = 'fixed';
                                t.style.opacity = '0';
                                document.body.appendChild(t);
                                t.select();
                                try { document.execCommand('copy'); } catch (__) {}
                                t.remove();
                            }
                            this.copied = true;
                            clearTimeout(this._t);
                            this._t = setTimeout(() => { this.copied = false; }, 2200);
                        },

                        // Instagram has no web link-share, so: copy the link and open Instagram to paste it
                        toInstagram() {
                            this.copy();
                            this.hint = 'Link copied! Paste it in your Instagram story, post or message.';
                            window.open('https://www.instagram.com/', '_blank', 'noopener');
                        },

                        // Phones (and some desktops): the device's own share sheet lists every installed app
                        async nativeShare() {
                            try {
                                await navigator.share({ title: this.title, url: this.url });
                                this.close();
                            } catch (_) {}
                        }
                    }"
                    @share-post.window="openShare($event.detail)"
                    @keydown.escape.window="close()"
                    @popstate.window="close()">

                    <template x-teleport="body">
                        <div x-show="open"
                             x-transition.opacity.duration.150ms
                             style="display:none"
                             class="fixed inset-0 z-[1000] flex items-end justify-center bg-black/60 sm:items-center sm:p-6"
                             @click.self="close()"
                             role="dialog" aria-modal="true" aria-label="Share this post">

                            <div class="w-full max-w-md overflow-hidden rounded-t-3xl bg-white pb-[env(safe-area-inset-bottom)] shadow-2xl sm:rounded-3xl sm:pb-0">

                                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                                    <h3 class="text-base font-bold text-gray-900">Share</h3>
                                    <button type="button" @click="close()" aria-label="Close"
                                            class="rounded-full p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="max-h-[80vh] overflow-y-auto p-5">

                                    <p class="nf-clamp nf-wrap mb-3 text-sm font-semibold leading-snug text-gray-800" x-text="title"></p>

                                    {{-- Copy link --}}
                                    <div class="mb-5 flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 p-1.5 pl-3">
                                        <input type="text" readonly :value="url" @focus="$event.target.select()"
                                               aria-label="Post link"
                                               class="min-w-0 flex-1 truncate border-0 bg-transparent p-0 text-xs text-gray-600 focus:outline-none focus:ring-0">
                                        <button type="button" @click="copy()"
                                                :class="copied ? 'bg-green-600' : 'bg-tpc-primary hover:bg-tpc-secondary'"
                                                class="shrink-0 rounded-lg px-4 py-2.5 text-xs font-bold text-white transition">
                                            <span x-text="copied ? 'Copied!' : 'Copy link'"></span>
                                        </button>
                                    </div>

                                    {{-- Apps --}}
                                    <div class="grid grid-cols-4 gap-x-2 gap-y-4">

                                        <a :href="'https://www.facebook.com/sharer/sharer.php?u=' + enc(url)"
                                           target="_blank" rel="noopener noreferrer" @click="close()"
                                           class="group flex flex-col items-center gap-1.5 text-center focus:outline-none">
                                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-[#1877F2] text-white shadow-sm transition group-hover:scale-105 group-focus-visible:ring-2 group-focus-visible:ring-tpc-primary sm:h-14 sm:w-14">
                                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                                                </svg>
                                            </span>
                                            <span class="text-[11px] font-semibold text-gray-600">Facebook</span>
                                        </a>

                                        <a :href="'https://mail.google.com/mail/?view=cm&amp;fs=1&amp;su=' + enc(title) + '&amp;body=' + enc(title + '\n\n' + url)"
                                           target="_blank" rel="noopener noreferrer" @click="close()"
                                           class="group flex flex-col items-center gap-1.5 text-center focus:outline-none">
                                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-red-500 text-white shadow-sm transition group-hover:scale-105 group-focus-visible:ring-2 group-focus-visible:ring-tpc-primary sm:h-14 sm:w-14">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                            </span>
                                            <span class="text-[11px] font-semibold text-gray-600">Gmail</span>
                                        </a>

                                        <button type="button" @click="toInstagram()"
                                                class="group flex flex-col items-center gap-1.5 text-center focus:outline-none">
                                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 text-white shadow-sm transition group-hover:scale-105 group-focus-visible:ring-2 group-focus-visible:ring-tpc-primary sm:h-14 sm:w-14">
                                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                                                </svg>
                                            </span>
                                            <span class="text-[11px] font-semibold text-gray-600">Instagram</span>
                                        </button>

                                        <a :href="'https://wa.me/?text=' + enc(title + ' ' + url)"
                                           target="_blank" rel="noopener noreferrer" @click="close()"
                                           class="group flex flex-col items-center gap-1.5 text-center focus:outline-none">
                                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-green-500 text-white shadow-sm transition group-hover:scale-105 group-focus-visible:ring-2 group-focus-visible:ring-tpc-primary sm:h-14 sm:w-14">
                                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                                                </svg>
                                            </span>
                                            <span class="text-[11px] font-semibold text-gray-600">WhatsApp</span>
                                        </a>

                                        <a :href="'https://twitter.com/intent/tweet?text=' + enc(title) + '&amp;url=' + enc(url)"
                                           target="_blank" rel="noopener noreferrer" @click="close()"
                                           class="group flex flex-col items-center gap-1.5 text-center focus:outline-none">
                                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-black text-white shadow-sm transition group-hover:scale-105 group-focus-visible:ring-2 group-focus-visible:ring-tpc-primary sm:h-14 sm:w-14">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.213 5.567zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                                </svg>
                                            </span>
                                            <span class="text-[11px] font-semibold text-gray-600">X</span>
                                        </a>

                                        <a :href="'https://t.me/share/url?url=' + enc(url) + '&amp;text=' + enc(title)"
                                           target="_blank" rel="noopener noreferrer" @click="close()"
                                           class="group flex flex-col items-center gap-1.5 text-center focus:outline-none">
                                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-sky-500 text-white shadow-sm transition group-hover:scale-105 group-focus-visible:ring-2 group-focus-visible:ring-tpc-primary sm:h-14 sm:w-14">
                                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <line x1="22" y1="2" x2="11" y2="13"/>
                                                    <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                                                </svg>
                                            </span>
                                            <span class="text-[11px] font-semibold text-gray-600">Telegram</span>
                                        </a>

                                        {{-- Any other app (Messenger, Viber, Threads, ...) via the device share sheet --}}
                                        <button type="button" x-show="canNative" @click="nativeShare()"
                                                class="group flex flex-col items-center gap-1.5 text-center focus:outline-none">
                                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-700 text-white shadow-sm transition group-hover:scale-105 group-focus-visible:ring-2 group-focus-visible:ring-tpc-primary sm:h-14 sm:w-14">
                                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                    <circle cx="5" cy="12" r="1.8"/>
                                                    <circle cx="12" cy="12" r="1.8"/>
                                                    <circle cx="19" cy="12" r="1.8"/>
                                                </svg>
                                            </span>
                                            <span class="text-[11px] font-semibold text-gray-600">More apps</span>
                                        </button>
                                    </div>

                                    <p x-show="hint" x-text="hint" aria-live="polite"
                                       class="mt-4 rounded-xl bg-tpc-primary/10 px-3 py-2 text-xs font-medium leading-relaxed text-tpc-primary"></p>
                                </div>
                            </div>
                        </div>
                    </template>
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

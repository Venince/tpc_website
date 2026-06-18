{{-- resources/views/public/news/show.blade.php --}}
@extends('layouts.site')

@section('title', $post->title)

@section('content')

    {{-- ══════════════════════════════════════
         ARTICLE HEADER
    ══════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-tpc-secondary">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0"
            style="background: radial-gradient(ellipse at 70% 50%, rgba(255,255,255,0.06) 0%, transparent 60%),
                                radial-gradient(ellipse at 20% 80%, rgba(0,0,0,0.15) 0%, transparent 50%)"></div>
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-[0.04]"
            style="background-image: linear-gradient(rgba(255,255,255,0.8) 1px, transparent 1px),
                                    linear-gradient(90deg, rgba(255,255,255,0.8) 1px, transparent 1px);
                    background-size: 40px 40px;"></div>
        <div class="relative mx-auto max-w-4xl px-4 pt-10 pb-16 sm:pt-14 sm:pb-20">
            <div class="flex flex-col items-start text-left">

                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-1.5 text-[10px] sm:text-xs font-medium text-white/60 mb-5 sm:mb-6" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                    <svg class="h-2.5 w-2.5 sm:h-3 sm:w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="{{ route('news.index') }}" class="hover:text-white transition">News</a>
                    <svg class="h-2.5 w-2.5 sm:h-3 sm:w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-white/40 truncate max-w-[120px] sm:max-w-xs">{{ $post->title }}</span>
                </nav>

                {{-- Category + date row --}}
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1 bg-white/15 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full border border-white/20">
                        <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                        {{ $post->category ?? 'Announcement' }}
                    </span>
                    @if($post->published_at)
                        <span class="inline-flex items-center gap-1.5 text-[11px] sm:text-xs text-white/60">
                            <svg class="h-3 w-3 sm:h-3.5 sm:w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                            </svg>
                            <time datetime="{{ $post->published_at->toDateString() }}">
                                {{ $post->published_at->format('F d, Y') }}
                            </time>
                        </span>
                    @endif
                    <span class="hidden sm:inline text-xs text-white/40">· Talibon Polytechnic College</span>
                </div>

                {{-- Title --}}
                <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-5xl leading-tight max-w-3xl break-words">
                    {{ $post->title }}
                </h1>

            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-8 sm:h-12">
                <path d="M0 48 C480 0 960 0 1440 48 L1440 48 L0 48 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- ══════════════════════════════════════
     ARTICLE BODY
══════════════════════════════════════ --}}
<section class="bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 py-8 sm:py-12">

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

            <div class="h-1 bg-gradient-to-r from-tpc-primary via-tpc-primary to-tpc-accent"></div>

            @php
                // Unified photo set: gallery photos, falling back to legacy single image_path
                $galleryImages = $post->galleryImages->pluck('image_path')->toArray();
                if ($post->image_path && empty($galleryImages)) {
                    $galleryImages = [$post->image_path];
                }
                $imgCount = count($galleryImages);
            @endphp

            <div
                @if($imgCount > 0)
                    x-data="{
                        open: false,
                        current: 0,
                        images: {{ json_encode(array_map(fn($p) => asset('storage/' . $p), $galleryImages)) }},
                        openAt(i) { this.current = i; this.open = true; document.body.style.overflow='hidden'; },
                        close()   { this.open = false; document.body.style.overflow=''; },
                        prev()    { this.current = (this.current - 1 + this.images.length) % this.images.length; },
                        next()    { this.current = (this.current + 1) % this.images.length; }
                    }"
                    @keydown.escape.window="close()"
                @endif
            >

                <div class="p-5 sm:p-7 lg:p-10">

                    {{-- ── Photos: every image, full integrity, nothing cropped or hidden ── --}}
                    @if($imgCount > 0)
                        <div class="mb-8 sm:mb-10">
                            <div class="flex items-center gap-3 mb-4 sm:mb-5">
                                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                                <h2 class="text-[10px] sm:text-xs font-bold tracking-widest text-tpc-primary uppercase">Photos</h2>
                                <div class="flex-1 h-px bg-gray-200"></div>
                                <span class="text-[10px] sm:text-xs text-gray-400 font-medium tabular-nums shrink-0">
                                    {{ $imgCount }} {{ Str::plural('photo', $imgCount) }}
                                </span>
                            </div>

                            @if($imgCount === 1)
                                <button type="button" @click="openAt(0)"
                                        class="block w-full rounded-xl overflow-hidden bg-gray-50 border border-gray-100 cursor-pointer">
                                    <img src="{{ asset('storage/' . $galleryImages[0]) }}"
                                        class="object-contain mx-auto hover:opacity-95 transition"
                                        alt="{{ $post->title }}" loading="lazy">
                                </button>
                            @else
                                @php
                                    // Only go to 3 columns once there are enough photos to fill them evenly —
                                    // otherwise one column ends up stranded with a single tall photo while
                                    // another sits empty below a short one.
                                    $galleryColumnClass = $imgCount >= 5 ? 'columns-2 sm:columns-3' : 'columns-2';
                                @endphp
                                <div class="{{ $galleryColumnClass }} gap-1.5 sm:gap-2">
                                    @foreach($galleryImages as $idx => $path)
                                        <button type="button" @click="openAt({{ $idx }})"
                                                class="block w-full mb-1.5 sm:mb-2 break-inside-avoid rounded-xl overflow-hidden bg-gray-50 border border-gray-100 cursor-pointer">
                                            <img src="{{ asset('storage/' . $path) }}"
                                                class="w-full h-auto max-h-[28rem] object-contain mx-auto hover:opacity-90 transition duration-200"
                                                alt="{{ $post->title }}" loading="lazy">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Pull quote / excerpt --}}
                    @if($post->excerpt)
                        <div class="border-l-4 border-tpc-primary bg-tpc-primary/5 px-4 sm:px-5 py-3 sm:py-4 mb-6 sm:mb-8 rounded-r-xl">
                            <p class="text-xs sm:text-sm text-gray-700 leading-relaxed">{{ $post->excerpt }}</p>
                        </div>
                    @endif

                    {{-- Body text --}}
                    <article class="prose prose-sm sm:prose max-w-none text-gray-700 leading-relaxed text-justify
                                prose-headings:text-gray-900 prose-headings:font-bold
                                prose-a:text-tpc-primary prose-a:font-medium hover:prose-a:text-tpc-secondary
                                prose-strong:text-gray-900
                                prose-blockquote:border-tpc-primary prose-blockquote:text-gray-600">
                        {!! nl2br(e($post->body)) !!}
                    </article>

                    {{-- Tags / meta footer --}}
                    @if($post->published_at)
                        <div class="mt-8 sm:mt-10 pt-5 sm:pt-6 border-t border-gray-100 flex flex-wrap items-center gap-2 sm:gap-3 text-[10px] sm:text-xs text-gray-400">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="h-3 w-3 sm:h-3.5 sm:w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                                </svg>
                                Published {{ $post->published_at->format('F d, Y') }}
                            </span>
                            <span>·</span>
                            <span class="hidden sm:inline">Talibon Polytechnic College</span>
                            <span class="ml-auto inline-block bg-tpc-primary/10 text-tpc-primary text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                {{ $post->category ?? 'Announcement' }}
                            </span>
                        </div>
                    @endif

                    {{-- Like button --}}
                    <div class="mt-4 sm:mt-6 flex items-center gap-3">
                        <button
                            type="button"
                            data-post-id="{{ $post->id }}"
                            data-likes="{{ $post->likes_count }}"
                            onclick="handleLike(this)"
                            class="like-btn inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-bold
                                    border border-gray-200 text-gray-500 hover:border-tpc-primary hover:text-tpc-primary
                                    hover:bg-tpc-primary/10 transition-all duration-200 select-none">
                            <svg class="like-icon h-3.5 w-3.5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/>
                                <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                            </svg>
                            <span class="like-count">{{ $post->likes_count }}</span>
                            <span class="like-label">Like</span>
                        </button>
                    </div>

                    {{-- Divider --}}
                    <div class="my-6 sm:my-8 border-t border-gray-100"></div>

                    {{-- ── Footer CTA ── --}}
                    <div class="relative bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                        <div class="h-1 bg-gradient-to-r from-tpc-primary to-tpc-accent"></div>
                        <div class="p-4 sm:p-6 sm:flex sm:items-center sm:justify-between gap-6">
                            <div>
                                <p class="text-[10px] sm:text-xs font-bold text-tpc-primary uppercase tracking-widest mb-1">Need Assistance?</p>
                                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                                    For questions regarding this announcement, please contact the college office.
                                </p>
                            </div>
                            <div class="mt-3 sm:mt-0 flex flex-wrap gap-2 sm:gap-3 shrink-0">
                                <a href="{{ route('contact') }}"
                                class="inline-flex items-center gap-1.5 sm:gap-2 rounded-full border-2 border-tpc-primary bg-tpc-primary px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-white
                                        hover:bg-tpc-secondary hover:border-tpc-secondary transition touch-manipulation">
                                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Contact Us
                                </a>
                                <a href="{{ route('academics') }}"
                                class="inline-flex items-center gap-1 rounded-full border-2 border-tpc-primary px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-tpc-primary
                                        hover:bg-tpc-primary hover:text-white transition touch-manipulation">
                                    View Programs
                                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ── Lightbox (tap any photo for a closer look) ── --}}
                @if($imgCount > 0)
                    <template x-teleport="body">
                        <div x-show="open"
                             x-transition.opacity.duration.200ms
                             class="fixed inset-0 z-[999] flex items-center justify-center bg-black/90"
                             @click.self="close()"
                             style="display:none">

                            <button @click="close()"
                                    class="absolute top-4 right-4 text-white/70 hover:text-white transition z-10">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>

                            <button x-show="images.length > 1"
                                    @click="prev()"
                                    class="absolute left-3 text-white/70 hover:text-white transition z-10 bg-black/30 rounded-full p-2">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>

                            <img :src="images[current]"
                                 class="max-h-[85vh] max-w-[90vw] rounded-lg object-contain shadow-2xl select-none"
                                 alt="">

                            <button x-show="images.length > 1"
                                    @click="next()"
                                    class="absolute right-3 text-white/70 hover:text-white transition z-10 bg-black/30 rounded-full p-2">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>

                            <div x-show="images.length > 1"
                                 class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/60 text-sm font-semibold tabular-nums">
                                <span x-text="current + 1"></span> / <span x-text="images.length"></span>
                            </div>

                            <div x-show="images.length > 1 && images.length <= 10"
                                 class="absolute bottom-10 left-1/2 -translate-x-1/2 flex gap-1.5">
                                <template x-for="(img, i) in images" :key="i">
                                    <button @click="current = i"
                                            :class="i === current ? 'bg-white w-4' : 'bg-white/40 w-2'"
                                            class="h-2 rounded-full transition-all duration-200">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                @endif

            </div>

        {{-- Back navigation --}}
        <div class="mt-6 sm:mt-8 flex items-center justify-between">
            <a href="{{ route('news.index') }}"
               class="inline-flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm font-bold text-tpc-primary hover:text-tpc-secondary transition group touch-manipulation">
                <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 transition-transform group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to all news
            </a>
            <a href="{{ route('news.index') }}"
               class="hidden sm:inline-flex items-center gap-1.5 text-xs font-bold text-gray-400 hover:text-tpc-primary transition uppercase tracking-wide">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v8a2 2 0 01-2 2z"/>
                </svg>
                More announcements
            </a>
        </div>

    </div>
</section>

@endsection

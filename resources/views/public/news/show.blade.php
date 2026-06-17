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
            <div class="flex flex-col items-center text-center">

                {{-- Breadcrumb --}}
                <nav class="flex items-center justify-center gap-1.5 text-[10px] sm:text-xs font-medium text-white/60 mb-5 sm:mb-6" aria-label="Breadcrumb">
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
                <div class="flex flex-wrap items-center justify-center gap-2 mb-4">
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
                <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-5xl leading-tight max-w-3xl">
                    {{ $post->title }}
                </h1>

                @if($post->excerpt)
                    <p class="mt-4 max-w-lg text-sm text-white/60 leading-relaxed">
                        {{ $post->excerpt }}
                    </p>
                @endif

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

                {{-- ── Featured Image ── --}}
                @if($post->image_path)
                    <div class="relative bg-gray-50 border-b border-gray-100">
                        {{-- Accent top bar --}}
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-tpc-primary via-tpc-primary to-tpc-accent z-10"></div>

                        <div class="flex items-center justify-center p-4 sm:p-6 pt-5 sm:pt-7">
                            <a href="{{ asset('storage/' . $post->image_path) }}"
                               target="_blank" rel="noopener"
                               class="group relative block w-full">
                                <img src="{{ asset('storage/' . $post->image_path) }}"
                                     alt="{{ $post->title }}"
                                     class="w-full object-contain rounded-xl hover:opacity-95 transition"
                                     loading="lazy" />
                                {{-- View hint overlay --}}
                                <span class="absolute inset-0 rounded-xl flex items-end justify-end p-2 sm:p-3 opacity-0 group-hover:opacity-100 transition">
                                    <span class="inline-flex items-center gap-1 bg-black/60 text-white text-[10px] font-bold uppercase tracking-wide px-2 py-1 rounded-lg backdrop-blur-sm">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        View full size
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                @else
                    {{-- No-image: just show the accent bar --}}
                    <div class="h-1 bg-gradient-to-r from-tpc-primary via-tpc-primary to-tpc-accent"></div>
                @endif

                {{-- ── Article content ── --}}
                <div class="p-5 sm:p-7 lg:p-10">

                    {{-- Pull quote / excerpt (if no image and has excerpt) --}}
                    @if($post->excerpt && !$post->image_path)
                        <div class="relative border-l-4 border-tpc-primary bg-tpc-primary/5 px-4 sm:px-6 py-4 sm:py-5 mb-6 sm:mb-8 rounded-r-xl">
                            <svg class="absolute -top-1 -left-1 h-4 w-4 sm:h-5 sm:w-5 text-tpc-primary/30" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                            <p class="text-xs sm:text-sm font-semibold text-gray-700 leading-relaxed italic">{{ $post->excerpt }}</p>
                        </div>
                    @endif

                    {{-- Body text --}}
                    <article class="prose prose-sm sm:prose max-w-none text-gray-700 leading-relaxed
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

                    {{-- ── Photo Gallery ── --}}
                    @if($post->galleryImages->isNotEmpty())
                        @php $gallery = $post->galleryImages; @endphp
                        <div class="mt-8 sm:mt-10"
                            x-data
                            x-init="Alpine.store('gallery').init(JSON.parse($el.dataset.paths))"
                            data-paths="{{ $gallery->pluck('image_path')->toJson() }}">

                            {{-- Section header --}}
                            <div class="flex items-center gap-3 mb-4">
                                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                                <h3 class="text-sm font-bold text-gray-900">Photo Gallery</h3>
                                <span class="inline-flex items-center gap-1 bg-tpc-primary/10 text-tpc-primary text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                    </svg>
                                    {{ $gallery->count() }} {{ Str::plural('photo', $gallery->count()) }}
                                </span>
                            </div>

                            {{-- Grid --}}
                            @php
                                $count = $gallery->count();
                                $gridClass = match(true) {
                                    $count === 1 => 'grid-cols-1',
                                    $count === 2 => 'grid-cols-2',
                                    default      => 'grid-cols-2 sm:grid-cols-3',
                                };
                            @endphp
                            <div class="grid {{ $gridClass }} gap-2">
                                @foreach($gallery as $i => $img)
                                    @php
                                        // Make first image span 2 cols when there are 3+ images
                                        $spanClass = ($i === 0 && $count >= 3) ? 'col-span-2 sm:col-span-1' : '';
                                        $aspectClass = ($i === 0 && $count >= 3) ? 'aspect-video sm:aspect-square' : 'aspect-square';
                                    @endphp
                                    <button type="button"
                                            @click="$store.gallery.open({{ $i }})"
                                            class="group relative {{ $spanClass }} {{ $aspectClass }}
                                                   rounded-xl overflow-hidden border border-gray-200
                                                   bg-gray-100 focus:outline-none focus:ring-2 focus:ring-tpc-primary/40">
                                        <img src="{{ asset('storage/' . $img->image_path) }}"
                                             alt="{{ $img->caption ?? ($post->title . ' photo ' . ($i + 1)) }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                             loading="lazy">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/15 transition-colors duration-300 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-lg"
                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607ZM10.5 7.5v6m3-3h-6"/>
                                            </svg>
                                        </div>
                                        @if($img->caption)
                                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent px-3 py-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <p class="text-white text-xs leading-snug">{{ $img->caption }}</p>
                                            </div>
                                        @endif
                                    </button>
                                @endforeach
                            </div>

                        </div>
                    @endif

                    {{-- Divider --}}

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
                        {{-- Accent stripe --}}
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

    {{-- ── Lightbox Portal ── --}}
    @if($post->galleryImages->isNotEmpty())
        @push('portal')
        <div x-data
             x-show="$store.gallery.isOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 backdrop-blur-sm p-4"
             @click.self="$store.gallery.close()"
             @keydown.escape.window="$store.gallery.close()"
             @keydown.arrow-left.window="$store.gallery.prev()"
             @keydown.arrow-right.window="$store.gallery.next()"
             style="display:none">

            <button @click="$store.gallery.close()" type="button"
                    class="absolute top-4 right-4 z-10 h-9 w-9 rounded-full bg-white/10 hover:bg-white/20
                           text-white flex items-center justify-center transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="absolute top-4 left-1/2 -translate-x-1/2 z-10 bg-white/10 text-white text-xs font-bold px-3 py-1 rounded-full">
                <span x-text="$store.gallery.current + 1"></span> / <span x-text="$store.gallery.images.length"></span>
            </div>

            <button @click="$store.gallery.prev()" type="button" x-show="$store.gallery.images.length > 1"
                    class="absolute left-3 sm:left-6 z-10 h-10 w-10 rounded-full bg-white/10 hover:bg-white/25
                           text-white flex items-center justify-center transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <img :src="$store.gallery.currentUrl"
                 :alt="'Photo ' + ($store.gallery.current + 1)"
                 class="max-h-[85vh] max-w-full rounded-xl object-contain shadow-2xl select-none"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">

            <button @click="$store.gallery.next()" type="button" x-show="$store.gallery.images.length > 1"
                    class="absolute right-3 sm:right-6 z-10 h-10 w-10 rounded-full bg-white/10 hover:bg-white/25
                           text-white flex items-center justify-center transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
        @endpush
    @endif

@endsection

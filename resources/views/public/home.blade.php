{{-- resources/views/public/home.blade.php --}}
@extends('layouts.site')

@section('title', 'Home')

@section('content')

    {{-- ══════════════════════════════════════
        MASTHEAD VIDEO BANNER
    ══════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-gray-950">

        {{-- Video --}}
        <video
            class="w-full object-cover block"
            style="max-height: 620px; min-height: 380px;"
            autoplay
            muted
            loop
            playsinline
            preload="metadata">
            <source src="{{ asset('videos/tpc-campus.mp4') }}" type="video/mp4">
        </video>

        {{-- Overlay --}}
        <div class="absolute inset-0 pointer-events-none"
            style="background: linear-gradient(to top, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.40) 45%, rgba(0,0,0,0.20) 100%);">
        </div>

        {{-- Centered content --}}
        <div class="absolute inset-0 flex flex-col items-center justify-center z-10 text-center px-4 sm:px-6">

            <img src="{{ asset('images/TPC-Logo.png') }}"
                alt="Talibon Polytechnic College Logo"
                class="h-20 w-20 sm:h-32 sm:w-32 object-contain drop-shadow-lg -mt-6 sm:-mt-10 mb-3 sm:mb-5">

            <h1 class="text-2xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight drop-shadow-lg px-2">
                TALIBON POLYTECHNIC COLLEGE
            </h1>
            <p class="mt-2 sm:mt-4 text-xs sm:text-base text-white/70 font-medium tracking-wide">
                San Isidro, Talibon, Bohol
            </p>
            <p class="mt-2 sm:mt-3 text-sm sm:text-lg text-white/75 max-w-xl leading-relaxed">
                A student-centered institution committed to quality education, research, and community development in Talibon, Bohol.
            </p>

            {{-- Explore Programs and Admission Guide buttons --}}
            {{-- <div class="mt-4 sm:mt-8 flex flex-col xs:flex-row flex-wrap gap-2 sm:gap-3 justify-center w-full max-w-xs xs:max-w-none">
                <a href="{{ route('academics') }}"
                class="inline-flex items-center justify-center gap-1.5 rounded-full border-2 border-white bg-white/95 backdrop-blur-sm px-5 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold text-tpc-primary shadow-lg transition hover:bg-tpc-primary hover:border-tpc-primary hover:text-white">
                    Explore Programs
                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="{{ route('admission') }}"
                class="inline-flex items-center justify-center rounded-full border-2 border-white/90 bg-black/25 backdrop-blur-sm px-5 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold text-white shadow-lg transition hover:bg-tpc-primary hover:border-tpc-primary hover:text-white">
                    Admission Guide
                </a>
            </div> --}}

        </div>

        {{-- Wave divider --}}
        <div class="absolute bottom-0 left-0 right-0 z-10" style="margin-bottom: -2px;">
            <svg viewBox="0 0 1440 50" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full block" style="height: 50px; display: block;">
                <path d="M0 50 C360 0 1080 0 1440 50 L1440 50 L0 50 Z" fill="white"/>
            </svg>
        </div>

    </section>

    {{-- ══════════════════════════════════════
         ABOUT – CAROUSEL
    ══════════════════════════════════════ --}}
    <section id="about" class="scroll-mt-20 bg-white overflow-hidden border-b border-gray-200">

        <div class="max-w-7xl mx-auto px-4 pt-10 pb-2 flex items-center gap-4">
            <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
            <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">About</h2>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        @if($aboutSlides->isNotEmpty())
        <div class="relative select-none py-6 sm:py-8" id="about-carousel">

            <div id="about-track"
                 class="relative mx-auto flex items-center justify-center overflow-visible"
                 style="height: 220px; max-width: 900px;">

                @foreach($aboutSlides as $i => $slide)
                <div class="about-slide absolute rounded-2xl overflow-hidden shadow-lg cursor-pointer"
                     data-index="{{ $i }}"
                     data-url="{{ route('about.show', $slide) }}"
                     style="width: 560px; max-width: 82vw; will-change: transform, opacity;">
                    <img src="{{ asset('storage/' . $slide->image_path) }}"
                         alt="About TPC"
                         class="w-full object-cover block pointer-events-none bg-gray-100"
                         style="height: 190px;"
                         loading="lazy"
                         draggable="false">
                </div>
                @endforeach
            </div>

            <button id="about-prev"
                    class="absolute left-2 sm:left-8 top-1/2 -translate-y-1/2 z-30
                           h-9 w-9 sm:h-10 sm:w-10 rounded-full bg-white border border-gray-200 shadow
                           text-gray-600 hover:text-tpc-primary hover:border-tpc-primary
                           flex items-center justify-center transition touch-manipulation"
                    aria-label="Previous slide">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button id="about-next"
                    class="absolute right-2 sm:right-8 top-1/2 -translate-y-1/2 z-30
                           h-9 w-9 sm:h-10 sm:w-10 rounded-full bg-white border border-gray-200 shadow
                           text-gray-600 hover:text-tpc-primary hover:border-tpc-primary
                           flex items-center justify-center transition touch-manipulation"
                    aria-label="Next slide">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <div class="flex justify-center gap-2 pt-2" id="about-dots">
                @foreach($aboutSlides as $i => $slide)
                    <button class="about-dot transition-all duration-300 rounded-full h-2 touch-manipulation"
                            data-index="{{ $i }}"
                            aria-label="Slide {{ $i + 1 }}"></button>
                @endforeach
            </div>
        </div>

        <style>
            .about-slide {
                transition: transform 0.45s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                            opacity  0.45s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                            z-index  0s;
            }
            .about-slide[data-pos="0"]  { transform: translateX(0) scale(1) rotateY(0deg); opacity: 1; z-index: 20; }
            .about-slide[data-pos="-1"] { transform: translateX(-62%) scale(0.78) rotateY(18deg); opacity: 0.55; z-index: 10; }
            .about-slide[data-pos="1"]  { transform: translateX(62%) scale(0.78) rotateY(-18deg); opacity: 0.55; z-index: 10; }
            .about-slide[data-pos="-2"] { transform: translateX(-100%) scale(0.6) rotateY(28deg); opacity: 0.25; z-index: 5; }
            .about-slide[data-pos="2"]  { transform: translateX(100%) scale(0.6) rotateY(-28deg); opacity: 0.25; z-index: 5; }
            .about-slide[data-pos="hidden"] { transform: translateX(0) scale(0.5); opacity: 0; z-index: 0; pointer-events: none; }
            .about-dot { background-color: #d1d5db; width: 0.5rem; }
            .about-dot.is-active { background-color: #166534; width: 1.5rem; }
            #about-track { perspective: 1200px; }

            @media (min-width: 640px) {
                #about-track { height: 320px !important; }
                .about-slide img { height: 280px !important; }
            }
        </style>

        <script>
        function initAboutCarousel() {
            const slides = Array.from(document.querySelectorAll('.about-slide'));
            const dots   = Array.from(document.querySelectorAll('.about-dot'));
            const total  = slides.length;
            if (total === 0) return;

            if (window._aboutCarouselTimer) {
                clearInterval(window._aboutCarouselTimer);
                window._aboutCarouselTimer = null;
            }

            let current = 0;

            function posFor(slideIdx, active) {
                let diff = slideIdx - active;
                if (diff > Math.floor(total / 2))  diff -= total;
                if (diff < -Math.floor(total / 2)) diff += total;
                if (diff < -2 || diff > 2) return 'hidden';
                return String(diff);
            }

            function render() {
                slides.forEach((el, i) => { el.dataset.pos = posFor(i, current); });
                dots.forEach((d, i) => { d.classList.toggle('is-active', i === current); });
            }

            function goTo(idx) { current = ((idx % total) + total) % total; render(); }
            function next() { goTo(current + 1); }
            function prev() { goTo(current - 1); }

            function startAutoplay() {
                if (window._aboutCarouselTimer) clearInterval(window._aboutCarouselTimer);
                window._aboutCarouselTimer = setInterval(next, 4000);
            }

            document.getElementById('about-next')?.addEventListener('click', () => { next(); startAutoplay(); });
            document.getElementById('about-prev')?.addEventListener('click', () => { prev(); startAutoplay(); });
            dots.forEach(d => { d.addEventListener('click', () => { goTo(+d.dataset.index); startAutoplay(); }); });
            slides.forEach((el, i) => {
                el.addEventListener('click', () => {
                    if (el.dataset.pos !== '0') {
                        goTo(i); startAutoplay();
                    } else {
                        window.location.href = el.dataset.url;
                    }
                });
            });

            let touchStartX = 0;
            const track = document.getElementById('about-track');
            track?.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
            track?.addEventListener('touchend', e => {
                const dx = e.changedTouches[0].clientX - touchStartX;
                if (Math.abs(dx) > 40) { dx < 0 ? next() : prev(); startAutoplay(); }
            }, { passive: true });

            render();
            startAutoplay();
        }

        window.initAboutCarousel = initAboutCarousel;
        initAboutCarousel();
        </script>

        @endif

        {{-- Vision / Mission --}}
        <div class="max-w-7xl mx-auto px-4 pb-12">
            <div class="grid gap-4 sm:gap-5 sm:grid-cols-2">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="h-1.5 bg-tpc-primary"></div>
                    <div class="p-5 sm:p-7">
                        <p class="text-xs font-bold tracking-widest text-tpc-primary uppercase mb-3">Vision</p>
                        <p class="text-sm sm:text-base leading-relaxed text-gray-700">
                            An institution committed to provide quality educational opportunities and academic programs
                            that empower students to become competitive and responsive to the needs of the community.
                        </p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="h-1.5 bg-tpc-primary"></div>
                    <div class="p-5 sm:p-7">
                        <p class="text-xs font-bold tracking-widest text-tpc-primary uppercase mb-3">Mission</p>
                        <p class="text-sm sm:text-base leading-relaxed text-gray-700">
                            To provide quality educational opportunities and support services, strengthen innovative
                            research and extension, and produce competent individuals in technological and professional
                            fields for community transformation and development.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         NEWS
    ══════════════════════════════════════ --}}
    <section class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-10 sm:py-14">

            {{-- Section header --}}
            <div class="flex items-end justify-between gap-4 mb-8 sm:mb-10">
                <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                    <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold tracking-widest text-tpc-primary/60 uppercase mb-0.5">TPC Updates</p>
                        <h2 class="text-lg sm:text-2xl font-bold text-gray-900 leading-tight">Latest News &amp; Announcements</h2>
                    </div>
                </div>
                <a href="{{ route('news.index') }}"
                   class="shrink-0 inline-flex items-center gap-1 text-xs font-bold text-tpc-primary hover:text-tpc-secondary uppercase tracking-wide transition group">
                    View All
                    <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            @if($latestNews->isNotEmpty())

                @php $featured = $latestNews->first(); @endphp

                {{-- ── FEATURED POST ── --}}
                <a href="{{ route('news.show', $featured) }}"
                   class="news-featured-card group relative block bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-5
                          hover:shadow-lg hover:border-tpc-primary/30 transition-all duration-300">

                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-tpc-primary via-tpc-primary to-tpc-accent z-10"></div>

                    <div class="sm:flex min-h-[220px] sm:min-h-[260px]">
                        @if($featured->image_path)
                            <div class="relative sm:w-[42%] bg-gray-100 overflow-hidden shrink-0">
                                <img src="{{ asset('storage/' . $featured->image_path) }}"
                                     alt="{{ $featured->title }}"
                                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500"
                                     loading="lazy" />
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/10 hidden sm:block"></div>
                                <div class="sm:hidden h-44"></div>
                            </div>
                        @endif

                        <div class="flex-1 flex flex-col justify-between p-4 sm:p-8">
                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="inline-flex items-center gap-1 bg-tpc-primary text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                                        <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                                        {{ $featured->category ?? 'Announcement' }}
                                    </span>
                                    <span class="inline-block bg-tpc-accent/20 text-tpc-primary text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                                        Latest
                                    </span>
                                    @if($featured->published_at)
                                        <time class="text-xs text-gray-400 font-medium sm:ml-auto" datetime="{{ $featured->published_at->toDateString() }}">
                                            {{ $featured->published_at->format('M d, Y') }}
                                        </time>
                                    @endif
                                </div>

                                <h3 class="text-base sm:text-2xl font-bold text-gray-900 group-hover:text-tpc-primary transition-colors duration-200 leading-snug mb-2 sm:mb-3">
                                    {{ $featured->title }}
                                </h3>

                                <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 sm:line-clamp-3">
                                    {{ $featured->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($featured->body), 180) }}
                                </p>
                            </div>

                            <div class="mt-4 sm:mt-6 flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 text-sm font-bold text-tpc-primary group-hover:gap-3 transition-all duration-200">
                                    Read Article
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- ── SECONDARY POSTS GRID ── --}}
                @if($latestNews->count() > 1)
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($latestNews->skip(1) as $post)
                        <article class="news-card group bg-white rounded-2xl border border-gray-200 shadow-sm
                                        hover:shadow-md hover:border-tpc-primary/30 hover:-translate-y-0.5
                                        transition-all duration-300 overflow-hidden flex flex-col">

                            @if($post->image_path)
                                <a href="{{ route('news.show', $post) }}" class="block shrink-0 bg-gray-50 overflow-hidden relative"
                                   style="height: 160px;">
                                    <img src="{{ asset('storage/' . $post->image_path) }}"
                                         alt="{{ $post->title }}"
                                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500"
                                         loading="lazy" />
                                    <span class="absolute top-3 left-3 inline-block bg-tpc-primary/90 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full">
                                        {{ $post->category ?? 'Announcement' }}
                                    </span>
                                </a>
                            @else
                                <div class="shrink-0 relative overflow-hidden flex items-center justify-center bg-tpc-primary/5"
                                     style="height: 72px;">
                                    <svg class="absolute inset-0 w-full h-full opacity-[0.04]" xmlns="http://www.w3.org/2000/svg">
                                        <defs><pattern id="dots-{{ $loop->index }}" x="0" y="0" width="16" height="16" patternUnits="userSpaceOnUse">
                                            <circle cx="2" cy="2" r="1.5" fill="#166534"/>
                                        </pattern></defs>
                                        <rect width="100%" height="100%" fill="url(#dots-{{ $loop->index }})"/>
                                    </svg>
                                    <span class="relative inline-block bg-tpc-primary/90 text-white text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full">
                                        {{ $post->category ?? 'Announcement' }}
                                    </span>
                                </div>
                            @endif

                            <div class="p-4 sm:p-5 flex flex-col flex-1">
                                @if($post->published_at)
                                    <time class="text-[11px] text-gray-400 font-medium mb-2 block">
                                        {{ $post->published_at->format('M d, Y') }}
                                    </time>
                                @endif

                                <h4 class="text-sm font-bold text-gray-800 group-hover:text-tpc-primary transition-colors duration-200 leading-snug flex-1 line-clamp-3">
                                    <a href="{{ route('news.show', $post) }}">{{ $post->title }}</a>
                                </h4>

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

            @else
                <div class="py-20 text-center text-gray-400 text-sm border border-dashed border-gray-300 rounded-2xl bg-white">
                    No news posts published yet.
                </div>
            @endif
        </div>
    </section>

    {{-- ══════════════════════════════════════
         PROGRAMS
    ══════════════════════════════════════ --}}
    <section class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-10 sm:py-14">

            <div class="flex items-center gap-4 mb-8 sm:mb-10">
                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">Academic Programs</h2>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            @php $programCount = $programs->count(); @endphp

            <div class="grid gap-4 sm:gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($programs as $program)
                    @php
                        $isLast          = $loop->last;
                        $lastRowRemainder = $programCount % 3;
                        $smRem           = $programCount % 2;
                        $lgCenter        = ($isLast && $lastRowRemainder === 1) ? 'lg:col-start-2' : '';
                        $smCenter        = ($isLast && $smRem === 1 && $lastRowRemainder !== 1) ? 'sm:col-start-1 sm:col-span-2 sm:max-w-sm sm:mx-auto sm:w-full' : '';
                    @endphp

                    <a href="{{ route('academics.show', $program) }}"
                        class="program-card group relative bg-white rounded-2xl border border-gray-200 shadow-sm
                                overflow-hidden flex flex-col {{ $lgCenter }} {{ $smCenter }}">

                        {{-- Top accent bar --}}
                        <div class="h-1.5 w-full bg-tpc-primary group-hover:bg-tpc-accent transition-colors duration-300"></div>

                        <div class="p-4 sm:p-6 flex flex-col flex-1">

                            {{-- Logo + code + name --}}
                            <div class="flex items-start gap-3 sm:gap-4 mb-3 sm:mb-4">
                                <div class="program-logo-wrap shrink-0 h-12 w-12 sm:h-16 sm:w-16 rounded-xl flex items-center justify-center
                                            {{ $program->logo_path ? 'p-1.5 sm:p-2' : 'bg-tpc-primary/5 border border-tpc-primary/10' }}">
                                    @if($program->logo_path)
                                        <img src="{{ asset('storage/' . $program->logo_path) }}"
                                            alt="{{ $program->code }} logo"
                                            class="h-full w-full object-contain" loading="lazy">
                                    @else
                                        <span class="text-xl sm:text-2xl">🎓</span>
                                    @endif
                                </div>
                                <div class="min-w-0 pt-0.5 sm:pt-1">
                                    <span class="inline-block bg-tpc-primary/10 text-tpc-primary text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-full mb-1">
                                        {{ $program->code ?? 'Program' }}
                                    </span>
                                    <h3 class="text-xs sm:text-sm font-bold text-gray-800 group-hover:text-tpc-primary transition-colors leading-snug">
                                        {{ $program->name }}
                                    </h3>
                                    @if($program->department)
                                        <p class="mt-0.5 text-[11px] sm:text-xs text-gray-400">{{ $program->department }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Description --}}
                            @if($program->description)
                                <p class="text-[11px] sm:text-xs text-gray-500 leading-relaxed line-clamp-3 flex-1">
                                    {{ $program->description }}
                                </p>
                            @else
                                <div class="flex-1"></div>
                            @endif

                            {{-- Footer arrow --}}
                            <div class="mt-4 sm:mt-5 pt-3 sm:pt-4 border-t border-gray-100 flex items-center justify-end">
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-tpc-primary group-hover:gap-2 transition-all duration-200">
                                    View Program
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>

                        </div>
                    </a>
                @empty
                    <div class="col-span-3 py-16 text-center text-gray-400 text-sm border border-dashed border-gray-300 rounded-2xl">
                        No programs added yet.
                    </div>
                @endforelse
            </div>

            <div class="mt-6 sm:mt-8 max-w-2xl mx-auto">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="h-1.5 bg-tpc-accent"></div>
                    <div class="p-5 sm:p-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-bold text-gray-800">Explore programs before enrolling</p>
                            <p class="mt-1 text-sm text-gray-500">View all academic programs offered by Talibon Polytechnic College.</p>
                        </div>
                        <div class="flex gap-2 sm:gap-3 shrink-0">
                            <a href="{{ route('academics') }}"
                               class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-full border-2 border-tpc-primary bg-tpc-primary px-4 sm:px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                View Programs
                            </a>
                            <a href="{{ route('news.index') }}"
                               class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-full border-2 border-tpc-primary px-4 sm:px-5 py-2.5 text-sm font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
                                Updates →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

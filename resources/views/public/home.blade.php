{{-- resources/views/public/home.blade.php --}}
@extends('layouts.site')

@section('title', 'Home')

@section('content')

    {{-- ══════════════════════════════════════
        MASTHEAD BANNER
    ══════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-tpc-primary">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/school-bg.jpg') }}" alt="" aria-hidden="true"
                class="h-full w-full object-cover object-center opacity-20">
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 py-16 lg:py-24 text-center">
            {{-- <span class="inline-block bg-white/15 backdrop-blur-sm text-tpc-accent text-xs font-bold tracking-widest uppercase px-3 py-1.5 rounded-full mb-4">
                Official College Website
            </span> --}}
            <div class="flex justify-center mb-5 -mt-10">
                <img src="{{ asset('images/TPC-Logo.png') }}"
                    alt="Talibon Polytechnic College Logo"
                    class="h-20 w-20 object-contain drop-shadow-lg">
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                TALIBON POLYTECHNIC COLLEGE
            </h1>
            <p class="mt-4 text-base text-white/80 max-w-xl leading-relaxed mx-auto">
                A student-centered institution committed to quality education, research, and community development in Talibon, Bohol.
            </p>
            <div class="mt-8 flex flex-wrap gap-3 justify-center">
                <a href="{{ route('academics') }}"
                class="inline-flex items-center gap-2 rounded-l-full border-2 border-white bg-white px-6 py-3 text-sm font-bold text-tpc-primary transition hover:bg-tpc-accent hover:border-tpc-accent">
                    Explore Programs
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="{{ route('admission') }}"
                class="inline-flex items-center rounded-r-full border-2 border-white/60 px-6 py-3 text-sm font-bold text-white transition hover:bg-white hover:text-tpc-primary">
                    Admission Guide
                </a>
            </div>
        </div>

        {{-- Wave divider --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-8 sm:h-10">
                <path d="M0 40 C360 0 1080 0 1440 40 L1440 40 L0 40 Z" fill="white"/>
            </svg>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         ABOUT – CAROUSEL
    ══════════════════════════════════════ --}}
    <section id="about" class="scroll-mt-20 bg-white overflow-hidden border-b border-gray-200">

        <div class="max-w-7xl mx-auto px-4 pt-10 pb-2 flex items-center gap-4">
            <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
            <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">About the College</h2>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        @if($aboutSlides->isNotEmpty())
        <div class="relative select-none py-8" id="about-carousel">

            <div id="about-track"
                 class="relative mx-auto flex items-center justify-center overflow-visible"
                 style="height: 320px; max-width: 900px;">

                @foreach($aboutSlides as $i => $slide)
                <div class="about-slide absolute rounded-2xl overflow-hidden shadow-lg cursor-pointer"
                     data-index="{{ $i }}"
                     style="width: 560px; max-width: 78vw; will-change: transform, opacity;">
                    <img src="{{ asset('storage/' . $slide->image_path) }}"
                         alt="About TPC"
                         class="w-full object-cover block pointer-events-none bg-gray-100"
                         style="height: 280px;"
                         loading="lazy"
                         draggable="false">
                </div>
                @endforeach
            </div>

            <button id="about-prev"
                    class="absolute left-3 sm:left-8 top-1/2 -translate-y-1/2 z-30
                           h-10 w-10 rounded-full bg-white border border-gray-200 shadow
                           text-gray-600 hover:text-tpc-primary hover:border-tpc-primary
                           flex items-center justify-center transition"
                    aria-label="Previous slide">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button id="about-next"
                    class="absolute right-3 sm:right-8 top-1/2 -translate-y-1/2 z-30
                           h-10 w-10 rounded-full bg-white border border-gray-200 shadow
                           text-gray-600 hover:text-tpc-primary hover:border-tpc-primary
                           flex items-center justify-center transition"
                    aria-label="Next slide">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <div class="flex justify-center gap-2 pt-2" id="about-dots">
                @foreach($aboutSlides as $i => $slide)
                    <button class="about-dot transition-all duration-300 rounded-full h-2"
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
            slides.forEach((el, i) => { el.addEventListener('click', () => { if (el.dataset.pos !== '0') { goTo(i); startAutoplay(); } }); });

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
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="h-1.5 bg-tpc-primary"></div>
                    <div class="p-7">
                        <p class="text-xs font-bold tracking-widest text-tpc-primary uppercase mb-3">Vision</p>
                        <p class="text-base leading-relaxed text-gray-700">
                            An institution committed to provide quality educational opportunities and academic programs
                            that empower students to become competitive and responsive to the needs of the community.
                        </p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="h-1.5 bg-tpc-accent"></div>
                    <div class="p-7">
                        <p class="text-xs font-bold tracking-widest text-tpc-primary uppercase mb-3">Mission</p>
                        <p class="text-base leading-relaxed text-gray-700">
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
        <div class="max-w-7xl mx-auto px-4 py-14">

            {{-- Section header --}}
            <div class="flex items-end justify-between gap-4 mb-10">
                <div class="flex items-center gap-4">
                    <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                    <div>
                        <p class="text-[10px] font-bold tracking-widest text-tpc-primary/60 uppercase mb-0.5">TPC Updates</p>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight">Latest News &amp; Announcements</h2>
                    </div>
                </div>
                <a href="{{ route('news.index') }}"
                   class="shrink-0 inline-flex items-center gap-1.5 text-xs font-bold text-tpc-primary hover:text-tpc-secondary uppercase tracking-wide transition group">
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
                   class="news-featured-card group relative block bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6
                          hover:shadow-lg hover:border-tpc-primary/30 transition-all duration-300">

                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-tpc-primary via-tpc-primary to-tpc-accent z-10"></div>

                    <div class="sm:flex min-h-[260px]">
                        @if($featured->image_path)
                            <div class="relative sm:w-[42%] bg-gray-100 overflow-hidden shrink-0">
                                <img src="{{ asset('storage/' . $featured->image_path) }}"
                                     alt="{{ $featured->title }}"
                                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500"
                                     loading="lazy" />
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/10 hidden sm:block"></div>
                                <div class="sm:hidden h-52"></div>
                            </div>
                        @endif

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

                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 group-hover:text-tpc-primary transition-colors duration-200 leading-snug mb-3">
                                    {{ $featured->title }}
                                </h3>

                                <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">
                                    {{ $featured->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($featured->body), 180) }}
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

                {{-- ── SECONDARY POSTS GRID ── --}}
                @if($latestNews->count() > 1)
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($latestNews->skip(1) as $post)
                        <article class="news-card group bg-white rounded-2xl border border-gray-200 shadow-sm
                                        hover:shadow-md hover:border-tpc-primary/30 hover:-translate-y-0.5
                                        transition-all duration-300 overflow-hidden flex flex-col">

                            @if($post->image_path)
                                <a href="{{ route('news.show', $post) }}" class="block shrink-0 bg-gray-50 overflow-hidden relative"
                                   style="height: 168px;">
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
                                     style="height: 80px;">
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

                            <div class="p-5 flex flex-col flex-1">
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
        <div class="max-w-7xl mx-auto px-4 py-14">

            <div class="flex items-center gap-4 mb-10">
                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">Academic Programs</h2>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            @php
                $programCount     = $programs->count();
                $lastRowRemainder = $programCount % 3;
            @endphp

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($programs as $program)
                    @php
                        $isLast   = $loop->last;
                        $lgCenter = ($isLast && $lastRowRemainder === 1) ? 'lg:col-start-2' : '';
                    @endphp
                    <a href="{{ route('academics.show', $program) }}"
                       class="group bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md hover:border-tpc-primary/40 transition-all duration-300 overflow-hidden flex items-center gap-4 p-5 {{ $lgCenter }}">
                        <div class="h-1.5 absolute top-0 left-0 right-0 bg-tpc-primary group-hover:bg-tpc-accent transition-colors duration-300 rounded-t-2xl"></div>
                        @if($program->logo_path)
                            <div class="shrink-0 h-14 w-14 rounded-xl bg-tpc-primary/5 border border-tpc-primary/10 flex items-center justify-center p-2">
                                <img src="{{ asset('storage/' . $program->logo_path) }}"
                                     alt="{{ $program->code }} logo"
                                     class="h-full w-full object-contain" loading="lazy">
                            </div>
                        @else
                            <div class="shrink-0 h-14 w-14 rounded-xl bg-tpc-primary/5 border border-tpc-primary/10 flex items-center justify-center text-2xl">
                                🎓
                            </div>
                        @endif
                        <div class="min-w-0">
                            <span class="inline-block bg-tpc-primary/10 text-tpc-primary text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-full mb-1">
                                {{ $program->code ?? 'Program' }}
                            </span>
                            <h3 class="text-sm font-bold text-gray-800 group-hover:text-tpc-primary transition leading-snug">
                                {{ $program->name }}
                            </h3>
                        </div>
                    </a>
                @empty
                    <div class="col-span-3 py-16 text-center text-gray-400 text-sm border border-dashed border-gray-300 rounded-2xl">
                        No programs added yet.
                    </div>
                @endforelse
            </div>

            <div class="mt-8 max-w-2xl mx-auto">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="h-1.5 bg-tpc-accent"></div>
                    <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="font-bold text-gray-800">Explore programs before enrolling</p>
                            <p class="mt-1 text-sm text-gray-500">View all academic programs offered by Talibon Polytechnic College.</p>
                        </div>
                        <div class="flex flex-wrap gap-3 shrink-0">
                            <a href="{{ route('academics') }}"
                               class="inline-flex items-center rounded-l-full border-2 border-tpc-primary bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                View Programs
                            </a>
                            <a href="{{ route('news.index') }}"
                               class="inline-flex items-center rounded-r-full border-2 border-tpc-primary px-5 py-2.5 text-sm font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
                                Latest Updates →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

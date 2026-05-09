{{-- resources/views/public/home.blade.php --}}
@extends('layouts.site')

@section('title', 'Home')

@section('content')

    {{-- ══════════════════════════════════════
         MASTHEAD BANNER
    ══════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-tpc-primary">
        {{-- Background photo with dark overlay --}}
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/school-bg.jpg') }}" alt="" aria-hidden="true"
                 class="h-full w-full object-cover object-center opacity-40">
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 py-12 lg:py-16">
            <div class="max-w-3xl">
                <p class="text-xs font-bold tracking-widest text-tpc-accent uppercase mb-3">
                    Official College Publication
                </p>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                    Talibon Polytechnic College
                </h1>
                <p class="mt-3 text-base text-white/80 max-w-xl leading-relaxed">
                    A student-centered institution committed to quality education, research, and community development in Talibon, Bohol.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('academics') }}"
                       class="inline-flex items-center rounded border-2 border-white bg-white px-5 py-2.5 text-sm font-bold text-tpc-primary transition hover:bg-tpc-accent hover:border-tpc-accent">
                        Explore Programs
                    </a>
                    <a href="{{ route('admission') }}"
                       class="inline-flex items-center rounded border-2 border-white/60 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-white hover:text-tpc-primary">
                        Admission Guide
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         NEWSPAPER BODY
    ══════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 py-10">

        {{-- Section label --}}
        <div class="flex items-center gap-4 mb-6">
            <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
            <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">Latest News & Announcements</h2>
            <div class="flex-1 h-px bg-gray-200"></div>
            <a href="{{ route('news.index') }}"
               class="text-xs font-bold text-tpc-primary hover:text-tpc-secondary uppercase tracking-wide transition">
                View All →
            </a>
        </div>

        @if($latestNews->isNotEmpty())
        <div class="grid gap-6 lg:grid-cols-3">

            {{-- ── Featured article (left, large) ── --}}
            @php $featured = $latestNews->first(); @endphp
            <article class="lg:col-span-2 group border border-gray-200 hover:border-tpc-primary transition bg-white">
                @if($featured->image_path)
                    <a href="{{ route('news.show', $featured) }}" class="block overflow-hidden">
                        <img src="{{ asset('storage/' . $featured->image_path) }}"
                            alt="{{ $featured->title }}"
                            class="w-full object-contain group-hover:scale-[1.02] transition duration-300">
                    </a>
                @else
                    <div class="w-full h-3 bg-tpc-primary"></div>
                @endif

                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="bg-tpc-primary text-white text-[11px] font-bold uppercase tracking-wider px-2 py-0.5">
                            {{ $featured->category ?? 'Announcement' }}
                        </span>
                        @if($featured->published_at)
                            <time class="text-xs text-gray-400" datetime="{{ $featured->published_at->toDateString() }}">
                                {{ $featured->published_at->format('F d, Y') }}
                            </time>
                        @endif
                    </div>

                    <h3 class="text-2xl font-bold text-tpc-ink group-hover:text-tpc-primary transition leading-snug">
                        <a href="{{ route('news.show', $featured) }}">{{ $featured->title }}</a>
                    </h3>

                    <p class="mt-3 text-sm leading-relaxed text-gray-600">
                        {{ $featured->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($featured->body), 200) }}
                    </p>

                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <a href="{{ route('news.show', $featured) }}"
                           class="text-sm font-bold text-tpc-primary hover:text-tpc-secondary transition">
                            Read Full Article →
                        </a>
                    </div>
                </div>
            </article>

            {{-- ── Sidebar articles (right column) ── --}}
            <aside class="flex flex-col gap-0 border border-gray-200 divide-y divide-gray-200 bg-white">

                {{-- Sidebar header --}}
                <div class="px-4 py-3 bg-tpc-primary">
                    <p class="text-xs font-bold text-white uppercase tracking-widest">More Stories</p>
                </div>

                @foreach($latestNews->skip(1) as $post)
                    <article class="group flex gap-3 p-4 hover:bg-gray-50 transition">
                        @if($post->image_path)
                            <a href="{{ route('news.show', $post) }}" class="shrink-0">
                                <img src="{{ asset('storage/' . $post->image_path) }}"
                                    alt="{{ $post->title }}"
                                    class="h-16 w-20 object-contain bg-gray-50"
                                    loading="lazy">
                            </a>
                        @else
                            <div class="shrink-0 h-16 w-1.5 bg-tpc-primary rounded-sm"></div>
                        @endif

                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-tpc-primary uppercase tracking-wide">
                                {{ $post->category ?? 'Announcement' }}
                            </span>
                            <h4 class="mt-0.5 text-sm font-bold text-tpc-ink group-hover:text-tpc-primary transition leading-snug line-clamp-2">
                                <a href="{{ route('news.show', $post) }}">{{ $post->title }}</a>
                            </h4>
                            @if($post->published_at)
                                <time class="mt-1 block text-[11px] text-gray-400">
                                    {{ $post->published_at->format('M d, Y') }}
                                </time>
                            @endif
                        </div>
                    </article>
                @endforeach

                <div class="p-4 mt-auto">
                    <a href="{{ route('news.index') }}"
                       class="block w-full text-center border-2 border-tpc-primary py-2 text-sm font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
                        All News →
                    </a>
                </div>
            </aside>
        </div>

        @else
        <div class="border border-dashed border-gray-300 py-16 text-center text-gray-400">
            No news posts published yet.
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════
         DIVIDER
    ══════════════════════════════════════ --}}
    <div class="border-t-4 border-double border-gray-300"></div>

    {{-- ══════════════════════════════════════
         ABOUT / VISION / MISSION
    ══════════════════════════════════════ --}}
    <section id="about" class="scroll-mt-20 bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-12">

            <div class="flex items-center gap-4 mb-8">
                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">About the College</h2>
                <div class="flex-1 h-px bg-gray-300"></div>
            </div>

            <div class="grid gap-8 lg:grid-cols-3">
                {{-- Intro --}}
                <div class="lg:col-span-1">
                    <h3 class="text-xl font-bold text-tpc-ink leading-snug">
                        Building competitive and community-responsive graduates
                    </h3>
                    <p class="mt-3 text-sm leading-relaxed text-gray-600">
                        Talibon Polytechnic College is committed to providing accessible, high-quality education
                        through academic excellence, innovation, and service to the community.
                    </p>
                    {{-- <a href="{{ route('academics') }}"
                       class="mt-5 inline-flex items-center text-sm font-bold text-tpc-primary hover:text-tpc-secondary transition">
                        View Programs →
                    </a> --}}
                </div>

                {{-- Vision --}}
                <div class="bg-white border border-gray-200 border-l-4 border-l-tpc-primary p-6">
                    <p class="text-[11px] font-bold tracking-widest text-tpc-primary uppercase mb-2">Vision</p>
                    <p class="text-sm leading-relaxed text-gray-700">
                        An institution committed to provide quality educational opportunities and academic programs
                        that empower students to become competitive and responsive to the needs of the community.
                    </p>
                </div>

                {{-- Mission --}}
                <div class="bg-white border border-gray-200 border-l-4 border-l-tpc-secondary p-6">
                    <p class="text-[11px] font-bold tracking-widest text-tpc-primary uppercase mb-2">Mission</p>
                    <p class="text-sm leading-relaxed text-gray-700">
                        To provide quality educational opportunities and support services, strengthen innovative
                        research and extension, and produce competent individuals in technological and professional
                        fields for community transformation and development.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         PROGRAMS
    ══════════════════════════════════════ --}}
    <section class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-12">

            <div class="flex items-center gap-4 mb-8">
                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">Academic Programs</h2>
                <div class="flex-1 h-px bg-gray-200"></div>
                {{-- <a href="{{ route('academics') }}"
                   class="text-xs font-bold text-tpc-primary hover:text-tpc-secondary uppercase tracking-wide transition">
                    View All →
                </a> --}}
            </div>

            @php
                $programCount = $programs->count();
                $lastRowRemainder = $programCount % 3;
            @endphp

            <div class="grid gap-px bg-gray-200 border border-gray-200 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($programs as $program)
                    @php
                        $isLast = $loop->last;
                        $colStart = ($isLast && $lastRowRemainder === 1) ? 'lg:col-start-2' : '';
                    @endphp
                    <article class="group bg-white p-6 flex items-center gap-4 hover:bg-tpc-primary/5 transition {{ $colStart }}">
                        @if($program->logo_path)
                            <img src="{{ asset('storage/' . $program->logo_path) }}"
                                 alt="{{ $program->code }} logo"
                                 class="h-14 w-14 object-contain shrink-0"
                                 loading="lazy">
                        @else
                            <div class="h-14 w-14 shrink-0 flex items-center justify-center bg-tpc-primary/10 text-2xl">
                                🎓
                            </div>
                        @endif
                        <div>
                            <p class="text-[10px] font-bold text-tpc-primary uppercase tracking-wider">
                                {{ $program->code ?? 'Program' }}
                            </p>
                            <h3 class="mt-0.5 text-sm font-bold text-tpc-ink group-hover:text-tpc-primary transition leading-snug">
                                {{ $program->name }}
                            </h3>
                        </div>
                    </article>
                @empty
                    <div class="bg-white col-span-3 py-12 text-center text-gray-400 text-sm">
                        No programs added yet.
                    </div>
                @endforelse
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('academics') }}"
                   class="inline-flex items-center border-2 border-tpc-primary px-6 py-2.5 text-sm font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
                    View All Academic Programs →
                </a>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         CTA BANNER
    ══════════════════════════════════════ --}}
    <section class="bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-10 lg:flex lg:items-center lg:justify-between gap-8">
            <div>
                <p class="text-xs font-bold tracking-widest text-tpc-accent uppercase mb-1">Enrollment Open</p>
                <h2 class="text-2xl font-bold text-white">Ready to enroll at TPC?</h2>
                <p class="mt-1 text-sm text-white/75">
                    Review our admission requirements and start your application today.
                </p>
            </div>
            <div class="mt-6 flex flex-wrap gap-3 lg:mt-0 lg:shrink-0">
                <a href="{{ route('admission') }}"
                   class="inline-flex items-center border-2 border-white bg-white px-6 py-2.5 text-sm font-bold text-tpc-primary hover:bg-tpc-accent hover:border-tpc-accent transition">
                    Admission Guide
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center border-2 border-white/60 px-6 py-2.5 text-sm font-bold text-white hover:bg-white hover:text-tpc-primary transition">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

@endsection

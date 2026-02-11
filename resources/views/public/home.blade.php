{{-- resources/views/public/home.blade.php --}}
@extends('layouts.site')

@section('title', 'Home')

@section('content')
    {{-- HERO --}}
    <section id="top" class="relative isolate overflow-hidden">
        {{-- Base gradient --}}
        <div class="absolute inset-0 z-0 bg-gradient-to-br from-tpc-primary/10 via-white to-tpc-accent/20"></div>

        {{-- Decorative blobs --}}
        <div class="absolute -top-24 -right-24 z-0 h-80 w-80 rounded-full bg-tpc-accent/30 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 z-0 h-80 w-80 rounded-full bg-tpc-primary/15 blur-3xl"></div>

        {{-- Subtle TPC Logo watermark (HERO only) --}}
        {{-- <div aria-hidden="true" class="pointer-events-none absolute inset-0 z-0 flex items-center justify-center">
            <img
                src="{{ asset('images/TPC-Logo.png') }}"
                alt=""
                class="w-[560px] max-w-[85vw] select-none opacity-[0.06] saturate-0 mix-blend-multiply"
                draggable="false"
            />
        </div> --}}

        <div class="relative z-10 max-w-7xl mx-auto px-4 py-16 lg:py-24">
            <div class="min-h-[64vh] flex items-center justify-center">
                <div class="max-w-3xl text-center -translate-y-6 sm:-translate-y-10">
                    <div class="inline-flex items-center gap-2 rounded-full border border-tpc-primary/20 bg-white/70 px-3 py-1 text-sm">
                        <span class="h-2 w-2 rounded-full bg-tpc-primary"></span>
                        <span class="text-tpc-ink/80">Official Website</span>
                    </div>

                    <h1 class="mt-6 text-4xl font-semibold tracking-tight text-tpc-ink sm:text-5xl">
                        Welcome to
                        <span class="text-tpc-primary">Talibon Polytechnic College</span>
                    </h1>

                    <p class="mt-5 mx-auto max-w-2xl text-base leading-relaxed text-tpc-ink/80 sm:text-lg">
                        A clean, student-centered learning environment committed to quality education,
                        research, and community development.
                    </p>

                    <div class="mt-8 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
                        <a href="{{ route('academics') }}"
                           class="order-1 w-full max-w-[280px] sm:max-w-none sm:w-auto inline-flex items-center justify-center rounded-lg bg-tpc-primary px-6 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-tpc-secondary focus:outline-none focus:ring-2 focus:ring-tpc-primary/40 focus:ring-offset-2">
                            Explore Programs
                        </a>

                        <a href="{{ route('admission') }}"
                           class="order-2 w-full max-w-[280px] sm:max-w-none sm:w-auto inline-flex items-center justify-center rounded-lg border border-tpc-primary/30 bg-white px-6 py-3 text-sm font-medium text-tpc-primary shadow-sm transition hover:bg-tpc-primary/5 focus:outline-none focus:ring-2 focus:ring-tpc-primary/40 focus:ring-offset-2 sm:order-3">
                            Admission Guide
                        </a>

                        {{-- Learn more (mobile: last, desktop: middle) --}}
                        <a href="{{ route('home') }}#about"
                           data-scroll-about
                           aria-label="Learn more"
                           class="tpc-learnmore order-3 sm:order-2 inline-flex h-11 w-11 items-center justify-center rounded-full
                                  border border-tpc-primary/25 bg-white/80 text-tpc-primary shadow-sm transition
                                  hover:bg-tpc-primary/5 hover:text-tpc-secondary
                                  focus:outline-none focus:ring-2 focus:ring-tpc-primary/40 focus:ring-offset-2">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M6 9l6 6 6-6"
                                      stroke="currentColor"
                                      stroke-width="3.6"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ABOUT / MISSION / VISION --}}
    <section id="about" class="scroll-mt-20 tpc-section-alt">
        <div class="max-w-7xl mx-auto px-4 py-16">
            <div class="max-w-3xl">
                <p class="text-xs font-medium tracking-wide text-tpc-primary uppercase">About</p>
                <h2 class="mt-2 text-3xl font-semibold tracking-tight text-tpc-ink">
                    Building competitive and community-responsive graduates
                </h2>
                <p class="mt-4 text-base leading-relaxed text-tpc-ink/80">
                    Talibon Polytechnic College is committed to providing accessible, high-quality education through
                    academic excellence, innovation, and service to the community.
                </p>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-2">
                <div class="tpc-card p-6">
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-semibold text-tpc-ink">Vision</h3>
                    </div>
                    <p class="mt-4 text-tpc-ink/80 leading-relaxed">
                        An institution committed to provide quality educational opportunities and academic programs
                        that empower students to become competitive and responsive to the needs of the community.
                    </p>
                </div>

                <div class="tpc-card p-6">
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-semibold text-tpc-ink">Mission</h3>
                    </div>
                    <p class="mt-4 text-tpc-ink/80 leading-relaxed">
                        To provide quality educational opportunities and support services, strengthen innovative research and extension,
                        and produce competent individuals in technological and professional fields for community transformation and development.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- PROGRAMS PREVIEW --}}
    <section class="tpc-section-alt relative isolate overflow-hidden">
        <div class="relative z-10 max-w-7xl mx-auto px-4 pb-16">
            <div class="flex items-end justify-between gap-6">
                <div>
                    <p class="text-xs font-medium tracking-wide text-tpc-primary uppercase">Academics</p>
                    <h2 class="mt-2 text-2xl font-semibold text-tpc-ink">Featured Programs</h2>
                    <p class="mt-2 text-sm text-tpc-ink/70">
                        Browse our academic offerings designed to prepare students for real-world careers.
                    </p>
                </div>
                <a href="{{ route('academics') }}"
                   class="hidden sm:inline-flex items-center rounded-lg border border-tpc-primary/30 bg-white px-4 py-2 text-sm font-medium text-tpc-primary transition hover:bg-tpc-primary/5">
                    View all programs →
                </a>
            </div>

            @php
                $programCount = $programs->count();
                $remLg = $programCount % 3;
            @endphp

            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-6">
                @forelse ($programs as $program)
                    @php
                        $isLast = $loop->last;
                        $isSecondLast = $loop->remaining === 1;

                        // sm:grid-cols-2 => if odd count, last item alone -> center it
                        $centerLastOnSm = $isLast && ($programCount % 2 === 1);

                        // lg:grid-cols-6 with each card lg:col-span-2 (3 cards/row)
                        // If remainder 1: last item should start at col 3 (center)
                        $centerOneOnLg = $isLast && ($remLg === 1);

                        // If remainder 2: last 2 items should start at col 2 and 4
                        $centerTwoFirstOnLg = $isSecondLast && ($remLg === 2);
                        $centerTwoLastOnLg  = $isLast && ($remLg === 2);
                    @endphp

                    <article
                        class="w-full group rounded-2xl border border-tpc-primary/10 bg-white/90 backdrop-blur-[1px] p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-tpc-primary/30 hover:shadow-md
                               lg:col-span-2
                               {{ $centerLastOnSm ? 'sm:col-span-2 sm:justify-self-center sm:max-w-[520px] lg:max-w-none' : '' }}
                               {{ $centerOneOnLg ? 'lg:col-start-3' : '' }}
                               {{ $centerTwoFirstOnLg ? 'lg:col-start-2' : '' }}
                               {{ $centerTwoLastOnLg ? 'lg:col-start-4' : '' }}"
                    >
                        <div class="flex flex-col items-center text-center">
                            @if ($program->logo_path)
                                <img
                                    src="{{ asset('storage/' . $program->logo_path) }}"
                                    alt="{{ $program->code }} logo"
                                    class="h-20 w-20 rounded-2xl object-contain"
                                    loading="lazy"
                                />
                            @else
                                <span class="inline-flex h-20 w-20 items-center justify-center rounded-2xl text-tpc-secondary text-3xl">
                                    🎓
                                </span>
                            @endif

                            <h3 class="mt-4 text-lg font-semibold text-tpc-ink group-hover:text-tpc-primary transition">
                                {{ $program->name }}
                            </h3>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-tpc-primary/30 p-8 text-center text-tpc-ink/70 lg:col-span-6">
                        No programs added yet.
                    </div>
                @endforelse
            </div>

            <div class="mt-8 sm:hidden">
                <a href="{{ route('academics') }}"
                   class="inline-flex w-full items-center justify-center rounded-lg border border-tpc-primary/30 bg-white px-4 py-3 text-sm font-medium text-tpc-primary transition hover:bg-tpc-primary/5">
                    View all programs →
                </a>
            </div>
        </div>
    </section>

    {{-- NEWS PREVIEW --}}
    <section class="tpc-section-alt">
        <div class="max-w-7xl mx-auto px-4 pb-20">
            <div class="flex items-end justify-between gap-6">
                <div>
                    <p class="text-xs font-medium tracking-wide text-tpc-primary uppercase">Updates</p>
                    <h2 class="mt-2 text-2xl font-semibold text-tpc-ink">Latest News & Announcements</h2>
                    <p class="mt-2 text-sm text-tpc-ink/70">
                        Stay informed with campus updates, events, and important notices.
                    </p>
                </div>
                <a href="{{ route('news.index') }}"
                   class="hidden sm:inline-flex items-center rounded-lg border border-tpc-primary/30 bg-white px-4 py-2 text-sm font-medium text-tpc-primary transition hover:bg-tpc-primary/5">
                    View all news →
                </a>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-3">
                @forelse ($latestNews as $post)
                    <article class="group overflow-hidden rounded-2xl border border-tpc-primary/10 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-tpc-primary/30 hover:shadow-md">
                        {{-- Image (NO CROP) --}}
                        @if ($post->image_path)
                            <a href="{{ route('news.show', $post) }}" class="block border-b border-tpc-primary/10 bg-white">
                                <div class="flex items-center justify-center p-3">
                                    <img
                                        src="{{ asset('storage/' . $post->image_path) }}"
                                        alt="{{ $post->title }} image"
                                        class="h-44 w-full object-contain"
                                        loading="lazy"
                                    />
                                </div>
                            </a>
                        @else
                            <div class="h-2 bg-tpc-primary/5"></div>
                        @endif

                        <div class="p-6">
                            <div class="flex items-center justify-between gap-3">
                                <span class="inline-flex items-center rounded-full bg-tpc-accent/30 px-3 py-1 text-xs font-medium text-tpc-secondary">
                                    {{ $post->category ?? 'Announcement' }}
                                </span>

                                @if ($post->published_at)
                                    <time class="text-xs text-tpc-ink/50" datetime="{{ $post->published_at->toDateString() }}">
                                        {{ $post->published_at->format('M d, Y') }}
                                    </time>
                                @endif
                            </div>

                            <h3 class="mt-3 text-lg font-semibold text-tpc-ink group-hover:text-tpc-primary transition">
                                <a href="{{ route('news.show', $post) }}" class="focus:outline-none">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            <p class="mt-3 text-sm leading-relaxed text-tpc-ink/70">
                                {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 120) }}
                            </p>

                            <div class="mt-5">
                                <a href="{{ route('news.show', $post) }}"
                                   class="text-sm font-medium text-tpc-primary hover:text-tpc-secondary">
                                    Read more →
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-tpc-primary/30 p-8 text-center text-tpc-ink/70 lg:col-span-3">
                        No news posts yet.
                    </div>
                @endforelse
            </div>

            <div class="mt-8 sm:hidden">
                <a href="{{ route('news.index') }}"
                   class="inline-flex w-full items-center justify-center rounded-lg border border-tpc-primary/30 bg-white px-4 py-3 text-sm font-medium text-tpc-primary transition hover:bg-tpc-primary/5">
                    View all news →
                </a>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-tpc-primary/5">
        <div class="max-w-7xl mx-auto px-4 py-16">
            <div class="rounded-2xl border border-tpc-primary/10 bg-white p-8 shadow-sm lg:flex lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-tpc-ink">Ready to enroll?</h2>
                    <p class="mt-2 text-sm text-tpc-ink/70">
                        See the admission requirements and enrollment process.
                    </p>
                </div>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row lg:mt-0">
                    <a href="{{ route('admission') }}"
                       class="inline-flex items-center justify-center rounded-lg bg-tpc-primary px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-tpc-secondary focus:outline-none focus:ring-2 focus:ring-tpc-primary/40 focus:ring-offset-2">
                        View Admission
                    </a>
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center justify-center rounded-lg border border-tpc-primary/30 bg-white px-5 py-3 text-sm font-medium text-tpc-primary shadow-sm transition hover:bg-tpc-primary/5 focus:outline-none focus:ring-2 focus:ring-tpc-primary/40 focus:ring-offset-2">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

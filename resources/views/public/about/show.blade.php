{{-- resources/views/public/about/show.blade.php --}}
@extends('layouts.site')

@section('title', $slide->title ?: 'About TPC')

@section('content')

    @php
        // Same set and order as the home carousel (active, by sort_order), used for previous / next / thumbnails.
        $slides = \App\Models\AboutSlide::active()->get();
        $total = $slides->count();
        $idx = $slides->search(fn($s) => $s->id === $slide->id);
        $prev = $idx !== false && $total > 1 ? $slides[($idx - 1 + $total) % $total] : null;
        $next = $idx !== false && $total > 1 ? $slides[($idx + 1) % $total] : null;
        $imgUrl = asset('storage/' . $slide->image_path);
        $chev = 'h-4 w-4 shrink-0';
    @endphp

    {{-- Page header: same structure as Academics / Admission / Organizational Chart --}}
    <section class="relative overflow-hidden bg-tpc-secondary">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0"
            style="background: radial-gradient(ellipse at 70% 50%, rgba(255,255,255,0.06) 0%, transparent 60%),
                                radial-gradient(ellipse at 20% 80%, rgba(0,0,0,0.15) 0%, transparent 50%)">
        </div>
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-[0.04]"
            style="background-image: linear-gradient(rgba(255,255,255,0.8) 1px, transparent 1px),
                                      linear-gradient(90deg, rgba(255,255,255,0.8) 1px, transparent 1px);
                    background-size: 40px 40px;">
        </div>

        <div class="relative mx-auto max-w-4xl px-4 pt-10 pb-16 sm:pt-14 sm:pb-20">
            <div class="flex flex-col items-center text-center">
                <h1 class="max-w-3xl text-2xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    {{ $slide->title ?: 'About Talibon Polytechnic College' }}
                </h1>

                <nav class="mt-5 flex flex-wrap items-center justify-center gap-1.5 text-xs font-medium text-white/70"
                    aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                    <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('home') }}#about" class="hover:text-white transition">About</a>
                    @if ($slide->title)
                        <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="max-w-[160px] truncate text-white sm:max-w-xs"
                            aria-current="page">{{ $slide->title }}</span>
                    @endif
                </nav>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
                class="w-full h-8 sm:h-12">
                <path d="M0 48 C480 0 960 0 1440 48 L1440 48 L0 48 Z" fill="#f9fafb" />
            </svg>
        </div>
    </section>

    <section class="bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 py-8 sm:py-12 grid gap-8 lg:gap-12 lg:grid-cols-12">

            {{-- Slide viewer --}}
            <div class="min-w-0 lg:col-span-8">

                <a href="{{ $imgUrl }}" target="_blank" rel="noopener"
                    class="block border border-gray-200 bg-white p-2 sm:p-4 focus-visible:outline focus-visible:outline-2 focus-visible:outline-tpc-primary">
                    <img src="{{ $imgUrl }}" alt="{{ $slide->title ?: 'Talibon Polytechnic College' }}"
                        class="mx-auto w-full max-h-[80vh] object-contain">
                </a>

                {{-- Previous / position / next --}}
                <div class="mt-3 flex items-center justify-between gap-3 text-sm">
                    @if ($prev)
                        <a href="{{ route('about.show', $prev) }}" rel="prev"
                            class="inline-flex h-10 items-center gap-1.5 rounded border border-gray-300 px-3 font-semibold text-gray-700 hover:border-tpc-primary hover:text-tpc-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-tpc-primary">
                            <svg class="{{ $chev }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </a>
                        <span class="text-gray-500 tabular-nums">{{ $idx + 1 }} of {{ $total }}</span>
                        <a href="{{ route('about.show', $next) }}" rel="next"
                            class="inline-flex h-10 items-center gap-1.5 rounded border border-gray-300 px-3 font-semibold text-gray-700 hover:border-tpc-primary hover:text-tpc-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-tpc-primary">
                            Next
                            <svg class="{{ $chev }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ $imgUrl }}" target="_blank" rel="noopener"
                            class="font-semibold text-tpc-primary underline-offset-4 hover:underline hover:text-tpc-secondary">View
                            full size</a>
                    @endif
                </div>

                {{-- Thumbnails --}}
                @if ($total > 1)
                    <ul class="mt-4 flex gap-2 overflow-x-auto pb-1" aria-label="All slides">
                        @foreach ($slides as $i => $s)
                            @php $current = $s->id === $slide->id; @endphp
                            <li class="shrink-0">
                                <a href="{{ route('about.show', $s) }}"
                                    @if ($current) aria-current="true" @endif
                                    aria-label="{{ $s->title ?: 'Slide ' . ($i + 1) }}"
                                    class="block h-16 w-24 overflow-hidden bg-gray-100 border-2 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-tpc-primary {{ $current ? 'border-tpc-primary' : 'border-transparent opacity-70 hover:opacity-100' }}">
                                    <img src="{{ asset('storage/' . $s->image_path) }}" alt=""
                                        class="h-full w-full object-cover" loading="lazy">
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Details --}}
            <aside class="lg:col-span-4">
                <div class="lg:sticky lg:top-24">
                    @if ($slide->caption)
                        <div class="border-l-4 border-tpc-primary pl-4">
                            <p class="text-left text-base leading-relaxed text-gray-700">{{ $slide->caption }}</p>
                        </div>
                    @endif

                    <div class="{{ $slide->caption ? 'mt-8 border-t border-gray-200 pt-6' : '' }}">
                        <h2 class="text-lg font-semibold text-gray-900">Learn more about TPC</h2>
                        <ul class="mt-3 divide-y divide-gray-200 border-y border-gray-200 text-sm font-semibold">
                            @foreach ([['Academic programs', route('academics')], ['Organizational chart', route('org-chart')], ['Contact the college office', route('contact')]] as [$label, $href])
                                <li>
                                    <a href="{{ $href }}"
                                        class="flex items-center justify-between gap-3 py-3 text-gray-800 hover:text-tpc-primary">
                                        {{ $label }}
                                        <svg class="{{ $chev }} text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </aside>
        </div>

        {{-- Back navigation --}}
        <div class="max-w-7xl mx-auto px-4 pb-10 sm:pb-14">
            <div
                class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 pt-5 text-sm font-semibold">
                <a href="{{ route('home') }}#about"
                    class="inline-flex items-center gap-1.5 text-tpc-primary hover:text-tpc-secondary">
                    <svg class="{{ $chev }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to About
                </a>
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-tpc-primary">Back to Home</a>
            </div>
        </div>
    </section>

@endsection

{{-- resources/views/public/services/show.blade.php --}}
@extends('layouts.site')

@section('title', $service->title)

@section('content')

    {{-- ── HEADER ──────────────────────────────────────────────────────── --}}
    <section class="relative overflow-hidden bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-8 sm:py-12">

            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-white/70 hover:text-white uppercase tracking-wide transition mb-5 sm:mb-6">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Home
            </a>

            <div class="max-w-3xl">
                <span class="inline-block bg-white/20 text-white text-[10px] sm:text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full backdrop-blur-sm mb-3">
                    Services
                </span>
                <h1 class="text-2xl sm:text-4xl font-bold text-white leading-tight">
                    {{ $service->title }}
                </h1>
                @if ($service->description)
                    <p class="mt-3 sm:mt-4 max-w-2xl text-sm sm:text-base text-white/75 leading-relaxed">
                        {{ $service->description }}
                    </p>
                @endif

                <div class="mt-5 sm:mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('admission') }}"
                       class="inline-flex items-center gap-2 rounded-full border-2 border-white bg-white px-5 py-2.5 text-xs sm:text-sm font-bold text-tpc-primary hover:bg-tpc-primary hover:border-white hover:text-white transition">
                        How to Enroll
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center gap-2 rounded-full border-2 border-white/90 bg-black/20 backdrop-blur-sm px-5 py-2.5 text-xs sm:text-sm font-bold text-white hover:bg-white/15 transition">
                        Ask a Question
                    </a>
                </div>
            </div>
        </div>

        {{-- Wave divider --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-6 sm:h-10">
                <path d="M0 40 C360 0 1080 0 1440 40 L1440 40 L0 40 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- ── BODY ────────────────────────────────────────────────────────── --}}
    <section class="bg-gray-50 py-8 sm:py-14">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid gap-8 lg:grid-cols-3">

                {{-- Main content column --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Featured image (if present) --}}
                    @if ($service->featured_image_path)
                        <div class="rounded-3xl overflow-hidden border border-gray-200 shadow-sm">
                            <img src="{{ asset('storage/' . $service->featured_image_path) }}"
                                 alt="{{ $service->title }}"
                                 class="w-full object-cover"
                                 loading="eager">
                        </div>
                    @endif

                    {{-- Content blocks --}}
                    @if ($service->contents->isNotEmpty())
                        @foreach ($service->contents as $block)

                            {{-- ── TEXT BLOCK ──────────────────────────────── --}}
                            @if ($block->isText())
                                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 sm:p-8">
                                    @if ($block->heading)
                                        <div class="flex items-center gap-3 sm:gap-4 mb-4 sm:mb-5">
                                            <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                                            <h2 class="text-base sm:text-lg font-bold text-gray-900">{{ $block->heading }}</h2>
                                        </div>
                                    @endif
                                    <div class="prose prose-sm sm:prose-base prose-gray max-w-none
                                                prose-p:leading-relaxed prose-p:text-gray-600
                                                prose-headings:font-bold prose-headings:text-gray-900">
                                        {!! nl2br(e($block->body)) !!}
                                    </div>
                                </div>

                            {{-- ── IMAGE BLOCK ─────────────────────────────── --}}
                            @elseif ($block->isImage() && $block->image_path)
                                <figure class="rounded-3xl overflow-hidden border border-gray-200 shadow-sm">
                                    @if ($block->heading)
                                        <div class="bg-white px-6 py-4 border-b border-gray-100">
                                            <div class="flex items-center gap-3">
                                                <span class="block h-4 w-1 bg-tpc-primary rounded-sm shrink-0"></span>
                                                <h3 class="text-sm font-bold text-gray-900">{{ $block->heading }}</h3>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="bg-gray-50">
                                        <img src="{{ asset('storage/' . $block->image_path) }}"
                                             alt="{{ $block->heading ?? $block->image_caption ?? $service->title }}"
                                             class="w-full object-cover max-h-96"
                                             loading="lazy">
                                    </div>
                                    @if ($block->image_caption)
                                        <figcaption class="bg-white px-6 py-3 text-xs text-gray-400 italic border-t border-gray-100">
                                            {{ $block->image_caption }}
                                        </figcaption>
                                    @endif
                                </figure>
                            @endif

                        @endforeach
                    @else
                        {{-- Empty state --}}
                        <div class="py-16 text-center border border-dashed border-gray-300 rounded-3xl bg-white">
                            <p class="text-base font-semibold text-gray-300 mb-1">Content coming soon</p>
                            <p class="text-xs text-gray-400">More details about this service will be available soon.</p>
                        </div>
                    @endif
                </div>

                {{-- ── SIDEBAR ──────────────────────────────────────────── --}}
                <aside class="space-y-5 sm:space-y-6">

                    {{-- CTA card --}}
                    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="h-1.5 bg-tpc-primary"></div>
                        <div class="px-5 py-4 border-b border-gray-100">
                            <p class="text-xs font-bold text-tpc-primary uppercase tracking-widest">Interested?</p>
                        </div>
                        <div class="p-5 space-y-3">
                            <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                                Want to learn more about <span class="font-semibold text-gray-800">{{ $service->title }}</span>?
                                Check our admission guide or get in touch.
                            </p>
                            <a href="{{ route('admission') }}"
                               class="flex items-center justify-center rounded-full border-2 border-tpc-primary bg-tpc-primary px-5 py-2.5 text-xs sm:text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                View Admission Guide
                            </a>
                            <a href="{{ route('contact') }}"
                               class="flex items-center justify-center rounded-full border-2 border-tpc-primary px-5 py-2.5 text-xs sm:text-sm font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
                                Contact Us
                            </a>
                        </div>
                    </div>

                    {{-- Other Services --}}
                    @php
                        $otherServices = \App\Models\Service::active()
                            ->ordered()
                            ->where('id', '!=', $service->id)
                            ->get();
                    @endphp

                    @if ($otherServices->isNotEmpty())
                        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="h-1.5 bg-tpc-primary"></div>
                            <div class="px-5 py-4 border-b border-gray-100">
                                <p class="text-xs font-bold text-tpc-primary uppercase tracking-widest">Other Services</p>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @foreach ($otherServices as $other)
                                    <a href="{{ route('services.show', $other) }}"
                                       class="group flex items-center gap-3 px-5 py-3.5 hover:bg-tpc-primary/5 transition">
                                        <div class="shrink-0 h-8 w-8 rounded-lg bg-tpc-primary/10 flex items-center justify-center">
                                            <svg class="h-3.5 w-3.5 text-tpc-primary/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-600 truncate group-hover:text-gray-900 transition leading-snug flex-1">
                                            {{ $other->title }}
                                        </p>
                                        <svg class="h-3.5 w-3.5 text-gray-300 group-hover:text-tpc-primary transition ml-auto shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </aside>
            </div>
        </div>
    </section>

@endsection

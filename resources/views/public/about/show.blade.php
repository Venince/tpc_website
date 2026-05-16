{{-- resources/views/public/about/show.blade.php --}}
@extends('layouts.site')

@section('title', $slide->title ?: 'About TPC')

@section('content')

    {{-- ══════════════════════════════════════
         PAGE HEADER
    ══════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-tpc-primary">
        <div class="max-w-4xl mx-auto px-4 py-8 sm:py-10 relative z-10">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 text-xs font-medium text-white/60 mb-5 sm:mb-6 flex-wrap" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition shrink-0">Home</a>
                <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('home') }}#about" class="hover:text-white transition shrink-0">About</a>
                @if($slide->title)
                    <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-white/40 truncate max-w-[140px] sm:max-w-xs">{{ $slide->title }}</span>
                @endif
            </nav>

            {{-- Label row --}}
            <div class="flex flex-wrap items-center gap-2 mb-3 sm:mb-4">
                <span class="inline-flex items-center gap-1 bg-white/15 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full border border-white/20">
                    <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                    About the College
                </span>
                <span class="text-xs text-white/40 hidden sm:inline">· Talibon Polytechnic College</span>
            </div>

            {{-- Title --}}
            <h1 class="text-xl sm:text-3xl lg:text-4xl font-bold text-white leading-tight max-w-3xl">
                {{ $slide->title ?: 'About Talibon Polytechnic College' }}
            </h1>

            @if($slide->caption)
                <p class="mt-3 sm:mt-4 text-xs sm:text-sm text-white/70 leading-relaxed max-w-2xl line-clamp-3 sm:line-clamp-none">
                    {{ $slide->caption }}
                </p>
            @endif
        </div>

        {{-- Wave divider --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-6 sm:h-10">
                <path d="M0 40 C360 0 1080 0 1440 40 L1440 40 L0 40 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         SLIDE BODY
    ══════════════════════════════════════ --}}
    <section class="bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 py-6 sm:py-12">

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                {{-- Accent top bar --}}
                <div class="h-1 bg-gradient-to-r from-tpc-primary via-tpc-primary to-tpc-accent"></div>

                {{-- Featured image --}}
                <div class="relative bg-gray-50 border-b border-gray-100">
                    <div class="flex items-center justify-center p-3 sm:p-6">
                        <a href="{{ asset('storage/' . $slide->image_path) }}"
                           target="_blank" rel="noopener"
                           class="group relative block w-full">
                            <img src="{{ asset('storage/' . $slide->image_path) }}"
                                 alt="{{ $slide->title ?: 'About TPC' }}"
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

                {{-- Caption / content area --}}
                <div class="p-4 sm:p-7 lg:p-10">

                    @if($slide->caption)
                        <div class="relative border-l-4 border-tpc-primary bg-tpc-primary/5 px-4 sm:px-6 py-4 sm:py-5 mb-6 sm:mb-8 rounded-r-xl">
                            <svg class="absolute -top-1 -left-1 h-4 w-4 sm:h-5 sm:w-5 text-tpc-primary/30" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                            <p class="text-xs sm:text-sm font-semibold text-gray-700 leading-relaxed italic">{{ $slide->caption }}</p>
                        </div>
                    @endif

                    {{-- Meta footer --}}
                    <div class="mt-2 pt-4 sm:pt-6 border-t border-gray-100 flex flex-wrap items-center gap-2 sm:gap-3 text-xs text-gray-400">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="hidden xs:inline">Talibon Polytechnic College</span>
                            <span class="xs:hidden">TPC</span>
                        </span>
                        <span class="hidden xs:inline">·</span>
                        <span class="ml-auto inline-block bg-tpc-primary/10 text-tpc-primary text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            About
                        </span>
                    </div>

                    {{-- Divider --}}
                    <div class="my-5 sm:my-8 border-t border-gray-100"></div>

                    {{-- Footer CTA --}}
                    <div class="relative bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                        <div class="h-1 bg-gradient-to-r from-tpc-primary to-tpc-accent"></div>
                        <div class="p-4 sm:p-6 sm:flex sm:items-center sm:justify-between gap-6">
                            <div>
                                <p class="text-xs font-bold text-tpc-primary uppercase tracking-widest mb-1">Want to know more?</p>
                                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                                    Explore our academic programs or get in touch with the college office.
                                </p>
                            </div>
                            <div class="mt-4 sm:mt-0 flex gap-2 sm:gap-3 shrink-0">
                                <a href="{{ route('academics') }}"
                                   class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 sm:gap-2 rounded-full border-2 border-tpc-primary bg-tpc-primary px-4 sm:px-5 py-2.5 text-xs sm:text-sm font-bold text-white
                                          hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    View Programs
                                </a>
                                <a href="{{ route('contact') }}"
                                   class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1 rounded-full border-2 border-tpc-primary px-4 sm:px-5 py-2.5 text-xs sm:text-sm font-bold text-tpc-primary
                                          hover:bg-tpc-primary hover:text-white transition">
                                    Contact Us
                                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Back navigation --}}
            <div class="mt-5 sm:mt-8 flex items-center justify-between">
                <a href="{{ route('home') }}#about"
                   class="inline-flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm font-bold text-tpc-primary hover:text-tpc-secondary transition group">
                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 transition-transform group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to About
                </a>
                <a href="{{ route('home') }}"
                   class="hidden sm:inline-flex items-center gap-1.5 text-xs font-bold text-gray-400 hover:text-tpc-primary transition uppercase tracking-wide">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Back to Home
                </a>
            </div>

        </div>
    </section>

@endsection

{{-- resources/views/public/news/show.blade.php --}}
@extends('layouts.site')

@section('title', $post->title)

@section('content')

    {{-- ══════════════════════════════════════
         ARTICLE HEADER
    ══════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-tpc-primary">
        <div class="max-w-4xl mx-auto px-4 py-10 relative z-10">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 text-xs font-medium text-white/60 mb-6" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('news.index') }}" class="hover:text-white transition">News</a>
                <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-white/40 truncate max-w-[160px] sm:max-w-xs">{{ $post->title }}</span>
            </nav>

            {{-- Category + date row --}}
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <span class="inline-flex items-center gap-1 bg-white/15 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full border border-white/20">
                    <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                    {{ $post->category ?? 'Announcement' }}
                </span>
                @if($post->published_at)
                    <span class="inline-flex items-center gap-1.5 text-xs text-white/60">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                        <time datetime="{{ $post->published_at->toDateString() }}">
                            {{ $post->published_at->format('F d, Y') }}
                        </time>
                    </span>
                @endif
                <span class="text-xs text-white/40">· Talibon Polytechnic College</span>
            </div>

            {{-- Title --}}
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white leading-tight max-w-3xl">
                {{ $post->title }}
            </h1>

            @if($post->excerpt)
                <p class="mt-4 text-sm text-white/70 leading-relaxed max-w-2xl">
                    {{ $post->excerpt }}
                </p>
            @endif
        </div>

        {{-- Wave divider --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-8 sm:h-10">
                <path d="M0 40 C360 0 1080 0 1440 40 L1440 40 L0 40 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         ARTICLE BODY
    ══════════════════════════════════════ --}}
    <section class="bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 py-12">

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                {{-- ── Featured Image ── --}}
                @if($post->image_path)
                    <div class="relative bg-gray-50 border-b border-gray-100">
                        {{-- Accent top bar --}}
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-tpc-primary via-tpc-primary to-tpc-accent z-10"></div>

                        <div class="flex items-center justify-center p-6 pt-7">
                            <a href="{{ asset('storage/' . $post->image_path) }}"
                               target="_blank" rel="noopener"
                               class="group relative block">
                                <img src="{{ asset('storage/' . $post->image_path) }}"
                                     alt="{{ $post->title }}"
                                     class="w-full max-h-[520px] object-contain rounded-xl hover:opacity-95 transition"
                                     loading="lazy" />
                                {{-- View hint overlay --}}
                                <span class="absolute inset-0 rounded-xl flex items-end justify-end p-3 opacity-0 group-hover:opacity-100 transition">
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
                <div class="p-7 sm:p-10">

                    {{-- Pull quote / excerpt (if no image and has excerpt) --}}
                    @if($post->excerpt && !$post->image_path)
                        <div class="relative border-l-4 border-tpc-primary bg-tpc-primary/5 px-6 py-5 mb-8 rounded-r-xl">
                            <svg class="absolute -top-1 -left-1 h-5 w-5 text-tpc-primary/30" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                            <p class="text-sm font-semibold text-gray-700 leading-relaxed italic">{{ $post->excerpt }}</p>
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
                        <div class="mt-10 pt-6 border-t border-gray-100 flex flex-wrap items-center gap-3 text-xs text-gray-400">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                                </svg>
                                Published {{ $post->published_at->format('F d, Y') }}
                            </span>
                            <span>·</span>
                            <span>Talibon Polytechnic College</span>
                            <span class="ml-auto inline-block bg-tpc-primary/10 text-tpc-primary text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                {{ $post->category ?? 'Announcement' }}
                            </span>
                        </div>
                    @endif

                    {{-- Divider --}}
                    <div class="my-8 border-t border-gray-100"></div>

                    {{-- ── Footer CTA ── --}}
                    <div class="relative bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                        {{-- Accent stripe --}}
                        <div class="h-1 bg-gradient-to-r from-tpc-primary to-tpc-accent"></div>
                        <div class="p-6 sm:flex sm:items-center sm:justify-between gap-6">
                            <div>
                                <p class="text-xs font-bold text-tpc-primary uppercase tracking-widest mb-1">Need Assistance?</p>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    For questions regarding this announcement, please contact the college office.
                                </p>
                            </div>
                            <div class="mt-4 sm:mt-0 flex flex-wrap gap-3 shrink-0">
                                <a href="{{ route('contact') }}"
                                   class="inline-flex items-center gap-2 rounded-lg border-2 border-tpc-primary bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white
                                          hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Contact Us
                                </a>
                                <a href="{{ route('academics') }}"
                                   class="inline-flex items-center gap-1 rounded-lg border-2 border-tpc-primary px-5 py-2.5 text-sm font-bold text-tpc-primary
                                          hover:bg-tpc-primary hover:text-white transition">
                                    View Programs
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Back navigation --}}
            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('news.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-bold text-tpc-primary hover:text-tpc-secondary transition group">
                    <svg class="h-4 w-4 transition-transform group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
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

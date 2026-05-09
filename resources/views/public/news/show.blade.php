{{-- resources/views/public/news/show.blade.php --}}
@extends('layouts.site')

@section('title', $post->title)

@section('content')

    {{-- ARTICLE HEADER --}}
    <section class="relative overflow-hidden bg-tpc-primary">
        <div class="max-w-4xl mx-auto px-4 py-10">
            <a href="{{ route('news.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-white/70 hover:text-white uppercase tracking-wide transition mb-6">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to News
            </a>

            <div class="mb-4">
                <span class="inline-block bg-white/20 text-white text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full backdrop-blur-sm">
                    {{ $post->category ?? 'Announcement' }}
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white leading-tight">
                {{ $post->title }}
            </h1>

            <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-white/60">
                @if($post->published_at)
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                        <time datetime="{{ $post->published_at->toDateString() }}">
                            {{ $post->published_at->format('F d, Y') }}
                        </time>
                    </span>
                    <span>·</span>
                @endif
                <span>Talibon Polytechnic College</span>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-8 sm:h-10">
                <path d="M0 40 C360 0 1080 0 1440 40 L1440 40 L0 40 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- ARTICLE BODY --}}
    <section class="bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 py-12">

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                {{-- Featured Image --}}
                @if($post->image_path)
                    <div class="border-b border-gray-100 bg-gray-50">
                        <div class="flex items-center justify-center p-6">
                            <a href="{{ asset('storage/' . $post->image_path) }}" target="_blank" rel="noopener">
                                <img src="{{ asset('storage/' . $post->image_path) }}"
                                     alt="{{ $post->title }}"
                                     class="w-full max-h-[520px] object-contain hover:opacity-90 transition rounded-lg"
                                     loading="lazy" />
                            </a>
                        </div>
                        <div class="border-t border-gray-100 px-6 py-2">
                            <p class="text-xs text-gray-400 italic">Click image to view full size</p>
                        </div>
                    </div>
                @endif

                <div class="p-7 sm:p-10">

                    {{-- Excerpt --}}
                    @if($post->excerpt)
                        <div class="border-l-4 border-tpc-primary bg-tpc-primary/5 px-5 py-4 mb-8 rounded-r-lg">
                            <p class="text-sm font-semibold text-gray-700 leading-relaxed">{{ $post->excerpt }}</p>
                        </div>
                    @endif

                    {{-- Body --}}
                    <article class="prose prose-sm sm:prose max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($post->body)) !!}
                    </article>

                    {{-- Divider --}}
                    <div class="my-10 border-t border-gray-100"></div>

                    {{-- Footer CTA --}}
                    <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-tpc-primary px-6 py-3">
                            <p class="text-xs font-bold text-white uppercase tracking-widest">Need Assistance?</p>
                        </div>
                        <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <p class="text-sm text-gray-600">
                                For questions regarding this announcement, please contact the college office.
                            </p>
                            <div class="flex flex-wrap gap-3 shrink-0">
                                <a href="{{ route('contact') }}"
                                   class="inline-flex items-center rounded-lg border-2 border-tpc-primary bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                    Contact Us
                                </a>
                                <a href="{{ route('academics') }}"
                                   class="inline-flex items-center rounded-lg border-2 border-tpc-primary px-5 py-2.5 text-sm font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
                                    View Programs →
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Back link --}}
            <div class="mt-8 text-center">
                <a href="{{ route('news.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-bold text-tpc-primary hover:text-tpc-secondary transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to all news
                </a>
            </div>

        </div>
    </section>

@endsection

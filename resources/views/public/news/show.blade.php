{{-- resources/views/public/news/show.blade.php --}}
@extends('layouts.site')

@section('title', $post->title)

@section('content')

    {{-- ARTICLE HEADER --}}
    <section class="bg-tpc-primary">
        <div class="max-w-4xl mx-auto px-4 py-10">
            <a href="{{ route('news.index') }}"
               class="inline-flex items-center text-xs font-bold text-white/70 hover:text-white uppercase tracking-wide transition mb-5">
                ← Back to News
            </a>

            <div class="mb-4">
                <span class="bg-white text-tpc-primary text-[11px] font-bold uppercase tracking-wider px-2 py-1">
                    {{ $post->category ?? 'Announcement' }}
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white leading-tight">
                {{ $post->title }}
            </h1>

            <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-white/60">
                @if($post->published_at)
                    <time datetime="{{ $post->published_at->toDateString() }}">
                        {{ $post->published_at->format('F d, Y') }}
                    </time>
                    <span>·</span>
                @endif
                <span>Talibon Polytechnic College</span>
            </div>
        </div>
    </section>

    {{-- ARTICLE BODY --}}
    <section class="bg-white">
        <div class="max-w-4xl mx-auto px-4 py-12">

            {{-- Featured Image --}}
            @if($post->image_path)
                <div class="border border-gray-200 bg-white mb-8">
                    <div class="flex items-center justify-center p-4">
                        <a href="{{ asset('storage/' . $post->image_path) }}" target="_blank" rel="noopener">
                            <img src="{{ asset('storage/' . $post->image_path) }}"
                                 alt="{{ $post->title }}"
                                 class="w-full max-h-[560px] object-contain hover:opacity-90 transition"
                                 loading="lazy" />
                        </a>
                    </div>
                    <div class="border-t border-gray-100 px-4 py-2">
                        <p class="text-xs text-gray-400 italic">Click image to view full size</p>
                    </div>
                </div>
            @endif

            {{-- Excerpt --}}
            @if($post->excerpt)
                <div class="border-l-4 border-tpc-primary bg-tpc-primary/5 px-5 py-4 mb-8">
                    <p class="text-sm font-semibold text-gray-700 leading-relaxed">{{ $post->excerpt }}</p>
                </div>
            @endif

            {{-- Body --}}
            <article class="prose prose-sm sm:prose max-w-none text-gray-700 leading-relaxed">
                {!! nl2br(e($post->body)) !!}
            </article>

            {{-- Divider --}}
            <div class="my-10 border-t-4 border-double border-gray-200"></div>

            {{-- Footer CTA --}}
            <div class="border border-gray-200">
                <div class="bg-tpc-primary px-5 py-3">
                    <p class="text-xs font-bold text-white uppercase tracking-widest">Need Assistance?</p>
                </div>
                <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-sm text-gray-600">
                        For questions regarding this announcement, please contact the college office.
                    </p>
                    <div class="flex flex-wrap gap-3 shrink-0">
                        <a href="{{ route('contact') }}"
                           class="inline-flex items-center border-2 border-tpc-primary bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                            Contact Us
                        </a>
                        <a href="{{ route('academics') }}"
                           class="inline-flex items-center border-2 border-tpc-primary px-5 py-2.5 text-sm font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
                            View Programs →
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

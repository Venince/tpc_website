{{-- resources/views/public/news/show.blade.php --}}
@extends('layouts.site')

@section('title', $post->title)

@section('content')
    <section class="bg-transparent">
        <div class="max-w-4xl mx-auto px-4 py-12">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('news.index') }}"
                   class="text-sm font-medium text-tpc-primary hover:text-tpc-secondary">
                    ← Back to News
                </a>

                <span class="inline-flex items-center rounded-full bg-tpc-accent/30 px-3 py-1 text-xs font-medium text-tpc-secondary">
                    {{ $post->category ?? 'Announcement' }}
                </span>
            </div>

            <h1 class="mt-5 text-3xl font-semibold tracking-tight text-tpc-ink sm:text-4xl">
                {{ $post->title }}
            </h1>

            <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-tpc-ink/60">
                @if ($post->published_at)
                    <time datetime="{{ $post->published_at->toDateString() }}">
                        Published: {{ $post->published_at->format('M d, Y') }}
                    </time>
                @endif

                <span>•</span>

                <span>Talibon Polytechnic College</span>
            </div>

                @if ($post->image_path)
                    <div class="mt-7 overflow-hidden rounded-2xl border border-tpc-primary/10 bg-white shadow-sm">
                        <div class="flex items-center justify-center p-4">
                            <img
                                src="{{ asset('storage/' . $post->image_path) }}"
                                alt="{{ $post->title }} image"
                                class="w-full max-h-[520px] object-contain"
                                loading="lazy"
                            />
                        </div>
                    </div>
                @endif

            @if ($post->excerpt)
                <p class="mt-6 rounded-xl border border-tpc-primary/10 bg-tpc-primary/5 p-4 text-sm text-tpc-ink/80">
                    {{ $post->excerpt }}
                </p>
            @endif

            <article class="prose max-w-none mt-8">
                {!! nl2br(e($post->body)) !!}
            </article>

            <div class="mt-10 rounded-2xl border border-tpc-primary/10 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-tpc-ink">Need assistance?</h2>
                <p class="mt-2 text-sm text-tpc-ink/70">
                    For questions regarding this announcement, please contact the college office.
                </p>
                <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center justify-center rounded-lg bg-tpc-primary px-5 py-3 text-sm font-medium text-white hover:bg-tpc-secondary">
                        Contact Us
                    </a>
                    <a href="{{ route('academics') }}"
                       class="inline-flex items-center justify-center rounded-lg border border-tpc-primary/30 bg-white px-5 py-3 text-sm font-medium text-tpc-primary hover:bg-tpc-primary/5">
                        View Programs →
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection{{-- resources/views/public/news/show.blade.php --}}
@extends('layouts.site')

@section('title', $post->title)

@section('content')
    <section class="bg-transparent">
        <div class="max-w-4xl mx-auto px-4 py-12">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('news.index') }}"
                   class="text-sm font-medium text-tpc-primary hover:text-tpc-secondary">
                    ← Back to News
                </a>

                <span class="inline-flex items-center rounded-full bg-tpc-accent/30 px-3 py-1 text-xs font-medium text-tpc-secondary">
                    {{ $post->category ?? 'Announcement' }}
                </span>
            </div>

            <h1 class="mt-5 text-3xl font-semibold tracking-tight text-tpc-ink sm:text-4xl">
                {{ $post->title }}
            </h1>

            <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-tpc-ink/60">
                @if ($post->published_at)
                    <time datetime="{{ $post->published_at->toDateString() }}">
                        Published: {{ $post->published_at->format('M d, Y') }}
                    </time>
                @endif

                <span>•</span>
                <span>Talibon Polytechnic College</span>
            </div>

            {{-- Featured Image (SHOW FULL IMAGE, NO CROP) --}}
            @if ($post->image_path)
                <div class="mt-8 overflow-hidden rounded-2xl border border-tpc-primary/10 bg-white shadow-sm">
                    <div class="flex items-center justify-center p-4">
                        <a href="{{ asset('storage/' . $post->image_path) }}" target="_blank" rel="noopener">
                            <img
                                src="{{ asset('storage/' . $post->image_path) }}"
                                alt="{{ $post->title }} image"
                                class="w-full max-h-[520px] object-contain"
                                loading="lazy"
                            />
                        </a>
                    </div>
                </div>
            @endif

            @if ($post->excerpt)
                <p class="mt-6 rounded-xl border border-tpc-primary/10 bg-tpc-primary/5 p-4 text-sm text-tpc-ink/80">
                    {{ $post->excerpt }}
                </p>
            @endif

            <article class="prose max-w-none mt-8">
                {!! nl2br(e($post->body)) !!}
            </article>

            <div class="mt-10 rounded-2xl border border-tpc-primary/10 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-tpc-ink">Need assistance?</h2>
                <p class="mt-2 text-sm text-tpc-ink/70">
                    For questions regarding this announcement, please contact the college office.
                </p>
                <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center justify-center rounded-lg bg-tpc-primary px-5 py-3 text-sm font-medium text-white hover:bg-tpc-secondary">
                        Contact Us
                    </a>
                    <a href="{{ route('academics') }}"
                       class="inline-flex items-center justify-center rounded-lg border border-tpc-primary/30 bg-white px-5 py-3 text-sm font-medium text-tpc-primary hover:bg-tpc-primary/5">
                        View Programs →
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection


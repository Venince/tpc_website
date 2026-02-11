{{-- resources/views/public/news/index.blade.php --}}
@extends('layouts.site')

@section('title', 'News')

@section('content')
    {{-- PAGE HEADER --}}
    <section class="bg-transparent">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <p class="text-xs font-medium tracking-wide text-tpc-primary uppercase">Updates</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-tpc-ink sm:text-4xl">News & Announcements</h1>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-tpc-ink/70">
                Read the latest campus announcements, events, advisories, and scholarship updates.
            </p>

            {{-- Filters --}}
            <form method="GET" action="{{ route('news.index') }}" class="mt-6 grid gap-3 sm:grid-cols-3">
                <input name="q" value="{{ request('q') }}" placeholder="Search news..."
                       class="rounded-lg border border-tpc-primary/20 px-3 py-2 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20" />

                <select name="category"
                        class="rounded-lg border border-tpc-primary/20 px-3 py-2 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20">
                    <option value="">All categories</option>
                    @foreach (['Announcement','Event','Advisory','Scholarship'] as $cat)
                        <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>

                <button class="rounded-lg bg-tpc-primary px-4 py-2 text-sm font-medium text-white hover:bg-tpc-secondary">
                    Apply Filters
                </button>
            </form>

            @if (request('q') || request('category'))
                <div class="mt-3">
                    <a href="{{ route('news.index') }}"
                       class="text-sm font-medium text-tpc-primary hover:text-tpc-secondary">
                        Clear filters
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- POSTS GRID --}}
    <section class="bg-transparent">
        <div class="max-w-7xl mx-auto px-4 pb-20">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($posts as $post)
                    <article class="group overflow-hidden rounded-2xl border border-tpc-primary/10 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-tpc-primary/30 hover:shadow-md">
                        {{-- Image (NO CROP in index; looks consistent) --}}
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

                            <h2 class="mt-3 text-lg font-semibold text-tpc-ink group-hover:text-tpc-primary transition">
                                <a href="{{ route('news.show', $post) }}">
                                    {{ $post->title }}
                                </a>
                            </h2>

                            <p class="mt-3 text-sm leading-relaxed text-tpc-ink/70">
                                {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 150) }}
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
                    <div class="rounded-2xl border border-dashed border-tpc-primary/30 p-10 text-center text-tpc-ink/70 lg:col-span-3">
                        No published news posts found.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        </div>
    </section>
@endsection

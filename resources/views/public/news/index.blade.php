{{-- resources/views/public/news/index.blade.php --}}
@extends('layouts.site')

@section('title', 'News')

@section('content')

    {{-- PAGE HEADER --}}
    <section class="bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <p class="text-xs font-bold tracking-widest text-tpc-accent uppercase mb-1">Talibon Polytechnic College</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-white leading-tight">News & Announcements</h1>
            <p class="mt-2 max-w-2xl text-sm text-white/75 leading-relaxed">
                Read the latest campus announcements, events, advisories, and scholarship updates.
            </p>

            {{-- Filters --}}
            <form method="GET" action="{{ route('news.index') }}" class="mt-6 flex flex-wrap gap-3">
                <input name="q" value="{{ request('q') }}" placeholder="Search news..."
                       class="border-2 border-white/40 bg-white/10 text-white placeholder-white/50 px-3 py-2 text-sm focus:border-white focus:outline-none backdrop-blur-sm w-64" />

                <select name="category"
                        class="border-2 border-white/40 bg-white/10 text-white px-3 py-2 text-sm focus:border-white focus:outline-none">
                    <option value="" class="text-tpc-ink">All categories</option>
                    @foreach(['Announcement','Event','Advisory','Scholarship'] as $cat)
                        <option value="{{ $cat }}" class="text-tpc-ink" @selected(request('category') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>

                <button class="border-2 border-white bg-white px-5 py-2 text-sm font-bold text-tpc-primary hover:bg-tpc-accent hover:border-tpc-accent transition">
                    Filter
                </button>

                @if(request('q') || request('category'))
                    <a href="{{ route('news.index') }}"
                       class="inline-flex items-center border-2 border-white/40 px-5 py-2 text-sm font-bold text-white/80 hover:text-white hover:border-white transition">
                        Clear ×
                    </a>
                @endif
            </form>
        </div>
    </section>

    {{-- POSTS --}}
    <section class="bg-white">
        <div class="max-w-7xl mx-auto px-4 py-12">

            <div class="flex items-center gap-4 mb-8">
                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">
                    {{ request('q') || request('category') ? 'Search Results' : 'All Posts' }}
                </h2>
                <div class="flex-1 h-px bg-gray-200"></div>
                @if($posts->total() > 0)
                    <span class="text-xs text-gray-400">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}</span>
                @endif
            </div>

            @if($posts->isNotEmpty())
                <div class="grid gap-px bg-gray-200 border border-gray-200 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($posts as $post)
                        <article class="group bg-white flex flex-col hover:bg-gray-50 transition">
                            @if($post->image_path)
                                <a href="{{ route('news.show', $post) }}" class="block border-b border-gray-200 bg-white overflow-hidden">
                                    <div class="flex items-center justify-center p-4 h-44">
                                        <img src="{{ asset('storage/' . $post->image_path) }}"
                                             alt="{{ $post->title }}"
                                             class="max-h-full w-full object-contain group-hover:scale-[1.02] transition duration-300"
                                             loading="lazy" />
                                    </div>
                                </a>
                            @else
                                <div class="h-1.5 bg-tpc-primary"></div>
                            @endif

                            <div class="p-5 flex flex-col flex-1">
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <span class="bg-tpc-primary text-white text-[10px] font-bold uppercase tracking-wider px-2 py-0.5">
                                        {{ $post->category ?? 'Announcement' }}
                                    </span>
                                    @if($post->published_at)
                                        <time class="text-[11px] text-gray-400" datetime="{{ $post->published_at->toDateString() }}">
                                            {{ $post->published_at->format('M d, Y') }}
                                        </time>
                                    @endif
                                </div>

                                <h2 class="text-base font-bold text-tpc-ink group-hover:text-tpc-primary transition leading-snug">
                                    <a href="{{ route('news.show', $post) }}">{{ $post->title }}</a>
                                </h2>

                                <p class="mt-2 text-sm text-gray-500 leading-relaxed flex-1">
                                    {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 120) }}
                                </p>

                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <a href="{{ route('news.show', $post) }}"
                                       class="text-xs font-bold text-tpc-primary hover:text-tpc-secondary uppercase tracking-wide transition">
                                        Read Article →
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="border border-dashed border-gray-300 py-20 text-center text-gray-400 text-sm">
                    No posts found{{ request('q') ? ' for "' . request('q') . '"' : '' }}.
                </div>
            @endif
        </div>
    </section>

@endsection

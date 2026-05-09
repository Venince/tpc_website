{{-- resources/views/public/news/index.blade.php --}}
@extends('layouts.site')

@section('title', 'News')

@section('content')

    {{-- PAGE HEADER --}}
    <section class="relative overflow-hidden bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <p class="text-xs font-bold tracking-widest text-tpc-accent uppercase mb-1">Talibon Polytechnic College</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-white leading-tight">News & Announcements</h1>
            <p class="mt-2 max-w-2xl text-sm text-white/75 leading-relaxed">
                Read the latest campus announcements, events, advisories, and scholarship updates.
            </p>

            {{-- Filters --}}
            <form method="GET" action="{{ route('news.index') }}" class="mt-6 flex flex-wrap gap-3">
                <input name="q" value="{{ request('q') }}" placeholder="Search news..."
                       class="rounded-lg border-2 border-white/30 bg-white/10 text-white placeholder-white/50 px-4 py-2 text-sm focus:border-white focus:outline-none backdrop-blur-sm w-64" />

                <select name="category"
                        class="rounded-lg border-2 border-white/30 bg-white/10 text-white px-4 py-2 text-sm focus:border-white focus:outline-none">
                    <option value="" class="text-tpc-ink">All categories</option>
                    @foreach(['Announcement','Event','Advisory','Scholarship'] as $cat)
                        <option value="{{ $cat }}" class="text-tpc-ink" @selected(request('category') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>

                <button class="rounded-lg border-2 border-white bg-white px-5 py-2 text-sm font-bold text-tpc-primary hover:bg-tpc-accent hover:border-tpc-accent transition">
                    Filter
                </button>

                @if(request('q') || request('category'))
                    <a href="{{ route('news.index') }}"
                       class="inline-flex items-center rounded-lg border-2 border-white/40 px-5 py-2 text-sm font-bold text-white/80 hover:text-white hover:border-white transition">
                        Clear ×
                    </a>
                @endif
            </form>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-8 sm:h-10">
                <path d="M0 40 C360 0 1080 0 1440 40 L1440 40 L0 40 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- POSTS --}}
    <section class="bg-gray-50 min-h-[40vh]">
        <div class="max-w-7xl mx-auto px-4 py-14">

            <div class="flex items-center gap-4 mb-10">
                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">
                    {{ request('q') || request('category') ? 'Search Results' : 'All Posts' }}
                </h2>
                <div class="flex-1 h-px bg-gray-200"></div>
                @if($posts->total() > 0)
                    <span class="text-xs text-gray-400 font-medium">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}</span>
                @endif
            </div>

            @if($posts->isNotEmpty())

                {{-- Featured first post --}}
                @php $featured = $posts->first(); @endphp
                @if($posts->currentPage() === 1)
                <a href="{{ route('news.show', $featured) }}"
                   class="group block bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md hover:border-tpc-primary/40 transition-all duration-300 overflow-hidden mb-8">
                    <div class="sm:flex">
                        @if($featured->image_path)
                            <div class="sm:w-2/5 bg-gray-100 flex items-center justify-center overflow-hidden">
                                <img src="{{ asset('storage/' . $featured->image_path) }}"
                                     alt="{{ $featured->title }}"
                                     class="w-full h-56 sm:h-full object-contain group-hover:scale-[1.02] transition duration-300"
                                     loading="lazy" />
                            </div>
                        @endif
                        <div class="flex-1 p-7 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="inline-block bg-tpc-primary text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                                        {{ $featured->category ?? 'Announcement' }}
                                    </span>
                                    <span class="inline-block bg-tpc-accent/20 text-tpc-primary text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                                        Latest
                                    </span>
                                    @if($featured->published_at)
                                        <time class="text-xs text-gray-400 ml-auto" datetime="{{ $featured->published_at->toDateString() }}">
                                            {{ $featured->published_at->format('F d, Y') }}
                                        </time>
                                    @endif
                                </div>
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 group-hover:text-tpc-primary transition leading-snug mb-3">
                                    {{ $featured->title }}
                                </h2>
                                <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">
                                    {{ $featured->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($featured->body), 180) }}
                                </p>
                            </div>
                            <div class="mt-6 flex items-center gap-2 text-sm font-bold text-tpc-primary group-hover:gap-3 transition-all">
                                Read Article
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
                @endif

                {{-- Remaining posts grid --}}
                @php $remaining = $posts->currentPage() === 1 ? $posts->skip(1) : $posts; @endphp
                @if($remaining->isNotEmpty())
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($remaining as $post)
                        <article class="group bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md hover:border-tpc-primary/40 transition-all duration-300 overflow-hidden flex flex-col">

                            {{-- Top accent bar --}}
                            <div class="h-1.5 w-full bg-tpc-primary group-hover:bg-tpc-accent transition-colors duration-300"></div>

                            @if($post->image_path)
                                <a href="{{ route('news.show', $post) }}" class="block bg-gray-50 overflow-hidden">
                                    <div class="flex items-center justify-center h-44 p-3">
                                        <img src="{{ asset('storage/' . $post->image_path) }}"
                                             alt="{{ $post->title }}"
                                             class="max-h-full w-full object-contain group-hover:scale-[1.02] transition duration-300"
                                             loading="lazy" />
                                    </div>
                                </a>
                            @endif

                            <div class="p-5 flex flex-col flex-1">
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <span class="inline-block bg-tpc-primary/10 text-tpc-primary text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                        {{ $post->category ?? 'Announcement' }}
                                    </span>
                                    @if($post->published_at)
                                        <time class="text-[11px] text-gray-400" datetime="{{ $post->published_at->toDateString() }}">
                                            {{ $post->published_at->format('M d, Y') }}
                                        </time>
                                    @endif
                                </div>

                                <h2 class="text-sm font-bold text-gray-800 group-hover:text-tpc-primary transition leading-snug flex-1">
                                    <a href="{{ route('news.show', $post) }}">{{ $post->title }}</a>
                                </h2>

                                <p class="mt-2 text-xs text-gray-500 leading-relaxed line-clamp-2">
                                    {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 100) }}
                                </p>

                                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                    <a href="{{ route('news.show', $post) }}"
                                       class="inline-flex items-center gap-1 text-xs font-bold text-tpc-primary group-hover:gap-2 transition-all duration-200">
                                        Read Article
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                @endif

                <div class="mt-10">
                    {{ $posts->links() }}
                </div>

            @else
                <div class="py-24 text-center text-gray-400 text-sm border border-dashed border-gray-300 rounded-2xl bg-white">
                    <p class="text-lg font-semibold text-gray-300 mb-1">No posts found</p>
                    <p>{{ request('q') ? 'Try a different search term.' : 'Check back soon for updates.' }}</p>
                </div>
            @endif
        </div>
    </section>

@endsection

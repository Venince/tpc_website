@php
/** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Models\NewsPost[] $posts */
@endphp

@extends('admin.layout', ['title' => 'News Posts'])

@section('content')

    {{-- Top actions only (title moved to header) --}}
    <div class="flex items-center justify-end gap-3">
        <a
            href="{{ route('admin.news-posts.create') }}"
            class="inline-flex items-center gap-2 rounded-2xl bg-tpc-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm
                   hover:bg-tpc-secondary transition
                   focus:outline-none focus:ring-2 focus:ring-tpc-primary/30"
        >
            <span class="text-base leading-none">+</span>
            New Post
        </a>
    </div>

    {{-- ✅ Mobile cards --}}
    <div class="mt-5 space-y-3 sm:hidden">
        @forelse ($posts as $post)
            <div class="rounded-2xl border border-tpc-primary/10 bg-white p-4 shadow-sm">
                <div class="flex items-start gap-4">
                    {{-- Image --}}
                    <div class="shrink-0">
                        @if($post->image_path)
                            <img
                                src="{{ asset('storage/' . $post->image_path) }}"
                                alt="Post image"
                                class="h-14 w-20 rounded-xl border border-tpc-primary/10 bg-white object-cover"
                                loading="lazy"
                            />
                        @else
                            <div class="h-14 w-20 rounded-xl bg-tpc-primary/5 border border-tpc-primary/10 grid place-items-center text-tpc-ink/40 text-xs">
                                —
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <div class="truncate text-sm font-semibold text-tpc-ink">
                                {{ $post->title }}
                            </div>

                            {{-- Status badge --}}
                            @if($post->is_published)
                                <span class="shrink-0 rounded-full bg-tpc-accent/30 px-2 py-1 text-xs font-semibold text-tpc-secondary">
                                    Published
                                </span>
                            @else
                                <span class="shrink-0 rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">
                                    Draft
                                </span>
                            @endif
                        </div>

                        <div class="mt-1 text-xs text-tpc-ink/60">
                            Category: <span class="font-medium text-tpc-ink/80">{{ $post->category }}</span>
                        </div>

                        <div class="mt-3 flex items-center justify-end gap-4">
                            <a
                                href="{{ route('admin.news-posts.edit', $post) }}"
                                class="text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition"
                            >
                                Edit
                            </a>

                            <form
                                method="POST"
                                action="{{ route('admin.news-posts.destroy', $post) }}"
                                onsubmit="return confirm('Delete this news post?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button class="text-sm font-semibold text-red-600 hover:text-red-700 transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-tpc-primary/10 bg-white p-6 text-center text-tpc-ink/70">
                No news posts yet.
            </div>
        @endforelse

        <div class="pt-2">
            {{ $posts->links() }}
        </div>
    </div>

    {{-- ✅ Desktop table --}}
    <div class="mt-5 hidden sm:block overflow-hidden rounded-2xl border border-tpc-primary/10 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-tpc-primary/5 text-tpc-ink/70">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Image</th>
                        <th class="px-4 py-3 text-left font-medium">Title</th>
                        <th class="px-4 py-3 text-left font-medium">Category</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-tpc-primary/5 transition">
                            <td class="px-4 py-3">
                                @if($post->image_path)
                                    <img
                                        src="{{ asset('storage/' . $post->image_path) }}"
                                        alt="Post image"
                                        class="h-10 w-14 rounded-xl border border-tpc-primary/10 bg-white object-cover"
                                        loading="lazy"
                                    />
                                @else
                                    <span class="inline-flex h-10 w-14 items-center justify-center rounded-xl bg-tpc-primary/5 text-tpc-ink/40 text-xs">
                                        —
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 font-medium">{{ $post->title }}</td>
                            <td class="px-4 py-3 text-tpc-ink/70">{{ $post->category }}</td>
                            <td class="px-4 py-3">
                                @if($post->is_published)
                                    <span class="rounded-full bg-tpc-accent/30 px-2 py-1 text-xs font-semibold text-tpc-secondary">
                                        Published
                                    </span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a
                                    href="{{ route('admin.news-posts.edit', $post) }}"
                                    class="font-semibold text-tpc-primary hover:text-tpc-secondary transition"
                                >
                                    Edit
                                </a>

                                <form
                                    class="inline"
                                    method="POST"
                                    action="{{ route('admin.news-posts.destroy', $post) }}"
                                    onsubmit="return confirm('Delete this news post?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button class="ml-3 font-semibold text-red-600 hover:text-red-700 transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-8 text-center text-tpc-ink/70" colspan="5">No news posts yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 hidden sm:block">
        {{ $posts->links() }}
    </div>

@endsection

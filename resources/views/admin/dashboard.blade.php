@extends('admin.layout', ['title' => 'Dashboard'])

@section('content')

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
            <p class="text-xs text-tpc-ink/60">Programs</p>
            <p class="mt-2 text-2xl font-semibold text-tpc-ink">{{ $programCount }}</p>
        </div>
        <div class="rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
            <p class="text-xs text-tpc-ink/60">Active Programs</p>
            <p class="mt-2 text-2xl font-semibold text-tpc-ink">{{ $activeProgramCount }}</p>
        </div>
        <div class="rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
            <p class="text-xs text-tpc-ink/60">News Posts</p>
            <p class="mt-2 text-2xl font-semibold text-tpc-ink">{{ $newsCount }}</p>
        </div>
        <div class="rounded-2xl border border-tpc-primary/10 bg-white p-5 shadow-sm">
            <p class="text-xs text-tpc-ink/60">Published News</p>
            <p class="mt-2 text-2xl font-semibold text-tpc-ink">{{ $publishedNewsCount }}</p>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-tpc-primary/10 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-tpc-ink">Recent News</h2>
            <a href="{{ route('admin.news-posts.index') }}" class="text-sm font-medium text-tpc-primary hover:text-tpc-secondary">
                Manage →
            </a>
        </div>

        <div class="mt-4 divide-y">
            @forelse ($recentNews as $post)
                <div class="py-3 flex items-center justify-between gap-4">
                    <div>
                        <p class="font-medium text-tpc-ink">{{ $post->title }}</p>
                        <p class="text-xs text-tpc-ink/60">
                            {{ $post->category }} • {{ $post->created_at->format('M d, Y') }}
                            • {{ $post->is_published ? 'Published' : 'Draft' }}
                        </p>
                    </div>
                    <a class="text-sm font-medium text-tpc-primary hover:text-tpc-secondary"
                       href="{{ route('admin.news-posts.edit', $post) }}">
                        Edit
                    </a>
                </div>
            @empty
                <p class="py-6 text-sm text-tpc-ink/70">No news posts yet.</p>
            @endforelse
        </div>
    </div>
@endsection

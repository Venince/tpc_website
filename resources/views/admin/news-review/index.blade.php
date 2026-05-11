@extends('admin.layout', ['title' => 'News Review'])

@section('content')

    <div class="flex items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-tpc-ink">News Review Queue</h1>
            <p class="mt-1 text-sm text-tpc-ink/70">Approve or decline posts submitted by admins.</p>
        </div>
    </div>

    {{-- Status tabs --}}
    <div class="mt-5 flex gap-2 flex-wrap">
        @foreach([
            'pending'  => ['Pending',  $counts['pending'],  'bg-yellow-100 text-yellow-800', 'border-yellow-300'],
            'approved' => ['Approved', $counts['approved'], 'bg-tpc-accent/30 text-tpc-secondary', 'border-tpc-primary/30'],
            'declined' => ['Declined', $counts['declined'], 'bg-red-100 text-red-700', 'border-red-200'],
        ] as $key => [$label, $count, $activeCls, $activeBorder])
            <a
                href="{{ route('admin.news-review.index', ['status' => $key]) }}"
                class="inline-flex items-center gap-2 rounded-full border px-4 py-1.5 text-sm font-semibold transition
                       {{ $status === $key
                            ? $activeCls . ' ' . $activeBorder
                            : 'border-tpc-primary/15 bg-white text-tpc-ink/60 hover:border-tpc-primary/30 hover:text-tpc-ink' }}"
            >
                {{ $label }}
                <span class="rounded-full px-1.5 py-0.5 text-xs font-bold
                             {{ $status === $key ? 'bg-white/60' : 'bg-tpc-primary/10 text-tpc-ink/70' }}">
                    {{ $count }}
                </span>
            </a>
        @endforeach
    </div>

    {{-- Mobile cards --}}
    <div class="mt-5 space-y-3 sm:hidden">
        @forelse ($posts as $post)
            <div class="rounded-2xl border border-tpc-primary/10 bg-white p-4 shadow-sm">
                <div class="flex items-start gap-3">
                    @if($post->image_path)
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt=""
                             class="h-14 w-20 shrink-0 rounded-xl object-cover border border-tpc-primary/10" loading="lazy" />
                    @else
                        <div class="h-14 w-20 shrink-0 rounded-xl bg-tpc-primary/5 border border-tpc-primary/10 grid place-items-center text-xs text-tpc-ink/30">—</div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <div class="truncate text-sm font-semibold text-tpc-ink">{{ $post->title }}</div>
                        <div class="mt-0.5 text-xs text-tpc-ink/60">{{ $post->category }} · {{ $post->created_at->format('M d, Y') }}</div>
                        @if($post->review_note)
                            <p class="mt-1 text-xs text-red-600 italic truncate">{{ $post->review_note }}</p>
                        @endif
                        <div class="mt-3 flex gap-3">
                            <a href="{{ route('admin.news-review.show', $post) }}"
                               class="text-sm font-semibold text-tpc-primary hover:text-tpc-secondary">Preview</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-tpc-primary/10 bg-white p-6 text-center text-tpc-ink/60">
                No {{ $status }} posts.
            </div>
        @endforelse

        <div class="pt-2">{{ $posts->links() }}</div>
    </div>

    {{-- Desktop table --}}
    <div class="mt-5 hidden sm:block overflow-hidden rounded-2xl border border-tpc-primary/10 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-tpc-primary/5 text-tpc-ink/70">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Image</th>
                        <th class="px-4 py-3 text-left font-medium">Title</th>
                        <th class="px-4 py-3 text-left font-medium">Category</th>
                        <th class="px-4 py-3 text-left font-medium">Submitted</th>
                        <th class="px-4 py-3 text-left font-medium">Review Note</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-tpc-primary/5 transition">
                            <td class="px-4 py-3">
                                @if($post->image_path)
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt=""
                                         class="h-10 w-14 rounded-xl border border-tpc-primary/10 object-cover" loading="lazy" />
                                @else
                                    <span class="inline-flex h-10 w-14 items-center justify-center rounded-xl bg-tpc-primary/5 text-tpc-ink/40 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium max-w-[220px] truncate">{{ $post->title }}</td>
                            <td class="px-4 py-3 text-tpc-ink/70">{{ $post->category }}</td>
                            <td class="px-4 py-3 text-tpc-ink/60 whitespace-nowrap">{{ $post->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-xs text-red-600 italic max-w-[160px] truncate">
                                {{ $post->review_note ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.news-review.show', $post) }}"
                                   class="font-semibold text-tpc-primary hover:text-tpc-secondary transition">
                                    Preview
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-8 text-center text-tpc-ink/70" colspan="6">No {{ $status }} posts.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 hidden sm:block">{{ $posts->links() }}</div>

@endsection

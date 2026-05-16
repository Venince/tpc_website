@extends('admin.layout', ['title' => 'News Review'])

@section('content')

    {{-- ── Page header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-lg font-bold text-tpc-ink">News Review Queue</h1>
            <p class="mt-0.5 text-xs text-tpc-ink/50">Approve or decline posts submitted by admins</p>
        </div>
    </div>

    {{-- ── Status tabs ── --}}
    <div class="flex gap-2 flex-wrap mb-5">
        @foreach([
            'pending'  => ['Pending',  $counts['pending'],  'bg-yellow-100 text-yellow-800 border-yellow-300'],
            'approved' => ['Approved', $counts['approved'], 'bg-tpc-accent/30 text-tpc-secondary border-tpc-primary/30'],
            'declined' => ['Declined', $counts['declined'], 'bg-red-100 text-red-700 border-red-200'],
        ] as $key => [$label, $count, $activeCls])
            <a href="{{ route('admin.news-review.index', ['status' => $key]) }}"
               class="inline-flex items-center gap-2 rounded-full border px-4 py-1.5 text-sm font-semibold transition
                      {{ $status === $key
                           ? $activeCls
                           : 'border-tpc-primary/15 bg-white text-tpc-ink/60 hover:border-tpc-primary/30 hover:text-tpc-ink' }}">
                {{ $label }}
                <span class="rounded-full px-1.5 py-0.5 text-xs font-bold
                             {{ $status === $key ? 'bg-white/60' : 'bg-tpc-primary/8 text-tpc-ink/60' }}">
                    {{ $count }}
                </span>
            </a>
        @endforeach
    </div>

    {{-- ── Mobile cards ── --}}
    <div class="space-y-3 sm:hidden">
        @forelse ($posts as $post)
            <div class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm overflow-hidden">
                <div class="flex items-start gap-3 p-4">
                    @if($post->image_path)
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt=""
                             class="h-16 w-20 shrink-0 rounded-xl object-cover border border-tpc-primary/10" loading="lazy" />
                    @else
                        <div class="h-16 w-20 shrink-0 rounded-xl bg-tpc-primary/5 border border-tpc-primary/10 grid place-items-center">
                            <svg class="h-5 w-5 text-tpc-ink/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 18h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v10.5a1.5 1.5 0 001.5 1.5z"/>
                            </svg>
                        </div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-tpc-ink leading-snug line-clamp-2">{{ $post->title }}</p>
                        <p class="mt-0.5 text-xs text-tpc-ink/50">
                            <span class="font-medium text-tpc-ink/70">{{ $post->category }}</span>
                            · {{ $post->created_at->format('M d, Y') }}
                        </p>
                        @if($post->review_note)
                            <p class="mt-1 text-xs text-red-600 italic line-clamp-2">{{ $post->review_note }}</p>
                        @endif
                    </div>
                </div>
                <div class="border-t border-tpc-primary/8 px-4 py-2.5 flex justify-end">
                    <a href="{{ route('admin.news-review.show', $post) }}"
                       class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-tpc-primary hover:bg-tpc-primary/8 transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Preview & Review
                    </a>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-tpc-primary/10 bg-white p-10 text-center">
                <svg class="mx-auto h-10 w-10 text-tpc-ink/15 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                </svg>
                <p class="text-sm font-medium text-tpc-ink/50">No {{ $status }} posts</p>
            </div>
        @endforelse

        <div class="pt-1">{{ $posts->links() }}</div>
    </div>

    {{-- ── Desktop table ── --}}
    <div class="hidden sm:block overflow-hidden rounded-2xl border border-tpc-primary/10 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-tpc-primary/8 bg-tpc-primary/4">
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-widest text-tpc-ink/50 w-20">Image</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-widest text-tpc-ink/50">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-widest text-tpc-ink/50 w-32">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-widest text-tpc-ink/50 w-28">Submitted</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-widest text-tpc-ink/50">Review Note</th>
                        <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-widest text-tpc-ink/50 w-28">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-tpc-primary/6">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-tpc-primary/3 transition">
                            <td class="px-4 py-3">
                                @if($post->image_path)
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt=""
                                         class="h-10 w-14 rounded-xl border border-tpc-primary/10 object-cover" loading="lazy" />
                                @else
                                    <div class="h-10 w-14 rounded-xl bg-tpc-primary/5 border border-tpc-primary/10 grid place-items-center">
                                        <svg class="h-4 w-4 text-tpc-ink/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 18h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v10.5a1.5 1.5 0 001.5 1.5z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-tpc-ink">{{ $post->title }}</span>
                                @if($post->excerpt)
                                    <p class="mt-0.5 text-xs text-tpc-ink/50 truncate max-w-xs">{{ $post->excerpt }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-lg bg-tpc-primary/8 px-2.5 py-1 text-xs font-semibold text-tpc-primary/90">
                                    {{ $post->category }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-tpc-ink/50 whitespace-nowrap">
                                {{ $post->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 text-xs text-red-600 italic max-w-[200px] truncate">
                                {{ $post->review_note ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.news-review.show', $post) }}"
                                   class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-tpc-primary hover:bg-tpc-primary/8 transition">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Preview
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-14 text-center">
                                <svg class="mx-auto h-10 w-10 text-tpc-ink/15 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                </svg>
                                <p class="text-sm font-medium text-tpc-ink/50">No {{ $status }} posts</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 hidden sm:block">{{ $posts->links() }}</div>

@endsection

@php
/** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Models\NewsPost[] $posts */
@endphp

@extends('admin.layout', ['title' => 'News Posts'])

@section('content')

    <div class="flex items-center justify-end gap-3">
        <a
            href="{{ route('admin.news-posts.create') }}"
            class="inline-flex items-center gap-2 rounded-2xl bg-tpc-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm
                   hover:bg-tpc-secondary transition focus:outline-none focus:ring-2 focus:ring-tpc-primary/30"
        >
            <span class="text-base leading-none">+</span>
            New Post
        </a>
    </div>

    {{-- Info banner for regular admins only --}}
    @if(!auth()->user()->is_super_admin)
        <div class="mt-4 rounded-2xl border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
            <span class="font-semibold">Note:</span>
            New posts are sent to the superadmin for approval before going live.
        </div>
    @endif

    {{-- ── Mobile cards ── --}}
    <div class="mt-5 space-y-3 sm:hidden">
        @forelse ($posts as $post)
            <div class="rounded-2xl border border-tpc-primary/10 bg-white p-4 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="shrink-0">
                        @if($post->image_path)
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post image"
                                 class="h-14 w-20 rounded-xl border border-tpc-primary/10 bg-white object-cover" loading="lazy" />
                        @else
                            <div class="h-14 w-20 rounded-xl bg-tpc-primary/5 border border-tpc-primary/10 grid place-items-center text-tpc-ink/40 text-xs">—</div>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <div class="truncate text-sm font-semibold text-tpc-ink">{{ $post->title }}</div>
                            @include('admin.news-posts._status-badge', ['post' => $post])
                        </div>
                        <div class="mt-1 text-xs text-tpc-ink/60">
                            Category: <span class="font-medium text-tpc-ink/80">{{ $post->category }}</span>
                        </div>

                        {{-- Declined note button --}}
                        @if($post->isDeclined() && $post->review_note)
                            <button
                                type="button"
                                onclick="openNoteModal({{ json_encode($post->review_note) }})"
                                class="mt-1 text-xs font-semibold text-red-600 hover:text-red-700 underline underline-offset-2"
                            >
                                View decline reason
                            </button>
                        @endif

                        <div class="mt-3 flex items-center justify-end gap-4">
                            <a href="{{ route('admin.news-posts.edit', $post) }}"
                               class="text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">Edit</a>
                            <form method="POST" action="{{ route('admin.news-posts.destroy', $post) }}"
                                  onsubmit="return confirm('Delete this news post?');">
                                @csrf @method('DELETE')
                                <button class="text-sm font-semibold text-red-600 hover:text-red-700 transition">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-tpc-primary/10 bg-white p-6 text-center text-tpc-ink/70">No news posts yet.</div>
        @endforelse

        <div class="pt-2">{{ $posts->links() }}</div>
    </div>

    {{-- ── Desktop table ── --}}
    <div class="mt-5 hidden sm:block overflow-hidden rounded-2xl border border-tpc-primary/10 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-tpc-primary/5 text-tpc-ink/70">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Image</th>
                        <th class="px-4 py-3 text-left font-medium">Title</th>
                        <th class="px-4 py-3 text-left font-medium">Category</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-left font-medium">Review Note</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-tpc-primary/5 transition">
                            <td class="px-4 py-3">
                                @if($post->image_path)
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post image"
                                         class="h-10 w-14 rounded-xl border border-tpc-primary/10 bg-white object-cover" loading="lazy" />
                                @else
                                    <span class="inline-flex h-10 w-14 items-center justify-center rounded-xl bg-tpc-primary/5 text-tpc-ink/40 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium">{{ $post->title }}</td>
                            <td class="px-4 py-3 text-tpc-ink/70">{{ $post->category }}</td>
                            <td class="px-4 py-3">
                                @include('admin.news-posts._status-badge', ['post' => $post])
                            </td>
                            <td class="px-4 py-3">
                                @if($post->review_note)
                                    @if($post->isDeclined())
                                        {{-- Declined: show View button that opens modal --}}
                                        <button
                                            type="button"
                                            onclick="openNoteModal({{ json_encode($post->review_note) }})"
                                            class="inline-flex items-center gap-1 rounded-lg border border-red-200 bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 hover:bg-red-100 transition"
                                        >
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View Note
                                        </button>
                                    @else
                                        {{-- Approved: just show the auto note in muted text --}}
                                        <span class="text-xs text-tpc-ink/50 italic">{{ $post->review_note }}</span>
                                    @endif
                                @else
                                    <span class="text-tpc-ink/30">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.news-posts.edit', $post) }}"
                                   class="font-semibold text-tpc-primary hover:text-tpc-secondary transition">Edit</a>
                                <form class="inline" method="POST" action="{{ route('admin.news-posts.destroy', $post) }}"
                                      onsubmit="return confirm('Delete this news post?');">
                                    @csrf @method('DELETE')
                                    <button class="ml-3 font-semibold text-red-600 hover:text-red-700 transition">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-8 text-center text-tpc-ink/70" colspan="6">No news posts yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 hidden sm:block">{{ $posts->links() }}</div>

    {{-- ── Decline Note Modal ── --}}
    <div
        id="note-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center p-4"
        aria-modal="true"
        role="dialog"
    >
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeNoteModal()"></div>

        {{-- Panel --}}
        <div class="relative w-full max-w-md rounded-2xl border border-red-100 bg-white shadow-xl">
            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-red-100 px-5 py-4">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-red-100">
                        <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </span>
                    <h3 class="text-sm font-bold text-tpc-ink">Decline Reason</h3>
                </div>
                <button
                    type="button"
                    onclick="closeNoteModal()"
                    class="flex h-8 w-8 items-center justify-center rounded-xl text-tpc-ink/50 hover:bg-gray-100 hover:text-tpc-ink transition"
                    aria-label="Close"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-5 py-5">
                <p id="note-modal-body" class="text-sm text-tpc-ink/80 leading-relaxed whitespace-pre-wrap"></p>
            </div>

            {{-- Footer --}}
            <div class="border-t border-gray-100 px-5 py-4">
                <p class="mb-3 text-xs text-tpc-ink/50">Edit your post and re-submit to request approval again.</p>
                <button
                    type="button"
                    onclick="closeNoteModal()"
                    class="w-full rounded-xl bg-tpc-primary px-4 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary transition"
                >
                    Got it
                </button>
            </div>
        </div>
    </div>

<script>
    function openNoteModal(note) {
        document.getElementById('note-modal-body').textContent = note || 'No reason provided.';
        const modal = document.getElementById('note-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeNoteModal() {
        const modal = document.getElementById('note-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeNoteModal();
    });
</script>

@endsection

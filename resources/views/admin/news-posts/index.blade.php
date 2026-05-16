@php
/** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Models\NewsPost[] $posts */
@endphp

@extends('admin.layout', ['title' => 'News Posts'])

@section('content')

    {{-- ── Page header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-lg font-bold text-tpc-ink">News Posts</h1>
            <p class="mt-0.5 text-xs text-tpc-ink/50">Manage announcements and updates</p>
        </div>
        <a href="{{ route('admin.news-posts.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-tpc-primary px-4 py-2.5 text-sm font-semibold text-white
                  shadow-sm hover:bg-tpc-secondary transition focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 self-start sm:self-auto">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Post
        </a>
    </div>

    {{-- Info banner for regular admins only --}}
    @if(!auth()->user()->is_super_admin)
        <div class="mb-5 flex items-start gap-3 rounded-2xl border border-yellow-200 bg-yellow-50 px-4 py-3">
            <svg class="h-4 w-4 text-yellow-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <p class="text-xs text-yellow-800">
                <span class="font-semibold">Note:</span>
                New posts are sent to the superadmin for approval before going live.
            </p>
        </div>
    @endif

    {{-- ── Mobile cards ── --}}
    <div class="space-y-3 sm:hidden">
        @forelse ($posts as $post)
            <div class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm overflow-hidden">
                <div class="flex items-start gap-3 p-4">
                    {{-- Thumbnail --}}
                    <div class="shrink-0">
                        @if($post->image_path)
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt=""
                                 class="h-16 w-20 rounded-xl border border-tpc-primary/10 object-cover" loading="lazy" />
                        @else
                            <div class="h-16 w-20 rounded-xl bg-tpc-primary/5 border border-tpc-primary/10 grid place-items-center">
                                <svg class="h-5 w-5 text-tpc-ink/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 18h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v10.5a1.5 1.5 0 001.5 1.5z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <p class="text-sm font-semibold text-tpc-ink leading-snug line-clamp-2">{{ $post->title }}</p>
                            @include('admin.news-posts._status-badge', ['post' => $post])
                        </div>
                        <p class="text-xs text-tpc-ink/50">
                            <span class="font-medium text-tpc-ink/70">{{ $post->category }}</span>
                            · {{ $post->created_at->format('M d, Y') }}
                        </p>

                        @if($post->isDeclined() && $post->review_note)
                            <button type="button" onclick="openNoteModal({{ json_encode($post->review_note) }})"
                                    class="mt-1.5 inline-flex items-center gap-1 text-xs font-semibold text-red-600 hover:text-red-700">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View decline reason
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Card footer actions --}}
                <div class="flex items-center justify-end gap-1 border-t border-tpc-primary/8 px-4 py-2.5">
                    <a href="{{ route('admin.news-posts.edit', $post) }}"
                       class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-tpc-primary hover:bg-tpc-primary/8 transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.news-posts.destroy', $post) }}"
                          onsubmit="return confirm('Delete this news post?');">
                        @csrf @method('DELETE')
                        <button class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-tpc-primary/10 bg-white p-10 text-center">
                <svg class="mx-auto h-10 w-10 text-tpc-ink/15 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/>
                </svg>
                <p class="text-sm text-tpc-ink/50 font-medium">No news posts yet</p>
                <a href="{{ route('admin.news-posts.create') }}" class="mt-3 inline-block text-xs font-semibold text-tpc-primary hover:text-tpc-secondary">Create your first post →</a>
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
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-widest text-tpc-ink/50 w-32">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-widest text-tpc-ink/50 w-28">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-widest text-tpc-ink/50">Review Note</th>
                        <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-widest text-tpc-ink/50 w-28">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-tpc-primary/6">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-tpc-primary/3 transition group">
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
                            <td class="px-4 py-3">
                                @include('admin.news-posts._status-badge', ['post' => $post])
                            </td>
                            <td class="px-4 py-3 text-xs text-tpc-ink/50 whitespace-nowrap">
                                {{ $post->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3">
                                @if($post->review_note)
                                    @if($post->isDeclined())
                                        <button type="button"
                                                onclick="openNoteModal({{ json_encode($post->review_note) }})"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 hover:bg-red-100 transition">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View Note
                                        </button>
                                    @else
                                        <span class="text-xs text-tpc-ink/40 italic">{{ Str::limit($post->review_note, 40) }}</span>
                                    @endif
                                @else
                                    <span class="text-tpc-ink/25 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.news-posts.edit', $post) }}"
                                   class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-tpc-primary hover:bg-tpc-primary/8 transition">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    </svg>
                                    Edit
                                </a>
                                <form class="inline" method="POST" action="{{ route('admin.news-posts.destroy', $post) }}"
                                      onsubmit="return confirm('Delete this news post?');">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-14 text-center">
                                <svg class="mx-auto h-10 w-10 text-tpc-ink/15 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/>
                                </svg>
                                <p class="text-sm font-medium text-tpc-ink/50">No news posts yet</p>
                                <a href="{{ route('admin.news-posts.create') }}" class="mt-2 inline-block text-xs font-semibold text-tpc-primary hover:text-tpc-secondary">Create your first post →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 hidden sm:block">{{ $posts->links() }}</div>

    {{-- ── Decline Note Modal ── --}}
    <div id="note-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" aria-modal="true" role="dialog">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeNoteModal()"></div>

        <div class="relative w-full max-w-md rounded-2xl border border-red-100 bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-red-100 px-5 py-4">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-red-100">
                        <svg class="h-3.5 w-3.5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </span>
                    <h3 class="text-sm font-bold text-tpc-ink">Decline Reason</h3>
                </div>
                <button type="button" onclick="closeNoteModal()"
                        class="flex h-8 w-8 items-center justify-center rounded-xl text-tpc-ink/40 hover:bg-gray-100 hover:text-tpc-ink transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="px-5 py-5">
                <p id="note-modal-body" class="text-sm text-tpc-ink/80 leading-relaxed whitespace-pre-wrap"></p>
            </div>

            <div class="border-t border-gray-100 px-5 py-4">
                <p class="mb-3 text-xs text-tpc-ink/50">Edit your post and re-submit to request approval again.</p>
                <button type="button" onclick="closeNoteModal()"
                        class="w-full rounded-xl bg-tpc-primary px-4 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary transition">
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
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeNoteModal(); });
</script>

@endsection

@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Models\NewsPost[] $posts */
@endphp
@extends('admin.layout', ['title' => 'News Posts'])

@section('content')
    <x-admin.page-header title="News posts" subtitle="Manage announcements and updates." :breadcrumbs="['News Posts' => null]">
        <x-admin.button :href="route('admin.news-posts.create')" icon="plus">New post</x-admin.button>
    </x-admin.page-header>

    @unless (auth()->user()->is_super_admin)
        <x-admin.alert type="warning" class="mb-4">
            <strong class="font-semibold">Note:</strong> new posts are sent to the superadmin for approval before going live.
        </x-admin.alert>
    @endunless

    {{-- Hidden bulk-delete form (ids[] are generated from the Alpine selection) --}}
    <section x-data="tpcFilter({
                selected: [],
                note: null,
                get pageIds() { return Array.from(this.$root.querySelectorAll('[data-post-id]')).map(r => r.dataset.postId); },
                get allChecked() { return this.pageIds.length > 0 && this.selected.length === this.pageIds.length; },
                toggleAll(on) { this.selected = on ? this.pageIds : []; },
                bulkDelete() {
                    this.$dispatch('confirm-action', {
                        form: this.$refs.bulkForm,
                        title: 'Delete ' + this.selected.length + ' post(s)?',
                        message: 'The selected posts and their photos will be permanently removed. This cannot be undone.',
                        label: 'Delete selected', danger: true
                    });
                }
            })" @keydown.escape.window="note = null">

        <form x-ref="bulkForm" method="POST" action="{{ route('admin.news-posts.bulkDestroy') }}" class="hidden">
            @csrf @method('DELETE')
            <template x-for="id in selected" :key="id"><input type="hidden" name="ids[]" :value="id"></template>
        </form>

        {{-- Bulk action bar --}}
        <div x-show="selected.length > 0" x-cloak x-transition.opacity
             class="sticky top-0 z-20 mb-3 flex items-center justify-between gap-3 rounded-xl border border-brand-200 bg-brand-50 px-4 py-2.5" role="region" aria-label="Bulk actions">
            <p class="text-sm font-medium text-brand-dark"><span x-text="selected.length"></span> selected</p>
            <div class="flex items-center gap-2">
                <button type="button" class="ui-btn ui-btn-secondary ui-btn-sm" @click="toggleAll(false)">Clear</button>
                <button type="button" class="ui-btn ui-btn-danger-solid ui-btn-sm" @click="bulkDelete()"><x-admin.icon name="trash" class="h-3.5 w-3.5" /> Delete selected</button>
            </div>
        </div>

        <x-admin.card :padding="false">
            @if ($posts->total() > 0)
                <div class="flex flex-col gap-3 border-b border-ui-line p-4 sm:flex-row sm:items-center sm:justify-between">
                    <x-admin.search-input placeholder="Filter this page by title or category" />
                    <div class="flex items-center gap-2">
                        <label for="status-filter" class="sr-only">Status</label>
                        <select id="status-filter" x-model="status" class="ui-select ui-select-auto">
                            <option value="">All statuses</option>
                            <option value="approved">Approved</option>
                            <option value="pending">Pending review</option>
                            <option value="declined">Declined</option>
                        </select>
                        <button type="button" x-show="active" x-cloak @click="reset()" class="ui-btn ui-btn-ghost ui-btn-sm">Clear</button>
                    </div>
                </div>

                <div class="ui-table-wrap">
                    <table class="ui-table">
                        <caption class="sr-only">News posts</caption>
                        <thead>
                            <tr>
                                <th scope="col" class="w-10">
                                    <input type="checkbox" class="ui-check" aria-label="Select all posts on this page"
                                           :checked="allChecked" :indeterminate.prop="selected.length > 0 && !allChecked" @change="toggleAll($event.target.checked)">
                                </th>
                                <th scope="col">Post</th>
                                <th scope="col" class="hidden md:table-cell">Category</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="hidden lg:table-cell">Date</th>
                                <th scope="col" class="ui-col-actions"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                @php
                                    $thumb = $post->image_path ?? optional($post->galleryImages->first())->image_path;
                                @endphp
                                <tr data-post-id="{{ $post->id }}" data-search="{{ $post->title }} {{ $post->category }} {{ $post->excerpt }}" data-status="{{ $post->status }}"
                                    x-show="matches($el)">
                                    <td>
                                        <input type="checkbox" class="ui-check" value="{{ $post->id }}" x-model="selected" aria-label="Select {{ $post->title }}">
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            @if ($thumb)
                                                <img src="{{ asset('storage/' . $thumb) }}" alt="" class="ui-thumb !h-12 !w-16" loading="lazy">
                                            @else
                                                <span class="ui-thumb grid !h-12 !w-16 place-items-center text-gray-300"><x-admin.icon name="newspaper" class="h-5 w-5" /></span>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="max-w-[22rem] truncate font-medium text-ui-ink">{{ $post->title }}</p>
                                                @if ($post->excerpt)
                                                    <p class="max-w-[22rem] truncate text-xs text-ui-muted">{{ $post->excerpt }}</p>
                                                @endif
                                                @if ($post->isDeclined() && $post->review_note)
                                                    <button type="button" class="mt-0.5 text-xs font-medium text-red-700 hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600 rounded"
                                                            @click="note = @js($post->review_note)">View decline reason</button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="hidden md:table-cell"><x-admin.badge type="neutral">{{ $post->category }}</x-admin.badge></td>
                                    <td>@include('admin.news-posts._status-badge', ['post' => $post])</td>
                                    <td class="hidden whitespace-nowrap text-ui-muted lg:table-cell">{{ $post->created_at->format('M d, Y') }}</td>
                                    <td class="ui-col-actions">
                                        <div class="inline-flex items-center gap-1">
                                            <form method="POST" action="{{ route('admin.news-posts.repost', $post) }}"
                                                  data-confirm="A copy of this post will be created{{ auth()->user()->is_super_admin ? ' and published' : ' and sent for review' }}."
                                                  data-confirm-title="Repost this as a new post?" data-confirm-label="Repost" data-confirm-danger="false">
                                                @csrf
                                                <button type="submit" class="ui-btn ui-btn-ghost ui-btn-icon ui-btn-sm" aria-label="Repost {{ $post->title }}" title="Repost">
                                                    <x-admin.icon name="duplicate" class="h-4 w-4" />
                                                </button>
                                            </form>
                                            <x-admin.button :href="route('admin.news-posts.edit', $post)" variant="ghost" size="sm" class="ui-btn-icon" icon="pencil" aria-label="Edit {{ $post->title }}" title="Edit" />
                                            <form method="POST" action="{{ route('admin.news-posts.destroy', $post) }}"
                                                  data-confirm="“{{ $post->title }}” and its photos will be permanently deleted." data-confirm-title="Delete this news post?" data-confirm-label="Delete post">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="ui-btn ui-btn-ghost-danger ui-btn-icon ui-btn-sm" aria-label="Delete {{ $post->title }}" title="Delete">
                                                    <x-admin.icon name="trash" class="h-4 w-4" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr x-show="none" x-cloak>
                                <td colspan="6"><x-admin.empty-state icon="search" title="No matching posts" description="Try a different search or clear the filters." /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{ $posts->links('components.admin.pagination') }}
            @else
                <x-admin.empty-state icon="newspaper" title="No news posts yet" description="Create your first announcement to share it on the website.">
                    <x-admin.button :href="route('admin.news-posts.create')" icon="plus">Create your first post</x-admin.button>
                </x-admin.empty-state>
            @endif
        </x-admin.card>

        {{-- Decline reason dialog (teleported: #tpc-admin-main is transformed, so fixed positioning must live on <body>) --}}
        <template x-teleport="body">
            <div x-show="note !== null" x-cloak class="fixed inset-0 z-[70] flex items-end justify-center sm:items-center sm:p-6" role="dialog" aria-modal="true" aria-labelledby="decline-title">
                <div class="absolute inset-0 bg-gray-900/40" @click="note = null" aria-hidden="true"></div>
                <div class="relative w-full rounded-t-2xl border border-ui-line bg-white p-5 shadow-ui-lg sm:max-w-md sm:rounded-xl">
                    <div class="flex items-start gap-3">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-red-50 text-red-600"><x-admin.icon name="x-circle" class="h-5 w-5" /></span>
                        <div class="min-w-0">
                            <h3 id="decline-title" class="text-base font-semibold text-ui-ink">Decline reason</h3>
                            <p class="mt-2 whitespace-pre-line break-words text-sm text-ui-ink" x-text="note || 'No reason provided.'"></p>
                            <p class="mt-3 text-xs text-ui-muted">Edit your post and re-submit to request approval again.</p>
                        </div>
                    </div>
                    <div class="mt-5 flex justify-end"><button type="button" class="ui-btn ui-btn-primary" @click="note = null">Got it</button></div>
                </div>
            </div>
        </template>
    </section>
@endsection

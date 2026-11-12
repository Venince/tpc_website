@extends('admin.layout', ['title' => 'Messages'])

@section('content')
    @php $isSuper = auth()->check() && auth()->user()->is_super_admin; @endphp

    <x-admin.page-header title="Inbox" :breadcrumbs="['Messages' => null]" />

    <section x-data="{
                selected: [],
                get pageIds() { return Array.from(this.$root.querySelectorAll('[data-msg-id]')).map(r => r.dataset.msgId); },
                get allChecked() { return this.pageIds.length > 0 && this.selected.length === this.pageIds.length; },
                toggleAll(on) { this.selected = on ? this.pageIds : []; },
                bulkDelete() {
                    this.$dispatch('confirm-action', {
                        form: this.$refs.bulkForm,
                        title: 'Delete ' + this.selected.length + ' message(s)?',
                        message: 'The selected messages will be permanently removed. This cannot be undone.',
                        label: 'Delete selected', danger: true
                    });
                }
             }">

        <p class="-mt-3 mb-5 text-sm text-ui-muted">Unread messages: <span id="admin-unread-count" class="font-semibold text-ui-ink tabular-nums">{{ $unreadCount }}</span></p>

        @if ($isSuper)
            <form x-ref="bulkForm" method="POST" action="{{ route('admin.messages.bulkDestroy') }}" class="hidden">
                @csrf @method('DELETE')
                <template x-for="id in selected" :key="id"><input type="hidden" name="ids[]" :value="id"></template>
            </form>
            <div x-show="selected.length > 0" x-cloak x-transition.opacity
                 class="sticky top-0 z-20 mb-3 flex items-center justify-between gap-3 rounded-xl border border-brand-200 bg-brand-50 px-4 py-2.5" role="region" aria-label="Bulk actions">
                <p class="text-sm font-medium text-brand-dark"><span x-text="selected.length"></span> selected</p>
                <div class="flex items-center gap-2">
                    <button type="button" class="ui-btn ui-btn-secondary ui-btn-sm" @click="toggleAll(false)">Clear</button>
                    <button type="button" class="ui-btn ui-btn-danger-solid ui-btn-sm" @click="bulkDelete()"><x-admin.icon name="trash" class="h-3.5 w-3.5" /> Delete selected</button>
                </div>
            </div>
        @endif

        <x-admin.card :padding="false">
            {{-- Server-side filter (handled by ContactMessageController@index) --}}
            <form method="GET" class="flex flex-col gap-3 border-b border-ui-line p-4 sm:flex-row sm:items-center">
                <div class="relative w-full sm:max-w-sm">
                    <label for="q" class="sr-only">Search messages</label>
                    <x-admin.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                    <input id="q" type="search" name="q" value="{{ $q }}" placeholder="Search name, email, subject…" class="ui-input !pl-9">
                </div>
                <div class="flex items-center gap-2">
                    <label for="status" class="sr-only">Status</label>
                    <select id="status" name="status" class="ui-select ui-select-auto">
                        <option value="unread" @selected($status === 'unread')>Unread</option>
                        <option value="read" @selected($status === 'read')>Read</option>
                        <option value="all" @selected($status === 'all')>All</option>
                    </select>
                    <x-admin.button type="submit" variant="secondary" icon="funnel">Filter</x-admin.button>
                </div>
            </form>

            @if ($isSuper && $messages->count())
                <div class="flex items-center gap-3 border-b border-ui-line bg-ui-bg px-4 py-2">
                    <input type="checkbox" id="select-all" class="ui-check" :checked="allChecked" :indeterminate.prop="selected.length > 0 && !allChecked" @change="toggleAll($event.target.checked)">
                    <label for="select-all" class="cursor-pointer text-xs font-medium text-ui-muted">Select all on this page</label>
                </div>
            @endif

            @forelse ($messages as $m)
                <div data-msg-id="{{ $m->id }}" class="group flex items-start gap-3 border-b border-ui-line px-4 py-3.5 transition-colors last:border-b-0 hover:bg-ui-bg {{ $m->is_read ? '' : 'bg-brand-50/40' }}">
                    @if ($isSuper)
                        <input type="checkbox" class="ui-check mt-1" value="{{ $m->id }}" x-model="selected" aria-label="Select message from {{ $m->name }}">
                    @endif

                    <a href="{{ route('admin.messages.show', $m) }}"
                       class="min-w-0 flex-1 rounded focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                            @if (! $m->is_read)
                                <x-admin.badge type="success" dot>Unread</x-admin.badge>
                            @endif
                            <p class="min-w-0 truncate text-sm {{ $m->is_read ? 'font-medium' : 'font-semibold' }} text-ui-ink">{{ $m->subject }}</p>
                        </div>
                        <p class="mt-1 truncate text-xs text-ui-muted"><span class="font-medium text-ui-ink">{{ $m->name }}</span> · {{ $m->email }}</p>
                        <p class="mt-1 line-clamp-1 text-sm text-ui-muted">{{ \Illuminate\Support\Str::limit($m->message, 160) }}</p>
                    </a>

                    <div class="flex shrink-0 flex-col items-end gap-1 text-right">
                        <p class="text-xs text-ui-muted">{{ $m->created_at->timezone('Asia/Manila')->format('M d, Y') }}</p>
                        <p class="text-xs text-ui-muted">{{ $m->created_at->timezone('Asia/Manila')->format('h:i A') }}</p>
                        @if ($isSuper)
                            <form method="POST" action="{{ route('admin.messages.destroy', $m) }}"
                                  data-confirm="This message from {{ $m->name }} will be permanently deleted." data-confirm-title="Delete this message?" data-confirm-label="Delete message">
                                @csrf @method('DELETE')
                                <button type="submit" class="ui-btn ui-btn-ghost-danger ui-btn-icon ui-btn-sm" aria-label="Delete message from {{ $m->name }}" title="Delete">
                                    <x-admin.icon name="trash" class="h-4 w-4" />
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <x-admin.empty-state icon="chat" title="No messages found"
                    :description="$status === 'unread' ? 'All caught up! No unread messages.' : 'No messages match your search.'" />
            @endforelse

            {{ $messages->links('components.admin.pagination') }}
        </x-admin.card>
    </section>
@endsection

@extends('admin.layout', ['title' => 'Feedback'])

@section('content')
    @php $isSuper = auth()->check() && auth()->user()->is_super_admin; @endphp

    <x-admin.page-header title="Feedback" :breadcrumbs="['Feedback' => null]"
        :subtitle="'Unread: ' . $unreadCount . ' · Average rating: ' . ($avgRating ?: '—') . ' / 5'" />

    <section x-data="{
                selected: [],
                get pageIds() { return Array.from(this.$root.querySelectorAll('[data-fb-id]')).map(r => r.dataset.fbId); },
                get allChecked() { return this.pageIds.length > 0 && this.selected.length === this.pageIds.length; },
                toggleAll(on) { this.selected = on ? this.pageIds : []; },
                bulkDelete() {
                    this.$dispatch('confirm-action', {
                        form: this.$refs.bulkForm,
                        title: 'Delete ' + this.selected.length + ' feedback item(s)?',
                        message: 'The selected feedback will be permanently removed. This cannot be undone.',
                        label: 'Delete selected', danger: true
                    });
                }
             }">

        @if ($isSuper)
            <form x-ref="bulkForm" method="POST" action="{{ route('admin.feedback.bulkDestroy') }}" class="hidden">
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
            {{-- Server-side filters (FeedbackController@index) --}}
            <form method="GET" class="flex flex-col gap-3 border-b border-ui-line p-4 lg:flex-row lg:items-center">
                <div class="relative w-full lg:max-w-sm">
                    <label for="q" class="sr-only">Search feedback</label>
                    <x-admin.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                    <input id="q" type="search" name="q" value="{{ $q }}" placeholder="Search name, email, comment…" class="ui-input !pl-9">
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <label for="status" class="sr-only">Status</label>
                    <select id="status" name="status" class="ui-select ui-select-auto">
                        <option value="unread" @selected($status === 'unread')>Unread</option>
                        <option value="read" @selected($status === 'read')>Read</option>
                        <option value="all" @selected($status === 'all')>All</option>
                    </select>
                    <label for="rating" class="sr-only">Rating</label>
                    <select id="rating" name="rating" class="ui-select ui-select-auto">
                        <option value="all" @selected($rating === 'all')>All ratings</option>
                        @for ($r = 5; $r >= 1; $r--)
                            <option value="{{ $r }}" @selected((string) $rating === (string) $r)>{{ $r }} star{{ $r > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                    <label for="category" class="sr-only">Category</label>
                    <select id="category" name="category" class="ui-select ui-select-auto">
                        <option value="all" @selected($category === 'all')>All categories</option>
                        @foreach ($categories as $key => $label)
                            <option value="{{ $key }}" @selected($category === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-admin.button type="submit" variant="secondary" icon="funnel">Filter</x-admin.button>
                </div>
            </form>

            @if ($isSuper && $feedbacks->count())
                <div class="flex items-center gap-3 border-b border-ui-line bg-ui-bg px-4 py-2">
                    <input type="checkbox" id="select-all" class="ui-check" :checked="allChecked" :indeterminate.prop="selected.length > 0 && !allChecked" @change="toggleAll($event.target.checked)">
                    <label for="select-all" class="cursor-pointer text-xs font-medium text-ui-muted">Select all on this page</label>
                </div>
            @endif

            @forelse ($feedbacks as $f)
                <div data-fb-id="{{ $f->id }}" class="flex items-start gap-3 border-b border-ui-line px-4 py-3.5 transition-colors last:border-b-0 hover:bg-ui-bg {{ $f->is_read ? '' : 'bg-brand-50/40' }}">
                    @if ($isSuper)
                        <input type="checkbox" class="ui-check mt-1" value="{{ $f->id }}" x-model="selected" aria-label="Select feedback from {{ $f->name ?: 'Anonymous' }}">
                    @endif

                    <a href="{{ route('admin.feedback.show', $f) }}" class="min-w-0 flex-1 rounded focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                            @unless ($f->is_read)<x-admin.badge type="success" dot>Unread</x-admin.badge>@endunless
                            <span class="inline-flex text-brand" role="img" aria-label="{{ $f->rating }} out of 5 stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <x-admin.icon name="star" :class="'h-4 w-4 ' . ($i <= $f->rating ? 'fill-current' : 'text-gray-300')" />
                                @endfor
                            </span>
                            @if ($f->category)<x-admin.badge>{{ $f->categoryLabel() }}</x-admin.badge>@endif
                            @if ($f->responded_at)<x-admin.badge type="success">Responded</x-admin.badge>@endif
                        </div>
                        <p class="mt-1.5 truncate text-xs text-ui-muted">
                            <span class="font-medium text-ui-ink">{{ $f->name ?: 'Anonymous' }}</span>@if ($f->email) · {{ $f->email }}@endif
                        </p>
                        <p class="mt-1 line-clamp-2 text-sm text-ui-muted">{{ $f->message ? \Illuminate\Support\Str::limit($f->message, 160) : 'No comment left.' }}</p>
                    </a>

                    <div class="flex shrink-0 flex-col items-end gap-1 text-right">
                        <p class="text-xs text-ui-muted">{{ $f->created_at->timezone('Asia/Manila')->format('M d, Y') }}</p>
                        <p class="text-xs text-ui-muted">{{ $f->created_at->timezone('Asia/Manila')->format('h:i A') }}</p>
                        @if ($isSuper)
                            <form method="POST" action="{{ route('admin.feedback.destroy', $f) }}"
                                  data-confirm="This feedback will be permanently deleted." data-confirm-title="Delete this feedback?" data-confirm-label="Delete feedback">
                                @csrf @method('DELETE')
                                <button type="submit" class="ui-btn ui-btn-ghost-danger ui-btn-icon ui-btn-sm" aria-label="Delete this feedback" title="Delete">
                                    <x-admin.icon name="trash" class="h-4 w-4" />
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <x-admin.empty-state icon="star" title="No feedback found"
                    :description="$status === 'unread' ? 'All caught up! No unread feedback.' : 'No feedback matches your filters.'" />
            @endforelse

            {{ $feedbacks->links('components.admin.pagination') }}
        </x-admin.card>
    </section>
@endsection

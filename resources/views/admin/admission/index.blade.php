@extends('admin.layout', ['title' => 'Admission Page'])

@section('content')
    <x-admin.page-header title="Admission page" subtitle="Manage all sections and items shown on the public Admission page."
        :breadcrumbs="['Admission' => null]">
        <x-admin.button :href="route('admission')" target="_blank" rel="noopener" variant="secondary" icon="external">View live page</x-admin.button>
    </x-admin.page-header>

    <div x-data="{
            selected: {},   // { sectionId: [itemId, …] }
            get total() { return Object.values(this.selected).reduce((n, ids) => n + ids.length, 0); },
            sectionIds(sid) { return Array.from(this.$root.querySelectorAll('[data-item-section=&quot;' + sid + '&quot;]')).map(el => el.dataset.itemId); },
            isChecked(sid, id) { return (this.selected[sid] || []).includes(id); },
            toggle(sid, id, on) {
                const set = new Set(this.selected[sid] || []);
                on ? set.add(id) : set.delete(id);
                this.selected = { ...this.selected, [sid]: [...set] };
            },
            allChecked(sid) { const ids = this.sectionIds(sid); return ids.length > 0 && (this.selected[sid] || []).length === ids.length; },
            someChecked(sid) { const n = (this.selected[sid] || []).length; return n > 0 && n < this.sectionIds(sid).length; },
            toggleSection(sid, on) { this.selected = { ...this.selected, [sid]: on ? this.sectionIds(sid) : [] }; },
            clear() { this.selected = {}; },
            bulkDelete() {
                const token = document.querySelector('meta[name=&quot;csrf-token&quot;]').content;
                const picked = this.selected;
                this.$dispatch('confirm-action', {
                    title: 'Delete ' + this.total + ' item(s)?',
                    message: 'The selected items will be permanently removed. This cannot be undone.',
                    label: 'Delete selected', danger: true,
                    onConfirm: () => {
                        const requests = Object.keys(picked).filter(sid => picked[sid].length).map(sid => {
                            const form = document.getElementById('bulk-form-' + sid);
                            if (!form) return Promise.resolve();
                            const body = new FormData();
                            body.append('_token', token);
                            body.append('_method', 'DELETE');
                            picked[sid].forEach(id => body.append('ids[]', id));
                            return fetch(form.action, { method: 'POST', body });
                        });
                        Promise.all(requests).then(() => window.location.reload());
                    }
                });
            }
         }">

        <div x-show="total > 0" x-cloak x-transition.opacity
             class="sticky top-0 z-20 mb-3 flex items-center justify-between gap-3 rounded-xl border border-brand-200 bg-brand-50 px-4 py-2.5" role="region" aria-label="Bulk actions">
            <p class="text-sm font-medium text-brand-dark"><span x-text="total"></span> selected</p>
            <div class="flex items-center gap-2">
                <button type="button" class="ui-btn ui-btn-secondary ui-btn-sm" @click="clear()">Clear</button>
                <button type="button" class="ui-btn ui-btn-danger-solid ui-btn-sm" @click="bulkDelete()"><x-admin.icon name="trash" class="h-3.5 w-3.5" /> Delete selected</button>
            </div>
        </div>

        <div class="space-y-4">
            @foreach ($sections as $section)
                <x-admin.card :padding="false">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ui-line px-5 py-4">
                        <div class="flex min-w-0 items-center gap-3">
                            <x-admin.badge :type="$section->is_visible ? 'success' : 'neutral'" dot>{{ $section->is_visible ? 'Visible' : 'Hidden' }}</x-admin.badge>
                            <div class="min-w-0">
                                <h3 class="truncate text-sm font-semibold text-ui-ink">{{ $section->label }}</h3>
                                <p class="text-xs text-ui-muted">Type: <span class="font-medium text-ui-ink">{{ $section->type }}</span> · Key: <code class="rounded bg-ui-bg-2 px-1 py-0.5 text-[11px]">{{ $section->key }}</code></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-admin.button :href="route('admin.admission.sections.edit', $section)" variant="secondary" size="sm" icon="pencil">Edit section</x-admin.button>
                            @if ($section->type !== 'note')
                                <x-admin.button :href="route('admin.admission.sections.items.create', $section)" size="sm" icon="plus">Add item</x-admin.button>
                            @endif
                        </div>
                    </div>

                    @if ($section->note)
                        <div class="border-b border-ui-line bg-amber-50/60 px-5 py-2.5 text-sm text-amber-900">
                            <span class="font-semibold">Note:</span> {{ $section->note }}
                        </div>
                    @endif

                    @if ($section->type !== 'note')
                        @if ($section->items->isEmpty())
                            <x-admin.empty-state icon="document-text" title="No items yet" description="Items appear in this section on the public page.">
                                <x-admin.button :href="route('admin.admission.sections.items.create', $section)" icon="plus">Add the first item</x-admin.button>
                            </x-admin.empty-state>
                        @else
                            {{-- One bulk form per section (posted via fetch from the selection bar) --}}
                            <form id="bulk-form-{{ $section->id }}" method="POST" action="{{ route('admin.admission.sections.items.bulkDestroy', $section) }}" class="hidden">
                                @csrf @method('DELETE')
                            </form>

                            <div class="flex items-center gap-3 border-b border-ui-line bg-ui-bg px-5 py-2">
                                <input type="checkbox" id="sel-all-{{ $section->id }}" class="ui-check"
                                       :checked="allChecked({{ $section->id }})" :indeterminate.prop="someChecked({{ $section->id }})"
                                       @change="toggleSection({{ $section->id }}, $event.target.checked)">
                                <label for="sel-all-{{ $section->id }}" class="cursor-pointer text-xs font-medium text-ui-muted">Select all in this section</label>
                            </div>

                            <ul id="sortable-{{ $section->id }}" class="divide-y divide-ui-line">
                                @foreach ($section->items as $item)
                                    <li data-id="{{ $item->id }}" data-item-id="{{ $item->id }}" data-item-section="{{ $section->id }}" class="flex items-center gap-3 bg-white px-5 py-3">
                                        <input type="checkbox" class="ui-check" value="{{ $item->id }}" aria-label="Select {{ $item->title }}"
                                               :checked="isChecked({{ $section->id }}, '{{ $item->id }}')"
                                               @change="toggle({{ $section->id }}, '{{ $item->id }}', $event.target.checked)">
                                        <span class="drag-handle cursor-grab touch-none rounded p-1 text-gray-400 hover:bg-ui-bg-2 hover:text-gray-600 active:cursor-grabbing" role="img" aria-label="Drag to reorder" title="Drag to reorder">
                                            <x-admin.icon name="grip" class="h-4 w-4" />
                                        </span>
                                        @if ($section->type === 'steps')
                                            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-brand text-xs font-semibold text-white">{{ $loop->iteration }}</span>
                                        @else
                                            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-brand" aria-hidden="true"></span>
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-sm font-medium text-ui-ink">{{ $item->title }}</p>
                                            @if ($item->body)<p class="truncate text-xs text-ui-muted">{{ $item->body }}</p>@endif
                                        </div>
                                        <div class="flex shrink-0 items-center gap-1">
                                            <x-admin.button :href="route('admin.admission.sections.items.edit', [$section, $item])" variant="ghost" size="sm" class="ui-btn-icon" icon="pencil" aria-label="Edit {{ $item->title }}" title="Edit" />
                                            <form method="POST" action="{{ route('admin.admission.sections.items.destroy', [$section, $item]) }}"
                                                  data-confirm="“{{ $item->title }}” will be permanently removed." data-confirm-title="Delete this item?" data-confirm-label="Delete item">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="ui-btn ui-btn-ghost-danger ui-btn-icon ui-btn-sm" aria-label="Delete {{ $item->title }}" title="Delete">
                                                    <x-admin.icon name="trash" class="h-4 w-4" />
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <p class="border-t border-ui-line px-5 py-2.5 text-xs text-ui-muted">Drag rows to reorder · saves automatically</p>
                        @endif
                    @endif
                </x-admin.card>
            @endforeach
        </div>
    </div>

    <script>
        // app.js calls window.initAdmissionSortable() after PJAX swaps; this also runs on every render.
        window.initAdmissionSortable = function () {
            document.querySelectorAll('[id^="sortable-"]').forEach(function (list) {
                window.tpcSortable(list.id, '/tpc_admin/admission/sections/' + list.id.replace('sortable-', '') + '/reorder');
            });
        };
        window.initAdmissionSortable();
    </script>
@endsection

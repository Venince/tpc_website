@extends('admin.layout', ['title' => 'Admission Page'])

@section('content')

    {{-- ── Page header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-lg font-bold text-tpc-ink">Admission Page</h1>
            <p class="mt-0.5 text-xs text-tpc-ink/50">Manage all sections and items shown on the public Admission page</p>
        </div>
        <a href="{{ route('admission') }}" target="_blank"
           class="inline-flex items-center gap-1.5 self-start sm:self-auto rounded-xl border border-tpc-primary/25 bg-white px-4 py-2.5 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5 transition">
            View Live Page
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>
    </div>

    {{-- ── Bulk-delete action bar (hidden until items are checked) ── --}}
    <div id="bulk-bar"
         class="hidden sticky top-4 z-30 mb-4 flex items-center justify-between gap-3
                rounded-2xl border border-red-200 bg-white px-5 py-3 shadow-lg shadow-red-50">
        <p class="text-sm font-semibold text-tpc-ink">
            <span id="bulk-count">0</span> item(s) selected
        </p>
        <div class="flex items-center gap-2">
            <button type="button" onclick="clearAllSelections()"
                    class="rounded-xl border border-tpc-primary/20 px-3 py-1.5 text-xs font-semibold text-tpc-ink/60 hover:bg-gray-50 transition">
                Cancel
            </button>
            <button type="button" onclick="submitBulkDelete()"
                    class="inline-flex items-center gap-1.5 rounded-xl bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700 transition">
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                </svg>
                Delete Selected
            </button>
        </div>
    </div>

    <div class="space-y-5">
        @foreach ($sections as $section)
            <div class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm overflow-hidden">

                {{-- Section Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 bg-tpc-primary/4 border-b border-tpc-primary/8">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="shrink-0 inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider
                            {{ $section->is_visible ? 'bg-tpc-accent/30 text-tpc-secondary' : 'bg-gray-100 text-gray-500' }}">
                            {{ $section->is_visible ? 'Visible' : 'Hidden' }}
                        </span>
                        <div class="min-w-0">
                            <p class="font-bold text-tpc-ink text-sm truncate">{{ $section->label }}</p>
                            <p class="text-[11px] text-tpc-ink/40 mt-0.5">
                                Type: <span class="font-semibold text-tpc-ink/60">{{ $section->type }}</span>
                                &middot; Key: <span class="font-mono text-tpc-ink/60">{{ $section->key }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('admin.admission.sections.edit', $section) }}"
                           class="inline-flex items-center gap-1.5 rounded-xl border border-tpc-primary/20 bg-white px-3 py-1.5 text-xs font-semibold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                            </svg>
                            Edit Section
                        </a>
                        @if ($section->type !== 'note')
                            <a href="{{ route('admin.admission.sections.items.create', $section) }}"
                               class="inline-flex items-center gap-1.5 rounded-xl bg-tpc-primary px-3 py-1.5 text-xs font-semibold text-white hover:bg-tpc-secondary transition">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Item
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Section note preview --}}
                @if ($section->note)
                    <div class="flex items-start gap-2.5 px-5 py-3 border-b border-dashed border-tpc-primary/10 bg-tpc-primary/[0.02]">
                        <svg class="h-3.5 w-3.5 text-tpc-primary mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                        </svg>
                        <p class="text-xs text-tpc-ink/60"><span class="font-semibold text-tpc-primary">Note:</span> {{ $section->note }}</p>
                    </div>
                @endif

                {{-- Items --}}
                @if ($section->type !== 'note')
                    @if ($section->items->isEmpty())
                        <div class="px-5 py-8 text-center">
                            <svg class="mx-auto h-8 w-8 text-tpc-ink/15 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                            </svg>
                            <p class="text-xs text-tpc-ink/40 font-medium">No items yet</p>
                            <a href="{{ route('admin.admission.sections.items.create', $section) }}"
                               class="mt-2 inline-block text-xs font-semibold text-tpc-primary hover:text-tpc-secondary">Add the first item →</a>
                        </div>
                    @else
                        {{-- Select-all row for this section --}}
                        <div class="flex items-center gap-3 px-5 py-2 border-b border-tpc-primary/6 bg-tpc-primary/[0.015]">
                            <input type="checkbox"
                                   class="select-all-section h-3.5 w-3.5 rounded border-gray-300 text-tpc-primary cursor-pointer"
                                   data-section="{{ $section->id }}"
                                   title="Select all in this section"
                                   onchange="toggleSection(this)">
                            <label class="text-[11px] text-tpc-ink/40 cursor-pointer select-none"
                                   onclick="this.previousElementSibling.click()">
                                Select all in this section
                            </label>
                        </div>

                        {{-- Hidden bulk-delete form for this section --}}
                        <form id="bulk-form-{{ $section->id }}"
                              method="POST"
                              action="{{ route('admin.admission.sections.items.bulkDestroy', $section) }}"
                              class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>

                        <ul class="divide-y divide-tpc-primary/6" id="sortable-{{ $section->id }}">
                            @foreach ($section->items as $item)
                                <li class="flex items-start gap-3 px-5 py-3.5 hover:bg-tpc-primary/[0.02] transition group item-row"
                                    data-id="{{ $item->id }}"
                                    data-section="{{ $section->id }}">

                                    {{-- Checkbox --}}
                                    <input type="checkbox"
                                           class="item-checkbox mt-1 h-3.5 w-3.5 shrink-0 rounded border-gray-300 text-red-600 cursor-pointer"
                                           value="{{ $item->id }}"
                                           data-section="{{ $section->id }}"
                                           onchange="onItemCheck()">

                                    {{-- Drag handle --}}
                                    <span class="mt-1 shrink-0 cursor-grab text-tpc-ink/20 group-hover:text-tpc-primary/40 transition select-none drag-handle text-base leading-none"
                                          title="Drag to reorder">⠿</span>

                                    {{-- Item badge --}}
                                    @if ($section->type === 'steps')
                                        <span class="shrink-0 flex h-5 w-5 mt-0.5 items-center justify-center bg-tpc-primary text-white text-[10px] font-bold rounded-md">
                                            {{ $loop->iteration }}
                                        </span>
                                    @else
                                        <span class="mt-2 shrink-0 h-1.5 w-1.5 rounded-full bg-tpc-primary/60"></span>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-tpc-ink">{{ $item->title }}</p>
                                        @if ($item->body)
                                            <p class="text-xs text-tpc-ink/55 mt-0.5">{{ $item->body }}</p>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-1 shrink-0 ml-auto opacity-0 group-hover:opacity-100 transition">
                                        <a href="{{ route('admin.admission.sections.items.edit', [$section, $item]) }}"
                                           class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-tpc-primary hover:bg-tpc-primary/8 transition">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <form method="POST"
                                              action="{{ route('admin.admission.sections.items.destroy', [$section, $item]) }}"
                                              onsubmit="return confirm('Delete this item?');">
                                            @csrf @method('DELETE')
                                            <button class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <div class="flex items-center gap-1.5 px-5 py-2 border-t border-tpc-primary/6">
                            <svg class="h-3 w-3 text-tpc-ink/25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/>
                            </svg>
                            <p class="text-[11px] text-tpc-ink/30">Drag rows to reorder · saves automatically</p>
                        </div>
                    @endif
                @endif
            </div>
        @endforeach
    </div>

    <script>
    // ── Bulk selection state ──────────────────────────────────────────────
    // Tracks: { sectionId: Set<itemId> }
    var bulkSelected = {};

    function onItemCheck() {
        // Rebuild bulkSelected from all checked checkboxes
        bulkSelected = {};
        document.querySelectorAll('.item-checkbox:checked').forEach(function (cb) {
            var sid = cb.dataset.section;
            if (!bulkSelected[sid]) bulkSelected[sid] = [];
            bulkSelected[sid].push(cb.value);
        });

        // Sync each section's select-all checkbox state
        document.querySelectorAll('.select-all-section').forEach(function (sa) {
            var sid = sa.dataset.section;
            var all  = document.querySelectorAll('.item-checkbox[data-section="' + sid + '"]');
            var chk  = document.querySelectorAll('.item-checkbox[data-section="' + sid + '"]:checked');
            sa.indeterminate = chk.length > 0 && chk.length < all.length;
            sa.checked       = chk.length === all.length && all.length > 0;
        });

        updateBulkBar();
    }

    function toggleSection(selectAllCb) {
        var sid     = selectAllCb.dataset.section;
        var checked = selectAllCb.checked;
        document.querySelectorAll('.item-checkbox[data-section="' + sid + '"]').forEach(function (cb) {
            cb.checked = checked;
        });
        onItemCheck();
    }

    function updateBulkBar() {
        var total = 0;
        Object.values(bulkSelected).forEach(function (ids) { total += ids.length; });

        var bar = document.getElementById('bulk-bar');
        document.getElementById('bulk-count').textContent = total;

        if (total > 0) {
            bar.classList.remove('hidden');
            bar.classList.add('flex');
        } else {
            bar.classList.add('hidden');
            bar.classList.remove('flex');
        }
    }

    function clearAllSelections() {
        document.querySelectorAll('.item-checkbox, .select-all-section').forEach(function (cb) {
            cb.checked = cb.indeterminate = false;
        });
        bulkSelected = {};
        updateBulkBar();
    }

    function submitBulkDelete() {
        var total = 0;
        Object.values(bulkSelected).forEach(function (ids) { total += ids.length; });
        if (total === 0) return;

        if (!confirm('Delete ' + total + ' selected item(s)? This cannot be undone.')) return;

        // We may have selections across multiple sections.
        // Submit one form per affected section sequentially via fetch, then reload.
        var sections = Object.keys(bulkSelected);
        var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        var requests = sections.map(function (sid) {
            var form = document.getElementById('bulk-form-' + sid);
            if (!form) return Promise.resolve();

            var body = new FormData();
            body.append('_token', csrfToken);
            body.append('_method', 'DELETE');
            bulkSelected[sid].forEach(function (id) { body.append('ids[]', id); });

            return fetch(form.action, { method: 'POST', body: body });
        });

        Promise.all(requests).then(function () {
            window.location.reload();
        });
    }

    // ── Sortable (unchanged) ──────────────────────────────────────────────
    window.initAdmissionSortable = function () {
        if (typeof Sortable === 'undefined') return;

        document.querySelectorAll('[id^="sortable-"]').forEach(function (list) {
            if (list._sortable) return;

            var sectionId = list.id.replace('sortable-', '');
            list._sortable = Sortable.create(list, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: function () {
                    var order = Array.from(list.querySelectorAll('[data-id]')).map(function (el) {
                        return el.dataset.id;
                    });
                    fetch('/tpc_admin/admission/sections/' + sectionId + '/reorder', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ order: order }),
                    });
                },
            });
        });
    };

    window.initAdmissionSortable();
    </script>

@endsection

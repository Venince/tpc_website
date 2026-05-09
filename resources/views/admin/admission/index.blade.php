@extends('admin.layout', ['title' => 'Admission Page'])

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-tpc-ink/60">Manage all sections and items shown on the public Admission page.</p>
        <a href="{{ route('admission') }}" target="_blank"
           class="inline-flex items-center gap-1.5 text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">
            View Live Page
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>
    </div>

    <div class="space-y-6">
        @foreach ($sections as $section)
            <div class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm overflow-hidden">

                {{-- Section Header --}}
                <div class="flex items-center justify-between gap-4 px-5 py-4 bg-tpc-primary/5 border-b border-tpc-primary/10">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="shrink-0 inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider
                            {{ $section->is_visible ? 'bg-tpc-accent/30 text-tpc-secondary' : 'bg-gray-100 text-gray-500' }}">
                            {{ $section->is_visible ? 'Visible' : 'Hidden' }}
                        </span>
                        <div class="min-w-0">
                            <p class="font-bold text-tpc-ink truncate">{{ $section->label }}</p>
                            <p class="text-xs text-tpc-ink/50 mt-0.5">Type: <span class="font-medium">{{ $section->type }}</span> &middot; Key: <span class="font-mono">{{ $section->key }}</span></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('admin.admission.sections.edit', $section) }}"
                           class="rounded-xl border border-tpc-primary/20 bg-white px-3 py-1.5 text-xs font-semibold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
                            Edit Section
                        </a>
                        @if ($section->type !== 'note')
                            <a href="{{ route('admin.admission.sections.items.create', $section) }}"
                               class="rounded-xl bg-tpc-primary px-3 py-1.5 text-xs font-semibold text-white hover:bg-tpc-secondary transition">
                                + Add Item
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Section note preview --}}
                @if ($section->note)
                    <div class="px-5 py-3 border-b border-dashed border-tpc-primary/10 bg-tpc-primary/[0.03]">
                        <p class="text-xs text-tpc-ink/60"><span class="font-semibold text-tpc-primary">Note/Tip:</span> {{ $section->note }}</p>
                    </div>
                @endif

                {{-- Items --}}
                @if ($section->type !== 'note')
                    @if ($section->items->isEmpty())
                        <div class="px-5 py-6 text-center text-sm text-tpc-ink/40">No items yet. Add one above.</div>
                    @else
                        <ul class="divide-y divide-gray-100" id="sortable-{{ $section->id }}">
                            @foreach ($section->items as $item)
                                <li class="flex items-start gap-4 px-5 py-3.5 hover:bg-gray-50 transition group"
                                    data-id="{{ $item->id }}">

                                    {{-- Drag handle --}}
                                    <span class="mt-0.5 shrink-0 cursor-grab text-gray-300 group-hover:text-tpc-primary/40 transition select-none drag-handle"
                                          title="Drag to reorder">⠿</span>

                                    {{-- Item badge (step number or bullet) --}}
                                    @if ($section->type === 'steps')
                                        <span class="shrink-0 flex h-6 w-6 items-center justify-center bg-tpc-primary text-white text-xs font-bold rounded">
                                            {{ $loop->iteration }}
                                        </span>
                                    @else
                                        <span class="mt-2 shrink-0 h-1.5 w-1.5 rounded-full bg-tpc-primary"></span>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-tpc-ink">{{ $item->title }}</p>
                                        @if ($item->body)
                                            <p class="text-xs text-tpc-ink/60 mt-0.5">{{ $item->body }}</p>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-3 shrink-0 ml-auto">
                                        <a href="{{ route('admin.admission.sections.items.edit', [$section, $item]) }}"
                                           class="text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition">Edit</a>

                                        <form method="POST"
                                              action="{{ route('admin.admission.sections.items.destroy', [$section, $item]) }}"
                                              onsubmit="return confirm('Delete this item?');">
                                            @csrf @method('DELETE')
                                            <button class="text-xs font-semibold text-red-500 hover:text-red-700 transition">Delete</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Reorder hint --}}
                        <p class="px-5 py-2 text-[11px] text-tpc-ink/30 border-t border-gray-100">
                            Drag rows to reorder. Changes save automatically.
                        </p>
                    @endif
                @endif
            </div>
        @endforeach
    </div>

@endsection

@push('scripts')
{{-- SortableJS for drag-to-reorder --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.querySelectorAll('[id^="sortable-"]').forEach(function (list) {
    const sectionId = list.id.replace('sortable-', '');

    Sortable.create(list, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function () {
            const order = [...list.querySelectorAll('[data-id]')].map(el => el.dataset.id);

            fetch(`/tpc_admin/admission/sections/${sectionId}/reorder`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ order }),
            });
        },
    });
});
</script>
@endpush

@extends('admin.layout', ['title' => $program->code . ' — Details'])

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <a href="{{ route('admin.programs.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition mb-2">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Programs
            </a>
            <h1 class="text-lg font-semibold text-neo-ink">Detail Sections</h1>
            <p class="text-xs text-neo-ink/45 mt-0.5">
                Managing sections for <span class="font-semibold text-neo-ink/70">{{ $program->name }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('academics.show', $program) }}" target="_blank"
               class="inline-flex items-center gap-1.5 rounded-xl bg-neo-surface shadow-neo-sm px-3 py-2 text-xs font-semibold text-tpc-primary
                      transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                View Live
            </a>
            <a href="{{ route('admin.programs.details.create', $program) }}"
               class="inline-flex items-center gap-2 rounded-xl bg-tpc-primary px-4 py-2.5 text-sm font-semibold text-white
                      shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 shrink-0">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add Section
            </a>
        </div>
    </div>

    @if ($program->details->isEmpty())
        <div class="rounded-2xl bg-neo-bg shadow-neo-inset-sm py-20 text-center">
            <p class="text-sm text-neo-ink/40 mb-3">No sections yet.</p>
            <a href="{{ route('admin.programs.details.create', $program) }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add your first section
            </a>
        </div>
    @else
        <div class="space-y-3" id="sortable-details">
            @foreach ($program->details as $detail)
                <div class="flex items-start gap-3 rounded-2xl bg-neo-surface shadow-neo-sm p-4 hover:shadow-neo-hover transition group"
                     data-id="{{ $detail->id }}">

                    <span class="mt-1 shrink-0 cursor-grab text-neo-ink/20 group-hover:text-tpc-primary/40 transition select-none drag-handle text-base">⠿</span>

                    @php
                        $typeStyles = [
                            'gallery' => 'bg-purple-50 text-purple-700',
                            'list'    => 'bg-blue-50 text-blue-700',
                            'text'    => 'bg-tpc-primary/10 text-tpc-secondary',
                        ];
                    @endphp
                    <span class="shrink-0 mt-0.5 rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider
                                 {{ $typeStyles[$detail->type] ?? 'bg-neo-bg text-neo-ink/50' }}">
                        {{ $detail->type }}
                    </span>

                    <div class="flex-1 min-w-0">
                        @if ($detail->heading)
                            <p class="text-sm font-semibold text-neo-ink">{{ $detail->heading }}</p>
                        @endif

                        @if ($detail->type === 'text' && $detail->body)
                            <p class="mt-0.5 text-xs text-neo-ink/50 line-clamp-2">{{ $detail->body }}</p>

                        @elseif ($detail->type === 'list' && $detail->items)
                            <p class="mt-0.5 text-xs text-neo-ink/50">
                                {{ count($detail->items) }} item{{ count($detail->items) !== 1 ? 's' : '' }}:
                                {{ implode(', ', array_slice($detail->items, 0, 3)) }}{{ count($detail->items) > 3 ? '…' : '' }}
                            </p>

                        @elseif ($detail->type === 'gallery' && $detail->image_path)
                            <div class="mt-2 flex items-center gap-3">
                                <img src="{{ asset('storage/' . $detail->image_path) }}"
                                     class="h-12 w-12 object-cover rounded-xl shadow-neo-inset-sm shrink-0" alt="">
                                @if ($detail->caption)
                                    <p class="text-xs text-neo-ink/50 italic">{{ $detail->caption }}</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 shrink-0 ml-auto">
                        <a href="{{ route('admin.programs.details.edit', [$program, $detail]) }}"
                           class="text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition">Edit</a>
                        <form method="POST"
                              action="{{ route('admin.programs.details.destroy', [$program, $detail]) }}"
                              onsubmit="return confirm('Delete this section?');">
                            @csrf @method('DELETE')
                            <button class="text-xs font-semibold text-red-500 hover:text-red-700 transition">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="mt-3 text-[11px] text-neo-ink/30">Drag rows to reorder. Changes save automatically.</p>
    @endif

@endsection

@push('scripts')
<script>
(function() {
    function initSortable() {
        const list = document.getElementById('sortable-details');
        if (!list || typeof Sortable === 'undefined') return;
        if (list._sortable) { list._sortable.destroy(); }

        list._sortable = Sortable.create(list, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function () {
                const order = [...list.querySelectorAll('[data-id]')].map(el => el.dataset.id);
                fetch(`/tpc_admin/programs/{{ $program->id }}/details/reorder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ order }),
                });
            },
        });
    }

    if (typeof Sortable === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js';
        script.onload = initSortable;
        document.head.appendChild(script);
    } else {
        initSortable();
    }
})();
</script>
@endpush

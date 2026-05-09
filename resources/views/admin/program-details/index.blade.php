@extends('admin.layout', ['title' => $program->code . ' — Details'])

@section('content')

    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.programs.index') }}"
               class="text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">
                ← Back to Programs
            </a>
            <p class="mt-1 text-xs text-tpc-ink/50">
                Managing detail sections for <span class="font-semibold text-tpc-ink">{{ $program->name }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('academics.show', $program) }}" target="_blank"
               class="inline-flex items-center gap-1.5 text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">
                View Live
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
            <a href="{{ route('admin.programs.details.create', $program) }}"
               class="inline-flex items-center gap-2 rounded-2xl bg-tpc-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm
                      hover:bg-tpc-secondary transition focus:outline-none focus:ring-2 focus:ring-tpc-primary/30">
                <span class="text-base leading-none">+</span>
                Add Section
            </a>
        </div>
    </div>

    @if ($program->details->isEmpty())
        <div class="py-20 text-center border border-dashed border-tpc-primary/20 rounded-2xl text-tpc-ink/40 text-sm">
            No sections yet. Click <strong>+ Add Section</strong> to get started.
        </div>
    @else
        <div class="space-y-4" id="sortable-details">
            @foreach ($program->details as $detail)
                <div class="flex items-start gap-4 rounded-2xl border border-tpc-primary/10 bg-white p-4 shadow-sm hover:shadow-md transition group"
                     data-id="{{ $detail->id }}">

                    {{-- Drag handle --}}
                    <span class="mt-1 shrink-0 cursor-grab text-gray-300 group-hover:text-tpc-primary/40 transition select-none drag-handle text-lg"
                          title="Drag to reorder">⠿</span>

                    {{-- Type badge --}}
                    <span class="shrink-0 mt-0.5 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider
                        {{ $detail->type === 'gallery' ? 'bg-purple-100 text-purple-700' : ($detail->type === 'list' ? 'bg-blue-100 text-blue-700' : 'bg-tpc-accent/30 text-tpc-secondary') }}">
                        {{ $detail->type }}
                    </span>

                    {{-- Content preview --}}
                    <div class="flex-1 min-w-0">
                        @if ($detail->heading)
                            <p class="text-sm font-bold text-tpc-ink">{{ $detail->heading }}</p>
                        @endif

                        @if ($detail->type === 'text' && $detail->body)
                            <p class="mt-0.5 text-xs text-tpc-ink/60 line-clamp-2">{{ $detail->body }}</p>

                        @elseif ($detail->type === 'list' && $detail->items)
                            <p class="mt-0.5 text-xs text-tpc-ink/60">
                                {{ count($detail->items) }} item{{ count($detail->items) !== 1 ? 's' : '' }}:
                                {{ implode(', ', array_slice($detail->items, 0, 3)) }}{{ count($detail->items) > 3 ? '…' : '' }}
                            </p>

                        @elseif ($detail->type === 'gallery' && $detail->image_path)
                            <div class="mt-1.5 flex items-center gap-2">
                                <img src="{{ asset('storage/' . $detail->image_path) }}"
                                     class="h-12 w-12 object-cover rounded-lg border border-gray-200" alt="gallery">
                                @if ($detail->caption)
                                    <p class="text-xs text-tpc-ink/60 italic">{{ $detail->caption }}</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Actions --}}
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

        <p class="mt-3 text-[11px] text-tpc-ink/30">Drag rows to reorder. Changes save automatically.</p>
    @endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
const list = document.getElementById('sortable-details');
if (list) {
    Sortable.create(list, {
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
</script>
@endpush

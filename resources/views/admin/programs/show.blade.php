{{-- resources/views/admin/programs/show.blade.php --}}
@extends('admin.layout', ['title' => $program->code . ' — Manage'])

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.programs.index') }}"
               class="text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">
                ← Back to Programs
            </a>
            <div class="mt-2 flex items-center gap-3">
                @if ($program->logo_path)
                    <img src="{{ asset('storage/' . $program->logo_path) }}"
                         class="h-10 w-10 object-contain rounded-xl border border-tpc-primary/15 bg-white p-0.5"
                         alt="{{ $program->code }}">
                @endif
                <div>
                    <h1 class="text-lg font-bold text-tpc-ink leading-tight">{{ $program->name }}</h1>
                    <p class="text-xs text-tpc-ink/50">
                        {{ $program->code }}{{ $program->department ? ' · ' . $program->department : '' }}
                    </p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('academics.show', $program) }}" target="_blank"
               class="inline-flex items-center gap-1.5 rounded-xl border border-tpc-primary/20 px-3 py-2 text-xs font-semibold text-tpc-primary hover:bg-tpc-primary/5 transition">
                View Live ↗
            </a>
            <a href="{{ route('admin.programs.edit', $program) }}"
               class="inline-flex items-center gap-1.5 rounded-xl bg-tpc-primary px-4 py-2 text-xs font-semibold text-white hover:bg-tpc-secondary transition">
                Edit Program Info
            </a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">

        {{-- PEOPLE --}}
        <div class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-tpc-primary/10 bg-tpc-primary/5">
                <p class="text-sm font-bold text-tpc-ink">People</p>
                <a href="{{ route('admin.programs.people.create', $program) }}"
                   class="inline-flex items-center gap-1 rounded-xl bg-tpc-primary px-3 py-1.5 text-xs font-semibold text-white hover:bg-tpc-secondary transition">
                    + Add Person
                </a>
            </div>

            @if ($program->people->isEmpty())
                <p class="px-5 py-8 text-center text-sm text-tpc-ink/40">No people added yet.</p>
            @else
                <ul class="divide-y divide-gray-100" id="sortable-people">
                    @foreach ($program->people as $person)
                        <li class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition group"
                            data-id="{{ $person->id }}">

                            <span class="shrink-0 cursor-grab text-gray-300 group-hover:text-tpc-primary/40 drag-handle select-none">⠿</span>

                            @if ($person->photo_path)
                                <img src="{{ asset('storage/' . $person->photo_path) }}"
                                     class="h-9 w-9 rounded-full object-cover border border-gray-200 shrink-0"
                                     alt="{{ $person->name }}">
                            @else
                                <span class="h-9 w-9 rounded-full bg-tpc-primary/10 flex items-center justify-center text-sm font-bold text-tpc-primary shrink-0">
                                    {{ strtoupper(substr($person->name, 0, 1)) }}
                                </span>
                            @endif

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-tpc-ink truncate">{{ $person->name }}</p>
                                <p class="text-xs text-tpc-ink/50">
                                    <span class="font-medium text-tpc-primary">{{ $person->role_label }}</span>
                                    @if ($person->position) · {{ $person->position }} @endif
                                </p>
                            </div>

                            <div class="flex items-center gap-3 shrink-0">
                                <a href="{{ route('admin.programs.people.edit', [$program, $person]) }}"
                                   class="text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition">Edit</a>
                                <form method="POST"
                                      action="{{ route('admin.programs.people.destroy', [$program, $person]) }}"
                                      onsubmit="return confirm('Remove {{ $person->name }}?');">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-semibold text-red-500 hover:text-red-700 transition">Remove</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <p class="px-5 py-2 text-[11px] text-tpc-ink/30 border-t border-gray-100">Drag to reorder.</p>
            @endif
        </div>

        {{-- ACHIEVEMENTS --}}
        <div class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-tpc-primary/10 bg-tpc-primary/5">
                <p class="text-sm font-bold text-tpc-ink">Achievements</p>
                <a href="{{ route('admin.programs.achievements.create', $program) }}"
                   class="inline-flex items-center gap-1 rounded-xl bg-tpc-primary px-3 py-1.5 text-xs font-semibold text-white hover:bg-tpc-secondary transition">
                    + Add Achievement
                </a>
            </div>

            @if ($program->achievements->isEmpty())
                <p class="px-5 py-8 text-center text-sm text-tpc-ink/40">No achievements added yet.</p>
            @else
                <ul class="divide-y divide-gray-100" id="sortable-achievements">
                    @foreach ($program->achievements as $achievement)
                        <li class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition group"
                            data-id="{{ $achievement->id }}">

                            <span class="mt-1 shrink-0 cursor-grab text-gray-300 group-hover:text-tpc-primary/40 drag-handle select-none">⠿</span>

                            @if ($achievement->photo_path)
                                <img src="{{ asset('storage/' . $achievement->photo_path) }}"
                                     class="h-12 w-12 rounded-xl object-cover border border-gray-200 shrink-0" alt="">
                            @else
                                <span class="h-12 w-12 rounded-xl bg-tpc-primary/10 flex items-center justify-center text-xl shrink-0">🏆</span>
                            @endif

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-tpc-ink truncate">{{ $achievement->title }}</p>
                                <p class="text-xs text-tpc-ink/50">
                                    @if ($achievement->year) {{ $achievement->year }} @endif
                                    @if ($achievement->description)
                                        {{ $achievement->year ? ' · ' : '' }}{{ Str::limit($achievement->description, 60) }}
                                    @endif
                                </p>
                            </div>

                            <div class="flex items-center gap-3 shrink-0">
                                <a href="{{ route('admin.programs.achievements.edit', [$program, $achievement]) }}"
                                   class="text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition">Edit</a>
                                <form method="POST"
                                      action="{{ route('admin.programs.achievements.destroy', [$program, $achievement]) }}"
                                      onsubmit="return confirm('Delete this achievement?');">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-semibold text-red-500 hover:text-red-700 transition">Delete</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <p class="px-5 py-2 text-[11px] text-tpc-ink/30 border-t border-gray-100">Drag to reorder.</p>
            @endif
        </div>

    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
function makeSortable(id, url) {
    const el = document.getElementById(id);
    if (!el) return;
    Sortable.create(el, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: () => {
            const order = [...el.querySelectorAll('[data-id]')].map(e => e.dataset.id);
            fetch(url, {
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
makeSortable('sortable-people',       '/tpc_admin/programs/{{ $program->id }}/people/reorder');
makeSortable('sortable-achievements', '/tpc_admin/programs/{{ $program->id }}/achievements/reorder');
</script>
@endpush

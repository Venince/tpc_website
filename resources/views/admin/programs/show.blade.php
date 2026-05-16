@extends('admin.layout', ['title' => $program->code . ' — Manage'])

@section('content')

    {{-- Back + Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.programs.index') }}"
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition mb-4">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Programs
        </a>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                @if ($program->logo_path)
                    <img src="{{ asset('storage/' . $program->logo_path) }}"
                         class="h-12 w-12 rounded-xl border border-tpc-primary/15 bg-white object-contain p-1 shrink-0"
                         alt="{{ $program->code }}">
                @else
                    <span class="h-12 w-12 rounded-xl bg-tpc-primary/8 flex items-center justify-center text-2xl shrink-0">🎓</span>
                @endif
                <div>
                    <h1 class="text-base font-bold text-tpc-ink leading-tight">{{ $program->name }}</h1>
                    <p class="text-xs text-tpc-ink/50 mt-0.5">
                        {{ $program->code }}{{ $program->department ? ' · ' . $program->department : '' }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('academics.show', $program) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 rounded-xl border border-tpc-primary/20 bg-white px-3 py-2 text-xs font-semibold text-tpc-primary hover:bg-tpc-primary/5 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    View Live
                </a>
                <a href="{{ route('admin.programs.edit', $program) }}"
                   class="inline-flex items-center gap-1.5 rounded-xl bg-tpc-primary px-4 py-2 text-xs font-semibold text-white hover:bg-tpc-secondary transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Info
                </a>
            </div>
        </div>
    </div>

    {{-- Panels --}}
    <div class="grid gap-5 lg:grid-cols-2">

        {{-- PEOPLE --}}
        <div class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-tpc-primary/8 bg-tpc-primary/4">
                <p class="text-sm font-bold text-tpc-ink">People</p>
                <a href="{{ route('admin.programs.people.create', $program) }}"
                   class="inline-flex items-center gap-1 rounded-xl bg-tpc-primary px-3 py-1.5 text-xs font-semibold text-white hover:bg-tpc-secondary transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add
                </a>
            </div>

            @if ($program->people->isEmpty())
                <p class="px-5 py-10 text-center text-sm text-tpc-ink/35">No people added yet.</p>
            @else
                <ul class="divide-y divide-tpc-primary/5" id="sortable-people">
                    @foreach ($program->people as $person)
                        <li class="flex items-center gap-3 px-4 py-3 hover:bg-tpc-primary/3 transition group"
                            data-id="{{ $person->id }}">
                            <span class="shrink-0 cursor-grab text-gray-300 group-hover:text-tpc-primary/40 drag-handle select-none text-base">⠿</span>
                            @if ($person->photo_path)
                                <img src="{{ asset('storage/' . $person->photo_path) }}"
                                     class="h-9 w-9 rounded-full object-cover border border-tpc-primary/10 shrink-0"
                                     alt="{{ $person->name }}">
                            @else
                                <span class="h-9 w-9 rounded-full bg-tpc-primary/10 flex items-center justify-center text-sm font-bold text-tpc-primary shrink-0">
                                    {{ strtoupper(substr($person->name, 0, 1)) }}
                                </span>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-tpc-ink truncate">{{ $person->name }}</p>
                                <p class="text-xs text-tpc-ink/50">
                                    <span class="font-semibold text-tpc-primary">{{ $person->role_label }}</span>
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
                <p class="px-5 py-2 text-[11px] text-tpc-ink/30 border-t border-tpc-primary/5">Drag rows to reorder.</p>
            @endif
        </div>

        {{-- ACHIEVEMENTS --}}
        <div class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-tpc-primary/8 bg-tpc-primary/4">
                <p class="text-sm font-bold text-tpc-ink">Achievements</p>
                <a href="{{ route('admin.programs.achievements.create', $program) }}"
                   class="inline-flex items-center gap-1 rounded-xl bg-tpc-primary px-3 py-1.5 text-xs font-semibold text-white hover:bg-tpc-secondary transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add
                </a>
            </div>

            @if ($program->achievements->isEmpty())
                <p class="px-5 py-10 text-center text-sm text-tpc-ink/35">No achievements added yet.</p>
            @else
                <ul class="divide-y divide-tpc-primary/5" id="sortable-achievements">
                    @foreach ($program->achievements as $achievement)
                        <li class="flex items-start gap-3 px-4 py-3 hover:bg-tpc-primary/3 transition group"
                            data-id="{{ $achievement->id }}">
                            <span class="mt-1 shrink-0 cursor-grab text-gray-300 group-hover:text-tpc-primary/40 drag-handle select-none text-base">⠿</span>
                            @if ($achievement->photo_path)
                                <img src="{{ asset('storage/' . $achievement->photo_path) }}"
                                     class="h-11 w-11 rounded-xl object-cover border border-tpc-primary/10 shrink-0" alt="">
                            @else
                                <span class="h-11 w-11 rounded-xl bg-tpc-primary/8 flex items-center justify-center text-lg shrink-0">🏆</span>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-tpc-ink truncate">{{ $achievement->title }}</p>
                                <p class="text-xs text-tpc-ink/50">
                                    @if ($achievement->year) {{ $achievement->year }} @endif
                                    @if ($achievement->description)
                                        {{ $achievement->year ? ' · ' : '' }}{{ Str::limit($achievement->description, 55) }}
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
                <p class="px-5 py-2 text-[11px] text-tpc-ink/30 border-t border-tpc-primary/5">Drag rows to reorder.</p>
            @endif
        </div>

    </div>

@endsection

@push('scripts')
<script>
(function() {
    function initSortable() {
        if (typeof Sortable === 'undefined') return;

        function makeSortable(id, url) {
            const el = document.getElementById(id);
            if (!el) return;
            if (el._sortable) { el._sortable.destroy(); }

            el._sortable = Sortable.create(el, {
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
    }

    // Load SortableJS if not already loaded
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

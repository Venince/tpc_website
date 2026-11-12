@extends('admin.layout', ['title' => $program->code . ' — Manage'])

@section('content')
    <x-admin.page-header :title="$program->name"
        :subtitle="$program->code . ($program->department ? ' · ' . $program->department : '')"
        :breadcrumbs="['Programs' => route('admin.programs.index'), $program->code => null]">
        <x-admin.button :href="route('academics.show', $program)" target="_blank" rel="noopener" variant="secondary" icon="external">View live</x-admin.button>
        <x-admin.button :href="route('admin.programs.edit', $program)" icon="pencil">Edit info</x-admin.button>
    </x-admin.page-header>

    <div class="grid gap-4 lg:grid-cols-2">
        {{-- People --}}
        <x-admin.card title="People" description="Program head, coordinators and instructors." :padding="false">
            <x-slot:actions>
                <x-admin.button :href="route('admin.programs.people.create', $program)" size="sm" icon="plus">Add person</x-admin.button>
            </x-slot:actions>

            @if ($program->people->isEmpty())
                <x-admin.empty-state icon="users" title="No people added yet" description="Add the program head, coordinators and instructors." />
            @else
                <ul id="sortable-people" class="divide-y divide-ui-line">
                    @foreach ($program->people as $person)
                        <li data-id="{{ $person->id }}" class="flex items-center gap-3 bg-white px-4 py-3">
                            <span class="drag-handle cursor-grab touch-none rounded p-1 text-gray-400 hover:bg-ui-bg-2 hover:text-gray-600 active:cursor-grabbing" role="img" aria-label="Drag to reorder" title="Drag to reorder">
                                <x-admin.icon name="grip" class="h-4 w-4" />
                            </span>
                            @if ($person->photo_path)
                                <img src="{{ asset('storage/' . $person->photo_path) }}" alt="{{ $person->name }}" class="ui-thumb !rounded-full" loading="lazy">
                            @else
                                <span class="ui-thumb grid !rounded-full place-items-center text-sm font-semibold text-brand-dark">{{ strtoupper(substr($person->name, 0, 1)) }}</span>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-ui-ink">{{ $person->name }}</p>
                                <p class="mt-0.5 flex flex-wrap items-center gap-x-1.5 text-xs text-ui-muted">
                                    <x-admin.badge type="success">{{ $person->role_label }}</x-admin.badge>
                                    @if ($person->position)<span class="truncate">{{ $person->position }}</span>@endif
                                </p>
                            </div>
                            <div class="flex shrink-0 items-center gap-1">
                                <x-admin.button :href="route('admin.programs.people.edit', [$program, $person])" variant="ghost" size="sm" class="ui-btn-icon" icon="pencil" aria-label="Edit {{ $person->name }}" title="Edit" />
                                <form method="POST" action="{{ route('admin.programs.people.destroy', [$program, $person]) }}"
                                      data-confirm="{{ $person->name }} will be removed from this program." data-confirm-title="Remove this person?" data-confirm-label="Remove">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="ui-btn ui-btn-ghost-danger ui-btn-icon ui-btn-sm" aria-label="Remove {{ $person->name }}" title="Remove">
                                        <x-admin.icon name="trash" class="h-4 w-4" />
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <p class="border-t border-ui-line px-4 py-2.5 text-xs text-ui-muted">Drag the handle to reorder. Changes save automatically.</p>
            @endif
        </x-admin.card>

        {{-- Achievements --}}
        <x-admin.card title="Achievements" description="Awards and milestones." :padding="false">
            <x-slot:actions>
                <x-admin.button :href="route('admin.programs.achievements.create', $program)" size="sm" icon="plus">Add achievement</x-admin.button>
            </x-slot:actions>

            @if ($program->achievements->isEmpty())
                <x-admin.empty-state icon="star" title="No achievements added yet" description="Highlight awards, licensure results and milestones." />
            @else
                <ul id="sortable-achievements" class="divide-y divide-ui-line">
                    @foreach ($program->achievements as $achievement)
                        <li data-id="{{ $achievement->id }}" class="flex items-center gap-3 bg-white px-4 py-3">
                            <span class="drag-handle cursor-grab touch-none rounded p-1 text-gray-400 hover:bg-ui-bg-2 hover:text-gray-600 active:cursor-grabbing" role="img" aria-label="Drag to reorder" title="Drag to reorder">
                                <x-admin.icon name="grip" class="h-4 w-4" />
                            </span>
                            @if ($achievement->photo_path)
                                <img src="{{ asset('storage/' . $achievement->photo_path) }}" alt="" class="ui-thumb" loading="lazy">
                            @else
                                <span class="ui-thumb grid place-items-center text-gray-400"><x-admin.icon name="star" class="h-5 w-5" /></span>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-ui-ink">{{ $achievement->title }}</p>
                                <p class="mt-0.5 truncate text-xs text-ui-muted">
                                    @if ($achievement->year){{ $achievement->year }}@endif
                                    @if ($achievement->description){{ $achievement->year ? ' · ' : '' }}{{ Str::limit($achievement->description, 55) }}@endif
                                </p>
                            </div>
                            <div class="flex shrink-0 items-center gap-1">
                                <x-admin.button :href="route('admin.programs.achievements.edit', [$program, $achievement])" variant="ghost" size="sm" class="ui-btn-icon" icon="pencil" aria-label="Edit {{ $achievement->title }}" title="Edit" />
                                <form method="POST" action="{{ route('admin.programs.achievements.destroy', [$program, $achievement]) }}"
                                      data-confirm="This achievement and its photos will be permanently deleted." data-confirm-title="Delete this achievement?" data-confirm-label="Delete">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="ui-btn ui-btn-ghost-danger ui-btn-icon ui-btn-sm" aria-label="Delete {{ $achievement->title }}" title="Delete">
                                        <x-admin.icon name="trash" class="h-4 w-4" />
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <p class="border-t border-ui-line px-4 py-2.5 text-xs text-ui-muted">Drag the handle to reorder. Changes save automatically.</p>
            @endif
        </x-admin.card>
    </div>

    <script>
        (function () {
            window.tpcSortable('sortable-people', '/tpc_admin/programs/{{ $program->id }}/people/reorder');
            window.tpcSortable('sortable-achievements', '/tpc_admin/programs/{{ $program->id }}/achievements/reorder');
        })();
    </script>
@endsection

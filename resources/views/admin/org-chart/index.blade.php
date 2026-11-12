@extends('admin.layout', ['title' => 'Organizational Chart'])

@section('content')
    <x-admin.page-header title="Organizational chart" subtitle="Manage people and hierarchy displayed on the public org chart page."
        :breadcrumbs="['Organizational Chart' => null]">
        <x-admin.button :href="route('org-chart')" target="_blank" rel="noopener" variant="secondary" icon="external">View public page</x-admin.button>
        <x-admin.button :href="route('admin.org-chart.create')" icon="plus">Add person</x-admin.button>
    </x-admin.page-header>

    <x-admin.card :padding="false" x-data="tpcFilter()">
        @if ($nodes->isNotEmpty())
            <div class="flex flex-col gap-3 border-b border-ui-line p-4 sm:flex-row sm:items-center sm:justify-between">
                <x-admin.search-input placeholder="Search by name, title or department" />
                <div class="flex items-center gap-2">
                    <label for="status-filter" class="sr-only">Status</label>
                    <select id="status-filter" x-model="status" class="ui-select ui-select-auto">
                        <option value="">All statuses</option>
                        <option value="active">Active</option>
                        <option value="hidden">Hidden</option>
                    </select>
                    <button type="button" x-show="active" x-cloak @click="reset()" class="ui-btn ui-btn-ghost ui-btn-sm">Clear</button>
                </div>
            </div>

            <div class="ui-table-wrap">
                <table class="ui-table">
                    <caption class="sr-only">Organizational chart members</caption>
                    <thead>
                        <tr>
                            <th scope="col" class="w-12">#</th>
                            <th scope="col">Name / title</th>
                            <th scope="col" class="hidden md:table-cell">Department</th>
                            <th scope="col" class="hidden lg:table-cell">Reports to</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="ui-col-actions"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nodes as $node)
                            <tr data-search="{{ $node->name }} {{ $node->title }} {{ $node->department }}" data-status="{{ $node->is_active ? 'active' : 'hidden' }}" x-show="matches($el)">
                                <td class="text-ui-muted tabular-nums">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $node->photoUrl() }}" alt="" class="ui-thumb !rounded-full" loading="lazy">
                                        <div class="min-w-0">
                                            <p class="truncate font-medium text-ui-ink">{{ $node->name }}</p>
                                            <p class="truncate text-xs text-ui-muted">{{ $node->title }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden text-ui-muted md:table-cell">{{ $node->department ?? '—' }}</td>
                                <td class="hidden lg:table-cell">
                                    @if ($node->parents->isEmpty())
                                        <x-admin.badge type="success">Root</x-admin.badge>
                                    @else
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($node->parents as $p)<x-admin.badge>{{ $p->name }}</x-admin.badge>@endforeach
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if ($node->is_active)
                                        <x-admin.badge type="success" dot>Active</x-admin.badge>
                                    @else
                                        <x-admin.badge type="neutral" dot>Hidden</x-admin.badge>
                                    @endif
                                </td>
                                <td class="ui-col-actions">
                                    <div class="inline-flex items-center gap-1">
                                        <x-admin.button :href="route('admin.org-chart.edit', $node)" variant="ghost" size="sm" class="ui-btn-icon" icon="pencil" aria-label="Edit {{ $node->name }}" title="Edit" />
                                        <form method="POST" action="{{ route('admin.org-chart.destroy', $node) }}"
                                              data-confirm="{{ $node->name }} will be removed from the org chart." data-confirm-title="Remove this person?" data-confirm-label="Remove">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="ui-btn ui-btn-ghost-danger ui-btn-icon ui-btn-sm" aria-label="Remove {{ $node->name }}" title="Remove">
                                                <x-admin.icon name="trash" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr x-show="none" x-cloak>
                            <td colspan="6"><x-admin.empty-state icon="search" title="No matching people" description="Try a different search or clear the filters." /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <x-admin.empty-state icon="org-chart" title="No people added yet" description="Add the first person to start building the chart.">
                <x-admin.button :href="route('admin.org-chart.create')" icon="plus">Add the first person</x-admin.button>
            </x-admin.empty-state>
        @endif
    </x-admin.card>
@endsection

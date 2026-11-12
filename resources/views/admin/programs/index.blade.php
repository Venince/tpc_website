@extends('admin.layout', ['title' => 'Programs'])

@section('content')
    <x-admin.page-header title="Programs" subtitle="Manage academic programs, their people and achievements."
        :breadcrumbs="['Programs' => null]">
        <x-admin.button :href="route('admin.programs.create')" icon="plus">New program</x-admin.button>
    </x-admin.page-header>

    <x-admin.card :padding="false" x-data="tpcFilter()">
        @if ($programs->total() > 0)
            <div class="flex flex-col gap-3 border-b border-ui-line p-4 sm:flex-row sm:items-center sm:justify-between">
                <x-admin.search-input placeholder="Filter this page by code, name or department" />
                <div class="flex items-center gap-2">
                    <label for="status-filter" class="sr-only">Status</label>
                    <select id="status-filter" x-model="status" class="ui-select ui-select-auto">
                        <option value="">All statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <button type="button" x-show="active" x-cloak @click="reset()" class="ui-btn ui-btn-ghost ui-btn-sm">Clear</button>
                </div>
            </div>

            <div class="ui-table-wrap">
                <table class="ui-table">
                    <caption class="sr-only">Programs</caption>
                    <thead>
                        <tr>
                            <th scope="col">Program</th>
                            <th scope="col" class="hidden md:table-cell">Department</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="ui-col-actions"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($programs as $program)
                            <tr data-search="{{ $program->code }} {{ $program->name }} {{ $program->department }}"
                                data-status="{{ $program->is_active ? 'active' : 'inactive' }}"
                                x-show="matches($el)">
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if ($program->logo_path)
                                            <img src="{{ asset('storage/' . $program->logo_path) }}" alt="" class="ui-thumb !object-contain bg-white p-0.5" loading="lazy">
                                        @else
                                            <span class="ui-thumb grid place-items-center text-gray-400"><x-admin.icon name="book-open" class="h-5 w-5" /></span>
                                        @endif
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.programs.show', $program) }}" class="block truncate font-medium text-ui-ink hover:text-brand-dark focus-visible:outline-none focus-visible:underline">{{ $program->name }}</a>
                                            <p class="truncate text-xs text-ui-muted">{{ $program->code }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden text-ui-muted md:table-cell">{{ $program->department ?: '—' }}</td>
                                <td>
                                    @if ($program->is_active)
                                        <x-admin.badge type="success" dot>Active</x-admin.badge>
                                    @else
                                        <x-admin.badge type="neutral" dot>Inactive</x-admin.badge>
                                    @endif
                                </td>
                                <td class="ui-col-actions">
                                    <div class="inline-flex items-center gap-1">
                                        <x-admin.button :href="route('admin.programs.show', $program)" variant="secondary" size="sm" icon="users">Manage</x-admin.button>
                                        <x-admin.button :href="route('admin.programs.edit', $program)" variant="ghost" size="sm" class="ui-btn-icon" icon="pencil" aria-label="Edit {{ $program->code }}" title="Edit" />
                                        <form method="POST" action="{{ route('admin.programs.destroy', $program) }}"
                                              data-confirm="“{{ $program->name }}” and its logo will be permanently removed. This cannot be undone."
                                              data-confirm-title="Delete this program?" data-confirm-label="Delete program">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="ui-btn ui-btn-ghost-danger ui-btn-icon ui-btn-sm" aria-label="Delete {{ $program->code }}" title="Delete">
                                                <x-admin.icon name="trash" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr x-show="none" x-cloak>
                            <td colspan="4"><x-admin.empty-state icon="search" title="No matching programs" description="Try a different search or clear the filters." /></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{ $programs->links('components.admin.pagination') }}
        @else
            <x-admin.empty-state icon="book-open" title="No programs yet" description="Create your first program to show it on the Academics page.">
                <x-admin.button :href="route('admin.programs.create')" icon="plus">New program</x-admin.button>
            </x-admin.empty-state>
        @endif
    </x-admin.card>
@endsection

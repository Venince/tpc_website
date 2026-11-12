@extends('admin.layout', ['title' => 'Services'])

@section('content')
    <x-admin.page-header title="Services" subtitle="Manage all public-facing services." :breadcrumbs="['Services' => null]">
        <x-admin.button :href="route('admin.services.create')" icon="plus">New service</x-admin.button>
    </x-admin.page-header>

    <x-admin.card :padding="false" x-data="tpcFilter()">
        @if ($services->isNotEmpty())
            <div class="flex flex-col gap-3 border-b border-ui-line p-4 sm:flex-row sm:items-center sm:justify-between">
                <x-admin.search-input placeholder="Filter this page by title" />
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
                    <caption class="sr-only">Services</caption>
                    <thead>
                        <tr>
                            <th scope="col">Service</th>
                            <th scope="col" class="hidden sm:table-cell">Sections</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="ui-col-actions"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                            <tr data-search="{{ $service->title }} {{ $service->slug }}" data-status="{{ $service->is_active ? 'active' : 'inactive' }}" x-show="matches($el)">
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if ($service->featured_image_path)
                                            <img src="{{ asset('storage/' . $service->featured_image_path) }}" alt="" class="ui-thumb !h-12 !w-16" loading="lazy">
                                        @else
                                            <span class="ui-thumb grid !h-12 !w-16 place-items-center text-gray-300"><x-admin.icon name="cog-8-tooth" class="h-5 w-5" /></span>
                                        @endif
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.services.show', $service) }}" class="block max-w-[22rem] truncate font-medium text-ui-ink hover:text-brand-dark focus-visible:outline-none focus-visible:underline">{{ $service->title }}</a>
                                            <p class="max-w-[22rem] truncate text-xs text-ui-muted">/services/{{ $service->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden text-ui-muted sm:table-cell">{{ $service->contents_count ?? $service->contents()->count() }}</td>
                                <td>
                                    @if ($service->is_active)
                                        <x-admin.badge type="success" dot>Active</x-admin.badge>
                                    @else
                                        <x-admin.badge type="neutral" dot>Inactive</x-admin.badge>
                                    @endif
                                </td>
                                <td class="ui-col-actions">
                                    <div class="inline-flex items-center gap-1">
                                        <x-admin.button :href="route('admin.services.show', $service)" variant="secondary" size="sm" icon="eye">Manage</x-admin.button>
                                        <x-admin.button :href="route('admin.services.edit', $service)" variant="ghost" size="sm" class="ui-btn-icon" icon="pencil" aria-label="Edit {{ $service->title }}" title="Edit" />
                                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                              data-confirm="“{{ $service->title }}”, all of its content, achievements and attachments will be permanently deleted."
                                              data-confirm-title="Delete this service?" data-confirm-label="Delete service">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="ui-btn ui-btn-ghost-danger ui-btn-icon ui-btn-sm" aria-label="Delete {{ $service->title }}" title="Delete">
                                                <x-admin.icon name="trash" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr x-show="none" x-cloak>
                            <td colspan="4"><x-admin.empty-state icon="search" title="No matching services" description="Try a different search or clear the filters." /></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if ($services->hasPages())
                {{ $services->links('components.admin.pagination') }}
            @endif
        @else
            <x-admin.empty-state icon="cog-8-tooth" title="No services yet" description="Create your first service to get started.">
                <x-admin.button :href="route('admin.services.create')" icon="plus">Create service</x-admin.button>
            </x-admin.empty-state>
        @endif
    </x-admin.card>
@endsection

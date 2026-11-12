@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Models\User[] $users */
@endphp
@extends('admin.layout', ['title' => 'Manage Admin/Staff'])

@section('content')
    <x-admin.page-header title="Admin / Staff" subtitle="Manage administrator and staff accounts." :breadcrumbs="['Admin / Staff' => null]">
        <x-admin.button :href="route('admin.users.create')" icon="plus">New admin / staff</x-admin.button>
    </x-admin.page-header>

    <x-admin.card :padding="false" x-data="tpcFilter()">
        @if ($users->total() > 0)
            <div class="flex flex-col gap-3 border-b border-ui-line p-4 sm:flex-row sm:items-center sm:justify-between">
                <x-admin.search-input placeholder="Filter this page by name or email" />
                <p class="text-xs text-ui-muted"><span class="font-semibold text-ui-ink tabular-nums">{{ $users->total() }}</span> total</p>
            </div>

            <div class="ui-table-wrap">
                <table class="ui-table">
                    <caption class="sr-only">Admin and staff accounts</caption>
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col" class="hidden sm:table-cell">Email</th>
                            <th scope="col" class="hidden md:table-cell">Role</th>
                            <th scope="col" class="ui-col-actions"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr data-search="{{ $user->name }} {{ $user->email }}" x-show="matches($el)">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-brand-50 text-sm font-semibold text-brand-dark">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        <div class="min-w-0">
                                            <p class="truncate font-medium text-ui-ink">{{ $user->name }}</p>
                                            <p class="truncate text-xs text-ui-muted sm:hidden">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden text-ui-muted sm:table-cell">{{ $user->email }}</td>
                                <td class="hidden md:table-cell"><x-admin.badge type="success">Admin/Staff</x-admin.badge></td>
                                <td class="ui-col-actions">
                                    <div class="inline-flex items-center gap-1">
                                        <x-admin.button :href="route('admin.users.edit', $user)" variant="secondary" size="sm" icon="pencil">Edit</x-admin.button>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                              data-confirm="{{ $user->name }} will lose access immediately. This cannot be undone." data-confirm-title="Delete this account?" data-confirm-label="Delete account">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="ui-btn ui-btn-ghost-danger ui-btn-icon ui-btn-sm" aria-label="Delete {{ $user->name }}" title="Delete">
                                                <x-admin.icon name="trash" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr x-show="none" x-cloak>
                            <td colspan="4"><x-admin.empty-state icon="search" title="No matching accounts" description="Try a different search." /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            {{ $users->links('components.admin.pagination') }}
        @else
            <x-admin.empty-state icon="users" title="No accounts yet" description="Create the first admin or staff account to get started.">
                <x-admin.button :href="route('admin.users.create')" icon="plus">New admin / staff</x-admin.button>
            </x-admin.empty-state>
        @endif
    </x-admin.card>
@endsection

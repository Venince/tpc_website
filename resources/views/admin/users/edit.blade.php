@extends('admin.layout', ['title' => 'Edit Admin/Staff'])

@section('content')
    <x-admin.page-header title="Edit admin / staff" :subtitle="$user->email"
        :breadcrumbs="['Admin / Staff' => route('admin.users.index'), $user->name => null]" />

    <div class=" space-y-4" x-data="{ changePassword: {{ $errors->has('password') ? 'true' : 'false' }} }">
        <div class="flex items-center gap-3 rounded-xl border border-ui-line bg-white p-4 shadow-ui-xs">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-brand text-base font-semibold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-ui-ink">{{ $user->name }}</p>
                <p class="truncate text-xs text-ui-muted">{{ $user->email }}</p>
            </div>
            <x-admin.badge type="success">Admin/Staff</x-admin.badge>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
            @csrf @method('PUT')

            <x-admin.card title="Account information">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.field label="Name" name="name" required>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required class="ui-input @error('name') is-invalid @enderror">
                    </x-admin.field>
                    <x-admin.field label="Email" name="email" required>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="off" class="ui-input @error('email') is-invalid @enderror">
                    </x-admin.field>
                </div>
            </x-admin.card>

            <x-admin.card title="Reset password" description="Optional — leave unchanged to keep the current password.">
                <x-slot:actions>
                    <button type="button" class="ui-btn ui-btn-secondary ui-btn-sm" @click="changePassword = !changePassword" :aria-expanded="changePassword">
                        <span x-text="changePassword ? 'Cancel' : 'Change'"></span>
                    </button>
                </x-slot:actions>

                <div x-show="changePassword" x-cloak x-transition class="grid gap-5 sm:grid-cols-2">
                    {{-- Disabled while collapsed so blank values are never submitted --}}
                    <x-admin.field label="New password" name="password">
                        <x-admin.password-input name="password" placeholder="Min. 8 characters" x-bind:disabled="!changePassword" class="@error('password') is-invalid @enderror" />
                    </x-admin.field>
                    <x-admin.field label="Confirm new password" name="password_confirmation">
                        <x-admin.password-input name="password_confirmation" placeholder="Re-enter new password" x-bind:disabled="!changePassword" />
                    </x-admin.field>
                </div>
                <p x-show="!changePassword" class="text-sm text-ui-muted">The password is hidden. Choose “Change” to set a new one.</p>
            </x-admin.card>

            <div class="ui-actionbar">
                <x-admin.button :href="route('admin.users.index')" variant="secondary">Cancel</x-admin.button>
                <x-admin.button type="submit">Save changes</x-admin.button>
            </div>
        </form>

        <x-admin.card title="Danger zone" class="border-red-200">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-ui-ink">Delete this account</p>
                    <p class="text-sm text-ui-muted">Permanently removes <strong class="text-ui-ink">{{ $user->name }}</strong>’s access. This cannot be undone.</p>
                </div>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                      data-confirm="{{ $user->name }} will lose access immediately. This cannot be undone." data-confirm-title="Delete this account?" data-confirm-label="Delete account">
                    @csrf @method('DELETE')
                    <x-admin.button type="submit" variant="danger" icon="trash">Delete account</x-admin.button>
                </form>
            </div>
        </x-admin.card>
    </div>
@endsection

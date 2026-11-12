@extends('admin.layout', ['title' => 'Create Admin/Staff'])

@section('content')
    <x-admin.page-header title="New admin / staff" subtitle="Create an account that can manage website content."
        :breadcrumbs="['Admin / Staff' => route('admin.users.index'), 'New' => null]" />

    <form method="POST" action="{{ route('admin.users.store') }}" class=" space-y-4">
        @csrf

        <x-admin.card title="Account information">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-admin.field label="Name" name="name" required>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Full name" required autofocus class="ui-input @error('name') is-invalid @enderror">
                </x-admin.field>
                <x-admin.field label="Email" name="email" required>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required autocomplete="off" class="ui-input @error('email') is-invalid @enderror">
                </x-admin.field>
            </div>
        </x-admin.card>

        <x-admin.card title="Set password" description="Minimum 8 characters.">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-admin.field label="Password" name="password" required>
                    <x-admin.password-input name="password" placeholder="Min. 8 characters" required class="@error('password') is-invalid @enderror" />
                </x-admin.field>
                <x-admin.field label="Confirm password" name="password_confirmation" required>
                    <x-admin.password-input name="password_confirmation" placeholder="Re-enter password" required />
                </x-admin.field>
            </div>
        </x-admin.card>

        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.users.index')" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Create account</x-admin.button>
        </div>
    </form>
@endsection

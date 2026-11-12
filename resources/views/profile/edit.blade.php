@extends('admin.layout', ['title' => 'My Profile'])

@section('content')
    @php
        $me = auth()->user();
        $isSuper = (bool) ($me->is_super_admin ?? false);
    @endphp

    <x-admin.page-header title="My profile" subtitle="Manage your account details, password and security."
        :breadcrumbs="['My profile' => null]" />

    <div class="grid items-start gap-4 lg:grid-cols-3 xl:grid-cols-4">

        {{-- Identity --}}
        <aside class="space-y-4 lg:sticky lg:top-0" aria-label="Account summary">
            <x-admin.card>
                <div class="flex items-center gap-4 lg:flex-col lg:items-start lg:gap-3">
                    <span class="grid h-16 w-16 shrink-0 place-items-center rounded-full bg-brand text-2xl font-semibold text-white" aria-hidden="true">
                        {{ strtoupper(mb_substr($me->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-lg font-semibold leading-tight text-ui-ink">{{ $me->name }}</p>
                        <p class="mt-0.5 truncate text-sm text-ui-muted">{{ $me->email }}</p>
                        <x-admin.badge type="success" class="mt-2">{{ $isSuper ? 'Super Admin' : 'Admin' }}</x-admin.badge>
                    </div>
                </div>

                @if ($me->created_at)
                    <dl class="mt-4 border-t border-ui-line pt-4 text-sm">
                        <div class="flex items-baseline justify-between gap-3">
                            <dt class="text-ui-muted">Member since</dt>
                            <dd class="font-medium text-ui-ink">{{ $me->created_at->format('M Y') }}</dd>
                        </div>
                    </dl>
                @endif
            </x-admin.card>

            <x-admin.card title="On this page" :padding="false" class="hidden lg:block">
                <nav aria-label="Profile sections" class="p-2">
                    @foreach ([
                        ['#profile-info', 'Profile information', 'user-circle', false],
                        ['#update-password', 'Password', 'shield-check', false],
                        ['#delete-account', 'Delete account', 'trash', true],
                    ] as [$href, $label, $icon, $danger])
                        <a href="{{ $href }}"
                           class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand
                                  {{ $danger ? 'text-red-700 hover:bg-red-50' : 'text-ui-ink hover:bg-ui-bg-2' }}">
                            <x-admin.icon :name="$icon" :class="'h-4 w-4 ' . ($danger ? 'text-red-500' : 'text-gray-500')" />
                            {{ $label }}
                        </a>
                    @endforeach
                </nav>
            </x-admin.card>
        </aside>

        {{-- Forms --}}
        <div class="grid gap-4 xl:grid-cols-2 lg:col-span-2 xl:col-span-3">
            <div id="profile-info" class="scroll-mt-4">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div id="update-password" class="scroll-mt-4">
                @include('profile.partials.update-password-form')
            </div>

            <div id="delete-account" class="scroll-mt-4 xl:col-span-2">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection

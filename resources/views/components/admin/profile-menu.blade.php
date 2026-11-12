@php
    $user = auth()->user();
    $name = $user->name ?? 'Admin';
    $email = $user->email ?? '';
    $initials = collect(preg_split('/\s+/', trim($name)))->filter()->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
    $role = ($user->is_super_admin ?? false) ? 'Super Admin' : 'Admin';
@endphp
{{-- Header account menu: profile / account settings / sign out. --}}
<div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false" @click.outside="open = false">
    <button type="button" @click="open = !open; if (open) $nextTick(() => $refs.first?.focus())"
            :aria-expanded="open" aria-haspopup="menu" aria-label="Account menu"
            class="flex items-center gap-2 rounded-lg py-1 pl-1 pr-2 transition-colors hover:bg-ui-bg-2
                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand">
        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-brand text-xs font-semibold text-white">{{ $initials ?: 'A' }}</span>
        <span class="hidden min-w-0 text-left leading-tight sm:block">
            <span class="block max-w-[10rem] truncate text-sm font-medium text-ui-ink">{{ $name }}</span>
            <span class="block text-[11px] text-ui-muted">{{ $role }}</span>
        </span>
        <x-admin.icon name="chevron-down" class="hidden h-4 w-4 text-gray-400 transition-transform sm:block" x-bind:class="open ? 'rotate-180' : ''" />
    </button>

    <div x-cloak x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 z-50 mt-2 w-64 origin-top-right rounded-xl border border-ui-line bg-white p-1.5 shadow-ui-lg"
         role="menu" aria-label="Account">
        <div class="px-3 py-2.5">
            <p class="truncate text-sm font-semibold text-ui-ink">{{ $name }}</p>
            <p class="truncate text-xs text-ui-muted">{{ $email }}</p>
            <x-admin.badge type="success" class="mt-2">{{ $role }}</x-admin.badge>
        </div>
        <div class="ui-divider my-1"></div>
        <a x-ref="first" href="{{ route('profile.edit') }}" data-no-pjax="true" role="menuitem" class="ui-menu-item">
            <x-admin.icon name="user-circle" class="h-5 w-5 text-gray-400" />
            <span>Profile &amp; account settings</span>
        </a>
        <a href="{{ route('home') }}" data-no-pjax="true" role="menuitem" class="ui-menu-item">
            <x-admin.icon name="globe" class="h-5 w-5 text-gray-400" />
            <span>View website</span>
        </a>
        <div class="ui-divider my-1"></div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" role="menuitem" class="ui-menu-item ui-menu-item-danger">
                <x-admin.icon name="logout" class="h-5 w-5" />
                <span>Sign out</span>
            </button>
        </form>
    </div>
</div>

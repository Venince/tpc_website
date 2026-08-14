@php
    $user     = auth()->user();
    $name     = $user->name ?? 'Admin';
    $email    = $user->email ?? '';

    $initials = collect(preg_split('/\s+/', trim($name)))
        ->filter()->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('');

    $isAdmin     = $user && $user->can('access-admin');
    $isOnProfile = request()->routeIs('profile.*');
@endphp

{{-- Sidebar footer widget. Relies on `sidebarCollapsed` from the ancestor x-data (layout.blade.php). --}}
<div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">

    {{-- ── Trigger ────────────────────────────────────────────────── --}}
    <button
        type="button"
        @click="open = !open"
        :aria-expanded="open"
        aria-haspopup="menu"
        class="group relative flex w-full items-center gap-3 rounded-2xl bg-neo-surface px-3 py-2.5
            shadow-neo-sm transition
            hover:shadow-neo-hover active:shadow-neo-inset-sm
            focus:outline-none focus:ring-2 focus:ring-tpc-primary/20"
        :class="sidebarCollapsed ? 'sm:justify-center sm:gap-0 sm:px-2' : ''"
    >
        {{-- Avatar --}}
        <div class="relative grid h-9 w-9 shrink-0 place-items-center overflow-hidden rounded-xl
                    shadow-neo-sm bg-gradient-to-br from-tpc-primary to-tpc-secondary">
            <span class="text-xs font-bold tracking-tight text-white select-none">
                {{ $initials ?: 'A' }}
            </span>
            {{-- Online dot --}}
            <span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full bg-emerald-400 ring-2 ring-neo-surface"></span>
        </div>

        {{-- Name + role --}}
        <div
            class="min-w-0 flex-1 overflow-hidden text-left leading-tight transition-all duration-200 ease-[cubic-bezier(0.22,1,0.36,1)] opacity-100 max-w-[160px] translate-x-0"
            :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''"
        >
            <p class="text-sm font-semibold text-neo-ink truncate">
                {{ explode(' ', $name)[0] }}
            </p>
            <p class="text-[10px] font-medium text-tpc-primary/70 uppercase tracking-wide truncate">
                {{ $isAdmin ? 'Admin' : 'Account' }}
            </p>
        </div>

        {{-- Chevron --}}
        <svg
            class="h-3.5 w-3.5 shrink-0 text-neo-ink/40 transition-all duration-200 group-hover:text-neo-ink/70"
            :class="[open ? 'rotate-180' : '', sidebarCollapsed ? 'sm:hidden' : '']"
            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
        >
            <path fill-rule="evenodd"
                d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                clip-rule="evenodd"/>
        </svg>
    </button>

    {{-- ── Flyout menu (opens upward so it isn't clipped at the bottom of the screen) ────── --}}
    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
        @click.outside="open = false"
        class="absolute bottom-full left-0 mb-2 w-64 overflow-hidden rounded-2xl
               bg-neo-surface shadow-neo-lg"
        role="menu"
        style="transform-origin: bottom left;"
    >
        {{-- User card header --}}
        <div class="flex items-center gap-3 bg-neo-bg px-4 py-3.5">
            <div class="relative grid h-10 w-10 shrink-0 place-items-center overflow-hidden rounded-xl
                        shadow-neo-sm bg-gradient-to-br from-tpc-primary/20 to-tpc-accent/25">
                <span class="text-sm font-bold tracking-tight text-tpc-primary select-none">
                    {{ $initials ?: 'A' }}
                </span>
                <span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full bg-emerald-400 ring-2 ring-neo-bg"></span>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-neo-ink truncate">{{ $name }}</p>
                <p class="text-xs text-neo-ink/50 truncate">{{ $email }}</p>
            </div>
        </div>

        {{-- Menu items --}}
        <div class="py-1.5">

            {{-- Admin Panel — only on profile page --}}
            @if($isAdmin && $isOnProfile)
                <a
                    data-no-pjax="true"
                    href="{{ route('admin.dashboard') }}"
                    class="group flex items-center gap-3 px-3 py-2.5 mx-1.5 rounded-xl text-sm
                           text-neo-ink/80 hover:bg-black/[0.03] hover:text-tpc-primary transition-colors duration-150"
                    role="menuitem"
                >
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg shadow-neo-sm
                                 bg-tpc-primary/10 text-tpc-primary">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 10.5L12 3l9 7.5V21a1.5 1.5 0 0 1-1.5 1.5H4.5A1.5 1.5 0 0 1 3 21v-10.5Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 22.5V15a3 3 0 0 1 6 0v7.5"/>
                        </svg>
                    </span>
                    <div>
                        <p class="font-semibold text-sm leading-tight">Admin Panel</p>
                        <p class="text-xs text-neo-ink/50 leading-tight">Back to management</p>
                    </div>
                </a>
                <div class="my-1.5 mx-3 h-px bg-black/[0.06]"></div>
            @endif

            {{-- Profile — only when not on profile page --}}
            @if(!$isOnProfile)
                <a
                    data-no-pjax="true"
                    href="{{ route('profile.edit') }}"
                    class="group flex items-center gap-3 px-3 py-2.5 mx-1.5 rounded-xl text-sm
                           text-neo-ink/80 hover:bg-black/[0.03] hover:text-tpc-primary transition-colors duration-150"
                    role="menuitem"
                >
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg shadow-neo-sm
                                 bg-tpc-primary/10 text-tpc-primary">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 1 0-16 0"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 13a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/>
                        </svg>
                    </span>
                    <div>
                        <p class="font-semibold text-sm leading-tight">My Profile</p>
                        <p class="text-xs text-neo-ink/50 leading-tight">Account settings</p>
                    </div>
                </a>
                <div class="my-1.5 mx-3 h-px bg-black/[0.06]"></div>
            @endif

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="group w-full flex items-center gap-3 px-3 py-2.5 mx-1.5 rounded-xl text-sm
                           text-neo-ink/70 hover:bg-red-50 hover:text-red-600 transition-colors duration-150
                           focus:outline-none"
                    style="width: calc(100% - 0.75rem);"
                    role="menuitem"
                >
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg shadow-neo-sm
                                 bg-red-50 text-red-400 group-hover:bg-red-100 group-hover:text-red-600 transition-colors">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21V3"/>
                        </svg>
                    </span>
                    <span class="font-medium">Sign out</span>
                </button>
            </form>

        </div>
    </div>

</div>

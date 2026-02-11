@php
    $user = auth()->user();
    $name = $user->name ?? 'Admin';

    $initials = collect(preg_split('/\s+/', trim($name)))
        ->filter()
        ->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('');

    $isAdmin = $user && $user->can('access-admin');
    $roleLabel = $isAdmin ? 'Administrator' : 'Account';

    $isOnProfile = request()->routeIs('profile.*');
@endphp

<div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
    {{-- Trigger --}}
    <button
        type="button"
        @click="open = !open"
        :aria-expanded="open"
        aria-haspopup="menu"
        class="group inline-flex items-center gap-3 rounded-2xl border border-tpc-primary/15 bg-white/60 px-3 py-2 shadow-sm backdrop-blur
               transition hover:bg-white hover:shadow-md
               focus:outline-none focus:ring-2 focus:ring-tpc-primary/25"
    >
        {{-- Avatar --}}
        <div class="relative grid h-10 w-10 place-items-center overflow-hidden rounded-2xl border border-tpc-primary/15 bg-white">
            <div class="absolute inset-0 bg-gradient-to-br from-tpc-primary/15 via-transparent to-tpc-accent/25"></div>
            <span class="relative text-sm font-extrabold tracking-tight text-tpc-primary">
                {{ $initials ?: 'A' }}
            </span>
        </div>

        {{-- Name + role (KEEP this) --}}
        <div class="hidden sm:block text-left leading-tight">
            <p class="text-sm font-semibold text-tpc-ink truncate max-w-[180px]">
                {{ $name }}
            </p>

            <div class="mt-0.5 inline-flex items-center gap-2">
                <span class="inline-flex items-center rounded-full border border-tpc-primary/15 bg-white/70 px-2 py-0.5 text-[11px] font-semibold text-tpc-ink/70">
                    {{ $roleLabel }}
                </span>

                @if($isAdmin)
                    <span class="inline-flex h-1.5 w-1.5 rounded-full bg-tpc-primary"></span>
                @endif
            </div>
        </div>

        {{-- Chevron --}}
        <svg class="h-4 w-4 text-tpc-ink/50 transition-transform duration-200"
             :class="open ? 'rotate-180' : ''"
             viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd"
                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                  clip-rule="evenodd"/>
        </svg>
    </button>

    {{-- Dropdown --}}
    <div
        x-cloak
        x-show="open"
        x-transition.origin.top.right
        @click.outside="open = false"
        class="absolute right-0 mt-2 w-64 overflow-hidden rounded-2xl border border-tpc-primary/12 bg-white/90 shadow-2xl backdrop-blur"
        role="menu"
    >
        <div class="py-2">

            {{-- ✅ Admin Panel only shows on Profile page --}}
            @if($isAdmin && $isOnProfile)
                <a
                    data-no-pjax="true"
                    href="{{ route('admin.dashboard') }}"
                    class="group flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5"
                    role="menuitem"
                >
                    <span class="grid h-9 w-9 place-items-center rounded-xl border border-tpc-primary/12 bg-white/70 text-tpc-primary group-hover:bg-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 10.5L12 3l9 7.5V21a1.5 1.5 0 0 1-1.5 1.5H4.5A1.5 1.5 0 0 1 3 21v-10.5Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 22.5V15a3 3 0 0 1 6 0v7.5"/>
                        </svg>
                    </span>

                    <div class="min-w-0">
                        <p class="truncate">Admin Panel</p>
                        <p class="text-xs font-medium text-tpc-ink/60">Back to management</p>
                    </div>
                </a>

                <div class="my-2 h-px bg-tpc-primary/10"></div>
            @endif

            {{-- ✅ Profile only shows when NOT on Profile page --}}
            @if(!$isOnProfile)
                <a
                    data-no-pjax="true"
                    href="{{ route('profile.edit') }}"
                    class="group flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5"
                    role="menuitem"
                >
                    <span class="grid h-9 w-9 place-items-center rounded-xl border border-tpc-primary/12 bg-white/70 text-tpc-primary group-hover:bg-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M20 21a8 8 0 1 0-16 0"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 13a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/>
                        </svg>
                    </span>

                    <div class="min-w-0">
                        <p class="font-semibold truncate">Profile</p>
                        <p class="text-xs text-tpc-ink/60">Update account details</p>
                    </div>
                </a>

                <div class="my-2 h-px bg-tpc-primary/10"></div>
            @endif

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="group w-full flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-tpc-ink hover:bg-tpc-primary/5"
                    role="menuitem"
                >
                    <span class="grid h-9 w-9 place-items-center rounded-xl border border-tpc-primary/12 bg-white/70 text-tpc-primary group-hover:bg-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10 17l5-5-5-5"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12H3"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 3v18"/>
                        </svg>
                    </span>

                    Logout
                </button>
            </form>

        </div>
    </div>
</div>

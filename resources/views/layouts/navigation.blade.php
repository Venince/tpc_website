@php
    $user = auth()->user();
    $isAdmin = $user && $user->can('access-admin');
    $isOnProfile = request()->routeIs('profile.*');
@endphp

<nav x-data="{ open: false }"
     class="sticky top-0 z-50 border-b border-tpc-primary/10 bg-white/70 backdrop-blur-xl">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <div class="flex items-center justify-between gap-3">

            {{-- Left: Brand --}}
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('home') }}"
                   class="flex items-center gap-3 group min-w-0"
                   data-no-pjax="true">
                    <img
                        src="{{ asset('images/TPC-Logo.png') }}"
                        alt="TPC Logo"
                        class="h-10 w-auto drop-shadow-sm"
                        loading="eager"
                        decoding="async"
                    >

                    <div class="hidden sm:block min-w-0">
                        <p class="text-base font-semibold text-tpc-primary tracking-tight group-hover:text-tpc-secondary transition truncate">
                            Talibon Polytechnic College
                        </p>
                        <p class="text-xs text-tpc-ink/60 truncate">
                            {{ $isAdmin ? 'Administrator' : 'Account' }}
                        </p>
                    </div>
                </a>
            </div>

            {{-- Right: Dropdown + Mobile Toggle --}}
            <div class="flex items-center gap-3">
                @auth
                    <x-tpc-account-dropdown />
                @endauth

                @if(!$isOnProfile)
                    <button
                        @click="open = !open"
                        class="inline-flex sm:hidden items-center justify-center rounded-xl border border-tpc-primary/15 bg-white/60 p-2 text-tpc-primary shadow-sm hover:bg-white hover:shadow-md transition"
                        aria-label="Open menu"
                        type="button"
                    >
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>

        </div>
    </div>

    {{-- Mobile menu --}}
    @if($isOnProfile)
    <div x-cloak x-show="open" x-transition.opacity.duration.180ms class="sm:hidden">
        <div class="px-4 pb-4">
            <div class="rounded-2xl border border-tpc-primary/10 bg-white/80 backdrop-blur p-2 shadow-sm">
                <a href="{{ route('home') }}"
                   class="block rounded-xl px-3 py-2 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5"
                   data-no-pjax="true">
                    Home
                </a>

                @auth
                    @if($isAdmin && $isOnProfile)
                        <a href="{{ route('admin.dashboard') }}"
                           class="block rounded-xl px-3 py-2 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5"
                           data-no-pjax="true">
                            Admin
                        </a>
                    @endif

                    @if(!$isOnProfile)
                        <a href="{{ route('profile.edit') }}"
                           class="block rounded-xl px-3 py-2 text-sm font-semibold text-tpc-ink hover:bg-tpc-primary/5"
                           data-no-pjax="true">
                            Profile
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="pt-1">
                        @csrf
                        <button type="submit"
                                class="w-full text-left rounded-xl px-3 py-2 text-sm font-semibold text-tpc-ink hover:bg-tpc-primary/5">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
    @endif
</nav>

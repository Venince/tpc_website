@php
    $user = auth()->user();
    $isAdmin = $user && $user->can('access-admin');

    $isRoute = fn ($pattern) => request()->routeIs($pattern);

    // Active states
    $homeActive      = $isRoute('home');
    $academicsActive = $isRoute('academics');
    $admissionActive = $isRoute('admission');
    $newsActive      = $isRoute('news.*');
    $contactActive   = $isRoute('contact');

    $messagesActive  = $isRoute('admin.messages.*');
    $adminActive     = $isRoute('admin.*') && !$messagesActive;

    // Helper: keep underline animation + force "active" state when needed
    $navClass = fn (bool $active = false) => 'tpc-navlink' . ($active ? ' is-active' : '');
@endphp

<nav
    x-data="{ open: false }"
    x-on:keydown.escape.window="open=false"
    class="sticky top-0 z-50 border-b border-tpc-primary/25 bg-white/85 backdrop-blur-md"
>
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-3">
        {{-- Brand --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 group min-w-0" data-tpc-link id="brand-home">
            <img
                src="{{ asset('images/TPC-Logo.png') }}"
                alt="TPC Logo"
                class="h-10 w-auto drop-shadow-sm"
                loading="eager"
                decoding="async"
            >
            <span class="text-lg font-semibold text-tpc-primary tracking-tight group-hover:text-tpc-secondary transition truncate">
                Talibon Polytechnic College
            </span>
        </a>

        {{-- Desktop links --}}
        <div class="hidden sm:flex gap-6 items-center">
            <a id="nav-home" data-tpc-link class="{{ $navClass($homeActive) }}" href="{{ route('home') }}"
               @if($homeActive) aria-current="page" @endif>
                Home
            </a>

            <a id="nav-about" data-tpc-link class="{{ $navClass(false) }}" href="{{ route('home') }}#about">
                About
            </a>

            <a data-tpc-link class="{{ $navClass($academicsActive) }}" href="{{ route('academics') }}"
               @if($academicsActive) aria-current="page" @endif>
                Academics
            </a>

            <a data-tpc-link class="{{ $navClass($admissionActive) }}" href="{{ route('admission') }}"
               @if($admissionActive) aria-current="page" @endif>
                Admission
            </a>

            <a data-tpc-link class="{{ $navClass($newsActive) }}" href="{{ route('news.index') }}"
               @if($newsActive) aria-current="page" @endif>
                News
            </a>

            <a data-tpc-link class="{{ $navClass($contactActive) }}" href="{{ route('contact') }}"
               @if($contactActive) aria-current="page" @endif>
                Contact
            </a>

            @auth
                @if($isAdmin)
                    {{-- Messages (mail icon) --}}
                    <a
                        id="nav-messages"
                        data-tpc-link
                        href="{{ route('admin.messages.index') }}"
                        class="{{ $navClass($messagesActive) }} inline-flex items-center justify-center"
                        title="Messages"
                        aria-label="Messages"
                        @if($messagesActive) aria-current="page" data-active="true" @endif
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6" />
                        </svg>
                        <span class="sr-only">Messages</span>
                    </a>

                    {{-- Admin (profile icon) --}}
                    <a
                        id="nav-admin"
                        data-tpc-link
                        href="{{ route('admin.dashboard') }}"
                        class="{{ $navClass($adminActive) }} inline-flex items-center justify-center"
                        title="Admin"
                        aria-label="Admin"
                        data-no-pjax="true"
                        @if($adminActive) aria-current="page" data-active="true" @endif
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 1 0-16 0" />
                            <circle cx="12" cy="8" r="4" />
                        </svg>
                        <span class="sr-only">Admin</span>
                    </a>
                @endif
            @endauth
        </div>

        {{-- Mobile: Account dropdown + Hamburger --}}
        <div class="flex items-center gap-2 sm:hidden">
            @auth
                <x-tpc-account-dropdown />
            @endauth

            <button
                type="button"
                @click="open = !open"
                class="inline-flex items-center justify-center rounded-xl border border-tpc-primary/15 bg-white/70 p-2 text-tpc-primary shadow-sm hover:bg-white hover:shadow-md transition"
                aria-label="Toggle menu"
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
        </div>
    </div>

    {{-- Overlay: tapping outside closes --}}
    <div
        x-cloak
        x-show="open"
        x-transition.opacity.duration.150ms
        class="fixed inset-0 z-40 bg-black/20 sm:hidden"
        @click="open=false"
        aria-hidden="true"
    ></div>

    {{-- Mobile dropdown --}}
    <div
        x-cloak
        x-show="open"
        x-transition.origin.top.duration.150ms
        class="absolute left-0 right-0 z-50 sm:hidden"
    >
        <div
            class="mx-4 mt-3 rounded-2xl border border-tpc-primary/15 bg-white/95 shadow-xl backdrop-blur"
            @click.outside="open=false"
        >
            <div class="p-3 max-h-[calc(100vh-6rem)] overflow-y-auto space-y-1">
                @php
                    $mLink = "flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5";
                @endphp

                {{-- Close menu when navigating --}}
                <a class="{{ $mLink }}" data-tpc-link href="{{ route('home') }}" @click="open=false" @pointerdown="open=false">Home</a>
                <a class="{{ $mLink }}" data-tpc-link href="{{ route('home') }}#about" @click="open=false" @pointerdown="open=false">About</a>
                <a class="{{ $mLink }}" data-tpc-link href="{{ route('academics') }}" @click="open=false" @pointerdown="open=false">Academics</a>
                <a class="{{ $mLink }}" data-tpc-link href="{{ route('admission') }}" @click="open=false" @pointerdown="open=false">Admission</a>
                <a class="{{ $mLink }}" data-tpc-link href="{{ route('news.index') }}" @click="open=false" @pointerdown="open=false">News</a>
                <a class="{{ $mLink }}" data-tpc-link href="{{ route('contact') }}" @click="open=false" @pointerdown="open=false">Contact</a>

                @auth
                    @if($isAdmin)
                        <div class="my-2 h-px bg-tpc-primary/10"></div>

                        <a class="{{ $mLink }}" data-tpc-link href="{{ route('admin.messages.index') }}" @click="open=false" @pointerdown="open=false">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6" />
                            </svg>
                            Messages
                        </a>

                        <a class="{{ $mLink }}" href="{{ route('admin.dashboard') }}" data-no-pjax="true" @click="open=false" @pointerdown="open=false">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 1 0-16 0" />
                                <circle cx="12" cy="8" r="4" />
                            </svg>
                            Admin
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</nav>

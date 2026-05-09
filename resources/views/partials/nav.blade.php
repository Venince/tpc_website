@php
    $user    = auth()->user();
    $isAdmin = $user && $user->can('access-admin');
    $isRoute = fn ($pattern) => request()->routeIs($pattern);

    $homeActive      = $isRoute('home');
    $academicsActive = $isRoute('academics');
    $admissionActive = $isRoute('admission');
    $newsActive      = $isRoute('news.*');
    $contactActive   = $isRoute('contact');
    $messagesActive  = $isRoute('admin.messages.*');
    $adminActive     = $isRoute('admin.*') && !$messagesActive;
@endphp

<header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">

    {{-- Green top stripe --}}
    <div class="h-1 w-full bg-tpc-primary"></div>

    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">

        {{-- Brand --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 group min-w-0" data-tpc-link>
            <img src="{{ asset('images/TPC-Logo.png') }}" alt="TPC Logo"
                 class="h-10 w-auto" loading="eager" decoding="async">
            <div class="min-w-0">
                <p class="text-base font-bold text-tpc-primary leading-tight tracking-tight truncate">
                    Talibon Polytechnic College
                </p>
                <p class="text-[11px] text-gray-500 tracking-widest uppercase hidden sm:block">
                    Official Website
                </p>
            </div>
        </a>

        {{-- Desktop nav --}}
        <nav class="hidden sm:flex items-center gap-1" aria-label="Main navigation">
            @php
                $link = fn(bool $active) =>
                    'tpc-navlink px-3 py-2 text-sm font-semibold transition ' .
                    ($active ? 'tpc-active' : '');
            @endphp

            <a id="nav-home" data-tpc-link class="{{ $link($homeActive) }}"
               href="{{ route('home') }}" @if($homeActive) aria-current="page" @endif>
               Home
            </a>

            <a id="nav-about" data-tpc-link class="{{ $link(false) }}"
               href="{{ route('home') }}#about">
               About
            </a>

            <a data-tpc-link class="{{ $link($academicsActive) }}"
               href="{{ route('academics') }}" @if($academicsActive) aria-current="page" @endif>
               Academics
            </a>

            <a data-tpc-link class="{{ $link($admissionActive) }}"
               href="{{ route('admission') }}" @if($admissionActive) aria-current="page" @endif>
               Admission
            </a>

            <a data-tpc-link class="{{ $link($newsActive) }}"
               href="{{ route('news.index') }}" @if($newsActive) aria-current="page" @endif>
               News
            </a>

            <a data-tpc-link class="{{ $link($contactActive) }}"
               href="{{ route('contact') }}" @if($contactActive) aria-current="page" @endif>
               Contact
            </a>

            @auth
                @if($isAdmin)
                    <span class="mx-1 h-5 w-px bg-gray-200"></span>

                    <a id="nav-messages" data-tpc-link
                       href="{{ route('admin.messages.index') }}"
                       class="{{ $link($messagesActive) }} inline-flex items-center gap-1.5"
                       title="Messages" @if($messagesActive) aria-current="page" @endif>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6"/>
                        </svg>
                        <span class="sr-only">Messages</span>
                    </a>

                    <a id="nav-admin" data-tpc-link
                       href="{{ route('admin.dashboard') }}"
                       class="{{ $link($adminActive) }} inline-flex items-center gap-1.5"
                       title="Admin" data-no-pjax="true"
                       @if($adminActive) aria-current="page" @endif>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 1 0-16 0"/>
                            <circle cx="12" cy="8" r="4"/>
                        </svg>
                        <span class="sr-only">Admin</span>
                    </a>
                @endif
            @endauth
        </nav>

        {{-- Mobile: account + hamburger --}}
        <div class="flex items-center gap-2 sm:hidden" x-data="{ open: false }">
            @auth
                <x-tpc-account-dropdown />
            @endauth

            <button type="button" @click="open = !open"
                    class="inline-flex items-center justify-center rounded border border-gray-200 p-2 text-gray-600 hover:text-tpc-primary hover:border-tpc-primary transition"
                    aria-label="Toggle menu">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden':open,'inline-flex':!open}" class="inline-flex"
                          stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{'hidden':!open,'inline-flex':open}" class="hidden"
                          stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Mobile overlay --}}
            <div x-cloak x-show="open" x-transition.opacity.duration.150ms
                 class="fixed inset-0 z-40 bg-black/20"
                 @click="open=false" aria-hidden="true"></div>

            {{-- Mobile menu --}}
            <div x-cloak x-show="open" x-transition.origin.top.duration.150ms
                 class="absolute left-0 right-0 top-full z-50">
                <nav class="mx-4 mt-1 border border-gray-200 bg-white shadow-lg"
                     @click.outside="open=false">
                    @php
                        $mLink = 'tpc-navlink flex items-center gap-2 px-4 py-3 text-sm font-semibold text-gray-700 border-b border-gray-100 hover:text-tpc-primary hover:bg-tpc-primary/5 transition';
                    @endphp
                    <a class="{{ $mLink }}" data-tpc-link href="{{ route('home') }}" @click="open=false">Home</a>
                    <a class="{{ $mLink }}" data-tpc-link href="{{ route('home') }}#about" @click="open=false">About</a>
                    <a class="{{ $mLink }}" data-tpc-link href="{{ route('academics') }}" @click="open=false">Academics</a>
                    <a class="{{ $mLink }}" data-tpc-link href="{{ route('admission') }}" @click="open=false">Admission</a>
                    <a class="{{ $mLink }}" data-tpc-link href="{{ route('news.index') }}" @click="open=false">News</a>
                    <a class="{{ $mLink }} border-b-0" data-tpc-link href="{{ route('contact') }}" @click="open=false">Contact</a>

                    @auth
                        @if($isAdmin)
                            <div class="h-px bg-tpc-primary/20"></div>
                            <a class="{{ $mLink }}" data-tpc-link href="{{ route('admin.messages.index') }}" @click="open=false">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6"/>
                                </svg>
                                Messages
                            </a>
                            <a class="{{ $mLink }} border-b-0" href="{{ route('admin.dashboard') }}" data-no-pjax="true" @click="open=false">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 1 0-16 0"/>
                                    <circle cx="12" cy="8" r="4"/>
                                </svg>
                                Admin
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </div>

    </div>
</header>

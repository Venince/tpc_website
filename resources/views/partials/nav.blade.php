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

        {{-- Mobile: hamburger only --}}
        <div class="flex items-center gap-2 sm:hidden" x-data="{ open: false }">

            {{-- Hamburger button --}}
            <button
                type="button"
                @click="open = !open"
                class="relative inline-flex items-center justify-center w-9 h-9 rounded-lg text-tpc-primary transition-all duration-200"
                :class="open ? 'bg-tpc-primary text-white shadow-md' : 'bg-tpc-primary/8 hover:bg-tpc-primary/15'"
                aria-label="Toggle menu"
            >
                {{-- Hamburger icon --}}
                <svg x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h16"/>
                </svg>
                {{-- Close icon --}}
                <svg x-show="open" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Backdrop --}}
            <div
                x-cloak
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-40 bg-black/30 backdrop-blur-[2px]"
                @click="open = false"
                aria-hidden="true"
            ></div>

            {{-- Mobile menu panel --}}
            <div
                x-cloak
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2 scale-[0.98]"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-y-2 scale-[0.98]"
                class="absolute left-0 right-0 top-full z-50 px-3 pt-2 pb-3"
                @click.outside="open = false"
            >
                <nav class="rounded-2xl bg-white shadow-xl shadow-black/10 ring-1 ring-black/5 overflow-hidden">

                    {{-- Header bar inside menu --}}
                    <div class="bg-tpc-primary px-4 py-3 flex items-center gap-2">
                        <svg class="h-4 w-4 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="text-xs font-semibold text-white/80 uppercase tracking-widest">Navigation</span>
                    </div>

                    {{-- Nav links --}}
                    @php
                        $navItems = [
                            ['label' => 'Home',       'href' => route('home'),       'active' => $homeActive,      'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
                            ['label' => 'About',      'href' => route('home').'#about', 'active' => false,         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                            ['label' => 'Academics',  'href' => route('academics'),   'active' => $academicsActive, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>'],
                            ['label' => 'Admission',  'href' => route('admission'),   'active' => $admissionActive, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>'],
                            ['label' => 'News',       'href' => route('news.index'),  'active' => $newsActive,      'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>'],
                            ['label' => 'Contact',    'href' => route('contact'),     'active' => $contactActive,   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                        ];
                    @endphp

                    <div class="p-2">
                        @foreach ($navItems as $item)
                            <a
                                data-tpc-link
                                href="{{ $item['href'] }}"
                                @click="open = false"
                                @if($item['active']) aria-current="page" @endif
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 group
                                    {{ $item['active']
                                        ? 'bg-tpc-primary text-white shadow-sm'
                                        : 'text-gray-700 hover:bg-tpc-primary/8 hover:text-tpc-primary' }}"
                            >
                                <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-lg transition-all duration-150
                                    {{ $item['active']
                                        ? 'bg-white/20 text-white'
                                        : 'bg-gray-100 text-gray-500 group-hover:bg-tpc-primary/12 group-hover:text-tpc-primary' }}">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        {!! $item['icon'] !!}
                                    </svg>
                                </span>
                                <span class="flex-1">{{ $item['label'] }}</span>
                                @if($item['active'])
                                    <span class="w-1.5 h-1.5 rounded-full bg-white/60"></span>
                                @else
                                    <svg class="h-3.5 w-3.5 text-gray-300 group-hover:text-tpc-primary/40 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                @endif
                            </a>
                        @endforeach
                    </div>

                    @auth
                        @if($isAdmin)
                            {{-- Divider --}}
                            <div class="mx-4 my-1 border-t border-gray-100"></div>

                            {{-- Admin section --}}
                            <div class="px-2 pb-2">
                                <p class="px-3 pt-1 pb-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Admin</p>

                                <a data-tpc-link href="{{ route('admin.messages.index') }}" @click="open=false"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 group
                                       {{ $messagesActive
                                           ? 'bg-tpc-primary text-white shadow-sm'
                                           : 'text-gray-700 hover:bg-tpc-primary/8 hover:text-tpc-primary' }}">
                                    <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-lg transition-all
                                        {{ $messagesActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-tpc-primary/12 group-hover:text-tpc-primary' }}">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6"/>
                                        </svg>
                                    </span>
                                    <span class="flex-1">Messages</span>
                                    <svg class="h-3.5 w-3.5 text-gray-300 group-hover:text-tpc-primary/40 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>

                                <a href="{{ route('admin.dashboard') }}" data-no-pjax="true" @click="open=false"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 group
                                       {{ $adminActive
                                           ? 'bg-tpc-primary text-white shadow-sm'
                                           : 'text-gray-700 hover:bg-tpc-primary/8 hover:text-tpc-primary' }}">
                                    <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-lg transition-all
                                        {{ $adminActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-tpc-primary/12 group-hover:text-tpc-primary' }}">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 1 0-16 0"/>
                                            <circle cx="12" cy="8" r="4"/>
                                        </svg>
                                    </span>
                                    <span class="flex-1">Admin Dashboard</span>
                                    <svg class="h-3.5 w-3.5 text-gray-300 group-hover:text-tpc-primary/40 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    @endauth

                </nav>
            </div>

        </div>
    </div>
</header>

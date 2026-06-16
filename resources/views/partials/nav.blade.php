@php
    $user    = auth()->user();
    $isAdmin = $user && $user->can('access-admin');
    $isRoute = fn ($pattern) => request()->routeIs($pattern);

    $homeActive      = $isRoute('home');
    $academicsActive = $isRoute('academics') || $isRoute('org-chart');
    $admissionActive = $isRoute('admission');
    $newsActive      = $isRoute('news.*');
    $contactActive   = $isRoute('contact');
    $servicesActive  = $isRoute('services.*');
    $messagesActive  = $isRoute('admin.messages.*');
    $adminActive     = $isRoute('admin.*') && !$messagesActive;

    // Load active services once for the dropdown
    $navServices = \App\Models\Service::active()->ordered()->get();

    // Search destinations — label => [route/url, optional #anchor]
    $searchItems = [
        ['label' => 'About',                        'url' => route('home')          . '#about',                     'page' => 'Home'],
        ['label' => 'Vision',                       'url' => route('home')          . '#vision',                    'page' => 'Home'],
        ['label' => 'Mission',                      'url' => route('home')          . '#mission',                   'page' => 'Home'],
        ['label' => 'TPC Updates',                  'url' => route('home')          . '#tpc-updates',               'page' => 'Home'],
        ['label' => 'Latest News & Announcements',  'url' => route('home')          . '#latest-news',               'page' => 'Home'],
        ['label' => 'Academic Programs',            'url' => route('home')          . '#academic-programs',         'page' => 'Home'],
        ['label' => 'All Programs',                 'url' => route('academics')     . '#all-programs',              'page' => 'Academics'],
        ['label' => 'Organizational Chart',         'url' => route('org-chart'),                                    'page' => 'Academics'],
        ['label' => 'Enrollment & Requirements',    'url' => route('admission'),                                    'page' => 'Admission'],
        ['label' => 'Admission Requirements',       'url' => route('admission')     . '#admission-requirements',    'page' => 'Admission'],
        ['label' => 'Enrollment Process',           'url' => route('admission')     . '#enrollment-process',        'page' => 'Admission'],
        ['label' => 'Office Hours',                 'url' => route('admission')     . '#office-hours',              'page' => 'Admission'],
        ['label' => 'News & Announcements',         'url' => route('news.index'),                                   'page' => 'News'],
        ['label' => 'Latest Posts',                 'url' => route('news.index')    . '#latest-posts',              'page' => 'News'],
        ['label' => 'Get in Touch',                 'url' => route('contact')       . '#get-in-touch',              'page' => 'Contact'],
        ['label' => 'Contact',                      'url' => route('contact'),                                      'page' => 'Contact'],
        ['label' => 'Send a Message',               'url' => route('contact')       . '#send-message',              'page' => 'Contact'],
        ['label' => 'Contact Information',          'url' => route('contact')       . '#contact-information',       'page' => 'Contact'],
        ['label' => 'Campus Map',                   'url' => route('contact')       . '#campus-map',                'page' => 'Contact'],
    ];
@endphp

<header class="sticky top-0 z-50 bg-white overflow-x-clip border-b border-gray-100 shadow-sm">

    <div class="mx-auto px-4 py-3 flex items-center justify-between gap-4 sm:relative sm:flex sm:items-center sm:justify-center sm:min-h-[56px]">

    {{-- Brand (left) --}}
    <a href="{{ route('home') }}" class="flex items-center gap-3 group min-w-0 shrink-0 sm:absolute sm:left-4" data-tpc-link>
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

    {{-- Desktop nav (centered) --}}
     <nav class="hidden sm:flex sm:absolute sm:left-1/2 sm:-translate-x-1/2 justify-center items-center gap-1" aria-label="Main navigation">
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

        {{-- Services dropdown --}}
        @if ($navServices->isNotEmpty())
            <div class="relative" x-data="{ open: false }"
                 @mouseenter="open = true" @mouseleave="open = false"
                 @keydown.escape.window="open = false">
                <button type="button" @click="open = !open"
                        class="tpc-navlink inline-flex items-center gap-1 px-3 py-2 text-sm font-semibold transition {{ $servicesActive ? 'tpc-active' : '' }}"
                        :aria-expanded="open.toString()">
                    Services
                    <svg class="h-3.5 w-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>
                <div x-cloak x-show="open"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1 scale-[0.98]"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute left-0 top-full pt-1 w-64 z-50">
                    <div class="rounded-2xl border border-gray-200 bg-white shadow-xl shadow-black/10 ring-1 ring-black/5 overflow-hidden">
                        <div class="bg-tpc-primary px-4 py-2.5">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-white/70">Our Services</p>
                        </div>
                        <div class="py-1.5">
                            @foreach ($navServices as $svc)
                                <a href="{{ route('services.show', $svc) }}"
                                   data-tpc-link data-service-href="{{ route('services.show', $svc) }}"
                                   class="group flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-tpc-primary/6 hover:text-tpc-primary transition {{ request()->routeIs('services.show') && request()->route('service')?->is($svc) ? 'bg-tpc-primary/8 text-tpc-primary font-semibold' : '' }}">
                                    <span data-service-dot class="h-1.5 w-1.5 rounded-full bg-tpc-primary/30 shrink-0 group-hover:bg-tpc-primary transition"></span>
                                    <span class="truncate">{{ $svc->title }}</span>
                                    <svg class="ml-auto h-3.5 w-3.5 shrink-0 text-gray-300 group-hover:text-tpc-primary/50 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

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
    </nav>

    {{-- Right: search (desktop) + auth + hamburger (mobile) --}}
    <div class="flex items-center gap-2 sm:absolute sm:right-4">

        {{-- ── Search bar — always visible on desktop ── --}}
        <div class="hidden sm:block relative shrink-0" x-data="tpcSearch()" @keydown.escape.window="query = ''; results = []; activeIndex = -1;">
            <div class="flex items-center gap-2 h-8 px-3 w-44 rounded-full border border-tpc-primary/30 bg-tpc-primary/5
                        focus-within:border-tpc-primary/60 focus-within:bg-white focus-within:ring-2 focus-within:ring-tpc-primary/10 transition-all duration-200">
                <svg class="h-3.5 w-3.5 shrink-0 text-tpc-primary/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M20 20l-3-3"/>
                </svg>
                <input
                    x-ref="input"
                    type="text"
                    placeholder="Search…"
                    x-model="query"
                    @input="search()"
                    @keydown.arrow-down.prevent="moveDown()"
                    @keydown.arrow-up.prevent="moveUp()"
                    @keydown.enter.prevent="selectActive()"
                    class="flex-1 bg-transparent text-xs text-gray-700 placeholder-gray-400 outline-none border-none focus:ring-0 min-w-0 p-0"
                    autocomplete="off"
                    aria-label="Search site"
                    aria-autocomplete="list"
                    :aria-activedescendant="activeIndex >= 0 ? 'tpc-sr-' + activeIndex : null"
                />
                <button x-show="query.length > 0" x-cloak type="button"
                        @click="query = ''; results = []; activeIndex = -1; $refs.input.focus()"
                        class="text-gray-300 hover:text-gray-500 transition shrink-0" aria-label="Clear search">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Results dropdown --}}
            <div x-show="results.length > 0" x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute right-0 top-full mt-2 w-64 z-50 rounded-xl border border-gray-200 bg-white shadow-lg shadow-black/5 overflow-hidden"
                 role="listbox" aria-label="Search results">
                <div class="px-3 py-2 border-b border-gray-100">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Results</p>
                </div>
                <div class="py-1 max-h-64 overflow-y-auto">
                    <template x-for="(item, i) in results" :key="i">
                        <button type="button" :id="'tpc-sr-' + i"
                                @click="go(item)" @mouseenter="activeIndex = i"
                                role="option" :aria-selected="activeIndex === i"
                                class="w-full flex items-center gap-3 px-3 py-2 text-left transition-colors group"
                                :class="activeIndex === i ? 'bg-tpc-primary/6 text-tpc-primary' : 'text-gray-600 hover:bg-gray-50'">
                            <svg class="h-3.5 w-3.5 shrink-0 text-gray-300 group-hover:text-tpc-primary/50 transition-colors"
                                 :class="activeIndex === i ? 'text-tpc-primary/50' : ''"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M20 20l-3-3"/>
                            </svg>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium truncate" x-text="item.label"></p>
                                <p class="text-[10px] text-gray-400 truncate" x-text="item.page"></p>
                            </div>
                        </button>
                    </template>
                </div>
            </div>

            {{-- No results --}}
            <div x-show="query.length > 0 && results.length === 0" x-cloak
                 class="absolute right-0 top-full mt-2 w-56 z-50 rounded-xl border border-gray-200 bg-white shadow-lg shadow-black/5">
                <div class="px-3 py-3 text-xs text-gray-400 text-center">
                    No results for "<span x-text="query" class="font-medium text-gray-600"></span>"
                </div>
            </div>
        </div>

        {{-- Admin links (desktop) --}}
        @auth
            @if($isAdmin)
                <span class="hidden sm:block mx-1 h-5 w-px bg-tpc-primary/50"></span>
                <a id="nav-messages" data-tpc-link
                   href="{{ route('admin.messages.index') }}"
                   class="hidden sm:inline-flex {{ $link($messagesActive) }} items-center gap-1.5"
                   title="Messages" @if($messagesActive) aria-current="page" @endif>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6"/>
                    </svg>
                    <span class="sr-only">Messages</span>
                </a>
                <a id="nav-admin" data-tpc-link
                   href="{{ route('admin.dashboard') }}"
                   class="hidden sm:inline-flex {{ $link($adminActive) }} items-center gap-1.5"
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

        {{-- Mobile: hamburger + search icon --}}
        <div class="flex items-center gap-2 sm:hidden">

            {{-- Hamburger --}}
            <div x-data="{ open: false }" @close-hamburger.window="open = false">

                <button
                    type="button"
                    @click="open = !open"
                    class="relative inline-flex items-center justify-center w-9 h-9 rounded-lg text-tpc-primary transition-all duration-200"
                    :class="open ? 'bg-tpc-primary text-white shadow-md' : 'bg-tpc-primary/8 hover:bg-tpc-primary/15'"
                    aria-label="Toggle menu">
                    <svg x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h16"/>
                    </svg>
                    <svg x-show="open" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div x-cloak x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-40 bg-black/30 backdrop-blur-[2px]"
                     @click="open = false" aria-hidden="true"></div>

                <div x-cloak x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2 scale-[0.98]"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 -translate-y-2 scale-[0.98]"
                     class="absolute left-0 right-0 top-full z-50 px-3 pt-2 pb-3"
                     @click.outside="open = false">

                    <nav class="rounded-2xl bg-white shadow-xl shadow-black/10 ring-1 ring-black/5 overflow-hidden">

                        <div class="bg-tpc-primary px-4 py-3 flex items-center gap-2">
                            <svg class="h-4 w-4 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span class="text-xs font-semibold text-white/80 uppercase tracking-widest">Navigation</span>
                        </div>

                        @php
                            $navItemsTop = [
                                ['id' => 'mob-home',      'label' => 'Home',      'href' => route('home'),          'active' => $homeActive,      'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
                                ['id' => 'mob-about',     'label' => 'About',     'href' => route('home').'#about', 'active' => false,            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                                ['id' => 'mob-academics', 'label' => 'Academics', 'href' => route('academics'),     'active' => $academicsActive, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>'],
                            ];
                            $navItemsBottom = [
                                ['id' => 'mob-admission', 'label' => 'Admission', 'href' => route('admission'),     'active' => $admissionActive, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>'],
                                ['id' => 'mob-news',      'label' => 'News',      'href' => route('news.index'),    'active' => $newsActive,      'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>'],
                                ['id' => 'mob-contact',   'label' => 'Contact',   'href' => route('contact'),       'active' => $contactActive,   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                            ];
                        @endphp

                        {{-- ── Search inside hamburger menu ── --}}
                        <div class="px-2 pt-2" x-data="tpcSearch()">
                            <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 h-9
                                        focus-within:border-tpc-primary/50 focus-within:bg-white focus-within:ring-2 focus-within:ring-tpc-primary/10 transition-all">
                                <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M20 20l-3-3"/>
                                </svg>
                                <input
                                    x-ref="input"
                                    type="text"
                                    placeholder="Search pages & sections…"
                                    x-model="query"
                                    @input="search()"
                                    @keydown.arrow-down.prevent="moveDown()"
                                    @keydown.arrow-up.prevent="moveUp()"
                                    @keydown.enter.prevent="selectActive()"
                                    class="flex-1 bg-transparent text-sm text-gray-700 placeholder-gray-400 outline-none border-none focus:ring-0 p-0"
                                    autocomplete="off"
                                    aria-label="Search site"
                                />
                                <button
                                    type="button"
                                    x-show="query.length > 0"
                                    x-cloak
                                    @click="close()"
                                    class="text-gray-300 hover:text-gray-500 transition shrink-0"
                                    aria-label="Clear search">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Results --}}
                            <div x-show="results.length > 0" class="mt-1 max-h-48 overflow-y-auto rounded-lg border border-gray-200 bg-white">
                                <template x-for="(item, i) in results" :key="i">
                                    <button
                                        type="button"
                                        @click="go(item)"
                                        @mouseenter="activeIndex = i"
                                        class="w-full flex items-center gap-3 px-3 py-2.5 text-left transition-colors group"
                                        :class="activeIndex === i ? 'bg-tpc-primary/6 text-tpc-primary' : 'text-gray-600 hover:bg-gray-50'">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-gray-300 group-hover:text-tpc-primary/40 transition-colors"
                                            :class="activeIndex === i ? 'text-tpc-primary/40' : ''"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M20 20l-3-3"/>
                                        </svg>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium truncate" x-text="item.label"></p>
                                            <p class="text-xs text-gray-400 truncate" x-text="item.page"></p>
                                        </div>
                                    </button>
                                </template>
                            </div>

                            {{-- No results --}}
                            <div x-show="query.length > 0 && results.length === 0" class="mt-1 px-3 py-2.5 text-xs text-gray-400 text-center rounded-lg border border-gray-200 bg-white">
                                No results for "<span x-text="query" class="font-medium text-gray-600"></span>"
                            </div>
                        </div>

                        <div class="p-2">
                            {{-- Top items: Home, About, Academics --}}
                            @foreach ($navItemsTop as $item)
                                <a id="{{ $item['id'] }}" data-tpc-link href="{{ $item['href'] }}"
                                   @click="open = false" @if($item['active']) aria-current="page" @endif
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 group
                                       {{ $item['active'] ? 'bg-tpc-primary text-white shadow-sm' : 'text-gray-700 hover:bg-tpc-primary/8 hover:text-tpc-primary' }}">
                                    <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-lg transition-all duration-150
                                        {{ $item['active'] ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-tpc-primary/12 group-hover:text-tpc-primary' }}">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
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

                            {{-- Services (mobile — expandable sub-list) --}}
                            @if ($navServices->isNotEmpty())
                                <div x-data="{ servOpen: false }" x-init="servOpen = {{ $servicesActive ? 'true' : 'false' }}">
                                    <button type="button" id="mob-services" @click="servOpen = !servOpen"
                                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 group
                                                   {{ $servicesActive ? 'bg-tpc-primary text-white shadow-sm' : 'text-gray-700 hover:bg-tpc-primary/8 hover:text-tpc-primary' }}">
                                        <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-lg transition-all duration-150
                                                     {{ $servicesActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-tpc-primary/12 group-hover:text-tpc-primary' }}">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                                            </svg>
                                        </span>
                                        <span class="flex-1 text-left">Services</span>
                                        <svg class="h-3.5 w-3.5 transition-transform duration-200" :class="servOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                        </svg>
                                    </button>

                                    <div x-show="servOpen"
                                        x-transition:enter="transition ease-out duration-150"
                                        x-transition:enter-start="opacity-0 -translate-y-1"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-100"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 -translate-y-1"
                                        class="mt-1 ml-10 space-y-0.5">
                                        @foreach ($navServices as $svc)
                                            <a href="{{ route('services.show', $svc) }}"
                                            data-tpc-link @click="open = false"
                                            data-service-href="{{ route('services.show', $svc) }}"
                                            class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-gray-600 hover:bg-tpc-primary/8 hover:text-tpc-primary transition group">
                                                <span data-service-dot
                                                    class="h-1.5 w-1.5 rounded-full bg-tpc-primary/30 group-hover:bg-tpc-primary shrink-0 transition"></span>
                                                {{ $svc->title }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Bottom items: Admission, News, Contact --}}
                            @foreach ($navItemsBottom as $item)
                                <a id="{{ $item['id'] }}" data-tpc-link href="{{ $item['href'] }}"
                                   @click="open = false" @if($item['active']) aria-current="page" @endif
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 group
                                       {{ $item['active'] ? 'bg-tpc-primary text-white shadow-sm' : 'text-gray-700 hover:bg-tpc-primary/8 hover:text-tpc-primary' }}">
                                    <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-lg transition-all duration-150
                                        {{ $item['active'] ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-tpc-primary/12 group-hover:text-tpc-primary' }}">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
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
                                <div class="mx-4 my-1 border-t border-gray-100"></div>
                                <div class="px-2 pb-2">
                                    <p class="px-3 pt-1 pb-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Admin</p>

                                    <a id="mob-messages" data-tpc-link href="{{ route('admin.messages.index') }}" @click="open=false"
                                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 group
                                           {{ $messagesActive ? 'bg-tpc-primary text-white shadow-sm' : 'text-gray-700 hover:bg-tpc-primary/8 hover:text-tpc-primary' }}">
                                        <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-lg transition-all duration-150
                                            {{ $messagesActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-tpc-primary/12 group-hover:text-tpc-primary' }}">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6"/>
                                            </svg>
                                        </span>
                                        <span class="flex-1">Messages</span>
                                    </a>

                                    <a href="{{ route('admin.dashboard') }}" data-no-pjax="true" @click="open=false"
                                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 group
                                           {{ $adminActive ? 'bg-tpc-primary text-white shadow-sm' : 'text-gray-700 hover:bg-tpc-primary/8 hover:text-tpc-primary' }}">
                                        <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-lg transition-all
                                            {{ $adminActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-tpc-primary/12 group-hover:text-tpc-primary' }}">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 1 0-16 0"/>
                                                <circle cx="12" cy="8" r="4"/>
                                            </svg>
                                        </span>
                                        <span class="flex-1">Admin Dashboard</span>
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Search data + Alpine component ────────────────────────────── --}}
    <script>
    (function () {
        var TPC_SEARCH_ITEMS = @json($searchItems);

        function tpcSearch() {
            return {
                expanded: false,
                query: '',
                results: [],
                activeIndex: -1,

                expand() {
                    this.expanded = true;
                    this.$nextTick(() => {
                        if (this.$refs.input) this.$refs.input.focus();
                    });
                },

                close() {
                    this.expanded = false;
                    this.query = '';
                    this.results = [];
                    this.activeIndex = -1;
                },

                search() {
                    var q = this.query.trim().toLowerCase();
                    this.activeIndex = -1;
                    if (!q) { this.results = []; return; }
                    this.results = TPC_SEARCH_ITEMS.filter(function (item) {
                        return item.label.toLowerCase().indexOf(q) !== -1
                            || item.page.toLowerCase().indexOf(q) !== -1;
                    });
                },

                moveDown() {
                    if (this.results.length === 0) return;
                    this.activeIndex = (this.activeIndex + 1) % this.results.length;
                },

                moveUp() {
                    if (this.results.length === 0) return;
                    this.activeIndex = (this.activeIndex - 1 + this.results.length) % this.results.length;
                },

                selectActive() {
                    var idx = this.activeIndex >= 0 ? this.activeIndex : (this.results.length === 1 ? 0 : -1);
                    if (idx >= 0) this.go(this.results[idx]);
                },

                go(item) {
                    this.close();
                    window.dispatchEvent(new CustomEvent('close-hamburger'));
                    var url = item.url;
                    var hashIdx = url.indexOf('#');

                    if (hashIdx === -1) {
                        if (typeof pjaxNavigate === 'function') pjaxNavigate(url);
                        else window.location.href = url;
                        return;
                    }

                    var base   = url.substring(0, hashIdx);
                    var anchor = url.substring(hashIdx + 1);
                    var currentBase = window.location.origin + window.location.pathname;
                    var normalise = function (s) { return s.replace(/\/$/, ''); };

                    if (normalise(currentBase) === normalise(base)) {
                        tpcScrollToAnchor(anchor);
                    } else {
                        sessionStorage.setItem('tpc_scroll_to', anchor);
                        if (typeof pjaxNavigate === 'function') pjaxNavigate(base);
                        else window.location.href = base;
                    }
                }
            };
        }

        // Make it globally available for Alpine
        window.tpcSearch = tpcSearch;

        // Smooth scroll helper — tries id first, then data-section, then heading text match
        function tpcScrollToAnchor(anchor) {
            var el = document.getElementById(anchor);

            if (!el) {
                el = document.querySelector('[data-section="' + anchor + '"]');
            }

            if (!el) {
                var words = anchor.replace(/-/g, ' ').toLowerCase();
                var headings = document.querySelectorAll('h1, h2, h3, h4, section');
                for (var i = 0; i < headings.length; i++) {
                    if (headings[i].textContent.trim().toLowerCase().indexOf(words) !== -1) {
                        el = headings[i];
                        break;
                    }
                }
            }

            if (el) {
                var headerHeight = document.querySelector('header') ? document.querySelector('header').offsetHeight : 80;
                var top = el.getBoundingClientRect().top + window.scrollY - headerHeight - 16;
                window.scrollTo({ top: top, behavior: 'smooth' });
            }
        }

        // On page load — check if we need to scroll to a stored anchor
        document.addEventListener('DOMContentLoaded', function () {
            var anchor = sessionStorage.getItem('tpc_scroll_to');
            if (anchor) {
                sessionStorage.removeItem('tpc_scroll_to');
                // Small delay to let the page render
                setTimeout(function () { tpcScrollToAnchor(anchor); }, 300);
            }
        });

        // Expose for PJAX re-runs
        window.tpcScrollToAnchor = tpcScrollToAnchor;
    })();
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const homeLinks = document.querySelectorAll('[id="nav-home"], [id="mob-home"]');
        homeLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                const isOnHome = window.location.pathname === '/'
                    || window.location.pathname === '/home'
                    || window.location.hash !== '';
                if (isOnHome) {
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    history.replaceState(null, '', window.location.pathname);
                }
            });
        });

        // ── Sync mobile search panel top to actual header height ──
        var header = document.querySelector('header');
        var panel  = document.querySelector('[data-mob-search-panel]');
        if (header && panel) panel.style.top = header.offsetHeight + 'px';
    });
    </script>
</header>

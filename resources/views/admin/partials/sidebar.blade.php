@php
    $isActive = fn ($pattern) => request()->routeIs($pattern);

    $dashActive    = $isActive('admin.dashboard');
    $progActive    = $isActive('admin.programs.*');
    $newsActive    = $isActive('admin.news-posts.*');
    $reviewActive  = $isActive('admin.news-review.*');
    $admActive     = $isActive('admin.admission.*');
    $slidesActive  = $isActive('admin.about-slides.*');
    $msgsActive    = $isActive('admin.messages.*');
    $setActive     = $isActive('admin.settings.*');
    $usersActive   = $isActive('admin.users.*');
    $servicesActive = $isActive('admin.services.*');

    $pendingCount  = auth()->check() && auth()->user()->is_super_admin
                        ? \App\Models\NewsPost::pending()->count()
                        : 0;

    $unreadMsgs    = \App\Models\ContactMessage::where('is_read', false)->count();

    // Nav item base classes
    $itemBase   = "group relative flex items-center rounded-2xl py-2.5 text-sm transition duration-200 ease-out active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-tpc-primary/20";
    $itemIdle   = "text-white/70 hover:bg-white/10 hover:text-white hover:shadow-sm";
    $itemActive = "bg-white/15 shadow-sm text-white font-semibold";

    $labelBase = "relative truncate overflow-hidden transition-all duration-200 ease-[cubic-bezier(0.22,1,0.36,1)] opacity-100 max-w-[180px] translate-x-0";

    // Icon wrapper classes per state
    $iconBase   = "relative shrink-0 grid h-9 w-9 place-items-center rounded-xl border transition-transform duration-200 group-hover:scale-105 group-active:scale-95";
    $iconIdle   = "border-white/20 bg-white/10 text-white/50 group-hover:border-white/40 group-hover:text-white";
    $iconActive = "border-white/40 bg-white/20 text-white";
@endphp

<aside
    x-cloak
    class="fixed inset-y-0 left-0 z-50 w-72 border-r border-white/10 bg-tpc-primary backdrop-blur-xl
           overflow-hidden
           transform transition-transform duration-300 ease-[cubic-bezier(0.22,1,0.36,1)]
           sm:static sm:translate-x-0 sm:h-screen sm:sticky sm:top-0 sm:z-auto
           sm:transition-[width] sm:duration-300 sm:ease-[cubic-bezier(0.22,1,0.36,1)]"
    :class="[
        mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full',
        sidebarCollapsed ? 'sm:w-20' : 'sm:w-72'
    ].join(' ')"
>
    {{-- ── Brand ──────────────────────────────────────────────────────── --}}
   <div class="relative border-b border-white/10 px-5 py-4 flex-shrink-0"
         :class="sidebarCollapsed ? 'sm:px-3' : 'sm:px-5'">

        {{-- Mobile close --}}
        <button
            type="button"
            class="absolute right-3 top-3 sm:hidden inline-flex h-9 w-9 items-center justify-center rounded-xl
                   border border-tpc-primary/15 bg-white/70 text-tpc-ink/60 shadow-sm
                   hover:bg-white hover:text-tpc-primary hover:shadow-md transition"
            @click="closeMobileSidebar()"
            aria-label="Close sidebar"
        >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <a
            href="{{ route('admin.dashboard') }}"
            @click="closeMobileSidebar()"
            class="group flex items-center gap-3"
            :class="sidebarCollapsed ? 'sm:justify-center sm:gap-0' : 'sm:justify-start sm:gap-3'"
        >
            {{-- Logo pill --}}
            <div class="relative h-11 w-11 shrink-0 rounded-2xl border border-white/20 bg-white shadow-sm
                        grid place-items-center overflow-hidden
                        transition-transform duration-200 group-hover:scale-[1.03]">
                <div class="absolute inset-0 bg-gradient-to-br from-tpc-primary/10 via-transparent to-tpc-accent/20"></div>
                <img src="{{ asset('images/TPC-Logo.png') }}" alt="TPC Logo" class="relative h-9 w-auto">
            </div>

            <div
                class="min-w-0 overflow-hidden transition-all duration-200 ease-[cubic-bezier(0.22,1,0.36,1)] opacity-100 max-w-[220px] translate-x-0"
                :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''"
            >
                <p class="text-sm font-semibold text-white leading-tight transition">
                    TPC Admin
                </p>
                <p class="text-xs text-white/50 truncate">Manage website content</p>
            </div>
        </a>
    </div>

    {{-- ── Nav (scrollable) ────────────────────────────────────────────── --}}
    <nav class="flex-1 overflow-y-auto py-4 space-y-0.5 px-3" :class="sidebarCollapsed ? 'sm:px-2' : 'sm:px-3'">

        {{-- Section: Main --}}
        <p class="px-3 pb-2 text-[10px] font-semibold tracking-widest text-white/40 uppercase
                  overflow-hidden transition-all duration-200 ease-[cubic-bezier(0.22,1,0.36,1)]
                  opacity-100 max-h-10"
           :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-h-0 sm:pb-0' : ''">
            Main
        </p>

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}" @click="closeMobileSidebar()" title="Dashboard"
           class="{{ $itemBase }} {{ $dashActive ? $itemActive : $itemIdle }} px-3 gap-3"
           :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''">
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-7 w-[3px] rounded-r-full bg-white transition-opacity duration-200 {{ $dashActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-30' }}"></span>
            <span class="{{ $iconBase }} {{ $dashActive ? $iconActive : $iconIdle }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                </svg>
            </span>
            <span class="{{ $labelBase }}" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">Dashboard</span>
        </a>

        {{-- Programs --}}
        <a href="{{ route('admin.programs.index') }}" @click="closeMobileSidebar()" title="Programs"
           class="{{ $itemBase }} {{ $progActive ? $itemActive : $itemIdle }} px-3 gap-3"
           :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''">
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-7 w-[3px] rounded-r-full bg-white transition-opacity duration-200 {{ $progActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-30' }}"></span>
            <span class="{{ $iconBase }} {{ $progActive ? $iconActive : $iconIdle }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                </svg>
            </span>
            <span class="{{ $labelBase }}" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">Programs</span>
        </a>

        {{-- News Posts --}}
        <a href="{{ route('admin.news-posts.index') }}" @click="closeMobileSidebar()" title="News Posts"
           class="{{ $itemBase }} {{ $newsActive ? $itemActive : $itemIdle }} px-3 gap-3"
           :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''">
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-7 w-[3px] rounded-r-full bg-white transition-opacity duration-200 {{ $newsActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-30' }}"></span>
            <span class="{{ $iconBase }} {{ $newsActive ? $iconActive : $iconIdle }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z"/>
                </svg>
            </span>
            <span class="{{ $labelBase }}" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">News Posts</span>
        </a>

        {{-- News Review (Super Admin only) --}}
        @if(auth()->check() && auth()->user()->is_super_admin)
        <a href="{{ route('admin.news-review.index') }}" @click="closeMobileSidebar()" title="News Review"
           class="{{ $itemBase }} {{ $reviewActive ? $itemActive : $itemIdle }} px-3 gap-3"
           :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''">
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-7 w-[3px] rounded-r-full bg-white transition-opacity duration-200 {{ $reviewActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-30' }}"></span>
            <span class="{{ $iconBase }} {{ $reviewActive ? $iconActive : $iconIdle }} overflow-visible">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                </svg>
                {{-- @if($pendingCount > 0)
                    <span class="absolute -top-1.5 -right-1.5 flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-amber-400 px-1 text-[9px] font-bold leading-none text-amber-900 ring-2 ring-white"
                          :class="sidebarCollapsed ? 'sm:flex' : 'sm:hidden'">
                        {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                    </span>
                @endif --}}
            </span>
            <span class="{{ $labelBase }} flex items-center gap-2" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">
                News Review
                @if($pendingCount > 0)
                    <span class="ml-auto shrink-0 rounded-full bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold text-amber-800 ring-1 ring-amber-200/60">
                        {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                    </span>
                @endif
            </span>
        </a>
        @endif

        {{-- Admission --}}
        <a href="{{ route('admin.admission.index') }}" @click="closeMobileSidebar()" title="Admission"
           class="{{ $itemBase }} {{ $admActive ? $itemActive : $itemIdle }} px-3 gap-3"
           :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''">
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-7 w-[3px] rounded-r-full bg-white transition-opacity duration-200 {{ $admActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-30' }}"></span>
            <span class="{{ $iconBase }} {{ $admActive ? $iconActive : $iconIdle }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                </svg>
            </span>
            <span class="{{ $labelBase }}" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">Admission</span>
        </a>

        {{-- About Slides --}}
        <a href="{{ route('admin.about-slides.index') }}" @click="closeMobileSidebar()" title="About Slides"
           class="{{ $itemBase }} {{ $slidesActive ? $itemActive : $itemIdle }} px-3 gap-3"
           :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''">
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-7 w-[3px] rounded-r-full bg-white transition-opacity duration-200 {{ $slidesActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-30' }}"></span>
            <span class="{{ $iconBase }} {{ $slidesActive ? $iconActive : $iconIdle }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                </svg>
            </span>
            <span class="{{ $labelBase }}" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">About Slides</span>
        </a>

        {{-- Services --}}
        <a href="{{ route('admin.services.index') }}" @click="closeMobileSidebar()" title="Services"
           class="{{ $itemBase }} {{ $servicesActive ? $itemActive : $itemIdle }} px-3 gap-3"
           :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''">
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-7 w-[3px] rounded-r-full bg-white transition-opacity duration-200 {{ $servicesActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-30' }}"></span>
            <span class="{{ $iconBase }} {{ $servicesActive ? $iconActive : $iconIdle }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                </svg>
            </span>
            <span class="{{ $labelBase }}" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">Services</span>
        </a>

        {{-- Messages --}}
        <a href="{{ route('admin.messages.index') }}" @click="closeMobileSidebar()" title="Messages"
           class="{{ $itemBase }} {{ $msgsActive ? $itemActive : $itemIdle }} px-3 gap-3"
           :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''">
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-7 w-[3px] rounded-r-full bg-white transition-opacity duration-200 {{ $msgsActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-30' }}"></span>
            <span class="{{ $iconBase }} {{ $msgsActive ? $iconActive : $iconIdle }} overflow-visible">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                {{-- @if($unreadMsgs > 0)
                    <span class="absolute -top-1.5 -right-1.5 flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-red-500 px-1 text-[9px] font-bold leading-none text-white ring-2 ring-white"
                          :class="sidebarCollapsed ? 'sm:flex' : 'sm:hidden'">
                        {{ $unreadMsgs > 9 ? '9+' : $unreadMsgs }}
                    </span>
                @endif --}}
            </span>
            <span class="{{ $labelBase }} flex items-center gap-2" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">
                Messages
                @if($unreadMsgs > 0)
                    <span class="ml-auto shrink-0 rounded-full bg-red-100 px-1.5 py-0.5 text-[10px] font-bold text-red-700 ring-1 ring-red-200/60">
                        {{ $unreadMsgs > 9 ? '9+' : $unreadMsgs }}
                    </span>
                @endif
            </span>
        </a>

        {{-- Divider --}}
        <div class="py-3 px-3">
            <div class="h-px bg-white/10"></div>
        </div>

        {{-- Section: System --}}
        <p class="px-3 pb-2 text-[10px] font-semibold tracking-widest text-white/40 uppercase
                  overflow-hidden transition-all duration-200 ease-[cubic-bezier(0.22,1,0.36,1)]
                  opacity-100 max-h-10"
           :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-h-0 sm:pb-0' : ''">
            System
        </p>

        {{-- Users (Super Admin only) --}}
        @if(auth()->check() && (auth()->user()->is_super_admin ?? false) && \Illuminate\Support\Facades\Route::has('admin.users.index'))
        <a href="{{ route('admin.users.index') }}" @click="closeMobileSidebar()" title="Users"
           class="{{ $itemBase }} {{ $usersActive ? $itemActive : $itemIdle }} px-3 gap-3"
           :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''">
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-7 w-[3px] rounded-r-full bg-white transition-opacity duration-200 {{ $usersActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-30' }}"></span>
            <span class="{{ $iconBase }} {{ $usersActive ? $iconActive : $iconIdle }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                </svg>
            </span>
            <span class="{{ $labelBase }}" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">Users</span>
        </a>
        @endif

        {{-- Settings --}}
        <a href="{{ route('admin.settings.edit') }}" @click="closeMobileSidebar()" title="Settings"
           class="{{ $itemBase }} {{ $setActive ? $itemActive : $itemIdle }} px-3 gap-3"
           :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''">
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-7 w-[3px] rounded-r-full bg-white transition-opacity duration-200 {{ $setActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-30' }}"></span>
            <span class="{{ $iconBase }} {{ $setActive ? $iconActive : $iconIdle }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
            </span>
            <span class="{{ $labelBase }}" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">Settings</span>
        </a>

        {{-- Divider --}}
        <div class="py-3 px-3">
            <div class="h-px bg-tpc-primary/8"></div>
        </div>

        {{-- Back to site --}}
        <a href="{{ route('home') }}" data-no-pjax="true" @click="closeMobileSidebar()" title="Back to site"
           class="{{ $itemBase }} {{ $itemIdle }} px-3 gap-3"
           :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''">
            <span class="{{ $iconBase }} {{ $iconIdle }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/>
                </svg>
            </span>
            <span class="{{ $labelBase }}" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">Back to site</span>
        </a>

    </nav>


</aside>

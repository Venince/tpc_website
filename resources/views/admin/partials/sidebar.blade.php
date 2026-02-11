@php
    $isActive = fn ($pattern) => request()->routeIs($pattern);

    $dashActive  = $isActive('admin.dashboard');
    $progActive  = $isActive('admin.programs.*');
    $newsActive  = $isActive('admin.news-posts.*');
    $setActive   = $isActive('admin.settings.*');
    $usersActive = $isActive('admin.users.*'); // ✅ added

    $itemBase   = "group relative flex items-center rounded-2xl py-2.5 text-sm transition duration-200 ease-out active:scale-[0.98]
                   focus:outline-none focus:ring-2 focus:ring-tpc-primary/20";
    $itemIdle   = "text-tpc-ink/80 hover:bg-white/70 hover:shadow-sm";
    $itemActive = "bg-white shadow-sm text-tpc-primary font-semibold";

    $labelBase = "relative truncate overflow-hidden transition-all duration-200 ease-[cubic-bezier(0.22,1,0.36,1)]
                  opacity-100 max-w-[180px] translate-x-0";
@endphp

<aside
    x-cloak
    class="fixed inset-y-0 left-0 z-50 w-72 border-r border-tpc-primary/10 bg-white/80 backdrop-blur-xl overflow-hidden
           transform transition-transform duration-300 ease-[cubic-bezier(0.22,1,0.36,1)]
           sm:static sm:translate-x-0 sm:h-screen sm:sticky sm:top-0 sm:z-auto
           sm:transition-[width] sm:duration-300 sm:ease-[cubic-bezier(0.22,1,0.36,1)]"
    :class="[
        mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full',
        sidebarCollapsed ? 'sm:w-20' : 'sm:w-72'
    ].join(' ')"
>
    {{-- Brand --}}
    <div class="relative border-b border-tpc-primary/10 px-5 py-5"
         :class="sidebarCollapsed ? 'sm:px-3' : 'sm:px-5'">

        {{-- Close button (mobile only) --}}
        <button
            type="button"
            class="absolute right-3 top-3 sm:hidden inline-flex h-10 w-10 items-center justify-center rounded-2xl
                   border border-tpc-primary/15 bg-white/70 text-tpc-primary shadow-sm
                   hover:bg-white hover:shadow-md transition"
            @click="closeMobileSidebar()"
            aria-label="Close sidebar"
            title="Close"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <a
            href="{{ route('admin.dashboard') }}"
            @click="closeMobileSidebar()"
            class="group flex items-center gap-3"
            :class="sidebarCollapsed ? 'sm:justify-center sm:gap-0' : 'sm:justify-start sm:gap-3'"
        >
            <div class="relative h-11 w-11 shrink-0 rounded-2xl border border-tpc-primary/15 bg-white shadow-sm grid place-items-center overflow-hidden
                        transition-transform duration-200 group-hover:scale-[1.03]">
                <div class="absolute inset-0 bg-gradient-to-br from-tpc-primary/10 via-transparent to-tpc-accent/25"></div>
                <img src="{{ asset('images/TPC-Logo.png') }}" alt="TPC Logo" class="relative h-9 w-auto">
            </div>

            {{-- Text: always visible on mobile, hidden on desktop-collapsed --}}
            <div
                class="min-w-0 overflow-hidden transition-all duration-200 ease-[cubic-bezier(0.22,1,0.36,1)]
                       opacity-100 max-w-[220px] translate-x-0"
                :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''"
            >
                <p class="text-sm font-semibold text-tpc-ink leading-tight group-hover:text-tpc-primary transition">
                    TPC Admin
                </p>
                <p class="text-xs text-tpc-ink/60 truncate">
                    Manage website content
                </p>
            </div>
        </a>
    </div>

    {{-- Nav --}}
    <nav class="py-4 space-y-1 px-3" :class="sidebarCollapsed ? 'sm:px-2' : 'sm:px-3'">
        <p
            class="px-3 pb-2 text-[11px] font-semibold tracking-wide text-tpc-ink/50 uppercase
                   overflow-hidden transition-all duration-200 ease-[cubic-bezier(0.22,1,0.36,1)]
                   opacity-100 max-h-10"
            :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-h-0' : ''"
        >
            Main
        </p>

        {{-- Dashboard --}}
        <a
            href="{{ route('admin.dashboard') }}"
            @click="closeMobileSidebar()"
            title="Dashboard"
            class="{{ $itemBase }} {{ $dashActive ? $itemActive : $itemIdle }} px-3 gap-3 justify-start
                   sm:px-3 sm:gap-3 sm:justify-start"
            :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''"
        >
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 rounded-full bg-tpc-primary transition-opacity duration-200 {{ $dashActive ? 'opacity-100' : 'opacity-0' }}"></span>
            <span class="absolute inset-0 rounded-2xl ring-1 transition duration-200 {{ $dashActive ? 'ring-tpc-primary/10' : 'ring-transparent group-hover:ring-tpc-primary/10' }}"></span>

            <span class="relative grid h-9 w-9 place-items-center rounded-xl border border-tpc-primary/12 bg-white/70 text-tpc-primary
                         transition-transform duration-200 group-hover:scale-105 group-active:scale-95">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5V21a1.5 1.5 0 0 1-1.5 1.5H4.5A1.5 1.5 0 0 1 3 21v-10.5Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 22.5V15a3 3.5 0 0 1 6 0v7.5"/>
                </svg>
            </span>

            <span class="{{ $labelBase }}" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">Dashboard</span>
        </a>

        {{-- Programs --}}
        <a
            href="{{ route('admin.programs.index') }}"
            @click="closeMobileSidebar()"
            title="Programs"
            class="{{ $itemBase }} {{ $progActive ? $itemActive : $itemIdle }} px-3 gap-3 justify-start
                   sm:px-3 sm:gap-3 sm:justify-start"
            :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''"
        >
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 rounded-full bg-tpc-primary transition-opacity duration-200 {{ $progActive ? 'opacity-100' : 'opacity-0' }}"></span>
            <span class="absolute inset-0 rounded-2xl ring-1 transition duration-200 {{ $progActive ? 'ring-tpc-primary/10' : 'ring-transparent group-hover:ring-tpc-primary/10' }}"></span>

            <span class="relative grid h-9 w-9 place-items-center rounded-xl border border-tpc-primary/12 bg-white/70 text-tpc-primary
                         transition-transform duration-200 group-hover:scale-105 group-active:scale-95">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10M4 18h10"/>
                </svg>
            </span>

            <span class="{{ $labelBase }}" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">Programs</span>
        </a>

        {{-- News Posts --}}
        <a
            href="{{ route('admin.news-posts.index') }}"
            @click="closeMobileSidebar()"
            title="News Posts"
            class="{{ $itemBase }} {{ $newsActive ? $itemActive : $itemIdle }} px-3 gap-3 justify-start
                   sm:px-3 sm:gap-3 sm:justify-start"
            :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''"
        >
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 rounded-full bg-tpc-primary transition-opacity duration-200 {{ $newsActive ? 'opacity-100' : 'opacity-0' }}"></span>
            <span class="absolute inset-0 rounded-2xl ring-1 transition duration-200 {{ $newsActive ? 'ring-tpc-primary/10' : 'ring-transparent group-hover:ring-tpc-primary/10' }}"></span>

            <span class="relative grid h-9 w-9 place-items-center rounded-xl border border-tpc-primary/12 bg-white/70 text-tpc-primary
                         transition-transform duration-200 group-hover:scale-105 group-active:scale-95">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 4h12v16H6z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h6M9 16h6"/>
                </svg>
            </span>

            <span class="{{ $labelBase }}" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">News Posts</span>
        </a>

        {{-- ✅ Users (Super Admin only) --}}
        @if (
            auth()->check()
            && (auth()->user()->is_super_admin ?? false)
            && \Illuminate\Support\Facades\Route::has('admin.users.index')
        )
            <a
                href="{{ route('admin.users.index') }}"
                @click="closeMobileSidebar()"
                title="Admin/Staff"
                class="{{ $itemBase }} {{ $usersActive ? $itemActive : $itemIdle }} px-3 gap-3 justify-start
                        sm:px-3 sm:gap-3 sm:justify-start"
                :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''"
            >
                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 rounded-full bg-tpc-primary transition-opacity duration-200 {{ $usersActive ? 'opacity-100' : 'opacity-0' }}"></span>
                <span class="absolute inset-0 rounded-2xl ring-1 transition duration-200 {{ $usersActive ? 'ring-tpc-primary/10' : 'ring-transparent group-hover:ring-tpc-primary/10' }}"></span>

                <span class="relative grid h-9 w-9 place-items-center rounded-xl border border-tpc-primary/12 bg-white/70 text-tpc-primary
                             transition-transform duration-200 group-hover:scale-105 group-active:scale-95">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </span>

                <span class="{{ $labelBase }}" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">Users</span>
            </a>
        @endif

        {{-- Settings --}}
        <a
            href="{{ route('admin.settings.edit') }}"
            @click="closeMobileSidebar()"
            title="Settings"
            class="{{ $itemBase }} {{ $setActive ? $itemActive : $itemIdle }} px-3 gap-3 justify-start
                   sm:px-3 sm:gap-3 sm:justify-start"
            :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''"
        >
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 rounded-full bg-tpc-primary transition-opacity duration-200 {{ $setActive ? 'opacity-100' : 'opacity-0' }}"></span>
            <span class="absolute inset-0 rounded-2xl ring-1 transition duration-200 {{ $setActive ? 'ring-tpc-primary/10' : 'ring-transparent group-hover:ring-tpc-primary/10' }}"></span>

            <span class="relative grid h-9 w-9 place-items-center rounded-xl border border-tpc-primary/12 bg-white/70 text-tpc-primary
                         transition-transform duration-200 group-hover:scale-105 group-active:scale-95">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a7.8 7.8 0 0 0 .1-2l2-1.2-2-3.4-2.3.6a8.3 8.3 0 0 0-1.7-1l-.3-2.4H11l-.3 2.4a8.3 8.3 0 0 0-1.7 1L6.7 8.4 4.7 11.8l2 1.2a7.8 7.8 0 0 0 .1 2l-2 1.2 2 3.4 2.3-.6c.5.4 1.1.8 1.7 1l.3 2.4h4l.3-2.4c.6-.2 1.2-.6 1.7-1l2.3.6 2-3.4-2-1.2Z"/>
                </svg>
            </span>

            <span class="{{ $labelBase }}" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">Settings</span>
        </a>

        {{-- Divider --}}
        <div class="pt-4">
            <div class="h-px bg-tpc-primary/10"></div>
        </div>

        <p
            class="px-3 pt-4 pb-2 text-[11px] font-semibold tracking-wide text-tpc-ink/50 uppercase
                   overflow-hidden transition-all duration-200 ease-[cubic-bezier(0.22,1,0.36,1)]
                   opacity-100 max-h-10"
            :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-h-0' : ''"
        >
            Navigation
        </p>

        {{-- Back to site --}}
        <a
            href="{{ route('home') }}"
            data-no-pjax="true"
            @click="closeMobileSidebar()"
            title="Back to site"
            class="{{ $itemBase }} {{ $itemIdle }} px-3 gap-3 justify-start
                   sm:px-3 sm:gap-3 sm:justify-start"
            :class="sidebarCollapsed ? 'sm:px-2 sm:gap-0 sm:justify-center' : ''"
        >
            <span class="absolute inset-0 rounded-2xl ring-1 ring-transparent transition duration-200 group-hover:ring-tpc-primary/10"></span>

            <span class="relative grid h-9 w-9 place-items-center rounded-xl border border-tpc-primary/12 bg-white/70 text-tpc-primary
                         transition-transform duration-200 group-hover:scale-105 group-active:scale-95">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                </svg>
            </span>

            <span class="{{ $labelBase }}" :class="sidebarCollapsed ? 'sm:opacity-0 sm:max-w-0 sm:-translate-x-2' : ''">Back to site</span>
        </a>
    </nav>
</aside>

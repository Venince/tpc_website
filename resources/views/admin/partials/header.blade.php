@php
    $pageTitle = $title ?? 'Admin';
@endphp

<header class="sticky top-0 z-30 border-b border-tpc-primary/10 bg-white/70 backdrop-blur-xl">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">

        {{-- Left: Sidebar toggle + Page title --}}
        <div class="flex items-center gap-3 min-w-0">
            <button
                type="button"
                @click="toggleSidebar()"
                class="inline-flex items-center justify-center rounded-2xl border border-tpc-primary/15 bg-white/70 p-2 text-tpc-primary shadow-sm
                       hover:bg-white hover:shadow-md transition
                       focus:outline-none focus:ring-2 focus:ring-tpc-primary/25"
                aria-label="Toggle sidebar"
                title="Toggle sidebar"
            >
                {{-- Mobile icon --}}
                <svg class="h-5 w-5 sm:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>

                {{-- Desktop: collapse/expand icon --}}
                <svg class="hidden h-5 w-5 sm:block" x-show="!sidebarCollapsed" x-cloak
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 6v12" />
                </svg>

                <svg class="hidden h-5 w-5 sm:block" x-show="sidebarCollapsed" x-cloak
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6v12" />
                </svg>
            </button>

            <h1 class="text-xl sm:text-2xl font-semibold text-tpc-ink tracking-tight truncate">
                {{ $pageTitle }}
            </h1>
        </div>

        {{-- Right --}}
        <div class="flex items-center gap-3">
            <x-tpc-account-dropdown />
        </div>
    </div>
</header>

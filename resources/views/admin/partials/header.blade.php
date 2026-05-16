@php
    $pageTitle = $title ?? 'Admin';

    // Greeting based on time of day (Philippine Time = UTC+8)
    $hour = now()->setTimezone('Asia/Manila')->hour;
    $greeting = match(true) {
        $hour < 12 => 'Good morning',
        $hour < 18 => 'Good afternoon',
        default    => 'Good evening',
    };
    $firstName = explode(' ', auth()->user()->name ?? 'Admin')[0];

    // Unread message count for notification bell
    $unreadMessages = \App\Models\ContactMessage::where('is_read', false)->count();
@endphp

<header class="sticky top-0 z-30 border-b border-tpc-primary/10 bg-white/80 backdrop-blur-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">

            {{-- Left: toggle + title --}}
            <div class="flex items-center gap-3 min-w-0">

                {{-- Sidebar toggle --}}
                <button
                    type="button"
                    @click="toggleSidebar()"
                    class="inline-flex items-center justify-center rounded-xl border border-tpc-primary/15
                           bg-white/70 p-2 text-tpc-primary shadow-sm
                           hover:bg-white hover:shadow-md transition
                           focus:outline-none focus:ring-2 focus:ring-tpc-primary/25"
                    aria-label="Toggle sidebar"
                    title="Toggle sidebar"
                >
                    {{-- Mobile hamburger --}}
                    <svg class="h-5 w-5 sm:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    {{-- Desktop: collapse --}}
                    <svg class="hidden h-5 w-5 sm:block" x-show="!sidebarCollapsed" x-cloak
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 6v12" />
                    </svg>
                    {{-- Desktop: expand --}}
                    <svg class="hidden h-5 w-5 sm:block" x-show="sidebarCollapsed" x-cloak
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6v12" />
                    </svg>
                </button>

                {{-- Page title / greeting --}}
                <div class="min-w-0">
                    <h1 class="text-lg sm:text-xl font-semibold text-tpc-ink tracking-tight truncate leading-tight">
                        {{ $pageTitle }}
                    </h1>
                    {{-- Greeting shown only on dashboard, hidden on small screens --}}
                    @if(($title ?? '') === 'Dashboard')
                        <p class="hidden sm:block text-xs text-tpc-ink/50 leading-tight mt-0.5">
                            {{ $greeting }}, {{ $firstName }} 👋
                        </p>
                    @endif
                </div>
            </div>

            {{-- Right: actions + account --}}
            <div class="flex items-center gap-2 sm:gap-3">

                {{-- Quick: New News Post --}}
                <a
                    href="{{ route('admin.news-posts.create') }}"
                    class="hidden sm:inline-flex items-center gap-1.5 rounded-xl border border-tpc-primary/20
                           bg-tpc-primary px-3 py-1.5 text-xs font-semibold text-white shadow-sm
                           hover:bg-tpc-primary/90 hover:shadow-md transition active:scale-[0.97]"
                    title="Create new post"
                >
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                    </svg>
                    New Post
                </a>

                {{-- Notification bell (messages) --}}
                <a
                    href="{{ route('admin.messages.index') }}"
                    class="relative inline-flex items-center justify-center rounded-xl border border-tpc-primary/15
                           bg-white/70 p-2 text-tpc-ink/70 shadow-sm
                           hover:bg-white hover:text-tpc-primary hover:shadow-md transition
                           focus:outline-none focus:ring-2 focus:ring-tpc-primary/25"
                    title="Messages{{ $unreadMessages > 0 ? " ($unreadMessages unread)" : '' }}"
                    aria-label="Messages{{ $unreadMessages > 0 ? ", $unreadMessages unread" : '' }}"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>

                    @if($unreadMessages > 0)
                        <span class="absolute -top-1 -right-1 flex h-4.5 min-w-[1.1rem] items-center justify-center
                                     rounded-full bg-red-500 px-1 text-[9px] font-bold leading-none text-white
                                     ring-2 ring-white animate-pulse">
                            {{ $unreadMessages > 9 ? '9+' : $unreadMessages }}
                        </span>
                    @endif
                </a>

                {{-- Account dropdown --}}
                <x-tpc-account-dropdown />
            </div>

        </div>
    </div>
</header>

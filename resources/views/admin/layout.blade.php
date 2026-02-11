<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} - TPC</title>

    <script>
    document.documentElement.classList.add('tpc-admin-init');
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="text-tpc-ink tpc-admin-bg relative isolate overflow-x-hidden">

    <div aria-hidden="true" class="pointer-events-none fixed inset-0 z-0 flex items-center justify-center">
        <img
            src="{{ asset('images/TPC-Logo.png') }}"
            alt=""
            class="w-[580px] max-w-[90vw] select-none opacity-[0.06] saturate-0 mix-blend-multiply"
            draggable="false"
        />
    </div>

<div
    class="min-h-screen flex relative z-10"
    x-data="{
        // Desktop: collapsed = icons only
        sidebarCollapsed: (localStorage.getItem('tpcSidebarCollapsed') ?? 'false') === 'true',

        // Mobile: drawer open/close
        mobileSidebarOpen: false,

        toggleSidebar() {
            const isMobile = window.matchMedia('(max-width: 639px)').matches; // Tailwind sm breakpoint
            if (isMobile) {
                this.mobileSidebarOpen = !this.mobileSidebarOpen;
                return;
            }

            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('tpcSidebarCollapsed', this.sidebarCollapsed ? 'true' : 'false');
        },

        closeMobileSidebar() {
            this.mobileSidebarOpen = false;
        }
    }"
    x-init="
        // When resizing to desktop, force-close mobile drawer
        const mq = window.matchMedia('(min-width: 640px)');
        const handler = () => { if (mq.matches) mobileSidebarOpen = false; };
        handler();
        mq.addEventListener?.('change', handler);
    "
    x-effect="document.body.classList.toggle('overflow-hidden', mobileSidebarOpen)"
    @keydown.escape.window="mobileSidebarOpen = false"
>
    {{-- Mobile overlay (must be ABOVE header, BELOW sidebar) --}}
    <div
        x-cloak
        x-show="mobileSidebarOpen"
        x-transition.opacity.duration.200ms
        class="fixed inset-0 z-40 bg-black/40 sm:hidden"
        @click="closeMobileSidebar()"
        aria-hidden="true"
    ></div>

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    {{-- Main --}}
    <div class="flex-1 min-w-0">
        {{-- Header --}}
        @include('admin.partials.header', ['title' => $title ?? null])

        {{-- Only body/content area --}}
        <main id="tpc-admin-main" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{-- Flash success --}}
            @if (session('success'))
                <div class="mb-5 rounded-2xl border border-tpc-primary/15 bg-white/80 p-4 shadow-sm backdrop-blur">
                    <p class="text-sm">
                        <span class="font-semibold text-tpc-primary">Success:</span>
                        <span class="text-tpc-ink/80">{{ session('success') }}</span>
                    </p>
                </div>
            @endif

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="mb-5 rounded-2xl border border-red-200 bg-white/80 p-4 shadow-sm backdrop-blur">
                    <div class="font-semibold text-red-700">Please fix the following:</div>
                    <ul class="mt-2 list-disc pl-5 text-sm text-red-700/90">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Content card --}}
            <div class="rounded-3xl border border-tpc-primary/10 bg-white/70 shadow-sm backdrop-blur p-4 sm:p-6">
                @hasSection('page_actions')
                    <div
                        class="sticky top-[72px] sm:top-[80px] z-30 -mx-4 sm:-mx-6 -mt-4 sm:-mt-6 mb-4
                               border-b border-tpc-primary/10 bg-white/80 backdrop-blur-xl px-4 sm:px-6 py-3"
                    >
                        @yield('page_actions')
                    </div>
                @endif

                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </div>
        </main>
    </div>
</div>
</body>
</html>

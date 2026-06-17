<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Talibon Polytechnic College')</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/TPC-Logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/TPC-Logo.png') }}">

    <link rel="preload" as="image" href="{{ asset('images/TPC-Logo.png') }}">

    <script>window._tpcStorageBase = '{{ asset('storage') }}';</script>

    <script>
        @if(request()->routeIs('home'))
        // Add class before first paint so the browser commits opacity:0 + translateY(28px)
        document.documentElement.classList.add('tpc-home-init');
        // After DOM is ready, force a reflow then remove the class to trigger the transition
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('tpc-content');
            if (el) {
                void el.offsetHeight; // force reflow — commits the start state
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        document.documentElement.classList.remove('tpc-home-init');
                    });
                });
            }
        });
        @endif
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="font-sans text-tpc-ink bg-white">

    @include('partials.nav')

    <div id="tpc-content" class="relative tpc-prose min-h-screen flex flex-col">
        <main class="flex-1">
            @yield('content')
        </main>

        @include('partials.footer')
    </div>

    {{-- Persistent gallery lightbox (Alpine store, works across PJAX) --}}
    <div x-data
        x-show="$store.gallery.isOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 backdrop-blur-sm p-4"
        @click.self="$store.gallery.close()"
        @keydown.escape.window="$store.gallery.close()"
        @keydown.arrow-left.window="$store.gallery.prev()"
        @keydown.arrow-right.window="$store.gallery.next()"
        style="display:none">

        <button @click="$store.gallery.close()" type="button"
                class="absolute top-4 right-4 z-10 h-9 w-9 rounded-full bg-white/10 hover:bg-white/20
                    text-white flex items-center justify-center transition">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="absolute top-4 left-1/2 -translate-x-1/2 z-10 bg-white/10 text-white text-xs font-bold px-3 py-1 rounded-full">
            <span x-text="$store.gallery.current + 1"></span> / <span x-text="$store.gallery.images.length"></span>
        </div>

        <button @click="$store.gallery.prev()" type="button" x-show="$store.gallery.images.length > 1"
                class="absolute left-3 sm:left-6 z-10 h-10 w-10 rounded-full bg-white/10 hover:bg-white/25
                    text-white flex items-center justify-center transition">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <img :src="$store.gallery.currentUrl"
            :alt="'Photo ' + ($store.gallery.current + 1)"
            class="max-h-[85vh] max-w-full rounded-xl object-contain shadow-2xl select-none"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

        <button @click="$store.gallery.next()" type="button" x-show="$store.gallery.images.length > 1"
                class="absolute right-3 sm:right-6 z-10 h-10 w-10 rounded-full bg-white/10 hover:bg-white/25
                    text-white flex items-center justify-center transition">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    @stack('portal')  {{-- FABs, modals, overlays that need true viewport-fixed positioning --}}

    @stack('scripts')


</body>
</html>

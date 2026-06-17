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

    @stack('portal')  {{-- FABs, modals, overlays that need true viewport-fixed positioning --}}

    @stack('scripts')
</body>
</html>

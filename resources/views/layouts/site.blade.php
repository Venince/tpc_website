<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Talibon Polytechnic College')</title>

    <link rel="preload" as="image" href="{{ asset('images/TPC-Logo.png') }}">

    <script>
        document.documentElement.classList.add('tpc-init');
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans text-tpc-ink bg-white">

    @include('partials.nav')

    <div id="tpc-content" class="relative tpc-prose">
        <main>
            @yield('content')
        </main>

        @include('partials.footer')
    </div>

    @stack('scripts')
</body>
</html>

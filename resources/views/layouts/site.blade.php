<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Talibon Polytechnic College')</title>

    {{-- Helps avoid “logo blink” even on first load --}}
    <link rel="preload" as="image" href="{{ asset('images/TPC-Logo.png') }}">

    <script>
        // initial state for first paint (we fade content in after)
        document.documentElement.classList.add('tpc-init');
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-tpc-ink tpc-site-bg overflow-x-hidden">
    {{-- Modern lively background (still white) --}}
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-[radial-gradient(900px_circle_at_12%_10%,rgba(0,128,0,0.14),transparent_55%),radial-gradient(800px_circle_at_90%_0%,rgba(16,185,129,0.12),transparent_50%),radial-gradient(900px_circle_at_50%_100%,rgba(0,128,0,0.10),transparent_60%)]"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(0,128,0,0.10)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,128,0,0.10)_1px,transparent_1px)] bg-[size:56px_56px] opacity-[0.10]"></div>
    </div>

    {{-- NAV stays fixed and DOES NOT swap --}}
    @include('partials.nav')

    {{-- Everything inside this fades + swaps (content + footer) --}}
    <div id="tpc-content" class="relative tpc-prose">
        <main>
            @yield('content')
        </main>

        @include('partials.footer')
    </div>
</body>
</html>

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

    <script>
        document.documentElement.classList.add('tpc-init');
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Page load fade: content only, nav stays ── */
        html.tpc-init #tpc-content {
            opacity: 0;
        }
        #tpc-content {
            transition: opacity .35s ease;
        }

        /* Base: hidden + pushed down */
        .tpc-anim {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity .6s cubic-bezier(.4, 0, .2, 1),
                        transform .6s cubic-bezier(.4, 0, .2, 1);
            transition-delay: var(--tpc-d, 0ms);
            will-change: opacity, transform;
        }
        .tpc-anim.tpc-l { transform: translateX(-20px); }
        .tpc-anim.tpc-r { transform: translateX(20px);  }
        .tpc-anim.tpc-f { transform: none; }
        .tpc-anim.tpc-vis {
            opacity: 1 !important;
            transform: none !important;
        }
        @media (prefers-reduced-motion: reduce) {
            .tpc-anim {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
        }

        html {
            scrollbar-gutter: stable;
            overflow-y: scroll;
        }
    </style>

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

    {{-- ─── Scroll-reveal engine ───────────────────────────
         Auto-detects sections and animates:
           • Section header rows       → fade in
           • Grid children             → staggered fade-up
           • space-y list items        → staggered fade-up (news feed, messages)
           • Aside / sidebar cards     → slide from right
           • Standalone content cards  → fade-up
         Skips: video hero, coloured page-header sections
    ──────────────────────────────────────────────────────── --}}
    <script>
    (function () {
        'use strict';

        /* ── Reduced-motion bail-out ─────────────────────── */
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        /* ── State ───────────────────────────────────────── */
        var seen = typeof WeakSet !== 'undefined' ? new WeakSet() : null;

        /* ── IntersectionObserver ────────────────────────── */
        var io = window._tpcScrollIO || (window._tpcScrollIO = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                var el = entry.target;
                el.classList.add('tpc-vis');
                io.unobserve(el);
                /* Clean up after the transition so normal hover effects work */
                setTimeout(function () {
                    el.classList.remove('tpc-anim', 'tpc-vis', 'tpc-l', 'tpc-r', 'tpc-f');
                    el.style.removeProperty('--tpc-d');
                }, 800);
            });
        }, { threshold: 0.07, rootMargin: '0px 0px -28px 0px' }));

        /* ── Register an element ─────────────────────────── */
        function add(el, delay, dir) {
            if (!el) return;
            if (seen) {
                if (seen.has(el)) return;
                seen.add(el);
            } else {
                if (el.__tpcSeen) return;
                el.__tpcSeen = true;
            }
            el.classList.add('tpc-anim');
            if (dir)   el.classList.add('tpc-' + dir);
            if (delay) el.style.setProperty('--tpc-d', delay + 'ms');
            io.observe(el);
        }

        /* ── Should we skip this element? ───────────────── */
        function inHero(el) {
            var sec = el.closest('section');
            if (!sec) return true;
            /* Video hero (home masthead) */
            if (sec.querySelector('video')) return true;
            /* Coloured page-header sections (have an h1 AND no content grid) */
            var cl = sec.classList;
            if ((cl.contains('bg-tpc-secondary') || cl.contains('bg-tpc-primary')) &&
                sec.querySelector('h1')) return true;
            return false;
        }

        /* ── Main boot ───────────────────────────────────── */
        function boot() {
            var root = document.getElementById('tpc-content');
            if (!root) return;

            /* ── 1. Grid children — staggered fade-up ───── */
            root.querySelectorAll('.grid').forEach(function (grid) {
                if (inHero(grid)) return;
                var kids  = Array.from(grid.children);
                var count = kids.length;
                /*
                 * Two-column main+sidebar layouts get a left/right slide instead.
                 * Detect: grid has lg:grid-cols-3 or lg:grid-cols-2 AND only 2 direct kids.
                 */
                var is2col = count === 2 &&
                    (grid.className.indexOf('lg:grid-cols-3') !== -1 ||
                     grid.className.indexOf('lg:grid-cols-2') !== -1);

                kids.forEach(function (child, i) {
                    if (is2col) {
                        add(child, 0,  i === 0 ? 'l' : 'r');
                    } else {
                        add(child, Math.min(i * 80, 400));
                    }
                });
            });

            /* ── 2. Feed / list items (space-y containers) ─
               Covers: news articles, message cards, process steps
            ─────────────────────────────────────────────── */
            root.querySelectorAll('.space-y-4 > *, .space-y-5 > *, .space-y-6 > *').forEach(function (el, i) {
                if (inHero(el)) return;
                add(el, Math.min(i * 65, 390));
            });

            /* ── 3. Aside / sidebar cards — slide from right */
            root.querySelectorAll('aside > *').forEach(function (el, i) {
                add(el, i * 90, 'r');
            });

            /* ── 4. Section title rows — fade only ───────── */
            root.querySelectorAll(
                'section .flex.items-center.gap-4,' +
                'section .flex.items-end.justify-between.gap-4'
            ).forEach(function (el) {
                if (inHero(el)) return;
                if (!el.querySelector('h2, h3, [class*="tracking-widest"]')) return;
                add(el, 0, 'f');
            });

            /* ── 5. Standalone content cards ─────────────── */
            root.querySelectorAll(
                '.bg-white.rounded-2xl,' +
                '.bg-white.rounded-3xl'
            ).forEach(function (el) {
                if (inHero(el)) return;
                add(el, 0);
            });
        }

        /* Expose so PJAX can re-run after each swap */
        window.tpcScrollRevealBoot = boot;

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', boot);
        } else {
            boot();
        }
    })();
    </script>

    @stack('scripts')
</body>
</html>

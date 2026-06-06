<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Talibon Polytechnic College')</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/TPC-Logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/TPC-Logo.png') }}">

    <link rel="preload" as="image" href="{{ asset('images/TPC-Logo.png') }}">

    <script>
        document.documentElement.classList.add('tpc-init');
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ─── Scroll-reveal animations ─────────────────────── --}}
    <style>
        /* Base: hidden + pushed down */
        .tpc-anim {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity .6s cubic-bezier(.4, 0, .2, 1),
                        transform .6s cubic-bezier(.4, 0, .2, 1);
            transition-delay: var(--tpc-d, 0ms);
            will-change: opacity, transform;
        }
        /* Direction variants — applied alongside .tpc-anim */
        .tpc-anim.tpc-l { transform: translateX(-20px); }
        .tpc-anim.tpc-r { transform: translateX(20px);  }
        .tpc-anim.tpc-f { transform: none; }             /* fade only, no slide */
        /* Revealed */
        .tpc-anim.tpc-vis {
            opacity: 1 !important;
            transform: none !important;
        }
        /* Honour system-level preference */
        @media (prefers-reduced-motion: reduce) {
            .tpc-anim {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
        }

        /* ─── Prevent scrollbar-induced layout shift on page load ── */
        html {
            scrollbar-gutter: stable;          /* modern: reserves gutter without forcing scrollbar */
            overflow-y: scroll;                /* fallback for older browsers */
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
        var io = new IntersectionObserver(function (entries) {
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
        }, { threshold: 0.07, rootMargin: '0px 0px -28px 0px' });

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

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', boot);
        } else {
            boot();
        }
    })();
    </script>

    @stack('scripts')

    <script>
    function handleLike(btn) {
        const postId    = btn.dataset.postId;
        const storageKey = 'tpc_liked_' + postId;
        const countEl   = btn.querySelector('.like-count');
        const iconEl    = btn.querySelector('.like-icon');
        const isLiked   = !!localStorage.getItem(storageKey);
        const current   = parseInt(countEl.textContent);

        if (isLiked) {
            // ── Unlike ──
            countEl.textContent = Math.max(0, current - 1);
            iconEl.setAttribute('fill', 'none');
            iconEl.setAttribute('stroke', 'currentColor');
            iconEl.style.color = '';
            btn.classList.remove('text-tpc-primary', 'border-tpc-primary', 'bg-tpc-primary/10');
            btn.classList.add('text-gray-500', 'border-gray-200');
            localStorage.removeItem(storageKey);

            fetch('{{ url('/news') }}/' + postId + '/unlike', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            }).catch(() => {
                // Rollback
                countEl.textContent = current;
                iconEl.setAttribute('fill', 'currentColor');
                btn.classList.add('text-tpc-primary', 'border-tpc-primary', 'bg-tpc-primary/10');
                btn.classList.remove('text-gray-500', 'border-gray-200');
                localStorage.setItem(storageKey, '1');
            });

        } else {
            // ── Like ──
            countEl.textContent = current + 1;
            iconEl.setAttribute('fill', 'currentColor');
            iconEl.setAttribute('stroke', 'currentColor');
            btn.classList.add('text-tpc-primary', 'border-tpc-primary', 'bg-tpc-primary/10');
            btn.classList.remove('text-gray-500', 'border-gray-200');
            localStorage.setItem(storageKey, '1');

            fetch('{{ url('/news') }}/' + postId + '/like', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            }).catch(() => {
                // Rollback
                countEl.textContent = current;
                iconEl.setAttribute('fill', 'none');
                btn.classList.remove('text-tpc-primary', 'border-tpc-primary', 'bg-tpc-primary/10');
                btn.classList.add('text-gray-500', 'border-gray-200');
                localStorage.removeItem(storageKey);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.like-btn').forEach(function (btn) {
            const postId = btn.dataset.postId;
            if (localStorage.getItem('tpc_liked_' + postId)) {
                const iconEl = btn.querySelector('.like-icon');
                iconEl.setAttribute('fill', 'currentColor');
                iconEl.setAttribute('stroke', 'currentColor');
                btn.classList.add('text-tpc-primary', 'border-tpc-primary', 'bg-tpc-primary/10');
                btn.classList.remove('text-gray-500', 'border-gray-200');
            }
        });
    });
    </script>

</body>
</html>

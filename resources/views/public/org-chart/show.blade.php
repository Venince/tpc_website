@extends('layouts.site')

@section('title', 'Organizational Chart – Talibon Polytechnic College')

@section('content')

{{-- ── Hero ──────────────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden bg-tpc-secondary">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0"
         style="background: radial-gradient(ellipse at 70% 50%, rgba(255,255,255,0.06) 0%, transparent 60%),
                            radial-gradient(ellipse at 20% 80%, rgba(0,0,0,0.15) 0%, transparent 50%)"></div>

    <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-[0.04]"
         style="background-image: linear-gradient(rgba(255,255,255,0.8) 1px, transparent 1px),
                                  linear-gradient(90deg, rgba(255,255,255,0.8) 1px, transparent 1px);
                background-size: 40px 40px;"></div>

    <div class="relative mx-auto max-w-5xl px-4 py-14 sm:py-20">
        <div class="flex flex-col items-center text-center">
            <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-5xl">
                Organizational Chart
            </h1>
            <p class="mt-4 max-w-lg text-sm text-white/60 leading-relaxed">
                The administration and academic leadership of Talibon Polytechnic College.
            </p>
        </div>
    </div>

    {{-- Wave --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-8 sm:h-12">
            <path d="M0 48 C480 0 960 0 1440 48 L1440 48 L0 48 Z" fill="#f9fafb"/>
        </svg>
    </div>
</section>

{{-- ── Tree View ────────────────────────────────────────────────────────── --}}
<section class="bg-gray-50 py-14 px-4 min-h-[500px]">
    <div class="org-tree-scroll-wrap mx-auto max-w-7xl overflow-x-auto pb-8">
        @if ($tree->isEmpty())
            <div class="py-24 text-center text-gray-400">
                <div class="mx-auto mb-4 h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center">
                    <svg class="h-8 w-8 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium">No organizational chart data yet.</p>
            </div>
        @else
            <div class="org-tree">
                @include('public.org-chart._tree_nodes', ['nodes' => $tree, 'depth' => 0])
            </div>
        @endif
    </div>
</section>

{{--
    Script is placed HERE, inside @section('content'), so it lives inside
    #tpc-content and is re-executed by the PJAX engine on every navigation.
    Scripts in @push('scripts') are outside #tpc-content and are ignored by PJAX.
--}}
<script>
(function () {

    function orgDrawConnectors() {
        document.querySelectorAll('.org-hbar').forEach(function(el) { el.remove(); });

        document.querySelectorAll('.org-siblings').forEach(function(group) {
            var cols = Array.from(group.querySelectorAll(':scope > .org-col'));
            if (cols.length < 2) return;

            var grpRect = group.getBoundingClientRect();

            function getCenter(col) {
                var vline = col.querySelector('.org-col-vline-top');
                var card  = col.querySelector('.org-card');
                var el    = (vline && getComputedStyle(vline).display !== 'none') ? vline : card;
                var r     = el.getBoundingClientRect();
                return r.left + r.width / 2 - grpRect.left;
            }

            var leftPx  = getCenter(cols[0]);
            var rightPx = getCenter(cols[cols.length - 1]);
            if (rightPx <= leftPx) return;

            var bar = document.createElement('div');
            bar.className   = 'org-hbar';
            bar.style.left  = leftPx + 'px';
            bar.style.width = (rightPx - leftPx) + 'px';
            group.style.position = 'relative';
            group.appendChild(bar);
        });
    }

    function orgInitConnectors() {
        var tree = document.querySelector('.org-tree');
        if (!tree) return;

        tree.classList.remove('is-ready');

        var images  = Array.from(tree.querySelectorAll('img'));
        var pending = images.filter(function(img) { return !img.complete; });

        function run() {
            requestAnimationFrame(function() {
                requestAnimationFrame(function() {
                    requestAnimationFrame(function() {
                        orgDrawConnectors();
                        tree.classList.add('is-ready');
                    });
                });
            });
        }

        if (pending.length === 0) {
            run();
            return;
        }

        var loaded = 0;
        function onLoad() {
            loaded++;
            if (loaded >= pending.length) run();
        }

        pending.forEach(function(img) {
            img.addEventListener('load',  onLoad);
            img.addEventListener('error', onLoad);
        });

        setTimeout(function() {
            if (!tree.classList.contains('is-ready')) {
                orgDrawConnectors();
                tree.classList.add('is-ready');
            }
        }, 2000);
    }

    window.initOrgChart = orgInitConnectors;

    if (!window._orgResizeRegistered) {
        window._orgResizeRegistered = true;
        window.addEventListener('resize', orgDrawConnectors);
    }

    orgInitConnectors();

    window.addEventListener('pageshow', orgInitConnectors);

})();
</script>

@endsection

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

@php
    // How many people sit below a node (used for the expand/collapse counters)
    $countNodes = function ($nodes) use (&$countNodes) {
        return $nodes->sum(fn ($n) => 1 + $countNodes($n->allChildren));
    };

    // Unique people in the whole chart (a person with several managers appears once here)
    $ids = [];
    $collect = function ($nodes) use (&$collect, &$ids) {
        foreach ($nodes as $n) { $ids[$n->id] = true; $collect($n->allChildren); }
    };
    $collect($tree);
    $totalPeople = count($ids);

    // Big charts start with the deeper branches collapsed so the first view stays readable.
    $collapseFrom = $totalPeople > 45 ? 2 : ($totalPeople > 24 ? 3 : 99);
@endphp

{{-- ── Chart ────────────────────────────────────────────────────────────── --}}
<section class="bg-gray-50 px-4 py-10 sm:py-14 min-h-[500px]">
    <div class="mx-auto max-w-screen-2xl">
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
            <div class="oc" data-oc>

                {{-- Toolbar --}}
                <div class="oc-toolbar" role="region" aria-label="Chart controls">
                    <div class="oc-search">
                        <label for="oc-search-input" class="sr-only">Search people or offices</label>
                        <svg viewBox="0 0 20 20" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><circle cx="9" cy="9" r="5.5"/><path d="m13.5 13.5 3.5 3.5"/></svg>
                        <input id="oc-search-input" type="search" placeholder="Search a person, title or office" autocomplete="off" data-oc-search>
                    </div>

                    <div class="oc-actions">
                        <button type="button" class="oc-btn" data-oc-expand>Expand all</button>
                        <button type="button" class="oc-btn" data-oc-collapse>Collapse all</button>
                    </div>

                    <div class="oc-zoom" role="group" aria-label="Zoom">
                        <button type="button" class="oc-btn oc-btn-icon" data-oc-zoom="out" aria-label="Zoom out">−</button>
                        <span class="oc-zoom-label" data-oc-zoom-label aria-live="polite">100%</span>
                        <button type="button" class="oc-btn oc-btn-icon" data-oc-zoom="in" aria-label="Zoom in">+</button>
                        <button type="button" class="oc-btn" data-oc-zoom="fit">Fit to screen</button>
                    </div>

                    <p class="oc-meta">
                        <span>{{ $totalPeople }} {{ $totalPeople === 1 ? 'person' : 'people' }}</span>
                        <span class="oc-status" data-oc-status role="status" aria-live="polite"></span>
                    </p>
                </div>

                <div class="oc-viewport" data-oc-viewport>
                    <div class="oc-sizer" data-oc-sizer>
                        <div class="oc-canvas" data-oc-canvas>
                            <div class="oc-tree">
                                @include('public.org-chart._tree_nodes', [
                                    'nodes'        => $tree,
                                    'depth'        => 0,
                                    'collapseFrom' => $collapseFrom,
                                    'countNodes'   => $countNodes,
                                ])
                            </div>
                        </div>
                    </div>
                </div>

                <p class="oc-hint">Drag to move around. Hold Ctrl and scroll to zoom.</p>
            </div>
        @endif
    </div>
</section>

{{--
    Styles and script live INSIDE @section('content') so the PJAX engine re-applies them on every
    navigation (anything pushed outside #tpc-content is ignored by PJAX).
--}}
<style>
/* Formal, neutral chart. Every card looks the same; colour appears only for focus and search states. */
.oc{
    --oc-line:#9CA3AF; --oc-lw:1px; --oc-g:24px;
    --oc-ink:#111827; --oc-muted:#4B5563; --oc-faint:#6B7280;
    --oc-border:#D1D5DB; --oc-brand:#006400;
    color:var(--oc-ink);
}

/* ── Toolbar ───────────────────────────────────────────────────────────── */
.oc-toolbar{display:flex;flex-wrap:wrap;align-items:center;gap:.5rem;margin-bottom:1rem}
.oc-search{position:relative;flex:1 1 100%;max-width:none}
.oc-search svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--oc-faint);pointer-events:none}
.oc-search input{width:100%;height:44px;padding:0 12px 0 38px;border:1px solid var(--oc-border);border-radius:6px;background:#fff;font-size:1rem;color:var(--oc-ink)}
.oc-search input::placeholder{color:var(--oc-faint)}
.oc-search input:focus{outline:2px solid var(--oc-brand);outline-offset:1px;border-color:var(--oc-brand)}
.oc-btn{display:inline-flex;align-items:center;justify-content:center;height:44px;padding:0 1rem;border:1px solid var(--oc-border);border-radius:6px;background:#fff;color:var(--oc-ink);font-size:.875rem;font-weight:500;cursor:pointer}
.oc-btn:hover{background:#F3F4F6}
.oc-btn:focus-visible,.oc-toggle:focus-visible{outline:2px solid var(--oc-brand);outline-offset:2px}
.oc-btn-icon{width:38px;padding:0;font-size:1.125rem;line-height:1}
.oc-actions{display:grid;grid-template-columns:1fr 1fr;gap:.5rem;flex:1 1 100%}
.oc-zoom{display:none;align-items:center;gap:.375rem}
.oc-zoom-label{min-width:3rem;text-align:center;font-size:.8125rem;color:var(--oc-muted);font-variant-numeric:tabular-nums}
.oc-meta{display:flex;align-items:center;gap:.75rem;margin:0;font-size:.8125rem;color:var(--oc-muted)}
.oc-status{color:var(--oc-ink);font-weight:600}
.oc-hint{display:none;margin:.75rem 0 0;font-size:.8125rem;color:var(--oc-faint)}

/* ── Shared ────────────────────────────────────────────────────────────── */
.oc-viewport{position:relative}
.oc-sizer{margin:0 auto}
.oc-canvas{transform-origin:0 0}
.oc-children{list-style:none;margin:0;padding:0}
.oc-node{list-style:none;margin:0}
.oc-trunk,.oc-rowline{display:none}

.oc-card{
    position:relative;display:flex;align-items:center;gap:12px;
    background:#fff;border:1px solid var(--oc-border);border-radius:6px;
    padding:12px 14px;margin-bottom:8px;
}
.oc-photo{display:block;flex-shrink:0;width:44px;height:44px;border-radius:50%;object-fit:cover;object-position:top center;background:#E5E7EB}
.oc-text{min-width:0;flex:1}
.oc-name{margin:0;font-size:.9375rem;font-weight:600;line-height:1.3;overflow-wrap:anywhere}
.oc-title{margin:2px 0 0;font-size:.8125rem;line-height:1.4;color:var(--oc-muted);overflow-wrap:anywhere}
.oc-dept{display:block;margin-top:2px;font-size:.75rem;line-height:1.4;color:var(--oc-faint);overflow-wrap:anywhere}

.oc-card{flex-wrap:wrap}
.oc-toggle{
    flex:1 1 100%;width:100%;display:flex;align-items:center;justify-content:space-between;gap:6px;min-height:44px;padding:0 12px;margin:8px 0 0;
    border:1px solid var(--oc-border);border-radius:6px;background:#fff;color:#374151;
    font-size:.875rem;font-weight:600;cursor:pointer;
}
.oc-node.is-collapsed>.oc-card .oc-when-open,
.oc-node:not(.is-collapsed)>.oc-card .oc-when-collapsed{display:none}
.oc-toggle:hover{background:#F3F4F6}
.oc-toggle svg{transition:transform .15s}
.oc-node:not(.is-collapsed)>.oc-card .oc-toggle svg{transform:rotate(180deg)}
.oc-count-label{font-weight:500;color:var(--oc-muted)}
.oc-node.is-collapsed>.oc-branch{display:none}

/* Search: plain highlight, everything else recedes */
.oc-tree.has-search .oc-card{opacity:.4}
.oc-tree.has-search .oc-card.is-match{opacity:1;background:#FEF9C3;border-color:#A16207;color:var(--oc-ink)}
.oc-tree.has-search .oc-card.is-match .oc-title,.oc-tree.has-search .oc-card.is-match .oc-dept{color:#374151}

/* ── Phones & tablets (< 1024px): indented list ────────────────────────── */
.oc-branch{margin:0 0 8px 12px;padding:8px 0 0 12px;border-left:var(--oc-lw) solid var(--oc-line)}
.oc-children + .oc-children{margin-top:0}
.oc-team{display:grid;grid-template-columns:1fr;gap:8px}
.oc-team .oc-card{margin:0}
.oc-node>.oc-card:last-child{margin-bottom:8px}
@media (min-width:640px){
    .oc-search{flex:0 1 380px}
    .oc-actions{display:flex;flex:0 0 auto}
    .oc-team{grid-template-columns:repeat(2,minmax(0,1fr))}
    .oc-meta{margin-left:auto}
}

/* ── Desktop (≥ 1024px): tree canvas ───────────────────────────────────── */
@media (min-width:1024px){
    .oc-search input,.oc-btn{height:38px;font-size:.875rem}
    .oc-btn-icon{width:38px}
    .oc-zoom{display:inline-flex}
    .oc-hint{display:block}

    .oc-viewport{
        overflow:auto;max-height:78vh;padding:32px 24px 44px;
        background:#fff;border:1px solid #E5E7EB;border-radius:8px;
        scrollbar-width:thin;scrollbar-color:#9CA3AF transparent;
    }
    .oc-viewport.is-pannable{cursor:grab}
    .oc-viewport.is-dragging{cursor:grabbing;user-select:none}
    .oc-canvas{width:max-content}

    .oc-children{display:flex;justify-content:center;align-items:flex-start}
    .oc-node{position:relative;display:flex;flex-direction:column;align-items:center;padding:var(--oc-g) 12px 0}
    .oc-roots>.oc-node{padding-top:0}

    .oc-card{z-index:1;flex-direction:column;justify-content:flex-start;gap:0;width:216px;margin:0;padding:16px 14px 22px;text-align:center}
    .oc-photo{width:52px;height:52px;margin:0 auto 10px}
    .oc-text{flex:none;width:100%}
    .oc-card{flex-wrap:nowrap}
    .oc-toggle{position:absolute;left:50%;bottom:-14px;transform:translateX(-50%);z-index:3;flex:none;width:auto;display:inline-flex;justify-content:center;height:28px;min-height:0;margin:0;padding:0 9px;font-size:.75rem}
    .oc-when-collapsed,.oc-when-open,.oc-count-label{display:none!important}

    /* Connectors: 1px neutral lines, square corners, pure CSS */
    .oc-branch{position:relative;display:flex;flex-direction:column;align-items:center;margin:0;padding:var(--oc-g) 0 0;border:0}
    .oc-branch::before{content:'';position:absolute;top:0;left:50%;height:var(--oc-g);border-left:var(--oc-lw) solid var(--oc-line)}
    .oc-node::before,.oc-node::after{content:'';position:absolute;top:0;width:50%;height:var(--oc-g);border-top:var(--oc-lw) solid var(--oc-line)}
    .oc-node::before{right:50%}
    .oc-node::after{left:50%;border-left:var(--oc-lw) solid var(--oc-line)}
    .oc-node:only-child::before{display:none}
    .oc-node:only-child::after{border-top:0}
    .oc-node:first-child::before{border:0 none}
    .oc-node:last-child::after{border:0 none}
    .oc-node:last-child::before{right:calc(50% - var(--oc-lw));border-right:var(--oc-lw) solid var(--oc-line)}
    .oc-roots>.oc-node::before,.oc-roots>.oc-node::after{display:none}

    /* Children split over several rows: one trunk (drawn by the script) links the manager to the last row */
    .oc-trunk{display:block;position:absolute;top:0;left:50%;width:0;border-left:var(--oc-lw) solid var(--oc-line);z-index:0}
    .oc-children + .oc-children{padding-top:calc(var(--oc-g) + 16px)}
    .oc-children[data-reports-to]{position:relative}
    .oc-children[data-reports-to]::before{
        content:'Reports to ' attr(data-reports-to);position:absolute;top:17px;left:50%;transform:translateX(-50%);z-index:2;
        max-width:260px;padding:0 8px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
        background:#fff;font-size:11px;line-height:18px;color:var(--oc-faint)
    }

    /* People with no reports sit together in one block instead of a wide row */
    .oc-team{display:grid;grid-template-columns:repeat(var(--oc-cols,2),208px);gap:8px;padding:10px;background:#F3F4F6;border-radius:6px}
    .oc-team>.oc-node{display:block;padding:0}
    .oc-team>.oc-node::before,.oc-team>.oc-node::after{display:none}
    .oc-team .oc-card{width:208px;flex-direction:row;align-items:center;gap:10px;padding:10px 12px;text-align:left}
    .oc-team .oc-photo{width:36px;height:36px;margin:0}
    .oc-team .oc-text{flex:1;min-width:0;width:auto}
}
@media print{
    .oc-toolbar,.oc-hint{display:none}
    .oc-canvas{transform:none!important}
    .oc-viewport{overflow:visible;max-height:none}
}
@media (prefers-reduced-motion:reduce){ .oc-toggle svg{transition:none} }
</style>

<script>
(function () {
    var MIN = 0.35, FIT_MIN = 0.55, MAX = 1.6, STEP = 0.1;

    function init() {
        var root = document.querySelector('[data-oc]');
        if (!root || root.dataset.ocReady) return;
        root.dataset.ocReady = '1';

        var viewport = root.querySelector('[data-oc-viewport]');
        var sizer    = root.querySelector('[data-oc-sizer]');
        var canvas   = root.querySelector('[data-oc-canvas]');
        var tree     = root.querySelector('.oc-tree');
        var label    = root.querySelector('[data-oc-zoom-label]');
        var status   = root.querySelector('[data-oc-status]');
        var mq       = window.matchMedia('(min-width: 1024px)');
        var scale = 1, fit = true;

        /* When a manager's people are split over several rows, draw one trunk line from the manager
           down to the last row so it is obvious those rows belong to that manager. */
        function drawTrunks() {
            tree.querySelectorAll('.oc-branch.has-rows').forEach(function (branch) {
                var trunk = branch.querySelector(':scope > .oc-trunk');
                if (!trunk) {
                    trunk = document.createElement('div');
                    trunk.className = 'oc-trunk';
                    trunk.setAttribute('aria-hidden', 'true');
                    branch.insertBefore(trunk, branch.firstChild);
                }
                var rows = branch.querySelectorAll(':scope > .oc-children');
                var last = rows[rows.length - 1];
                trunk.style.height = (last && mq.matches)
                    ? (last.offsetTop + parseFloat(getComputedStyle(last).paddingTop || 0)) + 'px'   // down to the first connector of the last row
                    : '0';
            });
        }

        /* ── Fit / zoom (desktop canvas only) ── */
        function apply() {
            if (!mq.matches) {
                canvas.style.transform = '';
                sizer.style.width = '';
                sizer.style.height = '';
                return;
            }
            drawTrunks();
            var w = canvas.offsetWidth, h = canvas.offsetHeight;      // layout size, unaffected by transform
            if (fit) {
                var avail = viewport.clientWidth - 32;
                scale = Math.max(FIT_MIN, Math.min(1, avail / w));
            }
            canvas.style.transform = 'scale(' + scale + ')';
            sizer.style.width  = Math.ceil(w * scale) + 'px';
            sizer.style.height = Math.ceil(h * scale) + 'px';
            if (label) label.textContent = Math.round(scale * 100) + '%';
            viewport.classList.toggle('is-pannable',
                sizer.offsetWidth > viewport.clientWidth - 32 || sizer.offsetHeight > viewport.clientHeight - 64);
            // A chart wider than the window opens centred on the top of the hierarchy, not on its left edge
            if (fit) viewport.scrollLeft = Math.max(0, (viewport.scrollWidth - viewport.clientWidth) / 2);
        }

        function setScale(next) {
            next = Math.max(MIN, Math.min(MAX, next));
            if (Math.abs(next - scale) < 0.001) return;
            var cx = (viewport.scrollLeft + viewport.clientWidth / 2) / scale;
            var cy = (viewport.scrollTop + viewport.clientHeight / 2) / scale;
            fit = false;
            scale = next;
            apply();
            viewport.scrollLeft = cx * scale - viewport.clientWidth / 2;
            viewport.scrollTop  = cy * scale - viewport.clientHeight / 2;
        }

        root.querySelectorAll('[data-oc-zoom]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var a = btn.getAttribute('data-oc-zoom');
                if (a === 'in')  setScale(scale + STEP);
                if (a === 'out') setScale(scale - STEP);
                if (a === 'fit') { fit = true; apply(); viewport.scrollLeft = 0; viewport.scrollTop = 0; }
            });
        });

        viewport.addEventListener('wheel', function (e) {
            if (!mq.matches || !(e.ctrlKey || e.metaKey)) return;
            e.preventDefault();
            setScale(scale + (e.deltaY < 0 ? STEP : -STEP));
        }, { passive: false });

        /* Click-and-drag panning with a mouse (touch devices scroll natively) */
        var drag = null;
        viewport.addEventListener('pointerdown', function (e) {
            if (!mq.matches || e.pointerType !== 'mouse' || e.button !== 0) return;
            if (e.target.closest('button, a, input') || !viewport.classList.contains('is-pannable')) return;
            drag = { x: e.clientX, y: e.clientY, l: viewport.scrollLeft, t: viewport.scrollTop };
            viewport.classList.add('is-dragging');
            viewport.setPointerCapture(e.pointerId);
        });
        viewport.addEventListener('pointermove', function (e) {
            if (!drag) return;
            viewport.scrollLeft = drag.l - (e.clientX - drag.x);
            viewport.scrollTop  = drag.t - (e.clientY - drag.y);
        });
        function endDrag() { drag = null; viewport.classList.remove('is-dragging'); }
        viewport.addEventListener('pointerup', endDrag);
        viewport.addEventListener('pointercancel', endDrag);

        /* ── Expand / collapse ── */
        function setCollapsed(node, collapsed) {
            node.classList.toggle('is-collapsed', collapsed);
            var btn = node.querySelector(':scope > .oc-card .oc-toggle');
            if (!btn) return;
            var n = parseInt(btn.dataset.count, 10) || 0;
            btn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
            btn.setAttribute('aria-label', (collapsed ? 'Show ' : 'Hide ') + n + (n === 1 ? ' person' : ' people') + ' below ' + btn.dataset.name);
        }

        root.addEventListener('click', function (e) {
            var btn = e.target.closest('.oc-toggle');
            if (!btn) return;
            var node = btn.closest('.oc-node');
            setCollapsed(node, !node.classList.contains('is-collapsed'));
            apply();
        });

        root.querySelector('[data-oc-expand]').addEventListener('click', function () {
            tree.querySelectorAll('.oc-node.has-kids').forEach(function (n) { setCollapsed(n, false); });
            apply();
        });
        root.querySelector('[data-oc-collapse]').addEventListener('click', function () {
            tree.querySelectorAll('.oc-node.has-kids').forEach(function (n) {
                setCollapsed(n, parseInt(n.dataset.depth, 10) >= 1);   // keep the first level visible
            });
            apply();
        });

        /* ── Search: highlight matches and open the branches that contain them ── */
        var input = root.querySelector('[data-oc-search]');
        var timer = null;

        function runSearch() {
            var term = input.value.trim().toLowerCase();
            tree.querySelectorAll('.oc-card.is-match').forEach(function (c) { c.classList.remove('is-match'); });
            tree.classList.toggle('has-search', term !== '');
            if (term === '') { status.textContent = ''; apply(); return; }

            var first = null, count = 0;
            tree.querySelectorAll('.oc-node').forEach(function (node) {
                if ((node.dataset.search || '').indexOf(term) === -1) return;
                var card = node.querySelector(':scope > .oc-card');
                card.classList.add('is-match');
                count++;
                if (!first) first = card;
                var p = node.parentElement ? node.parentElement.closest('.oc-node') : null;   // open every ancestor
                while (p) { setCollapsed(p, false); p = p.parentElement ? p.parentElement.closest('.oc-node') : null; }
            });

            status.textContent = count === 0 ? 'No matches' : count + (count === 1 ? ' match' : ' matches');
            apply();
            if (first) reveal(first);
        }

        function reveal(card) {
            if (mq.matches) {
                var r = card.getBoundingClientRect(), v = viewport.getBoundingClientRect();
                viewport.scrollTo({
                    left: viewport.scrollLeft + (r.left - v.left) - (v.width - r.width) / 2,
                    top:  viewport.scrollTop  + (r.top  - v.top)  - (v.height - r.height) / 2,
                    behavior: 'smooth'
                });
            } else {
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        input.addEventListener('input', function () { clearTimeout(timer); timer = setTimeout(runSearch, 150); });

        /* ── Layout triggers ── */
        window._ocApply = apply;
        if (mq.addEventListener) mq.addEventListener('change', function () { fit = true; apply(); });
        apply();
        requestAnimationFrame(apply);
        window.addEventListener('load', apply);
        if (document.fonts && document.fonts.ready) document.fonts.ready.then(apply);
    }

    if (!window._ocResizeBound) {
        window._ocResizeBound = true;
        var t = null;
        window.addEventListener('resize', function () {
            clearTimeout(t);
            t = setTimeout(function () { if (window._ocApply) window._ocApply(); }, 120);
        });
    }

    window.initOrgChart = init;   // app.js calls this after each PJAX navigation
    init();
})();
</script>

@endsection

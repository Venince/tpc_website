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

                    <button type="button" class="oc-btn" data-oc-expand>Expand all</button>
                    <button type="button" class="oc-btn" data-oc-collapse>Collapse all</button>

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

                <p class="oc-hint">
                    <span class="oc-hint-wide">Drag to move around, Ctrl + scroll to zoom. Use the numbered buttons under a card to show or hide the people below it.</span>
                    <span class="oc-hint-narrow">Tap the numbered button on a card to show or hide the people below it.</span>
                </p>
            </div>
        @endif
    </div>
</section>

{{--
    Styles and script live INSIDE @section('content') so the PJAX engine re-applies them on every
    navigation (anything pushed outside #tpc-content is ignored by PJAX).
--}}
<style>
.oc{
    --oc-line:#B4CBB4; --oc-lw:2px; --oc-g:22px;
    --oc-green:#008000; --oc-dark:#006400; --oc-light:#90EE90;
    --oc-ink:#1F2937; --oc-muted:#6B7280; --oc-border:#E2E8E2;
    color:var(--oc-ink);
}

/* ── Toolbar ───────────────────────────────────────────────────────────── */
.oc-toolbar{display:flex;flex-wrap:wrap;align-items:center;gap:.625rem;margin-bottom:1rem}
.oc-search{position:relative;flex:1 1 240px;max-width:380px}
.oc-search svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#6B7280;pointer-events:none}
.oc-search input{width:100%;height:40px;padding:0 12px 0 36px;border:1px solid #D1D5DB;border-radius:10px;background:#fff;font-size:.875rem;color:var(--oc-ink)}
.oc-search input::placeholder{color:#6B7280}
.oc-search input:focus{outline:none;border-color:var(--oc-green);box-shadow:0 0 0 3px rgba(0,128,0,.18)}
.oc-btn{display:inline-flex;align-items:center;justify-content:center;gap:.375rem;height:40px;padding:0 .875rem;border:1px solid #D1D5DB;border-radius:10px;background:#fff;color:var(--oc-ink);font-size:.875rem;font-weight:500;cursor:pointer;transition:background-color .15s,border-color .15s,color .15s}
.oc-btn:hover{background:#F3F8F3;border-color:var(--oc-green);color:var(--oc-dark)}
.oc-btn:focus-visible,.oc-toggle:focus-visible{outline:none;box-shadow:0 0 0 3px rgba(0,128,0,.35)}
.oc-btn-icon{width:40px;padding:0;font-size:1.125rem;line-height:1}
.oc-zoom{display:none;align-items:center;gap:.375rem}
.oc-zoom-label{min-width:3.25rem;text-align:center;font-size:.8125rem;font-weight:600;color:var(--oc-muted);font-variant-numeric:tabular-nums}
.oc-meta{display:flex;align-items:center;gap:.75rem;margin:0 0 0 auto;font-size:.8125rem;color:var(--oc-muted)}
.oc-status{color:var(--oc-dark);font-weight:600}
.oc-hint{margin:.75rem 0 0;font-size:.8125rem;color:var(--oc-muted)}
.oc-hint-narrow{display:inline}
.oc-hint-wide{display:none}

/* ── Viewport / canvas ─────────────────────────────────────────────────── */
.oc-viewport{
    position:relative;overflow:visible;padding:0;
    background:transparent;border:0;
}
.oc-sizer{margin:0 auto}
.oc-canvas{transform-origin:0 0}
.oc-tree{display:block}

/* ── List / tree shared pieces ─────────────────────────────────────────── */
.oc-children{list-style:none;margin:0;padding:0}
.oc-node{list-style:none;margin:0}
.oc-card{
    position:relative;display:flex;align-items:center;gap:12px;text-align:left;
    width:auto;background:#fff;border:1px solid var(--oc-border);border-left:4px solid var(--oc-green);
    border-radius:12px;padding:10px 12px;margin-bottom:8px;
    box-shadow:0 1px 2px rgba(16,24,40,.05);
    transition:box-shadow .2s,border-color .2s,opacity .2s;
}
.oc-card[data-depth="0"]{border-left-color:var(--oc-dark)}
.oc-photo{display:block;flex-shrink:0;width:44px;height:44px;border-radius:50%;object-fit:cover;object-position:top center;background:#E5E7EB;box-shadow:0 0 0 2px #fff,0 0 0 3px #CFE5CF}
.oc-text{min-width:0;flex:1}
.oc-name{margin:0;font-size:.875rem;font-weight:600;line-height:1.3;overflow-wrap:anywhere}
.oc-title{margin:.15rem 0 0;font-size:.75rem;font-weight:500;line-height:1.35;color:var(--oc-dark);overflow-wrap:anywhere}
.oc-dept{display:inline-block;max-width:100%;margin-top:.4rem;padding:.125rem .6rem;border-radius:999px;background:#EEF6EE;color:#3D6B3D;font-size:.6875rem;font-weight:500;line-height:1.4;overflow-wrap:anywhere}
.oc-toggle{
    flex-shrink:0;display:inline-flex;align-items:center;gap:4px;height:30px;padding:0 10px;margin-left:auto;
    border:1px solid #B4CBB4;border-radius:999px;background:#fff;color:var(--oc-dark);
    font-size:.75rem;font-weight:600;cursor:pointer;transition:background-color .15s,color .15s,border-color .15s;
}
.oc-toggle:hover{background:var(--oc-green);border-color:var(--oc-green);color:#fff}
.oc-toggle svg{transition:transform .2s}
.oc-node:not(.is-collapsed)>.oc-card .oc-toggle svg{transform:rotate(180deg)}
.oc-node.is-collapsed>.oc-branch{display:none}
.oc-rowline{display:none}
.oc-trunk{display:none}

/* Search highlighting */
.oc-tree.has-search .oc-card{opacity:.45}
.oc-tree.has-search .oc-card.is-match{opacity:1;border-color:var(--oc-green);box-shadow:0 0 0 3px var(--oc-light),0 10px 24px -12px rgba(0,100,0,.45)}

/* ── Indented list (phones & tablets, < 1024px) ────────────────────────── */
.oc-branch{margin:0 0 0 18px;padding:2px 0 0 14px;border-left:var(--oc-lw) solid var(--oc-line)}
.oc-children + .oc-children{margin-top:2px}
.oc-roots>.oc-node>.oc-branch{margin-left:22px}
.oc-team{display:grid;grid-template-columns:1fr;gap:8px;padding:10px;background:#F6FAF6;border:1px solid #DDE9DD;border-radius:14px}
.oc-team .oc-card{margin:0}
@media (min-width:640px){ .oc-team{grid-template-columns:repeat(2,minmax(0,1fr))} }

/* ── Tree canvas (≥ 1024px) ────────────────────────────────────────────── */
@media (min-width:1024px){
    .oc-zoom{display:inline-flex}
    .oc-hint-narrow{display:none}
    .oc-hint-wide{display:inline}

    .oc-viewport{
        overflow:auto;max-height:78vh;padding:28px 16px 36px;
        background:#fff;border:1px solid #E5E7EB;border-radius:16px;
        background-image:radial-gradient(#E3EDE3 1px,transparent 1px);background-size:22px 22px;
        scrollbar-width:thin;scrollbar-color:#B4CBB4 transparent;
    }
    .oc-viewport.is-pannable{cursor:grab}
    .oc-viewport.is-dragging{cursor:grabbing;user-select:none}
    .oc-canvas{width:max-content}

    .oc-children{display:flex;justify-content:center;align-items:flex-start}
    .oc-node{position:relative;display:flex;flex-direction:column;align-items:center;padding:var(--oc-g) 10px 0}
    .oc-roots>.oc-node{padding-top:0}

    /* Cards become centred "badges" */
    .oc-card{flex-direction:column;justify-content:flex-start;width:216px;margin:0;padding:14px 12px 20px;text-align:center;gap:0;
             border-left:1px solid var(--oc-border);border-top:3px solid var(--oc-green)}
    .oc-card[data-depth="0"]{width:248px;padding-top:18px;border-top-color:var(--oc-dark);border-left-color:var(--oc-border)}
    .oc-card[data-depth="1"]{border-left-color:var(--oc-border)}
    .oc-card:hover{box-shadow:0 12px 26px -14px rgba(0,100,0,.4);border-color:#BFD8BF}
    .oc-photo{width:56px;height:56px;margin:0 auto 8px}
    .oc-card[data-depth="0"] .oc-photo{width:72px;height:72px}
    .oc-text{flex:none;width:100%}
    .oc-toggle{position:absolute;left:50%;bottom:-14px;margin:0;transform:translateX(-50%);z-index:3;box-shadow:0 1px 2px rgba(16,24,40,.08)}

    /* Connectors: pure CSS, so they survive scaling and never wait on images */
    .oc-branch{position:relative;display:flex;flex-direction:column;align-items:center;margin:0;padding:var(--oc-g) 0 0;border:0}
    .oc-roots>.oc-node>.oc-branch{margin:0}
    .oc-branch::before{content:'';position:absolute;top:0;left:50%;height:var(--oc-g);border-left:var(--oc-lw) solid var(--oc-line)}
    .oc-node::before,.oc-node::after{content:'';position:absolute;top:0;width:50%;height:var(--oc-g);border-top:var(--oc-lw) solid var(--oc-line)}
    .oc-node::before{right:50%}
    .oc-node::after{left:50%;border-left:var(--oc-lw) solid var(--oc-line)}
    .oc-node:only-child::before{display:none}
    .oc-node:only-child::after{border-top:0}
    .oc-node:first-child::before{border:0 none}
    .oc-node:last-child::after{border:0 none}
    .oc-node:last-child::before{border-right:var(--oc-lw) solid var(--oc-line);border-radius:0 8px 0 0}
    .oc-node:first-child::after{border-radius:8px 0 0 0}
    .oc-node:first-child:last-child::after{border-radius:0}
    .oc-roots>.oc-node::before,.oc-roots>.oc-node::after{display:none}
    .oc-roots{gap:0}

    /* Children split over several rows: a trunk (drawn by the script) links the manager to the last row */
    .oc-trunk{display:block;position:absolute;top:0;left:50%;width:0;border-left:var(--oc-lw) dashed var(--oc-line);z-index:0}
    .oc-children + .oc-children{margin-top:0;padding-top:calc(var(--oc-g) + 16px)}
    .oc-card{z-index:1}
    .oc-children[data-reports-to]{position:relative}
    .oc-children[data-reports-to]::before{
        content:'Reports to ' attr(data-reports-to);position:absolute;top:17px;left:50%;transform:translateX(-50%);z-index:2;
        max-width:240px;padding:1px 10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
        background:#fff;border:1px solid var(--oc-border);border-radius:999px;font-size:11px;font-weight:500;line-height:18px;color:var(--oc-muted)
    }

    /* Team panel: people with no reports are grouped into one tidy block */
    .oc-team{display:grid;grid-template-columns:repeat(var(--oc-cols,2),200px);gap:10px;padding:12px;margin:0;background:#F6FAF6;border:1px solid #DDE9DD;border-radius:14px}
    .oc-team>.oc-node{display:block;padding:0}
    .oc-team>.oc-node::before,.oc-team>.oc-node::after{display:none}
    .oc-team .oc-card{width:200px;flex-direction:row;align-items:center;gap:10px;padding:10px 12px;text-align:left;border-top:1px solid var(--oc-border);border-left:3px solid var(--oc-green)}
    .oc-team .oc-photo{width:40px;height:40px;margin:0}
    .oc-team .oc-text{flex:1;min-width:0;width:auto}
}
@media print{
    .oc-toolbar,.oc-hint{display:none}
    .oc-canvas{transform:none!important}
    .oc-viewport{overflow:visible;max-height:none}
}
@media (prefers-reduced-motion:reduce){
    .oc-card,.oc-toggle,.oc-toggle svg{transition:none}
}
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

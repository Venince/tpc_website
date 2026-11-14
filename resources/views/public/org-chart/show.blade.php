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
                {{-- Runs before the chart paints so the saved/default view never flashes (List on phones & tablets, Chart on desktop). --}}
                <script>(function(){try{var r=document.currentScript.parentNode,s=localStorage.getItem('tpcOrgView'),v=(s==='list'||s==='chart')?s:(matchMedia('(min-width:1024px)').matches?'chart':'list');if(v==='chart')r.classList.add('is-chart');}catch(e){}})();</script>

                {{-- Toolbar --}}
                <div class="oc-toolbar" role="region" aria-label="Chart controls">
                    <div class="oc-search">
                        <label for="oc-search-input" class="sr-only">Search people or offices</label>
                        <svg viewBox="0 0 20 20" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><circle cx="9" cy="9" r="5.5"/><path d="m13.5 13.5 3.5 3.5"/></svg>
                        <input id="oc-search-input" type="search" placeholder="Search a person, title or office" autocomplete="off" data-oc-search>
                    </div>

                    <div class="oc-view" role="group" aria-label="View">
                        <button type="button" class="oc-view-btn" data-oc-view="list" aria-pressed="true">List</button>
                        <button type="button" class="oc-view-btn" data-oc-view="chart" aria-pressed="false">Chart</button>
                    </div>

                    <div class="oc-actions">
                        <button type="button" class="oc-btn" data-oc-expand>Expand all</button>
                        <button type="button" class="oc-btn" data-oc-collapse>Collapse all</button>
                    </div>

                    <div class="oc-zoom" role="group" aria-label="Zoom">
                        <button type="button" class="oc-btn oc-btn-icon" data-oc-zoom="out" aria-label="Zoom out">&minus;</button>
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

                <p class="oc-hint">Drag to move around. Pinch, or hold Ctrl and scroll, to zoom.</p>
            </div>
        @endif
    </div>
</section>

{{--
    Styles and script live INSIDE @section('content') so the PJAX engine re-applies them on every
    navigation (anything pushed outside #tpc-content is ignored by PJAX).
--}}
<style>
/* Formal, neutral chart. Every card looks the same; colour appears only for focus and search states.
   The page has two views on every screen size:  .oc  = List (default on phones/tablets),  .oc.is-chart = Chart. */
.oc{
    --oc-line:#9CA3AF; --oc-lw:1px; --oc-g:24px;
    --oc-ink:#111827; --oc-muted:#4B5563; --oc-faint:#6B7280;
    --oc-border:#D1D5DB; --oc-brand:#006400;
    color:var(--oc-ink);
}

/* ── Toolbar (phone first) ─────────────────────────────────────────────── */
.oc-toolbar{display:flex;flex-wrap:wrap;align-items:center;gap:.5rem;margin-bottom:1rem}
.oc-search{position:relative;flex:1 1 100%}
.oc-search svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--oc-faint);pointer-events:none}
.oc-search input{width:100%;height:44px;padding:0 12px 0 38px;border:1px solid var(--oc-border);border-radius:6px;background:#fff;font-size:1rem;color:var(--oc-ink)}
.oc-search input::placeholder{color:var(--oc-faint)}
.oc-search input:focus{outline:2px solid var(--oc-brand);outline-offset:1px;border-color:var(--oc-brand)}
.oc-btn{display:inline-flex;align-items:center;justify-content:center;height:44px;padding:0 1rem;border:1px solid var(--oc-border);border-radius:6px;background:#fff;color:var(--oc-ink);font-size:.875rem;font-weight:500;cursor:pointer}
.oc-btn:hover{background:#F3F4F6}
.oc-btn:focus-visible,.oc-toggle:focus-visible{outline:2px solid var(--oc-brand);outline-offset:2px}
.oc-btn-icon{width:44px;padding:0;font-size:1.125rem;line-height:1}

/* List / Chart switch */
.oc-view{display:flex;flex:1 1 100%;overflow:hidden;border:1px solid var(--oc-border);border-radius:6px;background:#fff}
.oc-view-btn{flex:1;height:44px;padding:0 1rem;border:0;background:#fff;color:#374151;font-size:.875rem;font-weight:500;cursor:pointer}
.oc-view-btn + .oc-view-btn{border-left:1px solid var(--oc-border)}
.oc-view-btn:hover{background:#F3F4F6}
.oc-view-btn[aria-pressed="true"]{background:#1F2937;color:#fff}
.oc-view-btn:focus-visible{outline:2px solid var(--oc-brand);outline-offset:-3px}

.oc-actions{display:grid;grid-template-columns:1fr 1fr;gap:.5rem;flex:1 1 100%}
.oc-zoom{display:none;align-items:center;justify-content:center;gap:.375rem;flex:1 1 100%}
.oc.is-chart .oc-zoom{display:flex}
.oc-zoom-label{min-width:3rem;text-align:center;font-size:.8125rem;color:var(--oc-muted);font-variant-numeric:tabular-nums}
.oc-meta{display:flex;align-items:center;gap:.75rem;margin:0;font-size:.8125rem;color:var(--oc-muted)}
.oc-status{color:var(--oc-ink);font-weight:600}
.oc-hint{display:none;margin:.75rem 0 0;font-size:.8125rem;color:var(--oc-faint)}
.oc.is-chart .oc-hint{display:block}

/* ── Shared ────────────────────────────────────────────────────────────── */
.oc-viewport{position:relative}
.oc-sizer{margin:0 auto}
.oc-canvas{transform-origin:0 0}
.oc-children{list-style:none;margin:0;padding:0}
.oc-node{list-style:none;margin:0}
.oc-trunk,.oc-rowline{display:none}

.oc-card{
    position:relative;display:flex;flex-wrap:wrap;align-items:center;gap:12px;
    background:#fff;border:1px solid var(--oc-border);border-radius:6px;
    padding:12px 14px;margin-bottom:8px;
}
.oc-photo{display:block;flex-shrink:0;width:44px;height:44px;border-radius:50%;object-fit:cover;object-position:top center;background:#E5E7EB}
.oc-text{min-width:0;flex:1}
.oc-name{margin:0;font-size:.9375rem;font-weight:600;line-height:1.3;overflow-wrap:anywhere}
.oc-title{margin:2px 0 0;font-size:.8125rem;line-height:1.4;color:var(--oc-muted);overflow-wrap:anywhere}
.oc-dept{display:block;margin-top:2px;font-size:.75rem;line-height:1.4;color:var(--oc-faint);overflow-wrap:anywhere}

.oc-toggle{
    flex:1 1 100%;width:100%;display:flex;align-items:center;justify-content:space-between;gap:6px;min-height:44px;padding:0 12px;margin:8px 0 0;
    border:1px solid var(--oc-border);border-radius:6px;background:#fff;color:#374151;
    font-size:.875rem;font-weight:600;cursor:pointer;
}
.oc-toggle:hover{background:#F3F4F6}
.oc-toggle svg{transition:transform .15s}
.oc-node:not(.is-collapsed)>.oc-card .oc-toggle svg{transform:rotate(180deg)}
.oc-count-label{font-weight:500;color:var(--oc-muted)}
.oc-node.is-collapsed>.oc-card .oc-when-open,
.oc-node:not(.is-collapsed)>.oc-card .oc-when-collapsed{display:none}
.oc-node.is-collapsed>.oc-branch{display:none}

/* Search: plain highlight, everything else recedes */
.oc-tree.has-search .oc-card{opacity:.4}
.oc-tree.has-search .oc-card.is-match{opacity:1;background:#FEF9C3;border-color:#A16207}

/* ── LIST view ─────────────────────────────────────────────────────────── */
.oc-branch{margin:0 0 8px 12px;padding:8px 0 0 12px;border-left:var(--oc-lw) solid var(--oc-line)}
.oc-team{display:grid;grid-template-columns:1fr;gap:8px}
.oc-team .oc-card{margin:0}
.oc-node>.oc-card:last-child{margin-bottom:8px}
@media (min-width:640px){
    .oc-search{flex:0 1 380px}
    .oc-view{flex:0 0 auto}
    .oc-view-btn{flex:none}
    .oc-actions{display:flex;flex:0 0 auto}
    .oc.is-chart .oc-zoom{flex:0 0 auto}
    .oc-meta{margin-left:auto}
    .oc-team{grid-template-columns:repeat(2,minmax(0,1fr))}
}
@media (min-width:1024px){
    .oc-search input,.oc-btn,.oc-view-btn{height:38px;font-size:.875rem}
    .oc-btn-icon{width:38px}
}

/* ── CHART view (any screen size) ──────────────────────────────────────── */
.oc.is-chart .oc-viewport{
    overflow:auto;max-height:75vh;padding:16px 12px 32px;
    background:#fff;border:1px solid #E5E7EB;border-radius:8px;
    touch-action:pan-x pan-y;                 /* one finger pans natively; pinch is handled by the script */
    scrollbar-width:thin;scrollbar-color:#9CA3AF transparent;
}
@media (min-width:1024px){ .oc.is-chart .oc-viewport{max-height:78vh;padding:32px 24px 44px} }
.oc.is-chart .oc-viewport.is-pannable{cursor:grab}
.oc.is-chart .oc-viewport.is-dragging{cursor:grabbing;user-select:none}
.oc.is-chart .oc-canvas{width:max-content}

.oc.is-chart .oc-children{display:flex;justify-content:center;align-items:flex-start}
.oc.is-chart .oc-node{position:relative;display:flex;flex-direction:column;align-items:center;padding:var(--oc-g) 12px 0}
.oc.is-chart .oc-roots>.oc-node{padding-top:0}

.oc.is-chart .oc-card{z-index:1;flex-wrap:nowrap;flex-direction:column;justify-content:flex-start;gap:0;width:216px;margin:0;padding:16px 14px 22px;text-align:center}
.oc.is-chart .oc-photo{width:52px;height:52px;margin:0 auto 10px}
.oc.is-chart .oc-text{flex:none;width:100%}
.oc.is-chart .oc-toggle{position:absolute;left:50%;bottom:-14px;transform:translateX(-50%);z-index:3;flex:none;width:auto;display:inline-flex;justify-content:center;height:28px;min-height:0;margin:0;padding:0 9px;font-size:.75rem}
.oc.is-chart .oc-when-collapsed,.oc.is-chart .oc-when-open,.oc.is-chart .oc-count-label{display:none!important}

/* Connectors: 1px neutral lines, square corners, pure CSS */
.oc.is-chart .oc-branch{position:relative;display:flex;flex-direction:column;align-items:center;margin:0;padding:var(--oc-g) 0 0;border:0}
.oc.is-chart .oc-node.is-collapsed>.oc-branch{display:none}
.oc.is-chart .oc-branch::before{content:'';position:absolute;top:0;left:50%;height:var(--oc-g);border-left:var(--oc-lw) solid var(--oc-line)}
.oc.is-chart .oc-node::before,.oc.is-chart .oc-node::after{content:'';position:absolute;top:0;width:50%;height:var(--oc-g);border-top:var(--oc-lw) solid var(--oc-line)}
.oc.is-chart .oc-node::before{right:50%}
.oc.is-chart .oc-node::after{left:50%;border-left:var(--oc-lw) solid var(--oc-line)}
.oc.is-chart .oc-node:only-child::before{display:none}
.oc.is-chart .oc-node:only-child::after{border-top:0}
.oc.is-chart .oc-node:first-child::before{border:0 none}
.oc.is-chart .oc-node:last-child::after{border:0 none}
.oc.is-chart .oc-node:last-child::before{right:calc(50% - var(--oc-lw));border-right:var(--oc-lw) solid var(--oc-line)}
.oc.is-chart .oc-roots>.oc-node::before,.oc.is-chart .oc-roots>.oc-node::after{display:none}

/* Children split over several rows: one trunk (drawn by the script) links the manager to the last row */
.oc.is-chart .oc-trunk{display:block;position:absolute;top:0;left:50%;width:0;border-left:var(--oc-lw) solid var(--oc-line);z-index:0}
.oc.is-chart .oc-children + .oc-children{padding-top:calc(var(--oc-g) + 16px)}
.oc.is-chart .oc-children[data-reports-to]{position:relative}
.oc.is-chart .oc-children[data-reports-to]::before{
    content:'Reports to ' attr(data-reports-to);position:absolute;top:17px;left:50%;transform:translateX(-50%);z-index:2;
    max-width:260px;padding:0 8px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
    background:#fff;font-size:11px;line-height:18px;color:var(--oc-faint)
}

/* People with no reports sit together in one block instead of a wide row */
.oc.is-chart .oc-team{display:grid;grid-template-columns:repeat(var(--oc-cols,2),208px);gap:8px;padding:10px;background:#F3F4F6;border-radius:6px}
.oc.is-chart .oc-team>.oc-node{display:block;padding:0}
.oc.is-chart .oc-team>.oc-node::before,.oc.is-chart .oc-team>.oc-node::after{display:none}
.oc.is-chart .oc-team .oc-card{width:208px;flex-direction:row;align-items:center;gap:10px;padding:10px 12px;text-align:left}
.oc.is-chart .oc-team .oc-photo{width:36px;height:36px;margin:0}
.oc.is-chart .oc-team .oc-text{flex:1;min-width:0;width:auto}

@media print{
    .oc-toolbar,.oc-hint{display:none}
    .oc-canvas{transform:none!important}
    .oc.is-chart .oc-viewport{overflow:visible;max-height:none}
}
@media (prefers-reduced-motion:reduce){ .oc-toggle svg{transition:none} }
</style>

<script>
(function () {
    var MIN = 0.3, FIT_MIN = 0.55, MAX = 1.6, STEP = 0.1;
    var KEY = 'tpcOrgView';
    var DESKTOP_DEFAULT = 'chart';   // phones & tablets always default to the list
    var MOBILE_DEFAULT  = 'list';

    function stored() {
        try { var v = localStorage.getItem(KEY); return (v === 'list' || v === 'chart') ? v : null; } catch (e) { return null; }
    }

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
        var viewBtns = root.querySelectorAll('[data-oc-view]');
        var mq       = window.matchMedia('(min-width: 1024px)');

        var view  = stored() || (mq.matches ? DESKTOP_DEFAULT : MOBILE_DEFAULT);
        var scale = 1, fit = true;
        function isChart() { return view === 'chart'; }

        /* ── List / Chart switch ── */
        function setView(next, persist) {
            view = next;
            root.classList.toggle('is-chart', isChart());
            viewBtns.forEach(function (b) { b.setAttribute('aria-pressed', b.getAttribute('data-oc-view') === view ? 'true' : 'false'); });
            if (persist) { try { localStorage.setItem(KEY, view); } catch (e) {} }
            fit = true;
            apply();                                  // centres a wide chart on the top of the hierarchy
            if (isChart()) viewport.scrollTop = 0;
        }
        viewBtns.forEach(function (b) {
            b.addEventListener('click', function () { setView(b.getAttribute('data-oc-view'), true); });
        });

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
                trunk.style.height = (last && isChart())
                    ? (last.offsetTop + parseFloat(getComputedStyle(last).paddingTop || 0)) + 'px'
                    : '0';
            });
        }

        /* ── Fit / zoom (chart view only) ── */
        function apply() {
            if (!isChart()) {
                canvas.style.transform = '';
                sizer.style.width = '';
                sizer.style.height = '';
                viewport.classList.remove('is-pannable');
                return;
            }
            drawTrunks();
            var w = canvas.offsetWidth, h = canvas.offsetHeight;      // layout size, unaffected by transform
            var cs = getComputedStyle(viewport);
            var pad = parseFloat(cs.paddingLeft) + parseFloat(cs.paddingRight);
            if (fit) {
                scale = Math.max(FIT_MIN, Math.min(1, (viewport.clientWidth - pad) / w));
            }
            canvas.style.transform = 'scale(' + scale + ')';
            sizer.style.width  = Math.ceil(w * scale) + 'px';
            sizer.style.height = Math.ceil(h * scale) + 'px';
            if (label) label.textContent = Math.round(scale * 100) + '%';
            viewport.classList.toggle('is-pannable',
                sizer.offsetWidth > viewport.clientWidth - pad || sizer.offsetHeight > viewport.clientHeight - 64);
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
                if (a === 'fit') { fit = true; apply(); viewport.scrollLeft = 0; viewport.scrollTop = 0; apply(); }
            });
        });

        viewport.addEventListener('wheel', function (e) {
            if (!isChart() || !(e.ctrlKey || e.metaKey)) return;
            e.preventDefault();
            setScale(scale + (e.deltaY < 0 ? STEP : -STEP));
        }, { passive: false });

        /* Mouse: click-and-drag panning. Touch: one finger pans natively, two fingers pinch-zoom. */
        var drag = null;
        viewport.addEventListener('pointerdown', function (e) {
            if (!isChart() || e.pointerType !== 'mouse' || e.button !== 0) return;
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

        var pinch = null;
        function touchDist(t) { var dx = t[0].clientX - t[1].clientX, dy = t[0].clientY - t[1].clientY; return Math.sqrt(dx * dx + dy * dy); }
        viewport.addEventListener('touchstart', function (e) {
            if (isChart() && e.touches.length === 2) pinch = { d: touchDist(e.touches), s: scale };
        }, { passive: true });
        viewport.addEventListener('touchmove', function (e) {
            if (!isChart() || !pinch || e.touches.length !== 2) return;
            e.preventDefault();
            setScale(pinch.s * touchDist(e.touches) / pinch.d);
        }, { passive: false });
        function endPinch(e) { if (!e.touches || e.touches.length < 2) pinch = null; }
        viewport.addEventListener('touchend', endPinch);
        viewport.addEventListener('touchcancel', endPinch);

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
            if (isChart()) {
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
        // Crossing the desktop breakpoint switches to that size's default, unless the visitor picked a view.
        if (mq.addEventListener) mq.addEventListener('change', function () {
            if (!stored()) setView(mq.matches ? DESKTOP_DEFAULT : MOBILE_DEFAULT, false); else { fit = true; apply(); }
        });
        setView(view, false);
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

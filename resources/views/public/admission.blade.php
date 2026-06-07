{{-- resources/views/public/admission.blade.php --}}
@extends('layouts.site')

@section('title', 'Admission')

@section('content')

    {{-- PAGE HEADER --}}
    <section class="relative overflow-hidden bg-tpc-secondary">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0"
             style="background: radial-gradient(ellipse at 70% 50%, rgba(255,255,255,0.06) 0%, transparent 60%),
                                radial-gradient(ellipse at 20% 80%, rgba(0,0,0,0.15) 0%, transparent 50%)"></div>
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-[0.04]"
             style="background-image: linear-gradient(rgba(255,255,255,0.8) 1px, transparent 1px),
                                      linear-gradient(90deg, rgba(255,255,255,0.8) 1px, transparent 1px);
                    background-size: 40px 40px;"></div>
        <div class="relative mx-auto max-w-4xl px-4 pt-10 pb-16 sm:pt-14 sm:pb-20">
            <div class="flex flex-col items-center text-center">
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    Enrollment &amp; Requirements
                </h1>
                <p class="mt-3 max-w-lg text-sm text-white/60 leading-relaxed">
                    Learn the enrollment steps and prepare the needed documents to become a student of Talibon Polytechnic College.
                </p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="#requirements" data-scroll-btn id="btn-requirements"
                    class="inline-flex items-center justify-center rounded-full border-2 border-white bg-white px-5 py-2.5 text-sm font-bold text-tpc-primary transition-all duration-200">
                        View Requirements
                    </a>
                    <a href="#process" data-scroll-btn id="btn-process"
                    class="inline-flex items-center justify-center rounded-full border-2 border-white/90 bg-black/20 backdrop-blur-sm px-5 py-2.5 text-sm font-bold text-white transition-all duration-200">
                        Enrollment Process
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-8 sm:h-12">
                <path d="M0 48 C480 0 960 0 1440 48 L1440 48 L0 48 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 py-10 sm:py-14">
            <div class="grid gap-6 sm:gap-8 lg:grid-cols-3">

                {{-- LEFT MAIN --}}
                <div class="lg:col-span-2 space-y-6 sm:space-y-8">

                    {{-- Requirements --}}
                    @if (($sections->has('freshmen') && $sections['freshmen']->is_visible) ||
                         ($sections->has('transferee') && $sections['transferee']->is_visible))
                        <div id="requirements" class="scroll-mt-20 sm:scroll-mt-24">
                            <span id="admission-requirements" class="block -mt-24 pt-24 invisible absolute"></span>
                            <div class="flex items-center gap-3 sm:gap-4 mb-5 sm:mb-6">
                                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                                <h2 class="text-[10px] sm:text-xs font-bold tracking-widest text-tpc-primary uppercase">Admission Requirements</h2>
                                <div class="flex-1 h-px bg-gray-200"></div>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-500 mb-4 sm:mb-5">Bring original copies and photocopies as applicable.</p>
                            <div class="grid gap-4 sm:gap-5 sm:grid-cols-2">
                                @foreach (['freshmen', 'transferee'] as $key)
                                    @if ($sections->has($key) && $sections[$key]->is_visible)
                                        @php $sec = $sections[$key]; @endphp
                                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                                            <div class="h-1.5 bg-tpc-primary"></div>
                                            <div class="p-4 sm:p-6">
                                                <p class="text-[10px] sm:text-[11px] font-bold tracking-widest text-tpc-primary uppercase mb-3 sm:mb-4">{{ $sec->label }}</p>
                                                <ul class="space-y-2.5 sm:space-y-3">
                                                    @foreach ($sec->items as $item)
                                                        <li class="flex items-start gap-3 text-xs sm:text-sm text-gray-700">
                                                            <span class="mt-1.5 h-2 w-2 rounded-full bg-tpc-primary shrink-0"></span>
                                                            {{ $item->title }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            @if ($sections->has('requirements_note') && $sections['requirements_note']->is_visible && $sections['requirements_note']->note)
                                <div class="mt-4 sm:mt-5 flex gap-3 sm:gap-4 bg-tpc-primary/5 border border-tpc-primary/20 rounded-xl px-4 sm:px-5 py-3 sm:py-4">
                                    <span class="shrink-0 text-tpc-primary mt-0.5">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-[10px] sm:text-xs font-bold text-tpc-primary uppercase tracking-wide mb-1">{{ $sections['requirements_note']->label }}</p>
                                        <p class="text-xs sm:text-sm text-gray-600">{{ $sections['requirements_note']->note }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Enrollment Process --}}
                    @if ($sections->has('process') && $sections['process']->is_visible)
                        @php $process = $sections['process']; @endphp
                        <div id="process" class="scroll-mt-20 sm:scroll-mt-24">
                            <span id="enrollment-process" class="block -mt-24 pt-24 invisible absolute"></span>
                            <div class="flex items-center gap-3 sm:gap-4 mb-5 sm:mb-6">
                                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                                <h2 class="text-[10px] sm:text-xs font-bold tracking-widest text-tpc-primary uppercase">{{ $process->label }}</h2>
                                <div class="flex-1 h-px bg-gray-200"></div>
                            </div>
                            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                                <div class="h-1.5 bg-tpc-primary"></div>
                                <div class="divide-y divide-gray-100">
                                    @foreach ($process->items as $i => $step)
                                        <div class="flex gap-3 sm:gap-5 p-4 sm:p-5 hover:bg-gray-50 transition">
                                            <span class="shrink-0 flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-tpc-primary text-white text-xs sm:text-sm font-bold shadow-sm">{{ $i + 1 }}</span>
                                            <div class="pt-1 min-w-0">
                                                <p class="font-bold text-xs sm:text-sm text-gray-800">{{ $step->title }}</p>
                                                @if ($step->body)
                                                    <p class="mt-1 text-xs sm:text-sm text-gray-500 leading-relaxed">{{ $step->body }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @if ($process->note)
                                <div class="mt-4 sm:mt-5 flex gap-3 sm:gap-4 bg-tpc-primary/5 border border-tpc-primary/20 rounded-xl px-4 sm:px-5 py-3 sm:py-4">
                                    <span class="shrink-0 text-tpc-primary mt-0.5">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
                                        </svg>
                                    </span>
                                    <p class="text-xs sm:text-sm text-gray-600">{{ $process->note }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Explore Programs CTA --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="h-1.5 bg-tpc-accent"></div>
                        <div class="p-4 sm:p-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-bold text-sm sm:text-base text-gray-800">Explore programs before enrolling</p>
                                <p class="mt-1 text-xs sm:text-sm text-gray-500">View all academic programs offered by Talibon Polytechnic College.</p>
                            </div>
                            <div class="flex gap-2 sm:gap-3 shrink-0">
                                <a href="{{ route('academics') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-full border-2 border-tpc-primary bg-tpc-primary px-4 sm:px-5 py-2.5 text-xs sm:text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition">View Programs</a>
                                <a href="{{ route('news.index') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-full border-2 border-tpc-primary px-4 sm:px-5 py-2.5 text-xs sm:text-sm font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">Updates →</a>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT SIDEBAR --}}
                <aside class="space-y-4 sm:space-y-6">

                    {{-- Office Hours --}}
                    @if ($sections->has('office_hours') && $sections['office_hours']->is_visible)
                        @php $oh = $sections['office_hours']; @endphp
                        <div id="office-hours" class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="h-1.5 bg-tpc-primary"></div>
                            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100">
                                <p class="text-[10px] sm:text-xs font-bold text-tpc-primary uppercase tracking-widest">{{ $oh->label }}</p>
                            </div>
                            <div class="px-4 sm:px-5 divide-y divide-gray-100">
                                @foreach ($oh->items as $row)
                                    <div class="flex items-center justify-between py-2.5 sm:py-3 text-xs sm:text-sm">
                                        <span class="text-gray-500">{{ $row->title }}</span>
                                        <span class="font-bold text-gray-800">{{ $row->body }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @if ($oh->note)
                                <div class="border-t border-gray-100 bg-tpc-primary/5 px-4 sm:px-5 py-3 sm:py-4 rounded-b-2xl">
                                    <p class="text-[10px] sm:text-xs font-bold text-tpc-primary uppercase tracking-wide mb-1">Tip</p>
                                    <p class="text-xs sm:text-sm text-gray-600">{{ $oh->note }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Contact Admissions --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="h-1.5 bg-tpc-primary"></div>
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100">
                            <p class="text-[10px] sm:text-xs font-bold text-tpc-primary uppercase tracking-widest">Contact Admissions</p>
                        </div>
                        <div class="p-4 sm:p-5">
                            <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">Reach out for enrollment concerns, schedule, and guidance.</p>
                            <a href="{{ route('contact') }}" class="mt-3 sm:mt-4 flex items-center justify-center rounded-full border-2 border-tpc-primary bg-tpc-primary px-5 py-2.5 text-xs sm:text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition">Go to Contact Page</a>
                        </div>
                    </div>

                </aside>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════
         FAB + MODAL + SPINNER — injected directly onto <body>
         via JS so they escape #tpc-content's stacking context,
         and cleaned up automatically on PJAX navigation away.
    ══════════════════════════════════════════════════════ --}}
    <script>
    (function () {

        /* ── Styles ──────────────────────────────────────── */
        var styleEl = document.createElement('style');
        styleEl.id = 'dl-fab-styles';
        styleEl.textContent = [
            '#dl-fab{position:fixed;bottom:1.75rem;right:1.75rem;z-index:9999;display:flex;align-items:center;gap:.5rem;padding:.65rem 1.1rem;background:#166534;color:#fff;border-radius:9999px;font-size:.78rem;font-weight:700;letter-spacing:.04em;box-shadow:0 4px 20px rgba(22,101,52,.35),0 1px 4px rgba(0,0,0,.15);border:none;cursor:pointer;text-transform:uppercase;transition:transform .15s,box-shadow .15s,background .15s;}',
            '#dl-fab:hover{background:#14532d;box-shadow:0 6px 28px rgba(22,101,52,.45);transform:translateY(-1px);}',
            '#dl-fab svg{flex-shrink:0;}',
            '.dl-fab-label{white-space:nowrap;}',
            '@media(max-width:639px){#dl-fab{padding:.75rem;border-radius:50%;bottom:1.25rem;right:1.25rem;}.dl-fab-label{display:none;}}',
            '#dl-modal-overlay{position:fixed;inset:0;z-index:9998;background:rgba(0,0,0,.45);backdrop-filter:blur(3px);display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .2s;}',
            '#dl-modal-overlay.open{opacity:1;pointer-events:all;}',
            '#dl-modal{background:#fff;border-radius:1.25rem;width:min(26rem,calc(100vw - 2rem));box-shadow:0 24px 64px rgba(0,0,0,.18);overflow:hidden;transform:translateY(12px) scale(.97);transition:transform .22s cubic-bezier(.16,1,.3,1);}',
            '#dl-modal-overlay.open #dl-modal{transform:translateY(0) scale(1);}',
            '.dl-modal-header{background:linear-gradient(135deg,#166534 0%,#15803d 100%);padding:1.25rem 1.5rem 1rem;display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;}',
            '.dl-modal-header h3{color:#fff;font-size:.95rem;font-weight:800;letter-spacing:-.01em;line-height:1.2;margin:0;}',
            '.dl-modal-header p{color:rgba(255,255,255,.7);font-size:.72rem;margin:.25rem 0 0;}',
            '.dl-modal-close{background:rgba(255,255,255,.18);border:none;color:#fff;cursor:pointer;width:1.75rem;height:1.75rem;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .15s;}',
            '.dl-modal-close:hover{background:rgba(255,255,255,.32);}',
            '.dl-modal-body{padding:1.25rem 1.5rem;}',
            '.dl-toggle-list{display:flex;flex-direction:column;gap:.6rem;margin-bottom:1.1rem;}',
            '.dl-toggle{display:flex;align-items:center;gap:.75rem;padding:.65rem .9rem;background:#f8fafb;border:1.5px solid #e5e7eb;border-radius:.75rem;cursor:pointer;transition:border-color .14s,background .14s;user-select:none;}',
            '.dl-toggle:hover{background:#f0fdf4;border-color:#86efac;}',
            '.dl-toggle.selected{background:#f0fdf4;border-color:#16a34a;}',
            '.dl-toggle input[type=checkbox]{display:none;}',
            '.dl-check-box{width:1.15rem;height:1.15rem;border-radius:.3rem;border:2px solid #d1d5db;background:#fff;flex-shrink:0;display:flex;align-items:center;justify-content:center;transition:background .14s,border-color .14s;}',
            '.dl-toggle.selected .dl-check-box{background:#16a34a;border-color:#16a34a;}',
            '.dl-check-box svg{display:none;}',
            '.dl-toggle.selected .dl-check-box svg{display:block;}',
            '.dl-toggle-text{flex:1;}',
            '.dl-toggle-text strong{display:block;font-size:.8rem;font-weight:700;color:#1f2937;}',
            '.dl-toggle-text span{font-size:.7rem;color:#6b7280;}',
            '.dl-actions{display:grid;grid-template-columns:1fr 1fr;gap:.6rem;}',
            '.dl-btn{display:flex;align-items:center;justify-content:center;gap:.45rem;padding:.65rem .5rem;border-radius:.75rem;font-size:.78rem;font-weight:700;letter-spacing:.02em;cursor:pointer;border:2px solid transparent;transition:transform .12s,box-shadow .12s,background .14s,border-color .14s;text-transform:uppercase;}',
            '.dl-btn:active{transform:scale(.96);}',
            '.dl-btn:disabled{opacity:.45;pointer-events:none;}',
            '.dl-btn-print{background:#fff;border-color:#166534;color:#166534;}',
            '.dl-btn-print:hover{background:#f0fdf4;}',
            '.dl-btn-img{background:#166534;color:#fff;}',
            '.dl-btn-img:hover{background:#14532d;box-shadow:0 3px 12px rgba(22,101,52,.35);}',
            '.dl-note{margin-top:.9rem;padding:.55rem .75rem;background:#fffbeb;border:1px solid #fde68a;border-radius:.6rem;font-size:.68rem;color:#92400e;line-height:1.5;display:none;}',
            '.dl-note.visible{display:block;}',
            '#dl-spinner{position:fixed;inset:0;z-index:10000;background:rgba(255,255,255,.8);backdrop-filter:blur(4px);display:none;flex-direction:column;align-items:center;justify-content:center;gap:1rem;}',
            '#dl-spinner.visible{display:flex;}',
            '.dl-spinner-ring{width:3rem;height:3rem;border-radius:50%;border:3px solid #dcfce7;border-top-color:#166534;animation:dlspin .7s linear infinite;}',
            '@keyframes dlspin{to{transform:rotate(360deg);}}',
            '#dl-spinner p{font-size:.8rem;font-weight:600;color:#166534;letter-spacing:.03em;}',
            '@media print{*{-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;}',
            'body>*:not(#admission-print-root){display:none !important;}',
            '#admission-print-root{display:block !important;position:static !important;width:100% !important;margin:0 !important;}',
            '#dl-fab,#dl-modal-overlay,#dl-spinner{display:none !important;}',
            '.print-page{font-family:"Segoe UI",system-ui,sans-serif;max-width:780px;margin:0 auto;padding:0;color:#111827;}',
            '.print-header{background:#166534 !important;color:#fff !important;padding:28px 36px 22px;margin-bottom:28px;}',
            '.print-logo-row{display:flex;align-items:center;gap:14px;margin-bottom:10px;}',
            '.print-header h1{font-size:1.35rem;font-weight:800;margin:0;letter-spacing:-.02em;}',
            '.print-header p{font-size:.78rem;color:rgba(255,255,255,.72);margin:4px 0 0;}',
            '.print-date{font-size:.68rem;color:rgba(255,255,255,.55);margin-top:12px;}',
            '.print-section{margin-bottom:26px;break-inside:avoid;}',
            '.print-section-title{font-size:.65rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#166534;display:flex;align-items:center;gap:8px;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid #dcfce7;}',
            '.print-section-title::before{content:"";display:inline-block;width:4px;height:16px;background:#166534;border-radius:2px;}',
            '.print-req-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;}',
            '.print-req-card{border:1.5px solid #d1fae5;border-radius:10px;overflow:hidden;}',
            '.print-req-card-top{background:#166534 !important;color:#fff !important;font-size:.65rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;padding:7px 14px;}',
            '.print-req-card ul{list-style:none;padding:12px 14px;margin:0;}',
            '.print-req-card li{display:flex;align-items:flex-start;gap:8px;font-size:.78rem;color:#374151;padding:4px 0;line-height:1.4;}',
            '.print-req-card li::before{content:"";display:inline-block;flex-shrink:0;width:7px;height:7px;border-radius:50%;background:#16a34a !important;margin-top:5px;}',
            '.print-note{background:#f0fdf4 !important;border:1.5px solid #bbf7d0 !important;border-radius:8px;padding:10px 14px;margin-top:12px;font-size:.74rem;color:#166534;line-height:1.5;}',
            '.print-note strong{display:block;font-size:.62rem;text-transform:uppercase;letter-spacing:.07em;margin-bottom:3px;}',
            '.print-steps{display:flex;flex-direction:column;gap:0;border:1.5px solid #d1fae5;border-radius:10px;overflow:hidden;}',
            '.print-step{display:flex;align-items:flex-start;gap:14px;padding:11px 16px;border-bottom:1px solid #f0fdf4;background:#fff !important;}',
            '.print-step:last-child{border-bottom:none;}',
            '.print-step-num{flex-shrink:0;width:28px;height:28px;border-radius:50%;background:#166534 !important;color:#fff !important;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800;}',
            '.print-step-body{flex:1;padding-top:3px;}',
            '.print-step-body strong{display:block;font-size:.8rem;font-weight:700;color:#111827;}',
            '.print-step-body p{font-size:.74rem;color:#6b7280;margin:2px 0 0;line-height:1.4;}',
            '.print-schedule{border:1.5px solid #d1fae5;border-radius:10px;overflow:hidden;}',
            '.print-schedule-row{display:flex;justify-content:space-between;align-items:center;padding:9px 16px;border-bottom:1px solid #f0fdf4;font-size:.78rem;background:#fff !important;}',
            '.print-schedule-row:last-child{border-bottom:none;}',
            '.print-schedule-row span:first-child{color:#6b7280;}',
            '.print-schedule-row span:last-child{font-weight:700;color:#111827;}',
            '.print-footer{margin-top:32px;padding-top:14px;border-top:1px solid #e5e7eb;font-size:.65rem;color:#9ca3af;text-align:center;}}'
        ].join('');
        document.head.appendChild(styleEl);

        /* ── FAB HTML ─────────────────────────────────────── */
        var fabHTML =
            '<button id="dl-fab" aria-label="Download or Print" onclick="openDlModal()">' +
                '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                    '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>' +
                    '<polyline points="7 10 12 15 17 10"/>' +
                    '<line x1="12" y1="15" x2="12" y2="3"/>' +
                '</svg>' +
                '<span class="dl-fab-label">Download / Print</span>' +
            '</button>';

        /* ── Modal HTML ───────────────────────────────────── */
        var togglesHTML = '';
        @if (($sections->has('freshmen') && $sections['freshmen']->is_visible) || ($sections->has('transferee') && $sections['transferee']->is_visible))
        togglesHTML +=
            '<label class="dl-toggle selected" data-section="requirements">' +
                '<input type="checkbox" checked>' +
                '<div class="dl-check-box">' +
                    '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>' +
                '</div>' +
                '<div class="dl-toggle-text"><strong>Admission Requirements</strong><span>Freshmen &amp; Transferee document checklists</span></div>' +
            '</label>';
        @endif
        @if ($sections->has('process') && $sections['process']->is_visible)
        togglesHTML +=
            '<label class="dl-toggle selected" data-section="process">' +
                '<input type="checkbox" checked>' +
                '<div class="dl-check-box">' +
                    '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>' +
                '</div>' +
                '<div class="dl-toggle-text"><strong>Enrollment Process</strong><span>Step-by-step enrollment guide</span></div>' +
            '</label>';
        @endif
        @if ($sections->has('office_hours') && $sections['office_hours']->is_visible)
        togglesHTML +=
            '<label class="dl-toggle selected" data-section="office_hours">' +
                '<input type="checkbox" checked>' +
                '<div class="dl-check-box">' +
                    '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>' +
                '</div>' +
                '<div class="dl-toggle-text"><strong>Office Hours</strong><span>Admissions office schedule</span></div>' +
            '</label>';
        @endif

        var modalHTML =
            '<div id="dl-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="dl-modal-title" onclick="closeDlModalOutside(event)">' +
                '<div id="dl-modal">' +
                    '<div class="dl-modal-header">' +
                        '<div><h3 id="dl-modal-title">Download &amp; Print</h3><p>Select the sections you want to include</p></div>' +
                        '<button class="dl-modal-close" onclick="closeDlModal()" aria-label="Close">' +
                            '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                                '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>' +
                            '</svg>' +
                        '</button>' +
                    '</div>' +
                    '<div class="dl-modal-body">' +
                        '<div class="dl-toggle-list">' + togglesHTML + '</div>' +
                        '<div class="dl-actions">' +
                            '<button class="dl-btn dl-btn-print" onclick="doPrint()">' +
                                '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">' +
                                    '<polyline points="6 9 6 2 18 2 18 9"/>' +
                                    '<path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>' +
                                    '<rect x="6" y="14" width="12" height="8"/>' +
                                '</svg>Print' +
                            '</button>' +
                            '<button class="dl-btn dl-btn-img" onclick="doSaveImage()">' +
                                '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">' +
                                    '<rect x="3" y="3" width="18" height="18" rx="2"/>' +
                                    '<circle cx="8.5" cy="8.5" r="1.5"/>' +
                                    '<polyline points="21 15 16 10 5 21"/>' +
                                '</svg>Save as Image' +
                            '</button>' +
                        '</div>' +
                        '<div class="dl-note" id="dl-note-none">Please select at least one section.</div>' +
                    '</div>' +
                '</div>' +
            '</div>';

        var spinnerHTML =
            '<div id="dl-spinner">' +
                '<div class="dl-spinner-ring"></div>' +
                '<p>Generating image\u2026</p>' +
            '</div>';

        var printRootHTML = '<div id="admission-print-root" style="display:none;position:absolute;left:-9999px;top:0;width:800px;"></div>';

        /* ── Inject all elements directly onto <body> ─────── */
        document.body.insertAdjacentHTML('beforeend', fabHTML + modalHTML + spinnerHTML + printRootHTML);

        /* ── Bind toggle events ───────────────────────────── */
        document.querySelectorAll('.dl-toggle').forEach(function(lbl) {
            lbl.addEventListener('click', function() {
                var cb = lbl.querySelector('input[type=checkbox]');
                cb.checked = !cb.checked;
                lbl.classList.toggle('selected', cb.checked);
                document.getElementById('dl-note-none').classList.remove('visible');
            });
        });

        /* ── Cleanup: remove everything when PJAX navigates away ── */
        function dlCleanup() {
            ['dl-fab','dl-modal-overlay','dl-spinner','admission-print-root','dl-fab-styles'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.parentNode.removeChild(el);
            });
            /* Remove style from head */
            var s = document.getElementById('dl-fab-styles');
            if (s) s.parentNode.removeChild(s);
            document.removeEventListener('keydown', escHandler);
        }

        /* Listen for the moment #tpc-content gets swapped by PJAX.
           We detect this by watching for our FAB's parent (body) to
           no longer contain our anchor element. */
        var anchorEl = document.getElementById('requirements') || document.getElementById('process');
        if (anchorEl && typeof MutationObserver !== 'undefined') {
            var observer = new MutationObserver(function() {
                if (!document.getElementById('dl-fab')) { observer.disconnect(); return; }
                /* If the admission content anchor is gone, we've navigated away */
                if (!document.getElementById('requirements') && !document.getElementById('process')) {
                    dlCleanup();
                    observer.disconnect();
                }
            });
            observer.observe(document.getElementById('tpc-content') || document.body, { childList: true, subtree: false });
        }

        /* ── Escape key handler ───────────────────────────── */
        function escHandler(e) { if (e.key === 'Escape') closeDlModal(); }
        document.addEventListener('keydown', escHandler);

    })();

    /* ── Data ──────────────────────────────────────────── */
    var TPC_ADMISSION = {
        schoolName: "Talibon Polytechnic College",
        date: "{{ now()->format('F j, Y') }}",
        logoUrl: "{{ asset('images/TPC-Logo.png') }}",
        sections: {
            @if ($sections->has('freshmen') && $sections['freshmen']->is_visible)
            freshmen: {
                label: @json($sections['freshmen']->label),
                items: @json($sections['freshmen']->items->pluck('title'))
            },
            @endif
            @if ($sections->has('transferee') && $sections['transferee']->is_visible)
            transferee: {
                label: @json($sections['transferee']->label),
                items: @json($sections['transferee']->items->pluck('title'))
            },
            @endif
            @if ($sections->has('requirements_note') && $sections['requirements_note']->is_visible && $sections['requirements_note']->note)
            requirements_note: {
                label: @json($sections['requirements_note']->label),
                note:  @json($sections['requirements_note']->note)
            },
            @endif
            @if ($sections->has('process') && $sections['process']->is_visible)
            process: {
                label: @json($sections['process']->label),
                note:  @json($sections['process']->note ?? ''),
                items: @json($sections['process']->items->map(fn($i) => ['title' => $i->title, 'body' => $i->body]))
            },
            @endif
            @if ($sections->has('office_hours') && $sections['office_hours']->is_visible)
            office_hours: {
                label: @json($sections['office_hours']->label),
                note:  @json($sections['office_hours']->note ?? ''),
                items: @json($sections['office_hours']->items->map(fn($i) => ['title' => $i->title, 'body' => $i->body]))
            },
            @endif
        }
    };

    /* ── Modal open/close ──────────────────────────────── */
    function openDlModal() {
        document.getElementById('dl-modal-overlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeDlModal() {
        document.getElementById('dl-modal-overlay').classList.remove('open');
        document.body.style.overflow = '';
        document.getElementById('dl-note-none').classList.remove('visible');
    }
    function closeDlModalOutside(e) {
        if (e.target === document.getElementById('dl-modal-overlay')) closeDlModal();
    }

    /* ── Helpers ───────────────────────────────────────── */
    function getSelected() {
        var sel = {};
        document.querySelectorAll('.dl-toggle').forEach(function(lbl) {
            if (lbl.querySelector('input').checked) sel[lbl.dataset.section] = true;
        });
        return sel;
    }

    function esc(str) {
        return String(str||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    /* ── Build print HTML ──────────────────────────────── */
    function buildPrintHTML(selected) {
        var d = TPC_ADMISSION, html = '';
        html += '<div class="print-page">';
        html += '<div class="print-header">';
        html += '<div class="print-logo-row">';
        html += '<img src="'+d.logoUrl+'" alt="TPC Logo" style="width:44px;height:44px;object-fit:contain;flex-shrink:0;" />';
        html += '<div><h1>'+esc(d.schoolName)+'</h1><p>Enrollment &amp; Admission Information</p></div>';
        html += '</div>';
        var chips = [];
        if (selected.requirements) chips.push('Admission Requirements');
        if (selected.process)      chips.push('Enrollment Process');
        if (selected.office_hours) chips.push('Office Hours');
        if (chips.length) html += '<p class="print-date">Includes: '+chips.join(' &nbsp;·&nbsp; ')+' &nbsp;·&nbsp; Printed '+esc(d.date)+'</p>';
        html += '</div>';

        if (selected.requirements) {
            html += '<div class="print-section"><div class="print-section-title">Admission Requirements</div>';
            html += '<div class="print-req-grid">';
            ['freshmen','transferee'].forEach(function(k) {
                if (!d.sections[k]) return;
                var s = d.sections[k];
                html += '<div class="print-req-card"><div class="print-req-card-top">'+esc(s.label)+'</div><ul>';
                s.items.forEach(function(item) { html += '<li>'+esc(item)+'</li>'; });
                html += '</ul></div>';
            });
            html += '</div>';
            if (d.sections.requirements_note) {
                var rn = d.sections.requirements_note;
                html += '<div class="print-note"><strong>'+esc(rn.label)+'</strong>'+esc(rn.note)+'</div>';
            }
            html += '</div>';
        }

        if (selected.process && d.sections.process) {
            var p = d.sections.process;
            html += '<div class="print-section"><div class="print-section-title">'+esc(p.label)+'</div>';
            html += '<div class="print-steps">';
            p.items.forEach(function(step,i) {
                html += '<div class="print-step"><div class="print-step-num">'+(i+1)+'</div>';
                html += '<div class="print-step-body"><strong>'+esc(step.title)+'</strong>';
                if (step.body) html += '<p>'+esc(step.body)+'</p>';
                html += '</div></div>';
            });
            html += '</div>';
            if (p.note) html += '<div class="print-note">'+esc(p.note)+'</div>';
            html += '</div>';
        }

        if (selected.office_hours && d.sections.office_hours) {
            var oh = d.sections.office_hours;
            html += '<div class="print-section"><div class="print-section-title">'+esc(oh.label)+'</div>';
            html += '<div class="print-schedule">';
            oh.items.forEach(function(row) {
                html += '<div class="print-schedule-row"><span>'+esc(row.title)+'</span><span>'+esc(row.body)+'</span></div>';
            });
            html += '</div>';
            if (oh.note) html += '<div class="print-note">'+esc(oh.note)+'</div>';
            html += '</div>';
        }

        html += '<div class="print-footer">Talibon Polytechnic College &nbsp;·&nbsp; Official Admission Information &nbsp;·&nbsp; '+esc(d.date)+'</div>';
        html += '</div>';
        return html;
    }

    /* ── Print ─────────────────────────────────────────── */
    function doPrint() {
        var selected = getSelected();
        if (!Object.keys(selected).length) { document.getElementById('dl-note-none').classList.add('visible'); return; }
        var root = document.getElementById('admission-print-root');
        root.innerHTML = buildPrintHTML(selected);
        root.style.cssText = 'display:block;position:static;left:auto;top:auto;width:auto;';
        closeDlModal();
        setTimeout(function() {
            window.print();
            setTimeout(function() {
                root.style.cssText = 'display:none;position:absolute;left:-9999px;top:0;width:800px;';
            }, 500);
        }, 80);
    }

    /* ── Save as image ─────────────────────────────────── */
    function doSaveImage() {
        var selected = getSelected();
        if (!Object.keys(selected).length) { document.getElementById('dl-note-none').classList.add('visible'); return; }
        closeDlModal();
        document.getElementById('dl-spinner').classList.add('visible');

        function runCapture() {
            var root = document.getElementById('admission-print-root');
            root.innerHTML = buildPrintHTML(selected);
            root.style.cssText = 'display:block;position:absolute;left:-9999px;top:0;width:820px;background:#fff;padding:0;font-family:Segoe UI,system-ui,sans-serif;color:#111827;';
            applyPrintStyles(root);
            setTimeout(function() {
                html2canvas(root, {
                    scale:2, useCORS:true, allowTaint:false,
                    backgroundColor:'#ffffff', logging:false,
                    width:820, windowWidth:820,
                    onclone:function(cloned){ applyPrintStyles(cloned.getElementById('admission-print-root')); }
                }).then(function(canvas) {
                    document.getElementById('dl-spinner').classList.remove('visible');
                    root.style.cssText = 'display:none;position:absolute;left:-9999px;top:0;width:800px;';
                    var chips = [];
                    if (selected.requirements) chips.push('requirements');
                    if (selected.process)      chips.push('process');
                    if (selected.office_hours) chips.push('office-hours');
                    var link = document.createElement('a');
                    link.download = 'TPC-Admission-'+chips.join('-')+'.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                }).catch(function(err) {
                    document.getElementById('dl-spinner').classList.remove('visible');
                    root.style.cssText = 'display:none;position:absolute;left:-9999px;top:0;width:800px;';
                    console.error(err);
                    alert('Image generation failed. Please try Print instead.');
                });
            }, 150);
        }

        if (window.html2canvas) { runCapture(); }
        else {
            var s = document.createElement('script');
            s.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
            s.onload = runCapture;
            s.onerror = function() {
                document.getElementById('dl-spinner').classList.remove('visible');
                alert('Could not load image library. Please check your connection.');
            };
            document.head.appendChild(s);
        }
    }

    /* ── Apply inline styles for html2canvas ──────────── */
    function applyPrintStyles(root) {
        if (!root) return;
        var styleMap = {
            '.print-page':            'font-family:Segoe UI,system-ui,sans-serif;max-width:780px;margin:0 auto;padding:36px 36px 28px;color:#111827;background:#fff;',
            '.print-header':          'background:#166534;color:#fff;padding:28px 36px 22px;border-radius:12px;margin-bottom:28px;',
            '.print-logo-row':        'display:flex;align-items:center;gap:14px;margin-bottom:10px;',
            '.print-header h1':       'font-size:1.35rem;font-weight:800;margin:0;letter-spacing:-.02em;color:#fff;',
            '.print-header p':        'font-size:.78rem;color:rgba(255,255,255,.72);margin:4px 0 0;',
            '.print-date':            'font-size:.68rem;color:rgba(255,255,255,.55);margin-top:12px;',
            '.print-section':         'margin-bottom:26px;',
            '.print-section-title':   'font-size:.65rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#166534;display:flex;align-items:center;gap:8px;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid #dcfce7;',
            '.print-req-grid':        'display:grid;grid-template-columns:1fr 1fr;gap:14px;',
            '.print-req-card':        'border:1.5px solid #d1fae5;border-radius:10px;overflow:hidden;',
            '.print-req-card-top':    'background:#166534;color:#fff;font-size:.65rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;padding:7px 14px;',
            '.print-req-card ul':     'list-style:none;padding:12px 14px;margin:0;',
            '.print-req-card li':     'display:flex;align-items:flex-start;gap:8px;font-size:.78rem;color:#374151;padding:4px 0;line-height:1.4;',
            '.print-note':            'background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:8px;padding:10px 14px;margin-top:12px;font-size:.74rem;color:#166534;line-height:1.5;',
            '.print-note strong':     'display:block;font-size:.62rem;text-transform:uppercase;letter-spacing:.07em;margin-bottom:3px;',
            '.print-steps':           'display:flex;flex-direction:column;gap:0;border:1.5px solid #d1fae5;border-radius:10px;overflow:hidden;',
            '.print-step':            'display:flex;align-items:flex-start;gap:14px;padding:11px 16px;border-bottom:1px solid #f0fdf4;background:#fff;',
            '.print-step-num':        'flex-shrink:0;width:28px;height:28px;border-radius:50%;background:#166534;color:#fff;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800;',
            '.print-step-body':       'flex:1;padding-top:3px;',
            '.print-step-body strong':'display:block;font-size:.8rem;font-weight:700;color:#111827;',
            '.print-step-body p':     'font-size:.74rem;color:#6b7280;margin:2px 0 0;line-height:1.4;',
            '.print-schedule':        'border:1.5px solid #d1fae5;border-radius:10px;overflow:hidden;',
            '.print-schedule-row':    'display:flex;justify-content:space-between;align-items:center;padding:9px 16px;border-bottom:1px solid #f0fdf4;font-size:.78rem;background:#fff;',
            '.print-footer':          'margin-top:32px;padding-top:14px;border-top:1px solid #e5e7eb;font-size:.65rem;color:#9ca3af;text-align:center;',
        };
        Object.keys(styleMap).forEach(function(sel) {
            try { root.querySelectorAll(sel).forEach(function(el){ el.style.cssText += ';'+styleMap[sel]; }); } catch(e){}
        });
        root.querySelectorAll('.print-logo-row img').forEach(function(img) {
            img.style.cssText += ';width:44px;height:44px;object-fit:contain;flex-shrink:0;display:block;';
            img.setAttribute('crossorigin','anonymous');
        });
        root.querySelectorAll('.print-section-title').forEach(function(el) {
            if (!el.querySelector('.print-before-bar')) {
                var bar = document.createElement('span');
                bar.className = 'print-before-bar';
                bar.style.cssText = 'display:inline-block;width:4px;height:16px;background:#166534;border-radius:2px;flex-shrink:0;';
                el.insertBefore(bar, el.firstChild);
            }
        });
        root.querySelectorAll('.print-req-card li').forEach(function(li) {
            if (!li.querySelector('.print-bullet')) {
                var dot = document.createElement('span');
                dot.className = 'print-bullet';
                dot.style.cssText = 'display:inline-block;flex-shrink:0;width:7px;height:7px;border-radius:50%;background:#16a34a;margin-top:5px;';
                li.insertBefore(dot, li.firstChild);
            }
        });
    }

    /* ── Scroll buttons ────────────────────────────────── */
    function addDesktopHover(btn, on, off) {
        if (!btn) return;
        btn.addEventListener('mouseenter', function(){ Object.assign(btn.style, on); });
        btn.addEventListener('mouseleave', function(){ Object.assign(btn.style, off); });
    }
    addDesktopHover(document.getElementById('btn-requirements'),
        { backgroundColor:'var(--color-tpc-secondary,#166534)', borderColor:'var(--color-tpc-secondary,#166534)', color:'#fff' },
        { backgroundColor:'#fff', borderColor:'#fff', color:'' }
    );
    addDesktopHover(document.getElementById('btn-process'),
        { backgroundColor:'#fff', borderColor:'#fff', color:'var(--color-tpc-primary,#15803d)' },
        { backgroundColor:'', borderColor:'', color:'#fff' }
    );
    document.querySelectorAll('[data-scroll-btn]').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var target = document.querySelector(btn.getAttribute('href'));
            btn.style.transform = 'scale(0.93)'; btn.style.opacity = '0.65';
            setTimeout(function() {
                btn.style.transform = ''; btn.style.opacity = '';
                btn.style.backgroundColor = ''; btn.style.borderColor = ''; btn.style.color = '';
                if (target) target.scrollIntoView({ behavior:'smooth', block:'start' });
            }, 200);
        });
    });
    </script>

@endsection

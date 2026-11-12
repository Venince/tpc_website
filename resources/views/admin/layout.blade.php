<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} - TPC</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/TPC-Logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/TPC-Logo.png') }}">

    {{-- Inter (admin typeface). Falls back to the system UI font if the CDN is unreachable. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">

    <script>
        document.documentElement.classList.add('tpc-admin-init');
    </script>
    {{-- Shared drag-to-reorder helper (defined in <head> so inline page scripts inside <main> can call it, even on a hard load). --}}
    <script>
        window.tpcSortable = function (id, url, opts) {
            function build() {
                var el = document.getElementById(id);
                if (!el || typeof Sortable === 'undefined') return;
                if (el._sortable) el._sortable.destroy();
                el._sortable = Sortable.create(el, Object.assign({
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'opacity-40',
                    onEnd: function () {
                        var order = Array.prototype.map.call(el.querySelectorAll('[data-id]'), function (n) { return /^\d+$/.test(n.dataset.id) ? parseInt(n.dataset.id, 10) : n.dataset.id; });
                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ order: order })
                        }).then(function (r) {
                            if (!r.ok) throw new Error('HTTP ' + r.status);
                            if (window.tpcToast) window.tpcToast('success', 'New order saved.');
                        }).catch(function () {
                            if (window.tpcToast) window.tpcToast('error', 'Could not save the new order. Refresh and try again.');
                        });
                    }
                }, opts || {}));
            }
            if (typeof Sortable !== 'undefined') { build(); return; }
            var waited = 0;
            var t = setInterval(function () {
                waited += 100;
                if (typeof Sortable !== 'undefined') { clearInterval(t); build(); }
                else if (waited > 8000) { clearInterval(t); }
            }, 100);
        };
    </script>
    {{-- Client-side list filter used by index tables: x-data="tpcFilter()", rows carry data-search / data-status. --}}
    <script>
        window.tpcFilter = function (extra) {
            var base = {
                q: '', status: '',
                matches: function (el) {
                    var hay = (el.dataset.search || '').toLowerCase();
                    var okQ = !this.q || hay.indexOf(this.q.toLowerCase().trim()) !== -1;
                    var okS = !this.status || el.dataset.status === this.status;
                    return okQ && okS;
                },
                get active() { return this.q.trim() !== '' || this.status !== ''; },
                get none() {
                    if (!this.active) return false;
                    var self = this;
                    return !Array.prototype.some.call(this.$root.querySelectorAll('[data-search]'), function (el) { return self.matches(el); });
                },
                reset: function () { this.q = ''; this.status = ''; }
            };
            // Merge page-specific state while preserving getters (Object.assign would evaluate them).
            if (extra) Object.defineProperties(base, Object.getOwnPropertyDescriptors(extra));
            return base;
        };
    </script>
    {{-- Order matters: admin.css must load after app.css so component classes win over base form resets. --}}
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
</head>

<body class="ui-app h-full overflow-hidden">
    <a href="#tpc-admin-main"
       class="sr-only focus:not-sr-only focus:fixed focus:left-3 focus:top-3 focus:z-[100] focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-brand-dark focus:shadow-ui-lg">
        Skip to main content
    </a>

    {{--
      Shell state.
      · Off-canvas drawer below 768px (md). Static sidebar from md up.
      · Collapsed preference is remembered; first visit on tablet widths starts collapsed.
    --}}
    <div class="relative flex overflow-hidden" style="height:100vh;height:100dvh"
         x-data="{
            sidebarCollapsed: (() => {
                const saved = localStorage.getItem('tpcSidebarCollapsed');
                if (saved !== null) return saved === 'true';
                return window.matchMedia('(max-width: 1023px)').matches;
            })(),
            mobileSidebarOpen: false,
            tip: { show: false, text: '', top: 0, left: 0 },
            isDesktop() { return window.matchMedia('(min-width: 768px)').matches; },
            init() {
                const mq = window.matchMedia('(min-width: 768px)');
                const handler = () => { if (mq.matches) this.mobileSidebarOpen = false; };
                handler();
                mq.addEventListener?.('change', handler);
            },
            toggleSidebar() {
                if (!this.isDesktop()) { this.mobileSidebarOpen = !this.mobileSidebarOpen; return; }
                this.sidebarCollapsed = !this.sidebarCollapsed;
                localStorage.setItem('tpcSidebarCollapsed', this.sidebarCollapsed ? 'true' : 'false');
                this.tip.show = false;
            },
            closeMobileSidebar() { this.mobileSidebarOpen = false; },
            showTip(el, text) {
                if (!this.sidebarCollapsed || !this.isDesktop()) return;
                const r = el.getBoundingClientRect();
                this.tip = { show: true, text, top: r.top + r.height / 2, left: r.right + 10 };
            },
            hideTip() { this.tip.show = false; }
         }"
         @keydown.escape.window="mobileSidebarOpen = false">

        {{-- Mobile overlay --}}
        <div x-cloak x-show="mobileSidebarOpen" x-transition.opacity.duration.200ms
             class="fixed inset-0 z-40 bg-gray-900/40 md:hidden" @click="closeMobileSidebar()" aria-hidden="true"></div>

        {{-- Collapsed-sidebar tooltip (fixed + outside <aside> so it is never clipped) --}}
        <div x-cloak x-show="tip.show" x-transition.opacity.duration.100ms
             :style="`top:${tip.top}px;left:${tip.left}px`"
             class="pointer-events-none fixed z-[80] -translate-y-1/2 whitespace-nowrap rounded-md bg-gray-900 px-2.5 py-1.5 text-xs font-medium text-white shadow-ui-lg"
             role="tooltip" x-text="tip.text"></div>

        @include('admin.partials.sidebar')

        {{-- Right column: header + the only scrolling region --}}
        <div class="flex h-full min-w-0 flex-1 flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => $title ?? null])

            <main id="tpc-admin-main" class="ui-scroll flex-1 overflow-y-auto focus:outline-none" tabindex="-1">
                <div class="w-full px-4 py-5 sm:px-6 sm:py-6 lg:px-8 2xl:px-10">

                    {{-- Flash messages become toasts (see script below). Kept in the DOM so PJAX swaps can replay them. --}}
                    @php
                        $statusMessages = [
                            'profile-updated'        => 'Your profile has been updated.',
                            'password-updated'       => 'Your password has been updated.',
                            'verification-link-sent' => 'A new verification link has been sent to your email address.',
                        ];
                    @endphp
                    @foreach (['success', 'status', 'error', 'warning'] as $flashKey)
                        @php $flashVal = session($flashKey); @endphp
                        @if (is_string($flashVal) && $flashVal !== '')
                            <div hidden data-flash
                                 data-type="{{ $flashKey === 'error' ? 'error' : ($flashKey === 'warning' ? 'warning' : 'success') }}"
                                 data-message="{{ $flashKey === 'status' ? ($statusMessages[$flashVal] ?? $flashVal) : $flashVal }}"></div>
                        @endif
                    @endforeach

                    {{-- Validation summary --}}
                    @if ($errors->any())
                        <x-admin.alert type="error" title="Please fix the following" class="mb-6">
                            <ul class="list-disc space-y-0.5 pl-5">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </x-admin.alert>
                    @endif

                    @hasSection('page_actions')
                        <div class="mb-6 flex flex-wrap items-center gap-2">@yield('page_actions')</div>
                    @endif

                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                </div>
            </main>
        </div>

        {{-- Global confirmation dialog (forms opt in with data-confirm="…") --}}
        <div x-data="{
                open: false, title: '', message: '', label: 'Delete', danger: true, form: null, callback: null,
                ask(d) {
                    this.title = d.title; this.message = d.message; this.label = d.label; this.danger = d.danger;
                    this.form = d.form || null; this.callback = d.onConfirm || null; this.open = true;
                    this.$nextTick(() => this.$refs.cancel?.focus());
                },
                close() { this.open = false; this.form = null; this.callback = null; },
                accept() {
                    const f = this.form, cb = this.callback; this.close();
                    if (cb) { cb(); return; }
                    if (f) { f.dataset.confirmed = '1'; f.requestSubmit(); delete f.dataset.confirmed; }
                }
             }"
             @confirm-action.window="ask($event.detail)"
             @keydown.escape.window="open && close()"
             @keydown.tab="if (open) { $event.preventDefault(); ($refs.cancel === document.activeElement ? $refs.accept : $refs.cancel).focus(); }">
            <div x-cloak x-show="open" class="fixed inset-0 z-[90] flex items-end justify-center p-0 sm:items-center sm:p-6"
                 role="alertdialog" aria-modal="true" aria-labelledby="confirm-title" aria-describedby="confirm-message">
                <div x-show="open" x-transition.opacity.duration.150ms class="absolute inset-0 bg-gray-900/40" @click="close()" aria-hidden="true"></div>
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-95"
                     class="relative w-full rounded-t-2xl border border-ui-line bg-white p-5 shadow-ui-lg sm:max-w-md sm:rounded-xl">
                    <div class="flex items-start gap-4">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full"
                              :class="danger ? 'bg-red-50 text-red-600' : 'bg-brand-50 text-brand'">
                            <x-admin.icon name="warning" class="h-5 w-5" />
                        </span>
                        <div class="min-w-0">
                            <h3 id="confirm-title" class="text-base font-semibold text-ui-ink" x-text="title"></h3>
                            <p id="confirm-message" class="mt-1 text-sm text-ui-muted" x-text="message"></p>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                        <button type="button" x-ref="cancel" class="ui-btn ui-btn-secondary" @click="close()">Cancel</button>
                        <button type="button" x-ref="accept" class="ui-btn"
                                :class="danger ? 'ui-btn-danger-solid' : 'ui-btn-primary'"
                                @click="accept()" x-text="label"></button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Toast host --}}
        <div x-data="{
                toasts: [], n: 0,
                add(d) {
                    const id = ++this.n;
                    this.toasts.push({ id, type: d.type || 'success', message: d.message || '' });
                    setTimeout(() => this.remove(id), d.type === 'error' ? 9000 : 5000);
                },
                remove(id) { this.toasts = this.toasts.filter(t => t.id !== id); }
             }"
             @toast.window="add($event.detail)"
             class="pointer-events-none fixed inset-x-0 bottom-0 z-[95] flex flex-col items-center gap-2 p-4 sm:inset-x-auto sm:right-0 sm:items-end"
             aria-live="polite" aria-atomic="false">
            <template x-for="t in toasts" :key="t.id">
                <div x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-lg border border-ui-line bg-white p-3.5 shadow-ui-lg"
                     role="status">
                    <span class="mt-0.5 shrink-0" :class="t.type === 'error' ? 'text-red-600' : (t.type === 'warning' ? 'text-amber-600' : 'text-brand')">
                        <x-admin.icon name="check-circle" class="h-5 w-5" x-show="t.type === 'success'" />
                        <x-admin.icon name="x-circle" class="h-5 w-5" x-show="t.type === 'error'" />
                        <x-admin.icon name="warning" class="h-5 w-5" x-show="t.type === 'warning'" />
                    </span>
                    <p class="min-w-0 flex-1 text-sm text-ui-ink" x-text="t.message"></p>
                    <button type="button" class="-mr-1 -mt-1 rounded p-1 text-gray-400 hover:text-ui-ink focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand"
                            @click="remove(t.id)" aria-label="Dismiss notification">
                        <x-admin.icon name="x" class="h-4 w-4" />
                    </button>
                </div>
            </template>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

    {{-- Shell behaviours: confirm-before-submit, busy buttons, flash → toast. Layout is never swapped by PJAX, so this runs once. --}}
    <script>
        (function () {
            window.tpcToast = function (type, message) {
                window.dispatchEvent(new CustomEvent('toast', { detail: { type: type, message: message } }));
            };

            // 1) <form data-confirm="Message" data-confirm-title="…" data-confirm-label="Delete"> opens the dialog first.
            document.addEventListener('submit', function (e) {
                var f = e.target;
                if (!(f instanceof HTMLFormElement) || !f.dataset.confirm || f.dataset.confirmed === '1') return;
                e.preventDefault();
                e.stopImmediatePropagation();
                window.dispatchEvent(new CustomEvent('confirm-action', { detail: {
                    form: f,
                    title: f.dataset.confirmTitle || 'Are you sure?',
                    message: f.dataset.confirm,
                    label: f.dataset.confirmLabel || 'Delete',
                    danger: f.dataset.confirmDanger !== 'false'
                }}));
            }, true);

            // 2) Give the submit button a busy state (prevents double submits; reset on bfcache restore).
            document.addEventListener('submit', function (e) {
                var f = e.target;
                if (e.defaultPrevented || !(f instanceof HTMLFormElement) || f.dataset.ajax === 'true' || f.target) return;
                var btn = e.submitter || f.querySelector('button[type=submit]');
                if (btn) btn.setAttribute('aria-busy', 'true');
            });
            window.addEventListener('pageshow', function () {
                document.querySelectorAll('[aria-busy="true"]').forEach(function (b) { b.removeAttribute('aria-busy'); });
            });

            // 3) Flash holders inside <main> → toasts (initial load + every PJAX swap).
            var main = document.getElementById('tpc-admin-main');
            function flush() {
                if (!main) return;
                main.querySelectorAll('[data-flash]').forEach(function (el) {
                    if (el.dataset.done) return;
                    el.dataset.done = '1';
                    window.tpcToast(el.dataset.type, el.dataset.message);
                });
            }
            if (main) new MutationObserver(flush).observe(main, { childList: true });
            window.addEventListener('load', function () { setTimeout(flush, 50); });
        })();
    </script>

    @stack('scripts')
</body>
</html>

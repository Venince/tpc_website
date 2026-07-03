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

    <script>window._tpcStorageBase = '{{ asset('storage') }}';</script>

    <script>
        @if(request()->routeIs('home'))
        // Add class before first paint so the browser commits opacity:0 + translateY(28px)
        document.documentElement.classList.add('tpc-home-init');
        // After DOM is ready, force a reflow then remove the class to trigger the transition
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('tpc-content');
            if (el) {
                void el.offsetHeight; // force reflow — commits the start state
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        document.documentElement.classList.remove('tpc-home-init');
                    });
                });
            }
        });
        @endif
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

    {{-- Persistent gallery lightbox (Alpine store, works across PJAX) --}}
    <div x-data
        x-show="$store.gallery.isOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 backdrop-blur-sm p-4"
        @click.self="$store.gallery.close()"
        @keydown.escape.window="$store.gallery.close()"
        @keydown.arrow-left.window="$store.gallery.prev()"
        @keydown.arrow-right.window="$store.gallery.next()"
        style="display:none">

        <button @click="$store.gallery.close()" type="button"
                class="absolute top-4 right-4 z-10 h-9 w-9 rounded-full bg-white/10 hover:bg-white/20
                    text-white flex items-center justify-center transition">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="absolute top-4 left-1/2 -translate-x-1/2 z-10 bg-white/10 text-white text-xs font-bold px-3 py-1 rounded-full">
            <span x-text="$store.gallery.current + 1"></span> / <span x-text="$store.gallery.images.length"></span>
        </div>

        <button @click="$store.gallery.prev()" type="button" x-show="$store.gallery.images.length > 1"
                class="absolute left-3 sm:left-6 z-10 h-10 w-10 rounded-full bg-white/10 hover:bg-white/25
                    text-white flex items-center justify-center transition">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <img :src="$store.gallery.currentUrl"
            :alt="'Photo ' + ($store.gallery.current + 1)"
            class="max-h-[85vh] max-w-full rounded-xl object-contain shadow-2xl select-none"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

        <button @click="$store.gallery.next()" type="button" x-show="$store.gallery.images.length > 1"
                class="absolute right-3 sm:right-6 z-10 h-10 w-10 rounded-full bg-white/10 hover:bg-white/25
                    text-white flex items-center justify-center transition">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    {{-- Floating Feedback Widget --}}
    <div x-data="feedbackWidget()" class="fixed bottom-5 right-5 z-[9990]">

        {{-- Floating button --}}
        <button @click="open = true" x-show="!open" type="button" x-ref="fabBtn"
            :class="onDark
                ? 'bg-white text-tpc-primary shadow-lg shadow-black/25 hover:bg-tpc-accent ring-1 ring-black/5'
                : 'bg-tpc-primary text-white shadow-lg shadow-tpc-primary/30 hover:bg-tpc-secondary'"
            class="group flex items-center gap-2 rounded-full pl-4 pr-5 py-3 transition-colors duration-300
                focus:outline-none focus:ring-4 focus:ring-tpc-primary/20">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-6l-4 4v-4z"/>
            </svg>
            <span class="text-sm font-bold hidden sm:inline">Feedback</span>
        </button>

        {{-- Modal overlay --}}
        <div x-show="open" x-transition.opacity style="display:none"
            class="fixed inset-0 z-[9991] bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4"
            @click.self="close()" @keydown.escape.window="close()">

            <div x-show="open"
                x-transition:enter="transition ease-out duration-250"
                x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-8"
                @click.stop
                class="w-full sm:max-w-md bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

                <div class="h-1.5 bg-tpc-primary shrink-0"></div>

                {{-- Header --}}
                <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
                    <div>
                        <p class="text-[10px] font-bold text-tpc-primary uppercase tracking-widest">TPC Feedback</p>
                        <h3 class="text-lg font-bold text-gray-800 mt-0.5" x-text="submitted ? 'Thank you!' : 'Share your feedback'"></h3>
                    </div>
                    <button @click="close()" type="button" class="h-8 w-8 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="overflow-y-auto px-5 sm:px-6 py-5 flex-1">

                    {{-- Success state --}}
                    <template x-if="submitted">
                        <div class="text-center py-6">
                            <div class="mx-auto h-14 w-14 rounded-full bg-green-50 flex items-center justify-center mb-4">
                                <svg class="h-7 w-7 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500">Your feedback helps us improve. We appreciate you taking the time.</p>
                            <button @click="close()" type="button"
                                class="mt-5 inline-flex items-center rounded-full border-2 border-tpc-primary bg-tpc-primary px-5 py-2 text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                Close
                            </button>
                        </div>
                    </template>

                    {{-- Form --}}
                    <template x-if="!submitted">
                        <form @submit.prevent="submit()" class="space-y-5">

                            <input type="text" id="fb-widget-website" name="website" x-model="website" class="hidden" tabindex="-1" autocomplete="off">

                            {{-- Star rating --}}
                            <div>
                                <p class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2">
                                    How would you rate your experience? <span class="text-red-400">*</span>
                                </p>
                                <div class="flex gap-1.5" @mouseleave="hoverRating = 0">
                                    <template x-for="i in 5" :key="i">
                                        <button type="button" @click="rating = i" @mouseenter="hoverRating = i"
                                            class="transition-transform hover:scale-110 focus:outline-none">
                                            <svg class="h-9 w-9" :class="(hoverRating || rating) >= i ? 'text-amber-400' : 'text-gray-200'"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/>
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                                <p class="mt-1.5 text-xs font-semibold" :class="rating ? 'text-tpc-primary' : 'text-gray-300'" x-text="ratingLabel()"></p>
                            </div>

                            {{-- Category --}}
                            <div>
                                <label for="fb-widget-category" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2">Category</label>
                                <select id="fb-widget-category" name="category" x-model="category"
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-gray-800 focus:border-tpc-primary focus:ring-0 focus:outline-none transition">
                                    <option value="">General</option>
                                    @foreach(\App\Models\Feedback::CATEGORIES as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Message --}}
                            <div>
                                <label for="fb-widget-message" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2">Comments</label>
                                <textarea id="fb-widget-message" name="message" x-model="message" rows="3" placeholder="Tell us more (optional)..."
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-gray-800 focus:border-tpc-primary focus:ring-0 focus:outline-none transition resize-none placeholder-gray-300"></textarea>
                            </div>

                            {{-- Optional contact info --}}
                            <div>
                                <button type="button" @click="showContact = !showContact"
                                    class="flex items-center gap-1.5 text-xs font-bold text-tpc-primary hover:text-tpc-secondary transition">
                                    <svg class="h-3.5 w-3.5 transition-transform" :class="showContact ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    Add contact info (optional)
                                </button>
                                <div x-show="showContact" x-transition class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label for="fb-widget-name" class="sr-only">Your name</label>
                                        <input id="fb-widget-name" type="text" name="name" x-model="name" placeholder="Your name" autocomplete="name"
                                            class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-gray-800 focus:border-tpc-primary focus:ring-0 focus:outline-none transition placeholder-gray-300">
                                    </div>
                                    <div>
                                        <label for="fb-widget-email" class="sr-only">Your email</label>
                                        <input id="fb-widget-email" type="email" name="email" x-model="email" placeholder="Your email" autocomplete="email"
                                            class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-gray-800 focus:border-tpc-primary focus:ring-0 focus:outline-none transition placeholder-gray-300">
                                    </div>
                                </div>
                                <p class="mt-1.5 text-[11px] text-gray-400">Leave these blank to stay anonymous. We'll only use them if you'd like a reply.</p>
                            </div>

                            <p x-show="error" x-text="error" class="text-xs text-red-500 font-semibold"></p>
                        </form>
                    </template>
                </div>

                {{-- Footer --}}
                <template x-if="!submitted">
                    <div class="px-5 sm:px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between shrink-0">
                        <p class="text-[10px] text-gray-400">Anonymous by default</p>
                        <button @click="submit()" :disabled="loading || !rating" type="button"
                            class="inline-flex items-center gap-2 rounded-full border-2 border-tpc-primary bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition disabled:opacity-40 disabled:cursor-not-allowed">
                            <svg x-show="loading" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span x-text="loading ? 'Sending...' : 'Submit Feedback'"></span>
                        </button>
                    </div>
                </template>

            </div>
        </div>
    </div>

    <script>
        function feedbackWidget() {
            return {
                open: false, submitted: false, loading: false, error: '',
                rating: 0, hoverRating: 0, category: '', message: '',
                name: '', email: '', website: '', showContact: false,
                onDark: false,
                _rafPending: false,

                init() {
                    this._checkBg = this.checkBg.bind(this);
                    window.addEventListener('scroll', this._checkBg, { passive: true });
                    window.addEventListener('resize', this._checkBg);
                    this._checkBg();

                    // Re-check when PJAX swaps page content (sections/colors change)
                    const content = document.getElementById('tpc-content');
                    if (content && typeof MutationObserver !== 'undefined') {
                        new MutationObserver(() => setTimeout(this._checkBg, 80))
                            .observe(content, { childList: true, subtree: false });
                    }
                },

                checkBg() {
                    if (this._rafPending) return;
                    this._rafPending = true;
                    requestAnimationFrame(() => {
                        this._rafPending = false;
                        this._doCheckBg();
                    });
                },

                _doCheckBg() {
                    const btn = this.$refs.fabBtn;
                    if (!btn) return;

                    const rect = btn.getBoundingClientRect();
                    const points = [
                        [rect.left + rect.width / 2, rect.top + rect.height / 2],
                        [rect.left + rect.width / 2, rect.top - 4],
                        [rect.left + rect.width / 2, rect.bottom + 4],
                    ];

                    let color = null;

                    for (const [x, y] of points) {
                        let stack = [];
                        if (document.elementsFromPoint) {
                            stack = document.elementsFromPoint(x, y);
                        } else {
                            const el = document.elementFromPoint(x, y);
                            if (el) stack = [el];
                        }

                        let bgEl = stack.find(el => el !== btn && !btn.contains(el));

                        while (bgEl && bgEl !== document.documentElement) {
                            const bg = getComputedStyle(bgEl).backgroundColor;
                            const rgb = this.normalizeColor(bg);
                            if (rgb) { color = rgb; break; }
                            bgEl = bgEl.parentElement;
                        }

                        if (color) break;
                    }

                    if (!color) { this.onDark = false; return; }

                    const { r, g, b } = color;
                    const luminance = (0.2126 * r + 0.7152 * g + 0.0722 * b) / 255;
                    this.onDark = luminance < 0.55;
                },

                normalizeColor(colorStr) {
                    if (!colorStr || colorStr === 'transparent') return null;

                    if (!this._ctx) {
                        this._ctx = document.createElement('canvas').getContext('2d');
                    }
                    const ctx = this._ctx;

                    // Reset, then assign — invalid/transparent values leave fillStyle unchanged
                    ctx.fillStyle = '#000000';
                    ctx.fillStyle = colorStr;
                    const normalized = ctx.fillStyle; // always '#rrggbb' or 'rgba(r, g, b, a)'

                    let r, g, b, a = 1;

                    if (normalized[0] === '#') {
                        r = parseInt(normalized.slice(1, 3), 16);
                        g = parseInt(normalized.slice(3, 5), 16);
                        b = parseInt(normalized.slice(5, 7), 16);
                    } else {
                        const nums = normalized.match(/[\d.]+/g);
                        if (!nums || nums.length < 3) return null;
                        [r, g, b] = nums.map(Number);
                        if (nums.length >= 4) a = Number(nums[3]);
                    }

                    if (a < 0.4) return null; // treat near-transparent as "no background here"

                    return { r, g, b };
                },

                ratingLabel() {
                    const labels = { 0: 'Tap a star to rate', 1: 'Poor', 2: 'Fair', 3: 'Good', 4: 'Very Good', 5: 'Excellent' };
                    return labels[this.hoverRating || this.rating];
                },
                close() {
                    this.open = false;
                    if (this.submitted) setTimeout(() => this.reset(), 300);
                },
                reset() {
                    Object.assign(this, {
                        submitted: false, rating: 0, hoverRating: 0, category: '',
                        message: '', name: '', email: '', error: '', showContact: false,
                    });
                },
                async submit() {
                    this.error = '';
                    if (!this.rating) { this.error = 'Please select a star rating.'; return; }
                    this.loading = true;
                    try {
                        const res = await fetch('{{ route('feedback.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                rating: this.rating, category: this.category, message: this.message,
                                name: this.name, email: this.email, website: this.website,
                                page_url: window.location.href,
                            }),
                        });
                        if (!res.ok) {
                            const data = await res.json().catch(() => ({}));
                            throw new Error(data.message || 'Something went wrong. Please try again.');
                        }
                        this.submitted = true;
                    } catch (e) {
                        this.error = e.message || 'Something went wrong. Please try again.';
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }
    </script>

    @stack('portal')  {{-- FABs, modals, overlays that need true viewport-fixed positioning --}}

    @stack('scripts')


</body>
</html>

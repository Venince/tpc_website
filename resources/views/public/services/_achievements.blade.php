{{--
    Public "Achievements" card (sidebar).
    Usage: @include('public.services._achievements', ['service' => $service])
    Renders nothing when the service has no achievements.

    Sidebar: compact rows (thumbnail, year, title, 2-line preview). The first 3 are
    shown; the rest sit behind "Show all".
    Tap a row: opens a full-screen viewer (bottom-to-top on phones, two-column on
    desktop) with the whole photo gallery (arrows, thumbnails, keyboard, swipe)
    and the complete description.
--}}
@php
    $achievements   = $service->achievements;
    $initialVisible = 3;
@endphp

@if ($achievements->isNotEmpty())
    <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-md" x-data="{ showAll: false }">

        @once
            <style>
                .ach-clamp-2 {
                    display: -webkit-box;
                    -webkit-box-orient: vertical;
                    -webkit-line-clamp: 2;
                    overflow: hidden;
                }
                .ach-wrap {
                    overflow-wrap: anywhere;
                    word-break: break-word;
                }
                .ach-thumbs { scrollbar-width: thin; }
            </style>
        @endonce

        {{-- Header --}}
        <div class="flex items-center justify-between gap-3 border-b border-gray-100 px-5 py-4">
            <p class="text-xs font-bold uppercase tracking-widest text-tpc-primary">Achievements</p>
            <span class="rounded-full bg-tpc-primary/10 px-2 py-0.5 text-[11px] font-bold text-tpc-primary">
                {{ $achievements->count() }}
            </span>
        </div>

        <ul class="divide-y divide-gray-100">
            @foreach ($achievements as $achievement)
                @php
                    $urls  = $achievement->images->map(fn ($img) => asset('storage/' . $img->path))->values()->all();
                    $count = count($urls);

                    $panelClass = $count > 0
                        ? 'h-full sm:h-auto sm:max-h-[90vh] sm:max-w-5xl sm:rounded-3xl lg:h-[min(85vh,720px)] lg:flex-row'
                        : 'max-h-[88vh] rounded-t-3xl sm:max-w-xl sm:rounded-3xl';
                @endphp

                <li x-data="{
                        open: false,
                        current: 0,
                        touchX: null,
                        images: @js($urls),
                        openAt(i) { this.current = i; this.open = true; document.body.style.overflow = 'hidden'; },
                        close()   { this.open = false; document.body.style.overflow = ''; },
                        prev()    { this.current = (this.current - 1 + this.images.length) % this.images.length; },
                        next()    { this.current = (this.current + 1) % this.images.length; },
                        swipe(e)  {
                            if (this.touchX === null) return;
                            const dx = e.changedTouches[0].clientX - this.touchX;
                            this.touchX = null;
                            if (Math.abs(dx) < 50 || this.images.length < 2) return;
                            dx < 0 ? this.next() : this.prev();
                        }
                    }"
                    x-init="$watch('current', (v) => {
                        const el = document.getElementById('ach-{{ $achievement->id }}-thumb-' + v);
                        if (el) el.scrollIntoView({ inline: 'center', block: 'nearest', behavior: 'smooth' });
                    })"
                    x-show="showAll || {{ $loop->index }} < {{ $initialVisible }}"
                    @keydown.escape.window="if (open) close()"
                    @keydown.arrow-left.window="if (open) prev()"
                    @keydown.arrow-right.window="if (open) next()">

                    {{-- ── Sidebar row ─────────────────────────────────────────── --}}
                    <button type="button" @click="openAt(0)"
                            class="group flex w-full items-start gap-3.5 p-4 text-left transition hover:bg-gray-50 focus:outline-none focus-visible:bg-gray-50 focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-tpc-primary/40 sm:p-5"
                            aria-label="View details of {{ $achievement->title }}">

                        {{-- Thumbnail --}}
                        <span class="relative h-[88px] w-[88px] shrink-0 overflow-hidden rounded-2xl bg-gradient-to-br from-tpc-primary/15 to-tpc-primary/5 ring-1 ring-gray-100 sm:h-24 sm:w-24">
                            @if ($count > 0)
                                <img src="{{ $urls[0] }}" alt="" loading="lazy"
                                     class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @if ($count > 1)
                                    <span class="absolute bottom-1 right-1 inline-flex items-center gap-1 rounded-full bg-black/65 px-1.5 py-0.5 text-[10px] font-bold leading-none text-white">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                        </svg>
                                        {{ $count }}
                                    </span>
                                @endif
                            @else
                                <span class="flex h-full w-full items-center justify-center text-3xl" aria-hidden="true">🏆</span>
                            @endif
                        </span>

                        {{-- Text --}}
                        <span class="min-w-0 flex-1">
                            @if ($achievement->year)
                                <span class="mb-1 inline-block rounded-full bg-tpc-primary/10 px-2.5 py-0.5 text-[11px] font-bold text-tpc-primary">
                                    {{ $achievement->year }}
                                </span>
                            @endif

                            <span class="ach-clamp-2 ach-wrap block text-sm font-bold leading-snug text-gray-900">
                                {{ $achievement->title }}
                            </span>

                            @if ($achievement->description)
                                <span class="ach-clamp-2 ach-wrap mt-1 block text-xs leading-relaxed text-gray-500">
                                    {{ $achievement->description }}
                                </span>
                            @endif

                            <span class="mt-2 inline-flex items-center gap-1 text-[11px] font-bold text-tpc-primary">
                                {{ $count > 0 ? 'View photos & details' : 'View details' }}
                                <svg class="h-3 w-3 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </span>
                    </button>

                    {{-- ── Viewer (moved to <body> so nothing can clip it) ─────── --}}
                    <template x-teleport="body">
                        <div x-show="open"
                             x-transition.opacity.duration.200ms
                             style="display:none"
                             class="fixed inset-0 z-[999] flex items-end justify-center bg-black/80 sm:items-center sm:p-6"
                             @click.self="close()"
                             role="dialog" aria-modal="true" aria-label="{{ $achievement->title }}">

                            <div class="relative flex w-full flex-col overflow-hidden bg-white shadow-2xl {{ $panelClass }}">

                                {{-- Close (top-right, always reachable) --}}
                                <button type="button" @click="close()" aria-label="Close"
                                        class="absolute right-3 top-3 z-20 rounded-full bg-gray-900/70 p-2 text-white transition hover:bg-gray-900">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>

                                @if ($count > 0)
                                    {{-- Photo stage --}}
                                    <div class="flex shrink-0 flex-col bg-gray-900 lg:h-full lg:w-[58%]">

                                        <div class="relative h-[40vh] shrink-0 sm:h-[46vh] lg:h-auto lg:min-h-0 lg:flex-1"
                                             @touchstart.passive="touchX = $event.changedTouches[0].clientX"
                                             @touchend.passive="swipe($event)">
                                            <img :src="images[current]"
                                                 alt="{{ $achievement->title }}"
                                                 draggable="false"
                                                 class="h-full w-full select-none object-contain">

                                            <button type="button" x-show="images.length > 1" @click="prev()" aria-label="Previous photo"
                                                    class="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-black/45 p-2.5 text-white transition hover:bg-black/70 sm:left-3">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                                </svg>
                                            </button>

                                            <button type="button" x-show="images.length > 1" @click="next()" aria-label="Next photo"
                                                    class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-black/45 p-2.5 text-white transition hover:bg-black/70 sm:right-3">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </button>

                                            <div x-show="images.length > 1"
                                                 class="absolute bottom-2 left-1/2 -translate-x-1/2 rounded-full bg-black/60 px-3 py-1 text-xs font-semibold tabular-nums text-white">
                                                <span x-text="current + 1"></span> / <span x-text="images.length"></span>
                                            </div>
                                        </div>

                                        @if ($count > 1)
                                            <div class="ach-thumbs flex shrink-0 gap-2 overflow-x-auto bg-black/60 p-2.5">
                                                @foreach ($urls as $i => $url)
                                                    <button type="button"
                                                            id="ach-{{ $achievement->id }}-thumb-{{ $i }}"
                                                            @click="current = {{ $i }}"
                                                            :class="current === {{ $i }} ? 'opacity-100 ring-2 ring-white' : 'opacity-50 hover:opacity-80'"
                                                            class="h-14 w-14 shrink-0 overflow-hidden rounded-lg transition sm:h-16 sm:w-16"
                                                            aria-label="Show photo {{ $i + 1 }} of {{ $count }}">
                                                        <img src="{{ $url }}" alt="" loading="lazy" class="h-full w-full object-cover">
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                {{-- Details --}}
                                <div class="flex min-h-0 flex-1 flex-col {{ $count > 0 ? 'lg:w-[42%]' : '' }}">
                                    <div class="min-h-0 flex-1 overflow-y-auto p-5 sm:p-6 {{ $count > 0 ? '' : 'pr-14' }}">
                                        @if ($achievement->year)
                                            <span class="mb-2 inline-block rounded-full bg-tpc-primary/10 px-3 py-1 text-xs font-bold text-tpc-primary">
                                                {{ $achievement->year }}
                                            </span>
                                        @endif

                                        <h3 class="ach-wrap text-lg font-extrabold leading-snug text-gray-900 sm:text-xl">
                                            {{ $achievement->title }}
                                        </h3>

                                        @if ($achievement->description)
                                            <p class="ach-wrap mt-3 whitespace-pre-line text-sm leading-relaxed text-gray-600">{{ $achievement->description }}</p>
                                        @endif
                                    </div>

                                    {{-- Phone-only close bar (easy thumb reach) --}}
                                    <div class="shrink-0 border-t border-gray-100 p-3 sm:hidden">
                                        <button type="button" @click="close()"
                                                class="flex h-11 w-full items-center justify-center rounded-full bg-tpc-primary text-sm font-bold text-white transition hover:bg-tpc-secondary">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </li>
            @endforeach
        </ul>

        {{-- Show all / Show less --}}
        @if ($achievements->count() > $initialVisible)
            <div class="border-t border-gray-100 p-3">
                <button type="button" @click="showAll = !showAll"
                        class="flex h-11 w-full items-center justify-center gap-1.5 rounded-full text-xs font-bold text-tpc-primary transition hover:bg-tpc-primary/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-tpc-primary/40">
                    <span x-text="showAll ? 'Show less' : 'Show all {{ $achievements->count() }} achievements'"></span>
                    <svg class="h-3.5 w-3.5 transition" :class="showAll ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>
            </div>
        @endif
    </div>
@endif

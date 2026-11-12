@extends('admin.layout', ['title' => 'Dashboard'])

@section('content')
    @php
        // Greeting based on time of day (Philippine Time = UTC+8)
        $hour = now()->setTimezone('Asia/Manila')->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
        $firstName = explode(' ', auth()->user()->name ?? 'Admin')[0];
        $isSuper = auth()->user()?->is_super_admin;

        $draftNewsCount = max(0, $newsCount - $publishedNewsCount - $pendingNewsCount);
        $pct = fn ($part, $whole) => $whole > 0 ? (int) round(($part / $whole) * 100) : 0;
    @endphp

    <x-admin.page-header title="Dashboard" :subtitle="$greeting . ', ' . $firstName . '. Here is what is happening on the TPC website.'">
        <x-admin.button :href="route('admin.news-posts.create')" icon="plus">New news post</x-admin.button>
    </x-admin.page-header>

    {{-- ── Key figures ─────────────────────────────────────────────────────── --}}
    <section aria-label="Key figures" class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 xl:grid-cols-5">
        <x-admin.stat-card label="Programs" :value="$programCount" icon="book-open" :href="route('admin.programs.index')">
            <x-slot:meta><span class="font-medium text-brand-dark">{{ $activeProgramCount }}</span> active</x-slot:meta>
        </x-admin.stat-card>

        <x-admin.stat-card label="News posts" :value="$newsCount" icon="newspaper" :href="route('admin.news-posts.index')">
            <x-slot:meta><span class="font-medium text-brand-dark">{{ $publishedNewsCount }}</span> published</x-slot:meta>
        </x-admin.stat-card>

        <x-admin.stat-card label="Services" :value="$serviceCount" icon="cog-8-tooth" :href="route('admin.services.index')">
            <x-slot:meta><span class="font-medium text-brand-dark">{{ $activeServiceCount }}</span> active</x-slot:meta>
        </x-admin.stat-card>

        <x-admin.stat-card label="Messages" :value="$messageCount" icon="chat" :href="route('admin.messages.index')">
            <x-slot:meta>
                @if ($unreadMessageCount > 0)
                    <span class="font-medium text-red-600">{{ $unreadMessageCount }}</span> unread
                @else
                    All read
                @endif
            </x-slot:meta>
        </x-admin.stat-card>

        <x-admin.stat-card label="Feedback" :value="$feedbackCount" icon="star" :href="route('admin.feedback.index')" class="col-span-2 sm:col-span-1">
            <x-slot:meta>
                @if ($feedbackCount > 0)
                    Avg. <span class="font-medium text-brand-dark">{{ $avgFeedbackRating }}</span> / 5
                @else
                    No feedback yet
                @endif
            </x-slot:meta>
        </x-admin.stat-card>
    </section>

    {{-- ── Activity + quick actions ────────────────────────────────────────── --}}
    <div class="mt-6 grid gap-4 lg:grid-cols-3">
        <x-admin.card title="Activity overview" description="New messages, feedback and news posts over the last 14 days" class="lg:col-span-2">
            <x-slot:actions>
                <ul class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-ui-muted" aria-label="Chart legend">
                    <li class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-brand"></span>News</li>
                    <li class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-ui-ink"></span>Messages</li>
                    <li class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-brand-light ring-1 ring-brand/40"></span>Feedback</li>
                </ul>
            </x-slot:actions>

            <div class="relative h-64">
                {{-- Skeleton shown until Chart.js has drawn --}}
                <div id="activityChartSkeleton" class="absolute inset-0 flex flex-col justify-end gap-3" aria-hidden="true">
                    <div class="ui-skeleton h-3 w-1/3"></div>
                    <div class="ui-skeleton h-40 w-full"></div>
                    <div class="ui-skeleton h-3 w-full"></div>
                </div>
                <canvas id="activityChart" role="img"
                        aria-label="Line chart of daily news posts, messages and feedback over the last 14 days"></canvas>
            </div>
            <p id="activityChartError" class="hidden text-sm text-ui-muted">The chart could not be loaded. Check your internet connection and refresh the page.</p>
        </x-admin.card>

        <x-admin.card title="Quick actions">
            <ul class="-my-1 divide-y divide-ui-line">
                @php
                    $actions = array_values(array_filter([
                        ['Write a news post', route('admin.news-posts.create'), 'newspaper', null],
                        ['Add a program', route('admin.programs.create'), 'book-open', null],
                        ['Add a service', route('admin.services.create'), 'cog-8-tooth', null],
                        ['Add an about slide', route('admin.about-slides.create'), 'photo', null],
                        $isSuper ? ['Review pending news', route('admin.news-review.index'), 'shield-check', $pendingNewsCount] : null,
                        ['Read messages', route('admin.messages.index'), 'chat', $unreadMessageCount],
                    ]));
                @endphp
                @foreach ($actions as [$label, $url, $icon, $count])
                    <li>
                        <a href="{{ $url }}" class="group -mx-2 flex items-center gap-3 rounded-lg px-2 py-2.5 text-sm font-medium text-ui-ink transition-colors hover:bg-ui-bg-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-brand-50 text-brand"><x-admin.icon :name="$icon" class="h-4 w-4" /></span>
                            <span class="min-w-0 flex-1 truncate">{{ $label }}</span>
                            @if ($count)
                                <x-admin.badge :type="$icon === 'chat' ? 'danger' : 'warning'">{{ $count }}</x-admin.badge>
                            @endif
                            <x-admin.icon name="chevron-right" class="h-4 w-4 text-gray-300 transition-colors group-hover:text-brand" />
                        </a>
                    </li>
                @endforeach
            </ul>
        </x-admin.card>
    </div>

    {{-- ── Recent content ──────────────────────────────────────────────────── --}}
    <div class="mt-4 grid gap-4 lg:grid-cols-2">
        <x-admin.card title="Recent news posts" :padding="false">
            <x-slot:actions>
                <x-admin.button :href="route('admin.news-posts.index')" variant="ghost" size="sm">View all</x-admin.button>
            </x-slot:actions>

            @forelse ($recentNews as $post)
                <a href="{{ route('admin.news-posts.edit', $post) }}"
                   class="flex items-center gap-3 border-b border-ui-line px-5 py-3 transition-colors last:border-b-0 hover:bg-ui-bg focus-visible:outline-none focus-visible:bg-ui-bg">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-ui-ink">{{ $post->title }}</p>
                        <p class="mt-0.5 truncate text-xs text-ui-muted">{{ $post->category ?: 'Uncategorized' }} · {{ $post->created_at->diffForHumans() }}</p>
                    </div>
                    @if ($post->is_published)
                        <x-admin.badge type="success" dot>Published</x-admin.badge>
                    @elseif ($post->status === 'pending')
                        <x-admin.badge type="warning" dot>Pending</x-admin.badge>
                    @elseif ($post->status === 'declined')
                        <x-admin.badge type="danger" dot>Declined</x-admin.badge>
                    @else
                        <x-admin.badge type="neutral" dot>Draft</x-admin.badge>
                    @endif
                </a>
            @empty
                <x-admin.empty-state icon="newspaper" title="No news posts yet" description="Published announcements will appear here.">
                    <x-admin.button :href="route('admin.news-posts.create')" icon="plus">Create the first post</x-admin.button>
                </x-admin.empty-state>
            @endforelse
        </x-admin.card>

        <x-admin.card title="Recent messages" :padding="false">
            <x-slot:actions>
                <x-admin.button :href="route('admin.messages.index')" variant="ghost" size="sm">View all</x-admin.button>
            </x-slot:actions>

            @forelse ($recentMessages as $msg)
                <a href="{{ route('admin.messages.show', $msg) }}"
                   class="flex items-center gap-3 border-b border-ui-line px-5 py-3 transition-colors last:border-b-0 hover:bg-ui-bg focus-visible:outline-none focus-visible:bg-ui-bg">
                    <span class="h-2 w-2 shrink-0 rounded-full {{ $msg->is_read ? 'bg-transparent' : 'bg-brand' }}" @unless($msg->is_read) title="Unread" @endunless></span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm {{ $msg->is_read ? 'text-ui-ink' : 'font-semibold text-ui-ink' }}">{{ $msg->subject ?: 'No subject' }}</p>
                        <p class="mt-0.5 truncate text-xs text-ui-muted">{{ $msg->name }} · {{ $msg->created_at->diffForHumans() }}</p>
                    </div>
                    @unless ($msg->is_read)
                        <x-admin.badge type="success">New</x-admin.badge>
                    @endunless
                </a>
            @empty
                <x-admin.empty-state icon="chat" title="No messages yet" description="Messages sent through the contact form will appear here." />
            @endforelse
        </x-admin.card>
    </div>

    {{-- ── Content health + feedback ───────────────────────────────────────── --}}
    <div class="mt-4 grid gap-4 lg:grid-cols-2">
        <x-admin.card title="Content status" description="Share of items currently live">
            <dl class="space-y-5">
                @foreach ([
                    ['Programs active', $activeProgramCount, $programCount, route('admin.programs.index')],
                    ['Services active', $activeServiceCount, $serviceCount, route('admin.services.index')],
                    ['News published', $publishedNewsCount, $newsCount, route('admin.news-posts.index')],
                ] as [$label, $part, $whole, $url])
                    <div>
                        <div class="flex items-baseline justify-between gap-3 text-sm">
                            <dt class="font-medium text-ui-ink"><a href="{{ $url }}" class="rounded hover:text-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand">{{ $label }}</a></dt>
                            <dd class="tabular-nums text-ui-muted"><span class="font-semibold text-ui-ink">{{ $part }}</span> of {{ $whole }}</dd>
                        </div>
                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-ui-bg-2" role="progressbar" aria-label="{{ $label }}"
                             aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $pct($part, $whole) }}">
                            <div class="h-full rounded-full bg-brand transition-[width] duration-700" style="width: {{ $pct($part, $whole) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </dl>
            @if ($pendingNewsCount > 0 || $draftNewsCount > 0)
                <p class="mt-5 border-t border-ui-line pt-4 text-xs text-ui-muted">
                    News not yet public:
                    <span class="font-medium text-ui-ink">{{ $pendingNewsCount }}</span> pending review ·
                    <span class="font-medium text-ui-ink">{{ $draftNewsCount }}</span> draft or declined
                </p>
            @endif
        </x-admin.card>

        <x-admin.card title="Feedback ratings">
            <x-slot:actions>
                <x-admin.button :href="route('admin.feedback.index')" variant="ghost" size="sm">View all</x-admin.button>
            </x-slot:actions>

            @if ($feedbackCount > 0)
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center">
                    <div class="shrink-0 text-center sm:w-28">
                        <p class="text-4xl font-semibold tracking-tight text-ui-ink tabular-nums">{{ $avgFeedbackRating }}</p>
                        <div class="mt-1.5 flex justify-center gap-0.5 text-brand" aria-hidden="true">
                            @for ($i = 1; $i <= 5; $i++)
                                <x-admin.icon name="star" :class="'h-4 w-4 ' . ($i <= round($avgFeedbackRating) ? 'fill-current' : 'text-gray-300')" />
                            @endfor
                        </div>
                        <p class="mt-1 text-xs text-ui-muted">{{ $feedbackCount }} {{ \Illuminate\Support\Str::plural('rating', $feedbackCount) }}@if($unreadFeedbackCount > 0) · <span class="font-medium text-red-600">{{ $unreadFeedbackCount }} unread</span>@endif</p>
                    </div>
                    <ul class="min-w-0 flex-1 space-y-2" aria-label="Rating distribution">
                        @for ($r = 5; $r >= 1; $r--)
                            @php $c = $ratingCounts[$r] ?? 0; @endphp
                            <li class="flex items-center gap-3 text-xs">
                                <span class="w-8 shrink-0 text-ui-muted tabular-nums">{{ $r }} ★</span>
                                <div class="h-2 flex-1 overflow-hidden rounded-full bg-ui-bg-2">
                                    <div class="h-full rounded-full bg-brand" style="width: {{ $pct($c, $feedbackCount) }}%"></div>
                                </div>
                                <span class="w-6 shrink-0 text-right font-medium text-ui-ink tabular-nums">{{ $c }}</span>
                            </li>
                        @endfor
                    </ul>
                </div>
            @else
                <x-admin.empty-state icon="star" title="No feedback yet" description="Ratings submitted by visitors will be summarized here." />
            @endif
        </x-admin.card>
    </div>

    {{-- Chart.js is loaded on demand. This script lives inside <main>, so the PJAX navigation re-runs it on every visit. --}}
    <script>
        (function () {
            var CHART_SRC = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js';

            function whenChartReady(cb, tries) {
                tries = tries || 0;
                if (window.Chart) { cb(); return; }
                if (tries === 0 && !document.querySelector('script[data-chartjs]')) {
                    var s = document.createElement('script');
                    s.src = CHART_SRC;
                    s.dataset.chartjs = '1';
                    document.head.appendChild(s);
                }
                if (tries >= 80) { fail(); return; }
                setTimeout(function () { whenChartReady(cb, tries + 1); }, 100);
            }

            function fail() {
                var sk = document.getElementById('activityChartSkeleton');
                var err = document.getElementById('activityChartError');
                var canvas = document.getElementById('activityChart');
                if (sk) sk.classList.add('hidden');
                if (canvas) canvas.classList.add('hidden');
                if (err) err.classList.remove('hidden');
            }

            function build() {
                var canvas = document.getElementById('activityChart'); // always re-query: PJAX swaps the DOM
                if (!canvas) return;
                var existing = window.Chart.getChart(canvas);
                if (existing) existing.destroy();

                var labels   = @json($activityChart['labels']);
                var news     = @json($activityChart['news']);
                var messages = @json($activityChart['messages']);
                var feedback = @json($activityChart['feedback']);

                var base = { tension: 0.3, borderWidth: 2, pointRadius: 0, pointHoverRadius: 4, pointHoverBorderWidth: 2, pointHoverBackgroundColor: '#fff' };

                new window.Chart(canvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            Object.assign({ label: 'News', data: news, borderColor: '#008000', backgroundColor: 'rgba(0,128,0,0.08)', fill: true }, base),
                            Object.assign({ label: 'Messages', data: messages, borderColor: '#1F2937', backgroundColor: 'transparent' }, base),
                            Object.assign({ label: 'Feedback', data: feedback, borderColor: '#5FCB5F', backgroundColor: 'transparent', borderDash: [5, 4] }, base)
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#FFFFFF', titleColor: '#1F2937', bodyColor: '#6B7280',
                                borderColor: '#E5E7EB', borderWidth: 1, padding: 10, boxPadding: 4,
                                titleFont: { size: 12, weight: '600' }, bodyFont: { size: 12 }
                            }
                        },
                        scales: {
                            x: { grid: { display: false }, border: { display: false },
                                 ticks: { color: '#6B7280', font: { size: 11 }, maxRotation: 0, autoSkip: true, maxTicksLimit: 7 } },
                            y: { beginAtZero: true, grid: { color: '#F1F5F1' }, border: { display: false },
                                 ticks: { color: '#6B7280', font: { size: 11 }, precision: 0 } }
                        }
                    }
                });

                var sk = document.getElementById('activityChartSkeleton');
                if (sk) sk.classList.add('hidden');
            }

            function init() {
                whenChartReady(function () {
                    try { build(); } catch (e) { console.error('[Activity Overview]', e); fail(); }
                });
            }

            // app.js calls window.initActivityChart() after each PJAX swap.
            window.initActivityChart = init;
            init();
        })();
    </script>
@endsection

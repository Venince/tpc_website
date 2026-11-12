@php
    $user = auth()->user();
    $isSuper = $user && $user->is_super_admin;

    // Live counters (same queries the previous sidebar used)
    $unreadMsgs     = \App\Models\ContactMessage::where('is_read', false)->count();
    $unreadFeedback = \App\Models\Feedback::where('is_read', false)->count();
    $pendingCount   = $isSuper ? \App\Models\NewsPost::pending()->count() : 0;

    /*
     | Navigation groups. Role gating is unchanged:
     |   · News Review and Users are super-admin only.
     |   · Users additionally requires the route to exist (as before).
     | Each item: label, route name, active pattern, icon, optional badge [count, tone].
     */
    $groups = [
        'Overview' => [
            ['Dashboard', 'admin.dashboard', 'admin.dashboard', 'squares', null],
        ],
        'Content' => array_values(array_filter([
            ['News Posts', 'admin.news-posts.index', 'admin.news-posts.*', 'newspaper', null],
            $isSuper ? ['News Review', 'admin.news-review.index', 'admin.news-review.*', 'shield-check', [$pendingCount, 'warning']] : null,
            ['About Slides', 'admin.about-slides.index', 'admin.about-slides.*', 'photo', null],
            ['Organizational Chart', 'admin.org-chart.index', 'admin.org-chart.*', 'org-chart', null],
        ])),
        'Academics' => [
            ['Programs', 'admin.programs.index', 'admin.programs.*', 'book-open', null],
            ['Admission', 'admin.admission.index', 'admin.admission.*', 'document-text', null],
            ['Services', 'admin.services.index', 'admin.services.*', 'cog-8-tooth', null],
        ],
        'Inbox' => [
            ['Messages', 'admin.messages.index', 'admin.messages.*', 'chat', [$unreadMsgs, 'danger']],
            ['Feedback', 'admin.feedback.index', 'admin.feedback.*', 'star', [$unreadFeedback, 'danger']],
        ],
        'System' => array_values(array_filter([
            ($isSuper && \Illuminate\Support\Facades\Route::has('admin.users.index'))
                ? ['Users', 'admin.users.index', 'admin.users.*', 'users', null] : null,
            ['Settings', 'admin.settings.edit', 'admin.settings.*', 'settings', null],
        ])),
    ];
@endphp
<aside x-cloak aria-label="Sidebar"
       class="fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] flex-col border-r border-ui-line bg-white
              transition-[transform,width] duration-200 ease-out md:static md:z-30 md:max-w-none md:translate-x-0"
       :class="[
           mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full',
           sidebarCollapsed ? 'md:w-[72px] ui-sidebar-collapsed' : 'md:w-64'
       ]">

    {{-- Brand --}}
    <div class="flex h-14 shrink-0 items-center gap-3 border-b border-ui-line px-4">
        <a href="{{ route('admin.dashboard') }}" @click="closeMobileSidebar()"
           class="flex min-w-0 flex-1 items-center gap-3 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand"
           :class="sidebarCollapsed ? 'md:justify-center' : ''">
            <img src="{{ asset('images/TPC-Logo.png') }}" alt="" class="h-8 w-8 shrink-0 object-contain">
            <span class="ui-nav-text min-w-0 leading-tight">
                <span class="block truncate text-sm font-semibold text-ui-ink">TPC Admin</span>
                <span class="block truncate text-xs text-ui-muted">Talibon Polytechnic College</span>
            </span>
        </a>
        <span class="md:hidden">
            <button type="button" class="ui-btn ui-btn-ghost ui-btn-icon ui-btn-sm" @click="closeMobileSidebar()" aria-label="Close sidebar">
                <x-admin.icon name="x" class="h-4 w-4" />
            </button>
        </span>
    </div>

    {{-- Navigation --}}
    <nav id="tpc-admin-nav" aria-label="Admin navigation" class="ui-scroll flex-1 space-y-5 overflow-y-auto px-3 py-4">
        @foreach ($groups as $groupLabel => $items)
            <div>
                <p class="ui-nav-label ui-nav-group-label">{{ $groupLabel }}</p>
                <div class="ui-nav-group-rule" aria-hidden="true"></div>
                <ul class="space-y-0.5">
                    @foreach ($items as [$label, $routeName, $pattern, $icon, $badge])
                        @php
                            $active = request()->routeIs($pattern);
                            $count  = $badge[0] ?? 0;
                            $tone   = $badge[1] ?? 'danger';
                        @endphp
                        <li>
                            <a href="{{ route($routeName) }}"
                               @click="closeMobileSidebar()"
                               @mouseenter="showTip($el, @js($label))" @mouseleave="hideTip()"
                               @focus="showTip($el, @js($label))" @blur="hideTip()"
                               class="ui-nav-item"
                               @if ($active) aria-current="page" @endif
                               aria-label="{{ $label }}{{ $count > 0 ? ', ' . $count . ' unread' : '' }}">
                                <x-admin.icon :name="$icon" class="ui-nav-icon" />
                                <span class="ui-nav-text truncate">{{ $label }}</span>
                                @if ($routeName === 'admin.messages.index')
                                    {{-- id lets the live unread-count updater in app.js refresh this badge --}}
                                    <span id="nav-messages-badge" class="ui-nav-badge ml-auto shrink-0 rounded-full bg-red-100 px-1.5 py-0.5 text-[11px] font-semibold leading-none text-red-700 {{ $count > 0 ? '' : 'hidden' }}">{{ $count > 99 ? '99+' : ($count > 0 ? $count : '') }}</span>
                                @elseif ($count > 0)
                                    <span class="ui-nav-badge ml-auto shrink-0 rounded-full px-1.5 py-0.5 text-[11px] font-semibold leading-none
                                                 {{ $tone === 'warning' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-700' }}">
                                        {{ $count > 9 ? '9+' : $count }}
                                    </span>
                                @endif
                                @if ($count > 0)
                                    <span class="ui-nav-dot absolute right-2 top-2 h-2 w-2 rounded-full ring-2 ring-white {{ $tone === 'warning' ? 'bg-amber-500' : 'bg-red-500' }}" aria-hidden="true"></span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>

    {{-- Footer: back to the public site --}}
    <div class="shrink-0 border-t border-ui-line p-3">
        <a href="{{ route('home') }}" data-no-pjax="true" @click="closeMobileSidebar()"
           @mouseenter="showTip($el, 'View website')" @mouseleave="hideTip()" @focus="showTip($el, 'View website')" @blur="hideTip()"
           class="ui-nav-item" aria-label="View website">
            <x-admin.icon name="globe" class="ui-nav-icon" />
            <span class="ui-nav-text truncate">View website</span>
            <x-admin.icon name="external" class="ui-nav-text ml-auto h-4 w-4 text-gray-300" />
        </a>
    </div>
</aside>

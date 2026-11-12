@php
    $pageTitle = $title ?? 'Admin';
    // Unread messages drive the notification bell (existing behaviour)
    $unreadMessages = \App\Models\ContactMessage::where('is_read', false)->count();
@endphp
{{-- NOTE: the PJAX script re-syncs `header h1` text after navigation, so keep one <h1> here. --}}
<header class="flex h-14 shrink-0 items-center justify-between gap-3 border-b border-ui-line bg-white px-4 sm:px-6">
    <div class="flex min-w-0 items-center gap-3">
        <button type="button" @click="toggleSidebar()"
                class="ui-btn ui-btn-ghost ui-btn-icon -ml-1"
                aria-label="Toggle sidebar" aria-controls="tpc-admin-nav"
                :aria-expanded="isDesktop() ? !sidebarCollapsed : mobileSidebarOpen">
            <x-admin.icon name="bars" class="h-5 w-5" />
        </button>
        <h1 class="truncate text-sm font-semibold text-ui-ink">{{ $pageTitle }}</h1>
    </div>

    <div class="flex items-center gap-1 sm:gap-2">
        <a href="{{ route('admin.messages.index') }}"
           class="ui-btn ui-btn-ghost ui-btn-icon relative"
           title="Messages{{ $unreadMessages > 0 ? " ($unreadMessages unread)" : '' }}"
           aria-label="Messages{{ $unreadMessages > 0 ? ", $unreadMessages unread" : '' }}">
            <x-admin.icon name="bell" class="h-5 w-5" />
            @if ($unreadMessages > 0)
                <span class="absolute -right-0.5 -top-0.5 flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-semibold leading-none text-white ring-2 ring-white">
                    {{ $unreadMessages > 9 ? '9+' : $unreadMessages }}
                </span>
            @endif
        </a>
        <span class="mx-1 hidden h-6 w-px bg-ui-line sm:block" aria-hidden="true"></span>
        <x-admin.profile-menu />
    </div>
</header>

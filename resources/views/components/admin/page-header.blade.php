@props([
    'title',
    'subtitle' => null,
    // Associative: ['Programs' => route('admin.programs.index'), 'Edit' => null]
    'breadcrumbs' => [],
])
{{-- Standard page header: breadcrumb → title/subtitle → primary actions (slot). --}}
<header {{ $attributes->class(['mb-6']) }}>
    @if (count($breadcrumbs))
        <nav aria-label="Breadcrumb" class="mb-2">
            <ol class="flex flex-wrap items-center gap-x-1.5 gap-y-1 text-xs text-ui-muted">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="rounded transition-colors hover:text-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand">Dashboard</a>
                </li>
                @foreach ($breadcrumbs as $label => $url)
                    <li class="flex items-center gap-1.5">
                        <x-admin.icon name="chevron-right" class="h-3 w-3 text-gray-300" />
                        @if ($url)
                            <a href="{{ $url }}"
                               class="rounded transition-colors hover:text-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand">{{ $label }}</a>
                        @else
                            <span aria-current="page" class="max-w-[16rem] truncate font-medium text-ui-ink">{{ $label }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <h2 class="text-xl font-semibold tracking-tight text-ui-ink sm:text-2xl">{{ $title }}</h2>
            @if ($subtitle)
                <p class="mt-1 text-sm text-ui-muted">{{ $subtitle }}</p>
            @endif
        </div>
        @if (isset($slot) && trim((string) $slot) !== '')
            <div class="flex shrink-0 flex-wrap items-center gap-2">
                {{ $slot }}
            </div>
        @endif
    </div>
</header>

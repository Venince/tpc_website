{{-- Admin paginator view. Use:  {{ $items->links('components.admin.pagination') }} --}}
@if ($paginator->hasPages())
    @php $hasTotal = method_exists($paginator, 'total'); @endphp
    <nav role="navigation" aria-label="Pagination"
         class="flex flex-col items-center justify-between gap-3 border-t border-ui-line px-4 py-3 sm:flex-row">
        <p class="text-xs text-ui-muted">
            @if ($hasTotal)
                Showing <span class="font-medium text-ui-ink">{{ $paginator->firstItem() }}</span>–<span class="font-medium text-ui-ink">{{ $paginator->lastItem() }}</span>
                of <span class="font-medium text-ui-ink">{{ $paginator->total() }}</span>
            @else
                Page <span class="font-medium text-ui-ink">{{ $paginator->currentPage() }}</span>
            @endif
        </p>

        <div class="flex items-center gap-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="ui-btn ui-btn-secondary ui-btn-sm opacity-50" aria-disabled="true"><x-admin.icon name="chevron-left" class="h-3.5 w-3.5" />Previous</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="ui-btn ui-btn-secondary ui-btn-sm"><x-admin.icon name="chevron-left" class="h-3.5 w-3.5" />Previous</a>
            @endif

            {{-- Numbered pages --}}
            @isset($elements)
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="hidden px-1 text-ui-muted sm:inline">…</span>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="hidden sm:inline-flex"><span aria-current="page" class="ui-btn ui-btn-primary ui-btn-sm min-w-[2rem]">{{ $page }}</span></span>
                            @else
                                <span class="hidden sm:inline-flex"><a href="{{ $url }}" aria-label="Go to page {{ $page }}" class="ui-btn ui-btn-secondary ui-btn-sm min-w-[2rem]">{{ $page }}</a></span>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endisset

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="ui-btn ui-btn-secondary ui-btn-sm">Next<x-admin.icon name="chevron-right" class="h-3.5 w-3.5" /></a>
            @else
                <span class="ui-btn ui-btn-secondary ui-btn-sm opacity-50" aria-disabled="true">Next<x-admin.icon name="chevron-right" class="h-3.5 w-3.5" /></span>
            @endif
        </div>
    </nav>
@endif

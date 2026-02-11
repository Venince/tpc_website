@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="w-full">

        {{-- Mobile --}}
        <div class="flex items-center justify-between gap-3 sm:hidden">

            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center rounded-full border border-tpc-primary/20 bg-white/70 px-4 py-2 text-sm font-semibold text-tpc-ink/40 shadow-sm backdrop-blur cursor-not-allowed">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex items-center justify-center rounded-full border border-tpc-primary/30 bg-white/70 px-4 py-2 text-sm font-semibold text-tpc-primary shadow-sm backdrop-blur
                          hover:bg-tpc-primary/10 hover:text-tpc-secondary
                          focus:outline-none focus-visible:ring-2 focus-visible:ring-tpc-primary/25 transition">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex items-center justify-center rounded-full border border-tpc-primary/30 bg-white/70 px-4 py-2 text-sm font-semibold text-tpc-primary shadow-sm backdrop-blur
                          hover:bg-tpc-primary/10 hover:text-tpc-secondary
                          focus:outline-none focus-visible:ring-2 focus-visible:ring-tpc-primary/25 transition">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex items-center justify-center rounded-full border border-tpc-primary/20 bg-white/70 px-4 py-2 text-sm font-semibold text-tpc-ink/40 shadow-sm backdrop-blur cursor-not-allowed">
                    {!! __('pagination.next') !!}
                </span>
            @endif

        </div>

        {{-- Desktop --}}
        <div class="hidden sm:flex sm:items-center sm:justify-between sm:gap-6">

            <div class="text-sm text-tpc-ink/70">
                {!! __('Showing') !!}
                @if ($paginator->firstItem())
                    <span class="font-semibold text-tpc-ink">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-semibold text-tpc-ink">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                {!! __('of') !!}
                <span class="font-semibold text-tpc-ink">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </div>

            <div class="flex items-center justify-end">
                {{-- pill container --}}
                <span class="inline-flex items-center gap-1 rounded-full border border-tpc-primary/20 bg-white/70 p-1 shadow-sm backdrop-blur">

                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}"
                              class="inline-flex h-9 w-9 items-center justify-center rounded-full text-tpc-ink/35 cursor-not-allowed">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}"
                           class="inline-flex h-9 w-9 items-center justify-center rounded-full text-tpc-primary
                                  hover:bg-tpc-primary/10 hover:text-tpc-secondary
                                  focus:outline-none focus-visible:ring-2 focus-visible:ring-tpc-primary/25 transition">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pages --}}
                    @foreach ($elements as $element)

                        @if (is_string($element))
                            <span class="inline-flex h-9 items-center justify-center px-2 text-sm font-semibold text-tpc-ink/50">
                                {{ $element }}
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page"
                                          class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-full px-3 text-sm font-semibold text-white
                                                 bg-tpc-primary shadow-sm ring-1 ring-tpc-primary/20">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                       class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-full px-3 text-sm font-semibold text-tpc-primary
                                              hover:bg-tpc-primary/10 hover:text-tpc-secondary
                                              focus:outline-none focus-visible:ring-2 focus-visible:ring-tpc-primary/25 transition">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif

                    @endforeach

                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}"
                           class="inline-flex h-9 w-9 items-center justify-center rounded-full text-tpc-primary
                                  hover:bg-tpc-primary/10 hover:text-tpc-secondary
                                  focus:outline-none focus-visible:ring-2 focus-visible:ring-tpc-primary/25 transition">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}"
                              class="inline-flex h-9 w-9 items-center justify-center rounded-full text-tpc-ink/35 cursor-not-allowed">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @endif

                </span>
            </div>

        </div>
    </nav>
@endif

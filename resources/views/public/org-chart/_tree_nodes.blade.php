{{-- Recursive partial: $nodes = Collection, $depth = int --}}
<div class="org-level">
    @php
        $rows = $nodes->groupBy('row')->sortKeys();
    @endphp

    @foreach ($rows as $rowNum => $rowNodes)
        <div class="org-siblings" data-depth="{{ $depth }}">
            @foreach ($rowNodes as $node)
                <div class="org-col">
                    <div class="org-col-vline-top"></div>

                    {{-- ── Card ── --}}
                    <div class="org-card" data-depth="{{ $depth }}">

                        <div class="org-photo-wrap">
                            {{--
                                loading="lazy" intentionally removed.
                                Lazy images don't fire load events reliably on first
                                PJAX navigation, which breaks connector drawing and
                                causes the layout-flash bug.
                            --}}
                            <img src="{{ $node->photoUrl() }}"
                                 alt="{{ $node->name }}"
                                 width="72"
                                 height="72">
                        </div>

                        <div class="org-text-block">
                            <p class="org-name">{{ $node->name }}</p>
                            <p class="org-title">{{ $node->title }}</p>
                            @if ($node->department)
                                <span class="org-dept">{{ $node->department }}</span>
                            @endif
                        </div>

                    </div>

                    @if ($node->allChildren->isNotEmpty())
                        <div class="org-vline"></div>
                        <div class="org-children-wrap">
                            @include('public.org-chart._tree_nodes', [
                                'nodes' => $node->allChildren,
                                'depth' => $depth + 1,
                            ])
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Vertical connector between rows --}}
        @if (!$loop->last)
            <div class="org-row-connector"></div>
        @endif
    @endforeach
</div>

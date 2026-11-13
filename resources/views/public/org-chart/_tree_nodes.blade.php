{{--
    Recursive partial.
    $nodes        Collection of OrgChartNode (already active + ordered)
    $depth        int
    $collapseFrom int   branches at this depth or deeper start collapsed (99 = never)
    $countNodes   Closure(Collection): int  — number of people below a node
    $parentName   string|null  name of the manager these nodes report to

    Output is a semantic nested <ul>. Connectors are pure CSS (no JS measuring), so the
    chart can be scaled/zoomed freely and never depends on image load timing.
--}}
@php $rows = $nodes->groupBy('row')->sortKeys(); @endphp

@foreach ($rows as $rowNum => $rowNodes)
    @php
        // A row made only of people without reports becomes one compact "team" panel
        // instead of a very wide line of cards.
        $isTeam = $depth >= 1 && $rowNodes->count() >= 3 && $rowNodes->every(fn ($n) => $n->allChildren->isEmpty());
        $cols   = $isTeam ? min(4, max(2, (int) ceil(sqrt($rowNodes->count())))) : null;
    @endphp

    <ul class="oc-children {{ $depth === 0 ? 'oc-roots' : '' }} {{ $isTeam ? 'oc-team' : '' }}"
        @if ($isTeam) style="--oc-cols: {{ $cols }}" @endif
        @if (! $loop->first && ! empty($parentName)) data-reports-to="{{ $parentName }}" @endif>
        @foreach ($rowNodes as $node)
            @php
                $kids      = $node->allChildren;
                $hasKids   = $kids->isNotEmpty();
                $below     = $hasKids ? $countNodes($kids) : 0;
                $collapsed = $hasKids && $depth >= $collapseFrom;
                $multiRow  = $hasKids && $kids->groupBy('row')->count() > 1;
            @endphp

            <li class="oc-node {{ $hasKids ? 'has-kids' : '' }} {{ $collapsed ? 'is-collapsed' : '' }}"
                data-depth="{{ $depth }}"
                data-search="{{ \Illuminate\Support\Str::lower(trim($node->name . ' ' . $node->title . ' ' . $node->department)) }}">

                <div class="oc-card" data-depth="{{ $depth }}">
                    <img class="oc-photo" src="{{ $node->photoUrl() }}" alt="" width="56" height="56" loading="lazy" decoding="async">

                    <div class="oc-text">
                        <p class="oc-name">{{ $node->name }}</p>
                        @if ($node->title)<p class="oc-title">{{ $node->title }}</p>@endif
                        @if ($node->department)<span class="oc-dept">{{ $node->department }}</span>@endif
                    </div>

                    @if ($hasKids)
                        <button type="button" class="oc-toggle"
                                data-count="{{ $below }}" data-name="{{ $node->name }}"
                                aria-expanded="{{ $collapsed ? 'false' : 'true' }}"
                                aria-label="{{ $collapsed ? 'Show' : 'Hide' }} {{ $below }} {{ $below === 1 ? 'person' : 'people' }} below {{ $node->name }}">
                            <span>{{ $below }}</span>
                            <svg viewBox="0 0 20 20" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 7.5 5 5 5-5"/></svg>
                        </button>
                    @endif
                </div>

                @if ($hasKids)
                    <div class="oc-branch {{ $multiRow ? 'has-rows' : '' }}">
                        @include('public.org-chart._tree_nodes', [
                            'nodes'        => $kids,
                            'depth'        => $depth + 1,
                            'collapseFrom' => $collapseFrom,
                            'countNodes'   => $countNodes,
                            'parentName'   => $node->name,
                        ])
                    </div>
                @endif
            </li>
        @endforeach
    </ul>

    @if (! $loop->last)
        <div class="oc-rowline" aria-hidden="true"></div>
    @endif
@endforeach

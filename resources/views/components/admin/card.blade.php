@props([
    'title'   => null,
    'description' => null,
    'padding' => true,   // false → flush content (tables, lists)
])
<section {{ $attributes->class(['ui-card']) }}>
    @if ($title || isset($actions))
        <div class="ui-card-header">
            <div class="min-w-0">
                @if ($title)<h3 class="ui-card-title">{{ $title }}</h3>@endif
                @if ($description)<p class="ui-card-desc">{{ $description }}</p>@endif
            </div>
            @isset($actions)
                <div class="flex shrink-0 flex-wrap items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    @endif
    <div @class(['ui-card-body' => $padding])>{{ $slot }}</div>
    @isset($footer)
        <div class="ui-card-footer">{{ $footer }}</div>
    @endisset
</section>

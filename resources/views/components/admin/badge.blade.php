@props([
    'type' => 'neutral',   // success | neutral | warning | danger
    'dot'  => false,
])
<span {{ $attributes->class(['ui-badge', 'ui-badge-' . $type]) }}>
    @if ($dot)<span class="ui-badge-dot" aria-hidden="true"></span>@endif
    {{ $slot }}
</span>

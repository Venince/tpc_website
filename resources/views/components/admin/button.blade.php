@props([
    'variant' => 'primary',   // primary | secondary | ghost | danger | danger-solid
    'size'    => null,        // null | sm
    'href'    => null,
    'type'    => 'button',
    'icon'    => null,        // icon name from x-admin.icon
])
@php
    $classes = 'ui-btn ui-btn-' . $variant . ($size === 'sm' ? ' ui-btn-sm' : '');
    $iconClass = $size === 'sm' ? 'h-3.5 w-3.5' : 'h-4 w-4';
@endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)<x-admin.icon :name="$icon" :class="$iconClass" />@endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)<x-admin.icon :name="$icon" :class="$iconClass" />@endif
        {{ $slot }}
    </button>
@endif

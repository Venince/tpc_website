@props([
    'type' => 'success',   // success | error | warning
    'title' => null,
])
@php
    $icon = ['success' => 'check-circle', 'error' => 'x-circle', 'warning' => 'warning'][$type] ?? 'info';
@endphp
<div {{ $attributes->class(['ui-alert', 'ui-alert-' . $type]) }} role="{{ $type === 'error' ? 'alert' : 'status' }}">
    <x-admin.icon :name="$icon" class="mt-0.5 h-5 w-5 shrink-0" />
    <div class="min-w-0 flex-1">
        @if ($title)<p class="font-semibold">{{ $title }}</p>@endif
        <div @class(['mt-1' => $title])>{{ $slot }}</div>
    </div>
</div>

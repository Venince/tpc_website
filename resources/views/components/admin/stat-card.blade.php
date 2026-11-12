@props([
    'label',
    'value',
    'icon'  => 'squares',
    'href'  => null,
])
@php $tag = $href ? 'a' : 'div'; @endphp
<{{ $tag }} @if ($href) href="{{ $href }}" @endif
    {{ $attributes->class([
        'group flex items-start justify-between gap-3 rounded-xl border border-ui-line bg-white p-4 shadow-ui-xs',
        'transition-colors hover:border-brand-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand' => $href,
    ]) }}>
    <div class="min-w-0">
        <p class="text-xs font-medium text-ui-muted">{{ $label }}</p>
        <p class="mt-1.5 text-2xl font-semibold leading-none tracking-tight text-ui-ink tabular-nums">{{ $value }}</p>
        @isset($meta)<p class="mt-2 text-xs text-ui-muted">{{ $meta }}</p>@endisset
    </div>
    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-brand-50 text-brand">
        <x-admin.icon :name="$icon" class="h-5 w-5" />
    </span>
</{{ $tag }}>

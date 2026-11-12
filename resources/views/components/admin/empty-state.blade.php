@props([
    'title',
    'description' => null,
    'icon' => 'squares',
])
<div {{ $attributes->class(['flex flex-col items-center justify-center px-6 py-12 text-center']) }}>
    <span class="grid h-12 w-12 place-items-center rounded-full bg-brand-50 text-brand">
        <x-admin.icon :name="$icon" class="h-6 w-6" />
    </span>
    <h3 class="mt-4 text-sm font-semibold text-ui-ink">{{ $title }}</h3>
    @if ($description)<p class="mt-1 max-w-sm text-sm text-ui-muted">{{ $description }}</p>@endif
    @if (isset($slot) && trim((string) $slot) !== '')
        <div class="mt-5 flex flex-wrap items-center justify-center gap-2">{{ $slot }}</div>
    @endif
</div>

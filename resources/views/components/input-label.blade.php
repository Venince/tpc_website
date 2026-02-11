@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-tpc-ink']) }}>
    {{ $value ?? $slot }}
</label>

@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-neo-ink/70']) }}>
    {{ $value ?? $slot }}
</label>

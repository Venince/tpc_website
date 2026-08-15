@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => '
            w-full rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-3 py-2.5 text-sm text-neo-ink
            placeholder:text-neo-ink/30
            focus:outline-none focus:ring-2 focus:ring-tpc-primary/30
            disabled:opacity-60 disabled:cursor-not-allowed
            transition
        '
    ]) }}
/>

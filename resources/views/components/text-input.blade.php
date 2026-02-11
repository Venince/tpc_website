@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => '
            w-full rounded-xl border border-tpc-primary/20 bg-white/70 px-3 py-2 text-sm text-tpc-ink
            shadow-sm backdrop-blur
            placeholder:text-tpc-ink/45
            focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/25
            disabled:opacity-60 disabled:cursor-not-allowed
            transition
        '
    ]) }}
/>

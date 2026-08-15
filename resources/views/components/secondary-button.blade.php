<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center justify-center rounded-xl bg-neo-surface px-4 py-2.5 text-sm font-semibold text-tpc-primary
                shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm
                focus:outline-none focus:ring-2 focus:ring-tpc-primary/25 focus:ring-offset-2'
]) }}>
    {{ $slot }}
</button>

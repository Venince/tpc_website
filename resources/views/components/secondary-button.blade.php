<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center justify-center rounded-xl border border-tpc-primary/25 bg-white/70 px-4 py-2 text-sm font-semibold text-tpc-primary
                shadow-sm transition hover:bg-white hover:shadow-md
                focus:outline-none focus:ring-2 focus:ring-tpc-primary/25 focus:ring-offset-2 active:scale-[0.98]'
]) }}>
    {{ $slot }}
</button>

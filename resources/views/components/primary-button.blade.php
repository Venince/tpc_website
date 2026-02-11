<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center justify-center rounded-xl bg-tpc-primary px-4 py-2 text-sm font-semibold text-white
                shadow-sm transition hover:bg-tpc-secondary
                focus:outline-none focus:ring-2 focus:ring-tpc-primary/40 focus:ring-offset-2 active:scale-[0.98]'
]) }}>
    {{ $slot }}
</button>

<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center justify-center rounded-xl bg-tpc-primary px-4 py-2.5 text-sm font-semibold text-white
                shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm
                focus:outline-none focus:ring-2 focus:ring-tpc-primary/40 focus:ring-offset-2'
]) }}>
    {{ $slot }}
</button>

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-red-500/10 border border-red-500/30 rounded-xl font-semibold text-xs text-red-200 uppercase tracking-widest hover:bg-red-500/20 active:bg-red-500/25 focus:outline-none focus:ring-2 focus:ring-red-500/60 focus:ring-offset-2 focus:ring-offset-accent-dark transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

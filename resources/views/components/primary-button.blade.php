<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-accent border border-transparent rounded-xl font-bold text-sm text-[#17110c] uppercase tracking-widest hover:brightness-105 focus:outline-none focus:ring-2 focus:ring-accent/70 focus:ring-offset-2 focus:ring-offset-accent-dark transition ease-in-out duration-150 w-full shadow-lg shadow-accent/20']) }}>
    {{ $slot }}
</button>

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-koya border border-transparent rounded-2xl font-bold text-sm text-white uppercase tracking-widest hover:bg-koya/80 focus:bg-koya/80 active:bg-koya focus:outline-none focus:ring-2 focus:ring-koya focus:ring-offset-2 focus:ring-offset-koya-dark transition ease-in-out duration-150 w-full']) }}>
    {{ $slot }}
</button>

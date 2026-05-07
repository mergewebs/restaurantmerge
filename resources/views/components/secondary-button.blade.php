<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-white/5 border border-white/15 rounded-xl font-semibold text-xs text-[#fff7eb]/80 uppercase tracking-widest shadow-sm hover:bg-white/10 hover:text-[#fff7eb] focus:outline-none focus:ring-2 focus:ring-koya/50 focus:ring-offset-2 focus:ring-offset-koya-dark disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

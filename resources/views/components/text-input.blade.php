@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-white/5 border border-white/10 text-[#fff7eb] placeholder-[#fff7eb]/35 focus:border-accent focus:ring-accent/40 rounded-xl shadow-sm px-4 py-3']) }}>

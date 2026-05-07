@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-koya focus:ring-koya/50 rounded-2xl shadow-sm px-4 py-3']) }}>

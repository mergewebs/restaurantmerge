@props([
    'theme' => 'light',
])

@php
    $activeLocale = app()->getLocale();
    $isDark = $theme === 'dark';
    $wrapperClasses = $isDark
        ? 'inline-flex items-center gap-2 rounded-full border border-white/20 bg-black/20 px-2 py-1 text-white shadow-lg backdrop-blur'
        : 'inline-flex items-center gap-2 rounded-full border border-[#121212] bg-white px-2 py-1 text-[#121212] shadow-[2px_2px_0px_#121212]';
    $labelClasses = $isDark ? 'text-white/75' : 'text-[#121212]/70';
    $activeClasses = $isDark
        ? 'bg-white text-[#121212]'
        : 'bg-[#121212] text-white';
    $inactiveClasses = $isDark
        ? 'text-white/85 hover:bg-white/10'
        : 'text-[#121212] hover:bg-[#121212]/5';
@endphp

<div class="{{ $wrapperClasses }}" aria-label="{{ __('public.language_switcher') }}">
    <span class="text-[10px] font-black uppercase tracking-[0.16em] {{ $labelClasses }}">
        {{ __('public.language_short') }}
    </span>

    @foreach (config('app.available_locales', ['es' => 'Español', 'en' => 'English']) as $locale => $label)
        <form method="POST" action="{{ route('locale.update') }}">
            @csrf
            <input type="hidden" name="locale" value="{{ $locale }}">
            <button
                type="submit"
                class="rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-[0.14em] transition {{ $activeLocale === $locale ? $activeClasses : $inactiveClasses }}"
                title="{{ $label }}"
                @if ($activeLocale === $locale) aria-current="true" @endif
            >
                <span class="sm:hidden">{{ strtoupper($locale) }}</span>
                <span class="hidden sm:inline">{{ $label }}</span>
            </button>
        </form>
    @endforeach
</div>

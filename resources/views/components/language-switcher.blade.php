@props([
    'theme' => 'light',
])

@php
    $activeLocale = app()->getLocale();
    $isDark = $theme === 'dark';
@endphp

<div 
    x-data="{ open: false }" 
    @click.away="open = false"
    {{ $attributes->merge(['class' => "koya-lang-custom-dropdown koya-theme-{$theme}"]) }}
    aria-label="{{ __('public.language_switcher') }}"
>
    <!-- Toggle Button -->
    <button 
        type="button" 
        @click="open = !open" 
        class="koya-lang-toggle"
        :class="{ 'open': open }"
    >
        <!-- Globe Icon -->
        <svg class="globe-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="2" y1="12" x2="22" y2="12"></line>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
        </svg>
        <span class="active-lang-text">{{ strtoupper($activeLocale) }}</span>
        <!-- Chevron Icon -->
        <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="transform opacity-0 scale-95 translate-y-[-8px]"
        x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="transform opacity-0 scale-95 translate-y-[-8px]"
        class="koya-lang-menu"
        x-cloak
    >
        @foreach (config('app.available_locales', ['es' => 'Espa' . chr(195) . chr(177) . 'ol', 'en' => 'English']) as $locale => $label)
            @php
                $cleanLabel = $locale === 'es' ? 'Espa' . chr(195) . chr(177) . 'ol' : $label;
            @endphp
            <form method="POST" action="{{ route('locale.update') }}" class="koya-lang-option-form">
                @csrf
                <input type="hidden" name="locale" value="{{ $locale }}">
                <button 
                    type="submit" 
                    class="koya-lang-option-btn {{ $activeLocale === $locale ? 'active' : '' }}"
                >
                    <span class="option-code">{{ strtoupper($locale) }}</span>
                    <span class="option-label">{{ $cleanLabel }}</span>
                    @if ($activeLocale === $locale)
                        <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    @endif
                </button>
            </form>
        @endforeach
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }

    .koya-lang-custom-dropdown {
        position: relative;
        display: inline-block;
        font-family: 'Outfit', 'Biski_2', sans-serif;
        user-select: none;
        z-index: 50;
    }

    .koya-lang-toggle {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: transparent;
        border: none;
        border-radius: 9999px;
        padding: 8px 16px;
        cursor: pointer;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
    }

    .koya-lang-toggle .globe-icon {
        width: 14px;
        height: 14px;
        opacity: 0.8;
        transition: transform 0.3s ease;
    }

    .koya-lang-toggle .chevron-icon {
        width: 10px;
        height: 10px;
        opacity: 0.8;
        transition: transform 0.25s ease;
    }

    .koya-lang-toggle.open .chevron-icon {
        transform: rotate(180deg);
    }

    .koya-lang-toggle.open .globe-icon {
        transform: rotate(30deg);
    }

    /* Light Theme Toggle */
    .koya-theme-light .koya-lang-toggle {
        background-color: #ffffff;
        color: #121212;
        border: 3px solid #121212;
        box-shadow: 2px 2px 0px #121212;
    }

    .koya-theme-light .koya-lang-toggle:hover,
    .koya-theme-light .koya-lang-toggle.open {
        background-color: #fffcf4;
        transform: translate(-1px, -1px);
        box-shadow: 3px 3px 0px #121212;
    }

    /* Dark Theme Toggle */
    .koya-theme-dark .koya-lang-toggle {
        background-color: rgba(255, 255, 255, 0.06);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .koya-theme-dark .koya-lang-toggle:hover,
    .koya-theme-dark .koya-lang-toggle.open {
        background-color: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.25);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    /* Floating Menu Container */
    .koya-lang-menu {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        min-width: 170px;
        border-radius: 14px;
        padding: 6px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
        display: flex;
        flex-direction: column;
        gap: 4px;
        z-index: 100;
    }

    /* Light Theme Menu */
    .koya-theme-light .koya-lang-menu {
        background-color: #ffffff;
        border: 3px solid #121212;
        box-shadow: 4px 4px 0px #121212;
    }

    /* Dark Theme Menu */
    .koya-theme-dark .koya-lang-menu {
        background-color: rgba(18, 18, 18, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
    }

    .koya-lang-option-form {
        margin: 0;
        padding: 0;
        width: 100%;
    }

    .koya-lang-option-btn {
        display: flex;
        align-items: center;
        width: 100%;
        background: transparent;
        border: none;
        border-radius: 8px;
        padding: 8px 12px;
        cursor: pointer;
        font-family: 'Outfit', sans-serif;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        transition: all 0.2s ease;
        gap: 10px;
        outline: none;
    }

    .koya-lang-option-btn .option-code {
        font-size: 10px;
        font-weight: 900;
        opacity: 0.65;
        padding: 2px 5px;
        border-radius: 4px;
        background: rgba(127, 127, 127, 0.15);
    }

    .koya-lang-option-btn .option-label {
        flex-grow: 1;
    }

    .koya-lang-option-btn .check-icon {
        width: 12px;
        height: 12px;
    }

    /* Light Theme Option states */
    .koya-theme-light .koya-lang-option-btn {
        color: #121212;
    }

    .koya-theme-light .koya-lang-option-btn:hover {
        background-color: rgba(18, 18, 18, 0.05);
    }

    .koya-theme-light .koya-lang-option-btn.active {
        background-color: #088972;
        color: #ffffff;
    }

    .koya-theme-light .koya-lang-option-btn.active .option-code {
        background: rgba(255, 255, 255, 0.25);
        opacity: 0.9;
    }

    /* Dark Theme Option states */
    .koya-theme-dark .koya-lang-option-btn {
        color: rgba(255, 255, 255, 0.8);
    }

    .koya-theme-dark .koya-lang-option-btn:hover {
        background-color: rgba(255, 255, 255, 0.08);
        color: #ffffff;
    }

    .koya-theme-dark .koya-lang-option-btn.active {
        background-color: #ffffff;
        color: #121212;
    }

    .koya-theme-dark .koya-lang-option-btn.active .option-code {
        background: rgba(18, 18, 18, 0.1);
        opacity: 0.9;
    }

    @media (max-width: 768px) {
        .koya-lang-header-hide {
            display: none !important;
        }
    }
</style>

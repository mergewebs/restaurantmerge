<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.seo', ['seo' => $seo ?? []])
    @include('partials.favicon')
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        koya: {
                            DEFAULT: '#fac021', // yellow
                            green: '#088972',
                            greenDark: '#055f4f',
                            greenLight: '#0ba389',
                            dark: '#121212',
                            light: '#fffcf4',
                        }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        @font-face {
            font-family: 'Biski';
            src: url("{{ asset('fonts/Biski.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Biski_2';
            src: url("{{ asset('fonts/Biski_2.ttf') }}") format('truetype');
            font-weight: bold;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Biski_4';
            src: url("{{ asset('fonts/Biski_4.ttf') }}") format('truetype');
            font-weight: 800;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Bubbleboddy';
            src: url("{{ asset('fonts/Bubbleboddy_Neue1.ttf') }}") format('truetype');
            font-weight: bold;
            font-style: normal;
            font-display: swap;
        }

        :root {
            --color-yellow: #fac021;
            --color-green: #088972;
            --color-green-dark: #055f4f;
            --color-green-light: #0ba389;
            --color-dark: #121212;
            --color-light: #fffcf4;
            --font-headline: 'Biski', 'Outfit', sans-serif;
            --font-body: 'Biski_2', sans-serif;
            --font-accent: 'Biski', sans-serif;
            --border-width: 4px;
        }
        
        body {
            background-color: var(--color-yellow);
            color: var(--color-dark);
            font-family: var(--font-body);
            -webkit-tap-highlight-color: transparent;
        }

        .btn-koya-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--color-yellow);
            color: var(--color-dark);
            font-family: 'Biski', sans-serif;
            border: var(--border-width) solid var(--color-dark);
            box-shadow: 4px 4px 0px var(--color-dark);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-koya-primary:hover:not(:disabled) {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px var(--color-dark);
            background-color: #ffd147;
        }

        .btn-koya-primary:active:not(:disabled) {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px var(--color-dark);
        }

        .btn-koya-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--color-light);
            color: var(--color-dark);
            font-family: 'Biski', sans-serif;
            border: var(--border-width) solid var(--color-dark);
            box-shadow: 4px 4px 0px var(--color-dark);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-koya-secondary:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px var(--color-dark);
            background-color: #fff8e3;
        }
        
        .btn-koya-green {
            background-color: var(--color-green);
            color: white;
        }
        .btn-koya-green:hover:not(:disabled) {
            background-color: var(--color-green-light);
        }

        .sticker {
            display: inline-block;
            font-family: var(--font-headline);
            font-size: 0.9rem;
            background-color: var(--color-yellow);
            color: var(--color-dark);
            padding: 0.4rem 0.8rem;
            border: 2px solid var(--color-dark);
            border-radius: 8px;
            box-shadow: 2px 2px 0px var(--color-dark);
            transform: rotate(-3deg);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 900;
        }

        .sticker.sticker-green {
            background-color: var(--color-green);
            color: white;
            transform: rotate(3deg);
        }

        .sticker.sticker-white {
            background-color: white;
            color: var(--color-dark);
            transform: rotate(-1.5deg);
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Floating graphics */
        .floating-element {
            position: absolute;
            z-index: 1;
            pointer-events: none;
            filter: drop-shadow(3px 3px 0px var(--color-dark));
        }

        @keyframes floatSlow {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(3deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        @keyframes floatSlower {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(-4deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        .float-1 { animation: floatSlow 5s ease-in-out infinite alternate; }
        .float-2 { animation: floatSlower 7s ease-in-out infinite alternate; }
        .float-3 { animation: floatSlow 6s ease-in-out infinite alternate; }
        .float-4 { animation: floatSlower 8s ease-in-out infinite alternate; }

        .koya-card {
            background-color: white;
            border: var(--border-width) solid var(--color-dark);
            box-shadow: 6px 6px 0px var(--color-dark);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .koya-card:hover {
            transform: translate(-4px, -4px);
            box-shadow: 10px 10px 0px var(--color-dark);
        }

        .menu-tab {
            font-family: var(--font-accent);
            font-size: 0.85rem;
            padding: 0.45rem 1rem;
            border: 2px solid transparent;
            background: none;
            color: white;
            cursor: pointer;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            white-space: nowrap;
        }

        .menu-tab:hover {
            color: var(--color-yellow);
        }

        .menu-tab.active {
            background-color: var(--color-yellow);
            color: var(--color-dark);
            border-color: var(--color-dark);
            box-shadow: 2px 2px 0px var(--color-dark);
        }

        @media (min-width: 768px) {
            .menu-tab {
                font-size: 1.05rem;
                padding: 0.7rem 1.6rem;
            }
        }

        input {
            color: var(--color-dark) !important;
            background: white !important;
            border: 3px solid var(--color-dark) !important;
            border-radius: 12px !important;
            padding: 0.8rem 1rem !important;
            font-family: var(--font-body) !important;
            transition: all 0.2s ease !important;
            box-shadow: 3px 3px 0px var(--color-dark) !important;
        }
        
        input:focus {
            outline: none !important;
            transform: translate(-2px, -2px) !important;
            box-shadow: 5px 5px 0px var(--color-dark) !important;
            border-color: var(--color-green) !important;
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen pb-28 antialiased overflow-x-hidden selection:bg-koya selection:text-koya-dark relative">

    <!-- Floating Graphics Background -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <!-- Cartoon Burger -->
        <img src="https://koyabcn.es/wp-content/uploads/2025/07/home-cartoon-burger.png" alt="" aria-hidden="true" class="floating-element float-1 opacity-20 md:opacity-30 w-24 md:w-28" style="top: 12%; left: 3%;" loading="lazy" decoding="async" width="112" height="112">

        <!-- Cartoon Fritas -->
        <img src="https://koyabcn.es/wp-content/uploads/2025/08/fritas.png" alt="" aria-hidden="true" class="floating-element float-2 opacity-20 md:opacity-30 w-24 md:w-28" style="bottom: 25%; left: 4%;" loading="lazy" decoding="async" width="112" height="112">

        <!-- Cartoon Rice Bowl -->
        <img src="https://koyabcn.es/wp-content/uploads/2025/08/bol_arroz.png" alt="" aria-hidden="true" class="floating-element float-3 opacity-20 md:opacity-30 w-24 md:w-28" style="top: 20%; right: 4%;" loading="lazy" decoding="async" width="112" height="112">

        <!-- Cartoon Coffee -->
        <img src="https://koyabcn.es/wp-content/uploads/2025/08/cafe.png" alt="" aria-hidden="true" class="floating-element float-4 opacity-20 md:opacity-35 w-20 md:w-24" style="bottom: 15%; right: 6%;" loading="lazy" decoding="async" width="96" height="96">
    </div>

    <!-- Sticky Navigation Header -->
    <header class="sticky top-0 z-50 bg-[#088972] py-2 backdrop-blur-md relative">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <img 
                    src="{{ asset('images/koya-logo.png') }}"
                    alt="{{ __('public.seo_logo_alt') }}" width="56" height="56" 
                    class="w-14 h-14 rounded-full border-[3px] border-[#121212] object-cover shadow-[2px_2px_0px_#121212]"
                    onerror="this.src='/favicon.ico'"
                >
                <span class="font-['Biski'] text-2xl text-white tracking-wider uppercase drop-shadow-[2px_2px_0px_#121212]">KOYA</span>
            </a>
            
            <div class="flex items-center gap-2">
                <x-language-switcher theme="dark" />
                <a href="/" class="btn-koya-secondary text-xs uppercase py-2 px-4 rounded-full" style="font-size: 0.8rem; border-width: 3px;">
                    🏠 Home
                </a>
            </div>
        </div>
    </header>

    <div
        class="max-w-6xl mx-auto px-4 py-8 space-y-6 relative z-10"
        x-data="takeawayOrderPage({
            submitUrl: @js(route('order.public.store')),
            csrfToken: @js(csrf_token()),
            menuItems: @js($menuItemsFlat),
            restaurantName: @js($restaurantName),
            brandLogoUrl: @js($brandLogoUrl)
        })"
        x-init="init()"
    >
        <!-- Title area -->
        <div class="bg-white border-[4px] border-[#121212] p-5 rounded-[24px] shadow-[6px_6px_0px_#121212] flex flex-col md:flex-row md:items-center justify-between gap-4 relative overflow-hidden">
            <div class="z-10">
                <h1 class="font-['Biski'] text-3xl text-koya-dark uppercase tracking-wide text-[#121212]" x-text="takeawayTitle()"></h1>
                <p class="text-xs text-[#555] mt-1" x-text="takeawayCta()"></p>
            </div>
            
            <div class="flex items-center justify-between md:justify-end gap-6 border-t md:border-t-0 border-[#ebd6b0]/50 pt-3 md:pt-0 z-10">
                <div class="text-left md:text-right">
                    <p class="text-[10px] text-[#555] uppercase tracking-widest font-extrabold">{{ __('public.order_in_cart') }}</p>
                    <p class="text-sm font-black text-[#121212]"><span x-text="itemCount()"></span> <span x-text="itemLabel(itemCount())"></span></p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-[#555] uppercase tracking-widest font-extrabold">{{ __('public.order_total') }}</p>
                    <p class="font-['Biski'] text-2xl text-koya-green" x-text="currency(total())"></p>
                </div>
            </div>
        </div>

        <!-- Alert messages -->
        <div class="space-y-2" x-cloak>
            <div class="flex items-center gap-2.5 p-3 rounded-2xl bg-amber-500/10 border-[3px] border-[#121212] text-[#121212] text-xs font-black shadow-[2px_2px_0px_#121212]" x-show="offline">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span x-text="offlineLabel()"></span>
            </div>
            
            <div class="flex items-center gap-2.5 p-3 rounded-2xl bg-red-500/10 border-[3px] border-[#121212] text-red-700 text-xs font-black shadow-[2px_2px_0px_#121212]" x-show="errorMessage">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span x-text="errorMessage"></span>
            </div>
            
            <div class="flex flex-col gap-2 p-4 rounded-2xl bg-emerald-500/10 border-[3px] border-[#121212] text-emerald-800 text-sm shadow-[2px_2px_0px_#121212]" x-show="successMessage">
                <div class="flex items-center gap-2.5 font-['Biski'] text-lg">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ __('public.order_success_heading') }}</span>
                </div>
                <p class="text-xs font-bold" x-html="successMessage"></p>
            </div>
        </div>

        <!-- Category Horizontal Tabs Container -->
        <div class="sticky top-[76px] z-40 py-3 -mx-4 px-4 bg-gradient-to-b from-[#fac021] via-[#fac021]/95 to-transparent">
            <div class="flex justify-center pb-1">
                <div class="flex flex-wrap justify-center gap-1.5 md:gap-2 bg-koya-greenDark p-1.5 md:p-2 rounded-2xl md:rounded-full border-[3px] border-koya-dark">
                    <button
                        type="button"
                        @click="activeCategory = 'All'"
                        class="menu-tab"
                        :class="activeCategory === 'All' ? 'active' : ''"
                    >
                        All Items
                    </button>
                    <template x-for="category in categories()" :key="category">
                        <button
                            type="button"
                            @click="activeCategory = category"
                            class="menu-tab"
                            :class="activeCategory === category ? 'active' : ''"
                        >
                            <span x-text="category"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Products Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-24">
            <template x-for="item in filteredItems()" :key="item.id">
                <div
                    class="koya-card rounded-[24px] p-5 flex flex-col justify-between group relative"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                >
                    <!-- Card Top Area -->
                    <div>
                        <!-- Product Image Container -->
                        <div class="relative w-full h-48 rounded-[16px] overflow-hidden mb-4 bg-koya-light border-[3px] border-dashed border-[#ebd6b0] flex items-center justify-center">
                            <template x-if="getImagePath(item)">
                                <img 
                                    :src="getImagePath(item)" 
                                    :alt="item.name" 
                                    :class="getImageScaleClass(item)"
                                    loading="lazy"
                                    decoding="async"
                                    fetchpriority="low"
                                    width="600"
                                    height="600"
                                />
                            </template>
                            
                            <!-- If no image, show placeholder -->
                            <template x-if="!getImagePath(item)">
                                <div class="flex flex-col items-center justify-center text-center p-4">
                                    <div class="w-16 h-16 rounded-full bg-koya/20 border-2 border-[#fac021]/30 flex items-center justify-center mb-2">
                                        <span class="text-koya-dark font-black text-xl" x-text="translatedName(item.name).substring(0, 2).toUpperCase()"></span>
                                    </div>
                                    <span class="text-[9px] uppercase tracking-widest text-[#121212]/40" x-text="item.category"></span>
                                </div>
                            </template>
                        </div>
                            
                            <!-- Badges Overlay -->
                            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5 max-w-[90%] pointer-events-none">
                            </div>
                        </div>

                        <!-- Product Title and Price -->
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <h3 class="font-['Biski'] text-xl text-koya-dark uppercase tracking-wide leading-tight group-hover:text-koya-green transition-colors" x-html="formatName(translatedName(item.name))"></h3>
                            <span class="font-bold text-xl text-koya-green whitespace-nowrap" x-text="currency(item.price)"></span>
                        </div>
                        
                        <!-- Description -->
                        <p
                            x-show="hasDescription(item)"
                            x-text="getDescription(item)"
                            class="font-['Biski_2'] text-sm text-[#555] leading-relaxed mb-6"
                        ></p>
                    </div>

                    <!-- Action: Add / Qty control -->
                    <div class="pt-4 border-t border-[#ebd6b0]/50">
                        <!-- Add Button -->
                        <template x-if="!isInCart(item.id)">
                            <button
                                type="button"
                                @click="addItem(item)"
                                class="btn-koya-primary w-full py-3 px-4 rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 border-[3px]"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add to Order
                            </button>
                        </template>
                        
                        <!-- Qty buttons direct on card -->
                        <template x-if="isInCart(item.id)">
                            <div class="w-full flex items-center justify-between gap-2 bg-[#fac021]/10 border-[3px] border-[#121212] rounded-xl p-1 shadow-[2px_2px_0px_#121212]">
                                <button
                                    type="button"
                                    @click="decreaseQty(item.id)"
                                    class="w-8 h-8 rounded-lg bg-white border-2 border-[#121212] font-black text-lg flex items-center justify-center transition-transform active:scale-75 shadow-[1px_1px_0px_#121212]"
                                >
                                    &minus;
                                </button>
                                <div class="flex-1 text-center select-none">
                                    <span class="text-[9px] text-[#555] block leading-none font-bold uppercase tracking-wider">{{ __('public.order_in_cart') }}</span>
                                    <span class="text-sm font-black text-[#088972] block mt-0.5" x-text="getCartQty(item.id)"></span>
                                </div>
                                <button
                                    type="button"
                                    @click="increaseQty(item.id)"
                                    class="w-8 h-8 rounded-lg bg-white border-2 border-[#121212] font-black text-lg flex items-center justify-center transition-transform active:scale-75 shadow-[1px_1px_0px_#121212]"
                                >
                                    &#43;
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
</template>
            <div
                x-show="activeCategory === 'Smash Burger'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="group relative flex flex-col rounded-[28px] bg-[#fffcf4] border-[3px] border-[#121212] shadow-[6px_6px_0px_#121212] overflow-hidden"
            >
                <div class="p-6 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <h3 class="font-['Biski'] text-xl text-koya-dark uppercase tracking-wide leading-tight">{!! __('public.burger_bun_availability') !!}</h3>
                            <span class="font-bold text-xl text-koya-green whitespace-nowrap">2&euro;</span>
                        </div>
                        <p class="font-['Biski_2'] text-sm text-[#555] leading-relaxed">{!! __('public.burger_bun_supplement') !!}</p>
                    </div>
                    <div class="pt-4 mt-6 border-t border-[#ebd6b0]/50">
                        <span class="block w-full py-3 px-4 rounded-xl text-xs font-black uppercase tracking-wider text-center bg-[#fac021]/25 text-[#121212] border-[3px] border-dashed border-[#121212]">Supplement</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Panel Drawer (Slide-out) -->
        <div x-cloak>
            <!-- Overlay Backdrop -->
            <div
                x-show="showOrderPanel"
                class="fixed inset-0 z-50 bg-[#121212]/80 backdrop-blur-sm"
                x-transition.opacity
                @click="showOrderPanel = false"
            ></div>
            
            <!-- Side Panel -->
            <div
                class="fixed inset-y-0 right-0 z-50 w-full max-w-md h-screen bg-[#fffcf4] border-l-[4px] border-[#121212] p-6 overflow-y-auto flex flex-col justify-between"
                x-show="showOrderPanel"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
            >
                <!-- Drawer Header -->
                <div>
                    <div class="flex items-center justify-between border-b-[3px] border-[#121212] pb-4">
                        <div>
                            <h2 class="font-['Biski'] text-2xl uppercase text-[#121212]">{{ __('public.order_panel_title') }}</h2>
                            <p class="text-xs text-[#555] mt-0.5 font-bold" x-text="i18n.itemSelected.replace('__COUNT__', itemCount())"></p>
                        </div>
                        <button 
                            type="button" 
                            class="p-2 rounded-xl bg-[#fac021] border-[3px] border-[#121212] hover:bg-[#ffd147] text-[#121212] shadow-[2px_2px_0px_#121212] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all" 
                            @click="showOrderPanel = false"
                            aria-label="Close cart"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Cart Items List -->
                    <div class="mt-6 space-y-3 overflow-y-auto max-h-[calc(100vh-340px)] pr-1 no-scrollbar">
                        <template x-for="item in cart" :key="item.id">
                            <div class="rounded-2xl bg-[#fac021] border-[3px] border-[#121212] p-4 flex gap-3 items-center justify-between shadow-[3px_3px_0px_#121212]">
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-sm text-[#121212] truncate uppercase" x-html="formatName(translatedName(item.name))"></h4>
                                    <p class="text-xs text-[#121212]/80 mt-0.5">
                                        <span x-text="currency(item.price)"></span> each
                                    </p>
                                    <p class="text-xs text-koya-green font-black mt-1">
                                        Line Total: <span x-text="currency(item.quantity * item.price)"></span>
                                    </p>
                                </div>
                                
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <button 
                                        type="button" 
                                        class="w-7 h-7 rounded bg-[#fffcf4] hover:bg-[#fffcf4]/80 border-2 border-[#121212] font-black text-center flex items-center justify-center active:scale-95 shadow-[1px_1px_0px_#121212]" 
                                        @click="decreaseQty(item.id)"
                                    >
                                        -
                                    </button>
                                    <span class="w-6 text-center text-sm font-black text-[#121212]" x-text="item.quantity"></span>
                                    <button 
                                        type="button" 
                                        class="w-7 h-7 rounded bg-[#fffcf4] hover:bg-[#fffcf4]/80 border-2 border-[#121212] font-black text-center flex items-center justify-center active:scale-95 shadow-[1px_1px_0px_#121212]" 
                                        @click="increaseQty(item.id)"
                                    >
                                        +
                                    </button>
                                    
                                    <button 
                                        type="button" 
                                        class="ml-1 w-8 h-8 rounded bg-red-100 hover:bg-red-200 border-2 border-red-600 text-red-600 flex items-center justify-center active:scale-95 transition-colors shadow-[1px_1px_0px_#121212]" 
                                        @click="removeItem(item.id)"
                                        title="{{ __('Remove item') }}"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <div x-show="cart.length === 0" class="py-8 text-center">
                            <div class="w-16 h-16 rounded-full bg-koya-light border-2 border-[#121212] flex items-center justify-center mx-auto mb-3 shadow-[2px_2px_0px_#121212]">
                                <svg class="w-6 h-6 text-[#121212]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-[#555] font-bold">{{ __('public.order_empty') }}</p>
                            <p class="text-xs text-[#777] mt-1">{{ __('public.order_empty_help') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Customer Fields & Checkout Button -->
                <div class="border-t-[3px] border-[#121212] pt-4 mt-4 space-y-4 bg-transparent shrink-0">
                    <div class="bg-[#fac021]/15 border-2 border-[#121212] p-4 rounded-2xl flex justify-between items-center shadow-[3px_3px_0px_#121212]">
                        <span class="text-xs font-black uppercase text-[#121212]/70 tracking-wider font-['Biski_2']">{{ __('public.order_total_to_pay') }}</span>
                        <span class="font-['Biski'] text-2xl text-koya-green" x-text="currency(total())"></span>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="text-[10px] uppercase tracking-widest text-[#121212]/60 font-black block mb-1">{{ __('public.order_customer_name') }}</label>
                            <input 
                                type="text" 
                                x-model="customerName" 
                                @input="persistState()" 
                                placeholder="{{ __('public.order_customer_name_placeholder') }}" 
                                class="w-full text-base rounded-xl px-4 py-3"
                            >
                        </div>
                        <div>
                            <label class="text-[10px] uppercase tracking-widest text-[#121212]/60 font-black block mb-1">{{ __('public.order_customer_phone') }}</label>
                            <input 
                                type="tel" 
                                x-model="customerPhone" 
                                @input="persistState()" 
                                placeholder="+34 600 000 000" 
                                class="w-full text-base rounded-xl px-4 py-3"
                            >
                        </div>
                    </div>

                    <button
                        type="button"
                        class="btn-koya-primary btn-koya-green w-full py-4 px-6 rounded-2xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 border-[3px]"
                        :disabled="isSubmitting || cart.length === 0 || !customerName.trim() || offline"
                        @click="submitOrder()"
                        style="border-width: 4px;"
                    >
                        <span x-show="!isSubmitting" x-text="submitLabel()"></span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="submittingLabel()"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Floating Cart Indicator Trigger Button -->
        <div class="fixed bottom-6 right-6 z-40 pointer-events-none">
            <button
                type="button"
                @click="showOrderPanel = true"
                class="pointer-events-auto relative w-16 h-16 rounded-full border-[3px] border-[#121212] bg-[#fac021] text-[#121212] shadow-[4px_4px_0px_#121212] flex items-center justify-center active:translate-x-[2px] active:translate-y-[2px] active:shadow-none hover:scale-105 transition-transform"
                :class="cart.length > 0 ? 'bg-[#fac021] border-[#121212] animate-pulse-slow' : 'bg-white'"
                title="{{ __('public.order_current') }}"
                aria-label="{{ __('public.order_open_current') }}"
            >
                <svg class="w-6.5 h-6.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <!-- Cart Items Counter Badge -->
                <span 
                    class="absolute -top-1.5 -right-1.5 min-w-[1.4rem] h-5 px-1.5 rounded-full bg-red-600 border-2 border-[#121212] text-white text-[10px] font-black leading-4 text-center shadow" 
                    x-text="itemCount()"
                    x-show="cart.length > 0"
                    x-cloak
                ></span>
            </button>
        </div>

        <!-- Toast Notifications Overlay -->
        <div
            class="fixed left-1/2 -translate-x-1/2 bottom-28 z-50 pointer-events-none"
            x-show="toast.visible"
            x-cloak
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
            aria-live="polite"
        >
            <div class="sticker border-[3px] border-[#121212] text-xs font-black shadow-[3px_3px_0px_#121212] flex items-center gap-2 bg-[#fac021] text-[#121212]">
                <svg class="w-4 h-4 text-koya-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <span x-text="toast.message"></span>
            </div>
        </div>
    </div>

    <!-- Page Logic Script -->
    <script>
        function takeawayOrderPage(config) {
            return {
                submitUrl: config.submitUrl,
                csrfToken: config.csrfToken,
                menuItems: Array.isArray(config.menuItems) ? config.menuItems : [],
                restaurantName: config.restaurantName || 'Restaurant',
                brandLogoUrl: config.brandLogoUrl || '',
                i18n: {
                    takeawayTitle: @js(__('public.takeaway_title')),
                    takeawayCta: @js(__('public.takeaway_cta')),
                    offline: @js(__('public.takeaway_offline')),
                    submit: @js(__('public.takeaway_order_button')),
                    submitting: @js(__('public.takeaway_submitting')),
                },
                cart: [],
                activeCategory: 'All',
                showOrderPanel: false,
                customerName: '',
                customerPhone: '',
                isSubmitting: false,
                successMessage: '',
                errorMessage: '',
                offline: !navigator.onLine,
                storageKey: 'takeaway-public-cart-v2',
                toast: {
                    visible: false,
                    message: '',
                    timer: null,
                },

                init() {
                    this.loadState();
                    window.addEventListener('online', () => this.offline = false);
                    window.addEventListener('offline', () => this.offline = true);
                    
                    // Default to the first category if category items exist
                    const cats = this.categories();
                    if (cats.length > 0) {
                        this.activeCategory = cats[0];
                    }
                },

                categories() {
                    return [...new Set(this.menuItems.map((item) => item.category))];
                },

                filteredItems() {
                    if (this.activeCategory === 'All') {
                        return this.menuItems;
                    }
                    return this.menuItems.filter((item) => item.category === this.activeCategory);
                },

                takeawayTitle() {
                    return this.i18n.takeawayTitle || 'Take Away';
                },

                takeawayCta() {
                    return this.i18n.takeawayCta || '';
                },

                offlineLabel() {
                    return this.i18n.offline || 'Offline mode: build your cart now and submit when connection comes back.';
                },

                submitLabel() {
                    return this.i18n.submit || 'Submit Order';
                },

                submittingLabel() {
                    return this.i18n.submitting || 'Submitting...';
                },

                isInCart(itemId) {
                    return this.cart.some((item) => Number(item.id) === Number(itemId));
                },

                getCartQty(itemId) {
                    const target = this.cart.find((item) => Number(item.id) === Number(itemId));
                    return target ? target.quantity : 0;
                },

                addItem(menuItem) {
                    const existing = this.cart.find((item) => Number(item.id) === Number(menuItem.id));
                    if (existing) {
                        existing.quantity += 1;
                    } else {
                        this.cart.push({
                            id: Number(menuItem.id),
                            name: menuItem.name,
                            price: Number(menuItem.price || 0),
                            quantity: 1,
                        });
                    }

                    this.showAddedToast(menuItem.name);
                    this.persistState();
                },

                increaseQty(itemId) {
                    const target = this.cart.find((item) => Number(item.id) === Number(itemId));
                    if (!target) return;
                    target.quantity += 1;
                    this.persistState();
                },

                decreaseQty(itemId) {
                    const target = this.cart.find((item) => Number(item.id) === Number(itemId));
                    if (!target) return;

                    target.quantity -= 1;
                    if (target.quantity < 1) {
                        this.removeItem(itemId);
                        return;
                    }

                    this.persistState();
                },

                removeItem(itemId) {
                    this.cart = this.cart.filter((item) => Number(item.id) !== Number(itemId));
                    this.persistState();
                },

                itemCount() {
                    return this.cart.reduce((sum, item) => sum + Number(item.quantity || 0), 0);
                },

                total() {
                    return this.cart.reduce((sum, item) => sum + (Number(item.price || 0) * Number(item.quantity || 0)), 0);
                },

                currency(value) {
                    const amount = Number(value || 0);
                    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(amount);
                },

                translatedName(name) {
                    return name || '';
                },

                formatName(name) {
                    if (!name) return '';
                    return name.replace(/(\(\d+\s*uds\.?\))/gi, '<span style="font-size: 0.75em; opacity: 0.85; font-weight: normal; text-transform: none; display: inline-block; margin-left: 4px;">$1</span>');
                },

                async submitOrder() {
                    if (this.isSubmitting) return;

                    this.isSubmitting = true;
                    this.errorMessage = '';
                    this.successMessage = '';

                    try {
                        const response = await fetch(this.submitUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                customer_name: this.customerName.trim(),
                                customer_phone: this.customerPhone.trim(),
                                items: this.cart.map((item) => ({
                                    menu_item_id: item.id,
                                    quantity: item.quantity,
                                })),
                            }),
                        });

                        const payload = await response.json();

                        if (!response.ok) {
                            throw new Error(payload.message || this.i18n.submitError);
                        }

                        this.successMessage = payload.message || `Order #${payload.order_id} received.`;
                        this.cart = [];
                        this.customerName = '';
                        this.customerPhone = '';
                        this.showOrderPanel = false;
                        this.persistState();
                    } catch (error) {
                        this.errorMessage = error?.message || this.i18n.submitError;
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                persistState() {
                    localStorage.setItem(this.storageKey, JSON.stringify({
                        cart: this.cart,
                        customerName: this.customerName,
                        customerPhone: this.customerPhone,
                    }));
                },

                loadState() {
                    try {
                        const raw = localStorage.getItem(this.storageKey);
                        if (!raw) return;
                        const parsed = JSON.parse(raw);
                        this.cart = Array.isArray(parsed.cart) ? parsed.cart : [];
                        this.customerName = typeof parsed.customerName === 'string' ? parsed.customerName : '';
                        this.customerPhone = typeof parsed.customerPhone === 'string' ? parsed.customerPhone : '';
                    } catch {
                        this.cart = [];
                    }
                },

                showAddedToast(itemName) {
                    const safeName = (itemName || 'Item').toString().trim();
                    this.toast.message = `${safeName} added`;
                    this.toast.visible = true;

                    if (this.toast.timer) {
                        clearTimeout(this.toast.timer);
                    }

                    this.toast.timer = setTimeout(() => {
                        this.toast.visible = false;
                    }, 1200);
                },

                getImagePath(item) {
                    return item?.image_thumbnail_url || item?.image_url || null;
                },

                getImageScaleClass(item) {
                    const scaledItems = [
                        'ADOBO',
                        'KIMCHI BURGER',
                        'TARTUFATA',
                        'CANGRE BURGER',
                        'GYOZAS FRITAS IBÉRICAS (5 uds.)',
                        'EDAMAME AL VAPOR',
                        'SWEET & SPICY WINGS',
                        'EBI FRY (5 uds.)',
                        'ROLLITOS (5 uds.)',
                        'TACOS DE CARILLADA DE TERNERA',
                    ];

                    return scaledItems.includes(item?.name)
                        ? 'max-h-[85%] max-w-[85%] object-contain scale-110 transition-transform duration-500 group-hover:scale-[1.18] group-hover:rotate-2'
                        : 'max-h-[85%] max-w-[85%] object-contain transition-transform duration-500 group-hover:scale-110 group-hover:rotate-2';
                },

                hasDescription(item) {
                    return typeof item.description === 'string' && item.description.trim() !== '';
                },

                getDescription(item) {
                    return this.hasDescription(item) ? item.description.trim() : '';
                },

                getBadge(itemName) {
                    const badges = {
                        'Tartufata': 'Truffle',
                        'Smash Burger': 'Classic',
                        'Red Onion': 'Smoky',
                        'KOYA SALAD': 'Fresh',
                        'KATSUDON': 'Crispy',
                        'SOBOKU': 'Simple',
                    };
                    return this.i18n.menuBadges?.[badges[itemName]] || badges[itemName] || null;
                },
            };
        }
    </script>
</body>
</html>






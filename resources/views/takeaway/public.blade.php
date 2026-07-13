<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $restaurantName }} | Takeaway & Order</title><link rel="icon" type="image/png" href="https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg">
    <link rel="icon" type="image/png" href="https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
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
                            DEFAULT: '#c89b3c',
                            light: '#d9b45f',
                            dark: '#b98627',
                            gold: '#fac021',
                        },
                        darkBg: '#120d09',
                        darkCard: '#1d1611',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        serif: ['Cormorant Garamond', 'serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        @font-face {
            font-family: 'Biski';
            src: url('/fonts/Biski.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        :root {
            --koya: #c89b3c;
            --koya-gold: #fac021;
            --bg: #120d09;
        }
        
        body {
            background: 
                radial-gradient(circle at 0% 0%, rgba(200, 155, 60, 0.15), transparent 45rem),
                radial-gradient(circle at 100% 100%, rgba(124, 47, 40, 0.12), transparent 45rem),
                linear-gradient(180deg, #120d09 0%, #17110c 50%, #0d0906 100%);
            color: #fff7eb;
            font-family: 'Outfit', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        
        .glass {
            background: rgba(29, 22, 17, 0.75);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(244, 222, 183, 0.08);
        }
        
        .glass-card {
            background: linear-gradient(135deg, rgba(39, 30, 23, 0.7) 0%, rgba(25, 19, 15, 0.8) 100%);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(244, 222, 183, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .glass-card:hover {
            border-color: rgba(200, 155, 60, 0.3);
            box-shadow: 0 15px 30px -10px rgba(200, 155, 60, 0.1);
        }
 
        .btn-koya {
            background: linear-gradient(135deg, #d9b45f 0%, #b98627 100%);
            color: #120d09;
            font-family: 'Biski', sans-serif;
            box-shadow: 0 4px 15px rgba(200, 155, 60, 0.3);
            transition: all 0.2s ease;
        }
        
        .btn-koya:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(200, 155, 60, 0.45);
            filter: brightness(1.05);
        }

        .btn-koya:active:not(:disabled) {
            transform: translateY(1px);
            box-shadow: 0 2px 10px rgba(200, 155, 60, 0.2);
        }
        
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .85; transform: scale(1.03); }
        }

        input {
            color: #fff7eb !important;
            background: rgba(255, 247, 235, 0.04) !important;
            border: 1px solid rgba(244, 222, 183, 0.1) !important;
            transition: all 0.2s ease !important;
        }
        
        input:focus {
            border-color: rgba(217, 180, 95, 0.8) !important;
            background: rgba(255, 247, 235, 0.08) !important;
            box-shadow: 0 0 0 3px rgba(217, 180, 95, 0.15) !important;
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen pb-16 antialiased selection:bg-koya selection:text-darkBg">

    <div
        class="max-w-6xl mx-auto px-4 py-6 space-y-6"
        x-data="takeawayOrderPage({
            submitUrl: @js(route('takeaway.public.store')),
            csrfToken: @js(csrf_token()),
            menuItems: @js($menuItemsFlat),
            restaurantName: @js($restaurantName),
            brandLogoUrl: @js($brandLogoUrl)
        })"
        x-init="init()"
    >
        <!-- Header Section -->
        <header class="glass rounded-3xl p-5 border border-white/10 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-koya/5 to-transparent pointer-events-none"></div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 relative z-10">
                <!-- Brand Info & Navigation -->
                <div class="flex items-center gap-4">
                    <a href="/" class="shrink-0 group" title="Back to Homepage">
                        <img 
                            src="https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg" 
                            alt="KOYA Logo" 
                            class="h-16 w-16 rounded-full border-2 border-koya object-cover shadow-lg transition-transform duration-300 group-hover:scale-105"
                            onerror="this.src='/favicon.ico'"
                        >
                    </a>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <x-language-switcher theme="dark" />
                            <a href="/" class="text-[10px] bg-white/5 hover:bg-white/10 text-white/80 hover:text-white px-2.5 py-1 rounded-full border border-white/10 transition-colors uppercase font-bold tracking-wider">
                                Home
                            </a>
                            <span class="text-[9px] bg-koya/20 text-koya border border-koya/30 px-2 py-0.5 rounded-full uppercase font-black tracking-widest animate-pulse-slow">
                                Ordering Online
                            </span>
                        </div>
                        <h1 class="text-2xl font-extrabold uppercase tracking-tight text-white mt-1" x-text="takeawayTitle()"></h1>
                        <p class="text-xs text-white/60 mt-0.5" x-text="takeawayCta()"></p>
                    </div>
                </div>
                
                <!-- Quick Cart Total info -->
                <div class="flex items-center justify-between sm:justify-end gap-6 border-t border-white/5 sm:border-t-0 pt-3 sm:pt-0">
                    <div class="text-left sm:text-right">
                        <p class="text-[10px] text-white/40 uppercase tracking-widest">Selected Items</p>
                        <p class="text-sm font-bold text-white mt-0.5"><span x-text="itemCount()"></span> items</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-white/40 uppercase tracking-widest">Order Total</p>
                        <p class="text-2xl font-black text-koya-gold mt-0.5" x-text="currency(total())"></p>
                    </div>
                </div>
            </div>
            
            <!-- Alert message banners -->
            <div class="mt-4 space-y-2" x-cloak>
                <div class="flex items-center gap-2.5 p-3 rounded-2xl bg-amber-500/10 border border-amber-500/25 text-amber-300 text-xs font-semibold" x-show="offline">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span x-text="offlineLabel()"></span>
                </div>
                
                <div class="flex items-center gap-2.5 p-3 rounded-2xl bg-red-500/10 border border-red-500/25 text-red-300 text-xs font-semibold" x-show="errorMessage">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span x-text="errorMessage"></span>
                </div>
                
                <div class="flex flex-col gap-2 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-300 text-sm" x-show="successMessage">
                    <div class="flex items-center gap-2.5 font-bold">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Order Successful!</span>
                    </div>
                    <p class="text-xs text-white/80" x-html="successMessage"></p>
                </div>
            </div>
        </header>

        <!-- Category Horizontal Tabs Container -->
        <div class="sticky top-0 z-40 py-3 -mx-4 px-4 bg-gradient-to-b from-[#120d09] via-[#120d09]/95 to-transparent backdrop-blur-md">
            <div class="flex flex-wrap items-center justify-center gap-2 py-1">
                <button
                    type="button"
                    @click="activeCategory = 'All'"
                    class="px-5 py-2.5 rounded-full text-xs font-extrabold uppercase tracking-wider transition-all duration-300 border"
                    :class="activeCategory === 'All' ? 'bg-koya text-darkBg border-koya shadow-lg shadow-koya/20 scale-105' : 'bg-white/5 text-white/70 border-white/10 hover:bg-white/10 hover:text-white'"
                >
                    All Menu
                </button>
                <template x-for="category in categories()" :key="category">
                    <button
                        type="button"
                        @click="activeCategory = category"
                        class="px-5 py-2.5 rounded-full text-xs font-extrabold uppercase tracking-wider transition-all duration-300 border"
                        :class="activeCategory === category ? 'bg-koya text-darkBg border-koya shadow-lg shadow-koya/20 scale-105' : 'bg-white/5 text-white/70 border-white/10 hover:bg-white/10 hover:text-white'"
                    >
                        <span x-text="category"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- Products Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-24">
            <template x-for="item in filteredItems()" :key="item.id">
                <div
                    class="glass-card rounded-3xl p-5 flex flex-col justify-between group"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                >
                    <!-- Card Top Area -->
                    <div>
                        <!-- Product Image Container -->
                        <div class="relative w-full h-48 rounded-2xl overflow-hidden mb-4 bg-gradient-to-br from-white/5 to-white/10 border border-white/5 flex items-center justify-center">
                            <template x-if="getImagePath(item)">
                                <img 
                                    :src="getImagePath(item)" 
                                    :alt="item.name" 
                                    class="max-h-[85%] max-w-[85%] object-contain transition-transform duration-500 group-hover:scale-110 group-hover:rotate-2"
                                    loading="lazy"
                                />
                            </template>
                            
                            <!-- If no image, show elegant placeholder -->
                            <template x-if="!getImagePath(item)">
                                <div class="flex flex-col items-center justify-center text-center p-4">
                                    <div class="w-16 h-16 rounded-full bg-koya/10 border border-koya/20 flex items-center justify-center mb-2">
                                        <span class="text-koya font-black text-xl" x-text="item.name.substring(0, 2).toUpperCase()"></span>
                                    </div>
                                    <span class="text-[9px] uppercase tracking-widest text-white/30" x-text="item.category"></span>
                                </div>
                            </template>
                            
                            <!-- Badges Overlay -->
                            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5 max-w-[90%] pointer-events-none">
                                <template x-if="getBadge(item.name)">
                                    <span class="bg-koya text-darkBg text-[9px] font-black uppercase px-2.5 py-1 rounded-lg tracking-wider shadow" x-text="getBadge(item.name)"></span>
                                </template>
                            </div>
                        </div>

                        <!-- Product Title and Price -->
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <h3 class="text-lg font-black text-white leading-tight group-hover:text-koya transition-colors duration-200" x-html="formatName(item.name)"></h3>
                            <span class="text-lg font-black text-koya-gold whitespace-nowrap" x-text="currency(item.price)"></span>
                        </div>
                        
                        <!-- Description -->
                        <p 
                            class="text-xs text-white/60 font-light leading-relaxed mb-6" 
                            x-text="getDescription(item)"
                        ></p>
                    </div>

                    <!-- Action: Add / Qty control -->
                    <div class="pt-4 border-t border-white/5">
                        <!-- Add Button -->
                        <template x-if="!isInCart(item.id)">
                            <button
                                type="button"
                                @click="addItem(item)"
                                class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-white/5 hover:bg-koya hover:text-darkBg border border-white/10 hover:border-koya text-xs font-black uppercase tracking-wider transition-all duration-200 active:scale-95"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add to Order
                            </button>
                        </template>
                        
                        <!-- Qty buttons direct on card -->
                        <template x-if="isInCart(item.id)">
                            <div class="w-full flex items-center justify-between gap-2 bg-koya/5 border border-koya/25 rounded-xl p-1">
                                <button
                                    type="button"
                                    @click="decreaseQty(item.id)"
                                    class="w-9 h-9 rounded-lg bg-white/5 hover:bg-white/10 text-white font-black text-lg flex items-center justify-center transition-colors active:scale-90"
                                >
                                    &minus;
                                </button>
                                <div class="flex-1 text-center select-none">
                                    <span class="text-[10px] text-white/40 block leading-none">In Cart</span>
                                    <span class="text-sm font-black text-koya-gold block mt-0.5" x-text="getCartQty(item.id)"></span>
                                </div>
                                <button
                                    type="button"
                                    @click="increaseQty(item.id)"
                                    class="w-9 h-9 rounded-lg bg-white/5 hover:bg-white/10 text-white font-black text-lg flex items-center justify-center transition-colors active:scale-90"
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
                class="group relative flex flex-col rounded-[28px] border border-koya/30 bg-white/5 shadow-[0_20px_60px_rgba(0,0,0,0.25)] overflow-hidden"
            >
                <div class="p-6 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <h3 class="text-lg font-black text-white leading-tight">{!! __('public.burger_bun_availability') !!}</h3>
                            <span class="text-lg font-black text-koya-gold whitespace-nowrap">2&euro;</span>
                        </div>
                        <p class="text-xs text-white/60 font-light leading-relaxed">{!! __('public.burger_bun_supplement') !!}</p>
                    </div>
                    <div class="pt-4 mt-6 border-t border-white/5">
                        <span class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-white/5 border border-white/10 text-xs font-black uppercase tracking-wider text-white/70">Supplement</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Panel Drawer (Slide-out) -->
        <div x-cloak>
            <!-- Overlay Backdrop -->
            <div
                x-show="showOrderPanel"
                class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm"
                x-transition.opacity
                @click="showOrderPanel = false"
            ></div>
            
            <!-- Side Panel -->
            <div
                class="fixed inset-y-0 right-0 z-50 w-full max-w-md h-screen glass border-l border-white/10 p-6 overflow-y-auto flex flex-col justify-between"
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
                    <div class="flex items-center justify-between border-b border-white/10 pb-4">
                        <div>
                            <h2 class="text-xl font-extrabold uppercase text-white">Your Cart</h2>
                            <p class="text-xs text-white/50 mt-0.5"><span x-text="itemCount()"></span> items selected</p>
                        </div>
                        <button 
                            type="button" 
                            class="p-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-white/70 hover:text-white transition-colors" 
                            @click="showOrderPanel = false"
                            aria-label="Close cart"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Cart Items List -->
                    <div class="mt-6 space-y-3 overflow-y-auto max-h-[calc(100vh-320px)] pr-1 no-scrollbar">
                        <template x-for="item in cart" :key="item.id">
                            <div class="rounded-2xl bg-white/5 border border-white/5 p-4 flex gap-3 items-center justify-between">
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-extrabold text-white truncate" x-html="formatName(item.name)"></h4>
                                    <p class="text-xs text-white/50 mt-0.5">
                                        <span x-text="currency(item.price)"></span> each
                                    </p>
                                    <p class="text-xs text-koya-gold font-bold mt-1">
                                        Line Total: <span x-text="currency(item.quantity * item.price)"></span>
                                    </p>
                                </div>
                                
                                <div class="flex items-center gap-2 shrink-0">
                                    <button 
                                        type="button" 
                                        class="w-7 h-7 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-white font-black text-center flex items-center justify-center active:scale-95" 
                                        @click="decreaseQty(item.id)"
                                    >
                                        -
                                    </button>
                                    <span class="w-6 text-center text-sm font-black text-white" x-text="item.quantity"></span>
                                    <button 
                                        type="button" 
                                        class="w-7 h-7 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-white font-black text-center flex items-center justify-center active:scale-95" 
                                        @click="increaseQty(item.id)"
                                    >
                                        +
                                    </button>
                                    
                                    <button 
                                        type="button" 
                                        class="ml-1 w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500/20 border border-red-500/25 text-red-400 flex items-center justify-center active:scale-95 transition-colors" 
                                        @click="removeItem(item.id)"
                                        title="Remove item"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <div x-show="cart.length === 0" class="py-8 text-center">
                            <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-3 border border-white/5">
                                <svg class="w-6 h-6 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-white/40 font-medium">Your cart is empty.</p>
                            <p class="text-xs text-white/30 mt-1">Tap the plus buttons on menu items to add them here.</p>
                        </div>
                    </div>
                </div>

                <!-- Customer Fields & Checkout Button -->
                <div class="border-t border-white/10 pt-4 mt-4 space-y-4 bg-transparent shrink-0">
                    <div class="bg-[#211915]/50 border border-white/5 p-4 rounded-2xl flex justify-between items-center">
                        <span class="text-sm font-extrabold uppercase text-white/70">Total to Pay</span>
                        <span class="text-2xl font-black text-koya-gold animate-pulse-slow" x-text="currency(total())"></span>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="text-[10px] uppercase tracking-widest text-white/50 font-black block mb-1">Customer Name</label>
                            <input 
                                type="text" 
                                x-model="customerName" 
                                @input="persistState()" 
                                placeholder="Write your full name" 
                                class="w-full text-base rounded-xl px-4 py-3"
                            >
                        </div>
                        <div>
                            <label class="text-[10px] uppercase tracking-widest text-white/50 font-black block mb-1">Phone Number (Optional)</label>
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
                        class="btn-koya w-full py-4 px-6 rounded-2xl text-xs font-black uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-2"
                        :disabled="isSubmitting || cart.length === 0 || !customerName.trim() || offline"
                        @click="submitOrder()"
                    >
                        <span x-show="!isSubmitting" x-text="submitLabel()"></span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-darkBg" fill="none" viewBox="0 0 24 24">
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
                class="pointer-events-auto relative w-16 h-16 rounded-full border border-koya/40 bg-koya text-darkBg shadow-2xl flex items-center justify-center transition-all duration-300 active:scale-90 hover:scale-105"
                :class="cart.length > 0 ? 'bg-koya-gold border-koya-gold shadow-koya/40 animate-pulse-slow' : 'bg-koya'"
                title="Current Order"
                aria-label="Open current order"
            >
                <svg class="w-6.5 h-6.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <!-- Cart Items Counter Badge -->
                <span 
                    class="absolute -top-1 -right-1 min-w-[1.4rem] h-5 px-1 rounded-full bg-red-600 border border-white text-white text-[10px] font-black leading-5 text-center shadow" 
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
            <div class="px-4 py-2.5 rounded-xl border border-koya/30 bg-[#1d1611]/95 text-koya-light text-xs font-black uppercase tracking-widest shadow-2xl flex items-center gap-2">
                <svg class="w-4 h-4 text-koya-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
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
                            throw new Error(payload.message || 'Could not submit your order right now.');
                        }

                        this.successMessage = payload.message || `Order #${payload.order_id} received.`;
                        this.cart = [];
                        this.customerName = '';
                        this.customerPhone = '';
                        this.showOrderPanel = false;
                        this.persistState();
                    } catch (error) {
                        this.errorMessage = error?.message || 'Could not submit your order right now.';
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
                    return item?.image_url || null;
                },

                getDescription(item) {
                    if (item.description && item.description.trim() !== '') {
                        return item.description;
                    }
                    const descriptions = {
                        'Tartufata': 'Double smash burger with premium black truffle paste, melted Swiss cheese, caramelized onions, and truffle mayo on a toasted brioche bun.',
                        'Smash Burger': 'Perfectly seasoned double smash patty with cheddar cheese, crisp lettuce, tomato, pickles, and our signature burger sauce.',
                        'Red Onion': 'Double smash patty with caramelized red onions, melted cheddar, pickles, and dynamic smoky barbecue sauce.',
                        'SOBOKU': 'A simple, clean-tasting single smash patty with cheddar cheese, yellow mustard, and pickles inside a soft bun.',
                        'KOYA SALAD': 'Fresh mix of crisp greens, cherry tomatoes, edamame, pickled cabbage, avocado, and ginger sesame dressing.',
                        'KATSUDON': 'Crispy chicken panko cutlet, simmered eggs, onions, and sweet-savory dashi broth served over steamed rice.',
                        'Mojito': 'Classic refreshing cocktail with rum, lime, and fresh mint.',
                    };
                    return descriptions[item.name] || '';
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
                    return badges[itemName] || null;
                },
            };
        }
    </script>
</body>
</html>


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>KOYA | Our Menu</title>
    
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
            --font-headline: 'Bubbleboddy', 'Outfit', sans-serif;
            --font-body: 'Biski_2', sans-serif;
            --font-accent: 'Biski_2', 'Biski', sans-serif;
            --border-width: 4px;
        }
        
        body {
            background-color: var(--color-green);
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
            font-family: var(--font-headline);
            border: var(--border-width) solid var(--color-dark);
            box-shadow: 4px 4px 0px var(--color-dark);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-koya-primary:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px var(--color-dark);
            background-color: #ffd147;
        }

        .btn-koya-primary:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px var(--color-dark);
        }

        .btn-koya-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--color-light);
            color: var(--color-dark);
            font-family: var(--font-headline);
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

        .btn-koya-secondary:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px var(--color-dark);
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
            color: var(--color-dark);
            cursor: pointer;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            white-space: nowrap;
        }

        .menu-tab:hover {
            color: var(--color-green);
        }

        .menu-tab.active {
            background-color: var(--color-green);
            color: white;
            border-color: var(--color-dark);
            box-shadow: 2px 2px 0px var(--color-dark);
        }

        @media (min-width: 768px) {
            .menu-tab {
                font-size: 1.05rem;
                padding: 0.7rem 1.6rem;
            }
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen pb-28 antialiased overflow-x-hidden selection:bg-koya selection:text-koya-dark relative">

    <!-- Floating Graphics Background -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <!-- Tomato Slice -->
        <svg class="floating-element float-1 opacity-25 md:opacity-40" width="60" height="60" viewBox="0 0 100 100" style="top: 12%; left: 3%;">
            <circle cx="50" cy="50" r="45" fill="#f24423" stroke="#121212" stroke-width="6"/>
            <circle cx="50" cy="50" r="35" fill="#d12e0f" />
            <circle cx="35" cy="40" r="6" fill="#f24423" />
            <circle cx="65" cy="40" r="6" fill="#f24423" />
            <circle cx="50" cy="65" r="6" fill="#f24423" />
        </svg>

        <!-- Cheese Slice -->
        <svg class="floating-element float-2 opacity-25 md:opacity-40" width="70" height="70" viewBox="0 0 100 100" style="bottom: 25%; left: 4%;">
            <polygon points="10,15 90,25 65,85 10,15" fill="#fac021" stroke="#121212" stroke-width="6"/>
            <circle cx="35" cy="40" r="8" fill="#e0ab16" />
            <circle cx="60" cy="50" r="5" fill="#e0ab16" />
        </svg>

        <!-- Lettuce Leaf -->
        <svg class="floating-element float-3 opacity-25 md:opacity-40" width="80" height="80" viewBox="0 0 100 100" style="top: 20%; right: 4%;">
            <path d="M 20 60 C 10 40, 20 15, 50 20 C 80 15, 90 40, 80 60 C 70 80, 30 80, 20 60 Z" fill="#4fa838" stroke="#121212" stroke-width="6"/>
            <path d="M 50 20 Q 50 50 50 75" fill="none" stroke="#121212" stroke-width="4"/>
            <path d="M 50 40 Q 30 35 25 40" fill="none" stroke="#121212" stroke-width="4"/>
            <path d="M 50 55 Q 70 50 75 55" fill="none" stroke="#121212" stroke-width="4"/>
        </svg>

        <!-- Sesame seeds -->
        <svg class="floating-element float-4 opacity-25 md:opacity-40" width="50" height="50" viewBox="0 0 100 100" style="bottom: 15%; right: 6%;">
            <path d="M 30 30 Q 35 15 40 30 Q 35 45 30 30 Z" fill="#ebd6b0" stroke="#121212" stroke-width="3" transform="rotate(15 30 30)"/>
            <path d="M 70 40 Q 75 25 80 40 Q 75 55 70 40 Z" fill="#ebd6b0" stroke="#121212" stroke-width="3" transform="rotate(-20 70 40)"/>
            <path d="M 45 70 Q 50 55 55 70 Q 50 85 45 70 Z" fill="#ebd6b0" stroke="#121212" stroke-width="3" transform="rotate(45 45 70)"/>
        </svg>
    </div>

    <!-- Sticky Navigation Header -->
    <header class="sticky top-0 z-50 bg-[#fac021] border-b-[4px] border-[#121212] py-2 backdrop-blur-md relative">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <img 
                    src="https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg" 
                    alt="KOYA Logo" 
                    class="w-14 h-14 rounded-full border-[3px] border-[#121212] object-cover shadow-[2px_2px_0px_#121212]"
                    onerror="this.src='/favicon.ico'"
                >
                <span class="font-['Bubbleboddy'] text-2xl text-[#121212] tracking-wider uppercase drop-shadow-[2px_2px_0px_#ebd6b0]">KOYA</span>
            </a>
            
            <div class="flex items-center gap-2">
                <a href="/" class="btn-koya-secondary text-[11px] sm:text-xs uppercase py-2 px-3 sm:py-2.5 sm:px-4 rounded-full" style="font-size: 0.75rem; border-width: 3px;">
                    🏠 <span class="hidden sm:inline">Home</span>
                </a>
                <a href="{{ route('order.public') }}" class="btn-koya-primary text-[11px] sm:text-xs uppercase py-2 px-3 sm:py-2.5 sm:px-4 rounded-full" style="font-size: 0.75rem; border-width: 3px; background-color: var(--color-green); color: white;">
                    ⚡ <span class="hidden sm:inline">Order Online</span>
                </a>
            </div>
        </div>
    </header>

    <div
        class="max-w-6xl mx-auto px-4 py-8 space-y-8 relative z-10"
        x-data="menuPage({
            menuItems: @js($menuItemsFlat)
        })"
        x-init="init()"
    >
        <!-- Page Title Banner -->
        <div class="text-center space-y-2 mt-4">
            <h2 class="font-['Bubbleboddy'] text-4xl sm:text-5xl text-white uppercase tracking-tight drop-shadow-[3px_3px_0px_var(--color-dark)]">
                Our Full Menu
            </h2>
            <p class="font-['Biski_2'] text-base text-white/90 max-w-md mx-auto">
                Fusion food prepared with fresh ingredients. Click categories below to view!
            </p>
        </div>

        <!-- Category Horizontal Tabs Container -->
        <div class="sticky top-[76px] z-40 py-4 -mx-4 px-4 bg-gradient-to-b from-[#088972] via-[#088972]/95 to-transparent">
            <div class="flex justify-start md:justify-center overflow-x-auto no-scrollbar pb-1">
                <div class="flex gap-1.5 md:gap-2 bg-[#fac021] p-1.5 md:p-2 rounded-full border-[3px] border-koya-dark">
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                            <template x-if="getImagePath(item.name)">
                                <img 
                                    :src="getImagePath(item.name)" 
                                    :alt="item.name" 
                                    class="max-h-[85%] max-w-[85%] object-contain transition-transform duration-500 group-hover:scale-110 group-hover:rotate-2"
                                    loading="lazy"
                                />
                            </template>
                            
                            <!-- If no image, show placeholder -->
                            <template x-if="!getImagePath(item.name)">
                                <div class="flex flex-col items-center justify-center text-center p-4">
                                    <div class="w-16 h-16 rounded-full bg-koya/20 border-2 border-[#fac021]/30 flex items-center justify-center mb-2">
                                        <span class="text-koya-dark font-black text-xl" x-text="item.name.substring(0, 2).toUpperCase()"></span>
                                    </div>
                                    <span class="text-[9px] uppercase tracking-widest text-[#121212]/40" x-text="item.category"></span>
                                </div>
                            </template>
                            
                            <!-- Badges Overlay -->
                            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5 max-w-[90%] pointer-events-none">
                                <template x-if="item.is_package">
                                    <span class="sticker sticker-green text-[9px] px-2 py-0.5 rounded border border-[#121212]">
                                        Combo Pack
                                    </span>
                                </template>
                                <template x-if="getBadge(item.name)">
                                    <span class="sticker text-[9px] px-2 py-0.5 rounded border border-[#121212]" x-text="getBadge(item.name)"></span>
                                </template>
                            </div>
                        </div>

                        <!-- Product Title and Price -->
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <h3 class="font-['Bubbleboddy'] text-xl text-koya-dark uppercase tracking-wide leading-tight group-hover:text-koya-green transition-colors" x-text="item.name"></h3>
                            <span class="font-bold text-xl text-koya-green whitespace-nowrap" x-text="currency(item.price)"></span>
                        </div>
                        
                        <!-- Description -->
                        <p 
                            class="font-['Biski_2'] text-sm text-[#555] leading-relaxed mb-4 line-clamp-3 min-h-[3.375rem]" 
                            x-text="getDescription(item)"
                        ></p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Sticky bottom call to action -->
        <div class="fixed bottom-6 inset-x-4 z-40 max-w-md mx-auto pointer-events-none">
            <div class="pointer-events-auto bg-[#fac021] border-[3px] border-[#121212] rounded-2xl p-4 shadow-[4px_4px_0px_#121212] flex items-center justify-between gap-4">
                <div>
                    <h4 class="font-['Bubbleboddy'] text-sm font-black text-[#121212] uppercase tracking-wider">Ready to taste?</h4>
                    <p class="font-['Biski_2'] text-xs text-[#121212]/80 mt-0.5">Order now for takeaway or delivery!</p>
                </div>
                <a 
                    href="{{ route('order.public') }}" 
                    class="btn-koya-primary px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap"
                    style="font-size: 0.75rem; border-width: 3px; background-color: var(--color-green); color: white;"
                >
                    Order Online
                </a>
            </div>
        </div>
    </div>

    <!-- Page Logic Script -->
    <script>
        function menuPage(config) {
            return {
                menuItems: Array.isArray(config.menuItems) ? config.menuItems : [],
                activeCategory: 'All',

                init() {
                    // Default to first category
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

                currency(value) {
                    const amount = Number(value || 0);
                    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(amount);
                },

                getImagePath(itemName) {
                    const images = {
                        'Classic Smash Burger': 'smash-burger.png',
                        'Truffle Double Smash': 'tartufata.png',
                        'Tartufata': 'tartufata.png',
                        'Smash Burger': 'smash-burger.png',
                        'Red Onion': 'red-onion.png',
                        'SOBOKU': 'soboku.png',
                        'Family Takeaway Pack': 'smash-burger.png',
                        'Spicy Korean Rice Bowl': 'katsudon.png',
                        'Mediterranean Rice Bowl': 'koya-salad.png',
                        'KOYA SALAD': 'koya-salad.png',
                        'KATSUDON': 'katsudon.png',
                    };
                    const name = images[itemName];
                    return name ? `/images/${name}` : null;
                },

                getDescription(item) {
                    if (item.package_details && item.package_details.trim() !== '') {
                        return item.package_details;
                    }
                    const descriptions = {
                        'Classic Smash Burger': 'Two smashed beef patties, cheddar cheese, pickles, and our signature KOYA sauce in a toasted brioche bun.',
                        'Truffle Double Smash': 'Double smashed beef patty, truffle mayo, caramelized onions, and melted Swiss cheese.',
                        'Tartufata': 'Double smash burger with premium black truffle paste, melted Swiss cheese, caramelized onions, and truffle mayo on a toasted brioche bun.',
                        'Smash Burger': 'Perfectly seasoned double smash patty with cheddar cheese, crisp lettuce, tomato, pickles, and our signature burger sauce.',
                        'Red Onion': 'Double smash patty with caramelized red onions, melted cheddar, pickles, and dynamic smoky barbecue sauce.',
                        'SOBOKU': 'A simple, clean-tasting single smash patty with cheddar cheese, yellow mustard, and pickles inside a soft bun.',
                        'Loaded Nachos': 'Crispy tortilla chips topped with cheese sauce, guacamole, pico de gallo, and jalapeños.',
                        'Crispy Chicken Bites': 'Tender buttermilk chicken bites served with honey mustard sauce.',
                        'Spicy Korean Rice Bowl': 'Juicy chicken bites glazed in gochujang sauce, steamed rice, pickled radish, edamame, and sesame.',
                        'Mediterranean Rice Bowl': 'Grilled salmon or falafel, quinoa, cherry tomatoes, cucumbers, tzatziki sauce, and fresh herbs.',
                        'KOYA SALAD': 'Fresh mix of crisp greens, cherry tomatoes, edamame, pickled cabbage, avocado, and ginger sesame dressing.',
                        'KATSUDON': 'Crispy chicken panko cutlet, simmered eggs, onions, and sweet-savory dashi broth served over steamed rice.',
                        'House Fries': 'Golden crispy fries seasoned with our house spice blend.',
                        'Sweet Potato Fries': 'Sweet potato fries served with chipotle garlic mayo.',
                        'Cheesecake Jar': 'Creamy NY-style cheesecake with red berry coulis in a jar.',
                        'Chocolate Brownie': 'Warm chocolate brownie served with a scoop of vanilla ice cream.',
                        'Homemade Lemonade': 'Freshly squeezed lemons with a touch of mint and honey.',
                        'Local Lager': 'Cold, refreshing local lager.',
                        'House IPA': 'Cold, craft India Pale Ale with citrus notes.',
                        'Mojito': 'Classic refreshing cocktail with rum, lime, and fresh mint.',
                        'Still Water': 'Refreshing mineral water.',
                        'Espresso': 'Freshly brewed aromatic espresso.',
                        'Lunch Combo': 'Burger or rice bowl, side, and drink.',
                        'Family Takeaway Pack': 'Four burgers, two large fries, and four drinks.'
                    };
                    return descriptions[item.name] || 'Delicious KOYA recipe prepared with fresh ingredients.';
                },

                getBadge(itemName) {
                    const badges = {
                        'Classic Smash Burger': 'Favorite',
                        'Truffle Double Smash': 'Popular',
                        'Spicy Korean Rice Bowl': 'New',
                        'Cheesecake Jar': 'Favorite',
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

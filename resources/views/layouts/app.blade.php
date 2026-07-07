<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Sample Restaurant</title>
        <link rel="icon" type="image/png" href="https://grandrestaurantv7-0.b-cdn.net/bistro/wp-content/uploads/sites/3/2024/08/logo-white.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Flatpickr -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_orange.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
        
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            koya: {
                                DEFAULT: '#c89b3c',
                                dark: '#17110c',
                                glass: 'rgba(35, 27, 21, 0.82)',
                            }
                        },
                        fontFamily: {
                            sans: ['Outfit', 'sans-serif'],
                            display: ['Cormorant Garamond', 'serif'],
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

            :root {
                --koya: #c89b3c;
                --bg: #17110c;
                --white: #fff7eb;
                --white-muted: rgba(255, 255, 255, 0.6);
            }

            body { 
                background-color: var(--bg); 
                color: var(--white); 
                -webkit-tap-highlight-color: transparent; 
                font-family: 'Outfit', sans-serif;
                margin: 0;
                line-height: 1.6;
            }

            /* Glassmorphism System */
            .glass { 
                background: rgba(20, 20, 20, 0.75); 
                backdrop-filter: blur(20px); 
                -webkit-backdrop-filter: blur(20px); 
                border: 1px solid rgba(255, 255, 255, 0.08);
            }

            .btn-koya { 
                background-color: var(--koya); 
                color: #17110c; 
                font-family: 'Biski', sans-serif;
                border-radius: 12px; 
                font-weight: 800; 
                text-transform: uppercase;
                letter-spacing: 0.1em;
                padding: 1rem 1.5rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 10px 30px -10px rgba(200, 155, 60, 0.36);
                border: none;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .btn-koya:active { transform: scale(0.96); }

            .bottom-nav-active { 
                color: var(--koya); 
                filter: drop-shadow(0 0 10px rgba(200, 155, 60, 0.3));
            }

            .bottom-menu-panel {
                background: linear-gradient(180deg, rgba(28, 28, 28, 0.96) 0%, rgba(18, 18, 18, 0.94) 100%);
                border: 1px solid rgba(255, 255, 255, 0.08);
                box-shadow: 0 -24px 50px -28px rgba(0, 0, 0, 0.9);
            }

            .bottom-menu-item {
                display: flex;
                align-items: center;
                gap: 0.85rem;
                border-radius: 0.95rem;
                padding: 0.8rem 0.95rem;
                border: 1px solid rgba(255, 255, 255, 0.08);
                color: rgba(255, 255, 255, 0.76);
                background: rgba(255, 255, 255, 0.02);
                transition: all 0.2s ease;
            }

            .bottom-menu-item:hover {
                border-color: rgba(255, 92, 0, 0.32);
                color: #ffffff;
                background: rgba(255, 92, 0, 0.08);
                transform: translateY(-1px);
            }

            .bottom-menu-item.active {
                color: #ffffff;
                border-color: rgba(255, 92, 0, 0.45);
                background: linear-gradient(135deg, rgba(255, 92, 0, 0.2), rgba(255, 92, 0, 0.08));
            }

            .bottom-quick-link {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 0.3rem;
                width: 100%;
                color: rgba(255, 255, 255, 0.45);
                transition: all 0.2s ease;
                text-align: center;
            }

            .bottom-quick-link:hover {
                color: rgba(255, 255, 255, 0.9);
            }

            .bottom-quick-link.active {
                color: var(--koya);
                filter: drop-shadow(0 0 8px rgba(255, 92, 0, 0.3));
            }

            /* Inputs */
            input, select, textarea {
                width: 100%;
                color: white !important;
                background: rgba(255, 255, 255, 0.03) !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                border-radius: 16px !important;
                padding: 1rem 1.25rem !important;
                font-family: 'Outfit', sans-serif !important;
                outline: none !important;
                transition: all 0.3s ease;
            }
            input:focus {
                border-color: var(--koya) !important;
                background: rgba(255, 255, 255, 0.05) !important;
            }

            select option {
                background-color: #1a1a1a;
                color: white;
            }

            label {
                display: block;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.15em;
                color: var(--white-muted);
                margin-bottom: 0.5rem;
                padding-left: 0.25rem;
            }
            [x-cloak] { display: none !important; }
        </style>
        @include('layouts.restaurant-theme')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            <!-- Main Content -->
            <main class="px-5 pt-24 pb-8">
                @if (isset($header))
                    <div class="mb-8 px-2">
                        <h2 class="font-display text-4xl font-bold text-[#fff7eb] tracking-normal">
                            {{ $header }}
                        </h2>
                    </div>
                @endif
                {{ $slot }}
            </main>

            @php
                $bottomMenuItems = [
                    ['route' => route('tables.index'), 'active' => request()->routeIs('tables.*') && !request()->routeIs('tables.kitchen'), 'label' => 'Tables', 'icon' => 'M4 10h16M4 14h16M8 6h8m-8 12h8M6 18h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z'],
                    ['route' => route('tables.kitchen'), 'active' => request()->routeIs('tables.kitchen'), 'label' => 'Kitchen', 'icon' => 'M3 8h18M6 8V6a2 2 0 012-2h8a2 2 0 012 2v2M7 8v8a2 2 0 002 2h6a2 2 0 002-2V8M10 12h4'],
                    ['route' => route('reservations.index'), 'active' => request()->routeIs('reservations.*'), 'label' => 'Reservations', 'icon' => 'M8 7V3m8 4V3M6 11h12M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ];

                if (auth()->user()->isAdmin()) {
                    $bottomMenuItems[] = ['route' => route('finance.stats'), 'active' => request()->routeIs('finance.*'), 'label' => 'Finance', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'];
                    $bottomMenuItems[] = ['route' => route('restaurant.config'), 'active' => request()->routeIs('restaurant.config'), 'label' => 'Config', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'];
                    $bottomMenuItems[] = ['route' => route('menu-items.index'), 'active' => request()->routeIs('menu-items.*'), 'label' => 'Menu', 'icon' => 'M6 3v8M9 3v8M6 7h3M7.5 11v10M15 3v20M15 3c3 0 4 2.5 4 5.5S18 14 15 14'];
                }

                if (auth()->user()->isUserAdmin()) {
                    $bottomMenuItems[] = ['route' => route('users.index'), 'active' => request()->routeIs('users.*'), 'label' => 'Users', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'];
                }

                $primaryMenuItems = array_slice($bottomMenuItems, 0, 3);
                $overflowMenuItems = array_slice($bottomMenuItems, 3);
                $menuDropdownItems = array_merge($overflowMenuItems, [
                    [
                        'route' => route('takeaway.public'),
                        'active' => false,
                        'label' => 'Takeaway',
                        'icon' => 'M3 5h18M7 5v14m10-14v14M5 19h14',
                        'target' => '_blank',
                        'rel' => 'noopener',
                    ],
                    [
                        'route' => route('profile.edit'),
                        'active' => request()->routeIs('profile.*'),
                        'label' => 'Profile',
                        'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                    ],
                ]);
                $hasOverflowMenu = count($menuDropdownItems) > 0;
            @endphp

            <!-- Global Navigation (Top) -->
            <nav x-data="{ open: false }" @keydown.escape.window="open = false" class="fixed top-0 left-0 right-0 z-50">
                <div class="glass border-y border-white/10 px-5 py-3 flex items-center justify-between shadow-2xl">
                    <a href="{{ route('dashboard') }}" class="shrink-0 mr-3">
                        <x-application-logo class="h-10 w-auto" />
                    </a>
                    <div class="bottom-primary-links flex-1 grid gap-1 pr-1" style="grid-template-columns: repeat({{ max(count($primaryMenuItems), 1) }}, minmax(0, 1fr));">
                        @foreach($primaryMenuItems as $item)
                            <a href="{{ $item['route'] }}" class="bottom-quick-link {{ $item['active'] ? 'active' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $item['icon'] }}"></path>
                                </svg>
                                <span class="text-[9px] font-black uppercase tracking-wider">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                    @if($hasOverflowMenu)
                        <button
                            type="button"
                            @click="open = !open"
                            :aria-expanded="open.toString()"
                            aria-controls="bottom-hamburger-menu"
                            class="ml-2 w-10 h-10 rounded-xl bg-koya/15 border border-koya/30 text-koya hover:bg-koya hover:text-white transition-all duration-200 flex items-center justify-center shrink-0"
                        >
                            <svg x-show="!open" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 7h16M4 12h16M4 17h16"></path>
                            </svg>
                            <svg x-show="open" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>

                @if($hasOverflowMenu)
                    <div
                        id="bottom-hamburger-menu"
                        x-cloak
                        x-show="open"
                        x-transition:enter="transition ease-out duration-250"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-180"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-3"
                        @click.outside="open = false"
                        class="bottom-menu-panel mt-3 mx-5 rounded-2xl p-3"
                    >
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($menuDropdownItems as $item)
                                <a href="{{ $item['route'] }}" @click="open = false" class="bottom-menu-item {{ $item['active'] ? 'active' : '' }}" @if(!empty($item['target'])) target="{{ $item['target'] }}" @endif @if(!empty($item['rel'])) rel="{{ $item['rel'] }}" @endif>
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $item['icon'] }}"></path>
                                    </svg>
                                    <span class="text-[11px] font-black uppercase tracking-widest truncate">{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="w-full bottom-menu-item text-red-400 hover:text-red-300" @click="open = false">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span class="text-[11px] font-black uppercase tracking-widest">Logout</span>
                            </button>
                        </form>
                    </div>
                @endif
            </nav>
        </div>
    </body>
</html>

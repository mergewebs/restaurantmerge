<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @include('partials.seo', ['seo' => \App\Support\Seo::noIndex(['title' => 'Admin Dashboard | '.config('app.name')])])
        @include('partials.favicon')

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
                            accent: {
                                DEFAULT: '#4f46e5',
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
                --accent: #c89b3c;
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

            .btn-accent { 
                background-color: var(--accent); 
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
            .btn-accent:active { transform: scale(0.96); }

            .bottom-nav-active { 
                color: var(--accent); 
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
                width: auto;
                color: rgba(255, 255, 255, 0.45);
                transition: all 0.2s ease;
                text-align: center;
            }

            .bottom-quick-link:hover {
                color: rgba(255, 255, 255, 0.9);
            }

            .bottom-quick-link.active {
                color: var(--accent);
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
                font-size: 1rem !important;
                font-family: 'Outfit', sans-serif !important;
                outline: none !important;
                transition: all 0.3s ease;
            }
            input:focus {
                border-color: var(--accent) !important;
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

            /* Animated Background Blobs */
            .bg-glow-container {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: -1;
                overflow: hidden;
                pointer-events: none;
            }
            .bg-glow-blob {
                position: absolute;
                border-radius: 50%;
                filter: blur(120px);
                opacity: 0.05;
                animation: floatBlob 25s infinite alternate ease-in-out;
            }
            .bg-glow-1 {
                top: -10%;
                left: -10%;
                width: 50vw;
                height: 50vw;
                background-color: var(--accent);
                animation-duration: 35s;
            }
            .bg-glow-2 {
                bottom: -15%;
                right: -5%;
                width: 60vw;
                height: 60vw;
                background-color: #ff5c00;
                animation-duration: 45s;
                animation-delay: -5s;
            }
            .bg-glow-3 {
                top: 40%;
                left: 60%;
                width: 40vw;
                height: 40vw;
                background-color: #10b981;
                opacity: 0.03;
                animation-duration: 40s;
                animation-delay: -10s;
            }

            @keyframes floatBlob {
                0% {
                    transform: translate(0, 0) scale(1) rotate(0deg);
                }
                50% {
                    transform: translate(8%, 12%) scale(1.1) rotate(180deg);
                }
                100% {
                    transform: translate(-5%, -8%) scale(0.9) rotate(360deg);
                }
            }

            [data-async-region] {
                position: relative;
                transition: opacity 0.2s ease;
            }

            [data-async-region].is-async-loading {
                pointer-events: none;
            }

            [data-async-region].is-async-loading > * {
                opacity: 0.5;
                transition: opacity 0.2s ease;
            }

            [data-async-region]::after {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: inherit;
                background: rgba(255, 255, 255, 0);
                opacity: 0;
                transition: opacity 0.2s ease;
                pointer-events: none;
            }

            [data-async-region].is-async-loading::after {
                background: rgba(255, 255, 255, 0.3);
                opacity: 1;
            }

            .async-loading-badge {
                position: absolute;
                top: 0.75rem;
                right: 0.75rem;
                z-index: 5;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.55rem 0.8rem;
                border-radius: 999px;
                background: rgba(23, 17, 12, 0.92);
                color: #fff7eb;
                font-size: 0.68rem;
                font-weight: 800;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                box-shadow: 0 10px 28px rgba(15, 23, 42, 0.18);
                opacity: 0;
                transform: translateY(-6px);
                transition: opacity 0.2s ease, transform 0.2s ease;
                pointer-events: none;
            }

            [data-async-region].is-async-loading .async-loading-badge {
                opacity: 1;
                transform: translateY(0);
            }

            .async-loading-spinner {
                width: 0.9rem;
                height: 0.9rem;
                border-radius: 999px;
                border: 2px solid rgba(255, 247, 235, 0.25);
                border-top-color: var(--accent);
                animation: asyncSpin 0.75s linear infinite;
                flex-shrink: 0;
            }
            @keyframes asyncSpin {
                to { transform: rotate(360deg); }
            }
        </style>
        @include('layouts.admin-theme')
        @include('partials.toast-notifications')
    </head>
    <body class="font-sans antialiased relative">
        <div class="bg-glow-container">
            <div class="bg-glow-blob bg-glow-1"></div>
            <div class="bg-glow-blob bg-glow-2"></div>
            <div class="bg-glow-blob bg-glow-3"></div>
        </div>
        <div class="min-h-screen relative">
            <header class="mobile-admin-header lg:hidden">
                <a href="{{ route('dashboard') }}" class="mobile-admin-brand" aria-label="Restaurant dashboard">
                    <img src="{{ asset('restaurant-mark.svg') }}" alt="" width="40" height="40">
                    <span><strong>{{ config('restaurant.name') }}</strong><small>{{ ucfirst(auth()->user()->role) }}</small></span>
                </a>

                <div x-data="{ open: false }" @click.outside="open = false" class="mobile-profile-menu">
                    <button type="button" @click="open = !open" class="mobile-admin-profile-button" :aria-expanded="open" aria-haspopup="menu">
                        <span class="mobile-admin-avatar">{{ strtoupper(mb_substr(auth()->user()->name, 0, 2)) }}</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-cloak x-show="open" x-transition class="mobile-profile-dropdown" role="menu">
                        <a href="{{ route('profile.edit') }}" role="menuitem">Profile</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('restaurant.config') }}" role="menuitem">Settings</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Are you sure you want to log out?')">
                            @csrf

                            <button type="submit" class="mobile-profile-logout" role="menuitem">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="admin-main px-5 pt-32 lg:pt-36 pb-8">
                @php
                    [$pageTitle, $pageDescription] = match (true) {
                        request()->routeIs('dashboard') => ['Today’s Service', 'Reservations, service and cash flow at a glance.'],
                        request()->routeIs('orders.*') => ['Orders', 'Keep every order moving from the first item to payment.'],
                        request()->routeIs('tables.kitchen*') => ['Kitchen Pass', 'Keep every plate moving and every table informed.'],
                        request()->routeIs('tables.*') => ['Dining Room', 'Manage tables, orders and the rhythm of service.'],
                        request()->routeIs('reservations.*') => ['Reservations', 'Plan the room and welcome every guest well.'],
                        request()->routeIs('finance.*') => ['Finance', 'A clear daily view of sales, costs and performance.'],
                        request()->routeIs('menu-items.*') => ['Menu', 'Keep dishes, pricing and availability up to date.'],
                        request()->routeIs('inventory.*') => ['Inventory', 'Track stock, suppliers, purchasing and waste.'],
                        request()->routeIs('cash-register.*') => ['Cash Register', 'Payments, refunds and daily cash reconciliation.'],
                        request()->routeIs('users.*') || request()->routeIs('staff.*') => ['Staff Management', 'Schedules, attendance, assignments and permissions.'],
                        request()->routeIs('restaurant.*') => ['Restaurant Settings', 'Opening hours, closures and service capacity.'],
                        request()->routeIs('profile.*') => ['Your Profile', 'Personal details and account security.'],
                        default => ['Restaurant Workspace', 'Everything your team needs for a smooth service.'],
                    };
                @endphp
                <section class="admin-service-heading" aria-labelledby="admin-page-title">
                    <div class="admin-service-copy">
                        <p class="admin-service-eyebrow">
                            <span></span>
                            Merge Service Desk
                        </p>
                        <h1 id="admin-page-title">{{ isset($header) ? $header : $pageTitle }}</h1>
                        <p>{{ $pageDescription }}</p>
                    </div>
                    <div class="admin-service-meta">
                        <span class="admin-service-day">{{ now()->format('l') }}</span>
                        <strong>{{ now()->format('d M') }}</strong>
                        <small>{{ ucfirst(auth()->user()->role) }} shift</small>
                    </div>
                </section>
                {{ $slot }}
            </main>

            @php
                $tablesFeatureEnabled = config('features.tables', false);
                $kitchenFeatureEnabled = config('features.kitchen', false);
                $bottomMenuItems = [];
                $bottomMenuItems[] = ['route' => route('dashboard'), 'active' => request()->routeIs('dashboard'), 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'];
                $bottomMenuItems[] = ['route' => route('orders.index'), 'active' => request()->routeIs('orders.*'), 'label' => 'Orders', 'icon' => 'M9 5h6M9 9h6M9 13h3m-7 7h14a2 2 0 002-2V4a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'];

                if ($tablesFeatureEnabled) {
                    $bottomMenuItems[] = ['route' => route('tables.index'), 'active' => request()->routeIs('tables.*') && !request()->routeIs('tables.kitchen'), 'label' => 'Tables', 'icon' => 'M4 10h16M4 14h16M8 6h8m-8 12h8M6 18h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z'];
                }

                if ($kitchenFeatureEnabled) {
                    $bottomMenuItems[] = ['route' => route('tables.kitchen'), 'active' => request()->routeIs('tables.kitchen'), 'label' => 'Kitchen', 'icon' => 'M3 8h18M6 8V6a2 2 0 012-2h8a2 2 0 012 2v2M7 8v8a2 2 0 002 2h6a2 2 0 002-2V8M10 12h4'];
                }

                $bottomMenuItems[] = ['route' => route('reservations.index'), 'active' => request()->routeIs('reservations.*'), 'label' => 'Reservations', 'icon' => 'M8 7V3m8 4V3M6 11h12M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'];

                if (auth()->user()->isAdmin()) {
                    $bottomMenuItems[] = ['route' => route('finance.index'), 'active' => request()->routeIs('finance.*'), 'label' => 'Finance', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'];
                    $bottomMenuItems[] = ['route' => route('menu-items.index'), 'active' => request()->routeIs('menu-items.*'), 'label' => 'Menu', 'icon' => 'M6 3v8M9 3v8M6 7h3M7.5 11v10M15 3v20M15 3c3 0 4 2.5 4 5.5S18 14 15 14'];
                    $bottomMenuItems[] = ['route' => route('inventory.index'), 'active' => request()->routeIs('inventory.*'), 'label' => 'Inventory', 'icon' => 'M20 7l-8-4-8 4m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'];
                    $bottomMenuItems[] = ['route' => route('cash-register.index'), 'active' => request()->routeIs('cash-register.*'), 'label' => 'Register', 'icon' => 'M17 9V7a5 5 0 00-10 0v2M5 9h14l1 12H4L5 9zm4 4h6'];
                }

                $bottomMenuItems[] = ['route' => route('users.index'), 'active' => request()->routeIs('users.*') || request()->routeIs('staff.*'), 'label' => 'Staff', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'];

                $primaryMenuItems = $bottomMenuItems;
            @endphp

            <!-- Global Navigation (Top) -->
            <nav class="admin-navigation fixed left-0 right-0 z-50" aria-label="Primary navigation">
                <div class="glass border-y border-white/10 px-5 py-3 flex items-center justify-center shadow-2xl">
                    <a href="{{ route('dashboard') }}" class="admin-brand-lockup mr-5 shrink-0" aria-label="Restaurant dashboard">
                        <img src="{{ asset('restaurant-mark.svg') }}" alt="" class="h-11 w-11 rounded-xl border-2 border-white/20 object-cover shadow-lg" width="44" height="44">
                        <span><strong>{{ config('restaurant.name') }}</strong><small>{{ config('app.name') }}</small></span>
                    </a>
                    <div class="bottom-primary-links flex items-center gap-10 md:gap-16 pr-1">
                        @foreach($primaryMenuItems as $item)
                            <a href="{{ $item['route'] }}" class="bottom-quick-link {{ $item['active'] ? 'active' : '' }}" @if(!empty($item['target'])) target="{{ $item['target'] }}" @endif @if(!empty($item['rel'])) rel="{{ $item['rel'] }}" @endif>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $item['icon'] }}"></path>
                                </svg>
                                <span class="text-[9px] font-black uppercase tracking-wider hidden lg:inline">{{ $item['label'] }}</span>
                            </a>
                        @endforeach

                        <div x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false" class="relative">
                            <button @click="dropdownOpen = !dropdownOpen" class="bottom-quick-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" title="Profile">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-[9px] font-black uppercase tracking-wider hidden lg:inline">Profile</span>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-cloak x-show="dropdownOpen" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 top-full mt-2 w-48 bg-white border border-gray-100 rounded-2xl shadow-xl p-1.5 z-50 dropdown-menu-container">
                                
                                <!-- Profile link in dropdown -->
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:text-indigo-600 hover:bg-indigo-50/50 rounded-lg transition duration-150 {{ request()->routeIs('profile.*') ? 'text-indigo-600 bg-indigo-50/30' : '' }}">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile
                                </a>
                                
                                <!-- Config Link -->
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('restaurant.config') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:text-indigo-600 hover:bg-indigo-50/50 rounded-lg transition duration-150 {{ request()->routeIs('restaurant.config') ? 'text-indigo-600 bg-indigo-50/30' : '' }}">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        </svg>
                                        Config
                                    </a>
                                @endif
                                
                                <hr class="my-1 border-gray-100">
                                
                                <!-- Logout Button -->
                                <form method="POST" action="{{ route('logout') }}" class="w-full" onsubmit="return confirm('Are you sure you want to log out?')">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-red-500 hover:text-red-700 hover:bg-red-50/50 rounded-lg transition duration-150">
                                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const autoSubmitTimers = new WeakMap();

                const getAutoSubmitTimerMap = (form) => {
                    if (!autoSubmitTimers.has(form)) {
                        autoSubmitTimers.set(form, new Map());
                    }

                    return autoSubmitTimers.get(form);
                };

                const submitAutoSubmitForm = (form) => {
                    if (!(form instanceof HTMLFormElement)) {
                        return;
                    }

                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                        return;
                    }

                    form.submit();
                };

                const toggleAutoSubmitPanels = (field) => {
                    const targetName = field.dataset.rangeToggleTarget;
                    if (!targetName) {
                        return;
                    }

                    const targetValue = field.dataset.rangeToggleValue || 'custom';
                    document.querySelectorAll(`[data-range-toggle-panel="${targetName}"]`).forEach((panel) => {
                        panel.classList.toggle('hidden', field.value !== targetValue);
                    });
                };

                const shouldDelayAutoSubmit = (field) => {
                    const rangePanel = field.closest('[data-range-toggle-panel]');
                    if (!rangePanel) {
                        return false;
                    }

                    const rangeFields = Array.from(rangePanel.querySelectorAll('input[type="date"]'));
                    return rangeFields.length > 1 && rangeFields.some((rangeField) => !rangeField.value);
                };

                const initializeAutoSubmitForms = (root = document) => {
                    root.querySelectorAll('form[data-auto-submit-form]').forEach((form) => {
                        form.querySelectorAll('[data-range-toggle-target]').forEach((field) => {
                            if (field instanceof HTMLElement) {
                                toggleAutoSubmitPanels(field);
                            }
                        });
                    });
                };

                initializeAutoSubmitForms();

                document.addEventListener('input', (event) => {
                    const field = event.target;
                    if (!(field instanceof HTMLElement) || !field.hasAttribute('data-auto-submit-search')) {
                        return;
                    }

                    const form = field.closest('form[data-auto-submit-form]');
                    if (!(form instanceof HTMLFormElement)) {
                        return;
                    }

                    const defaultDelay = Number(form.dataset.autoSubmitDelay || 650);
                    const fieldDelay = Number(field.dataset.autoSubmitDelay || defaultDelay);
                    const timers = getAutoSubmitTimerMap(form);
                    const key = field.getAttribute('name') || field.id || 'search';

                    window.clearTimeout(timers.get(key));
                    timers.set(key, window.setTimeout(() => submitAutoSubmitForm(form), fieldDelay));
                });

                document.addEventListener('change', (event) => {
                    const field = event.target;
                    if (!(field instanceof HTMLElement)) {
                        return;
                    }

                    const form = field.closest('form[data-auto-submit-form]');
                    if (!(form instanceof HTMLFormElement) || field.matches('input[type="hidden"]') || field.disabled) {
                        return;
                    }

                    const skipValue = field.dataset.autoSubmitSkipOn;

                    if (field.hasAttribute('data-range-toggle-target')) {
                        toggleAutoSubmitPanels(field);
                    }

                    if (skipValue && 'value' in field && field.value === skipValue) {
                        return;
                    }

                    if (field.hasAttribute('data-auto-submit-search')) {
                        return;
                    }

                    if (shouldDelayAutoSubmit(field)) {
                        return;
                    }

                    submitAutoSubmitForm(form);
                });
                const showToast = (message, level = 'info') => {
                    window.RestaurantNotifications?.show(message, level);
                };

                const isSameOriginUrl = (href) => {
                    try {
                        const url = new URL(href, window.location.href);

                        return url.origin === window.location.origin;
                    } catch (error) {
                        return false;
                    }
                };

                const shouldInterceptLink = (link) => {
                    if (!(link instanceof HTMLAnchorElement)) {
                        return false;
                    }

                    const href = link.getAttribute('href');

                    if (!href || href.startsWith('#') || link.target === '_blank' || link.hasAttribute('download')) {
                        return false;
                    }

                    return isSameOriginUrl(href);
                };

                const shouldInterceptForm = (form, region) => {
                    if (!(form instanceof HTMLFormElement)) {
                        return false;
                    }

                    if (form.dataset.async === 'off') {
                        return false;
                    }

                    const method = (form.getAttribute('method') || 'GET').toUpperCase();

                    if (method !== 'GET' && region.dataset.asyncMutations !== 'true') {
                        return false;
                    }

                    return true;
                };

                const ensureLoadingBadge = (region) => {
                    let badge = region.querySelector(':scope > .async-loading-badge');

                    if (!badge) {
                        badge = document.createElement('div');
                        badge.className = 'async-loading-badge';
                        badge.innerHTML = '<span class="async-loading-spinner"></span><span>Updating</span>';
                        region.prepend(badge);
                    }

                    return badge;
                };

                const extractFeedback = (region) => {
                    const feedback = region.querySelector('[data-async-feedback]');

                    if (!feedback) {
                        return null;
                    }

                    return {
                        message: feedback.textContent.trim(),
                        level: feedback.dataset.feedbackLevel || 'info',
                    };
                };

                const parseHtml = (html) => {
                    const parser = new DOMParser();

                    return parser.parseFromString(html, 'text/html');
                };

                const reinitializeRegion = (region) => {
                    if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                        window.Alpine.initTree(region);
                    }
                };

                const replaceRegion = (currentRegion, nextRegion) => {
                    currentRegion.replaceWith(nextRegion);
                    reinitializeRegion(nextRegion);
                    initializeAsyncRegions(nextRegion.parentElement || document);

                    return nextRegion;
                };

                const buildGetUrl = (form) => {
                    const action = form.getAttribute('action') || window.location.href;
                    const url = new URL(action, window.location.href);
                    const formData = new FormData(form);

                    url.search = '';

                    for (const [key, value] of formData.entries()) {
                        if (typeof value !== 'string') {
                            continue;
                        }

                        url.searchParams.append(key, value);
                    }

                    return url;
                };

                const submitRegionRequest = async (region, request) => {
                    const regionName = region.dataset.asyncRegion;

                    if (!regionName) {
                        return;
                    }

                    if (region._asyncAbortController) {
                        region._asyncAbortController.abort();
                    }

                    const controller = new AbortController();
                    region._asyncAbortController = controller;

                    ensureLoadingBadge(region);
                    region.classList.add('is-async-loading');

                    try {
                        const response = await fetch(request.url, {
                            method: request.method,
                            body: request.body,
                            headers: {
                                'X-Async-Request': '1',
                            },
                            credentials: 'same-origin',
                            signal: controller.signal,
                        });

                        const html = await response.text();
                        const documentFragment = parseHtml(html);
                        const nextRegion = documentFragment.querySelector(`[data-async-region="${regionName}"]`);

                        if (!nextRegion) {
                            window.location.assign(response.url || request.url);
                            return;
                        }

                        if (documentFragment.title) {
                            document.title = documentFragment.title;
                        }

                        const replacedRegion = replaceRegion(region, nextRegion);
                        window.history.replaceState({}, document.title, response.url || request.url);

                        window.RestaurantNotifications?.consumeFeedback(replacedRegion);

                        const autofocusTarget = replacedRegion.querySelector('[data-async-autofocus], .text-red-500, .text-red-600, .text-red-700, [aria-invalid="true"]');
                        if (autofocusTarget instanceof HTMLElement && request.method !== 'GET') {
                            autofocusTarget.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    } catch (error) {
                        if (error.name !== 'AbortError') {
                            showToast('We could not update this section. Please try again.', 'error');
                        }
                    } finally {
                        region.classList.remove('is-async-loading');
                    }
                };

                const initializeAsyncRegions = (root = document) => {
                    root.querySelectorAll('[data-async-region]').forEach((region) => {
                        if (region.dataset.asyncReady === 'true') {
                            return;
                        }

                        region.dataset.asyncReady = 'true';
                        ensureLoadingBadge(region);

                        region.addEventListener('submit', (event) => {
                            const form = event.target;

                            if (!shouldInterceptForm(form, region)) {
                                return;
                            }

                            event.preventDefault();

                            const method = (form.getAttribute('method') || 'GET').toUpperCase();
                            if (method === 'GET') {
                                submitRegionRequest(region, {
                                    method: 'GET',
                                    url: buildGetUrl(form),
                                });
                                return;
                            }

                            submitRegionRequest(region, {
                                method,
                                url: new URL(form.getAttribute('action') || window.location.href, window.location.href),
                                body: new FormData(form),
                            });
                        });

                        region.addEventListener('click', (event) => {
                            const link = event.target.closest('a');

                            if (!shouldInterceptLink(link) || link.dataset.async === 'off') {
                                return;
                            }

                            event.preventDefault();
                            submitRegionRequest(region, {
                                method: 'GET',
                                url: new URL(link.href, window.location.href),
                            });
                        });
                    });
                };

                initializeAsyncRegions();
            });
        </script>
        @include('partials.form-submit-lock')
</body>
</html>

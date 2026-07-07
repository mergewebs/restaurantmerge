<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KOYA | {{ __('public.reservation_title') }}</title>`r`n    <link rel="icon" type="image/png" href="https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg">
    <link rel="icon" type="image/png" href="https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg">

    <!-- Preconnect to external resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <!-- Scripts & Tailwind CSS (for layout utilities) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
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

    <!-- Fonts & Styling System -->
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

        /* Basic Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--color-light);
            color: var(--color-dark);
            overflow-x: hidden;
        }

        /* Layout Utilities */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Styling System & Custom Classes */
        .btn-koya-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--color-yellow);
            color: var(--color-dark);
            font-family: 'Biski', sans-serif;
            font-size: 1.25rem;
            padding: 1rem 2rem;
            border: var(--border-width) solid var(--color-dark);
            border-radius: 9999px;
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
            font-size: 1.25rem;
            padding: 1rem 2rem;
            border: var(--border-width) solid var(--color-dark);
            border-radius: 9999px;
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

        /* Sticker badge styles */
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

        /* Navigation Header */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background-color: rgba(8, 137, 114, 0.95);
            padding: 0.5rem 0;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .logo-img {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            border: 3px solid var(--color-dark);
            object-fit: cover;
            box-shadow: 3px 3px 0px var(--color-dark);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-links a {
            font-family: var(--font-headline);
            font-size: 1.1rem;
            color: white;
            text-decoration: none;
            transition: all 0.2s ease;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            position: relative;
            padding-bottom: 4px;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: var(--color-yellow);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .nav-links a:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        .nav-links a:hover {
            transform: scale(1.05);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Mobile Menu */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            width: 44px;
            height: 44px;
            align-items: center;
            justify-content: center;
        }

        .mobile-toggle svg {
            fill: white;
            width: 28px;
            height: 28px;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-toggle {
                display: flex;
            }

            .logo-img {
                width: 60px !important;
                height: 60px !important;
            }

            header {
                padding: 0.4rem 0 !important;
            }
        }

        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--color-green);
            z-index: 999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            border-bottom: var(--border-width) solid var(--color-dark);
            transform: translateY(-100%);
            transition: transform 0.4s cubic-bezier(0.77, 0, 0.175, 1);
        }

        .mobile-menu-overlay.open {
            transform: translateY(0);
        }

        .mobile-menu-overlay a {
            font-family: var(--font-headline);
            font-size: 2rem;
            color: white;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Custom Reservation Card Styles */
        .reservation-card {
            background-color: #ffffff;
            border: var(--border-width) solid var(--color-dark);
            border-radius: 28px;
            box-shadow: 8px 8px 0px var(--color-dark);
            padding: 2.5rem;
            width: 100%;
            max-width: 620px;
            position: relative;
            z-index: 10;
        }

        @media (max-width: 640px) {
            .reservation-card {
                padding: 1.5rem;
                border-radius: 20px;
            }
        }

        .reservation-card label {
            display: block;
            margin-bottom: 0.5rem;
            font-family: var(--font-headline);
            font-size: 0.95rem;
            text-transform: uppercase;
            font-weight: 900;
            color: var(--color-dark);
            letter-spacing: 0.05em;
        }

        .reservation-card input,
        .reservation-card select,
        .reservation-card textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 3px solid var(--color-dark);
            border-radius: 12px;
            font-size: 1rem;
            color: var(--color-dark);
            background-color: var(--color-light);
            font-family: var(--font-body);
            transition: all 0.2s ease;
            box-shadow: 2px 2px 0px var(--color-dark);
        }

        .reservation-card input:focus,
        .reservation-card select:focus,
        .reservation-card textarea:focus {
            border-color: var(--color-green);
            background-color: #ffffff;
            outline: none;
            box-shadow: 4px 4px 0px var(--color-dark);
            transform: translateY(-2px);
        }

        /* Custom Flatpickr styling matching Koya */
        .flatpickr-calendar {
            background: var(--color-light) !important;
            border: 3px solid var(--color-dark) !important;
            box-shadow: 6px 6px 0px var(--color-dark) !important;
            border-radius: 16px !important;
            font-family: var(--font-body) !important;
        }
        
        .flatpickr-day.selected, 
        .flatpickr-day.startRange, 
        .flatpickr-day.endRange, 
        .flatpickr-day.selected.inRange, 
        .flatpickr-day.startRange.inRange, 
        .flatpickr-day.endRange.inRange, 
        .flatpickr-day.selected:focus, 
        .flatpickr-day.startRange:focus, 
        .flatpickr-day.endRange:focus, 
        .flatpickr-day.selected:hover, 
        .flatpickr-day.startRange:hover, 
        .flatpickr-day.endRange:hover, 
        .flatpickr-day.selected.prevMonthDay, 
        .flatpickr-day.selected.nextMonthDay {
            background: var(--color-green) !important;
            border-color: var(--color-dark) !important;
            color: white !important;
            font-weight: bold !important;
        }
        
        .flatpickr-day:hover {
            background: var(--color-yellow) !important;
            color: var(--color-dark) !important;
            border-color: var(--color-dark) !important;
        }
        
        .flatpickr-months .flatpickr-month {
            color: var(--color-dark) !important;
            fill: var(--color-dark) !important;
        }
        
        .flatpickr-current-month .numInputWrapper span.arrowUp:after {
            border-bottom-color: var(--color-dark) !important;
        }
        
        .flatpickr-current-month .numInputWrapper span.arrowDown:after {
            border-top-color: var(--color-dark) !important;
        }

        /* Floating background ingredients */
        .floating-element {
            position: absolute;
            z-index: 1;
            pointer-events: none;
            filter: drop-shadow(3px 3px 0px var(--color-dark));
        }

        .float-1 {
            top: 15%;
            left: 5%;
            animation: floatSlow 5s ease-in-out infinite alternate;
        }
        .float-2 {
            bottom: 15%;
            left: 8%;
            animation: floatSlower 7s ease-in-out infinite alternate;
        }
        .float-3 {
            top: 20%;
            right: 5%;
            animation: floatSlow 6s ease-in-out infinite alternate;
        }
        .float-4 {
            bottom: 20%;
            right: 8%;
            animation: floatSlower 5s ease-in-out infinite alternate;
        }

        @keyframes floatSlow {
            0% { transform: translate(0px, 0px) rotate(0deg); }
            50% { transform: translate(15px, -20px) rotate(4deg); }
            100% { transform: translate(0px, 0px) rotate(0deg); }
        }

        @keyframes floatSlower {
            0% { transform: translate(0px, 0px) rotate(0deg); }
            50% { transform: translate(-20px, -30px) rotate(-6deg); }
            100% { transform: translate(0px, 0px) rotate(0deg); }
        }

        /* Footer */
        footer {
            background-color: var(--color-green);
            background-image: radial-gradient(rgba(250, 192, 33, 0.12) 15%, transparent 15%);
            background-size: 24px 24px;
            color: var(--color-light);
            padding: 5rem 0 3rem 0;
            font-size: 0.95rem;
            border-top: var(--border-width) solid var(--color-dark);
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background-color: var(--color-yellow);
            border-bottom: 2px solid var(--color-dark);
            z-index: 10;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-logo-desc {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .footer-logo-wrap {
            display: inline-block;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            width: fit-content;
        }

        .footer-logo-wrap:hover {
            transform: scale(1.05) rotate(-2deg);
        }

        .footer-logo-img {
            height: 90px;
            width: auto;
            border-radius: 12px;
            border: 3px solid var(--color-dark);
            box-shadow: 3px 3px 0px var(--color-dark);
        }

        .footer-p {
            line-height: 1.6;
            color: rgba(255, 252, 244, 0.9);
            font-weight: 500;
            max-width: 380px;
        }

        .footer-nav-title {
            font-family: var(--font-headline);
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-shadow: 2px 2px 0px var(--color-dark);
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .footer-links a {
            color: rgba(255, 252, 244, 0.85);
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .footer-links a:hover {
            color: var(--color-yellow);
            transform: translateX(6px);
        }

        .social-icons {
            display: flex;
            gap: 1.2rem;
            margin-top: 1.2rem;
        }

        .social-icon {
            background-color: var(--color-light);
            border: 3px solid var(--color-dark);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 3px 3px 0px var(--color-dark);
            transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .social-icon:hover {
            background-color: var(--color-yellow);
            transform: translate(-3px, -3px);
            box-shadow: 6px 6px 0px var(--color-dark);
        }

        .social-icon svg {
            width: 20px;
            height: 20px;
            fill: var(--color-dark);
            transition: fill 0.25s ease;
        }

        .footer-bottom {
            border-top: 3px solid var(--color-dark);
            padding-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            font-weight: 600;
            color: rgba(255, 252, 244, 0.9);
        }

        .footer-credit-link {
            color: var(--color-yellow);
            text-decoration: none;
            border-bottom: 2px solid var(--color-yellow);
            font-weight: 800;
            transition: all 0.2s ease;
        }

        .footer-credit-link:hover {
            color: white;
            border-color: white;
        }

        @media (max-width: 992px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="{ mobileMenuOpen: false }">

    <!-- Toast Notifications Container -->
    @if(session('success') || session('error') || $errors->any())
    <div 
        x-data="{ 
            visible: true,
            init() {
                setTimeout(() => {
                    this.visible = false;
                }, 5000); // Disappear after 5 seconds
            }
        }"
        x-show="visible"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-x-12 opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-12 opacity-0"
        class="fixed top-24 right-6 z-[2000] w-full max-w-[350px] sm:max-w-[400px] px-4 sm:px-0"
        x-cloak
    >
        @if(session('success'))
            <div style="background-color: #088972; border: 4px solid var(--color-dark); box-shadow: 4px 4px 0px var(--color-dark);" class="relative text-white rounded-2xl p-5 flex items-start gap-3">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1 text-sm font-bold uppercase tracking-wider leading-snug pr-4">
                    {!! session('success') !!}
                </div>
                <button @click="visible = false" class="text-white hover:scale-110 active:scale-95 transition-all font-black text-xl leading-none absolute top-3 right-4" aria-label="Close">&times;</button>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div style="background-color: #fff5f5; border: 4px solid #f24423; box-shadow: 4px 4px 0px #f24423;" class="relative text-[#f24423] rounded-2xl p-5 flex items-start gap-3">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-6 h-6 text-[#f24423]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 text-xs font-bold uppercase tracking-wider leading-relaxed pr-4">
                    @if(session('error'))
                        <p>{{ session('error') }}</p>
                    @endif
                    @if($errors->any())
                        <ul class="list-none mt-1 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <button @click="visible = false" class="text-[#f24423] hover:scale-110 active:scale-95 transition-all font-black text-xl leading-none absolute top-3 right-4" aria-label="Close">&times;</button>
            </div>
        @endif
    </div>
    @endif

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" :class="mobileMenuOpen ? 'open' : ''">
        <a href="/" @click="mobileMenuOpen = false">Home</a>
        <a href="/#menu-section" @click="mobileMenuOpen = false">Menu</a>
        <a href="/#contact" @click="mobileMenuOpen = false">Find Us</a>
        <a href="{{ route('reservations.public.create') }}" class="sticker text-center" style="display: block; width: 220px;" @click="mobileMenuOpen = false">Reservar</a>
        <a href="{{ route('order.public') }}" class="sticker sticker-white text-center" style="display: block; width: 220px;" @click="mobileMenuOpen = false">Pedir Online</a>
    </div>

    <!-- Navigation Header -->
    <header>
        <div class="container header-container">
            <a href="/" class="logo-wrap">
                <img src="https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg" alt="KOYA Logo" class="logo-img" onerror="this.src='/favicon.ico'">
            </a>

            <nav class="nav-links">
                <a href="/#hero">Home</a>
                <a href="/#menu-section">Menu</a>
                <a href="/#contact">Find Us</a>
            </nav>

            <div class="nav-actions">
                <a href="{{ route('reservations.public.create') }}" class="btn-koya-primary" style="font-size: 1rem; padding: 0.6rem 1.2rem; border-width: 3px; display: inline-flex;">Reservar</a>
                <button class="mobile-toggle" @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Toggle Menu">
                    <svg x-show="!mobileMenuOpen" viewBox="0 0 24 24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
                    <svg x-show="mobileMenuOpen" viewBox="0 0 24 24" x-cloak><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content Section -->
    <main style="background-color: var(--color-yellow); min-height: 100vh; position: relative; overflow: hidden; padding: 9rem 1.5rem 6rem 1.5rem;" class="flex items-center justify-center">
        
        <!-- Floating Graphics in the Background -->
        <svg class="floating-element float-1 opacity-20 sm:opacity-40" width="60" height="60" viewBox="0 0 100 100">
            <!-- Tomato Slice -->
            <circle cx="50" cy="50" r="45" fill="#f24423" stroke="#121212" stroke-width="6"/>
            <circle cx="50" cy="50" r="35" fill="#d12e0f" />
            <circle cx="35" cy="40" r="6" fill="#f24423" />
            <circle cx="65" cy="40" r="6" fill="#f24423" />
            <circle cx="50" cy="65" r="6" fill="#f24423" />
        </svg>

        <svg class="floating-element float-2 opacity-20 sm:opacity-40" width="70" height="70" viewBox="0 0 100 100">
            <!-- Cheese Slice -->
            <polygon points="10,15 90,25 65,85 10,15" fill="#fac021" stroke="#121212" stroke-width="6"/>
            <circle cx="35" cy="40" r="8" fill="#e0ab16" />
            <circle cx="60" cy="50" r="5" fill="#e0ab16" />
        </svg>

        <svg class="floating-element float-3 opacity-20 sm:opacity-40" width="85" height="85" viewBox="0 0 100 100">
            <!-- Lettuce leaf -->
            <path d="M 20 60 C 10 40, 20 15, 50 20 C 80 15, 90 40, 80 60 C 70 80, 30 80, 20 60 Z" fill="#4fa838" stroke="#121212" stroke-width="6"/>
            <path d="M 50 20 Q 50 50 50 75" fill="none" stroke="#121212" stroke-width="4"/>
            <path d="M 50 40 Q 30 35 25 40" fill="none" stroke="#121212" stroke-width="4"/>
            <path d="M 50 55 Q 70 50 75 55" fill="none" stroke="#121212" stroke-width="4"/>
        </svg>

        <svg class="floating-element float-4 opacity-20 sm:opacity-40" width="50" height="50" viewBox="0 0 100 100">
            <!-- Sesame seeds -->
            <path d="M 30 30 Q 35 15 40 30 Q 35 45 30 30 Z" fill="#ebd6b0" stroke="#121212" stroke-width="3" transform="rotate(15 30 30)"/>
            <path d="M 70 40 Q 75 25 80 40 Q 75 55 70 40 Z" fill="#ebd6b0" stroke="#121212" stroke-width="3" transform="rotate(-20 70 40)"/>
            <path d="M 45 70 Q 50 55 55 70 Q 50 85 45 70 Z" fill="#ebd6b0" stroke="#121212" stroke-width="3" transform="rotate(45 45 70)"/>
        </svg>

        <!-- Form Card -->
        <div class="reservation-card">
            
            <!-- Card Header -->
            <div class="mb-8 pb-4 border-b-4 border-dashed border-[#121212]">
                <h2 style="font-family: var(--font-headline); font-size: 2.2rem; line-height: 1.1; color: var(--color-dark); text-transform: uppercase;">
                    {{ __('public.reservation_title') }}
                </h2>
            </div>



            <!-- Reservation Form -->
            <form action="{{ route('reservations.public.store') }}" method="POST" x-data="{ 
                date: '{{ old('reservation_time') ? substr(old('reservation_time'), 0, 10) : date('Y-m-d') }}',
                selectedTime: '{{ old('reservation_time') ? substr(old('reservation_time'), 11, 5) : '' }}',
                partySize: {{ old('party_size', 1) }},
                slots: [],
                taken_per_time: {},
                closed: false,
                has_session_3: false,
                max_capacity: 25,
                dayOfWeek: null,
                loading: false,
                isSubmitting: false,
                submissionToken: '',
                showEmail: false,
                showNotes: false,
                createSubmissionToken() {
                    if (window.crypto && typeof window.crypto.randomUUID === 'function') {
                        return window.crypto.randomUUID();
                    }

                    return 'token-' + Date.now() + '-' + Math.floor(Math.random() * 1000000);
                },
                initSubmissionToken() {
                    this.submissionToken = this.createSubmissionToken();
                },
                handleSubmit(event) {
                    if (this.isSubmitting || this.loading || this.closed || !this.selectedTime) {
                        event.preventDefault();
                        return;
                    }

                    this.isSubmitting = true;
                    event.target.submit();
                },
                async fetchSlots() {
                    if (!this.date) return;
                    this.loading = true;
                    try {
                        console.log('Fetching slots for:', this.date);
                        const response = await fetch('{{ route('restaurant.schedule.data') }}?date=' + this.date);
                        const data = await response.json();
                        console.log('Response data:', data);
                        this.closed = data.closed;
                        this.slots = data.slots || [];
                        this.taken_per_time = data.taken_per_time || {};
                        this.max_capacity = data.max_party_size || 25;
                        this.dayOfWeek = data.day_of_week;
                        this.has_session_3 = data.has_session_3;

                        if (this.partySize > this.max_capacity) {
                            this.partySize = this.max_capacity;
                        }

                        if (this.selectedTime) {
                            if (this.getAvailableSpots(this.selectedTime) < this.partySize) {
                                this.selectedTime = '';
                            }
                        }
                    } catch (e) {
                        console.error('Error fetching slots:', e);
                    }
                    this.loading = false;
                },
                getAvailableSpots(timeStr) {
                    if (!timeStr) return this.max_capacity;
                    let taken = this.taken_per_time[timeStr] || 0;
                    return Math.max(0, this.max_capacity - taken);
                },
                get availableTimes() {
                    let times = [];
                    this.slots.forEach(slot => {
                        const startHour = parseInt(slot.start.split(':')[0]);
                        if (this.has_session_3 && startHour >= 17) {
                            let timeStr = slot.start.substring(0, 5);
                            let available = this.getAvailableSpots(timeStr);
                            if (available >= this.partySize) {
                                const isPartiallyBooked = available < this.max_capacity;
                                const availabilityText = isPartiallyBooked ? ` (${available} spots left)` : '';
                                times.push({ 
                                    value: timeStr, 
                                    label: `${slot.start.substring(0, 5)} - ${slot.end.substring(0, 5)}${availabilityText}` 
                                });
                            }
                        } else {
                            let current = new Date('2000-01-01T' + (slot.start.length === 5 ? slot.start + ':00' : slot.start));
                            let stop = new Date('2000-01-01T' + (slot.end.length === 5 ? slot.end + ':00' : slot.end));
                            
                            while (current < stop) {
                                let h = current.getHours().toString().padStart(2, '0');
                                let m = current.getMinutes().toString().padStart(2, '0');
                                let timeStr = `${h}:${m}`;
                                
                                let available = this.getAvailableSpots(timeStr);
                                if (available >= this.partySize) {
                                    const isPartiallyBooked = available < this.max_capacity;
                                    const availabilityText = isPartiallyBooked ? ` (${available} spots left)` : '';
                                    times.push({ 
                                        value: timeStr, 
                                        label: `${timeStr}${availabilityText}` 
                                    });
                                }
                                current.setMinutes(current.getMinutes() + 15);
                            }
                        }
                    });
                    return times;
                },
                async initFlatpickr() {
                    let closedData = { weekly_closed: [], exceptions: [] };
                    try {
                        const resp = await fetch('{{ route('restaurant.closed-dates') }}');
                        if (resp.ok) {
                            closedData = await resp.json();
                        }
                    } catch (e) {
                        console.error('Error fetching closed dates:', e);
                    }
                    
                    flatpickr(this.$refs.dateInput, {
                        minDate: 'today',
                        defaultDate: this.date,
                        dateFormat: 'Y-m-d',
                        disable: [
                            (date) => {
                                const day = date.getDay();
                                if (closedData.weekly_closed.includes(day)) return true;
                                
                                const dateStr = date.getFullYear() + '-' + 
                                              String(date.getMonth() + 1).padStart(2, '0') + '-' + 
                                              String(date.getDate()).padStart(2, '0');
                                if (closedData.exceptions.includes(dateStr)) return true;
                                
                                return false;
                            }
                        ],
                        onChange: (selectedDates, dateStr) => {
                            this.date = dateStr;
                            this.fetchSlots();
                        },
                        locale: '{{ app()->getLocale() }}' === 'es' ? 'es' : 'en'
                    });
                }
            }" @submit.prevent="handleSubmit" x-init="initSubmissionToken(); fetchSlots(); initFlatpickr();" class="space-y-6">
                
                @csrf
                <input type="hidden" name="reservation_time" x-bind:value="date + ' ' + selectedTime">
                <input type="hidden" name="submission_token" x-bind:value="submissionToken">

                <!-- Name & Phone -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label>{{ __('public.full_name') }}</label>
                        <input type="text" name="customer_name" required value="{{ old('customer_name') }}" placeholder="{{ __('public.order_customer_name_placeholder') }}">
                    </div>
                    <div>
                        <label>{{ __('public.phone') }}</label>
                        <input type="tel" name="customer_phone" required value="{{ old('customer_phone') }}" placeholder="{{ __('public.order_customer_phone_placeholder') }}">
                    </div>
                </div>

                <!-- Date & Arrival Time -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label>{{ __('public.date') }}</label>
                        <div class="relative">
                            <input type="text" x-ref="dateInput" x-model="date" readonly required class="cursor-pointer !pr-12">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label>{{ __('public.arrival_time') }}</label>
                        <select x-model="selectedTime" required :disabled="loading || closed">
                            <option value="" disabled selected x-text="loading ? '{{ __('public.loading') }}' : (closed ? '{{ __('public.closed') }}' : '{{ __('public.select_time') }}')"></option>
                            <template x-for="timeOption in availableTimes">
                                <option :value="timeOption.value" x-text="timeOption.label"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <!-- Party Size & Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label>{{ __('public.party_size') }}</label>
                        <select name="party_size" x-model.number="partySize" required :disabled="loading">
                            <template x-for="i in getAvailableSpots(selectedTime)">
                                <option :value="i" x-text="i + (i === 1 ? ' {{ __('public.person') }}' : ' {{ __('public.people') }}')" :selected="i == partySize"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label>{{ __('public.email') }}</label>
                        <input type="email" name="customer_email" required value="{{ old('customer_email') }}" placeholder="koya@example.com">
                    </div>
                </div>

                <!-- Availability alerts -->
                <template x-if="slots.length > 0 && availableTimes.length === 0 && !loading && !closed">
                    <div class="w-full py-6 text-center bg-gray-50 rounded-2xl border-4 border-dashed border-[#121212] px-4">
                        <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">
                            {{ __('public.no_availability', ['size' => '']) }} <span x-text="partySize"></span> {{ __('public.people') }}
                        </p>
                    </div>
                </template>

                <div x-show="closed" x-cloak class="p-4 bg-red-50 border-4 border-[#f24423] text-[#f24423] rounded-2xl text-sm text-center font-bold">
                    {{ __('public.closed') }}
                </div>

                <!-- Notes toggle -->
                <div class="pt-4 border-t-2 border-dashed border-[#121212] space-y-4">
                    <div x-show="!showNotes" class="text-center">
                        <button type="button" @click="showNotes = true" class="text-[10px] font-bold text-[#088972] hover:underline transition-all uppercase tracking-[0.2em]">
                            + {{ __('public.allergy_prompt') }}
                        </button>
                    </div>

                    <div x-show="showNotes" x-cloak x-transition>
                        <label>{{ __('public.notes') }}</label>
                        <textarea name="notes" rows="2" placeholder="{{ __('public.notes_placeholder') }}">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button x-ref="submitButton" type="submit" class="btn-koya-primary" :disabled="closed || !selectedTime || isSubmitting" :class="closed || !selectedTime || isSubmitting ? 'opacity-50 cursor-not-allowed' : ''" style="width: 100%; justify-content: center; display: inline-flex;">
                        <span x-show="!isSubmitting">{{ __('public.submit') }}</span>
                        <span x-show="isSubmitting" x-cloak>{{ __('public.submitting') }}</span>
                    </button>
                </div>
            </form>

        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-logo-desc">
                    <a href="/" class="footer-logo-wrap">
                        <img src="https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg" alt="KOYA Logo" class="footer-logo-img" onerror="this.src='/favicon.ico'">
                    </a>
                    <p class="footer-p">
                        {{ __('public.homepage_meta_description') }}
                    </p>
                    <div class="social-icons">
                        <a href="#" class="social-icon" aria-label="Instagram">
                            <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="#" class="social-icon" aria-label="Facebook">
                            <svg viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="footer-nav-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="/#hero">Home</a></li>
                        <li><a href="/#menu-section">Menu</a></li>
                        <li><a href="/#contact">Location & Hours</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2026 KOYA BCN. All rights reserved.</p>
                <p>Create by <a href="http://mergewebs.com/" target="_blank" class="footer-credit-link">Merge</a></p>
            </div>
        </div>
    </footer>
</body>
</html>

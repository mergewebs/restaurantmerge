<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ __('public.homepage_meta_description') }}">
    <title>KOYA | Smash Burger & BOL</title><link rel="icon" type="image/png" href="https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg">
    <link rel="icon" type="image/png" href="https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg">

    <!-- Preconnect to external resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&display=swap" rel="stylesheet">

    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Fonts -->
    <style>
        @font-face {
            font-family: 'Biski';
            src: url('/fonts/Biski.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Biski_2';
            src: url('/fonts/Biski_2.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Biski_4';
            src: url('/fonts/Biski_4.ttf') format('truetype');
            font-weight: 800;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Bubbleboddy';
            src: url('/fonts/Bubbleboddy_Neue1.ttf') format('truetype');
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

        .btn-koya-green {
            background-color: var(--color-green);
            color: white;
        }
        .btn-koya-green:hover {
            background-color: var(--color-green-light);
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

        .sticker.sticker-large {
            font-size: 1.4rem;
            padding: 0.6rem 1.2rem;
            border: var(--border-width) solid var(--color-dark);
            box-shadow: 4px 4px 0px var(--color-dark);
            border-radius: 12px;
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

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            background-color: var(--color-yellow);
            border: 3.5px solid var(--color-dark);
            box-shadow: 3.5px 3.5px 0px var(--color-dark);
            cursor: pointer;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 1010;
        }

        .mobile-toggle:hover {
            transform: translate(-1.5px, -1.5px);
            box-shadow: 5px 5px 0px var(--color-dark);
        }

        .mobile-toggle:active {
            transform: translate(1.5px, 1.5px);
            box-shadow: 1.5px 1.5px 0px var(--color-dark);
        }

        .hamburger-inner {
            position: relative;
            width: 20px;
            height: 14px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .hamburger-inner span {
            display: block;
            width: 100%;
            height: 3px;
            background-color: var(--color-dark);
            border-radius: 9999px;
            transition: transform 0.3s cubic-bezier(0.77, 0, 0.175, 1), opacity 0.3s ease, background-color 0.3s ease;
        }

        /* Animation from Hamburger to Close 'X' */
        .mobile-toggle.is-open {
            background-color: white;
        }

        .mobile-toggle.is-open .hamburger-inner span {
            background-color: var(--color-dark);
        }

        .mobile-toggle.is-open .hamburger-inner span:nth-child(1) {
            transform: translateY(5.5px) rotate(45deg);
        }

        .mobile-toggle.is-open .hamburger-inner span:nth-child(2) {
            opacity: 0;
            transform: scale(0);
        }

        .mobile-toggle.is-open .hamburger-inner span:nth-child(3) {
            transform: translateY(-5.5px) rotate(-45deg);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--color-green), #0ba389, #055f4f, var(--color-green));
            background-size: 400% 400%;
            padding: 8rem 0 5rem 0;
            border-bottom: var(--border-width) solid var(--color-dark);
            position: relative;
            overflow: hidden;
            animation: gradientShift 15s ease infinite;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(rgba(250, 192, 33, 0.18) 20%, transparent 20%);
            background-size: 40px 40px;
            opacity: 0.45;
            pointer-events: none;
            animation: backgroundDrift 12s linear infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes backgroundDrift {
            0% { background-position: 0 0; }
            100% { background-position: 40px 40px; }
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 3rem;
            align-items: center;
            position: relative;
            z-index: 5;
        }

        .hero-content {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .hero-badge-wrap {
            margin-bottom: 1.5rem;
        }

        .hero-title {
            font-family: var(--font-headline);
            font-size: 4.5rem;
            line-height: 0.95;
            color: white;
            text-shadow: 4px 4px 0px var(--color-dark);
            margin-bottom: 1.5rem;
            text-transform: uppercase;
        }

        .hero-title span {
            color: var(--color-yellow);
        }

        .hero-subtitle {
            font-family: var(--font-accent);
            font-size: 1.75rem;
            color: var(--color-light);
            margin-bottom: 2.5rem;
            text-shadow: 2px 2px 0px rgba(0,0,0,0.3);
            max-width: 550px;
        }

        .hero-btns {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        /* Visual Area */
        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 560px;
        }

        .hero-visual::before {
            content: '';
            position: absolute;
            width: 380px;
            height: 380px;
            background-color: var(--color-yellow);
            border-radius: 50%;
            opacity: 0.18;
            filter: blur(60px);
            z-index: 1;
            animation: pulseGlow 5s ease-in-out infinite alternate;
            pointer-events: none;
        }

        @keyframes pulseGlow {
            0% { transform: scale(0.85); opacity: 0.15; }
            100% { transform: scale(1.15); opacity: 0.25; }
        }

        .hero-main-img-wrap {
            position: relative;
            width: 420px;
            height: 420px;
            z-index: 10;
            animation: tiltShake 6s ease-in-out infinite alternate;
        }

        .hero-main-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(10px 10px 0px rgba(0,0,0,0.15));
            transition: opacity 0.6s ease-in-out, transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .rotating-badge-wrap {
            position: absolute;
            width: 550px;
            height: 550px;
            z-index: 2;
            pointer-events: none;
            animation: spinSlow 20s linear infinite;
        }

        .rotating-badge-svg {
            width: 100%;
            height: 100%;
        }

        /* Floating Ingredients */
        .floating-element {
            position: absolute;
            z-index: 12;
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
            left: 10%;
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
            animation: floatSlower 8s ease-in-out infinite alternate;
        }

        /* Section Headings */
        .section-header {
            text-align: center;
            margin-bottom: 4rem;
            position: relative;
        }

        .section-title {
            font-family: var(--font-headline);
            font-size: 3.5rem;
            color: var(--color-dark);
            text-transform: uppercase;
            line-height: 1;
            display: inline-block;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 8px;
            background-color: var(--color-yellow);
            border-radius: 999px;
            border: 2px solid var(--color-dark);
        }

        /* Featured Products Section */
        .featured {
            padding: 6rem 0;
            background-color: var(--color-light);
            position: relative;
        }

        .featured-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
        }

        .koya-card {
            background-color: white;
            border: var(--border-width) solid var(--color-dark);
            border-radius: 24px;
            padding: 2rem;
            position: relative;
            box-shadow: 8px 8px 0px var(--color-yellow);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 420px;
        }

        .koya-card:hover {
            transform: translate(-6px, -6px);
            box-shadow: 14px 14px 0px var(--color-dark);
        }

        .koya-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .koya-card-img-wrap {
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            background-color: #fffcf4;
            border: 3px dashed #ebd6b0;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
        }

        .koya-card-img {
            max-height: 140px;
            max-width: 90%;
            object-fit: contain;
            transition: all 0.3s ease;
        }

        .koya-card:hover .koya-card-img {
            transform: scale(1.1) rotate(5deg);
        }

        .koya-card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .koya-card-title {
            font-family: var(--font-headline);
            font-size: 1.8rem;
            color: var(--color-dark);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .koya-card-desc {
            font-size: 0.95rem;
            color: #555;
            line-height: 1.4;
            margin-bottom: 1.5rem;
        }

        .koya-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
        }

        .koya-card-price {
            font-family: 'Biski_2', sans-serif;
            font-size: 1.8rem;
            color: var(--color-green);
        }

        /* Menu Section with Tabs */
        .menu-section {
            padding: 6rem 0;
            background-color: var(--color-green);
            color: white;
            border-top: var(--border-width) solid var(--color-dark);
            border-bottom: var(--border-width) solid var(--color-dark);
            position: relative;
        }

        .menu-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(rgba(255, 255, 255, 0.05) 20%, transparent 20%);
            background-size: 30px 30px;
            pointer-events: none;
        }

        .menu-section .section-title {
            color: white;
            text-shadow: 3px 3px 0px var(--color-dark);
        }

        .menu-tabs-container {
            display: flex;
            justify-content: center;
            margin-bottom: 4rem;
            position: relative;
            z-index: 10;
        }

        .menu-tabs {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            background-color: var(--color-green-dark);
            padding: 0.6rem;
            border-radius: 9999px;
            border: 3px solid var(--color-dark);
        }

        .menu-tab {
            font-family: 'Biski', sans-serif;
            font-size: 1.1rem;
            padding: 0.8rem 1.8rem;
            border: 3px solid transparent;
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

        @media (max-width: 768px) {
            .menu-tabs {
                border-radius: 2rem;
                padding: 0.8rem;
                gap: 0.6rem;
            }
            .menu-tab {
                padding: 0.6rem 1.2rem;
                font-size: 0.95rem;
            }
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 2rem;
            position: relative;
            z-index: 5;
        }

        .menu-card-simple {
            background-color: white;
            border: 3px solid var(--color-dark);
            border-radius: 24px;
            padding: 1.25rem 1rem;
            color: var(--color-dark);
            box-shadow: 5px 5px 0px var(--color-dark);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-decoration: none;
            isolation: isolate;
        }

        .menu-card-simple:hover {
            transform: translate(-5px, -5px);
            box-shadow: 10px 10px 0px var(--color-dark);
            background-color: #fffef9;
            border-color: var(--color-green);
        }

        .menu-item-inner {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .menu-item-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .menu-item-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.5rem;
            margin-bottom: 0.15rem;
            width: 100%;
        }

        .menu-item-name {
            font-family: 'Biski', sans-serif;
            font-size: 1.25rem;
            color: var(--color-dark);
            text-transform: uppercase;
            margin: 0;
            line-height: 1.2;
            transition: color 0.2s ease, transform 0.2s ease;
            flex: 1;
            min-width: 0;
        }

        .menu-card-simple:hover .menu-item-name {
            color: var(--color-green);
            transform: translateX(2px);
        }

        .menu-item-description {
            font-size: 0.85rem;
            color: #555;
            line-height: 1.5;
            margin: 0;
        }

        .menu-item-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.25rem;
        }

        .menu-item-price {
            font-family: 'Biski_2', sans-serif;
            font-size: 1.05rem;
            color: var(--color-dark);
            background-color: var(--color-yellow);
            border: 1.5px solid var(--color-dark);
            padding: 0.1rem 0.5rem;
            border-radius: 9999px;
            box-shadow: 1.5px 1.5px 0px var(--color-dark);
            font-weight: 800;
            display: inline-block;
            white-space: nowrap;
            flex-shrink: 0;
            margin-top: 0.15rem;
        }

        .menu-item-order-link {
            font-family: 'Bubbleboddy', 'Outfit', sans-serif;
            font-size: 0.8rem;
            color: var(--color-green);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: color 0.2s ease;
        }

        .menu-card-simple:hover .menu-item-order-link {
            color: var(--color-green-light);
        }

        .menu-item-order-link .arrow-icon {
            width: 14px;
            height: 14px;
            transition: transform 0.2s ease;
        }

        .menu-card-simple:hover .arrow-icon {
            transform: translateX(4px);
        }

        .menu-item-img-container {
            width: 100px;
            height: 100px;
            overflow: visible;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .menu-card-simple:hover .menu-item-img-container {
            transform: scale(1.1) rotate(5deg);
        }

        .menu-item-img-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .drinks-highlight-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
            position: relative;
            z-index: 5;
            align-items: start;
        }

        .drinks-showcase,
        .sides-showcase {
            background-color: var(--color-yellow);
            border: 3px solid var(--color-dark);
            border-radius: 28px;
            box-shadow: 7px 7px 0px var(--color-dark);
            padding: 1.6rem 1.4rem;
        }

        .drinks-showcase-title,
        .sides-showcase-title {
            font-family: 'Biski', sans-serif;
            font-size: 1.55rem;
            color: var(--color-green);
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .drinks-showcase-title-image,
        .sides-showcase-title-image {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .drinks-showcase-title-image img,
        .sides-showcase-title-image img {
            width: 72px;
            height: auto;
            display: block;
        }

        .drink-cocktail-marker {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 120px;
        }

        .drink-cocktail-marker img {
            width: 88px;
            height: auto;
            display: block;
        }

        .drinks-showcase-grid,
        .sides-showcase-grid {
            display: grid;
            gap: 0.9rem;
        }

        .drinks-showcase-item,
        .sides-showcase-item {
            padding-bottom: 0.8rem;
            border-bottom: 1px dashed rgba(18, 18, 18, 0.22);
        }

        .drinks-showcase-item:last-child,
        .sides-showcase-item:last-child {
            padding-bottom: 0;
            border-bottom: none;
        }

        .drinks-showcase-top,
        .sides-showcase-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .drinks-showcase-name,
        .sides-showcase-name {
            font-family: 'Biski', sans-serif;
            font-size: 1.15rem;
            color: var(--color-green);
            margin: 0;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .drinks-showcase-price,
        .sides-showcase-price {
            font-family: 'Biski_2', sans-serif;
            font-size: 1rem;
            color: #d92c2c;
            flex-shrink: 0;
            line-height: 1;
            margin-top: 0.1rem;
        }

        .drinks-showcase-description,
        .sides-showcase-description {
            font-size: 0.85rem;
            color: var(--color-dark);
            line-height: 1.45;
            margin: 0.35rem 0 0;
        }

        .sides-showcase {
            margin-top: 3rem;
            max-width: 520px;
            margin-left: auto;
            margin-right: auto;
        }

        @media (max-width: 992px) {
            .drinks-highlight-grid {
                grid-template-columns: 1fr;
            }

            .sides-showcase {
                max-width: none;
                margin-left: 0;
                margin-right: 0;
            }
        }

        .sticker-badge {
            font-family: var(--font-headline);
            font-size: 0.65rem;
            background-color: var(--color-yellow);
            color: var(--color-dark);
            padding: 0.15rem 0.45rem;
            border: 1.5px solid var(--color-dark);
            border-radius: 6px;
            box-shadow: 1.5px 1.5px 0px var(--color-dark);
            transform: rotate(-2deg);
            text-transform: uppercase;
            font-weight: 800;
            display: inline-block;
            white-space: nowrap;
        }

        .sticker-badge.on-image {
            position: absolute;
            top: -5px;
            left: -5px;
            z-index: 2;
            transform: rotate(-5deg);
        }

        /* About / Story Section */
        .about {
            padding: 7rem 0;
            background-color: #fffcf4;
            border-bottom: var(--border-width) solid var(--color-dark);
            position: relative;
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .about-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .about-title {
            font-family: var(--font-headline);
            font-size: 3.5rem;
            color: var(--color-dark);
            line-height: 0.95;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
        }

        .about-title span {
            color: var(--color-green);
        }

        .about-p {
            font-size: 1.15rem;
            line-height: 1.6;
            color: #333;
            margin-bottom: 2rem;
        }

        .about-badges {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2.5rem;
        }

        .about-visual {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .about-img-wrap {
            position: relative;
            width: 380px;
            height: 380px;
            background-color: var(--color-yellow);
            border: var(--border-width) solid var(--color-dark);
            border-radius: 40px;
            box-shadow: 12px 12px 0px var(--color-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .about-img {
            width: 85%;
            height: 85%;
            object-fit: contain;
            animation: rotateWobble 8s ease-in-out infinite alternate;
        }

        .about-deco-sticker {
            position: absolute;
            bottom: -20px;
            right: -10px;
            z-index: 10;
        }

        /* Call To Action (CTA) */
        .cta {
            background-color: var(--color-yellow);
            border-bottom: var(--border-width) solid var(--color-dark);
            padding: 5rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-container {
            position: relative;
            z-index: 5;
            max-width: 800px;
            margin: 0 auto;
        }

        .cta-title {
            font-family: var(--font-headline);
            font-size: 3.8rem;
            color: var(--color-dark);
            line-height: 1;
            margin-bottom: 2rem;
            text-transform: uppercase;
        }

        .cta-btn-wrap {
            position: relative;
            display: inline-block;
        }

        .cta-arrow {
            position: absolute;
            left: -80px;
            top: 50%;
            transform: translateY(-50%);
            animation: bounceLeftRight 1.5s infinite alternate;
        }

        .cta-arrow-right {
            position: absolute;
            right: -80px;
            top: 50%;
            transform: translateY(-50%) rotate(180deg);
            animation: bounceRightLeft 1.5s infinite alternate;
        }

        /* Contact & Location Section */
        .contact {
            padding: 6rem 0;
            background-color: var(--color-yellow);
            border-bottom: var(--border-width) solid var(--color-dark);
            position: relative;
            overflow: hidden;
        }

        .contact::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(rgba(18, 18, 18, 0.08) 15%, transparent 15%);
            background-size: 24px 24px;
            pointer-events: none;
            z-index: 1;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3.5rem;
            position: relative;
            z-index: 2;
        }

        .contact-card {
            background-color: white;
            border: var(--border-width) solid var(--color-dark);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 8px 8px 0px var(--color-dark);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease;
        }

        .contact-card:hover {
            transform: translate(-4px, -4px);
            box-shadow: 12px 12px 0px var(--color-dark);
        }

        .contact-card-title {
            font-family: var(--font-headline);
            font-size: 2rem;
            color: var(--color-dark);
            margin-bottom: 2rem;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .contact-card-title svg {
            fill: var(--color-green);
            width: 32px;
            height: 32px;
        }

        .contact-info-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .contact-info-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .contact-info-icon {
            background-color: var(--color-yellow);
            border: 2px solid var(--color-dark);
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 2px 2px 0px var(--color-dark);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), background-color 0.2s ease;
        }

        .contact-info-item:hover .contact-info-icon {
            transform: scale(1.15) rotate(-8deg);
            background-color: #ffd147;
        }

        .contact-info-icon svg {
            width: 20px;
            height: 20px;
            fill: var(--color-dark);
        }

        .contact-info-text h4 {
            font-family: var(--font-headline);
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
        }

        .contact-info-text p {
            font-size: 0.95rem;
            color: #555;
            line-height: 1.4;
        }

        .contact-info-text p a {
            color: var(--color-dark);
            text-decoration: none;
            font-weight: 600;
            border-bottom: 2px solid var(--color-green);
            transition: all 0.2s ease;
            padding-bottom: 1px;
        }

        .contact-info-text p a:hover {
            color: var(--color-green);
            border-bottom-color: var(--color-dark);
        }

        /* Schedule list styling */
        .schedule-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .schedule-row {
            display: flex;
            justify-content: space-between;
            padding-bottom: 0.6rem;
            border-bottom: 2px dashed rgba(18, 18, 18, 0.12);
            font-size: 1rem;
        }

        .schedule-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .schedule-day {
            font-weight: 800;
            font-family: var(--font-headline);
            text-transform: uppercase;
        }

        .schedule-hours {
            color: var(--color-green);
            font-weight: 600;
        }

        .schedule-hours.closed {
            color: #e53e3e;
            font-weight: 800;
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

        /* Animations Keyframes */
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

        @keyframes spinSlow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes tiltShake {
            0% { transform: rotate(-2deg) scale(1); }
            50% { transform: rotate(2deg) scale(1.02); }
            100% { transform: rotate(-2deg) scale(1); }
        }

        @keyframes rotateWobble {
            0% { transform: rotate(-5deg) scale(1); }
            50% { transform: rotate(5deg) scale(0.98); }
            100% { transform: rotate(-5deg) scale(1); }
        }

        @keyframes bounceLeftRight {
            0% { transform: translate(0, -50%); }
            100% { transform: translate(-15px, -50%); }
        }

        @keyframes bounceRightLeft {
            0% { transform: translate(0, -50%) rotate(180deg); }
            100% { transform: translate(-15px, -50%) rotate(180deg); }
        }

        /* Scroll Reveal base style */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive Breakpoints */
        @media (max-width: 992px) {
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
                text-align: center;
            }

            .hero-content {
                align-items: center;
                order: 2;
            }

            .hero-visual {
                order: 1;
            }

            .hero-title {
                display: none;
            }

            .hero-btns {
                justify-content: center;
            }

            .about-grid {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .about-text {
                align-items: center;
                text-align: center;
            }

            .about-badges {
                justify-content: center;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-toggle {
                display: flex;
            }

            .header-reserve-btn {
                display: none !important;
            }

            .hero-title {
                display: none;
            }

            .hero-btns {
                gap: 0.8rem;
                flex-wrap: nowrap;
                width: 100%;
                justify-content: center;
            }

            .hero-btns .btn-koya-primary, 
            .hero-btns .btn-koya-secondary {
                font-size: 1rem;
                padding: 0.75rem 1.25rem;
                border-width: 3px;
                box-shadow: 3px 3px 0px var(--color-dark);
                flex: 1;
                max-width: 160px;
                white-space: nowrap;
            }

            .hero-btns .btn-koya-primary:hover,
            .hero-btns .btn-koya-secondary:hover {
                transform: translate(-1px, -1px);
                box-shadow: 4px 4px 0px var(--color-dark);
            }

            .hero-btns .btn-koya-primary:active,
            .hero-btns .btn-koya-secondary:active {
                transform: translate(1px, 1px);
                box-shadow: 2px 2px 0px var(--color-dark);
            }

            .section-title {
                font-size: 2.8rem;
            }

            .cta-arrow, .cta-arrow-right {
                display: none;
            }

            .rotating-badge-wrap {
                width: 380px;
                height: 380px;
            }

            .hero-main-img-wrap {
                width: 290px;
                height: 290px;
            }
            
            .hero-visual {
                height: 400px;
            }

            .about-img-wrap {
                width: 290px;
                height: 290px;
            }

            .logo-img {
                width: 60px !important;
                height: 60px !important;
            }

            header {
                padding: 0.4rem 0 !important;
            }
        }

        @media (max-width: 480px) {
            .rotating-badge-wrap {
                width: 290px;
                height: 290px;
            }

            .hero-main-img-wrap {
                width: 220px;
                height: 220px;
            }
            
            .hero-visual {
                height: 300px;
            }

            .hero-btns {
                gap: 0.5rem;
            }

            .hero-btns .btn-koya-primary, 
            .hero-btns .btn-koya-secondary {
                font-size: 0.9rem;
                padding: 0.6rem 1rem;
            }
        }

        /* Mobile overlay menu standard styles */
        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            height: 100dvh;
            background-color: var(--color-green);
            background-image: radial-gradient(rgba(250, 192, 33, 0.08) 15%, transparent 15%);
            background-size: 20px 20px;
            z-index: 999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 90px 20px 30px 20px;
            overflow-y: auto;
            transform: translateY(-100%);
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .mobile-menu-overlay.open {
            transform: translateY(0);
        }

        /* Header styling when menu overlay is open */
        header.header-overlay-open {
            background-color: transparent !important;
            backdrop-filter: none !important;
            box-shadow: none !important;
        }

        /* Group container for vertical links */
        .mobile-links-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.8rem;
            width: 100%;
            margin-bottom: 1.25rem;
        }

        /* Individual link styles */
        .mobile-menu-overlay .menu-link {
            font-family: var(--font-headline);
            font-size: 2rem;
            color: white;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-shadow: 3px 3px 0px var(--color-dark);
            -webkit-text-stroke: 1px var(--color-dark);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            opacity: 0;
            transform: translateY(25px);
            display: inline-block;
        }

        .mobile-menu-overlay.open .menu-link {
            opacity: 1;
            transform: translateY(0);
        }

        /* Staggered transition delay for links */
        .mobile-menu-overlay .menu-link:nth-child(1) { transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1) 0.08s, transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.08s; }
        .mobile-menu-overlay .menu-link:nth-child(2) { transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1) 0.15s, transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.15s; }
        .mobile-menu-overlay .menu-link:nth-child(3) { transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1) 0.22s, transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.22s; }

        .mobile-menu-overlay .menu-link:hover {
            color: var(--color-yellow);
            transform: scale(1.08) rotate(-1.5deg);
        }

        /* Action buttons container */
        .mobile-actions-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.8rem;
            width: 100%;
            margin-bottom: auto; /* Pushes the social media section to the bottom */
        }

        /* Sticker buttons inside overlay */
        .mobile-menu-overlay .sticker-btn {
            font-size: 1.25rem;
            width: 240px;
            padding: 0.7rem 1.2rem;
            border-width: 3.5px;
            box-shadow: 3.5px 3.5px 0px var(--color-dark);
            border-radius: 12px;
            opacity: 0;
            transform: translateY(25px) rotate(-3deg);
            display: block;
            text-decoration: none;
            text-align: center;
        }
        
        .mobile-menu-overlay .sticker-btn.sticker-white {
            transform: translateY(25px) rotate(2deg);
        }

        .mobile-menu-overlay.open .sticker-btn {
            opacity: 1;
            transform: translateY(0) rotate(-3deg);
        }

        .mobile-menu-overlay.open .sticker-btn.sticker-white {
            transform: translateY(0) rotate(2deg);
        }

        /* Staggered transition delay for buttons */
        .mobile-menu-overlay .sticker-btn:nth-child(1) { transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1) 0.29s, transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.29s, box-shadow 0.2s ease, background-color 0.2s ease; }
        .mobile-menu-overlay .sticker-btn:nth-child(2) { transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1) 0.36s, transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.36s, box-shadow 0.2s ease, background-color 0.2s ease; }

        .mobile-menu-overlay .sticker-btn:hover {
            transform: scale(1.04) rotate(-1deg) !important;
            box-shadow: 5px 5px 0px var(--color-dark);
        }
        .mobile-menu-overlay .sticker-btn.sticker-white:hover {
            transform: scale(1.04) rotate(1deg) !important;
            box-shadow: 5px 5px 0px var(--color-dark);
        }

        /* Social media section inside overlay */
        .mobile-socials-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.6rem;
            width: 100%;
            margin-top: 1.5rem;
            opacity: 0;
            transform: translateY(15px);
            transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1) 0.43s, transform 0.4s cubic-bezier(0.16, 1, 0.3, 1) 0.43s;
        }

        .mobile-menu-overlay.open .mobile-socials-section {
            opacity: 1;
            transform: translateY(0);
        }

        .mobile-socials-title {
            font-family: var(--font-headline);
            font-size: 0.9rem;
            color: rgba(255, 252, 244, 0.85);
            text-transform: uppercase;
            letter-spacing: 0.15em;
            text-shadow: 1px 1px 0px var(--color-dark);
        }

        .mobile-socials-container {
            display: flex;
            gap: 1.2rem;
        }

        .mobile-socials-container .social-icon {
            background-color: var(--color-light);
            border: 3px solid var(--color-dark);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 3.5px 3.5px 0px var(--color-dark);
            transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .mobile-socials-container .social-icon:hover {
            background-color: var(--color-yellow);
            transform: translate(-3px, -3px) rotate(-8deg);
            box-shadow: 5px 5px 0px var(--color-dark);
        }

        .mobile-socials-container .social-icon svg {
            width: 20px;
            height: 20px;
            fill: var(--color-dark);
        }

        /* Gallery Promo Section */
        .gallery-promo {
            background-color: var(--color-yellow);
            padding: 5rem 0;
            border-bottom: var(--border-width) solid var(--color-dark);
            position: relative;
            z-index: 10;
        }

        .gallery-promo-title {
            font-family: var(--font-headline);
            font-size: 2.2rem;
            color: var(--color-dark);
            text-align: center;
            margin-bottom: 4rem;
            line-height: 1.3;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .gallery-grid {
            display: flex;
            justify-content: center;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 0;
        }

        .gallery-card {
            flex: 1;
            max-width: 280px;
            margin: 0 0.5rem;
            border: 5px solid var(--color-dark);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 8px 8px 0px var(--color-dark);
            background-color: var(--color-light);
            aspect-ratio: 9 / 13;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .gallery-card:hover {
            transform: scale(1.08) rotate(0deg) translateY(0) !important;
            box-shadow: 12px 12px 0px var(--color-dark);
            z-index: 10 !important;
        }

        /* Tilted position card classes matching 2nd screenshot layout */
        .card-rotate-1 {
            transform: rotate(-8deg) translateY(30px);
            z-index: 1;
        }

        .card-rotate-2 {
            transform: rotate(-3deg) translateY(-20px);
            z-index: 4;
        }

        .card-rotate-3 {
            transform: rotate(4deg) translateY(15px);
            z-index: 3;
        }

        .card-rotate-4 {
            transform: rotate(8deg) translateY(-15px);
            z-index: 2;
        }

        .gallery-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Responsive styles for gallery */
        @media (max-width: 992px) {
            .gallery-promo {
                padding: 4rem 0;
            }
            .gallery-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem 1.5rem;
                padding: 1.5rem;
                max-width: 600px;
                margin: 0 auto;
            }
            .gallery-card {
                flex: none;
                max-width: 100%;
                margin: 0;
                border-width: 4px;
                border-radius: 20px;
                box-shadow: 6px 6px 0px var(--color-dark);
            }
            /* Subtle rotations for 2-column grid layout */
            .card-rotate-1 {
                transform: rotate(-4deg) translateY(5px);
            }
            .card-rotate-2 {
                transform: rotate(-2deg) translateY(-5px);
            }
            .card-rotate-3 {
                transform: rotate(2deg) translateY(5px);
            }
            .card-rotate-4 {
                transform: rotate(4deg) translateY(-5px);
            }
            .gallery-promo-title {
                font-size: 1.5rem;
                margin-bottom: 2rem;
            }
        }

        @media (max-width: 576px) {
            .gallery-promo {
                padding: 3rem 0;
            }
            .gallery-grid {
                gap: 1.5rem 1rem;
                padding: 0.75rem;
            }
            .gallery-card {
                border-width: 3px;
                border-radius: 16px;
                box-shadow: 4px 4px 0px var(--color-dark);
            }
            .gallery-promo-title {
                font-size: 1.25rem;
                margin-bottom: 1.5rem;
            }
        }
        [x-cloak] { display: none !important; }

        /* Responsive Menu Grid Layout and Spacing */
        @media (max-width: 768px) {
            .menu-grid {
                grid-template-columns: 1fr !important;
                gap: 1.75rem !important;
            }
        }

        /* Increased spacing between items in the showcase lists */
        .drinks-showcase-grid,
        .sides-showcase-grid {
            gap: 0.6rem !important;
        }
        .drinks-showcase-item,
        .sides-showcase-item {
            padding-bottom: 0.5rem !important;
        }

        /* Spacing and style for Allergy Disclaimer */
        .allergy-disclaimer {
            text-align: center;
            margin-top: 2.25rem;
            margin-bottom: 2rem;
            padding: 0 1rem;
        }
        .allergy-disclaimer-text {
            color: rgba(255, 255, 255, 0.6);
            font-style: italic;
            font-size: 0.95rem;
            font-family: 'Biski_2', sans-serif;
            line-height: 1.5;
        }

        /* Extra Notice Card Styling */
        .extra-notice-card {
            text-align: left;
            margin-top: 2.5rem;
            background-color: var(--color-yellow);
            border: 3px solid var(--color-dark);
            border-radius: 20px;
            padding: 1.25rem 1.5rem;
            box-shadow: 4px 4px 0px var(--color-dark);
            display: inline-block;
            width: 100%;
            box-sizing: border-box;
        }
        @media (min-width: 768px) {
            .extra-notice-card {
                max-width: 520px;
                width: auto;
            }
        }

        .drinks-highlight-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
        @media (max-width: 992px) {
            .drinks-highlight-grid {
                grid-template-columns: 1fr !important;
            }
        }
        @media (min-width: 993px) {
            .drinks-highlight-grid .drinks-showcase:first-child {
                grid-column: span 2 !important;
            }
        }
        @media (min-width: 768px) {
            .drinks-showcase-grid.cols-2 {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.6rem 1.5rem !important;
            }
        }
    
    </style>
</head>
<body x-data="{ mobileMenuOpen: false }">

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" :class="mobileMenuOpen ? 'open' : ''" x-cloak>
        <div class="mobile-links-container">
            <a href="#hero" class="menu-link" @click="mobileMenuOpen = false">{{ __('public.nav_home') }}</a>
            <a href="#menu-section" class="menu-link" @click="mobileMenuOpen = false">{{ __('public.nav_menu') }}</a>
            <a href="#contact" class="menu-link" @click="mobileMenuOpen = false">{{ __('public.nav_find_us') }}</a>
        </div>
        
        <div class="mobile-actions-container">
            <a href="{{ route('reservations.public.create') }}" class="sticker sticker-large text-center sticker-btn" @click="mobileMenuOpen = false">{{ __('public.nav_reserve') }}</a>
            @if(config('features.takeaway', false))
                <a href="{{ route('order.public') }}" class="sticker sticker-large sticker-white text-center sticker-btn" @click="mobileMenuOpen = false">{{ __('public.nav_order_online') }}</a>
            @endif
        </div>

        <div class="mobile-lang-container-wrap" style="margin-top: 0.75rem; margin-bottom: 0.75rem; display: flex; justify-content: center; width: 100%;">
            <x-language-switcher theme="dark" />
        </div>

        <div class="mobile-socials-section">
            <p class="mobile-socials-title">Connect</p>
            <div class="mobile-socials-container">
                <a href="https://www.instagram.com/koyabcn" target="_blank" class="social-icon" aria-label="Instagram">
                    <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                </a>
                <a href="https://www.facebook.com/koyabcn" target="_blank" class="social-icon" aria-label="Facebook">
                    <svg viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/></svg>
                </a>
                <a href="https://wa.me/34671620787" target="_blank" class="social-icon" aria-label="WhatsApp">
                    <svg viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.504-5.714-1.466L0 24zm12.35-3.555c1.72-.001 3.4-.462 4.87-1.334l.348-.206 3.626.95-.968-3.535.226-.36c.962-1.532 1.467-3.31 1.464-5.14-.003-5.263-4.286-9.544-9.554-9.544-2.553 0-4.954.994-6.762 2.804-1.808 1.81-2.8 4.212-2.8 6.768-.002 5.263 4.282 9.545 9.548 9.545zm5.244-7.142c-.287-.143-1.697-.838-1.959-.933-.262-.096-.453-.143-.642.143-.19.285-.733.933-.898 1.121-.165.189-.33.213-.617.07-1.122-.56-1.916-.985-2.673-2.285-.195-.336-.195-.543-.053-.685.127-.127.287-.333.43-.5.142-.167.19-.285.287-.476.096-.19.048-.356-.024-.5-.072-.143-.643-1.551-.881-2.122-.23-.556-.464-.48-.642-.489-.166-.008-.357-.01-.548-.01-.19 0-.5.07-.762.356-.262.285-1 .978-1 2.386 0 1.407 1.024 2.766 1.167 2.956.143.19 2.016 3.078 4.883 4.316.682.294 1.214.47 1.63.602.686.218 1.311.187 1.805.114.55-.082 1.697-.693 1.936-1.363.238-.67.238-1.246.166-1.363-.07-.118-.261-.19-.547-.333z"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Header -->
    <header :class="mobileMenuOpen ? 'header-overlay-open' : ''">
        <div class="container header-container">
            <a href="#" class="logo-wrap">
                <img src="https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg" alt="KOYA Logo" class="logo-img" onerror="this.src='/favicon.ico'">
            </a>

            <nav class="nav-links">
                <a href="#hero">{{ __('public.nav_home') }}</a>
                <a href="#menu-section">{{ __('public.nav_menu') }}</a>
                <a href="#contact">{{ __('public.nav_find_us') }}</a>
            </nav>

            <div class="nav-actions">
                <x-language-switcher theme="dark" class="koya-lang-header-hide" style="margin-right: 0.5rem;" />
                <a href="{{ route('reservations.public.create') }}" class="btn-koya-primary header-reserve-btn" style="font-size: 1rem; padding: 0.6rem 1.2rem; border-width: 3px; display: inline-flex;">{{ __('public.nav_reserve') }}</a>
                <button class="mobile-toggle" @click="mobileMenuOpen = !mobileMenuOpen" :class="mobileMenuOpen ? 'is-open' : ''" aria-label="{{ __('public.nav_menu') }}">
                    <div class="hamburger-inner">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="hero">
        <!-- Floating graphics (SVGs drawn in style matching KOYA colors) -->
        <svg class="floating-element float-1" width="60" height="60" viewBox="0 0 100 100">
            <!-- Tomato Slice -->
            <circle cx="50" cy="50" r="45" fill="#f24423" stroke="#121212" stroke-width="6"/>
            <circle cx="50" cy="50" r="35" fill="#d12e0f" />
            <circle cx="35" cy="40" r="6" fill="#f24423" />
            <circle cx="65" cy="40" r="6" fill="#f24423" />
            <circle cx="50" cy="65" r="6" fill="#f24423" />
        </svg>

        <svg class="floating-element float-2" width="70" height="70" viewBox="0 0 100 100">
            <!-- Cheese Slice -->
            <polygon points="10,15 90,25 65,85 10,15" fill="#fac021" stroke="#121212" stroke-width="6"/>
            <circle cx="35" cy="40" r="8" fill="#e0ab16" />
            <circle cx="60" cy="50" r="5" fill="#e0ab16" />
        </svg>

        <svg class="floating-element float-3" width="85" height="85" viewBox="0 0 100 100">
            <!-- Lettuce leaf -->
            <path d="M 20 60 C 10 40, 20 15, 50 20 C 80 15, 90 40, 80 60 C 70 80, 30 80, 20 60 Z" fill="#4fa838" stroke="#121212" stroke-width="6"/>
            <path d="M 50 20 Q 50 50 50 75" fill="none" stroke="#121212" stroke-width="4"/>
            <path d="M 50 40 Q 30 35 25 40" fill="none" stroke="#121212" stroke-width="4"/>
            <path d="M 50 55 Q 70 50 75 55" fill="none" stroke="#121212" stroke-width="4"/>
        </svg>

        <svg class="floating-element float-4" width="50" height="50" viewBox="0 0 100 100">
            <!-- Sesame seeds -->
            <path d="M 30 30 Q 35 15 40 30 Q 35 45 30 30 Z" fill="#ebd6b0" stroke="#121212" stroke-width="3" transform="rotate(15 30 30)"/>
            <path d="M 70 40 Q 75 25 80 40 Q 75 55 70 40 Z" fill="#ebd6b0" stroke="#121212" stroke-width="3" transform="rotate(-20 70 40)"/>
            <path d="M 45 70 Q 50 55 55 70 Q 50 85 45 70 Z" fill="#ebd6b0" stroke="#121212" stroke-width="3" transform="rotate(45 45 70)"/>
        </svg>

        <div class="container hero-grid">
            <div class="hero-content">

                <h1 class="hero-title">Smash Burger<br><span>& BOL</span></h1>

                <div class="hero-btns">
                    <a href="#menu-section" class="btn-koya-primary">{{ __('public.homepage_hero_cta_menu') }}</a>
                    <a href="{{ route('reservations.public.create') }}" class="btn-koya-secondary">{{ __('public.nav_reserve') }}</a>
                </div>
            </div>

            <div class="hero-visual">
                <!-- Rotating circular badge -->
                <div class="rotating-badge-wrap">
                    <svg class="rotating-badge-svg" viewBox="0 0 200 200">
                        <path id="circlePath" d="M 100, 100 m -80, 0 a 80,80 0 1,1 160,0 a 80,80 0 1,1 -160,0" fill="none"/>
                        <text font-family="'Bubbleboddy', 'Outfit', sans-serif" font-size="13.5" font-weight="900" fill="#fac021" letter-spacing="3.5">
                            <textPath href="#circlePath">
                                SMASH BURGER & BOL &#8226; SMASH BURGER & BOL &#8226; SMASH BURGER & BOL &#8226;
                            </textPath>
                        </text>
                    </svg>
                </div>
                <div class="hero-main-img-wrap">
                    <img src="{{ asset('images/smash-burger.png') }}" alt="KOYA Double Smash Burger" class="hero-main-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery/Fusion Promo Section -->
    <section class="gallery-promo">
        <div class="container">
            <h2 class="gallery-promo-title reveal">
                {{ __('public.homepage_gallery_title') }}
            </h2>
            <div class="gallery-grid">
                <div class="gallery-card card-rotate-1 reveal">
                    <img src="{{ asset('images/gallery-1.jpg') }}" alt="KOYA Chef holding Smash Burger" class="gallery-img" loading="lazy">
                </div>
                <div class="gallery-card card-rotate-2 reveal">
                    <img src="{{ asset('images/gallery-2.jpg') }}" alt="KOYA Bowl food lift with fork" class="gallery-img" loading="lazy">
                </div>
                <div class="gallery-card card-rotate-3 reveal">
                    <img src="{{ asset('images/gallery-3.jpg') }}" alt="Assembling a KOYA Burger" class="gallery-img" loading="lazy">
                </div>
                <div class="gallery-card card-rotate-4 reveal">
                    <img src="{{ asset('images/gallery-4.jpg') }}" alt="KOYA Chef smiling with Smash Burger" class="gallery-img" loading="lazy">
                </div>
            </div>
        </div>
    </section>



    <!-- Menu Section -->
    <section id="menu-section" class="menu-section" x-data="{ activeTab: 'Smash Burgers' }">
        <div class="container">
            <div class="section-header reveal">
                <h2 class="section-title">{{ __('public.homepage_menu_title') }}</h2>
            </div>

            <!-- Category Tabs -->
            <div class="menu-tabs-container reveal">
                <div class="menu-tabs">
                    <button class="menu-tab" :class="activeTab === 'Smash Burgers' ? 'active' : ''" @click="activeTab = 'Smash Burgers'">SMASH BURGER</button>
                    <button class="menu-tab" :class="activeTab === 'BOL' ? 'active' : ''" @click="activeTab = 'BOL'">BOL (Bowls)</button>
                    <button class="menu-tab" :class="activeTab === 'Starters' ? 'active' : ''" @click="activeTab = 'Starters'">Entrantes</button>
                    <button class="menu-tab" :class="activeTab === 'Drinks' ? 'active' : ''" @click="activeTab = 'Drinks'">{{ __('public.menu_tab_drinks') }}</button>
                    <button class="menu-tab" :class="activeTab === 'Desserts' ? 'active' : ''" @click="activeTab = 'Desserts'">{{ __('public.menu_tab_desserts') }}</button>
                </div>
            </div>

            <!-- Menu Cards Grid -->
            @php
            // Prepare items matching database content or using our high quality fallbacks
            $imageMap = [
                'Spicy Korean Rice Bowl' => 'katsudon.png',
                'Mediterranean Rice Bowl' => 'koya-salad.png',
                'Teriyaki Tofu Bowl' => 'koya-salad.png',
                'KOYA SMASH SPECIAL' => 'smash-burger.png',
                'KIMCHI BURGER' => 'kimchI_burguer.png',
                'TARTUFATA' => 'tartufata.png',
                'ZORSADA' => 'ZORSADA.PNG',
                'RED ONION' => 'red-onion.png',
                'CANGRE BURGER' => 'cangre_burguer.png',
                'KOYA SALAD' => 'koya-salad.png',
                'SALMON SUPREME' => 'Salmon_supreme.png',
                'ADOBO' => 'adobo.png',
                'KATSUDON' => 'katsudon.png',
                'SOBOKU' => 'soboku.png',
            ];

            $displayItems = [];
            if ($menuItems && $menuItems->count() > 0) {
                foreach ($menuItems as $item) {
                    $category = $item->category;
                    $tabCategory = 'Starters';
                    if ($category === 'Smash Burger' || $category === 'Smash Burgers') {
                        $tabCategory = 'Smash Burgers';
                    } elseif (in_array($category, ['Bol de Arroz', 'Rice Bowls', 'BOL'])) {
                        $tabCategory = 'BOL';
                    } elseif (in_array($category, ['Entrantes', 'Starters'])) {
                        $tabCategory = 'Starters';
                    } elseif (stripos($category, 'acomp') !== false || in_array(strtolower(trim($category)), ['sides', 'fries'])) {
                        $tabCategory = 'Fries';
                    } elseif (in_array($category, ['Bebidas', 'Cervezas', 'Cocteles', 'Cafes', 'Drinks', 'Beers', 'Cocktails', 'Coffee'])) {
                        $tabCategory = 'Drinks';
                    } elseif (in_array($category, ['Postres', 'Postres de la Casa', 'Homemade Desserts', 'Desserts'])) {
                        $tabCategory = 'Desserts';
                    }

                    $description = $item->description ?? $item->package_details ?? '';

                    $badge = null;
                    if ($item->name === 'Spicy Korean Rice Bowl') $badge = 'New';
                    elseif ($item->name === 'Cheesecake Jar') $badge = 'KOYA Favorite';
                    elseif ($item->name === 'TARTUFATA') $badge = 'Truffle';
                    elseif ($item->name === 'RED ONION') $badge = 'Smoky';
                    elseif ($item->name === 'KOYA SALAD') $badge = 'Fresh';
                    elseif ($item->name === 'KATSUDON') $badge = 'Crispy';
                    elseif ($item->name === 'SOBOKU') $badge = 'Simple';
                    
                    $displayItems[] = [
                        'name' => $item->name,
                        'category' => $item->category,
                        'tab_category' => $tabCategory,
                        'price' => $item->price,
                        'description' => $description,
                        'badge' => $badge,
                        'image_url' => $item->image_url,
                        'image_thumbnail_url' => $item->image_thumbnail_url,
                    ];
                }
            } else {
                // Fallbacks if DB is completely empty
                $displayItems = [
                    ['name' => 'KOYA SMASH SPECIAL', 'tab_category' => 'Smash Burgers', 'price' => 13.99, 'description' => 'Pan cronut, rabo de toro, ternera, queso havarti, lechuga, cebolla crujiente y salsa casera', 'badge' => null],
                    ['name' => 'WAGYU', 'tab_category' => 'Smash Burgers', 'price' => 12.95, 'description' => 'Pan brioche, Wagyu, pepino, queso cheddar y salsa de la casa', 'badge' => null],
                    ['name' => 'KIMCHI BURGER', 'tab_category' => 'Smash Burgers', 'price' => 10.00, 'description' => 'Pollo frito, kimchi casero, salsa hoisin, lechuga y cebolla japonesa', 'badge' => null],
                    ['name' => 'HAWAIIANA', 'tab_category' => 'Smash Burgers', 'price' => 10.99, 'description' => "Ternera, lechuga, pi\u{00F1}a, queso cheddar, bacon, salsa barbacoa y cebolla crujiente", 'badge' => null],
                    ['name' => 'TARTUFATA', 'tab_category' => 'Smash Burgers', 'price' => 12.50, 'description' => "Ternera, champi\u{00F1}ones salteados, queso havarti con salsa trufa y perejil", 'badge' => 'Truffle'],
                    ['name' => 'VEGAN', 'tab_category' => 'Smash Burgers', 'price' => 12.00, 'description' => 'Pan vegano o sin gluten, carne vegana, lechuga, cebolla encurtida, pepino encurtido y salsa miso', 'badge' => null],
                    ['name' => 'ZORSADA', 'tab_category' => 'Smash Burgers', 'price' => 11.95, 'description' => 'Pan brioche, ternera, zorza y sobrasada de Lugo con sofrito de cebolla, queso cheddar y huevo frito', 'badge' => null],
                    ['name' => 'RED ONION', 'tab_category' => 'Smash Burgers', 'price' => 10.25, 'description' => 'Ternera, cebolla caramelizada, queso cheddar, col morada y cebolla encurtida', 'badge' => 'Smoky'],
                    ['name' => 'KRISPY GARLIC CHICKEN', 'tab_category' => 'Smash Burgers', 'price' => 9.99, 'description' => 'Pollo rebozado, lechuga, queso cheddar, cebolla crujiente y salsa agridulce', 'badge' => null],
                    ['name' => 'CANGRE BURGER', 'tab_category' => 'Smash Burgers', 'price' => 11.99, 'description' => "Pan brioche, cangrejo c\u{00E1}scara blanda rebozado de panko japon\u{00E9}s con encurtida de pepino y cebolla roja con salsa ahumada de la casa", 'badge' => null],
                    ['name' => 'CHIQUI BURGER', 'tab_category' => 'Smash Burgers', 'price' => 7.00, 'description' => 'Pan brioche, una patty, queso cheddar con salsa de la casa con patatas incluidas', 'badge' => null],
                    ['name' => 'SOBOKU', 'tab_category' => 'Smash Burgers', 'price' => 13.50, 'description' => "Osobuco de ternera a baja temperatura, r\u{00FA}cula, berenjena, setas de shimeji y queso havarti con salsa de la casa", 'badge' => 'Simple'],
                    ['name' => 'SALMON SUPREME', 'tab_category' => 'BOL', 'price' => 13.00, 'description' => "Salm\u{00F3}n al horno, arroz, salsa teriyaki de pescado, pepino y tomate concasse", 'badge' => null],
                    ['name' => 'ADOBO', 'tab_category' => 'BOL', 'price' => 10.00, 'description' => "Trocitos de panceta de cerdo marinado de soja con cebolla pochada y cocinado al wok", 'badge' => null],
                    ['name' => 'KATSUDON', 'tab_category' => 'BOL', 'price' => 11.25, 'description' => "Lomo empanado, s\u{00E9}samo, salsa tonkatsu, cebolla crujiente y rodajas de rabanitos con huevo frito", 'badge' => 'Crispy'],
                    ['name' => 'KOYA SALAD', 'tab_category' => 'Starters', 'price' => 6.50, 'description' => 'lechuga, wakame, sonomono, col valencia, cebolla crujiente y vinagreta de la casa', 'badge' => 'Fresh'],
                    ['name' => 'GYOZAS FRITAS IBÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¾Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¾ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¾ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¾Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â¦ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¦ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â°RICAS (5 uds.)', 'tab_category' => 'Starters', 'price' => 9.75, 'description' => "Ib\u{00E9}rico, shiitake, cebolla Japonesa, aceite de s\u{00E9}samo y salsa de mayonesa hoisin", 'badge' => null],
                    ['name' => 'EDAMAME AL VAPOR', 'tab_category' => 'Starters', 'price' => 4.50, 'description' => "Sal maldon, aceite de s\u{00E9}samo y shichimi", 'badge' => null],
                    ['name' => 'SWEET & SPICY WINGS', 'tab_category' => 'Starters', 'price' => 7.75, 'description' => 'Alitas de pollo, gomashio, cebolla japonesa y salsa teriyaki', 'badge' => null],
                    ['name' => 'EBI FRY (5 uds.)', 'tab_category' => 'Starters', 'price' => 7.75, 'description' => 'Tempura de gambas y salsa de miel wasabi', 'badge' => null],
                    ['name' => 'ROLLITOS (5 uds.)', 'tab_category' => 'Starters', 'price' => 8.99, 'description' => 'Rollito de ternera y verdura con tartufata', 'badge' => null],
                    ['name' => 'KROQUETAS (5 uds.)', 'tab_category' => 'Starters', 'price' => 10.00, 'description' => 'Croquetas de ternera con rabo de toro', 'badge' => null],
                    ['name' => 'TACOS DE CARILLADA DE TERNERA', 'tab_category' => 'Starters', 'price' => 11.99, 'description' => 'Galtas de ternera, vino tinto y bresa al fuego lento con arroz blanco', 'badge' => null],
                    ['name' => 'BIL-BAO', 'tab_category' => 'Starters', 'price' => 9.99, 'description' => '2 mini pan bao relleno de cochinita pibil', 'badge' => null],
                    ['name' => 'KROQUETAS de berenjena con trufa (POR UNIDAD)', 'tab_category' => 'Starters', 'price' => 2.50, 'description' => 'Berenjena asada empanada de panko japonesa con trufa, miso rojo katsuobushi y pure', 'badge' => null],
                    ['name' => 'POP-billas', 'tab_category' => 'Starters', 'price' => 16.50, 'description' => "Pulpo hervido salteado de salsa kimchi, patatas con aji amarillo y chimichurri japon\u{00E9}s", 'badge' => null],
                    ['name' => 'BONIATO FRITO', 'tab_category' => 'Fries', 'price' => 4.20, 'description' => 'Sal, perejil, oregano y katsubishi', 'badge' => null],
                    ['name' => 'PATATAS FRITAS', 'tab_category' => 'Fries', 'price' => 3.50, 'description' => '', 'badge' => null],
                    ['name' => 'MEZCLUM FRIES', 'tab_category' => 'Fries', 'price' => 4.50, 'description' => 'Patatas y boniato', 'badge' => null],
                    ['name' => 'BRAVAS', 'tab_category' => 'Fries', 'price' => 4.00, 'description' => 'Con triple salsa de la casa', 'badge' => null],
                    ['name' => 'VERMUT DE LA CASA', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 3.50, 'description' => '', 'badge' => null],
                    ['name' => 'VINO BLANCO', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 3.85, 'description' => '', 'badge' => null],
                    ['name' => 'VINO TINTO', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 3.60, 'description' => '', 'badge' => null],
                    ['name' => 'COPA DE SANGRIA', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 4.50, 'description' => '', 'badge' => null],
                    ['name' => '1 L SANGRIA', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 12.75, 'description' => '', 'badge' => null],
                    ['name' => 'COPA DE CERVEZA', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 2.30, 'description' => '', 'badge' => null],
                    ['name' => 'CLARA', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 2.40, 'description' => '', 'badge' => null],
                    ['name' => 'PINTA', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 5.50, 'description' => '', 'badge' => null],
                    ['name' => 'NESTEA', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 2.60, 'description' => '', 'badge' => null],
                    ['name' => 'AQUARIUS', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 2.60, 'description' => '', 'badge' => null],
                    ['name' => 'FANTA', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 2.40, 'description' => 'LIMÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¾Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¾ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¾ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¾Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â¦ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¦ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â¦ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦ÃƒÂ¢Ã¢â€šÂ¬Ã…â€œN / NARANJA', 'badge' => null],
                    ['name' => 'COCA COLA', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 2.40, 'description' => 'NORMAL / ZERO', 'badge' => null],
                    ['name' => 'AGUA GRANDE 50CL', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 2.20, 'description' => '', 'badge' => null],
                    ['name' => 'AGUA CON GAS 33CL', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 2.00, 'description' => '', 'badge' => null],
                    ['name' => 'ZUMO DE FRUTAS', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 2.50, 'description' => '', 'badge' => null],
                    ['name' => 'TINTO VERANO', 'category' => 'Bebidas', 'tab_category' => 'Drinks', 'price' => 3.95, 'description' => '', 'badge' => null],
                    ['name' => 'CAFE SOLO', 'category' => 'Cafes', 'tab_category' => 'Drinks', 'price' => 1.40, 'description' => '', 'badge' => null],
                    ['name' => 'CAFE CORTADO', 'category' => 'Cafes', 'tab_category' => 'Drinks', 'price' => 1.50, 'description' => '', 'badge' => null],
                    ['name' => 'CAFE CON LECHE', 'category' => 'Cafes', 'tab_category' => 'Drinks', 'price' => 1.60, 'description' => '', 'badge' => null],
                    ['name' => 'CAFE AMERICANO', 'category' => 'Cafes', 'tab_category' => 'Drinks', 'price' => 1.50, 'description' => '', 'badge' => null],
                    ['name' => 'CAFE TRIFASICO', 'category' => 'Cafes', 'tab_category' => 'Drinks', 'price' => 2.60, 'description' => '', 'badge' => null],
                    ['name' => 'INFUSIONES', 'category' => 'Cafes', 'tab_category' => 'Drinks', 'price' => 2.30, 'description' => '', 'badge' => null],
                    ['name' => 'TE VERDE o NEGRO', 'category' => 'Cafes', 'tab_category' => 'Drinks', 'price' => 2.65, 'description' => '', 'badge' => null],
                    ['name' => "CARAJILLO DE BAILEY'S", 'category' => 'Cafes', 'tab_category' => 'Drinks', 'price' => 2.60, 'description' => '', 'badge' => null],
                    ['name' => 'CARAJILLO DE WHISKY/BRANDY', 'category' => 'Cafes', 'tab_category' => 'Drinks', 'price' => 2.60, 'description' => '', 'badge' => null],
                    ['name' => 'CHUPITO DE WHISKY/BRANDY', 'category' => 'Cafes', 'tab_category' => 'Drinks', 'price' => 2.00, 'description' => '', 'badge' => null],
                    ['name' => 'CHUPITO DE ALMENDRA', 'category' => 'Cafes', 'tab_category' => 'Drinks', 'price' => 2.00, 'description' => '', 'badge' => null],
                    ['name' => 'MOJITO', 'category' => 'Cocteles', 'tab_category' => 'Drinks', 'price' => 8.50, 'description' => 'Bacardi, sifÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¾Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¾ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¾ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¾Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â¦ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¦ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â³n y menta', 'badge' => null],
                    ['name' => 'TEQUILA SUNRISE', 'category' => 'Cocteles', 'tab_category' => 'Drinks', 'price' => 8.50, 'description' => 'Zumo de naranja, tequila & granadina', 'badge' => null],
                    ['name' => 'NEGRONI', 'category' => 'Cocteles', 'tab_category' => 'Drinks', 'price' => 8.50, 'description' => 'Martini rosso, Campari, gin', 'badge' => null],
                    ['name' => 'VODKA 7', 'category' => 'Cocteles', 'tab_category' => 'Drinks', 'price' => 8.50, 'description' => 'Vodka & refresco de 7 Up', 'badge' => null],
                    ['name' => 'GIN TONIC', 'category' => 'Cocteles', 'tab_category' => 'Drinks', 'price' => 8.00, 'description' => 'Gin & tÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¾Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¾ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¾ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¾Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â¦ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¦ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â³nica', 'badge' => null],
                    ['name' => 'ESTRELLA DAMM', 'category' => 'Cervezas', 'tab_category' => 'Drinks', 'price' => 2.40, 'description' => '', 'badge' => null],
                    ['name' => 'ESTRELLA FREE DAMM', 'category' => 'Cervezas', 'tab_category' => 'Drinks', 'price' => 2.60, 'description' => '', 'badge' => null],
                    ['name' => 'ESTRELLA FREE DAMM TOSTADA', 'category' => 'Cervezas', 'tab_category' => 'Drinks', 'price' => 2.50, 'description' => '', 'badge' => null],
                    ['name' => 'TURIA', 'category' => 'Cervezas', 'tab_category' => 'Drinks', 'price' => 2.60, 'description' => '', 'badge' => null],
                    ['name' => 'VOLLDAMN', 'category' => 'Cervezas', 'tab_category' => 'Drinks', 'price' => 2.60, 'description' => '', 'badge' => null],
                    ['name' => 'MANGO FLOAT', 'tab_category' => 'Desserts', 'price' => 5.90, 'description' => 'Mango fresco, galletas y crema de leche', 'badge' => null],
                    ['name' => 'BROWNIE DE PISTACHIO', 'tab_category' => 'Desserts', 'price' => 5.75, 'description' => 'Harina de almendra y pistachio', 'badge' => null],
                    ['name' => 'MATCHA CHEESECAKE', 'tab_category' => 'Desserts', 'price' => 6.00, 'description' => 'Tarta de queso con matcha', 'badge' => null],
                    ['name' => 'TARTA DE CHOCOLATE', 'tab_category' => 'Desserts', 'price' => 5.50, 'description' => 'Chocolate negro, azucar moreno, huevo, leche, almendras y mermelada de melocoton', 'badge' => null],
                    ['name' => 'KOULANT', 'tab_category' => 'Desserts', 'price' => 5.25, 'description' => 'Coulant de chocolate con vainilla', 'badge' => null],
                ];
            }
            $firstCocktailName = collect($displayItems)->firstWhere('category', 'Cocteles')['name'] ?? null;
            @endphp

            <div class="menu-grid">
                @foreach($displayItems as $item)
                    @php
                    $imageName = $imageMap[$item['name']] ?? null;
                    $imageUrl = $item['image_thumbnail_url'] ?? $item['image_url'] ?? ($imageName ? asset('images/' . $imageName) : null);
                    @endphp
                    @php
                    $skipStandardDrinkCard = $item['tab_category'] === 'Drinks';
                    @endphp
                    @if(! $skipStandardDrinkCard)
                        <div class="menu-card-simple reveal" x-show="activeTab === '{{ $item['tab_category'] }}'"
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                            <div class="menu-item-inner">
                                <div class="menu-item-info">
                                    <div class="menu-item-top">
                                        <h3 class="menu-item-name">{!! preg_replace('/(\(\d+\s*uds\.?\))/i', '<span style="font-size: 0.75em; opacity: 0.85; font-weight: normal; text-transform: none; display: inline-block; margin-left: 4px;">$1</span>', e($item['name'])) !!}</h3>
                                        <span class="menu-item-price">{{ number_format($item['price'], 2) }}&euro;</span>
                                    </div>
                                    @if(!empty($item['description']))
                                        <p class="menu-item-description" style="margin-top: 0.25rem; min-height: auto;">{{ $item['description'] }}</p>
                                    @endif
                                </div>
                                @if(!empty($imageUrl))
                                    <div class="menu-item-img-container">
                                        <img src="{{ $imageUrl }}" alt="{{ $item['name'] }}" loading="lazy" decoding="async" width="100" height="100" @if($item['name'] === 'ADOBO') style="transform: scale(1.1);" @endif onerror="this.style.display='none'">
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            @php
                $sideItems = collect($displayItems)->where('tab_category', 'Fries')->unique('name')->values();
                
                $drinks = collect($displayItems)->where('tab_category', 'Drinks');
                $drinkCoffeeItems = $drinks->where('category', 'Cafes')->values();
                $drinkCocktailItems = $drinks->where('category', 'Cocteles')->values();
                
                $drinkBeerItems = $drinks->filter(function($item) {
                    return ($item['category'] ?? '') === 'Cervezas' || 
                           preg_match('/(cerveza|clara|pinta)/i', $item['name']);
                })->values();
                
                $drinkWineItems = $drinks->filter(function($item) {
                    return preg_match('/(vino|sangria|vermut|tinto)/i', $item['name']);
                })->values();
                
                $usedDrinkNames = $drinkCoffeeItems->pluck('name')
                    ->merge($drinkCocktailItems->pluck('name'))
                    ->merge($drinkBeerItems->pluck('name'))
                    ->merge($drinkWineItems->pluck('name'))
                    ->unique()
                    ->toArray();
                
                $drinkSoftItems = $drinks->filter(function($item) use ($usedDrinkNames) {
                    return !in_array($item['name'], $usedDrinkNames);
                })->values();
            @endphp

            <div class="drinks-highlight-grid reveal" x-show="activeTab === 'Drinks'"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                
                <!-- 1. Refrescos y Aguas -->
                <div class="drinks-showcase">
                    <div class="drinks-showcase-title">Refrescos y Aguas</div>
                    <div class="drinks-showcase-grid cols-2">
                        @foreach($drinkSoftItems as $item)
                            <div class="drinks-showcase-item">
                                <div class="drinks-showcase-top">
                                    <h3 class="drinks-showcase-name">{{ $item['name'] }}</h3>
                                    <span class="drinks-showcase-price">{{ number_format($item['price'], 2) }}&euro;</span>
                                </div>
                                @if(!empty($item['description']))
                                    <p class="drinks-showcase-description">{{ $item['description'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 2. Cafés y Té -->
                <div class="drinks-showcase">
                    <div class="drinks-showcase-title drinks-showcase-title-image">
                        <img src="{{ asset('images/cafe.png') }}" alt="Café">
                    </div>
                    <div class="drinks-showcase-grid">
                        @foreach($drinkCoffeeItems as $item)
                            <div class="drinks-showcase-item">
                                <div class="drinks-showcase-top">
                                    <h3 class="drinks-showcase-name">{{ $item['name'] }}</h3>
                                    <span class="drinks-showcase-price">{{ number_format($item['price'], 2) }}&euro;</span>
                                </div>
                                @if(!empty($item['description']))
                                    <p class="drinks-showcase-description">{{ $item['description'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Cervezas -->
                <div class="drinks-showcase">
                    <div class="drinks-showcase-title">Cervezas</div>
                    <div class="drinks-showcase-grid">
                        @foreach($drinkBeerItems as $item)
                            <div class="drinks-showcase-item">
                                <div class="drinks-showcase-top">
                                    <h3 class="drinks-showcase-name">{{ $item['name'] }}</h3>
                                    <span class="drinks-showcase-price">{{ number_format($item['price'], 2) }}&euro;</span>
                                </div>
                                @if(!empty($item['description']))
                                    <p class="drinks-showcase-description">{{ $item['description'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 4. Cócteles -->
                <div class="drinks-showcase">
                    <div class="drinks-showcase-title drinks-showcase-title-image">
                        <img src="{{ asset('images/Picture7.png') }}" alt="Cócteles">
                    </div>
                    <div class="drinks-showcase-grid">
                        @foreach($drinkCocktailItems as $item)
                            <div class="drinks-showcase-item">
                                <div class="drinks-showcase-top">
                                    <h3 class="drinks-showcase-name">{{ $item['name'] }}</h3>
                                    <span class="drinks-showcase-price">{{ number_format($item['price'], 2) }}&euro;</span>
                                </div>
                                @if(!empty($item['description']))
                                    <p class="drinks-showcase-description">{{ $item['description'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 5. Vinos y Vermut -->
                <div class="drinks-showcase">
                    <div class="drinks-showcase-title">Vinos y Sangrías</div>
                    <div class="drinks-showcase-grid">
                        @foreach($drinkWineItems as $item)
                            <div class="drinks-showcase-item">
                                <div class="drinks-showcase-top">
                                    <h3 class="drinks-showcase-name">{{ $item['name'] }}</h3>
                                    <span class="drinks-showcase-price">{{ number_format($item['price'], 2) }}&euro;</span>
                                </div>
                                @if(!empty($item['description']))
                                    <p class="drinks-showcase-description">{{ $item['description'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="sides-showcase reveal" x-show="activeTab === 'Starters'"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                <div class="sides-showcase-title sides-showcase-title-image">
                    <img src="{{ asset('images/fritas.png') }}" alt="Acompañamientos / Sides">
                </div>
                <div class="sides-showcase-grid">
                    @foreach($sideItems as $item)
                        <div class="sides-showcase-item">
                            <div class="sides-showcase-top">
                                <h3 class="sides-showcase-name">{!! preg_replace('/(\(\d+\s*uds\.?\))/i', '<span style="font-size: 0.75em; opacity: 0.85; font-weight: normal; text-transform: none; display: inline-block; margin-left: 4px;">$1</span>', e($item['name'])) !!}</h3>
                                <span class="sides-showcase-price">{{ number_format($item['price'], 2) }}&euro;</span>
                            </div>
                            @if(!empty($item['description']))
                                <p class="sides-showcase-description">{{ $item['description'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="reveal extra-notice-card" x-show="activeTab === 'Smash Burgers'"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <h4 style="font-family: var(--font-headline); font-size: 1.35rem; color: var(--color-dark); text-transform: uppercase; margin-bottom: 0.25rem; line-height: 1.2;">
                    Pan vegetariano y Pan Sin Gluten disponible
                </h4>
                <p style="font-family: var(--font-body); font-size: 0.95rem; color: rgba(18, 18, 18, 0.75); margin: 0; font-weight: bold;">
                    +2&euro; de suplemento
                </p>
            </div>

            <!-- Allergy Disclaimer -->
            <div class="allergy-disclaimer reveal">
                <p class="allergy-disclaimer-text">
                    En caso de alergias, consulte con nuestro personal.
                </p>
            </div>
        </div>
    </section>


    <!-- Contact & Location Section -->
    <section id="contact" class="contact">
        <div class="container contact-grid">
            <!-- Contact Info -->
            <div class="contact-card reveal">
                <h3 class="contact-card-title">
                    <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    {{ __('public.homepage_contact_title') }}
                </h3>
                <ul class="contact-info-list">
                    <li class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        </div>
                        <div class="contact-info-text">
                            <h4>{{ __('public.homepage_address_label') }}</h4>
                            <p>{{ __('public.contact_address') }}</p>
                        </div>
                    </li>
                    <li class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg viewBox="0 0 24 24"><path d="M20 15.5c-1.25 0-2.45-.2-3.57-.57-.35-.11-.74-.03-1.02.24l-2.2 2.2c-2.83-1.44-5.15-3.75-6.59-6.59l2.2-2.2c.28-.27.36-.66.25-1.02C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1z"/></svg>
                        </div>
                        <div class="contact-info-text">
                            <h4>{{ __('public.homepage_phone_label') }}</h4>
                            <p><a href="tel:+34931668611">{{ __('public.contact_phone') }}</a> / <a href="https://wa.me/34671620787" target="_blank" rel="noopener noreferrer">{{ __('public.contact_whatsapp') }}</a></p>
                        </div>
                    </li>
                    <li class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </div>
                        <div class="contact-info-text">
                            <h4>{{ __('public.homepage_email_label') }}</h4>
                            <p><a href="mailto:{{ __('public.contact_email') }}">{{ __('public.contact_email') }}</a></p>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Opening Hours -->
            <div class="contact-card reveal">
                <h3 class="contact-card-title">
                    <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                    {{ __('public.homepage_hours_title') }}
                </h3>
                <div class="schedule-list">
                    @php
                    $daysOfWeek = [
                        __('public.day_0'),
                        __('public.day_1'),
                        __('public.day_2'),
                        __('public.day_3'),
                        __('public.day_4'),
                        __('public.day_5'),
                        __('public.day_6'),
                    ];
                    @endphp

                    @if($schedule && $schedule->count() > 0)
                        @foreach($schedule as $sched)
                            <div class="schedule-row">
                                <span class="schedule-day">{{ $daysOfWeek[$sched->day_of_week] }}</span>
                                @if($sched->is_closed)
                                    <span class="schedule-hours closed">{{ __('public.closed_short') }}</span>
                                @else
                                    <span class="schedule-hours">
                                        {{ substr($sched->open_time_1, 0, 5) }} - {{ substr($sched->close_time_1, 0, 5) }}
                                        @if($sched->open_time_2)
                                            &amp; {{ substr($sched->open_time_2, 0, 5) }} - {{ substr($sched->close_time_2, 0, 5) }}
                                        @endif
                                        @if($sched->open_time_3)
                                            &amp; {{ substr($sched->open_time_3, 0, 5) }} - {{ substr($sched->close_time_3, 0, 5) }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <!-- Fallback schedule -->
                        <div class="schedule-row">
                            <span class="schedule-day">{{ __('public.day_2') }}</span>
                            <span class="schedule-hours">18:00 - 23:00</span>
                        </div>
                        <div class="schedule-row">
                            <span class="schedule-day">{{ __('public.day_3') }}</span>
                            <span class="schedule-hours">18:00 - 23:00</span>
                        </div>
                        <div class="schedule-row">
                            <span class="schedule-day">{{ __('public.day_4') }}</span>
                            <span class="schedule-hours">18:00 - 23:00</span>
                        </div>
                        <div class="schedule-row">
                            <span class="schedule-day">{{ __('public.day_5') }}</span>
                            <span class="schedule-hours">13:00 - 16:30 &amp; 19:30 - 23:30</span>
                        </div>
                        <div class="schedule-row">
                            <span class="schedule-day">{{ __('public.day_6') }}</span>
                            <span class="schedule-hours">13:00 - 16:30 &amp; 19:30 - 23:30</span>
                        </div>
                        <div class="schedule-row">
                            <span class="schedule-day">{{ __('public.day_0') }}</span>
                            <span class="schedule-hours closed">{{ __('public.closed_short') }}</span>
                        </div>
                        <div class="schedule-row">
                            <span class="schedule-day">{{ __('public.day_1') }}</span>
                            <span class="schedule-hours closed">{{ __('public.closed_short') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-logo-desc">
                    <a href="#" class="footer-logo-wrap">
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
                    <h4 class="footer-nav-title">{{ __('public.nav_quick_links') }}</h4>
                    <ul class="footer-links">
                        <li><a href="#hero">{{ __('public.nav_home') }}</a></li>
                        <li><a href="#menu-section">{{ __('public.nav_menu') }}</a></li>
                        <li><a href="#contact">{{ __('public.nav_location_hours') }}</a></li>
                    </ul>
                </div>

            </div>

            <div class="footer-bottom">
                <p>{{ __('public.homepage_footer_rights') }}</p>
                <p>{{ __('public.homepage_footer_tagline') }} <a href="http://mergewebs.com/" target="_blank" class="footer-credit-link">Merge</a></p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <!-- Alpine.js is loaded from package.json via Vite, but we ensure it is ready -->
    <script>
        // Simple Scroll Reveal implementation
        document.addEventListener('DOMContentLoaded', () => {
            const reveals = document.querySelectorAll('.reveal');
            
            const revealOnScroll = () => {
                const triggerBottom = window.innerHeight * 0.85;
                reveals.forEach(reveal => {
                    const revealTop = reveal.getBoundingClientRect().top;
                    if (revealTop < triggerBottom) {
                        reveal.classList.add('reveal-active');
                    }
                });
            };

            window.addEventListener('scroll', revealOnScroll);
            revealOnScroll(); // initial trigger

            // Hero Image Cycler - Changes product images dynamically every X seconds (3.5 seconds)
            const heroImgWrap = document.querySelector('.hero-main-img-wrap');
            if (heroImgWrap) {
                const heroImages = [
                    "{{ asset('images/tartufata.png') }}",
                    "{{ asset('images/smash-burger.png') }}",
                    "{{ asset('images/red-onion.png') }}",
                    "{{ asset('images/koya-salad.png') }}",
                    "{{ asset('images/katsudon.png') }}",
                    "{{ asset('images/soboku.png') }}"
                ];
                let currentImgIndex = 0;
                
                setInterval(() => {
                    currentImgIndex = (currentImgIndex + 1) % heroImages.length;
                    
                    const nextSrc = heroImages[currentImgIndex];
                    const tempImg = new Image();
                    tempImg.src = nextSrc;
                    tempImg.onload = () => {
                        // Create new image element
                        const newImg = document.createElement('img');
                        newImg.src = nextSrc;
                        newImg.alt = "KOYA Product Showcase";
                        newImg.className = "hero-main-img";
                        newImg.style.opacity = '0';
                        newImg.style.transform = 'scale(0.95)';
                        
                        heroImgWrap.appendChild(newImg);
                        
                        // Trigger reflow to ensure transition is animated
                        newImg.offsetHeight;
                        
                        // Fade/Scale in the new image
                        newImg.style.opacity = '1';
                        newImg.style.transform = 'scale(1)';
                        
                        // Get all current images to fade them out
                        const oldImgs = Array.from(heroImgWrap.querySelectorAll('.hero-main-img')).filter(img => img !== newImg);
                        oldImgs.forEach(oldImg => {
                            oldImg.style.opacity = '0';
                            oldImg.style.transform = 'scale(1.05)';
                        });
                        
                        // Remove old images after the transition completes
                        setTimeout(() => {
                            oldImgs.forEach(oldImg => oldImg.remove());
                        }, 600);
                    };
                }, 3500); // Cycle every 3.5 seconds
            }
        });
    </script>
</body>
</html>



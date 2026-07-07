<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Nos aventuramos más allá de las 🍔, fusionando la cocina asiática y americana en cada bocado. 🥗 Fresh, fast, and modern street food in Barcelona.">
    <title>KOYA | Smash Burger & BOL</title>

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
            font-family: var(--font-headline);
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
            font-family: var(--font-headline);
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
            border-bottom: var(--border-width) solid var(--color-dark);
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

        /* Hero Section */
        .hero {
            background-color: var(--color-green);
            padding: 8rem 0 5rem 0;
            border-bottom: var(--border-width) solid var(--color-dark);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(rgba(250, 192, 33, 0.15) 20%, transparent 20%);
            background-size: 40px 40px;
            opacity: 0.4;
            pointer-events: none;
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

        .hero-main-img-wrap {
            position: relative;
            width: 420px;
            height: 420px;
            z-index: 10;
        }

        .hero-main-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(10px 10px 0px rgba(0,0,0,0.15));
            transition: opacity 0.4s ease-in-out, transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            animation: tiltShake 6s ease-in-out infinite alternate;
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
            gap: 1rem;
            background-color: var(--color-green-dark);
            padding: 0.6rem;
            border-radius: 9999px;
            border: 3px solid var(--color-dark);
            overflow-x: auto;
            max-width: 100%;
            scrollbar-width: none; /* Firefox */
        }

        .menu-tabs::-webkit-scrollbar {
            display: none; /* Safari & Chrome */
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

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 2rem;
            position: relative;
            z-index: 5;
        }

        .menu-card-simple {
            background-color: white;
            border: 3px solid var(--color-dark);
            border-radius: 20px;
            padding: 1.5rem;
            color: var(--color-dark);
            box-shadow: 5px 5px 0px var(--color-dark);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .menu-card-simple:hover {
            transform: translateY(-4px);
            box-shadow: 8px 8px 0px var(--color-dark);
        }

        .menu-card-simple:hover .menu-item-img-container {
            transform: scale(1.08) rotate(3deg);
        }

        .menu-item-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .menu-item-name {
            font-family: 'Biski', sans-serif;
            font-size: 1.4rem;
            color: var(--color-dark);
            text-transform: uppercase;
        }

        .menu-item-price {
            font-family: 'Biski_2', sans-serif;
            font-size: 1.4rem;
            color: var(--color-green);
            flex-shrink: 0;
        }

        .menu-item-description {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.4;
        }

        .menu-item-badge {
            position: absolute;
            top: 10px;
            right: 10px;
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
            background-color: var(--color-light);
            border-bottom: var(--border-width) solid var(--color-dark);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3.5rem;
        }

        .contact-card {
            background-color: white;
            border: var(--border-width) solid var(--color-dark);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 8px 8px 0px var(--color-dark);
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
            padding-bottom: 0.5rem;
            border-bottom: 2px dashed #ebd6b0;
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
            color: #cc3333;
        }

        /* Footer */
        footer {
            background-color: var(--color-dark);
            color: #aaa;
            padding: 4rem 0 2rem 0;
            font-size: 0.95rem;
            border-top: var(--border-width) solid var(--color-dark);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-logo-desc {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .footer-logo-title {
            font-family: var(--font-headline);
            font-size: 2rem;
            color: var(--color-yellow);
            text-transform: uppercase;
        }

        .footer-p {
            line-height: 1.6;
        }

        .footer-nav-title {
            font-family: var(--font-headline);
            font-size: 1.3rem;
            color: white;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .footer-links a {
            color: #aaa;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .footer-links a:hover {
            color: var(--color-yellow);
            padding-left: 4px;
        }

        .social-icons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-icon {
            background-color: #222;
            border: 2px solid #444;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .social-icon:hover {
            background-color: var(--color-yellow);
            border-color: var(--color-dark);
            transform: translateY(-3px);
        }

        .social-icon:hover svg {
            fill: var(--color-dark);
        }

        .social-icon svg {
            width: 18px;
            height: 18px;
            fill: white;
            transition: all 0.2s ease;
        }

        .footer-bottom {
            border-top: 1px solid #222;
            padding-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        /* Animations Keyframes */
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
                gap: 4rem;
                text-align: center;
            }

            .hero-content {
                align-items: center;
            }

            .hero-title {
                font-size: 3.5rem;
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

            .hero-title {
                font-size: 3rem;
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

        /* Mobile overlay menu standard styles */
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
    </style>
</head>
<body x-data="{ mobileMenuOpen: false }">

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" :class="mobileMenuOpen ? 'open' : ''">
        <a href="#hero" @click="mobileMenuOpen = false">Home</a>
        <a href="#menu-section" @click="mobileMenuOpen = false">Menu</a>
        <a href="#contact" @click="mobileMenuOpen = false">Find Us</a>
        <a href="{{ route('reservations.public.create') }}" class="sticker text-center" style="display: block; width: 220px;" @click="mobileMenuOpen = false">Reservar</a>
        <a href="{{ route('order.public') }}" class="sticker sticker-white text-center" style="display: block; width: 220px;" @click="mobileMenuOpen = false">Pedir Online</a>
    </div>

    <!-- Navigation Header -->
    <header>
        <div class="container header-container">
            <a href="#" class="logo-wrap">
                <img src="https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg" alt="KOYA Logo" class="logo-img" onerror="this.src='/favicon.ico'">
            </a>

            <nav class="nav-links">
                <a href="#hero">Home</a>
                <a href="#menu-section">Menu</a>
                <a href="#contact">Find Us</a>
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
                    <a href="#menu-section" class="btn-koya-primary">Ver Menú</a>
                    <a href="{{ route('reservations.public.create') }}" class="btn-koya-secondary">Reservar</a>
                </div>
            </div>

            <div class="hero-visual">
                <!-- Rotating circular badge -->
                <div class="rotating-badge-wrap">
                    <svg class="rotating-badge-svg" viewBox="0 0 200 200">
                        <path id="circlePath" d="M 100, 100 m -80, 0 a 80,80 0 1,1 160,0 a 80,80 0 1,1 -160,0" fill="none"/>
                        <text font-family="'Bubbleboddy', 'Outfit', sans-serif" font-size="13.5" font-weight="900" fill="#fac021" letter-spacing="3.5">
                            <textPath href="#circlePath">
                                SMASH BURGER & BOL • SMASH BURGER & BOL • SMASH BURGER & BOL •
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
                NOS AVENTURAMOS MÁS ALLÁ DE LAS 🍔, FUSIONANDO LA COCINA ASIÁTICA Y AMERICANA EN CADA BOCADO. 🥗
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
                <h2 class="section-title">Our Full Menu</h2>
            </div>

            <!-- Category Tabs -->
            <div class="menu-tabs-container reveal">
                <div class="menu-tabs">
                    <button class="menu-tab" :class="activeTab === 'Smash Burgers' ? 'active' : ''" @click="activeTab = 'Smash Burgers'">Burgers</button>
                    <button class="menu-tab" :class="activeTab === 'BOL' ? 'active' : ''" @click="activeTab = 'BOL'">BOL (Bowls)</button>
                    <button class="menu-tab" :class="activeTab === 'Sides' ? 'active' : ''" @click="activeTab = 'Sides'">Sides & Starters</button>
                    <button class="menu-tab" :class="activeTab === 'Drinks' ? 'active' : ''" @click="activeTab = 'Drinks'">Drinks</button>
                    <button class="menu-tab" :class="activeTab === 'Desserts' ? 'active' : ''" @click="activeTab = 'Desserts'">Desserts</button>
                </div>
            </div>

            <!-- Menu Cards Grid -->
            @php
            // Prepare items matching database content or using our high quality fallbacks
            $imageMap = [
                'Classic Smash Burger' => 'smash-burger.png',
                'Truffle Double Smash' => 'tartufata.png',
                'Bacon Cheese Smash' => 'soboku.png',
                'Spicy Korean Rice Bowl' => 'katsudon.png',
                'Mediterranean Rice Bowl' => 'koya-salad.png',
                'Teriyaki Tofu Bowl' => 'koya-salad.png',
                'Tartufata' => 'tartufata.png',
                'Smash Burger' => 'smash-burger.png',
                'Red Onion' => 'red-onion.png',
                'KOYA SALAD' => 'koya-salad.png',
                'KATSUDON' => 'katsudon.png',
                'SOBOKU' => 'soboku.png',
            ];

            $displayItems = [];
            if ($menuItems && $menuItems->count() > 0) {
                foreach ($menuItems as $item) {
                    $category = $item->category;
                    $tabCategory = 'Sides';
                    if ($category === 'Smash Burgers') {
                        $tabCategory = 'Smash Burgers';
                    } elseif ($category === 'Rice Bowls' || $category === 'BOL') {
                        $tabCategory = 'BOL';
                    } elseif (in_array($category, ['Sides', 'Starters'])) {
                        $tabCategory = 'Sides';
                    } elseif (in_array($category, ['Drinks', 'Beers', 'Cocktails', 'Coffee'])) {
                        $tabCategory = 'Drinks';
                    } elseif ($category === 'Homemade Desserts' || $category === 'Desserts') {
                        $tabCategory = 'Desserts';
                    }
                    
                    // Generate description if empty
                    $description = $item->package_details ?? '';
                    if (empty($description)) {
                        if ($item->name === 'Classic Smash Burger') $description = 'Two smashed beef patties, cheddar cheese, pickles, and our signature KOYA sauce in a toasted brioche bun.';
                        elseif ($item->name === 'Truffle Double Smash') $description = 'Double smashed beef patty, truffle mayo, caramelized onions, and melted Swiss cheese.';
                        elseif ($item->name === 'Tartufata') $description = 'Double smash burger with premium black truffle paste, melted Swiss cheese, caramelized onions, and truffle mayo on a toasted brioche bun.';
                        elseif ($item->name === 'Smash Burger') $description = 'Perfectly seasoned double smash patty with cheddar cheese, crisp lettuce, tomato, pickles, and our signature burger sauce.';
                        elseif ($item->name === 'Red Onion') $description = 'Double smash patty with caramelized red onions, melted cheddar, pickles, and dynamic smoky barbecue sauce.';
                        elseif ($item->name === 'KOYA SALAD') $description = 'Fresh mix of crisp greens, cherry tomatoes, edamame, pickled cabbage, avocado, and ginger sesame dressing.';
                        elseif ($item->name === 'KATSUDON') $description = 'Crispy chicken panko cutlet, simmered eggs, onions, and sweet-savory dashi broth served over steamed rice.';
                        elseif ($item->name === 'SOBOKU') $description = 'A simple, clean-tasting single smash patty with cheddar cheese, yellow mustard, and pickles inside a soft bun.';
                        elseif ($item->name === 'Loaded Nachos') $description = 'Crispy tortilla chips topped with cheese sauce, guacamole, pico de gallo, and jalapeños.';
                        elseif ($item->name === 'Crispy Chicken Bites') $description = 'Tender buttermilk chicken bites served with honey mustard sauce.';
                        elseif ($item->name === 'Spicy Korean Rice Bowl') $description = 'Juicy chicken bites glazed in gochujang sauce, steamed rice, pickled radish, edamame, and sesame.';
                        elseif ($item->name === 'Mediterranean Rice Bowl') $description = 'Grilled salmon or falafel, quinoa, cherry tomatoes, cucumbers, tzatziki sauce, and fresh herbs.';
                        elseif ($item->name === 'House Fries') $description = 'Golden crispy fries seasoned with our house spice blend.';
                        elseif ($item->name === 'Sweet Potato Fries') $description = 'Sweet potato fries served with chipotle garlic mayo.';
                        elseif ($item->name === 'Cheesecake Jar') $description = 'Creamy NY-style cheesecake with red berry coulis in a jar.';
                        elseif ($item->name === 'Chocolate Brownie') $description = 'Warm chocolate brownie served with a scoop of vanilla ice cream.';
                        elseif ($item->name === 'Homemade Lemonade') $description = 'Freshly squeezed lemons with a touch of mint and honey.';
                        elseif ($item->name === 'Local Lager') $description = 'Cold, refreshing local lager.';
                        elseif ($item->name === 'House IPA') $description = 'Cold, craft India Pale Ale with citrus notes.';
                        elseif ($item->name === 'Mojito') $description = 'Classic refreshing cocktail with rum, lime, and fresh mint.';
                        elseif ($item->name === 'Still Water') $description = 'Refreshing mineral water.';
                        elseif ($item->name === 'Espresso') $description = 'Freshly brewed aromatic espresso.';
                        else $description = 'Delicious KOYA recipe prepared with fresh ingredients.';
                    }

                    $badge = null;
                    if ($item->name === 'Classic Smash Burger') $badge = 'KOYA Favorite';
                    elseif ($item->name === 'Truffle Double Smash') $badge = 'Popular';
                    elseif ($item->name === 'Spicy Korean Rice Bowl') $badge = 'New';
                    elseif ($item->name === 'Cheesecake Jar') $badge = 'KOYA Favorite';
                    elseif ($item->name === 'Tartufata') $badge = 'Truffle';
                    elseif ($item->name === 'Smash Burger') $badge = 'Classic';
                    elseif ($item->name === 'Red Onion') $badge = 'Smoky';
                    elseif ($item->name === 'KOYA SALAD') $badge = 'Fresh';
                    elseif ($item->name === 'KATSUDON') $badge = 'Crispy';
                    elseif ($item->name === 'SOBOKU') $badge = 'Simple';
                    
                    $displayItems[] = [
                        'name' => $item->name,
                        'tab_category' => $tabCategory,
                        'price' => $item->price,
                        'description' => $description,
                        'badge' => $badge
                    ];
                }
            } else {
                // Fallbacks if DB is completely empty
                $displayItems = [
                    ['name' => 'Classic Smash Burger', 'tab_category' => 'Smash Burgers', 'price' => 11.90, 'description' => 'Two smashed beef patties, cheddar cheese, pickles, and our signature KOYA sauce in a toasted brioche bun.', 'badge' => 'KOYA Favorite'],
                    ['name' => 'Truffle Double Smash', 'tab_category' => 'Smash Burgers', 'price' => 14.50, 'description' => 'Double smashed beef patty, truffle mayo, caramelized onions, and melted Swiss cheese.', 'badge' => 'Popular'],
                    ['name' => 'Bacon Cheese Smash', 'tab_category' => 'Smash Burgers', 'price' => 12.90, 'description' => 'Double smash patty, crispy bacon, double cheddar, onion jam, and barbecue sauce.', 'badge' => 'Classic'],
                    ['name' => 'Tartufata', 'tab_category' => 'Smash Burgers', 'price' => 13.90, 'description' => 'Double smash burger with premium black truffle paste, melted Swiss cheese, caramelized onions, and truffle mayo on a toasted brioche bun.', 'badge' => 'Truffle'],
                    ['name' => 'Smash Burger', 'tab_category' => 'Smash Burgers', 'price' => 10.90, 'description' => 'Perfectly seasoned double smash patty with cheddar cheese, crisp lettuce, tomato, pickles, and our signature burger sauce.', 'badge' => 'Classic'],
                    ['name' => 'Red Onion', 'tab_category' => 'Smash Burgers', 'price' => 11.50, 'description' => 'Double smash patty with caramelized red onions, melted cheddar, pickles, and dynamic smoky barbecue sauce.', 'badge' => 'Smoky'],
                    ['name' => 'SOBOKU', 'tab_category' => 'Smash Burgers', 'price' => 10.50, 'description' => 'A simple, clean-tasting single smash patty with cheddar cheese, yellow mustard, and pickles inside a soft bun.', 'badge' => 'Simple'],
                    ['name' => 'Spicy Korean Rice Bowl', 'tab_category' => 'BOL', 'price' => 12.80, 'description' => 'Juicy chicken bites glazed in gochujang sauce, steamed rice, pickled radish, edamame, and sesame seeds.', 'badge' => 'New'],
                    ['name' => 'Mediterranean Rice Bowl', 'tab_category' => 'BOL', 'price' => 11.70, 'description' => 'Grilled salmon or falafel, quinoa, cherry tomatoes, cucumbers, tzatziki sauce, and fresh herbs.', 'badge' => 'Fresh'],
                    ['name' => 'Teriyaki Tofu Bowl', 'tab_category' => 'BOL', 'price' => 11.50, 'description' => 'Crispy teriyaki tofu, jasmine rice, broccoli, avocado, ginger, and sesame glaze.', 'badge' => 'Crunchy'],
                    ['name' => 'KOYA SALAD', 'tab_category' => 'BOL', 'price' => 9.80, 'description' => 'Fresh mix of crisp greens, cherry tomatoes, edamame, pickled cabbage, avocado, and ginger sesame dressing.', 'badge' => 'Fresh'],
                    ['name' => 'KATSUDON', 'tab_category' => 'BOL', 'price' => 12.50, 'description' => 'Crispy chicken panko cutlet, simmered eggs, onions, and sweet-savory dashi broth served over steamed rice.', 'badge' => 'Crispy'],
                    ['name' => 'Loaded Nachos', 'tab_category' => 'Sides', 'price' => 8.90, 'description' => 'Crispy tortilla chips topped with cheese sauce, guacamole, pico de gallo, and jalapeños.', 'badge' => 'Shareable'],
                    ['name' => 'Crispy Chicken Bites', 'tab_category' => 'Sides', 'price' => 7.50, 'description' => 'Tender buttermilk chicken bites served with honey mustard sauce.', 'badge' => 'Popular'],
                    ['name' => 'House Fries', 'tab_category' => 'Sides', 'price' => 4.20, 'description' => 'Golden crispy fries seasoned with our house spice blend.', 'badge' => 'Classic'],
                    ['name' => 'Sweet Potato Fries', 'tab_category' => 'Sides', 'price' => 4.90, 'description' => 'Sweet potato fries served with chipotle garlic mayo.', 'badge' => null],
                    ['name' => 'Homemade Lemonade', 'tab_category' => 'Drinks', 'price' => 3.50, 'description' => 'Freshly squeezed lemons with a touch of mint and honey.', 'badge' => 'Refreshing'],
                    ['name' => 'House IPA', 'tab_category' => 'Drinks', 'price' => 4.50, 'description' => 'Cold, craft India Pale Ale with citrus notes.', 'badge' => null],
                    ['name' => 'Mojito', 'tab_category' => 'Drinks', 'price' => 7.90, 'description' => 'Classic refreshing cocktail with rum, lime, and fresh mint.', 'badge' => null],
                    ['name' => 'Still Water', 'tab_category' => 'Drinks', 'price' => 2.00, 'description' => 'Refreshing mineral water.', 'badge' => null],
                    ['name' => 'Cheesecake Jar', 'tab_category' => 'Desserts', 'price' => 5.50, 'description' => 'Creamy NY-style cheesecake with red berry coulis in a jar.', 'badge' => 'KOYA Favorite'],
                    ['name' => 'Chocolate Brownie', 'tab_category' => 'Desserts', 'price' => 5.20, 'description' => 'Warm chocolate brownie served with a scoop of vanilla ice cream.', 'badge' => null],
                ];
            }
            @endphp

            <div class="menu-grid">
                @foreach($displayItems as $item)
                    @php
                    $imageName = $imageMap[$item['name']] ?? null;
                    @endphp
                    <div class="menu-card-simple reveal" x-show="activeTab === '{{ $item['tab_category'] }}'" x-transition>
                        <div style="display: flex; gap: 1rem; align-items: center; justify-content: space-between; width: 100%;">
                            <div style="flex: 1; min-width: 0;">
                                <div class="menu-item-top" style="margin-bottom: 0.25rem;">
                                    <h3 class="menu-item-name" style="margin: 0; line-height: 1.2;">
                                        {{ $item['name'] }}
                                        @if(!empty($item['badge']))
                                            <span class="sticker text-xs ml-2" style="font-size: 0.7rem; padding: 0.15rem 0.4rem; vertical-align: middle; display: inline-block;">{{ $item['badge'] }}</span>
                                        @endif
                                    </h3>
                                </div>
                                <p class="menu-item-description" style="margin-bottom: 0.5rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">{{ $item['description'] }}</p>
                                <span class="menu-item-price" style="font-weight: bold;">{{ number_format($item['price'], 2) }}€</span>
                            </div>
                            @if(!empty($imageName))
                                <div class="menu-item-img-container" style="width: 80px; height: 80px; border-radius: 12px; border: 2px solid var(--color-dark); overflow: hidden; flex-shrink: 0; background-color: var(--color-light); display: flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0px var(--color-dark); transition: transform 0.2s ease;">
                                    <img src="{{ asset('images/' . $imageName) }}" alt="{{ $item['name'] }}" style="width: 85%; height: 85%; object-fit: contain;" onerror="this.style.display='none'">
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>



    <!-- Call to Action (CTA) Section -->
    <section class="cta">
        <div class="container cta-container reveal">
            <h2 class="cta-title">Ready for your next KOYA craving?</h2>
            <div class="cta-btn-wrap">
                <!-- Playful bouncing arrows -->
                <svg class="cta-arrow" width="50" height="30" viewBox="0 0 50 30" fill="none">
                    <path d="M10 5L30 15L10 25" stroke="var(--color-dark)" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25 5L45 15L25 25" stroke="var(--color-dark)" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <a href="{{ route('order.public') }}" class="btn-koya-primary btn-koya-green" style="font-size: 1.5rem; padding: 1.25rem 3rem;">Order Now</a>

                <svg class="cta-arrow-right" width="50" height="30" viewBox="0 0 50 30" fill="none">
                    <path d="M10 5L30 15L10 25" stroke="var(--color-dark)" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25 5L45 15L25 25" stroke="var(--color-dark)" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
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
                    Visit KOYA
                </h3>
                <ul class="contact-info-list">
                    <li class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        </div>
                        <div class="contact-info-text">
                            <h4>Our Address</h4>
                            <p>Carrer de Ramon y Cajal, 45, Gràcia, 08012 Barcelona, Spain</p>
                        </div>
                    </li>
                    <li class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg viewBox="0 0 24 24"><path d="M20 15.5c-1.25 0-2.45-.2-3.57-.57-.35-.11-.74-.03-1.02.24l-2.2 2.2c-2.83-1.44-5.15-3.75-6.59-6.59l2.2-2.2c.28-.27.36-.66.25-1.02C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1z"/></svg>
                        </div>
                        <div class="contact-info-text">
                            <h4>Phone & WhatsApp</h4>
                            <p>+34 931 234 567 / +34 600 999 888</p>
                        </div>
                    </li>
                    <li class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </div>
                        <div class="contact-info-text">
                            <h4>Email Support</h4>
                            <p>hello@koyabcn.es</p>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Opening Hours -->
            <div class="contact-card reveal">
                <h3 class="contact-card-title">
                    <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                    Opening Hours
                </h3>
                <div class="schedule-list">
                    @php
                    $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    @endphp

                    @if($schedule && $schedule->count() > 0)
                        @foreach($schedule as $sched)
                            <div class="schedule-row">
                                <span class="schedule-day">{{ $daysOfWeek[$sched->day_of_week] }}</span>
                                @if($sched->is_closed)
                                    <span class="schedule-hours closed">Closed</span>
                                @else
                                    <span class="schedule-hours">
                                        {{ substr($sched->open_time_1, 0, 5) }} - {{ substr($sched->close_time_1, 0, 5) }}
                                        @if($sched->open_time_2)
                                            &amp; {{ substr($sched->open_time_2, 0, 5) }} - {{ substr($sched->close_time_2, 0, 5) }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <!-- Fallback schedule -->
                        <div class="schedule-row">
                            <span class="schedule-day">Monday</span>
                            <span class="schedule-hours closed">Closed</span>
                        </div>
                        @foreach(['Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <div class="schedule-row">
                                <span class="schedule-day">{{ $day }}</span>
                                <span class="schedule-hours">12:00 - 16:00 &amp; 19:00 - 23:30</span>
                            </div>
                        @endforeach
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
                    <h3 class="footer-logo-title">KOYA</h3>
                    <p class="footer-p">
                        Authentic Smash Burgers with crispy borders and bold Rice Bowls loaded with premium ingredients. Experience street food with attitude.
                    </p>
                    <div class="social-icons">
                        <a href="#" class="social-icon" aria-label="Instagram">
                            <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="#" class="social-icon" aria-label="Facebook">
                            <svg viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/></svg>
                        </a>
                        <a href="#" class="social-icon" aria-label="TikTok">
                            <svg viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.86-.74-3.99-1.72-.08-.07-.17-.14-.24-.22v6.52c-.05 2.12-.87 4.25-2.48 5.61-1.61 1.35-3.85 1.83-5.91 1.44-2.07-.39-3.96-1.73-4.99-3.56-1.03-1.84-1.12-4.16-.32-6.05.8-1.89 2.53-3.38 4.54-3.87 1.05-.26 2.15-.22 3.19.12v4.07c-.89-.35-1.91-.32-2.76.17-.86.49-1.44 1.4-1.57 2.38-.13.98.2 2.01.88 2.73.68.72 1.7 1.07 2.68.96.98-.11 1.83-.78 2.22-1.68.27-.63.34-1.32.33-2V.02z"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="footer-nav-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="#hero">Home</a></li>
                        <li><a href="#menu-section">Menu</a></li>
                        <li><a href="#contact">Location & Hours</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-nav-title">Services</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('order.public') }}">Takeaway & Delivery</a></li>
                        <li><a href="{{ route('reservations.public.create') }}">Book a Table</a></li>
                        <li><a href="{{ route('login') }}">Staff Login</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2026 KOYA BCN. All rights reserved.</p>
                <p>Designed with attitude 🤘</p>
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
            const heroImg = document.querySelector('.hero-main-img');
            if (heroImg) {
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
                    heroImg.style.opacity = '0';
                    heroImg.style.transform = 'scale(0.9) rotate(-2deg)';
                    setTimeout(() => {
                        currentImgIndex = (currentImgIndex + 1) % heroImages.length;
                        // Preload the next image to avoid flickering
                        const tempImg = new Image();
                        tempImg.src = heroImages[currentImgIndex];
                        tempImg.onload = () => {
                            heroImg.src = heroImages[currentImgIndex];
                            heroImg.style.opacity = '1';
                            heroImg.style.transform = 'scale(1) rotate(0deg)';
                        };
                    }, 400); // Wait for opacity/scale transition
                }, 3500); // Cycle every 3.5 seconds
            }
        });
    </script>
</body>
</html>


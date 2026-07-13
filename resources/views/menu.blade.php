<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo', ['seo' => $seo ?? []])
    <link rel="icon" type="image/png" href="https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
            --border-width: 4px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--color-green);
            color: var(--color-dark);
            overflow-x: hidden;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .menu-page-shell {
            min-height: 100vh;
            padding: 3.5rem 0 5rem;
            background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1.3px, transparent 1.3px);
            background-size: 18px 18px;
        }

        .btn-koya-primary,
        .btn-koya-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Biski', sans-serif;
            font-size: 0.95rem;
            padding: 0.8rem 1.3rem;
            border: 3px solid var(--color-dark);
            border-radius: 9999px;
            box-shadow: 4px 4px 0px var(--color-dark);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-koya-primary {
            background-color: var(--color-yellow);
            color: var(--color-dark);
        }

        .btn-koya-secondary {
            background-color: var(--color-light);
            color: var(--color-dark);
        }

        .btn-koya-primary:hover,
        .btn-koya-secondary:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px var(--color-dark);
        }

        .btn-koya-primary:active,
        .btn-koya-secondary:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px var(--color-dark);
        }

        .section-header {
            text-align: center;
            margin-bottom: 2.75rem;
            position: relative;
        }

        .section-title {
            font-family: var(--font-headline);
            font-size: 3.5rem;
            color: white;
            text-transform: uppercase;
            line-height: 1;
            display: inline-block;
            position: relative;
            text-shadow: 3px 3px 0px var(--color-dark);
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

        .menu-section {
            padding: 0;
            background-color: transparent;
            color: white;
            position: relative;
        }

        .menu-section::before {
            content: none;
        }

        .menu-tabs-container {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
            margin-bottom: 1rem;
        }

        .menu-tabs {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            background-color: rgba(5, 95, 79, 0.95);
            padding: 0.6rem;
            border-radius: 9999px;
            border: 3px solid var(--color-dark);
        }

        .menu-tab {
            font-family: 'Biski', sans-serif;
            font-size: 0.82rem;
            padding: 0.8rem 1.6rem;
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
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
            position: relative;
            z-index: 1;
            max-width: 980px;
            margin: 0 auto;
        }

        .menu-card-simple {
            background-color: white;
            border: 3px solid var(--color-dark);
            border-radius: 18px;
            padding: 0.95rem 1rem;
            color: var(--color-dark);
            box-shadow: 4px 4px 0px var(--color-dark);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .menu-card-simple:hover {
            transform: translate(-5px, -5px);
            box-shadow: 7px 7px 0px var(--color-dark);
            background-color: #fffef9;
            border-color: var(--color-green);
        }

        .menu-item-inner {
            display: flex;
            gap: 0.6rem;
            align-items: center;
            justify-content: space-between;
        }

        .menu-item-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .menu-item-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.35rem;
        }

        .menu-item-name {
            font-family: 'Biski', sans-serif;
            font-size: 0.82rem;
            color: var(--color-dark);
            text-transform: uppercase;
            line-height: 1.2;
            flex: 1;
        }

        .menu-card-simple:hover .menu-item-name {
            color: var(--color-green);
        }

        .menu-item-description {
            font-size: 0.7rem;
            color: #555;
            line-height: 1.35;
        }

        .menu-item-price {
            font-family: 'Biski_2', sans-serif;
            font-size: 0.82rem;
            color: var(--color-dark);
            background-color: var(--color-yellow);
            border: 1.5px solid var(--color-dark);
            padding: 0.1rem 0.5rem;
            border-radius: 9999px;
            box-shadow: 1.5px 1.5px 0px var(--color-dark);
            font-weight: 800;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .menu-item-img-container {
            width: 72px;
            height: 72px;
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
            z-index: 1;
            align-items: start;
            max-width: 980px;
            margin-left: auto;
            margin-right: auto;
        }

        .drinks-showcase,
        .sides-showcase,
        .extra-notice-card,
        .empty-state {
            background-color: var(--color-yellow);
            border: 3px solid var(--color-dark);
            border-radius: 28px;
            box-shadow: 7px 7px 0px var(--color-dark);
            padding: 1.6rem 1.4rem;
            color: var(--color-dark);
            position: relative;
            z-index: 1;
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

        .drinks-showcase-grid,
        .sides-showcase-grid {
            display: grid;
            gap: 0.6rem;
        }

        .drinks-showcase-item,
        .sides-showcase-item {
            padding-bottom: 0.5rem;
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
            gap: 0.6rem;
        }

        .drinks-showcase-name,
        .sides-showcase-name {
            font-family: 'Biski', sans-serif;
            font-size: 1.15rem;
            color: var(--color-green);
            line-height: 1.2;
            text-transform: uppercase;
        }

        .drinks-showcase-price,
        .sides-showcase-price {
            font-family: 'Biski_2', sans-serif;
            font-size: 1rem;
            color: #d92c2c;
            flex-shrink: 0;
        }

        .drinks-showcase-description,
        .sides-showcase-description {
            font-size: 0.85rem;
            color: var(--color-dark);
            line-height: 1.45;
            margin-top: 0.35rem;
        }

        .sides-showcase {
            margin-top: 3rem;
            max-width: 520px;
            margin-left: auto;
            margin-right: auto;
        }

        .extra-notice-card {
            margin-top: 2.5rem;
            display: inline-block;
            width: 100%;
            max-width: 520px;
        }

        .allergy-disclaimer {
            text-align: center;
            margin-top: 2.25rem;
            padding: 0 1rem;
            position: relative;
            z-index: 1;
        }

        .allergy-disclaimer-text {
            color: rgba(255, 255, 255, 0.68);
            font-style: italic;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .empty-state {
            text-align: center;
            max-width: 720px;
            margin: 0 auto;
        }

        [x-cloak] {
            display: none !important;
        }

        @media (max-width: 992px) {
            .drinks-highlight-grid {
                grid-template-columns: 1fr;
            }

            .drinks-highlight-grid .drinks-showcase:first-child {
                grid-column: auto;
            }
        }

        @media (min-width: 993px) {
            .drinks-highlight-grid .drinks-showcase:first-child {
                grid-column: span 2;
            }
        }

        @media (min-width: 768px) {
            .drinks-showcase-grid.cols-2 {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.6rem 1.5rem;
            }
        }
        @media (max-width: 768px) {
            .section-title {
                font-size: 2.8rem;
            }

            .menu-tabs {
                border-radius: 2rem;
                padding: 0.8rem;
                gap: 0.6rem;
            }

            .menu-tab {
                padding: 0.6rem 1.2rem;
                font-size: 0.95rem;
            }

            .menu-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
        @media (max-width: 480px) {
            .container {
                padding: 0 1rem;
            }

            .menu-item-inner {
                align-items: flex-start;
            }

            .menu-item-img-container {
                width: 64px;
                height: 64px;
            }
        }
    </style>
</head>
<body>    @php
        $normalizeMenuItemKey = function (?string $value): string {
            return Illuminate\Support\Str::upper(Illuminate\Support\Str::ascii((string) $value));
        };

        $menuTranslations = collect(trans('public.menu_items', [], app()->getLocale()))
            ->mapWithKeys(fn ($translation, $key) => [$normalizeMenuItemKey((string) $key) => $translation])
            ->all();

        $translateMenuItem = function (array $item) use ($menuTranslations, $normalizeMenuItemKey): array {
            $normalizedName = $normalizeMenuItemKey((string) ($item['name'] ?? ''));
            $translation = $menuTranslations[$normalizedName] ?? null;

            if (! is_array($translation) && $normalizedName !== '') {
                $closestKey = collect(array_keys($menuTranslations))
                    ->first(fn (string $key) => levenshtein($key, $normalizedName) <= 1);

                if (is_string($closestKey)) {
                    $translation = $menuTranslations[$closestKey] ?? null;
                }
            }

            if (is_array($translation)) {
                $item['name'] = $translation['name'] ?? ($item['name'] ?? '');
                $item['description'] = $translation['description'] ?? ($item['description'] ?? '');
            }

            return $item;
        };

        $displayItems = collect($menuItemsFlat ?? [])->map(function (array $item) {
            $category = (string) ($item['category'] ?? '');
            $tabCategory = 'Starters';

            if (in_array($category, ['Smash Burger', 'Smash Burgers'], true)) {
                $tabCategory = 'Smash Burgers';
            } elseif (in_array($category, ['Bol de Arroz', 'Rice Bowls', 'BOL'], true)) {
                $tabCategory = 'BOL';
            } elseif (in_array($category, ['Entrantes', 'Starters'], true)) {
                $tabCategory = 'Starters';
            } elseif (stripos($category, 'acomp') !== false || in_array(strtolower(trim($category)), ['sides', 'fries'], true)) {
                $tabCategory = 'Fries';
            } elseif (in_array($category, ['Bebidas', 'Cervezas', 'Cocteles', 'Cafes', 'Drinks', 'Beers', 'Cocktails', 'Coffee'], true)) {
                $tabCategory = 'Drinks';
            } elseif (in_array($category, ['Postres', 'Postres de la Casa', 'Homemade Desserts', 'Desserts'], true)) {
                $tabCategory = 'Desserts';
            }

            return [
                'name' => $item['name'] ?? '',
                'category' => $category,
                'tab_category' => $tabCategory,
                'price' => (float) ($item['price'] ?? 0),
                'description' => $item['description'] ?? '',
                'image_url' => $item['image_url'] ?? null,
                'image_thumbnail_url' => $item['image_thumbnail_url'] ?? null,
            ];
        })->map($translateMenuItem)->values();

        $scaledMenuImages = [
            'ADOBO',
            'KIMCHI BURGER',
            'TARTUFATA',
            'CANGRE BURGER',
            "GYOZAS FRITAS IBÉRICAS (5 uds.)",
            "GYOZAS FRITAS IBÉRICAS (5 uds.)",
            'EDAMAME AL VAPOR',
            'SWEET & SPICY WINGS',
            'EBI FRY (5 uds.)',
            'ROLLITOS (5 uds.)',
            'TACOS DE CARILLADA DE TERNERA',
        ];

        $sideItems = $displayItems->where('tab_category', 'Fries')->unique('name')->values();
        $drinks = $displayItems->where('tab_category', 'Drinks');
        $drinkCoffeeItems = $drinks->where('category', 'Cafes')->values();
        $drinkCocktailItems = $drinks->where('category', 'Cocteles')->values();

        $drinkBeerItems = $drinks->filter(function ($item) {
            return ($item['category'] ?? '') === 'Cervezas' || preg_match('/(cerveza|clara|pinta)/i', $item['name']);
        })->map($translateMenuItem)->values();

        $drinkWineItems = $drinks->filter(function ($item) {
            return preg_match('/(vino|sangria|vermut|tinto)/i', $item['name']);
        })->map($translateMenuItem)->values();

        $usedDrinkNames = $drinkCoffeeItems->pluck('name')
            ->merge($drinkCocktailItems->pluck('name'))
            ->merge($drinkBeerItems->pluck('name'))
            ->merge($drinkWineItems->pluck('name'))
            ->unique()
            ->toArray();

        $drinkSoftItems = $drinks->filter(function ($item) use ($usedDrinkNames) {
            return ! in_array($item['name'], $usedDrinkNames, true);
        })->map($translateMenuItem)->values();
    @endphp    <div class="menu-page-shell">
    <section id="menu-section" class="menu-section" x-data="{ activeTab: 'Starters' }">
        <div class="container">
            <div class="section-header">
                <h1 class="section-title">{{ __('public.homepage_menu_title') }}</h1>
            </div>
            <div class="menu-tabs-container">
                <div class="menu-tabs">
                    <button class="menu-tab" :class="activeTab === 'Starters' ? 'active' : ''" @click="activeTab = 'Starters'">{{ __('public.menu_tab_starters') }}</button>
                    <button class="menu-tab" :class="activeTab === 'Smash Burgers' ? 'active' : ''" @click="activeTab = 'Smash Burgers'">SMASH BURGER</button>
                    <button class="menu-tab" :class="activeTab === 'BOL' ? 'active' : ''" @click="activeTab = 'BOL'">BOL (Bowls)</button>
                    <button class="menu-tab" :class="activeTab === 'Drinks' ? 'active' : ''" @click="activeTab = 'Drinks'">{{ __('public.menu_tab_drinks') }}</button>
                    <button class="menu-tab" :class="activeTab === 'Desserts' ? 'active' : ''" @click="activeTab = 'Desserts'">{{ __('public.menu_tab_desserts') }}</button>
                </div>
            </div>

            @if($displayItems->isEmpty())
                <div class="empty-state">
                    <h2 style="font-family: var(--font-headline); font-size: 2rem; text-transform: uppercase; margin-bottom: 0.75rem;">Menu coming soon</h2>
                    <p style="line-height: 1.5;">No active menu items are available right now.</p>
                </div>
            @else

                <div class="menu-card-simple" x-show="activeTab === 'Smash Burgers'" x-cloak>
                    <div class="menu-item-inner">
                        <div class="menu-item-info">
                            <div class="menu-item-top">
                                <h3 class="menu-item-name">{!! __('public.burger_bun_availability') !!}</h3>
                                <span class="menu-item-price">2&euro;</span>
                            </div>
                            <p class="menu-item-description">{!! __('public.burger_bun_supplement') !!}</p>
                        </div>
                    </div>
                </div>

                <div class="menu-grid">
                    @foreach($displayItems as $item)
                        @php
                            $skipStandardDrinkCard = $item['tab_category'] === 'Drinks';
                            $imageUrl = $item['image_thumbnail_url'] ?? $item['image_url'];
                        @endphp
                        @if(! $skipStandardDrinkCard)
                            <div class="menu-card-simple" x-show="activeTab === '{{ $item['tab_category'] }}'" x-cloak>
                                <div class="menu-item-inner">
                                    <div class="menu-item-info">
                                        <div class="menu-item-top">
                                            <h3 class="menu-item-name">{!! preg_replace('/(\(\d+\s*uds\.?\))/i', '<span style="font-size: 0.75em; opacity: 0.85; font-weight: normal; text-transform: none; display: inline-block; margin-left: 4px;">$1</span>', e($item['name'])) !!}</h3>
                                            <span class="menu-item-price">{{ number_format($item['price'], 2) }}&euro;</span>
                                        </div>
                                        @if(!empty($item['description']))
                                            <p class="menu-item-description">{{ $item['description'] }}</p>
                                        @endif
                                    </div>

                                    @if(!empty($imageUrl))
                                        <div class="menu-item-img-container">
                                            <img src="{{ $imageUrl }}" alt="{{ $item['name'] }}" loading="lazy" decoding="async" width="100" height="100" @if(in_array($item['name'], $scaledMenuImages, true)) style="transform: scale(1.1);" @endif>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="drinks-highlight-grid" x-show="activeTab === 'Drinks'" x-cloak>
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

                    <div class="drinks-showcase">
                        <div class="drinks-showcase-title drinks-showcase-title-image">
                            <img src="{{ asset('images/cafe.png') }}" alt="{{ __('public.seo_drinks_coffee_alt') }}" width="72" height="72">
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

                    <div class="drinks-showcase">
                        <div class="drinks-showcase-title drinks-showcase-title-image">
                            <img src="{{ asset('images/Picture7.png') }}" alt="{{ __('public.seo_drinks_cocktail_alt') }}" width="72" height="72">
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

                    <div class="drinks-showcase">
                        <div class="drinks-showcase-title">Vinos y Sangrias</div>
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
                <div class="sides-showcase" x-show="activeTab === 'Starters' && {{ $sideItems->isNotEmpty() ? 'true' : 'false' }}" x-cloak>
                    <div class="sides-showcase-title sides-showcase-title-image">
                        <img src="{{ asset('images/fritas.png') }}" alt="{{ __('public.seo_sides_alt') }}" width="72" height="72">
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

                <div class="allergy-disclaimer">
                    <p class="allergy-disclaimer-text">{{ __('public.allergy_staff_notice') }}</p>
                </div>
            @endif
        </div>
    </section>
    </div>
</body>
</html>






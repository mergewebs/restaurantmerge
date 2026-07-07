<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('public.guest_title') }}</title>
        <link rel="icon" type="image/png" href="https://grandrestaurantv7-0.b-cdn.net/bistro/wp-content/uploads/sites/3/2024/08/logo-white.png">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            koya: {
                                DEFAULT: '#c89b3c',
                                dark: '#17110c',
                                gray: '#211915',
                            }
                        },
                        fontFamily: { sans: ['Outfit', 'sans-serif'] }
                    }
                }
            }
        </script>
        @include('layouts.restaurant-theme')
    </head>
    <body class="font-sans antialiased text-gray-100">
        <div class="min-h-screen flex flex-col items-center justify-center p-6">
            <div class="mb-8 flex w-full max-w-sm items-center justify-center gap-4">
                <x-application-logo class="w-44 h-auto" />
            </div>

            <div class="w-full max-w-sm glass p-8 rounded-[24px] border border-white/10 shadow-2xl overflow-hidden">
                {{ $slot }}
            </div>

            <div class="mt-8 text-center">
                <p class="text-gray-600 text-[10px] uppercase font-bold tracking-widest">{{ __('public.copyright') }}</p>
            </div>
        </div>
    </body>
</html>

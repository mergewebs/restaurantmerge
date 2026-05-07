<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Koya Smash Burger & Bol</title>
        <link rel="icon" type="image/png" href="https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">

        <!-- Tailwind CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            koya: {
                                DEFAULT: '#FF5C00',
                                dark: '#121212',
                                gray: '#1E1E1E',
                            }
                        },
                        fontFamily: { sans: ['Outfit', 'sans-serif'] }
                    }
                }
            }
        </script>
        <style>
            body { background-color: #121212; color: #F5F5F5; }
            .glass { background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(10px); }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-100">
        <div class="min-h-screen flex flex-col items-center justify-center p-6 bg-gradient-to-b from-koya/10 to-koya-dark">
             <div class="mb-8 flex flex-col items-center">
                 <x-application-logo class="w-28 h-auto" />
             </div>

            <div class="w-full max-w-sm glass p-8 rounded-[40px] border border-white/10 shadow-2xl overflow-hidden">
                {{ $slot }}
            </div>

            <div class="mt-8 text-center">
                <p class="text-gray-600 text-[10px] uppercase font-bold tracking-widest">© 2026 Koya Smash Burger & Bol</p>
            </div>
        </div>
    </body>
</html>

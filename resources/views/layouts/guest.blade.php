<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @include('partials.seo', ['seo' => \App\Support\Seo::noIndex(['title' => __('public.seo_login_title')])])
        @include('partials.favicon')

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
        @include('layouts.admin-theme')
        @include('partials.toast-notifications')
        <style>
            body {
                background-color: #f8fafc !important;
                background-image: 
                    radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.05) 0px, transparent 50%),
                    radial-gradient(at 100% 0%, rgba(200, 155, 60, 0.08) 0px, transparent 50%) !important;
                color: #0f172a !important;
                font-family: 'Outfit', sans-serif !important;
                display: flex !important;
                flex-direction: column !important;
                min-height: 100vh !important;
            }

            /* Card Styling Override */
            .glass {
                background: #ffffff !important;
                border: 1px solid #e2e8f0 !important;
                box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.05) !important;
                border-radius: 20px !important;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
                animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(16px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Application Logo styling */
            img[alt="KOYA Logo"] {
                border: 1px solid #e2e8f0 !important;
                box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04) !important;
                border-radius: 16px !important;
                background-color: #ffffff !important;
                width: 6.5rem !important; /* w-26 */
                height: 6.5rem !important;
                object-fit: cover !important;
                transition: transform 0.2s ease !important;
            }
            img[alt="KOYA Logo"]:hover {
                transform: scale(1.02) !important;
            }

            /* Titles */
            h2 {
                color: #0f172a !important;
                font-family: 'Outfit', sans-serif !important;
                font-weight: 700 !important;
                letter-spacing: -0.02em !important;
                margin-bottom: 0.25rem !important;
            }

            /* Labels */
            label {
                color: #475569 !important;
                font-family: 'Outfit', sans-serif !important;
                font-weight: 600 !important;
                font-size: 0.75rem !important;
                letter-spacing: 0.05em !important;
                text-transform: uppercase !important;
                margin-bottom: 0.375rem !important;
                display: block !important;
            }

            /* Remember Me wrapper overrides */
            label[for="remember_me"] {
                display: inline-flex !important;
                align-items: center !important;
                text-transform: none !important;
                font-weight: 500 !important;
                font-size: 0.875rem !important;
                color: #475569 !important;
                cursor: pointer !important;
                margin-bottom: 0 !important;
                letter-spacing: normal !important;
            }
            label[for="remember_me"] span {
                color: #475569 !important;
                font-family: 'Outfit', sans-serif !important;
                font-weight: 500 !important;
            }

            /* Input Fields styling */
            input[type="email"], input[type="password"], input[type="text"] {
                min-height: 3.125rem !important;
                background: #ffffff !important;
                border: 1px solid #cbd5e1 !important;
                border-radius: 12px !important;
                color: #0f172a !important;
                padding: 0 1rem !important;
                font-size: 1rem !important;
                font-weight: 500 !important;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
                box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02) !important;
                outline: none !important;
                width: 100% !important;
            }
            input[type="email"]:focus, input[type="password"]:focus, input[type="text"]:focus {
                border-color: #4f46e5 !important;
                box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12) !important;
            }

            /* Custom Checkbox style */
            input[type="checkbox"] {
                width: 1.15rem !important;
                height: 1.15rem !important;
                min-height: 1.15rem !important;
                background: #ffffff !important;
                border: 1px solid #cbd5e1 !important;
                border-radius: 4px !important;
                color: #4f46e5 !important;
                cursor: pointer !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin-right: 0.5rem !important;
                outline: none !important;
            }
            input[type="checkbox"]:focus {
                box-shadow: none !important;
                border-color: #4f46e5 !important;
            }

            /* Primary Button style overrides */
            button[type="submit"], .btn-koya {
                background-color: #4f46e5 !important; /* Indigo primary */
                color: #ffffff !important;
                border: none !important;
                border-radius: 12px !important;
                font-family: 'Outfit', sans-serif !important;
                font-size: 1rem !important;
                font-weight: 600 !important;
                text-transform: none !important;
                letter-spacing: normal !important;
                padding: 0.875rem 1.5rem !important;
                box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2), 0 2px 4px -2px rgba(79, 70, 229, 0.2) !important;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
                cursor: pointer !important;
                width: 100% !important;
                min-height: 3.125rem !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            button[type="submit"]:hover, .btn-koya:hover {
                background-color: #4338ca !important;
                transform: translateY(-1px) !important;
                box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3), 0 4px 6px -4px rgba(79, 70, 229, 0.3) !important;
            }
            button[type="submit"]:active, .btn-koya:active {
                transform: translateY(0) !important;
            }

            /* Links */
            a {
                color: #4f46e5 !important;
                font-weight: 600 !important;
                text-decoration: none !important;
                transition: all 0.2s ease !important;
                font-family: 'Outfit', sans-serif !important;
            }
            a:hover {
                color: #4338ca !important;
                text-decoration: underline !important;
            }

            /* Error validation styling */
            .text-red-400, .text-red-600, ul.text-red-400, ul.mt-2 {
                color: #dc2626 !important;
                font-weight: 600 !important;
                font-size: 0.85rem !important;
                list-style-type: none !important;
                padding-left: 0 !important;
            }

            /* Text elements override */
            .text-gray-500, p.text-gray-500 {
                color: #64748b !important;
                font-weight: 500 !important;
            }

            /* Copyright Footer */
            .text-gray-600 {
                color: #64748b !important;
                opacity: 0.8 !important;
                font-weight: 600 !important;
                letter-spacing: 0.05em !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-100">
        <div class="min-h-screen flex flex-col items-center justify-center p-4 sm:p-6">
            <div class="mb-6 flex w-full max-w-sm items-center justify-center gap-4">
                <x-application-logo class="w-28 h-auto rounded-2xl" />
            </div>

            <div class="w-full max-w-sm glass p-6 sm:p-8 rounded-[24px] border border-white/10 shadow-2xl overflow-hidden">
                {{ $slot }}
            </div>

            <div class="mt-8 text-center">
                <p class="text-gray-600 text-[10px] uppercase font-bold tracking-widest">{{ __('public.copyright', ['app_name' => config('app.name')]) }}</p>
            </div>
        </div>
        @include('partials.form-submit-lock')
</body>
</html>


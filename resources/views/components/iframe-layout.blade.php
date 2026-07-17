<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('public.iframe_title') }}</title>
    @include('partials.favicon')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        koya: '#c89b3c',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        display: ['Cormorant Garamond', 'serif'],
                        biski: ['Biski_4', 'sans-serif'],
                    },
                    animation: {
                        'bounce-short': 'bounce-short 0.6s ease-in-out 3',
                    },
                    keyframes: {
                        'bounce-short': {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        @font-face {
            font-family: 'Biski';
            src: url('{{ asset('fonts/Biski.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Biski_4';
            src: url('{{ asset('fonts/Biski_4.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            background-color: transparent;
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            color: #fff7eb;
        }

        input, select, textarea {
            width: 100% !important;
            padding: 0.875rem 1rem !important;
            border: 1px solid rgba(244, 222, 183, 0.22) !important;
            border-radius: 12px !important;
            font-size: 1rem !important;
            color: #fff7eb !important;
            background-color: rgba(35, 27, 21, 0.72) !important;
            font-family: 'Outfit', sans-serif !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #d9b45f !important;
            background-color: rgba(35, 27, 21, 0.88) !important;
            outline: none !important;
            box-shadow: 0 0 0 4px rgba(217, 180, 95, 0.14) !important;
            transform: translateY(-1px);
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 700;
            color: rgba(255, 247, 235, 0.72);
            font-size: 0.8125rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-family: 'Outfit', sans-serif !important;
        }

        .btn-reservation {
            background: linear-gradient(135deg, #d9b45f 0%, #b98627 100%) !important;
            color: #17110c !important;
            font-family: 'Biski', sans-serif !important;
            padding: 1.125rem 2rem;
            border-radius: 12px;
            font-weight: 800;
            width: 100%;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .btn-reservation:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -5px rgba(0, 0, 0, 0.08);
            filter: brightness(1.1);
        }

        .btn-reservation:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-reservation:disabled {
            background-color: #c89b3c !important;
            color: #17110c !important;
            box-shadow: none;
            cursor: not-allowed;
            opacity: 0.5;
        }

        [x-cloak] { display: none !important; }
    </style>
    @include('partials.toast-notifications')
</head>
<body class="antialiased">
    <div id="iframe-wrapper" class="w-full">
        <div class="flex justify-end pb-3">
            <x-language-switcher theme="dark" />
        </div>
        {{ $slot }}
    </div>

    <script>
        let lastHeight = 0;
        function sendHeight() {
            const wrapper = document.getElementById('iframe-wrapper');
            if (!wrapper) return;

            const height = wrapper.offsetHeight;

            if (height > 0 && Math.abs(height - lastHeight) > 5) {
                lastHeight = height;
                window.parent.postMessage({
                    height: height,
                    frameHeight: height,
                    type: 'resize'
                }, '*');
            }
        }

        window.addEventListener('load', () => setTimeout(sendHeight, 100));
        window.addEventListener('resize', sendHeight);

        const observer = new MutationObserver(() => {
            sendHeight();
        });
        observer.observe(document.body, { attributes: true, childList: true, subtree: true });
    </script>
    @include('partials.form-submit-lock')
</body>
</html>

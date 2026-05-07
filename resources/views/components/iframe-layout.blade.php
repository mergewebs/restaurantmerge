<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Reservation - Sample Restaurant</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

    <!-- Scripts -->
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

        /* WordPress Style Inputs */
        input, select, textarea {
            width: 100% !important;
            padding: 0.875rem 1rem !important;
            border: 1px solid rgba(244, 222, 183, 0.22) !important;
            border-radius: 12px !important;
            font-size: 0.96rem !important;
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
            font-family: 'Outfit', sans-serif !important;
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

        .time-slot {
            padding: 0.75rem 0.5rem;
            border: 1.5px solid #f3f4f6;
            border-radius: 12px;
            text-align: center;
            font-weight: 700;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            background: #f9fafb;
            color: #4b5563;
        }

        .time-slot:hover {
            border-color: #d9b45f;
            color: #d9b45f;
            background: #fff;
            transform: scale(1.02);
        }

        .time-slot.selected {
            background-color: #c89b3c;
            color: white;
            border-color: #c89b3c;
            box-shadow: 0 4px 6px -1px rgba(255, 197, 71, 0.2);
        }

        [x-cloak] { display: none !important; }

        /* Custom scrollbar for time slots */
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f3f4f6; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #ffc547; }

        /* Flatpickr Brand Customization */
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, 
        .flatpickr-day.selected.hover, .flatpickr-day.startRange.hover, .flatpickr-day.endRange.hover, 
        .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, 
        .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover {
            background: #c89b3c !important;
            border-color: #c89b3c !important;
            color: #17110c !important;
        }
        .flatpickr-current-month, .flatpickr-monthDropdown-months, .cur-month, .cur-year, .flatpickr-weekday {
            color: #17110c !important;
            fill: #17110c !important;
            font-weight: 700 !important;
        }
        .flatpickr-months {
            background: #c89b3c !important;
            border-radius: 12px 12px 0 0 !important;
            padding: 10px 0 !important;
        }
        .flatpickr-calendar {
            background: #ffffff !important;
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.2) !important;
            border-radius: 14px !important;
            border: none !important;
            padding-bottom: 5px !important;
            width: 315px !important;
        }
        .flatpickr-innerContainer {
            padding: 10px !important;
        }
        /* Reset global input/select styles for flatpickr internal elements */
        .flatpickr-calendar input, .flatpickr-calendar select {
            width: auto !important;
            padding: 0 !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            background: transparent !important;
            font-size: 1rem !important;
            height: auto !important;
        }
        .flatpickr-monthDropdown-months {
            padding: 0 5px !important;
            margin: 0 !important;
            display: inline-block !important;
            cursor: pointer !important;
        }
        .flatpickr-current-month {
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: auto !important;
        }
        .flatpickr-months .flatpickr-prev-month, .flatpickr-months .flatpickr-next-month {
            height: 40px !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 10px !important;
        }
        .flatpickr-months .flatpickr-prev-month svg, .flatpickr-months .flatpickr-next-month svg {
            fill: #17110c !important;
        }
    </style>
</head>
<body class="antialiased">
    <div id="iframe-wrapper" class="w-full">
        {{ $slot }}
    </div>

    <script>
        let lastHeight = 0;
        function sendHeight() {
            // Use offsetHeight of the wrapper div for more stability
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
</body>
</html>

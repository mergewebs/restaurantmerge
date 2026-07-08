<style>
    :root {
        --koya: #4f46e5; /* Standard brand Indigo */
        --koya-hover: #4338ca;
        --koya-light: rgba(79, 70, 229, 0.08);
        --bg: #f8fafc; /* Slate 50 */
        --white: #ffffff;
        --white-muted: #64748b;
        --border-color: #e2e8f0; /* Slate 200 */
        --text-primary: #0f172a; /* Slate 900 */
        --text-secondary: #334155; /* Slate 700 */
        --text-muted: #64748b; /* Slate 500 */
    }

    html {
        background-color: var(--bg) !important;
    }

    body { 
        background-color: var(--bg) !important; 
        background-image: none !important; /* Discard dark gradients */
        color: var(--text-secondary) !important; 
        -webkit-tap-highlight-color: transparent; 
        font-family: 'Outfit', sans-serif !important;
    }

    body::before {
        display: none !important; /* Hide overlay grids */
    }

    .bg-glow-container {
        display: none !important; /* Hide dark blobs */
    }

    /* Card system / Glass override */
    .glass, [class*="glass"] { 
        background: #ffffff !important; 
        backdrop-filter: none !important; 
        -webkit-backdrop-filter: none !important; 
        border: 1px solid var(--border-color) !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05), 0 10px 15px -3px rgba(0, 0, 0, 0.02) !important;
        color: var(--text-secondary) !important;
        border-radius: 16px !important;
    }

    /* Custom scrollbars */
    .custom-scrollbar::-webkit-scrollbar,
    .kitchen-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track,
    .kitchen-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb,
    .kitchen-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    /* Header override for light theme dashboard */
    main h2.font-display {
        color: var(--text-primary) !important;
    }

    /* Typography styling */
    h1, h2, h3, h4, h5, h6, .text-white {
        color: var(--text-primary) !important;
    }

    /* Override Tailwind text color classes to map to clean light theme readability */
    .text-slate-900, .text-gray-900, .text-gray-800, .text-gray-700 {
        color: var(--text-primary) !important;
    }

    .text-gray-400, .text-gray-500, .text-gray-300, .text-slate-400, .text-slate-500, .text-slate-300, .text-white\/50, .text-white\/60, .text-white\/70, .text-white\/80, .text-white\/90 {
        color: var(--text-muted) !important;
    }

    .text-white\/85 {
        color: var(--text-secondary) !important;
    }

    .text-white\/65 {
        color: var(--text-muted) !important;
    }
    a.text-white\/65:hover {
        color: var(--text-primary) !important;
    }
    .text-white\/30, .text-white\/40 {
        color: var(--text-muted) !important;
    }

    .text-gray-600 {
        color: var(--text-secondary) !important;
    }

    /* High contrast amber overrides for notes and status elements */
    .text-amber-400, .text-amber-500, .text-amber-600, .text-yellow-400, .text-yellow-500, [class*="text-amber-"], [class*="text-yellow-"] {
        color: #b45309 !important; /* dark readable amber */
    }
    .bg-amber-500\/5, .bg-amber-500\/10, .bg-amber-500\/20, [class*="bg-amber-"] {
        background-color: #fffbeb !important;
        border-color: #fde68a !important;
    }

    /* Input elements */
    input, select, textarea {
        min-height: 2.85rem;
        background: #ffffff !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 12px !important;
        color: var(--text-primary) !important;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.02) !important;
        padding: 0.75rem 1rem !important;
        width: 100% !important;
        outline: none !important;
        transition: all 0.2s ease-in-out;
    }

    input::placeholder, textarea::placeholder {
        color: #94a3b8 !important;
    }

    input:focus, select:focus, textarea:focus {
        border-color: var(--koya) !important;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12) !important;
        background: #ffffff !important;
    }

    select option {
        background: #ffffff !important;
        color: var(--text-primary) !important;
    }

    label, .text-xs.text-gray-400 {
        color: var(--text-secondary) !important;
        font-weight: 600 !important;
        font-size: 0.75rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        margin-bottom: 0.375rem !important;
        display: block;
    }

    /* Buttons */
    .btn-koya, .btn-reservation, a.btn-koya, a.btn-reservation {
        background: var(--koya) !important;
        color: #ffffff !important;
        border: 1px solid var(--koya) !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2) !important;
        font-weight: 700 !important;
        font-family: 'Outfit', sans-serif !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem 1.5rem !important;
        transition: all 0.2s ease !important;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-koya:hover, .btn-reservation:hover:not(:disabled), a.btn-koya:hover {
        background: var(--koya-hover) !important;
        border-color: var(--koya-hover) !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3) !important;
        color: #ffffff !important;
    }

    .btn-koya:active, .btn-reservation:active:not(:disabled) {
        transform: translateY(0);
    }

    .btn-koya:disabled {
        background: #cbd5e1 !important;
        border-color: #cbd5e1 !important;
        color: #94a3b8 !important;
        box-shadow: none !important;
        cursor: not-allowed;
    }

    /* Clean bg color utility classes to prevent sizing/padding overrides */
    .bg-koya {
        background-color: var(--koya) !important;
    }
    .text-koya {
        color: var(--koya) !important;
    }
    .border-koya {
        border-color: var(--koya) !important;
    }

    /* Tables */
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    th {
        background-color: #f1f5f9 !important;
        color: var(--text-secondary) !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem 1rem !important;
        border-bottom: 2px solid var(--border-color) !important;
    }

    td {
        border-bottom: 1px solid var(--border-color) !important;
        padding: 0.875rem 1rem !important;
        color: var(--text-secondary) !important;
        background-color: #ffffff !important;
    }

    tr:hover td {
        background-color: #f8fafc !important;
    }

    /* Navigation bar styling */
    nav.fixed, nav.fixed .glass, nav.fixed [class*="glass"] {
        background-color: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border-bottom: 1px solid var(--border-color) !important;
        border-radius: 0 !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }

    .glass.border-y {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    /* Bottom quick links (icons) */
    .bottom-quick-link {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.3rem !important;
        width: auto !important;
        color: var(--text-muted) !important;
        transition: all 0.2s ease !important;
        text-align: center !important;
    }
    .bottom-quick-link:hover {
        color: var(--koya) !important;
    }
    .bottom-quick-link.active {
        color: var(--koya) !important;
        filter: drop-shadow(0 2px 4px rgba(79, 70, 229, 0.15)) !important;
    }

    /* Hamburger / Menu buttons */
    button.bg-koya\/15, [class*="bg-koya/15"] {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 2.5rem !important;
        height: 2.5rem !important;
        border-radius: 12px !important;
        background-color: var(--koya-light) !important;
        color: var(--koya) !important;
        border-color: rgba(79, 70, 229, 0.2) !important;
        transition: all 0.2s ease !important;
        border: 1px solid rgba(79, 70, 229, 0.15) !important;
    }
    button.bg-koya\/15:hover, [class*="bg-koya/15"]:hover {
        background-color: var(--koya) !important;
        color: #ffffff !important;
    }

    /* Bottom menu panel / Dropdown */
    .bottom-menu-panel {
        background: #ffffff !important;
        border: 1px solid var(--border-color) !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
        border-radius: 16px !important;
    }

    .bottom-menu-item {
        display: flex !important;
        align-items: center !important;
        gap: 0.85rem !important;
        border-radius: 12px !important;
        padding: 0.8rem 0.95rem !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-secondary) !important;
        background: #f8fafc !important;
        transition: all 0.2s ease !important;
        font-weight: 600 !important;
    }
    .bottom-menu-item:hover {
        border-color: var(--koya) !important;
        color: var(--koya) !important;
        background: rgba(79, 70, 229, 0.05) !important;
        transform: translateY(-1px);
    }
    .bottom-menu-item.active {
        color: var(--koya) !important;
        border-color: var(--koya) !important;
        background: rgba(79, 70, 229, 0.08) !important;
    }

    /* Border colors */
    .border-white\/5, .border-white\/10, .border-white\/15, .border-white\/20, .border-white\/30, .border-gray-100, .border-gray-200, .border-gray-300, .border-gray-700, .border-slate-800 {
        border-color: var(--border-color) !important;
    }

    /* Background overrides for cards/divs inside layout */
    .bg-white {
        background-color: #ffffff !important;
    }
    .bg-gray-50, .bg-slate-50 {
        background-color: #f8fafc !important;
    }
    .bg-gray-100, .bg-slate-100 {
        background-color: #f1f5f9 !important;
    }
    .bg-gray-800, .bg-gray-900, .bg-slate-800, .bg-slate-900, .bg-koya-dark {
        background-color: #ffffff !important;
    }

    /* Buttons that use bg-white/5 or bg-white/10 */
    .bg-white\/5, .bg-white\/10, .bg-white\/15, .bg-white\/20, [class*="bg-white/5"], [class*="bg-white/10"] {
        background-color: #f1f5f9 !important;
        color: var(--text-secondary) !important;
        border: 1px solid var(--border-color) !important;
    }
    .bg-white\/5:hover, .bg-white\/10:hover, [class*="bg-white/5"]:hover, [class*="bg-white/10"]:hover {
        background-color: #e2e8f0 !important;
        color: var(--text-primary) !important;
    }

    /* High-end Modals Styling */
    .fixed.inset-0.z-\[100\] .absolute.inset-0.bg-black\/60 {
        background-color: rgba(15, 23, 42, 0.45) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
    }
    .fixed.inset-0.z-\[100\] .glass {
        background: #ffffff !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        border-radius: 24px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04), 0 0 0 1px rgba(0, 0, 0, 0.02) !important;
        padding: 2.25rem !important;
        max-width: 38rem !important;
        width: 100% !important;
    }
    .fixed.inset-0.z-\[100\] h3 {
        font-family: 'Cormorant Garamond', serif !important;
        font-size: 1.85rem !important;
        font-weight: 700 !important;
        color: var(--text-primary) !important;
        letter-spacing: -0.01em !important;
        margin-top: 0.25rem !important;
    }
    .fixed.inset-0.z-\[100\] button.text-gray-400 {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 2.25rem !important;
        height: 2.25rem !important;
        border-radius: 50% !important;
        background-color: #f1f5f9 !important;
        color: #64748b !important;
        transition: all 0.2s ease !important;
        border: none !important;
    }
    .fixed.inset-0.z-\[100\] button.text-gray-400:hover {
        background-color: #e2e8f0 !important;
        color: #0f172a !important;
    }
    .fixed.inset-0.z-\[100\] input, 
    .fixed.inset-0.z-\[100\] select, 
    .fixed.inset-0.z-\[100\] textarea {
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        font-size: 0.9rem !important;
    }
    .fixed.inset-0.z-\[100\] input:focus, 
    .fixed.inset-0.z-\[100\] select:focus, 
    .fixed.inset-0.z-\[100\] textarea:focus {
        background-color: #ffffff !important;
        border-color: var(--koya) !important;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12) !important;
    }
    .fixed.inset-0.z-\[100\] label {
        font-size: 0.7rem !important;
        font-weight: 700 !important;
        color: #475569 !important;
        letter-spacing: 0.08em !important;
        text-transform: uppercase !important;
        margin-bottom: 0.5rem !important;
    }
    .fixed.inset-0.z-\[100\] button[type="submit"] {
        width: 100% !important;
        padding: 0.875rem 1.5rem !important;
        font-size: 0.95rem !important;
        font-weight: 700 !important;
        border-radius: 12px !important;
        letter-spacing: 0.05em !important;
        text-transform: uppercase !important;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
    }
    .fixed.inset-0.z-\[100\] button[type="submit"]:disabled {
        background-color: #e2e8f0 !important;
        color: #94a3b8 !important;
        border-color: #e2e8f0 !important;
        box-shadow: none !important;
        cursor: not-allowed !important;
    }

    /* Interactive and lift classes */
    .hover-lift:hover {
        transform: translateY(-2px);
        background-color: #f8fafc !important;
        border-color: var(--koya) !important;
    }

    /* KPI specific colors in stats and dashboard */
    .kpi-card, .kpi-income, .kpi-expense, .kpi-profit {
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
        border-radius: 16px !important;
    }
    .kpi-card::before {
        display: none !important;
    }
    .kpi-income {
        border-left: 4px solid #10b981 !important;
    }
    .kpi-expense {
        border-left: 4px solid #ef4444 !important;
    }
    .kpi-profit {
        border-left: 4px solid #f59e0b !important;
    }
    .kpi-profit.negative {
        border-left: 4px solid #ef4444 !important;
    }

    /* Override inline range filter button styling in stats page */
    button[style*="rgba(255,92,0"] {
        background-color: var(--koya-light) !important;
        color: var(--koya) !important;
        border: 1px solid rgba(79, 70, 229, 0.2) !important;
    }

    /* Mobile specific adjustments */
    @media (max-width: 640px) {
        main {
            padding-inline: 0.75rem !important;
            padding-top: 5.5rem !important;
            padding-bottom: 2rem !important;
        }
        .glass, [class*="glass"] {
            padding: 1.25rem !important;
            border-radius: 12px !important;
        }
        .grid-cols-2, .grid-cols-3, .grid-cols-4 {
            grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
            gap: 1rem !important;
        }
        /* Make tables horizontal scrollable on mobile */
        .overflow-x-auto {
            border-radius: 12px;
            border: 1px solid var(--border-color);
            margin-inline: -0.25rem;
        }
        table {
            font-size: 0.875rem;
        }
        th, td {
            padding: 0.625rem 0.75rem !important;
        }
        
        /* Adjust page header title on mobile */
        h2.font-display {
            font-size: 2rem !important;
        }

        /* Modals full screen on mobile for ease of use */
        .fixed.inset-0.z-\[100\] .glass {
            max-height: 85vh;
            overflow-y: auto;
            margin-bottom: 2rem;
        }
    }

    /* Keyframe overrides to resolve containing block viewport bug on fixed modals */
    @keyframes fadeInOnly {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    .animate-fade-in-up {
        animation: fadeInOnly 0.5s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
</style>

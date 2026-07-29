<style>
    :root {
        --accent: #4f46e5; /* Standard brand Indigo */
        --accent-hover: #4338ca;
        --accent-light: rgba(79, 70, 229, 0.08);
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

    /* High contrast green and red overrides for readability in light theme */
    .text-green-400, .text-green-500, .text-green-600, .text-emerald-400, .text-emerald-500, .text-emerald-600, [class*="text-green-"], [class*="text-emerald-"] {
        color: #047857 !important; /* dark emerald green */
    }
    .text-red-400, .text-red-500, .text-red-600, [class*="text-red-"] {
        color: #b91c1c !important; /* dark readable red */
    }
    .bg-green-500\/5, .bg-green-500\/10, .bg-green-500\/20, .bg-emerald-500\/5, .bg-emerald-500\/10, .bg-emerald-500\/20, [class*="bg-green-"], [class*="bg-emerald-"] {
        background-color: #ecfdf5 !important;
        border-color: #a7f3d0 !important;
    }
    .bg-red-500\/5, .bg-red-500\/10, .bg-red-500\/20, [class*="bg-red-"] {
        background-color: #fef2f2 !important;
        border-color: #fecaca !important;
    }

    /* Preserve white text on solid buttons, badges, and dark status elements */
    button.text-white, button .text-white,
    a.text-white, a .text-white,
    .bg-green-500, .bg-red-500, .bg-emerald-500, .bg-blue-500, .bg-indigo-600, .bg-indigo-500,
    .btn-accent, .btn-reservation, .btn-danger,
    [class*="bg-green-500"] .text-white, [class*="bg-emerald-500"] .text-white, [class*="bg-red-500"] .text-white, [class*="bg-blue-500"] .text-white,
    [class*="bg-green-"] .text-white, [class*="bg-emerald-"] .text-white, [class*="bg-red-"] .text-white, [class*="bg-blue-"] .text-white, [class*="bg-indigo-"] .text-white,
    button[type="submit"] {
        color: #ffffff !important;
    }

    /* Solid action button overrides (preventing wildcard washout) */
    .bg-emerald-500, .bg-green-500, .bg-emerald-600, button.bg-emerald-600, .hover\:bg-emerald-700:hover, button.hover\:bg-emerald-700:hover {
        background-color: #10b981 !important;
        color: #ffffff !important;
    }
    .bg-rose-600, button.bg-rose-600, .hover\:bg-rose-700:hover, button.hover\:bg-rose-700:hover {
        background-color: #e11d48 !important;
        color: #ffffff !important;
    }
    .bg-indigo-600, button.bg-indigo-600, .hover\:bg-indigo-700:hover, button.hover\:bg-indigo-700:hover {
        background-color: #4f46e5 !important;
        color: #ffffff !important;
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
        border-color: var(--accent) !important;
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
    .btn-accent, .btn-reservation, a.btn-accent, a.btn-reservation {
        background: var(--accent) !important;
        color: #ffffff !important;
        border: 1px solid var(--accent) !important;
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

    .btn-accent:hover, .btn-reservation:hover:not(:disabled), a.btn-accent:hover {
        background: var(--accent-hover) !important;
        border-color: var(--accent-hover) !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3) !important;
        color: #ffffff !important;
    }

    .btn-accent:active, .btn-reservation:active:not(:disabled) {
        transform: translateY(0);
    }

    .btn-accent:disabled {
        background: #cbd5e1 !important;
        border-color: #cbd5e1 !important;
        color: #94a3b8 !important;
        box-shadow: none !important;
        cursor: not-allowed;
    }

    /* Clean bg color utility classes to prevent sizing/padding overrides */
    .bg-accent {
        background-color: var(--accent) !important;
    }
    .text-accent {
        color: var(--accent) !important;
    }
    .border-accent {
        border-color: var(--accent) !important;
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
    nav.fixed {
        top: 16px !important;
        left: 20px !important;
        right: 20px !important;
        width: calc(100% - 40px) !important;
        z-index: 50 !important;
    }

    nav.fixed .glass, nav.fixed [class*="glass"] {
        background-color: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(20px) saturate(180%) !important;
        -webkit-backdrop-filter: blur(20px) saturate(180%) !important;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.04), 0 0 1px 0 rgba(0, 0, 0, 0.04) !important;
        padding: 0.5rem 1.25rem !important;
        border-top: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-left: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-right: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-bottom: 1px solid rgba(226, 232, 240, 0.8) !important;
    }

    .glass.border-y {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    /* Logo Styling inside Nav */
    nav.fixed a.shrink-0 img, nav.fixed a.shrink-0 svg {
        border-radius: 12px !important;
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        padding: 2px !important;
        background-color: #ffffff !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
    }
    nav.fixed a.shrink-0:hover img, nav.fixed a.shrink-0:hover svg {
        transform: scale(1.08) rotate(4deg);
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.15) !important;
        border-color: rgba(79, 70, 229, 0.2) !important;
    }

    /* Central pill container for menu items */
    .bottom-primary-links {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 1.25rem !important;
        background-color: transparent !important;
        padding: 0 !important;
        border-radius: 0 !important;
        border: none !important;
        max-width: fit-content !important;
        margin: 0 auto !important;
    }

    /* Quick links (icons + label pills) */
    .bottom-quick-link {
        display: inline-flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem !important;
        padding: 0.45rem 1rem !important;
        border-radius: 9999px !important;
        color: #64748b !important; /* slate-500 */
        font-weight: 600 !important;
        font-size: 0.8rem !important;
        letter-spacing: -0.01em !important;
        text-decoration: none !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        width: auto !important;
        text-align: center !important;
    }
    .bottom-quick-link svg {
        width: 1rem !important;
        height: 1rem !important;
        stroke-width: 2.2 !important;
        color: #64748b !important;
        transition: all 0.2s ease !important;
    }
    .bottom-quick-link span {
        font-family: 'Outfit', sans-serif !important;
        font-size: 0.8rem !important;
        font-weight: 600 !important;
        text-transform: capitalize !important;
        letter-spacing: normal !important;
        display: inline !important;
        color: inherit !important;
    }
    .bottom-quick-link:hover {
        color: var(--accent) !important;
        background-color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02) !important;
    }
    .bottom-quick-link:hover svg {
        color: var(--accent) !important;
        transform: translateY(-0.5px);
    }
    .bottom-quick-link.active {
        color: #ffffff !important;
        background-color: var(--accent) !important;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2) !important;
    }
    .bottom-quick-link.active svg {
        color: #ffffff !important;
    }

    /* Hamburger / Menu buttons */
    button.bg-accent\/15, [class*="bg-accent/15"] {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 2.25rem !important;
        height: 2.25rem !important;
        border-radius: 10px !important;
        background-color: var(--accent-light) !important;
        color: var(--accent) !important;
        border: 1px solid rgba(79, 70, 229, 0.12) !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    button.bg-accent\/15:hover, [class*="bg-accent/15"]:hover {
        background-color: var(--accent) !important;
        color: #ffffff !important;
        border-color: var(--accent) !important;
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.15) !important;
    }

    /* Overflow menu dropdown panel */
    .bottom-menu-panel {
        position: absolute !important;
        top: calc(100% + 8px) !important;
        right: 0px !important;
        width: 280px !important;
        background: rgba(255, 255, 255, 0.98) !important;
        border: 1px solid rgba(226, 232, 240, 0.9) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08), 0 4px 12px -5px rgba(0, 0, 0, 0.03) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        padding: 0.5rem !important;
        z-index: 100 !important;
    }

    .bottom-menu-panel .grid-cols-2 {
        display: flex !important;
        flex-direction: column !important;
        gap: 0.25rem !important;
    }

    .bottom-menu-item {
        display: flex !important;
        align-items: center !important;
        gap: 0.75rem !important;
        border-radius: 8px !important;
        padding: 0.5rem 0.75rem !important;
        border: 1px solid transparent !important;
        color: var(--text-secondary) !important;
        background: transparent !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        font-weight: 550 !important;
    }
    .bottom-menu-item svg {
        width: 1rem !important;
        height: 1rem !important;
        stroke-width: 2 !important;
        color: #64748b !important;
        transition: color 0.2s ease;
    }
    .bottom-menu-item span {
        font-family: 'Outfit', sans-serif !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        text-transform: capitalize !important;
        letter-spacing: normal !important;
    }
    .bottom-menu-item:hover {
        color: var(--accent) !important;
        background-color: var(--accent-light) !important;
        border-color: rgba(79, 70, 229, 0.05) !important;
    }
    .bottom-menu-item:hover svg {
        color: var(--accent) !important;
    }
    .bottom-menu-item.active {
        color: var(--accent) !important;
        background-color: var(--accent-light) !important;
        border-color: rgba(79, 70, 229, 0.1) !important;
        font-weight: 700 !important;
    }
    .bottom-menu-item.active svg {
        color: var(--accent) !important;
    }

    .bottom-menu-panel form {
        margin-top: 0.25rem !important;
        border-top: 1px solid rgba(226, 232, 240, 0.8) !important;
        padding-top: 0.25rem !important;
    }
    .bottom-menu-panel form .bottom-menu-item {
        color: #ef4444 !important;
    }
    .bottom-menu-panel form .bottom-menu-item svg {
        color: #ef4444 !important;
    }
    .bottom-menu-panel form .bottom-menu-item:hover {
        background-color: rgba(239, 68, 68, 0.05) !important;
        border-color: rgba(239, 68, 68, 0.1) !important;
        color: #b91c1c !important;
    }
    .bottom-menu-panel form .bottom-menu-item:hover svg {
        color: #b91c1c !important;
    }

    @media (max-width: 1023px) {
        nav.fixed {
            top: 8px !important;
            left: 8px !important;
            right: 8px !important;
            width: calc(100% - 16px) !important;
        }
        nav.fixed .glass {
            padding: 0.4rem 0.75rem !important;
            border-radius: 12px !important;
        }
        .bottom-primary-links {
            display: flex !important;
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            overflow: visible !important;
            justify-content: space-between !important;
            background-color: transparent !important;
            padding: 0 !important;
            border: none !important;
            gap: 0.5rem !important;
        }
        .bottom-primary-links::-webkit-scrollbar {
            display: none !important;
        }
        .bottom-primary-links > a,
        .bottom-primary-links > div {
            flex: 1 1 0% !important;
            min-width: 0 !important;
        }
        .bottom-quick-link {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.3rem !important;
            width: 100% !important;
            height: 3.5rem !important;
            padding: 0.25rem 0 !important;
            border-radius: 14px !important;
        }
        .bottom-quick-link svg {
            width: 1.15rem !important;
            height: 1.15rem !important;
        }
        .bottom-quick-link span {
            display: block !important;
            font-size: 0.52rem !important;
            font-weight: 700 !important;
            text-transform: capitalize !important;
            line-height: 1 !important;
            letter-spacing: -0.01em !important;
            color: inherit !important;
        }
    }

    /* Header Profile Dropdown Button & Menu */
    nav.fixed .relative button[title="Profile"] {
        border: 1px solid transparent !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        color: #475569 !important;
        background-color: transparent !important;
        cursor: pointer;
    }
    nav.fixed .relative button[title="Profile"]:hover {
        color: var(--accent) !important;
        background-color: var(--accent-light) !important;
        border-color: rgba(79, 70, 229, 0.08) !important;
    }
    nav.fixed .relative button[title="Profile"].active {
        color: #ffffff !important;
        background-color: var(--accent) !important;
        border-color: var(--accent) !important;
    }
    nav.fixed .relative button[title="Profile"].active svg,
    nav.fixed .relative button[title="Profile"].active span {
        color: #ffffff !important;
    }

    .dropdown-menu-container {
        background-color: rgba(255, 255, 255, 0.98) !important;
        border: 1px solid rgba(226, 232, 240, 0.95) !important;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.1), 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
    }
    .dropdown-menu-container a,
    .dropdown-menu-container button {
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
        border-radius: 10px !important;
        padding: 0.5rem 0.75rem !important;
        transition: all 0.2s ease !important;
        color: var(--text-secondary) !important;
        background-color: transparent !important;
        border: none !important;
        font-weight: 550 !important;
        cursor: pointer;
    }
    .dropdown-menu-container a:hover {
        background-color: var(--accent-light) !important;
        color: var(--accent) !important;
    }
    .dropdown-menu-container a.text-indigo-600 {
        color: var(--accent) !important;
        background-color: var(--accent-light) !important;
    }
    .dropdown-menu-container a:hover svg {
        color: var(--accent) !important;
    }
    .dropdown-menu-container button.text-red-500 {
        color: #ef4444 !important;
    }
    .dropdown-menu-container button.text-red-500:hover {
        background-color: rgba(239, 68, 68, 0.05) !important;
        color: #b91c1c !important;
    }
    .dropdown-menu-container button.text-red-500:hover svg {
        color: #b91c1c !important;
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
    .bg-gray-800, .bg-gray-900, .bg-slate-800, .bg-slate-900, .bg-accent-dark {
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
    .fixed.inset-0.z-\[100\] .glass.max-w-2xl {
        background: #ffffff !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        border-radius: 24px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04), 0 0 0 1px rgba(0, 0, 0, 0.02) !important;
        padding: 1rem !important;
        max-width: 38rem !important;
        width: 100% !important;
    }

    /* Keep the finance entry overlay flush with, and centered in, the viewport. */
    .fixed.inset-0.z-\[100\]:is(.finance-add-modal, .finance-edit-modal, .reservation-add-modal, .product-add-modal, .product-edit-modal) {
        position: fixed !important;
        inset: 0 !important;
        width: auto !important;
        height: 100vh !important;
        height: 100dvh !important;
        min-height: 100vh !important;
        min-height: 100dvh !important;
        margin: 0 !important;
        align-items: center !important;
        box-sizing: border-box;
        isolation: isolate;
    }
    @media (min-width: 640px) {
        .fixed.inset-0.z-\[100\] .glass.max-w-2xl {
            padding: 1.5rem !important;
        }
    }
    .fixed.inset-0.z-\[100\] h3 {
        font-family: 'Cormorant Garamond', serif !important;
        font-size: 1.85rem !important;
        font-weight: 700 !important;
        color: var(--text-primary) !important;
        letter-spacing: -0.01em !important;
        margin-top: 0.25rem !important;
    }
    .fixed.inset-0.z-\[100\]:is(.finance-add-modal, .finance-edit-modal, .reservation-add-modal, .product-add-modal, .product-edit-modal) h3 {
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        letter-spacing: -0.01em !important;
    }
    .fixed.inset-0.z-\[100\].finance-edit-modal h3 {
        display: flex !important;
        align-items: center;
        gap: 0.75rem;
        margin: 0 !important;
    }
    .fixed.inset-0.z-\[100\].finance-edit-modal h3::before {
        content: '€';
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        flex: 0 0 2.5rem;
        border: 1px solid #a7f3d0;
        border-radius: 0.75rem;
        background: #ecfdf5;
        color: #059669;
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        font-weight: 800;
    }
    .fixed.inset-0.z-\[100\]:is(.product-add-modal, .product-edit-modal) h3 {
        display: flex !important;
        align-items: center;
        gap: 0.75rem;
        margin: 0 !important;
    }
    .fixed.inset-0.z-\[100\]:is(.product-add-modal, .product-edit-modal) h3::before {
        content: '+';
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        flex: 0 0 2.5rem;
        border: 1px solid #c7d2fe;
        border-radius: 0.75rem;
        background: #eef2ff;
        color: #4f46e5;
        font-family: 'Outfit', sans-serif;
        font-size: 1.25rem;
        font-weight: 800;
    }
    .fixed.inset-0.z-\[100\].product-edit-modal h3::before {
        content: '✎';
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
        color: var(--text-primary) !important;
    }
    .fixed.inset-0.z-\[100\] select option,
    .fixed.inset-0.z-\[100\] option,
    .fixed.inset-0.z-\[100\] option.bg-gray-900 {
        background-color: #ffffff !important;
        color: var(--text-primary) !important;
    }
    .fixed.inset-0.z-\[100\] input:focus, 
    .fixed.inset-0.z-\[100\] select:focus, 
    .fixed.inset-0.z-\[100\] textarea:focus {
        background-color: #ffffff !important;
        border-color: var(--accent) !important;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12) !important;
    }
    .fixed.inset-0.z-\[100\] input[name="amount"] {
        min-height: 4.5rem !important;
        padding-left: 3.75rem !important;
        font-size: 2.25rem !important;
        font-weight: 900 !important;
    }
    .fixed.inset-0.z-\[100\] .relative span.absolute {
        font-size: 1.85rem !important;
        left: 1.25rem !important;
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
        color: #64748b !important;
        border-color: #e2e8f0 !important;
        box-shadow: none !important;
        cursor: not-allowed !important;
    }

    /* Interactive and lift classes */
    .hover-lift:hover {
        transform: translateY(-2px);
        background-color: #f8fafc !important;
        border-color: var(--accent) !important;
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
        background-color: var(--accent-light) !important;
        color: var(--accent) !important;
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

        /* Allow scrollable modal wrapper on small screens if layout overflows viewport */
        .fixed.inset-0.z-\[100\] {
            align-items: flex-start !important;
            overflow-y: auto !important;
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }
        .fixed.inset-0.z-\[100\] .glass.max-w-2xl {
            max-height: none !important;
            overflow-y: visible !important;
            margin-bottom: 0 !important;
        }
        .fixed.inset-0.z-\[100\]:is(.finance-add-modal, .finance-edit-modal, .reservation-add-modal, .product-add-modal, .product-edit-modal) {
            align-items: center !important;
            justify-content: center !important;
            overflow: hidden !important;
            padding: max(0.75rem, env(safe-area-inset-top)) 0.375rem max(0.75rem, env(safe-area-inset-bottom)) !important;
            overscroll-behavior: contain;
        }
        .fixed.inset-0.z-\[100\]:is(.finance-add-modal, .finance-edit-modal, .reservation-add-modal, .product-add-modal, .product-edit-modal) > .glass.max-w-2xl {
            width: 100% !important;
            max-width: none !important;
            max-height: calc(100dvh - 1.5rem) !important;
            overflow-y: auto !important;
            overscroll-behavior: contain;
            border-radius: 20px !important;
            padding: 0.875rem !important;
            scrollbar-width: thin;
        }
        .fixed.inset-0.z-\[100\]:is(.finance-add-modal, .finance-edit-modal, .reservation-add-modal, .product-add-modal, .product-edit-modal) button.text-gray-400 {
            min-width: 2.5rem !important;
            min-height: 2.5rem !important;
            flex-shrink: 0;
        }
        .fixed.inset-0.z-\[100\]:is(.finance-add-modal, .finance-edit-modal, .reservation-add-modal, .product-add-modal, .product-edit-modal) button[type="submit"] {
            min-height: 2.75rem !important;
        }
        /* Mobile specific tighter modal sizing to prevent scrolling */
        .fixed.inset-0.z-\[100\] input, 
        .fixed.inset-0.z-\[100\] select, 
        .fixed.inset-0.z-\[100\] textarea {
            min-height: 2.5rem !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.85rem !important;
        }
        .fixed.inset-0.z-\[100\] label {
            font-size: 0.65rem !important;
            margin-bottom: 0.25rem !important;
        }
        .fixed.inset-0.z-\[100\] h3 {
            font-size: 1.4rem !important;
        }
        .fixed.inset-0.z-\[100\] button[type="submit"] {
            padding: 0.65rem 1.25rem !important;
            font-size: 0.875rem !important;
        }
        .fixed.inset-0.z-\[100\] .mb-6 {
            margin-bottom: 0.75rem !important;
        }
        .fixed.inset-0.z-\[100\] .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 0.75rem !important;
        }
        .fixed.inset-0.z-\[100\] .grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
            gap: 0.5rem !important;
        }
        .fixed.inset-0.z-\[100\] .grid-cols-4 {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 0.5rem !important;
        }
        .fixed.inset-0.z-\[100\] input[name="amount"] {
            min-height: 3.75rem !important;
            padding-left: 3.25rem !important;
            font-size: 1.75rem !important;
            font-weight: 900 !important;
        }
        .fixed.inset-0.z-\[100\] .relative span.absolute {
            font-size: 1.5rem !important;
            left: 1rem !important;
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

    /* KPI gradient cards with modern aesthetics */
    .kpi-card {
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid #e2e8f0 !important;
        background: #ffffff !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05) !important;
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        opacity: 0.85;
        transition: opacity 0.3s ease;
    }

    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.1) !important;
    }

    /* Income Card Styling */
    .kpi-income {
        background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%) !important;
        border-color: rgba(16, 185, 129, 0.2) !important;
        box-shadow: 0 4px 15px -3px rgba(16, 185, 129, 0.05) !important;
    }
    .kpi-income::before {
        background: linear-gradient(90deg, #10b981, #34d399);
    }
    .kpi-income:hover {
        box-shadow: 0 12px 24px -10px rgba(16, 185, 129, 0.2), 0 0 0 1px rgba(16, 185, 129, 0.3) !important;
    }

    /* Expense Card Styling */
    .kpi-expense {
        background: linear-gradient(135deg, #ffffff 0%, #fef2f2 100%) !important;
        border-color: rgba(239, 68, 68, 0.2) !important;
        box-shadow: 0 4px 15px -3px rgba(239, 68, 68, 0.05) !important;
    }
    .kpi-expense::before {
        background: linear-gradient(90deg, #ef4444, #f87171);
    }
    .kpi-expense:hover {
        box-shadow: 0 12px 24px -10px rgba(239, 68, 68, 0.2), 0 0 0 1px rgba(239, 68, 68, 0.3) !important;
    }

    /* Profit Card Styling */
    .kpi-profit {
        background: linear-gradient(135deg, #ffffff 0%, #fffbeb 100%) !important;
        border-color: rgba(245, 158, 11, 0.2) !important;
        box-shadow: 0 4px 15px -3px rgba(245, 158, 11, 0.05) !important;
    }
    .kpi-profit::before {
        background: linear-gradient(90deg, #fbbf24, #f59e0b);
    }
    .kpi-profit:hover {
        box-shadow: 0 12px 24px -10px rgba(251, 191, 36, 0.2), 0 0 0 1px rgba(251, 191, 36, 0.3) !important;
    }
    
    .kpi-profit.negative {
        background: linear-gradient(135deg, #ffffff 0%, #fef2f2 100%) !important;
        border-color: rgba(239, 68, 68, 0.2) !important;
        box-shadow: 0 4px 15px -3px rgba(239, 68, 68, 0.05) !important;
    }
    .kpi-profit.negative::before {
        background: linear-gradient(90deg, #ef4444, #dc2626);
    }
    .kpi-profit.negative:hover {
        box-shadow: 0 12px 24px -10px rgba(239, 68, 68, 0.2), 0 0 0 1px rgba(239, 68, 68, 0.3) !important;
    }

    .badge-dot {
        display: inline-block;
        width: 5px; height: 5px;
        border-radius: 50%;
        margin-right: 4px;
    }

    /* Hover lift cards */
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        background: #ffffff !important;
        border-color: rgba(0,0,0,0.1) !important;
    }

    /* Status pills */
    .status-pill {
        padding: 0.15rem 0.45rem;
        border-radius: 9999px;
        font-size: 0.55rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        line-height: 1;
    }
    .status-confirmed {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }
    .status-completed {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }
    .status-cancelled {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    /* Modern Premium Pagination Styling */
    nav[role="navigation"] {
        background: transparent !important;
        border: none !important;
        padding: 0.5rem 0 !important;
    }
    
    /* Description Text: "Showing X to Y of Z results" */
    nav[role="navigation"] p.text-sm {
        font-size: 0.75rem !important;
        color: #64748b !important; /* slate-500 */
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        font-family: 'Outfit', sans-serif !important;
    }
    nav[role="navigation"] p.text-sm span {
        color: #0f172a !important; /* slate-900 */
        font-weight: 900 !important;
    }

    /* Page Numbers Container - Remove merged border group */
    nav[role="navigation"] .shadow-sm.rounded-md {
        box-shadow: none !important;
        border: none !important;
        display: inline-flex !important;
        gap: 0.35rem !important; /* separate into beautiful pills! */
        background: transparent !important;
    }

    /* All pagination buttons (Active, Inactive, Arrows) */
    nav[role="navigation"] .shadow-sm.rounded-md > a,
    nav[role="navigation"] .shadow-sm.rounded-md > span,
    nav[role="navigation"] .shadow-sm.rounded-md > span > span,
    nav[role="navigation"] .shadow-sm.rounded-md > span > a {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 2.25rem !important;
        height: 2.25rem !important;
        padding: 0 0.5rem !important;
        border-radius: 10px !important; /* Rounded independent buttons */
        border: 1px solid #e2e8f0 !important;
        background-color: #ffffff !important;
        color: #475569 !important; /* slate-600 */
        font-size: 0.8rem !important;
        font-weight: 700 !important;
        transition: all 0.2s ease !important;
        margin: 0 !important;
    }

    /* Hover effect for inactive pagination buttons */
    nav[role="navigation"] .shadow-sm.rounded-md > a:hover,
    nav[role="navigation"] .shadow-sm.rounded-md > span > a:hover {
        background-color: #f1f5f9 !important;
        color: #4f46e5 !important; /* var(--accent) Brand color */
        border-color: #cbd5e1 !important;
        transform: translateY(-1px);
    }

    /* Active Page button */
    nav[role="navigation"] .shadow-sm.rounded-md [aria-current="page"] span,
    nav[role="navigation"] .shadow-sm.rounded-md [aria-current="page"] a {
        background-color: #4f46e5 !important; /* var(--accent) Brand color */
        color: #ffffff !important;
        border-color: #4f46e5 !important;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25) !important;
    }

    /* Disabled/Inactive buttons (e.g. arrows at limits) */
    nav[role="navigation"] .shadow-sm.rounded-md span[aria-disabled="true"] span {
        background-color: #f8fafc !important;
        color: #94a3b8 !important; /* slate-400 */
        border-color: #f1f5f9 !important;
        cursor: not-allowed !important;
        opacity: 0.6 !important;
    }

    .mobile-admin-header { display: none; }

    @media (max-width: 1023px) {
        .mobile-admin-header {
            position: fixed; top: 0; right: 0; left: 0; z-index: 51;
            display: flex; min-height: calc(3.75rem + env(safe-area-inset-top));
            align-items: center; justify-content: space-between;
            padding: env(safe-area-inset-top) max(1rem, env(safe-area-inset-right)) 0 max(1rem, env(safe-area-inset-left));
            border-bottom: 1px solid #e2e8f0; background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
            backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px);
        }
        .mobile-admin-brand { display: flex; align-items: center; }
        .mobile-admin-brand { min-width: 0; gap: 0.7rem; color: #0f172a; text-decoration: none; }
        .mobile-admin-brand img {
            width: 2.1rem; height: 2.1rem; flex: none; border: 1px solid #e2e8f0;
            border-radius: 12px; object-fit: cover; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.08);
        }
        .mobile-admin-brand span { display: flex; min-width: 0; flex-direction: column; line-height: 1.1; }
        .mobile-admin-brand strong {
            max-width: 10rem; overflow: hidden; color: #0f172a; font-size: 0.82rem;
            font-weight: 900; text-overflow: ellipsis; white-space: nowrap;
        }
        .mobile-admin-brand small {
            margin-top: 0.3rem; color: #64748b; font-size: 0.55rem; font-weight: 800;
            letter-spacing: 0.12em; text-transform: uppercase;
        }

        .mobile-profile-menu { position: relative; flex: none; }
        .mobile-admin-avatar {
            display: grid; width: 2rem; height: 2rem; min-height: 2rem;
            place-items: center; border: 1px solid #e2e8f0; border-radius: 999px;
            background: #fff; color: #334155; font-size: 0.68rem; font-weight: 800; text-decoration: none;
        }
        .mobile-admin-profile-button {
            display: inline-flex; min-height: 2.25rem; align-items: center; gap: 0.3rem;
            border: 0; padding: 0; background: transparent; color: #64748b; cursor: pointer;
        }
        .mobile-admin-profile-button > svg { width: 0.8rem; height: 0.8rem; }
        .mobile-profile-dropdown {
            position: absolute; top: calc(100% + 0.65rem); right: 0; z-index: 60;
            width: 10rem; padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 14px;
            background: #fff; box-shadow: 0 16px 35px rgba(15, 23, 42, 0.16);
        }
        .mobile-profile-dropdown a, .mobile-profile-dropdown button {
            display: flex; width: 100%; min-height: 2.4rem; align-items: center;
            border: 0; border-radius: 10px; padding: 0.55rem 0.7rem; background: transparent;
            color: #334155; font-size: 0.75rem; font-weight: 700; text-decoration: none; cursor: pointer;
        }
        .mobile-profile-dropdown a:hover { background: #f1f5f9; color: #4f46e5; }

        .mobile-profile-dropdown form {
            display: block !important; margin: 0.25rem 0 0 !important;
            padding-top: 0.35rem !important; border-top: 1px solid #e2e8f0 !important;
        }
        .mobile-profile-dropdown .mobile-profile-logout {
            display: flex !important; visibility: visible !important; opacity: 1 !important;
            min-height: 2.5rem !important; align-items: center !important; gap: 0.55rem !important;
            background: #fff1f2 !important; color: #dc2626 !important;
        }
        .mobile-profile-dropdown .mobile-profile-logout svg {
            width: 1rem !important; height: 1rem !important; flex: none;
        }
        .mobile-profile-dropdown .mobile-profile-logout:hover { background: #ffe4e6 !important; }
        nav.admin-navigation.fixed {
            top: auto !important; right: env(safe-area-inset-right) !important;
            bottom: max(0.5rem, env(safe-area-inset-bottom)) !important;
            left: env(safe-area-inset-left) !important;
            width: auto !important; z-index: 52 !important;
        }
        nav.admin-navigation.fixed .glass {
            min-height: 3.25rem; padding: 0.15rem 0.3rem !important;
            border: 1px solid rgba(226, 232, 240, 0.95) !important;
            border-radius: 16px !important; background: rgba(255, 255, 255, 0.96) !important;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.16) !important;
            backdrop-filter: blur(20px) !important; -webkit-backdrop-filter: blur(20px) !important;
        }
        nav.admin-navigation > .glass > a.shrink-0 { display: none !important; }
        nav.admin-navigation .bottom-primary-links > div.relative { display: none !important; }
        nav.admin-navigation .bottom-primary-links > div.nav-more-menu { display: block !important; }
        nav.admin-navigation .bottom-primary-links { gap: 0.15rem !important; }
        nav.admin-navigation .bottom-quick-link {
            height: 2.75rem !important; min-height: 2.75rem !important;
            gap: 0.1rem !important; padding: 0.1rem !important; border-radius: 11px !important;
        }
        nav.admin-navigation .bottom-quick-link svg {
            width: 1.25rem !important; height: 1.25rem !important;
        }
        nav.admin-navigation .bottom-quick-link span {
            display: block !important; max-width: 100%; overflow: hidden;
            font-size: clamp(0.45rem, 1.8vw, 0.58rem) !important;
            text-overflow: ellipsis; white-space: nowrap;
        }
        nav.admin-navigation .dropdown-menu-container {
            top: auto !important; right: 0 !important; bottom: calc(100% + 0.75rem) !important;
            margin: 0 !important; transform-origin: bottom right;
        }
        nav.admin-navigation .nav-more-dropdown {
            width: min(22rem, calc(100vw - 1.5rem));
            max-height: min(70vh, 30rem);
            overflow-y: auto;
        }
        main {
            padding-top: calc(4.75rem + env(safe-area-inset-top)) !important;
            padding-bottom: calc(5.9rem + env(safe-area-inset-bottom)) !important;
        }
    }
</style>

<style>
    /*
     * Restaurant management workspace
     * Warm, durable surfaces designed for long daily service shifts.
     */
    :root {
        --accent: #b84f32;
        --accent-hover: #963b25;
        --accent-light: rgba(184, 79, 50, 0.1);
        --restaurant-espresso: #271c16;
        --restaurant-espresso-soft: #38271d;
        --restaurant-paper: #fffdf8;
        --restaurant-cream: #f5efe4;
        --restaurant-cream-deep: #ede3d4;
        --restaurant-terracotta: #b84f32;
        --restaurant-olive: #5f704f;
        --restaurant-brass: #c49a4a;
        --restaurant-ink: #2d251f;
        --restaurant-muted: #7c6f63;
        --restaurant-border: #e5d8c6;
        --bg: var(--restaurant-cream);
        --white: var(--restaurant-paper);
        --white-muted: var(--restaurant-muted);
        --border-color: var(--restaurant-border);
        --text-primary: var(--restaurant-ink);
        --text-secondary: #554a40;
        --text-muted: var(--restaurant-muted);
    }

    html {
        background: var(--restaurant-cream) !important;
    }

    body {
        background-color: var(--restaurant-cream) !important;
        background-image:
            radial-gradient(circle at 7% 4%, rgba(196, 154, 74, 0.12), transparent 24rem),
            radial-gradient(circle at 94% 18%, rgba(95, 112, 79, 0.1), transparent 28rem),
            linear-gradient(180deg, #faf6ef 0%, var(--restaurant-cream) 36%, #f2eadf 100%) !important;
        background-attachment: fixed !important;
        color: var(--restaurant-ink) !important;
    }

    body::after {
        position: fixed;
        inset: 0;
        z-index: -1;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 120 120' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.025'/%3E%3C/svg%3E");
        content: "";
        opacity: 0.42;
        pointer-events: none;
    }

    h1, h2, h3, h4,
    main h2.font-display {
        color: var(--restaurant-ink) !important;
    }

    h1, h2, h3,
    .font-display {
        font-family: "Outfit", sans-serif !important;
        font-weight: 800;
        letter-spacing: -0.035em !important;
    }

    .admin-main {
        width: min(100%, 94rem);
        margin-inline: auto;
    }

    .admin-service-heading {
        position: relative;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 2rem;
        margin: 0 auto 2rem;
        padding: 0.4rem 0.25rem 1.35rem;
        border-bottom: 1px solid rgba(124, 111, 99, 0.2);
    }

    .admin-service-heading::after {
        position: absolute;
        bottom: -1px;
        left: 0.25rem;
        width: 4.5rem;
        height: 2px;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--restaurant-terracotta), var(--restaurant-brass));
        content: "";
    }

    .admin-service-copy {
        min-width: 0;
    }

    .admin-service-eyebrow {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        margin: 0 0 0.25rem;
        color: var(--restaurant-terracotta) !important;
        font-size: 0.64rem;
        font-weight: 900;
        letter-spacing: 0.2em;
        text-transform: uppercase;
    }

    .admin-service-eyebrow span {
        display: inline-block;
        width: 1.35rem;
        height: 1px;
        background: currentColor;
    }

    .admin-service-heading h1 {
        margin: 0;
        color: var(--restaurant-ink) !important;
        font-size: clamp(2.15rem, 4vw, 3.4rem);
        font-weight: 700;
        line-height: 0.96;
    }

    .admin-service-copy > p:last-child {
        margin: 0.6rem 0 0;
        color: var(--restaurant-muted) !important;
        font-size: 0.88rem;
        font-weight: 500;
    }

    .admin-service-meta {
        display: grid;
        min-width: 8.6rem;
        grid-template-columns: auto auto;
        align-items: baseline;
        gap: 0.05rem 0.45rem;
        border-left: 1px solid var(--restaurant-border);
        padding-left: 1.25rem;
        text-align: right;
    }

    .admin-service-day {
        grid-column: 1 / -1;
        color: var(--restaurant-olive);
        font-size: 0.63rem;
        font-weight: 900;
        letter-spacing: 0.13em;
        text-transform: uppercase;
    }

    .admin-service-meta strong {
        grid-column: 1 / -1;
        color: var(--restaurant-ink);
        font-family: "Cormorant Garamond", Georgia, serif;
        font-size: 1.45rem;
        line-height: 1;
    }

    .admin-service-meta small {
        grid-column: 1 / -1;
        margin-top: 0.2rem;
        color: var(--restaurant-muted);
        font-size: 0.62rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .glass,
    [class*="glass"] {
        border: 1px solid rgba(207, 190, 168, 0.72) !important;
        border-radius: 1.15rem !important;
        background: rgba(255, 253, 248, 0.94) !important;
        box-shadow:
            0 1px 0 rgba(255, 255, 255, 0.85) inset,
            0 12px 34px -26px rgba(57, 38, 25, 0.5) !important;
    }

    .hover-lift:hover,
    .glass:hover {
        border-color: rgba(184, 79, 50, 0.28) !important;
        box-shadow:
            0 1px 0 rgba(255, 255, 255, 0.9) inset,
            0 16px 36px -25px rgba(73, 42, 25, 0.55) !important;
    }

    input,
    select,
    textarea {
        border-color: #dacbb7 !important;
        border-radius: 0.8rem !important;
        background: #fffefa !important;
        color: var(--restaurant-ink) !important;
        box-shadow: 0 1px 0 rgba(255, 255, 255, 0.9) inset !important;
    }

    input::placeholder,
    textarea::placeholder {
        color: #aa9c8e !important;
    }

    input:focus,
    select:focus,
    textarea:focus {
        border-color: var(--restaurant-terracotta) !important;
        background: #fff !important;
        box-shadow: 0 0 0 4px rgba(184, 79, 50, 0.11) !important;
    }

    label,
    .text-xs.text-gray-400 {
        color: #66594e !important;
        letter-spacing: 0.08em !important;
    }

    .btn-accent,
    .btn-reservation,
    a.btn-accent,
    a.btn-reservation,
    button.bg-indigo-600,
    .bg-indigo-600 {
        border-color: var(--restaurant-terracotta) !important;
        background: linear-gradient(135deg, #c35b3d, var(--restaurant-terracotta)) !important;
        box-shadow: 0 8px 18px -10px rgba(132, 53, 31, 0.75) !important;
    }

    .btn-accent:hover,
    .btn-reservation:hover:not(:disabled),
    a.btn-accent:hover,
    button.bg-indigo-600:hover,
    .hover\:bg-indigo-700:hover {
        border-color: var(--restaurant-espresso) !important;
        background: var(--restaurant-espresso) !important;
        box-shadow: 0 10px 22px -12px rgba(39, 28, 22, 0.8) !important;
    }

    .text-indigo-600,
    .text-indigo-700,
    [class*="text-indigo-"] {
        color: var(--restaurant-terracotta) !important;
    }

    .bg-indigo-50,
    [class*="bg-indigo-50"] {
        border-color: #e7c8b9 !important;
        background: #fbede7 !important;
    }

    .border-indigo-100,
    [class*="border-indigo-100"] {
        border-color: #e7c8b9 !important;
    }

    .bg-emerald-600,
    button.bg-emerald-600,
    .hover\:bg-emerald-700:hover {
        border-color: var(--restaurant-olive) !important;
        background: var(--restaurant-olive) !important;
    }

    th {
        border-color: var(--restaurant-border) !important;
        background: #f0e7db !important;
        color: #67594d !important;
        letter-spacing: 0.09em !important;
    }

    td {
        border-color: #eee4d7 !important;
        color: #554a40 !important;
    }

    tbody tr:hover td {
        background: #fff9f1 !important;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb,
    .kitchen-scrollbar::-webkit-scrollbar-thumb {
        background: #cdbba4;
    }

    nav.admin-navigation.fixed {
        top: 1rem !important;
    }

    nav.admin-navigation.fixed > .glass {
        width: min(calc(100% - 2.5rem), 72rem);
        min-height: 4.1rem;
        margin-inline: auto;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 1.35rem !important;
        background:
            linear-gradient(135deg, rgba(53, 36, 26, 0.98), rgba(34, 24, 19, 0.99)) !important;
        box-shadow: 0 18px 45px -20px rgba(35, 22, 14, 0.68) !important;
    }

    nav.admin-navigation .admin-navigation-shell {
        justify-content: space-between !important;
        gap: clamp(0.75rem, 1.8vw, 1.5rem);
        padding: 0.55rem clamp(0.75rem, 1.5vw, 1.2rem) !important;
    }

    .admin-brand-lockup {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        text-decoration: none;
    }

    .admin-brand-lockup img {
        width: 2.35rem !important;
        height: 2.35rem !important;
        border-color: rgba(255, 255, 255, 0.18) !important;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25) !important;
    }

    .admin-brand-lockup > span {
        display: flex;
        flex-direction: column;
        line-height: 1;
    }

    .admin-brand-lockup strong {
        color: #fff9ed;
        font-family: "Cormorant Garamond", Georgia, serif;
        font-size: 1rem;
        letter-spacing: 0.08em;
    }

    .admin-brand-lockup small {
        margin-top: 0.25rem;
        color: #cbbcae;
        font-size: 0.48rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    nav.admin-navigation .bottom-primary-links {
        flex: 1 1 auto;
        min-width: 0;
        justify-content: flex-end !important;
        gap: clamp(0.25rem, 0.8vw, 0.7rem) !important;
    }

    nav.admin-navigation .bottom-quick-link {
        position: relative;
        min-width: clamp(3.35rem, 4.8vw, 4.2rem);
        min-height: 3rem;
        padding: 0.35rem 0.45rem;
        border-radius: 0.8rem !important;
        color: #cdbfb2 !important;
    }

    nav.admin-navigation .bottom-quick-link svg {
        color: currentColor !important;
    }

    nav.admin-navigation .bottom-quick-link:hover {
        background: rgba(255, 255, 255, 0.07) !important;
        color: #fff8ea !important;
    }

    nav.admin-navigation .bottom-quick-link.active {
        background: rgba(196, 154, 74, 0.16) !important;
        color: #f5d493 !important;
        box-shadow: 0 0 0 1px rgba(196, 154, 74, 0.2) inset !important;
    }

    nav.admin-navigation .nav-profile-menu > .bottom-quick-link {
        border: 1px solid rgba(245, 212, 147, 0.2);
        background: rgba(255, 255, 255, 0.08) !important;
        color: #fff1d2 !important;
    }

    nav.admin-navigation .nav-profile-menu > .bottom-quick-link:hover,
    nav.admin-navigation .nav-profile-menu > .bottom-quick-link.active {
        border-color: rgba(245, 212, 147, 0.45);
        background: rgba(196, 154, 74, 0.2) !important;
        color: #ffe2a5 !important;
    }

    body nav.admin-navigation .profile-menu-trigger,
    body nav.admin-navigation .profile-menu-trigger-label,
    body nav.admin-navigation .profile-menu-trigger-icon {
        color: #fff1d2 !important;
        opacity: 1 !important;
    }

    body nav.admin-navigation .profile-menu-trigger {
        background-color: rgba(255, 255, 255, 0.09) !important;
    }

    body nav.admin-navigation .profile-menu-trigger:hover,
    body nav.admin-navigation .profile-menu-trigger.active {
        color: #ffe2a5 !important;
        background-color: rgba(196, 154, 74, 0.22) !important;
    }

    .profile-dropdown { padding: 0.45rem !important; }
    .profile-dropdown-link {
        color: #49382d !important;
        background: transparent !important;
    }
    .profile-dropdown-link svg { color: #8b7566 !important; }
    .profile-dropdown-link:hover,
    .profile-dropdown-link.active {
        color: #9f482f !important;
        background: #fff4e5 !important;
    }
    .profile-dropdown-link:hover svg,
    .profile-dropdown-link.active svg { color: #b95738 !important; }
    button.profile-dropdown-logout {
        color: #b42318 !important;
        background: #fff1ef !important;
    }
    button.profile-dropdown-logout svg { color: #c73c30 !important; }
    button.profile-dropdown-logout:hover { color: #8f1f16 !important; background: #ffe4df !important; }

    .nav-more-count {
        position: absolute;
        top: 0.3rem;
        right: 0.35rem;
        display: grid !important;
        width: 1.05rem;
        height: 1.05rem;
        place-items: center;
        border: 1px solid rgba(245, 212, 147, 0.2);
        border-radius: 999px;
        background: rgba(196, 154, 74, 0.14);
        color: #f5d493;
        font-size: 0.55rem !important;
        line-height: 1;
    }

    .nav-more-dropdown {
        position: absolute;
        top: calc(100% + 0.85rem);
        right: 0;
        width: min(24rem, calc(100vw - 2rem));
        padding: 0.7rem;
        border: 1px solid var(--restaurant-border);
        border-radius: 1.1rem;
        background: var(--restaurant-paper);
        transform-origin: top right;
        box-shadow: 0 24px 55px -24px rgba(39, 28, 22, 0.5);
    }

    .nav-more-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.25rem 0.35rem 0.7rem;
        color: #392c24;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .nav-more-heading small { color: #9a8779; font-size: 0.6rem; }
    .nav-more-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.4rem; }
    .nav-more-item {
        display: flex;
        min-width: 0;
        align-items: center;
        gap: 0.6rem;
        padding: 0.65rem;
        border: 1px solid transparent;
        border-radius: 0.8rem;
        color: #6f5d50;
        font-size: 0.76rem;
        font-weight: 700;
        transition: 160ms ease;
    }
    .nav-more-item:hover, .nav-more-item.active { border-color: #e5d7c5; background: #fff8ee; color: #a84e33; }
    .nav-more-icon { display: grid; width: 1.9rem; height: 1.9rem; flex: none; place-items: center; border-radius: 0.6rem; background: #f5eadb; color: #9a6047; }
    .nav-more-icon svg { width: 0.95rem; height: 0.95rem; }
    .nav-more-arrow { width: 0.75rem; height: 0.75rem; margin-left: auto; color: #baa99a; }

    nav.admin-navigation .dropdown-menu-container {
        border-color: var(--restaurant-border) !important;
        background: var(--restaurant-paper) !important;
        box-shadow: 0 20px 45px -20px rgba(39, 28, 22, 0.45) !important;
    }

    .status-confirmed {
        border-color: rgba(95, 112, 79, 0.25);
        background: rgba(95, 112, 79, 0.1);
        color: #4d603f;
    }

    .status-completed {
        border-color: rgba(77, 104, 111, 0.23);
        background: rgba(77, 104, 111, 0.09);
        color: #456269;
    }

    .kpi-income {
        border-color: rgba(95, 112, 79, 0.24) !important;
        background: linear-gradient(145deg, #fffdf8, #f1f5eb) !important;
    }

    .kpi-expense {
        border-color: rgba(184, 79, 50, 0.2) !important;
        background: linear-gradient(145deg, #fffdf8, #fbede7) !important;
    }

    .kpi-profit {
        border-color: rgba(196, 154, 74, 0.25) !important;
        background: linear-gradient(145deg, #fffdf8, #fbf4e3) !important;
    }

    nav[role="navigation"] .shadow-sm.rounded-md [aria-current="page"] span,
    nav[role="navigation"] .shadow-sm.rounded-md [aria-current="page"] a {
        border-color: var(--restaurant-terracotta) !important;
        background: var(--restaurant-terracotta) !important;
        box-shadow: 0 5px 12px rgba(132, 53, 31, 0.2) !important;
    }

    .service-pulse-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.85rem;
        margin-bottom: 1.5rem;
    }

    .service-pulse-grid-compact {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .service-pulse-card {
        position: relative;
        display: flex;
        min-width: 0;
        align-items: center;
        gap: 0.85rem;
        overflow: hidden;
        border: 1px solid var(--restaurant-border);
        border-radius: 1rem;
        padding: 1rem;
        background: rgba(255, 253, 248, 0.9);
        box-shadow: 0 12px 26px -24px rgba(57, 38, 25, 0.58);
    }

    .service-pulse-card::after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 3px;
        background: currentColor;
        content: "";
        opacity: 0.7;
    }

    .service-pulse-icon {
        display: grid;
        width: 2.65rem;
        height: 2.65rem;
        flex: 0 0 auto;
        place-items: center;
        border-radius: 0.85rem;
        background: currentColor;
    }

    .service-pulse-icon svg {
        width: 1.3rem;
        height: 1.3rem;
        color: #fffaf2 !important;
    }

    .service-pulse-card > div:last-child {
        display: flex;
        min-width: 0;
        flex-direction: column;
    }

    .service-pulse-card span {
        color: var(--restaurant-muted);
        font-size: 0.59rem;
        font-weight: 900;
        letter-spacing: 0.11em;
        text-transform: uppercase;
    }

    .service-pulse-card strong {
        overflow: hidden;
        margin-top: 0.12rem;
        color: var(--restaurant-ink);
        font-family: "Cormorant Garamond", Georgia, serif;
        font-size: 1.65rem;
        line-height: 1;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .service-pulse-card small {
        overflow: hidden;
        margin-top: 0.28rem;
        color: var(--restaurant-muted);
        font-size: 0.62rem;
        font-weight: 600;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .pulse-reservations {
        color: var(--restaurant-terracotta);
    }

    .pulse-open,
    .pulse-balance {
        color: var(--restaurant-olive);
    }

    .pulse-sales {
        color: var(--restaurant-brass);
    }

    .pulse-closed {
        color: #a13e37;
    }

    @media (min-width: 1024px) {
        .admin-main {
            padding-top: 6.45rem !important;
        }
    }

    @media (max-width: 1023px) {
        .mobile-admin-header {
            border-color: rgba(207, 190, 168, 0.75) !important;
            background: rgba(255, 253, 248, 0.96) !important;
            box-shadow: 0 8px 24px -18px rgba(57, 38, 25, 0.55) !important;
        }

        .mobile-admin-brand strong,
        .mobile-admin-avatar {
            color: var(--restaurant-ink) !important;
        }

        .mobile-admin-brand small {
            color: var(--restaurant-terracotta) !important;
        }

        .mobile-admin-avatar {
            border-color: var(--restaurant-border);
            background: #fffaf2;
        }

        nav.admin-navigation.fixed {
            top: auto !important;
        }

        nav.admin-navigation.fixed .glass {
            gap: 0 !important;
            padding: 0.15rem 0.3rem !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            background: rgba(39, 28, 22, 0.97) !important;
            box-shadow: 0 18px 42px -18px rgba(39, 28, 22, 0.7) !important;
        }

        nav.admin-navigation .nav-more-dropdown {
            top: auto !important;
            right: 0 !important;
            bottom: calc(100% + 0.75rem) !important;
            width: min(22rem, calc(100vw - 1.5rem));
            max-height: min(70vh, 30rem);
            overflow-y: auto;
            transform-origin: bottom right;
        }

        nav.admin-navigation .bottom-quick-link {
            min-width: 0;
            color: #cdbfb2 !important;
        }

        nav.admin-navigation .bottom-quick-link.active {
            color: #f5d493 !important;
        }

        .admin-service-heading {
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.35rem;
            padding-bottom: 1rem;
        }

        .admin-service-heading h1 {
            font-size: clamp(1.9rem, 9vw, 2.5rem);
        }

        .admin-service-copy > p:last-child {
            max-width: 24rem;
            font-size: 0.78rem;
        }

        .admin-service-meta {
            min-width: 5.2rem;
            padding-left: 0.8rem;
        }

        .admin-service-day,
        .admin-service-meta small {
            font-size: 0.52rem;
        }

        .admin-service-meta strong {
            font-size: 1.2rem;
        }

        .service-pulse-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 520px) {
        .admin-main {
            padding-inline: 0.9rem !important;
        }

        .admin-service-copy > p:last-child,
        .admin-service-meta small {
            display: none;
        }

        .admin-service-heading {
            align-items: flex-end;
        }

        .admin-service-heading h1 {
            font-size: 2rem;
        }

        .glass,
        [class*="glass"] {
            border-radius: 1rem !important;
        }

        .service-pulse-grid {
            grid-template-columns: 1fr 1fr;
            gap: 0.65rem;
        }

        .service-pulse-card {
            align-items: flex-start;
            gap: 0.6rem;
            padding: 0.8rem;
        }

        .service-pulse-icon {
            width: 2.1rem;
            height: 2.1rem;
            border-radius: 0.7rem;
        }

        .service-pulse-icon svg {
            width: 1rem;
            height: 1rem;
        }

        .service-pulse-card strong {
            font-size: 1.25rem;
        }

        .service-pulse-card small {
            display: none;
        }
    }

    /*
     * One navigation model on every device.
     * Large screens use the same bottom dock as mobile, with roomier targets.
     */
    .desktop-secondary-link {
        display: none !important;
    }

    @media (min-width: 1024px) {
        .mobile-admin-header {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 51;
            display: flex;
            min-height: 4.25rem;
            align-items: center;
            justify-content: space-between;
            padding: 0 max(2rem, calc((100vw - 90rem) / 2));
            border-bottom: 1px solid rgba(207, 190, 168, 0.72);
            background: rgba(255, 253, 248, 0.96);
            box-shadow: 0 8px 28px -22px rgba(57, 38, 25, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .mobile-admin-brand {
            display: flex;
            min-width: 0;
            align-items: center;
            gap: 0.7rem;
            color: var(--restaurant-ink);
            text-decoration: none;
        }

        .mobile-admin-brand img {
            width: 2.35rem;
            height: 2.35rem;
            flex: none;
            border: 1px solid var(--restaurant-border);
            border-radius: 0.75rem;
            background: #fffaf2;
            object-fit: cover;
            box-shadow: 0 5px 14px rgba(57, 38, 25, 0.1);
        }

        .mobile-admin-brand > span {
            display: flex;
            min-width: 0;
            flex-direction: column;
            line-height: 1.05;
        }

        .mobile-admin-brand strong {
            overflow: hidden;
            color: var(--restaurant-ink);
            font-size: 0.82rem;
            font-weight: 900;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .mobile-admin-brand small {
            margin-top: 0.28rem;
            color: var(--restaurant-terracotta);
            font-size: 0.52rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .mobile-profile-menu {
            position: relative;
            flex: none;
        }

        .mobile-admin-profile-button {
            display: inline-flex;
            min-height: 2.65rem;
            align-items: center;
            gap: 0.4rem;
            border: 1px solid var(--restaurant-border);
            border-radius: 999px;
            padding: 0.25rem 0.55rem 0.25rem 0.28rem;
            background: #fffaf2;
            color: var(--restaurant-muted);
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(57, 38, 25, 0.06);
        }

        .mobile-admin-profile-button > svg {
            width: 0.8rem;
            height: 0.8rem;
        }

        .mobile-admin-avatar {
            display: grid;
            width: 2rem;
            height: 2rem;
            place-items: center;
            border-radius: 999px;
            background: linear-gradient(135deg, #3a291f, #70442e);
            color: #fff7e9;
            font-size: 0.65rem;
            font-weight: 900;
        }

        .mobile-profile-dropdown {
            position: absolute;
            top: calc(100% + 0.65rem);
            right: 0;
            z-index: 60;
            width: 12rem;
            padding: 0.4rem;
            border: 1px solid var(--restaurant-border);
            border-radius: 0.9rem;
            background: #fffdf9;
            box-shadow: 0 18px 42px rgba(50, 32, 21, 0.18);
        }

        .mobile-profile-dropdown a,
        .mobile-profile-dropdown button {
            display: flex;
            width: 100%;
            min-height: 2.55rem;
            align-items: center;
            border: 0;
            border-radius: 0.65rem;
            padding: 0.55rem 0.7rem;
            background: transparent;
            color: #49392f;
            font-size: 0.72rem;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
        }

        .mobile-profile-dropdown a:hover {
            background: #f7ede3;
            color: var(--restaurant-terracotta);
        }

        .mobile-profile-dropdown form {
            display: block !important;
            margin-top: 0.25rem !important;
            padding-top: 0.35rem !important;
            border-top: 1px solid var(--restaurant-border) !important;
        }

        .mobile-profile-dropdown .mobile-profile-logout {
            display: flex !important;
            align-items: center !important;
            gap: 0.55rem !important;
            background: #fff1ee !important;
            color: #b6352b !important;
        }

        .mobile-profile-dropdown .mobile-profile-logout svg {
            width: 1rem;
            height: 1rem;
            flex: none;
        }

        .admin-main {
            padding-top: 6rem !important;
            padding-bottom: 5rem !important;
        }

        nav.admin-navigation.fixed {
            top: auto !important;
            right: 0 !important;
            bottom: max(0.45rem, env(safe-area-inset-bottom)) !important;
            left: 0 !important;
            width: 100% !important;
            pointer-events: none;
        }

        nav.admin-navigation.fixed > .glass {
            width: min(calc(100% - 2rem), 54rem) !important;
            min-height: 3rem !important;
            margin-inline: auto !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
            border-radius: 0.9rem !important;
            background: rgba(39, 28, 22, 0.97) !important;
            box-shadow: 0 22px 55px -20px rgba(35, 22, 14, 0.72) !important;
            backdrop-filter: blur(22px) !important;
            -webkit-backdrop-filter: blur(22px) !important;
            pointer-events: auto;
        }

        nav.admin-navigation .admin-navigation-shell {
            justify-content: center !important;
            gap: 0 !important;
            padding: 0.1rem 0.2rem !important;
        }

        nav.admin-navigation .admin-brand-lockup {
            display: none !important;
        }

        nav.admin-navigation .bottom-primary-links {
            width: 100% !important;
            max-width: none !important;
            flex: 1 1 auto !important;
            justify-content: space-between !important;
            gap: 0.1rem !important;
            margin: 0 !important;
            overflow-x: auto !important;
            scrollbar-width: none;
        }

        nav.admin-navigation .bottom-primary-links::-webkit-scrollbar {
            display: none;
        }

        nav.admin-navigation .bottom-primary-links > a,
        nav.admin-navigation .bottom-primary-links > div {
            min-width: 0 !important;
            flex: 1 1 0 !important;
        }

        nav.admin-navigation .bottom-quick-link {
            display: flex !important;
            width: 100% !important;
            min-width: 0 !important;
            min-height: 2.5rem !important;
            flex-direction: column !important;
            gap: 0.04rem !important;
            padding: 0.12rem 0.08rem !important;
            border-radius: 0.62rem !important;
        }

        nav.admin-navigation .desktop-secondary-link {
            display: flex !important;
        }

        nav.admin-navigation .nav-more-menu {
            display: none !important;
        }

        nav.admin-navigation .nav-profile-menu {
            display: none !important;
        }

        nav.admin-navigation .bottom-quick-link svg {
            width: 1.15rem !important;
            height: 1.15rem !important;
        }

        nav.admin-navigation .bottom-quick-link span,
        nav.admin-navigation .profile-menu-trigger-label {
            display: block !important;
            max-width: 100%;
            overflow: hidden;
            font-size: 0.56rem !important;
            font-weight: 800 !important;
            line-height: 1.1;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        nav.admin-navigation .nav-more-count {
            top: 0.2rem;
            right: 0.35rem;
        }

        nav.admin-navigation .dropdown-menu-container,
        nav.admin-navigation .nav-more-dropdown,
        nav.admin-navigation .profile-dropdown {
            top: auto !important;
            right: 0 !important;
            bottom: calc(100% + 0.75rem) !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            transform-origin: bottom right;
        }

        nav.admin-navigation .nav-more-dropdown {
            width: min(24rem, calc(100vw - 2rem));
            max-height: min(68vh, 32rem);
            overflow-y: auto;
        }
    }
</style>

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
    .btn-koya, .btn-reservation, .btn-danger,
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
        color: var(--koya) !important;
        background-color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02) !important;
    }
    .bottom-quick-link:hover svg {
        color: var(--koya) !important;
        transform: translateY(-0.5px);
    }
    .bottom-quick-link.active {
        color: #ffffff !important;
        background-color: var(--koya) !important;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2) !important;
    }
    .bottom-quick-link.active svg {
        color: #ffffff !important;
    }

    /* Hamburger / Menu buttons */
    button.bg-koya\/15, [class*="bg-koya/15"] {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 2.25rem !important;
        height: 2.25rem !important;
        border-radius: 10px !important;
        background-color: var(--koya-light) !important;
        color: var(--koya) !important;
        border: 1px solid rgba(79, 70, 229, 0.12) !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    button.bg-koya\/15:hover, [class*="bg-koya/15"]:hover {
        background-color: var(--koya) !important;
        color: #ffffff !important;
        border-color: var(--koya) !important;
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
        color: var(--koya) !important;
        background-color: var(--koya-light) !important;
        border-color: rgba(79, 70, 229, 0.05) !important;
    }
    .bottom-menu-item:hover svg {
        color: var(--koya) !important;
    }
    .bottom-menu-item.active {
        color: var(--koya) !important;
        background-color: var(--koya-light) !important;
        border-color: rgba(79, 70, 229, 0.1) !important;
        font-weight: 700 !important;
    }
    .bottom-menu-item.active svg {
        color: var(--koya) !important;
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
        color: var(--koya) !important;
        background-color: var(--koya-light) !important;
        border-color: rgba(79, 70, 229, 0.08) !important;
    }
    nav.fixed .relative button[title="Profile"].text-indigo-600 {
        color: var(--koya) !important;
        background-color: var(--koya-light) !important;
        border-color: rgba(79, 70, 229, 0.15) !important;
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
        background-color: var(--koya-light) !important;
        color: var(--koya) !important;
    }
    .dropdown-menu-container a.text-indigo-600 {
        color: var(--koya) !important;
        background-color: var(--koya-light) !important;
    }
    .dropdown-menu-container a:hover svg {
        color: var(--koya) !important;
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
    .fixed.inset-0.z-\[100\] .glass.max-w-2xl {
        background: #ffffff !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        border-radius: 24px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04), 0 0 0 1px rgba(0, 0, 0, 0.02) !important;
        padding: 1rem !important;
        max-width: 38rem !important;
        width: 100% !important;
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
        border-color: var(--koya) !important;
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
        color: #4f46e5 !important; /* var(--koya) Brand color */
        border-color: #cbd5e1 !important;
        transform: translateY(-1px);
    }

    /* Active Page button */
    nav[role="navigation"] .shadow-sm.rounded-md [aria-current="page"] span,
    nav[role="navigation"] .shadow-sm.rounded-md [aria-current="page"] a {
        background-color: #4f46e5 !important; /* var(--koya) Brand color */
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
</style>

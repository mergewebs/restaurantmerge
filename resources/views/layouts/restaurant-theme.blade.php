<style>
    :root {
        --koya: #c89b3c;
        --koya-dark: #17110c;
        --koya-panel: rgba(35, 27, 21, 0.82);
        --koya-panel-solid: #211915;
        --koya-border: rgba(244, 222, 183, 0.14);
        --koya-cream: #fff7eb;
        --koya-muted: rgba(255, 247, 235, 0.66);
        --koya-burgundy: #7c2f28;
        --koya-olive: #657447;
        --koya-shadow: 0 22px 70px -34px rgba(0, 0, 0, 0.88);
    }

    html {
        background: var(--koya-dark);
    }

    body {
        background:
            radial-gradient(circle at 12% 8%, rgba(200, 155, 60, 0.18), transparent 28rem),
            radial-gradient(circle at 92% 18%, rgba(124, 47, 40, 0.15), transparent 26rem),
            linear-gradient(145deg, #120d09 0%, #1e1712 48%, #100c09 100%) !important;
        color: var(--koya-cream) !important;
        font-family: 'Outfit', sans-serif !important;
        letter-spacing: 0;
    }

    body::before {
        content: '';
        position: fixed;
        inset: 0;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(255,255,255,0.026) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.018) 1px, transparent 1px);
        background-size: 44px 44px;
        mask-image: linear-gradient(to bottom, rgba(0,0,0,0.65), transparent 68%);
        z-index: -1;
    }

    h1, h2, h3, .font-black {
        letter-spacing: 0 !important;
    }

    .font-display, h1, h2 {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 700;
    }

    .glass {
        background:
            linear-gradient(180deg, rgba(48, 37, 29, 0.9), rgba(29, 22, 18, 0.86)) !important;
        border: 1px solid var(--koya-border) !important;
        box-shadow: var(--koya-shadow);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
    }

    .btn-koya,
    .btn-reservation {
        background: linear-gradient(135deg, #d9b45f 0%, #b98627 100%) !important;
        color: #17110c !important;
        border: 1px solid rgba(255, 229, 174, 0.38) !important;
        border-radius: 12px !important;
        box-shadow: 0 16px 32px -22px rgba(217, 180, 95, 0.9) !important;
        font-weight: 800 !important;
        letter-spacing: 0.08em !important;
        min-height: 2.75rem;
    }

    .btn-koya:hover,
    .btn-reservation:hover:not(:disabled) {
        filter: brightness(1.04);
        transform: translateY(-1px);
    }

    input, select, textarea {
        min-height: 2.75rem;
        background: rgba(255, 247, 235, 0.075) !important;
        border: 1px solid rgba(244, 222, 183, 0.16) !important;
        border-radius: 12px !important;
        color: var(--koya-cream) !important;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.03) !important;
    }

    input::placeholder, textarea::placeholder {
        color: rgba(255, 247, 235, 0.38) !important;
    }

    input:focus, select:focus, textarea:focus {
        border-color: rgba(217, 180, 95, 0.76) !important;
        box-shadow: 0 0 0 4px rgba(217, 180, 95, 0.14) !important;
        background: rgba(255, 247, 235, 0.1) !important;
        outline: none !important;
    }

    select option {
        background: #211915;
        color: var(--koya-cream);
    }

    label {
        color: rgba(255, 247, 235, 0.68) !important;
        letter-spacing: 0.11em !important;
    }

    .glass .text-gray-900,
    .glass .text-gray-800,
    .glass .text-gray-700 {
        color: var(--koya-cream) !important;
    }

    .glass .text-gray-600,
    .glass .text-gray-500,
    .glass .text-gray-400,
    .glass .text-gray-300 {
        color: var(--koya-muted) !important;
    }

    .glass .border-gray-100,
    .glass .border-gray-200,
    .glass .border-gray-300 {
        border-color: var(--koya-border) !important;
    }

    .glass .bg-white {
        background-color: rgba(255, 247, 235, 0.08) !important;
    }

    table {
        border-collapse: separate;
        border-spacing: 0;
    }

    th {
        color: rgba(255, 247, 235, 0.58) !important;
        font-size: 0.68rem !important;
        letter-spacing: 0.11em !important;
        text-transform: uppercase;
    }

    td {
        border-color: rgba(244, 222, 183, 0.1) !important;
    }

    .text-koya, .bottom-nav-active {
        color: #d9b45f !important;
    }

    .bg-koya {
        background-color: #c89b3c !important;
    }

    .border-koya {
        border-color: #c89b3c !important;
    }

    .shadow-koya\/20 {
        --tw-shadow-color: rgba(200, 155, 60, 0.2) !important;
    }

    .bottom-menu-panel {
        background: linear-gradient(180deg, rgba(37, 28, 22, 0.98), rgba(24, 18, 14, 0.97)) !important;
        border-color: var(--koya-border) !important;
    }

    .bottom-menu-item,
    .bottom-quick-link {
        border-radius: 12px !important;
    }

    .bottom-quick-link.active {
        color: #d9b45f !important;
    }

    .rounded-3xl,
    .rounded-\[32px\],
    .rounded-\[40px\] {
        border-radius: 18px !important;
    }

    .rounded-2xl {
        border-radius: 14px !important;
    }

    @media (min-width: 768px) {
        main {
            max-width: 1500px;
            margin-inline: auto;
        }
    }

    @media (max-width: 640px) {
        main {
            padding-inline: 0.85rem !important;
            padding-top: 5.25rem !important;
        }

        .glass {
            box-shadow: 0 18px 46px -34px rgba(0, 0, 0, 0.9);
        }

        .grid-cols-2,
        .grid-cols-3 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .fixed.inset-0 .glass {
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
            padding: 1.15rem !important;
        }
    }
</style>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $restaurantName }} | Takeaway</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        koya: { DEFAULT: '#c89b3c' }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --koya: #c89b3c;
            --bg: #17110c;
        }
        body {
            background:
                radial-gradient(circle at 10% 0%, rgba(200,155,60,0.18), transparent 28rem),
                radial-gradient(circle at 100% 10%, rgba(124,47,40,0.16), transparent 26rem),
                linear-gradient(145deg, #120d09, #211915 52%, #100c09);
            color: #fff7eb;
            margin: 0;
            font-family: 'Outfit', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .glass {
            background: linear-gradient(180deg, rgba(48, 37, 29, 0.9), rgba(29, 22, 18, 0.86));
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(244, 222, 183, 0.14);
            box-shadow: 0 22px 70px -34px rgba(0, 0, 0, 0.88);
        }
        .btn-koya {
            background: linear-gradient(135deg, #d9b45f 0%, #b98627 100%);
            color: #17110c;
            border-radius: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.85rem 1.2rem;
            border: none;
        }
        input {
            width: 100%;
            color: #fff7eb !important;
            background: rgba(255, 247, 235, 0.075) !important;
            border: 1px solid rgba(244, 222, 183, 0.16) !important;
            border-radius: 12px !important;
            padding: 0.8rem 0.9rem !important;
            outline: none !important;
        }
        input:focus {
            border-color: rgba(217, 180, 95, 0.76) !important;
            background: rgba(255, 247, 235, 0.1) !important;
            box-shadow: 0 0 0 4px rgba(217, 180, 95, 0.14) !important;
        }
        .font-black { letter-spacing: 0 !important; }
        h1, h2 { font-family: 'Cormorant Garamond', serif; }
        .text-koya { color: #d9b45f !important; }
        .bg-koya { background-color: #c89b3c !important; }
        .rounded-3xl { border-radius: 18px !important; }
        .rounded-2xl { border-radius: 14px !important; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body>
    <div
        class="space-y-4 max-w-6xl mx-auto px-4 py-4"
        x-data="takeawayOrderPage({
            submitUrl: @js(route('takeaway.public.store')),
            csrfToken: @js(csrf_token()),
            menuItems: @js($menuItemsFlat),
            restaurantName: @js($restaurantName),
            brandLogoUrl: @js($brandLogoUrl)
        })"
        x-init="init()"
    >
        <div class="glass rounded-3xl p-4 border border-white/10">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0 flex items-center gap-3">
                    <template x-if="brandLogoUrl">
                        <img :src="brandLogoUrl" alt="Restaurant logo" class="h-12 w-28 object-contain shrink-0">
                    </template>
                    <div class="min-w-0">
                        <h1 class="text-xl font-black uppercase tracking-wide truncate" x-text="takeawayTitle()"></h1>
                        <p class="text-[11px] text-white/65 mt-0.5" x-text="takeawayCta()"></p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-white/50 uppercase tracking-widest">Total</p>
                    <p class="text-sm font-black text-koya" x-text="currency(total())"></p>
                </div>
            </div>
            <p class="mt-2 text-[11px] text-amber-300" x-show="offline" x-text="offlineLabel()"></p>
            <p class="mt-2 text-[11px] text-red-300" x-show="errorMessage" x-text="errorMessage"></p>
            <p class="mt-2 text-[11px] text-emerald-300" x-show="successMessage" x-text="successMessage"></p>
        </div>

        <div class="grid grid-cols-1 gap-4 pb-24">
            <div
                x-show="showOrderPanel"
                x-cloak
                class="fixed inset-0 z-[84] bg-black/70"
                x-transition.opacity
                @click="showOrderPanel = false"
            ></div>
            <div
                class="fixed inset-y-0 right-0 z-[85] w-full max-w-md h-screen glass border-l border-white/10 p-4 overflow-y-auto flex flex-col"
                x-show="showOrderPanel"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-8"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 translate-x-8"
            >
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-black">Your Takeaway</h2>
                    <button type="button" class="px-2.5 py-1.5 rounded-lg border border-white/15 bg-white/5 text-white/70 text-[10px] uppercase font-black" @click="showOrderPanel = false">Close</button>
                </div>

                <div class="mt-3 p-3 rounded-2xl bg-koya/10 border border-koya/20 flex items-center justify-between shrink-0">
                    <span class="text-xs font-black uppercase tracking-widest text-white/80">Total</span>
                    <span class="text-xl font-black text-koya" x-text="currency(total())"></span>
                </div>

                <div class="mt-3 space-y-2 flex-1 overflow-y-auto pr-1">
                    <template x-for="item in cart" :key="item.id">
                        <div class="rounded-xl bg-white/5 border border-white/10 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <p class="text-[13px] font-black text-white break-words" x-text="item.name"></p>
                                    <p class="text-[11px] text-white/60">
                                        <span x-text="item.quantity"></span>
                                        <span>x</span>
                                        <span x-text="currency(item.price)"></span>
                                        <span class="mx-1">&middot;</span>
                                        <span class="text-koya font-black" x-text="currency(item.quantity * item.price)"></span>
                                    </p>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <button type="button" class="w-8 h-8 rounded-lg border border-white/15 bg-white/5 text-white font-black" @click="decreaseQty(item.id)">-</button>
                                    <div class="w-8 h-8 rounded-lg border border-koya/30 bg-koya/15 text-koya text-center text-sm font-black leading-8" x-text="item.quantity"></div>
                                    <button type="button" class="w-8 h-8 rounded-lg border border-white/15 bg-white/5 text-white font-black" @click="increaseQty(item.id)">+</button>
                                    <button type="button" class="ml-1 w-8 h-8 rounded-lg border border-red-500/25 bg-red-500/10 text-red-400 text-base leading-none" @click="removeItem(item.id)" title="Remove item" aria-label="Remove item">&times;</button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <p x-show="cart.length === 0" class="text-xs text-white/50">No items yet. Tap menu items to start ordering.</p>
                </div>

                <div class="mt-3 space-y-2 border-t border-white/10 pt-3">
                    <div>
                        <label class="text-[10px] uppercase tracking-widest text-white/55 font-black">Customer Name</label>
                        <input type="text" x-model="customerName" @input="persistState()" placeholder="Your name">
                    </div>
                    <div>
                        <label class="text-[10px] uppercase tracking-widest text-white/55 font-black">Phone (Optional)</label>
                        <input type="tel" x-model="customerPhone" @input="persistState()" placeholder="+34...">
                    </div>
                    <button
                        type="button"
                        class="btn-koya w-full text-[11px]"
                        :disabled="isSubmitting || cart.length === 0 || !customerName.trim() || offline"
                        @click="submitOrder()"
                    >
                        <span x-show="!isSubmitting" x-text="submitLabel()"></span>
                        <span x-show="isSubmitting" x-text="submittingLabel()"></span>
                    </button>
                </div>
            </div>

            <div class="glass rounded-3xl border border-white/10 p-4 space-y-4">
                <template x-for="category in categories()" :key="category">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-koya mb-2" x-text="category"></p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <template x-for="item in itemsByCategory(category)" :key="item.id">
                                <button
                                    type="button"
                                    class="w-full text-left rounded-2xl border border-white/10 bg-white/5 px-3 py-2 active:scale-95 transition-transform"
                                    @click="addItem(item)"
                                >
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-[12px] font-black text-white truncate" x-text="item.name"></p>
                                        <p class="text-[12px] text-koya font-black whitespace-nowrap" x-text="currency(item.price)"></p>
                                    </div>
                                    <p x-show="item.is_package" class="mt-1 text-[9px] text-amber-300 font-bold uppercase tracking-wider">Package</p>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="fixed bottom-4 right-4 z-[70] pointer-events-none">
            <button
                type="button"
                @click="showOrderPanel = true"
                class="pointer-events-auto relative w-16 h-16 rounded-full border border-koya/40 bg-koya text-white shadow-2xl shadow-black/50 flex items-center justify-center active:scale-95 transition-transform"
                title="Current Order"
                aria-label="Open current order"
            >
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M9 5h6M7 8h10M8 12h8M6 3h12a2 2 0 012 2v14l-3-2-3 2-3-2-3 2V5a2 2 0 012-2z"></path>
                </svg>
                <span class="absolute -top-1 -right-1 min-w-[1.35rem] h-5 px-1 rounded-full bg-white text-koya text-[10px] font-black leading-5 text-center" x-text="itemCount()"></span>
            </button>
        </div>

        <div
            class="fixed left-1/2 -translate-x-1/2 bottom-24 z-[90] pointer-events-none"
            x-show="toast.visible"
            x-cloak
            x-transition:enter="transition ease-out duration-180"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-140"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-1 scale-95"
            aria-live="polite"
        >
            <div class="px-3 py-2 rounded-xl border border-emerald-400/25 bg-emerald-500/15 text-emerald-100 text-[11px] font-black uppercase tracking-widest shadow-xl shadow-black/40 whitespace-nowrap">
                <span x-text="toast.message"></span>
            </div>
        </div>
    </div>

    <script>
        function takeawayOrderPage(config) {
            return {
                submitUrl: config.submitUrl,
                csrfToken: config.csrfToken,
                menuItems: Array.isArray(config.menuItems) ? config.menuItems : [],
                restaurantName: config.restaurantName || 'Restaurant',
                brandLogoUrl: config.brandLogoUrl || '',
                i18n: {
                    takeawayTitle: @js(__('public.takeaway_title')),
                    takeawayCta: @js(__('public.takeaway_cta')),
                    offline: @js(__('public.takeaway_offline')),
                    submit: @js(__('public.takeaway_order_button')),
                    submitting: @js(__('public.takeaway_submitting')),
                },
                cart: [],
                showOrderPanel: false,
                customerName: '',
                customerPhone: '',
                isSubmitting: false,
                successMessage: '',
                errorMessage: '',
                offline: !navigator.onLine,
                storageKey: 'takeaway-public-cart-v2',
                toast: {
                    visible: false,
                    message: '',
                    timer: null,
                },

                init() {
                    this.loadState();
                    window.addEventListener('online', () => this.offline = false);
                    window.addEventListener('offline', () => this.offline = true);
                },

                categories() {
                    return [...new Set(this.menuItems.map((item) => item.category))];
                },

                takeawayTitle() {
                    return this.i18n.takeawayTitle || 'Take Away';
                },

                takeawayCta() {
                    return this.i18n.takeawayCta || '';
                },

                offlineLabel() {
                    return this.i18n.offline || '';
                },

                submitLabel() {
                    return this.i18n.submit || 'Submit';
                },

                submittingLabel() {
                    return this.i18n.submitting || 'Submitting...';
                },

                itemsByCategory(category) {
                    return this.menuItems.filter((item) => item.category === category);
                },

                addItem(menuItem) {
                    const existing = this.cart.find((item) => Number(item.id) === Number(menuItem.id));
                    if (existing) {
                        existing.quantity += 1;
                    } else {
                        this.cart.push({
                            id: Number(menuItem.id),
                            name: menuItem.name,
                            price: Number(menuItem.price || 0),
                            quantity: 1,
                            is_package: Boolean(menuItem.is_package),
                        });
                    }

                    this.showAddedToast(menuItem.name);
                    this.persistState();
                },

                increaseQty(itemId) {
                    const target = this.cart.find((item) => Number(item.id) === Number(itemId));
                    if (!target) return;
                    target.quantity += 1;
                    this.persistState();
                },

                decreaseQty(itemId) {
                    const target = this.cart.find((item) => Number(item.id) === Number(itemId));
                    if (!target) return;

                    target.quantity -= 1;
                    if (target.quantity < 1) {
                        this.removeItem(itemId);
                        return;
                    }

                    this.persistState();
                },

                removeItem(itemId) {
                    this.cart = this.cart.filter((item) => Number(item.id) !== Number(itemId));
                    this.persistState();
                },

                itemCount() {
                    return this.cart.reduce((sum, item) => sum + Number(item.quantity || 0), 0);
                },

                total() {
                    return this.cart.reduce((sum, item) => sum + (Number(item.price || 0) * Number(item.quantity || 0)), 0);
                },

                currency(value) {
                    const amount = Number(value || 0);
                    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(amount);
                },

                async submitOrder() {
                    if (this.isSubmitting) return;

                    this.isSubmitting = true;
                    this.errorMessage = '';
                    this.successMessage = '';

                    try {
                        const response = await fetch(this.submitUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                customer_name: this.customerName.trim(),
                                customer_phone: this.customerPhone.trim(),
                                items: this.cart.map((item) => ({
                                    menu_item_id: item.id,
                                    quantity: item.quantity,
                                })),
                            }),
                        });

                        const payload = await response.json();

                        if (!response.ok) {
                            throw new Error(payload.message || 'Could not submit your order right now.');
                        }

                        this.successMessage = payload.message || `Order #${payload.order_id} received.`;
                        this.cart = [];
                        this.customerName = '';
                        this.customerPhone = '';
                        this.showOrderPanel = false;
                        this.persistState();
                    } catch (error) {
                        this.errorMessage = error?.message || 'Could not submit your order right now.';
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                persistState() {
                    localStorage.setItem(this.storageKey, JSON.stringify({
                        cart: this.cart,
                        customerName: this.customerName,
                        customerPhone: this.customerPhone,
                    }));
                },

                loadState() {
                    try {
                        const raw = localStorage.getItem(this.storageKey);
                        if (!raw) return;
                        const parsed = JSON.parse(raw);
                        this.cart = Array.isArray(parsed.cart) ? parsed.cart : [];
                        this.customerName = typeof parsed.customerName === 'string' ? parsed.customerName : '';
                        this.customerPhone = typeof parsed.customerPhone === 'string' ? parsed.customerPhone : '';
                    } catch {
                        this.cart = [];
                    }
                },

                showAddedToast(itemName) {
                    const safeName = (itemName || 'Item').toString().trim();
                    this.toast.message = `${safeName} added`;
                    this.toast.visible = true;

                    if (this.toast.timer) {
                        clearTimeout(this.toast.timer);
                    }

                    this.toast.timer = setTimeout(() => {
                        this.toast.visible = false;
                    }, 1100);
                },
            };
        }
    </script>
</body>
</html>

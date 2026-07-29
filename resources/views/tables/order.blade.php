<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div
        class="space-y-4"
        x-data="waiterOrderPage({
            tableId: {{ $table->id }},
            tableNumber: {{ $table->number }},
            syncUrl: '{{ route('tables.order.sync', $table) }}',
            csrfToken: '{{ csrf_token() }}',
            initialOrder: @js($initialOrder),
            menuItems: @js($menuItemsFlat)
        })"
        x-init="init()"
    >
        <div class="glass rounded-3xl p-4 border border-white/10">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs text-white/50 uppercase tracking-widest">Order Page</p>
                    <h1 class="text-xl font-black uppercase tracking-wide">Table #{{ $table->number }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        @click="showCustomItemModal = true"
                        class="px-3 py-2 rounded-xl border border-accent/30 bg-accent/15 text-accent font-black uppercase text-[10px] tracking-widest"
                    >
                        Custom Item
                    </button>
                    <button
                        type="button"
                        onclick="window.print()"
                        class="px-3 py-2 rounded-xl border border-white/15 bg-white/5 text-white/80 font-black uppercase text-[10px] tracking-widest"
                    >
                        Print Order
                    </button>
                    <form method="POST" action="{{ route('tables.close-order', $table) }}" onsubmit="return confirm('Complete this order?');">
                        @csrf
                        <button
                            type="submit"
                            class="px-3 py-2 rounded-xl border border-emerald-500/40 bg-emerald-500/20 text-emerald-200 font-black uppercase text-[10px] tracking-widest"
                        >
                            Complete Order
                        </button>
                    </form>
                    <a href="{{ route('tables.index') }}" class="px-3 py-2 rounded-xl border border-white/15 bg-white/5 text-white/80 font-black uppercase text-[10px] tracking-widest">Back</a>
                </div>
            </div>
            <p class="mt-2 text-[11px] text-white/60" x-show="lastError" x-text="lastError"></p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 pb-24 lg:pb-4">
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
                    <h2 class="text-lg font-black">Current Order</h2>
                    <button type="button" class="px-2.5 py-1.5 rounded-lg border border-white/15 bg-white/5 text-white/70 text-[10px] uppercase font-black" @click="showOrderPanel = false">Close</button>
                </div>

                <div class="mt-3 p-3 rounded-2xl bg-accent/10 border border-accent/20 flex items-center justify-between shrink-0">
                    <span class="text-xs font-black uppercase tracking-widest text-white/80">Total</span>
                    <span class="text-xl font-black text-accent" x-text="currency(order.total)"></span>
                </div>

                <div class="mt-3 space-y-2 flex-1 overflow-y-auto pr-1">
                    <template x-for="item in order.items" :key="item.key">
                        <div class="rounded-xl bg-white/5 border border-white/10 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <p class="text-[13px] font-black text-white break-words" x-text="item.name"></p>
                                    <p class="text-[11px] text-white/60">
                                        <span x-text="item.quantity"></span>
                                        <span>x</span>
                                        <span x-text="currency(item.unit_price)"></span>
                                        <span class="mx-1">&middot;</span>
                                        <span class="text-accent font-black" x-text="currency(item.line_total)"></span>
                                    </p>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <button type="button" class="w-8 h-8 rounded-lg border border-white/15 bg-white/5 text-white font-black" @click="decreaseQty(item)">-</button>
                                    <div class="w-8 h-8 rounded-lg border border-accent/30 bg-accent/15 text-accent text-center text-sm font-black leading-8" x-text="item.quantity"></div>
                                    <button type="button" class="w-8 h-8 rounded-lg border border-white/15 bg-white/5 text-white font-black" @click="increaseQty(item)">+</button>
                                    <button type="button" class="ml-1 w-8 h-8 rounded-lg border border-red-500/25 bg-red-500/10 text-red-400 text-base leading-none" @click="removeItem(item)" title="Remove item" aria-label="Remove item">&times;</button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <p x-show="order.items.length === 0" class="text-xs text-white/50">No items yet. Tap menu items to start ordering.</p>
                </div>
            </div>

            <div class="lg:col-span-5 glass rounded-3xl border border-white/10 p-4 space-y-4">
                @forelse($menuItems as $category => $itemsInCategory)
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-accent mb-2">{{ $category }}</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach($itemsInCategory as $menuItem)
                                <button
                                    type="button"
                                    class="w-full text-left rounded-2xl border border-white/10 bg-white/5 px-3 py-2 active:scale-95 transition-transform"
                                    @click="addMenuItemById({{ $menuItem->id }})"
                                >
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-[12px] font-black text-white truncate">{{ $menuItem->name }}</p>
                                        <p class="text-[12px] text-accent font-black whitespace-nowrap">&euro;{{ number_format((float) $menuItem->price, 2) }}</p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-white/50">No active menu items found.</p>
                @endforelse
            </div>
        </div>

        <div
            x-show="showCustomItemModal"
            x-cloak
            class="restaurant-modal-wrap fixed inset-0 z-[80] flex items-end sm:items-center justify-center p-4"
            x-transition.opacity
        >
            <div class="restaurant-modal-shade absolute inset-0 bg-black/70" @click="showCustomItemModal = false"></div>
            <div class="restaurant-modal-card relative w-full max-w-lg glass rounded-3xl border border-white/10 p-4">
                <div class="restaurant-modal-head flex items-center justify-between gap-3">
                    <h3 class="text-sm font-black uppercase tracking-widest">Add Custom Item</h3>
                    <button type="button" class="px-2.5 py-1.5 rounded-lg border border-white/15 bg-white/5 text-white/70 text-[10px] uppercase font-black" @click="showCustomItemModal = false">Close</button>
                </div>

                <div class="mt-3 grid grid-cols-1 sm:grid-cols-4 gap-2">
                    <input type="text" x-model="custom.name" placeholder="Title" maxlength="100" class="!py-2.5 !px-3 !rounded-xl !text-base sm:col-span-2">
                    <input type="number" x-model.number="custom.unit_price" min="0" step="0.01" placeholder="Price" class="!py-2.5 !px-3 !rounded-xl !text-base">
                    <input type="number" x-model.number="custom.quantity" min="1" max="99" placeholder="Qty" class="!py-2.5 !px-3 !rounded-xl !text-base">
                    <button type="button" class="btn-accent !py-2.5 !rounded-xl !text-[10px] !tracking-widest sm:col-span-4" @click="addCustomItem()">Add Item</button>
                </div>
            </div>
        </div>

        <div class="fixed bottom-4 right-4 z-[70] pointer-events-none">
            <button
                type="button"
                @click="showOrderPanel = true"
                class="pointer-events-auto relative min-w-[6.25rem] h-14 px-4 rounded-2xl border border-accent/40 bg-accent text-white shadow-2xl shadow-black/50 flex items-center justify-center active:scale-95 transition-transform"
                title="Current Order"
                aria-label="Open current order"
            >
                <span class="text-[13px] font-black tracking-wide" x-text="currency(order.total)"></span>
                <span class="absolute -top-1 -right-1 min-w-[1.35rem] h-5 px-1 rounded-full bg-white text-accent text-[10px] font-black leading-5 text-center" x-text="itemCount()"></span>
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
        function waiterOrderPage(config) {
            return {
                tableId: config.tableId,
                tableNumber: config.tableNumber,
                syncUrl: config.syncUrl,
                csrfToken: config.csrfToken,
                menuItems: Array.isArray(config.menuItems) ? config.menuItems : [],
                initialOrder: config.initialOrder || { items: [], total: 0, id: null },
                order: { id: null, total: 0, items: [] },
                pendingActions: [],
                deviceId: null,
                online: navigator.onLine,
                isSyncing: false,
                showOrderPanel: false,
                showCustomItemModal: false,
                lastError: '',
                toast: {
                    visible: false,
                    message: '',
                    timer: null,
                },
                queueStorageKey: '',
                syncIntervalHandle: null,
                custom: {
                    name: '',
                    unit_price: null,
                    quantity: 1,
                },

                init() {
                    this.queueStorageKey = `waiter-order-queue-${this.tableId}`;
                    this.deviceId = this.getOrCreateDeviceId();
                    this.pendingActions = this.loadPendingQueue();
                    this.applyServerOrder(this.initialOrder);
                    this.replayPendingActions();

                    window.addEventListener('online', () => {
                        this.online = true;
                        this.syncNow();
                    });

                    window.addEventListener('offline', () => {
                        this.online = false;
                    });

                    this.syncIntervalHandle = setInterval(() => {
                        this.syncNow();
                    }, 3000);

                    if (this.pendingActions.length > 0) {
                        this.syncNow();
                    }
                },

                currency(value) {
                    const amount = Number(value || 0);
                    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(amount);
                },

                itemCount() {
                    return this.order.items.reduce((sum, item) => sum + Number(item.quantity || 0), 0);
                },

                addMenuItemById(menuItemId) {
                    const menuItem = this.menuItems.find((item) => Number(item.id) === Number(menuItemId));
                    if (!menuItem) {
                        this.lastError = 'Menu item not found.';
                        return;
                    }

                    this.addMenuItem(menuItem);
                },

                addMenuItem(menuItem) {
                    const target = this.findBestItemForMenuTap(menuItem);
                    this.showAddedToast(menuItem.name);

                    if (target) {
                        this.setItemQuantity(target, target.quantity + 1);
                        return;
                    }

                    const localRef = this.makeLocalRef();
                    const action = {
                        id: this.makeActionId(),
                        type: 'add_menu_item',
                        payload: {
                            menu_item_id: Number(menuItem.id),
                            quantity: 1,
                            local_ref: localRef,
                        },
                    };

                    this.applyOptimisticAction(action);
                    this.enqueueAction(action);
                },

                addCustomItem() {
                    const name = (this.custom.name || '').trim();
                    const quantity = Number(this.custom.quantity || 0);
                    const unitPrice = Number(this.custom.unit_price || 0);

                    if (!name || quantity < 1 || quantity > 99 || Number.isNaN(unitPrice) || unitPrice < 0) {
                        this.lastError = 'Custom item is invalid. Please check name, qty, and price.';
                        return;
                    }

                    const action = {
                        id: this.makeActionId(),
                        type: 'add_custom_item',
                        payload: {
                            local_ref: this.makeLocalRef(),
                            name,
                            quantity,
                            unit_price: unitPrice,
                        },
                    };

                    this.applyOptimisticAction(action);
                    this.enqueueAction(action);

                    this.custom = {
                        name: '',
                        unit_price: null,
                        quantity: 1,
                    };

                    this.showAddedToast(name);
                    this.showCustomItemModal = false;
                },

                increaseQty(item) {
                    this.setItemQuantity(item, item.quantity + 1);
                },

                decreaseQty(item) {
                    const nextQuantity = item.quantity - 1;

                    if (nextQuantity < 1) {
                        this.removeItem(item);
                        return;
                    }

                    this.setItemQuantity(item, nextQuantity);
                },

                setItemQuantity(item, quantity) {
                    const action = {
                        id: this.makeActionId(),
                        type: 'set_item_quantity',
                        payload: {
                            item_ref: this.itemRef(item),
                            quantity,
                        },
                    };

                    this.applyOptimisticAction(action);
                    this.enqueueAction(action);
                },

                removeItem(item) {
                    const action = {
                        id: this.makeActionId(),
                        type: 'remove_item',
                        payload: {
                            item_ref: this.itemRef(item),
                        },
                    };

                    this.applyOptimisticAction(action);
                    this.enqueueAction(action);
                },

                enqueueAction(action) {
                    this.pendingActions.push(action);
                    this.persistPendingQueue();
                    this.syncNow();
                },

                async syncNow() {
                    if (this.isSyncing || !this.online || this.pendingActions.length === 0) {
                        return;
                    }

                    this.isSyncing = true;
                    this.lastError = '';

                    const batch = this.pendingActions.slice(0, 50);

                    try {
                        const response = await fetch(this.syncUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                device_id: this.deviceId,
                                actions: batch,
                            }),
                        });

                        const payload = await response.json();

                        if (!response.ok) {
                            throw new Error(payload?.message || 'Sync failed');
                        }

                        const processed = Array.isArray(payload.processed_action_ids) ? payload.processed_action_ids : [];
                        const processedSet = new Set(processed);

                        this.pendingActions = this.pendingActions.filter((action) => !processedSet.has(action.id));
                        this.persistPendingQueue();

                        this.applyServerOrder(payload.order || { id: null, items: [], total: 0 });
                        this.replayPendingActions();

                        if (this.pendingActions.length > 0) {
                            setTimeout(() => this.syncNow(), 60);
                        }
                    } catch (error) {
                        this.lastError = this.online
                            ? 'Saved locally. Waiting to sync with server...'
                            : 'Offline mode: actions are queued locally.';
                    } finally {
                        this.isSyncing = false;
                    }
                },

                applyServerOrder(serverOrder) {
                    const items = Array.isArray(serverOrder.items) ? serverOrder.items : [];

                    this.order = {
                        id: serverOrder.id || null,
                        total: Number(serverOrder.total || 0),
                        items: items.map((item) => ({
                            key: `s:${item.id}`,
                            id: Number(item.id),
                            local_ref: null,
                            name: item.name,
                            quantity: Number(item.quantity || 0),
                            unit_price: Number(item.unit_price || 0),
                            line_total: Number(item.line_total || 0),
                        })),
                    };
                },

                replayPendingActions() {
                    this.pendingActions.forEach((action) => {
                        this.applyOptimisticAction(action);
                    });
                    this.recalculateOrderTotal();
                },

                applyOptimisticAction(action) {
                    const payload = action.payload || {};

                    if (action.type === 'add_menu_item') {
                        const localRef = payload.local_ref || this.makeLocalRef();
                        let target = this.findItemByRef(localRef);

                        if (!target) {
                            const menuItem = this.menuItems.find((item) => Number(item.id) === Number(payload.menu_item_id));
                            const name = menuItem ? menuItem.name : 'Menu Item';
                            const unitPrice = menuItem ? Number(menuItem.price) : 0;
                            const quantity = Number(payload.quantity || 1);

                            target = {
                                key: `l:${localRef}`,
                                id: null,
                                local_ref: localRef,
                                name,
                                quantity,
                                unit_price: unitPrice,
                                line_total: quantity * unitPrice,
                            };

                            this.order.items.push(target);
                        } else {
                            target.quantity += Number(payload.quantity || 1);
                            target.line_total = target.quantity * Number(target.unit_price || 0);
                        }
                    }

                    if (action.type === 'add_custom_item') {
                        const localRef = payload.local_ref || this.makeLocalRef();
                        const quantity = Number(payload.quantity || 1);
                        const unitPrice = Number(payload.unit_price || 0);

                        this.order.items.push({
                            key: `l:${localRef}`,
                            id: null,
                            local_ref: localRef,
                            name: payload.name || 'Custom Item',
                            quantity,
                            unit_price: unitPrice,
                            line_total: quantity * unitPrice,
                        });
                    }

                    if (action.type === 'set_item_quantity') {
                        const target = this.findItemByRef(payload.item_ref);
                        if (target) {
                            target.quantity = Math.max(1, Number(payload.quantity || 1));
                            target.line_total = target.quantity * Number(target.unit_price || 0);
                        }
                    }

                    if (action.type === 'remove_item') {
                        this.order.items = this.order.items.filter((item) => this.itemRef(item) !== payload.item_ref);
                    }

                    this.recalculateOrderTotal();
                },

                findBestItemForMenuTap(menuItem) {
                    return this.order.items.find((item) =>
                        item.name === menuItem.name
                        && Number(item.unit_price) === Number(menuItem.price)
                    );
                },

                findItemByRef(ref) {
                    return this.order.items.find((item) => this.itemRef(item) === ref) || null;
                },

                itemRef(item) {
                    if (item.id) {
                        return `s:${item.id}`;
                    }

                    return item.local_ref;
                },

                recalculateOrderTotal() {
                    this.order.total = this.order.items.reduce((sum, item) => {
                        return sum + Number(item.line_total || 0);
                    }, 0);
                },

                persistPendingQueue() {
                    localStorage.setItem(this.queueStorageKey, JSON.stringify(this.pendingActions));
                },

                loadPendingQueue() {
                    try {
                        const raw = localStorage.getItem(this.queueStorageKey);
                        if (!raw) {
                            return [];
                        }

                        const parsed = JSON.parse(raw);

                        return Array.isArray(parsed) ? parsed : [];
                    } catch {
                        return [];
                    }
                },

                getOrCreateDeviceId() {
                    const key = 'waiter-order-device-id';
                    const existing = localStorage.getItem(key);

                    if (existing) {
                        return existing;
                    }

                    const generated = this.makeLocalRef();
                    localStorage.setItem(key, generated);

                    return generated;
                },

                makeActionId() {
                    if (window.crypto && window.crypto.randomUUID) {
                        return window.crypto.randomUUID();
                    }

                    return `act-${Date.now()}-${Math.random().toString(16).slice(2)}`;
                },

                makeLocalRef() {
                    if (window.crypto && window.crypto.randomUUID) {
                        return `loc-${window.crypto.randomUUID()}`;
                    }

                    return `loc-${Date.now()}-${Math.random().toString(16).slice(2)}`;
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
</x-app-layout>


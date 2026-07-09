<x-app-layout>
    <style>
        @keyframes kitchenPulse {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 14px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .kitchen-pulse {
            animation: kitchenPulse 1.4s ease-out infinite;
        }
        .kitchen-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.22) transparent;
        }
        .kitchen-scrollbar::-webkit-scrollbar {
            width: 7px;
        }
        .kitchen-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.22);
            border-radius: 999px;
        }
    </style>
    <div data-page-feedback-wrapper>
        @if(session('success'))
            <div class="hidden" data-page-feedback data-feedback-level="success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="hidden" data-page-feedback data-feedback-level="error">{{ session('error') }}</div>
        @elseif($errors->any())
            <div class="hidden" data-page-feedback data-feedback-level="error">{{ $errors->first() }}</div>
        @endif
    </div>

    <div class="space-y-4 pb-4">

        <div class="sticky top-2 z-20 glass rounded-3xl p-4 border border-white/10 bg-gradient-to-r from-white/[0.06] via-white/[0.03] to-transparent backdrop-blur-xl">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black uppercase tracking-wide">Kitchen Board</h1>
                    <div class="mt-1 text-xs text-white/60 font-bold" id="kitchen-connection-state">
                        Connecting...
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('tables.index') }}" class="px-3 py-2 rounded-xl border border-white/15 bg-white/5 text-white/80 font-black uppercase text-[11px] tracking-wider">Tables</a>
                    <a href="{{ route('tables.completed') }}" class="px-3 py-2 rounded-xl border border-white/15 bg-white/5 text-white/80 font-black uppercase text-[11px] tracking-wider">Completed</a>
                </div>
            </div>
            <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                <div id="kitchen-filters" class="flex flex-wrap items-center gap-2">
                    <button type="button" data-filter="all" class="kitchen-filter-btn px-3 py-2 rounded-xl border text-xs font-black uppercase tracking-wider transition">All</button>
                    <button type="button" data-filter="pending" class="kitchen-filter-btn px-3 py-2 rounded-xl border text-xs font-black uppercase tracking-wider transition">Pending</button>
                    <button type="button" data-filter="in_preparation" class="kitchen-filter-btn px-3 py-2 rounded-xl border text-xs font-black uppercase tracking-wider transition">In Prep</button>
                    <button type="button" data-filter="ready" class="kitchen-filter-btn px-3 py-2 rounded-xl border text-xs font-black uppercase tracking-wider transition">Ready</button>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-[11px] font-black uppercase tracking-wider">
                    <span class="px-3 py-1.5 rounded-lg border border-white/20 bg-white/10 text-white/90">All <span data-count="all" class="text-koya">0</span></span>
                    <span class="px-3 py-1.5 rounded-lg border border-slate-200/30 bg-slate-300/20 text-slate-100">Pending <span data-count="pending">0</span></span>
                    <span class="px-3 py-1.5 rounded-lg border border-amber-300/40 bg-amber-400/20 text-amber-100">In Prep <span data-count="in_preparation">0</span></span>
                    <span class="px-3 py-1.5 rounded-lg border border-emerald-300/40 bg-emerald-500/20 text-emerald-100">Ready <span data-count="ready">0</span></span>
                </div>
            </div>
        </div>

        <div id="kitchen-new-order-alert" class="hidden glass rounded-2xl p-4 border border-emerald-400/30 bg-emerald-400/10 text-emerald-200 text-sm font-black uppercase tracking-wider"></div>

        <div id="kitchen-orders" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-3"></div>
    </div>

    <script>
        (() => {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const c = document.getElementById('kitchen-orders');
            const conn = document.getElementById('kitchen-connection-state');
            const alertBox = document.getElementById('kitchen-new-order-alert');
            const filterWrap = document.getElementById('kitchen-filters');
            const countNodes = {
                all: document.querySelector('[data-count="all"]'),
                pending: document.querySelector('[data-count="pending"]'),
                in_preparation: document.querySelector('[data-count="in_preparation"]'),
                ready: document.querySelector('[data-count="ready"]'),
            };
            const snapshotUrl = @json(route('tables.kitchen.snapshot'));
            const streamUrl = @json(route('tables.kitchen.stream'));
            const statusUrlTpl = @json(route('tables.orders.kitchen-status.update', ['order' => '__ORDER__']));
            const itemStatusUrlTpl = @json(route('tables.items.kitchen-status.update', ['item' => '__ITEM__']));
            const state = { version: Number(@json($initialKitchenVersion)), orders: new Map(), fp: new Map(), stream: null, reconnectTimer: null, pollTimer: null, optimistic: new Set(), filter: 'all' };
            const statusRank = { pending: 0, in_preparation: 0, ready: 1 };
            const escapeHtml = (v) => String(v).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
            const ensureNum = (v, d = 0) => Number.isFinite(Number(v)) ? Number(v) : d;
            const orderStatusLabel = (v) => v === 'ready' ? 'Ready' : (v === 'in_preparation' ? 'In Preparation' : 'Pending');
            const orderTone = (status) => {
                if (status === 'ready') return 'border-emerald-400/50 bg-emerald-500/10';
                if (status === 'in_preparation') return 'border-amber-400/50 bg-amber-400/10';
                return 'border-white/10 bg-white/[0.03]';
            };
            const orderChipTone = (status) => {
                if (status === 'ready') return 'bg-emerald-500/20 text-emerald-200 border border-emerald-400/40';
                if (status === 'in_preparation') return 'bg-amber-400/20 text-amber-100 border border-amber-300/40';
                return 'bg-white/10 text-white/80 border border-white/20';
            };
            const statusButtonTone = (buttonStatus, activeStatus) => {
                if (buttonStatus !== activeStatus) return 'border-white/20 bg-white/5 text-white/80 hover:bg-white/10';
                if (buttonStatus === 'ready') return 'border-emerald-400/60 bg-emerald-500/20 text-emerald-100';
                if (buttonStatus === 'in_preparation') return 'border-amber-300/70 bg-amber-400/25 text-amber-50';
                return 'border-slate-300/30 bg-slate-400/20 text-slate-100';
            };
            const itemTone = (status) => {
                if (status === 'ready') return 'border-emerald-400/50 bg-emerald-500/20 text-emerald-50';
                if (status === 'in_preparation') return 'border-amber-300/50 bg-amber-400/20 text-amber-50';
                return 'border-white/10 bg-white/5 text-white';
            };
            const itemStatusLabel = (status) => status === 'ready' ? 'Ready' : (status === 'in_preparation' ? 'In Prep' : 'Pending');
            const filterTone = (buttonFilter, activeFilter) => {
                if (buttonFilter !== activeFilter) return 'border-white/20 bg-white/5 text-white/80 hover:bg-white/10';
                if (buttonFilter === 'ready') return 'border-emerald-400/55 bg-emerald-500/20 text-emerald-100';
                if (buttonFilter === 'in_preparation') return 'border-amber-300/55 bg-amber-400/20 text-amber-100';
                if (buttonFilter === 'pending') return 'border-slate-200/40 bg-slate-400/25 text-slate-50';
                return 'border-koya/50 bg-koya/20 text-koya';
            };
            const emptyMarkup = () => `<div data-empty class="glass rounded-3xl p-8 border border-white/10 lg:col-span-2 xl:col-span-3 2xl:col-span-4 text-center"><p class="text-base text-white/60 font-semibold">${state.filter === 'all' ? 'No active kitchen orders right now.' : `No ${state.filter.replace('_', ' ')} orders right now.`}</p></div>`;
            const connState = (m) => {
                if (m === 'live') {
                    conn.textContent = 'Live updates connected';
                    conn.className = 'mt-2 text-xs text-emerald-300 font-black';
                    return;
                }
                if (m === 'weak') {
                    conn.textContent = 'Connection weak: syncing every few seconds';
                    conn.className = 'mt-2 text-xs text-amber-300 font-black';
                    return;
                }
                conn.textContent = 'Offline: showing last known orders';
                conn.className = 'mt-2 text-xs text-red-300 font-black';
            };
            const normalize = (o) => ({
                id: ensureNum(o.id),
                order_type: String(o.order_type || 'table'),
                source: String(o.source || (String(o.order_type || 'table') === 'takeaway' ? 'public_takeaway' : 'table')),
                source_label: String(o.source_label || 'Table #-'),
                table_number: o.table_number ?? null,
                customer_name: o.customer_name ? String(o.customer_name) : null,
                customer_phone: o.customer_phone ? String(o.customer_phone) : null,
                kitchen_status: String(o.kitchen_status || 'pending'),
                created_at: o.created_at || null,
                created_at_label: String(o.created_at_label || '--:--'),
                items: (Array.isArray(o.items) ? o.items : []).map((i) => ({
                    id: ensureNum(i.id),
                    name: String(i.name || 'Item'),
                    quantity: ensureNum(i.quantity, 1),
                    status: String(i.status || 'pending'),
                })),
            });
            const fingerprint = (o) => JSON.stringify([
                o.order_type,
                o.source,
                o.source_label,
                o.customer_name,
                o.customer_phone,
                o.table_number,
                o.kitchen_status,
                o.created_at_label,
                o.items.map((i) => [i.id, i.name, i.quantity, i.status]),
            ]);
            let alertTimer = null;
            function createdAgoLabel(createdAtIso) {
                if (!createdAtIso) return '--';
                const ts = new Date(createdAtIso).getTime();
                if (!Number.isFinite(ts) || ts <= 0) return '--';
                const diffMin = Math.max(0, Math.floor((Date.now() - ts) / 60000));
                if (diffMin < 1) return 'now';
                if (diffMin < 60) return `${diffMin}m ago`;
                const h = Math.floor(diffMin / 60);
                const m = diffMin % 60;
                return `${h}h ${m}m ago`;
            }
            function isNew(createdAtIso) {
                if (!createdAtIso) return false;
                const ts = new Date(createdAtIso).getTime();
                if (!Number.isFinite(ts) || ts <= 0) return false;
                return (Date.now() - ts) <= (3 * 60000);
            }
            function sortedIds() {
                const list = [...state.orders.values()];
                list.sort((a, b) => {
                    const ar = statusRank[a.kitchen_status] ?? 3;
                    const br = statusRank[b.kitchen_status] ?? 3;
                    if (ar !== br) return ar - br;
                    const at = new Date(a.created_at || 0).getTime();
                    const bt = new Date(b.created_at || 0).getTime();
                    return at - bt;
                });
                if (state.filter === 'all') return list.map((o) => o.id);
                return list.filter((o) => o.kitchen_status === state.filter).map((o) => o.id);
            }
            function updateCounts() {
                const totals = { all: state.orders.size, pending: 0, in_preparation: 0, ready: 0 };
                for (const order of state.orders.values()) {
                    if (totals[order.kitchen_status] !== undefined) totals[order.kitchen_status] += 1;
                }
                Object.keys(countNodes).forEach((k) => {
                    if (countNodes[k]) countNodes[k].textContent = String(totals[k] ?? 0);
                });
            }
            function syncFilterButtons() {
                filterWrap?.querySelectorAll('button[data-filter]').forEach((btn) => {
                    const filter = btn.getAttribute('data-filter');
                    btn.className = `kitchen-filter-btn px-3 py-2 rounded-xl border text-xs font-black uppercase tracking-wider transition ${filterTone(filter, state.filter)}`;
                });
            }
            function makeCard(o) {
                const el = document.createElement('article');
                el.className = `glass rounded-3xl p-4 border space-y-3 transition ${orderTone(o.kitchen_status)}`;
                el.setAttribute('data-order-id', String(o.id));
                el.innerHTML = `
                    <div class="flex items-start justify-between gap-3">
                        <div class="space-y-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 data-k="source" class="text-xl font-black leading-tight"></h2>
                                <span data-k="new" class="hidden px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/25 text-emerald-100 border border-emerald-300/50 kitchen-pulse">New</span>
                            </div>
                            <p data-k="customer" class="hidden text-xs text-white/70 font-semibold truncate"></p>
                            <p class="text-[10px] text-white/50 font-bold uppercase tracking-wider">Ordered: <span data-k="created-clock"></span> &middot; <span data-k="created-ago"></span></p>
                        </div>
                        <button type="button" data-order-status-order-id="${o.id}" data-status="completed" class="shrink-0 px-4 py-3 rounded-xl border border-emerald-500/40 bg-emerald-500/20 text-emerald-50 text-xs font-black uppercase tracking-wider transition hover:bg-emerald-500/30 shadow-lg shadow-emerald-500/10">Complete</button>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-black/10 p-2.5">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-[11px] text-white/40 font-black uppercase tracking-wider">Order Status</p>
                            <span data-k="status-chip" class="px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider"></span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" data-order-status-order-id="${o.id}" data-status="pending" class="min-h-[44px] px-2 py-2 rounded-xl border text-xs font-black uppercase tracking-wider transition">Pending</button>
                            <button type="button" data-order-status-order-id="${o.id}" data-status="in_preparation" class="min-h-[44px] px-2 py-2 rounded-xl border text-xs font-black uppercase tracking-wider transition">In Prep</button>
                            <button type="button" data-order-status-order-id="${o.id}" data-status="ready" class="min-h-[44px] px-2 py-2 rounded-xl border text-xs font-black uppercase tracking-wider transition">Ready</button>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-black uppercase tracking-widest text-white/40 mb-2 px-1">Food Items</h3>
                        <div data-k="items" class="mt-1 max-h-56 overflow-y-auto kitchen-scrollbar pr-1 grid grid-cols-2 gap-2"></div>
                    </div>
                `;
                return el;
            }
            function itemsMarkup(o) {
                if (!o.items.length) return '<p class="text-sm text-white/50">No food items in this order.</p>';
                return o.items.map((i) => `
                    <button type="button" data-item-status-item-id="${i.id}" data-item-order-id="${o.id}" data-current-status="${escapeHtml(i.status)}" class="w-full rounded-2xl border px-3 py-3 text-left transition min-h-[56px] ${itemTone(i.status)}">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-black truncate">${escapeHtml(i.name)}</p>
                            <span class="text-[11px] font-black uppercase tracking-wider">${itemStatusLabel(i.status)}</span>
                        </div>
                        <p class="text-xs font-bold opacity-85 mt-1">Qty: ${i.quantity}</p>
                    </button>
                `).join('');
            }
            function patchCard(o, el) {
                el.className = `glass rounded-3xl p-4 border space-y-3 transition ${orderTone(o.kitchen_status)}`;
                el.querySelector('[data-k="source"]').textContent = o.source_label;
                const customerText = o.customer_name
                    ? `${o.customer_name}${o.customer_phone ? ` - ${o.customer_phone}` : ''}`
                    : '';
                const customerNode = el.querySelector('[data-k="customer"]');
                customerNode.textContent = customerText;
                customerNode.classList.toggle('hidden', !customerText);
                el.querySelector('[data-k="created-clock"]').textContent = o.created_at_label;
                el.querySelector('[data-k="created-ago"]').textContent = createdAgoLabel(o.created_at);
                const isBrandNew = isNew(o.created_at);
                const newBadge = el.querySelector('[data-k="new"]');
                if (isBrandNew) {
                    newBadge.classList.remove('hidden');
                } else {
                    newBadge.classList.add('hidden');
                }
                const statusChip = el.querySelector('[data-k="status-chip"]');
                statusChip.className = `px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider whitespace-nowrap ${orderChipTone(o.kitchen_status)}`;
                statusChip.textContent = orderStatusLabel(o.kitchen_status);

                ['pending', 'in_preparation', 'ready'].forEach((st) => {
                    const b = el.querySelector(`button[data-status="${st}"]`);
                    if (b) {
                        b.className = `min-h-[44px] px-2 py-2 rounded-xl border text-xs font-black uppercase tracking-wider transition ${statusButtonTone(st, o.kitchen_status)}`;
                    }
                });

                const completeBtn = el.querySelector(`button[data-status="completed"]`);
                if (completeBtn) {
                    completeBtn.textContent = 'Mark as Completed';
                    if (o.kitchen_status === 'ready') {
                        completeBtn.className = "shrink-0 px-4 py-3 rounded-xl border border-emerald-500/40 bg-emerald-500/20 text-emerald-50 text-[10px] font-black uppercase tracking-wider transition hover:bg-emerald-500/30 shadow-lg shadow-emerald-500/10";
                    } else {
                        completeBtn.className = "shrink-0 px-4 py-3 rounded-xl border border-white/10 bg-white/5 text-white/40 text-[9px] font-black uppercase tracking-wider transition hover:bg-white/10 opacity-60";
                    }
                }
                el.querySelector('[data-k="items"]').innerHTML = itemsMarkup(o);
            }
            function render() {
                updateCounts();
                syncFilterButtons();
                const ids = sortedIds();
                const set = new Set(ids);
                [...c.querySelectorAll('[data-order-id]')].forEach((n) => {
                    if (!set.has(ensureNum(n.getAttribute('data-order-id')))) n.remove();
                });
                if (!ids.length) {
                    c.innerHTML = emptyMarkup();
                    return;
                }
                const e = c.querySelector('[data-empty]');
                if (e) e.remove();
                ids.forEach((id, i) => {
                    const o = state.orders.get(id);
                    if (!o) return;
                    let el = c.querySelector(`[data-order-id="${id}"]`);
                    if (!el) {
                        el = makeCard(o);
                        patchCard(o, el);
                        c.appendChild(el);
                    }
                    const before = c.children[i];
                    if (before !== el) c.insertBefore(el, before || null);
                });
            }
            function beep(times = 1) {
                try {
                    const AudioCtx = window.AudioContext || window.webkitAudioContext;
                    if (!AudioCtx) return;
                    const ctx = new AudioCtx();
                    for (let i = 0; i < times; i += 1) {
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.type = 'sine';
                        osc.frequency.value = i % 2 === 0 ? 920 : 740;
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        const start = ctx.currentTime + (i * 0.18);
                        gain.gain.setValueAtTime(0.0001, start);
                        gain.gain.exponentialRampToValueAtTime(0.08, start + 0.02);
                        gain.gain.exponentialRampToValueAtTime(0.0001, start + 0.14);
                        osc.start(start);
                        osc.stop(start + 0.15);
                    }
                } catch {}
            }
            function showAlert(message) {
                alertBox.textContent = message;
                alertBox.classList.remove('hidden');
                clearTimeout(alertTimer);
                alertTimer = setTimeout(() => alertBox.classList.add('hidden'), 6000);
            }
            function apply(orders, options = {}) {
                const previousIds = new Set(state.orders.keys());
                const next = new Map((Array.isArray(orders) ? orders : []).map((x) => {
                    const o = normalize(x);
                    return [o.id, o];
                }));
                [...state.orders.keys()].forEach((id) => {
                    if (!next.has(id)) {
                        state.orders.delete(id);
                        state.fp.delete(id);
                    }
                });
                next.forEach((o, id) => {
                    const fp = fingerprint(o);
                    state.orders.set(id, o);
                    if (fp !== state.fp.get(id)) {
                        const el = c.querySelector(`[data-order-id="${id}"]`);
                        if (el) patchCard(o, el);
                        state.fp.set(id, fp);
                    }
                });
                render();
                if (options.suppressAlerts) return;
                const freshOrders = [...next.values()].filter((o) => !previousIds.has(o.id));
                if (freshOrders.length > 0) {
                    const takeawayCount = freshOrders.filter((o) => o.source === 'public_takeaway').length;
                    if (takeawayCount > 0) {
                        showAlert(`New public takeaway order${takeawayCount > 1 ? 's' : ''} received`);
                        beep(2);
                    } else {
                        showAlert(`New order${freshOrders.length > 1 ? 's' : ''} received`);
                        beep(1);
                    }
                }
            }
            async function fetchSnapshot() {
                const u = new URL(snapshotUrl, window.location.origin);
                u.searchParams.set('since_version', String(state.version));
                const r = await fetch(u.toString(), { headers: { 'Accept': 'application/json' } });
                if (!r.ok) throw new Error('snapshot failed');
                const p = await r.json();
                state.version = Number(p.version || state.version);
                if (p.changed === false) return false;
                apply(p.orders || []);
                return true;
            }
            async function patchStatus(orderId, status) {
                const r = await fetch(statusUrlTpl.replace('__ORDER__', orderId), {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ status }),
                });
                if (!r.ok) throw new Error('status failed');
            }
            async function patchItemStatus(itemId, status) {
                const r = await fetch(itemStatusUrlTpl.replace('__ITEM__', itemId), {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ status }),
                });
                if (!r.ok) throw new Error('item status failed');
            }
            function optimisticOrderStatus(orderId, status) {
                const id = ensureNum(orderId);
                const order = state.orders.get(id);
                if (!order) return;

                if (status === 'completed') {
                    state.orders.delete(id);
                    state.fp.delete(id);
                    render();
                    return;
                }

                order.kitchen_status = status;
                order.items = order.items.map((item) => ({ ...item, status }));
                state.orders.set(id, order);
                state.fp.set(id, fingerprint(order));
                const card = c.querySelector(`[data-order-id="${id}"]`);
                if (card) patchCard(order, card);
            }
            function optimisticItemToggle(itemId) {
                const targetId = ensureNum(itemId);
                for (const [orderId, order] of state.orders.entries()) {
                    const idx = order.items.findIndex((i) => i.id === targetId);
                    if (idx === -1) continue;
                    const current = order.items[idx];
                    const nextStatus = current.status === 'ready' ? 'pending' : 'ready';
                    order.items[idx] = { ...current, status: nextStatus };
                    const allReady = order.items.length > 0 && order.items.every((i) => i.status === 'ready');
                    const allPending = order.items.length > 0 && order.items.every((i) => i.status === 'pending');
                    order.kitchen_status = allReady ? 'ready' : (allPending ? 'pending' : 'in_preparation');
                    state.orders.set(orderId, order);
                    state.fp.set(orderId, fingerprint(order));
                    const card = c.querySelector(`[data-order-id="${orderId}"]`);
                    if (card) patchCard(order, card);
                    return { orderId, nextStatus };
                }
                return null;
            }
            function stopPoll() {
                clearInterval(state.pollTimer);
                state.pollTimer = null;
            }
            function startPoll() {
                if (state.pollTimer) return;
                connState(navigator.onLine ? 'weak' : 'offline');
                state.pollTimer = setInterval(async () => {
                    if (!navigator.onLine) {
                        connState('offline');
                        return;
                    }
                    try {
                        await fetchSnapshot();
                        connState('weak');
                    } catch {
                        connState('offline');
                    }
                }, 6000);
            }
            function connectStream() {
                if (!navigator.onLine) {
                    startPoll();
                    return;
                }
                if (state.stream) state.stream.close();
                state.stream = new EventSource(`${streamUrl}${streamUrl.includes('?') ? '&' : '?'}last_version=${state.version}`);
                state.stream.addEventListener('open', () => {
                    stopPoll();
                    connState('live');
                });
                state.stream.addEventListener('kitchen.update', async (e) => {
                    try {
                        const d = JSON.parse(e.data || '{}');
                        if (Number(d.version) <= state.version) return;
                    } catch {}
                    try {
                        await fetchSnapshot();
                    } catch {
                        startPoll();
                    }
                });
                state.stream.onerror = () => {
                    if (state.stream) state.stream.close();
                    startPoll();
                    clearTimeout(state.reconnectTimer);
                    state.reconnectTimer = setTimeout(connectStream, 3000);
                };
            }
            filterWrap?.addEventListener('click', (e) => {
                const btn = e.target.closest('button[data-filter]');
                if (!btn) return;
                const nextFilter = btn.getAttribute('data-filter');
                if (!nextFilter || state.filter === nextFilter) return;
                state.filter = nextFilter;
                render();
            });
            c.addEventListener('click', async (e) => {
                const orderBtn = e.target.closest('button[data-order-status-order-id][data-status]');
                if (orderBtn) {
                    const lockKey = `order:${orderBtn.getAttribute('data-order-status-order-id')}`;
                    if (state.optimistic.has(lockKey)) return;
                    state.optimistic.add(lockKey);
                    orderBtn.disabled = true;
                    const orderId = orderBtn.getAttribute('data-order-status-order-id');
                    const status = orderBtn.getAttribute('data-status');

                    if (status === 'completed' && !confirm('Are you sure you want to permanently delete this order? This action cannot be undone.')) {
                        return;
                    }

                    optimisticOrderStatus(orderId, status);
                    try {
                        await patchStatus(orderId, status);
                        await fetchSnapshot();
                    } catch {
                        await fetchSnapshot();
                        alert('Could not update order status. Please try again.');
                    } finally {
                        state.optimistic.delete(lockKey);
                        orderBtn.disabled = false;
                    }
                    return;
                }

                const itemBtn = e.target.closest('button[data-item-status-item-id]');
                if (!itemBtn) return;
                const itemId = itemBtn.getAttribute('data-item-status-item-id');
                const lockKey = `item:${itemId}`;
                if (state.optimistic.has(lockKey)) return;
                state.optimistic.add(lockKey);
                itemBtn.disabled = true;
                const optimistic = optimisticItemToggle(itemId);
                try {
                    if (!optimistic) throw new Error('item not found');
                    await patchItemStatus(itemId, optimistic.nextStatus);
                    await fetchSnapshot();
                } catch {
                    await fetchSnapshot();
                    alert('Could not update item status. Please try again.');
                } finally {
                    state.optimistic.delete(lockKey);
                    itemBtn.disabled = false;
                }
            });
            window.addEventListener('offline', () => {
                if (state.stream) state.stream.close();
                startPoll();
                connState('offline');
            });
            window.addEventListener('online', () => connectStream());
            setInterval(() => {
                for (const [id, order] of state.orders.entries()) {
                    const card = c.querySelector(`[data-order-id="${id}"]`);
                    if (card) {
                        card.querySelector('[data-k="created-ago"]').textContent = createdAgoLabel(order.created_at);
                        const newBadge = card.querySelector('[data-k="new"]');
                        if (isNew(order.created_at)) {
                            newBadge.classList.remove('hidden');
                        } else {
                            newBadge.classList.add('hidden');
                        }
                    }
                }
            }, 20000);
            apply(@json($initialOrders), { suppressAlerts: true });
            connectStream();
        })();
    </script>
</x-app-layout>

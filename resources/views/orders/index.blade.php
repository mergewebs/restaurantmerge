<x-app-layout>
    <style>
        .orders-page { --o-ink:#2f241e; --o-rust:#b95536; --o-cream:#fbf7f0; --o-line:#eadfd3; }
        .order-board { border:1px solid var(--o-line); border-radius:20px; background:#fffdfa; box-shadow:0 12px 32px rgba(57,39,28,.06); }
        .order-status { border-radius:999px; padding:.28rem .58rem; font-size:.58rem; font-weight:900; letter-spacing:.08em; text-transform:uppercase; }
        .status-new { background:#f1f5f9; color:#475569; } .status-confirmed { background:#eff6ff; color:#1d4ed8; }
        .status-preparing { background:#fff7ed; color:#c2410c; } .status-ready { background:#ecfdf5; color:#047857; }
        .status-completed { background:#f0fdf4; color:#166534; } .status-cancelled { background:#fef2f2; color:#b91c1c; }
        .type-pill { color:#78685e; font-size:.62rem; font-weight:800; text-transform:capitalize; }
    </style>

    <div class="orders-page space-y-4" x-data="{ newOrder: {{ $errors->any() ? 'true' : 'false' }}, type: @js(old('order_type', 'dine_in')) }">
        @if(session('success'))<div class="hidden" data-page-feedback data-feedback-level="success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="hidden" data-page-feedback data-feedback-level="error">{{ session('error') }}</div>@endif

        <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[.18em] text-[#b95536]">Live order queue</p>
                <h1 class="text-2xl font-black text-[#2f241e]">Orders</h1>
                <p class="text-xs text-slate-500">See every order and move it forward quickly.</p>
            </div>
            <button type="button" @click="newOrder = true" class="rounded-xl bg-[#b95536] px-4 py-2.5 text-sm font-black text-white shadow-lg shadow-orange-900/10">+ New Order</button>
        </header>

        <nav class="flex gap-2 overflow-x-auto pb-1" aria-label="Order status filters">
            <a href="{{ route('orders.index') }}" class="shrink-0 rounded-full border px-3 py-1.5 text-xs font-bold {{ !request('status') ? 'border-[#b95536] bg-[#b95536] text-white' : 'border-slate-200 bg-white text-slate-600' }}">All {{ $statusCounts->sum() }}</a>
            @foreach(\App\Models\Order::WORKFLOW_STATUSES as $status)
                <a href="{{ route('orders.index', ['status' => $status]) }}" class="shrink-0 rounded-full border px-3 py-1.5 text-xs font-bold capitalize {{ request('status') === $status ? 'border-[#b95536] bg-[#b95536] text-white' : 'border-slate-200 bg-white text-slate-600' }}">{{ $status }} {{ $statusCounts[$status] ?? 0 }}</a>
            @endforeach
        </nav>

        <form method="GET" class="grid gap-2 sm:grid-cols-[1fr_180px_auto]">
            @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            <input name="search" value="{{ request('search') }}" placeholder="Search order, customer, phone, or table">
            <select name="type">
                <option value="">All order types</option>
                @foreach(\App\Models\Order::ORDER_TYPES as $type)<option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>@endforeach
            </select>
            <button class="rounded-xl border border-slate-200 bg-white px-4 text-xs font-black text-slate-700">Search</button>
        </form>

        <div class="order-board overflow-hidden">
            <div class="hidden grid-cols-[90px_1.2fr_1fr_105px_100px_150px] gap-3 border-b border-[#eadfd3] bg-[#fbf7f0] px-4 py-2 text-[9px] font-black uppercase tracking-widest text-slate-500 lg:grid">
                <span>Order</span><span>Customer / Source</span><span>Details</span><span>Waiting</span><span>Total</span><span>Status</span>
            </div>
            <div class="divide-y divide-[#eee5dc]">
                @forelse($orders as $order)
                    <article class="grid gap-3 px-4 py-3 lg:grid-cols-[90px_1.2fr_1fr_105px_100px_150px] lg:items-center">
                        <div class="flex items-center justify-between lg:block"><a href="{{ route('orders.show', $order) }}" class="font-black text-[#b95536]">#{{ $order->id }}</a><span class="order-status status-{{ $order->workflow_status }} lg:hidden">{{ $order->workflow_status }}</span><p class="hidden text-[10px] text-slate-400 lg:block">{{ $order->created_at->format('H:i') }}</p></div>
                        <div class="min-w-0"><a href="{{ route('orders.show', $order) }}" class="block truncate text-sm font-black text-slate-800">{{ $order->customer_name ?: ($order->table ? 'Table '.$order->table->number : ucfirst(str_replace('_', ' ', $order->order_type))) }}</a><p class="type-pill">{{ str_replace('_', ' ', $order->order_type) }}@if($order->customer_phone) · {{ $order->customer_phone }}@endif</p></div>
                        <div><p class="text-xs font-bold text-slate-700">{{ $order->items_count }} {{ \Illuminate\Support\Str::plural('item', $order->items_count) }}</p><p class="truncate text-[10px] text-slate-400">{{ $order->notes ?: 'No order notes' }}</p></div>
                        <div class="text-xs font-black text-amber-700" data-order-timer="{{ $order->created_at->toIso8601String() }}">—</div>
                        <div><p class="text-sm font-black text-slate-800">&euro;{{ number_format($order->total, 2) }}</p><p class="text-[9px] font-black uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">{{ $order->payment_status }}</p></div>
                        <div>
                            <span class="order-status status-{{ $order->workflow_status }} hidden lg:inline-block">{{ $order->workflow_status }}</span>
                            <form method="POST" action="{{ route('orders.status.update', $order) }}" class="mt-1.5">
                                @csrf @method('PATCH')
                                <select name="workflow_status" onchange="this.form.submit()" class="!min-h-0 !w-full !rounded-lg !px-2 !py-1 !text-[10px] !font-bold" aria-label="Change order {{ $order->id }} status">
                                    @foreach(\App\Models\Order::WORKFLOW_STATUSES as $status)<option value="{{ $status }}" @selected($order->workflow_status === $status)>{{ ucfirst($status) }}</option>@endforeach
                                </select>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="px-6 py-14 text-center"><p class="font-black text-slate-700">No orders found</p><p class="mt-1 text-xs text-slate-400">Create a new order or change the filters.</p></div>
                @endforelse
            </div>
        </div>
        {{ $orders->links() }}

        <div x-show="newOrder" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-950/55 backdrop-blur-sm" @click="newOrder = false"></div>
            <form method="POST" action="{{ route('orders.store') }}" class="relative w-full max-w-xl space-y-4 rounded-3xl bg-white p-5 shadow-2xl">
                @csrf
                <div class="flex items-center justify-between"><div><p class="text-[10px] font-black uppercase tracking-widest text-[#b95536]">Start an order</p><h2 class="text-xl font-black">New Order</h2></div><button type="button" @click="newOrder = false" class="text-2xl text-slate-400">&times;</button></div>
                <div><label class="text-xs font-bold">Order type</label><select name="order_type" x-model="type" required>@foreach(\App\Models\Order::ORDER_TYPES as $type)<option value="{{ $type }}">{{ ucfirst(str_replace('_', ' ', $type)) }}</option>@endforeach</select></div>
                <div x-show="type === 'dine_in'"><label class="text-xs font-bold">Table</label><select name="restaurant_table_id" :required="type === 'dine_in'"><option value="">Choose a table</option>@foreach($tables as $table)<option value="{{ $table->id }}" @selected(old('restaurant_table_id') == $table->id)>Table {{ $table->number }}</option>@endforeach</select></div>
                <div class="grid gap-3 sm:grid-cols-2"><div><label class="text-xs font-bold">Customer name</label><input name="customer_name" value="{{ old('customer_name') }}" placeholder="Optional"></div><div><label class="text-xs font-bold">Phone</label><input name="customer_phone" value="{{ old('customer_phone') }}" placeholder="Optional"></div></div>
                <div><label class="text-xs font-bold">Order notes</label><textarea name="notes" rows="2" placeholder="Delivery address, allergies, or instructions">{{ old('notes') }}</textarea></div>
                <button class="w-full rounded-xl bg-[#b95536] py-3 text-sm font-black text-white">Create Order</button>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const update = () => document.querySelectorAll('[data-order-timer]').forEach((el) => {
                const minutes = Math.max(0, Math.floor((Date.now() - new Date(el.dataset.orderTimer).getTime()) / 60000));
                el.textContent = minutes < 60 ? `${minutes} min` : `${Math.floor(minutes / 60)}h ${minutes % 60}m`;
            });
            update(); setInterval(update, 30000);
        })();
    </script>
</x-app-layout>

<x-app-layout>
    <style>
        .admin-service-heading{display:none!important}.orders-page{--o-ink:#30251f;--o-rust:#b95536;--o-cream:#fbf6ed;--o-paper:#fffdf8;--o-line:#eadfd3;--o-muted:#88776c;color:var(--o-ink)}
        .orders-hero{position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:1rem 1.1rem;border-radius:1.35rem;color:#fffaf1;background:radial-gradient(circle at 88% 15%,rgba(214,164,73,.24),transparent 27%),linear-gradient(135deg,#261b16,#3a2921);box-shadow:0 16px 36px rgba(48,31,23,.16)}
        .orders-hero:after{content:'';position:absolute;right:-3rem;top:-5rem;width:12rem;height:12rem;border:1px solid rgba(255,255,255,.08);border-radius:50%;pointer-events:none}.orders-hero>*{position:relative;z-index:1}
        .orders-create{display:inline-flex;min-height:44px;align-items:center;justify-content:center;gap:.4rem;border-radius:.85rem;background:var(--o-rust);padding:.65rem .9rem;color:#fff!important;font-size:.75rem;font-weight:900;box-shadow:0 8px 18px rgba(0,0,0,.16);touch-action:manipulation}
        .orders-summary{display:flex;gap:.45rem;margin-top:.55rem}.orders-summary span{border:1px solid rgba(255,255,255,.11);border-radius:999px;background:rgba(255,255,255,.07);padding:.25rem .5rem;color:rgba(255,250,241,.76);font-size:.58rem;font-weight:800}
        .order-filter-rail{display:flex;gap:.4rem;overflow-x:auto;padding:.05rem .05rem .35rem;scrollbar-width:none;scroll-snap-type:x proximity}.order-filter-rail::-webkit-scrollbar{display:none}.order-filter{flex:none;min-height:36px;scroll-snap-align:start;border:1px solid var(--o-line);border-radius:999px;background:var(--o-paper);padding:.45rem .72rem;color:#6f5e53;font-size:.68rem;font-weight:800;text-transform:capitalize}.order-filter.active{border-color:var(--o-rust);background:var(--o-rust);color:#fff}
        .order-tools{display:flex;align-items:center;justify-content:space-between;gap:.65rem}.order-tools-label{color:var(--o-muted);font-size:.64rem;font-weight:900;letter-spacing:.12em;text-transform:uppercase}.order-filter-toggle{display:inline-flex;min-height:40px;align-items:center;gap:.35rem;border:1px solid var(--o-line);border-radius:.75rem;background:var(--o-paper);padding:.45rem .7rem;color:#69584d;font-size:.68rem;font-weight:900}
        .order-search{display:grid;gap:.45rem;padding:.65rem;border:1px solid var(--o-line);border-radius:1rem;background:var(--o-cream)}.order-search input,.order-search select{min-height:42px!important;border-color:#dfd1c4!important;border-radius:.75rem!important;background:#fff!important;font-size:.75rem!important}
        .order-board{border:1px solid var(--o-line);border-radius:1.1rem;background:var(--o-paper);box-shadow:0 10px 26px rgba(57,39,28,.05)}.order-ticket{position:relative;display:grid;gap:.55rem;padding:.75rem}.order-ticket:before{content:'';position:absolute;inset:.65rem auto .65rem 0;width:3px;border-radius:0 3px 3px 0;background:#cbd5e1}.order-ticket[data-status="preparing"]:before{background:#e9783e}.order-ticket[data-status="ready"]:before{background:#3f9b6d}.order-ticket[data-status="confirmed"]:before{background:#4f7fc7}.order-ticket[data-status="cancelled"]:before{background:#c95a58}
        .order-status{border-radius:999px;padding:.28rem .55rem;font-size:.55rem;font-weight:900;letter-spacing:.07em;text-transform:uppercase;white-space:nowrap}.status-new{background:#f1f5f9;color:#475569}.status-confirmed{background:#eff6ff;color:#1d4ed8}.status-preparing{background:#fff1e8;color:#b64b20}.status-ready{background:#eaf8ef;color:#26724a}.status-completed{background:#f0fdf4;color:#166534}.status-cancelled{background:#fef2f2;color:#b91c1c}.type-pill{color:#78685e;font-size:.61rem;font-weight:800;text-transform:capitalize}
        .order-ticket-main{display:grid;grid-template-columns:minmax(0,1fr) auto;align-items:start;gap:.65rem}.order-ticket-meta{display:flex;min-width:0;align-items:center;gap:.55rem;color:var(--o-muted);font-size:.65rem;font-weight:700}.order-ticket-bottom{display:grid;grid-template-columns:auto auto minmax(118px,1fr);align-items:end;gap:.55rem;padding-top:.5rem;border-top:1px solid #f0e7de}.order-ticket-bottom label{display:block;margin-bottom:.15rem;color:#9a897c;font-size:.5rem;font-weight:900;letter-spacing:.1em;text-transform:uppercase}.order-ticket-bottom select{min-height:38px!important;width:100%!important;border-color:#dfd1c4!important;border-radius:.65rem!important;padding:.35rem 1.8rem .35rem .55rem!important;font-size:.65rem!important;font-weight:800!important}.order-modal-card{max-height:calc(100dvh - 2rem);overflow-y:auto}
        @media(min-width:640px){.order-search{display:grid!important;grid-template-columns:minmax(0,1fr) 180px auto;padding:.55rem}.order-filter-toggle{display:none}.orders-hero{padding:1.15rem 1.25rem}.order-ticket{padding:.85rem 1rem}.order-ticket-bottom{grid-template-columns:100px 100px minmax(140px,180px);justify-content:end}}
        @media(min-width:1024px){.order-ticket{grid-template-columns:70px minmax(180px,1.1fr) minmax(150px,.8fr) 95px 95px 160px;align-items:center;gap:.75rem}.order-ticket-main,.order-ticket-bottom{display:contents}.order-ticket-meta{display:block}.order-ticket-bottom label{display:none}.order-ticket:before{inset:.5rem auto .5rem 0}.order-mobile-only{display:none!important}}
        @media(max-width:639px){.orders-page{margin-inline:-.25rem}.orders-hero h1{font-size:1.5rem}.orders-hero .hero-copy{display:none}.orders-summary span:last-child{display:none}.orders-create{padding:.65rem}.orders-create .create-label{display:none}.order-modal-wrap{align-items:flex-end!important;padding:0!important}.order-modal-card{max-height:92dvh;border-radius:1.35rem 1.35rem 0 0!important;padding:1rem!important}.order-ticket-bottom>div:nth-child(2){text-align:center}}
    </style>

    <div class="orders-page space-y-3" x-data="{ newOrder: {{ $errors->any() ? 'true' : 'false' }}, filters: {{ (request('search') || request('type')) ? 'true' : 'false' }}, type: @js(old('order_type', 'dine_in')) }">
        @if(session('success'))<div class="hidden" data-page-feedback data-feedback-level="success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="hidden" data-page-feedback data-feedback-level="error">{{ session('error') }}</div>@endif

        <header class="orders-hero">
            <div>
                <p class="text-[9px] font-black uppercase tracking-[.18em] text-[#e7bd72]">Live order queue</p>
                <h1 class="text-2xl font-black text-[#fffaf1]">Service orders</h1>
                <p class="hero-copy text-xs text-white/55">Keep every table and takeaway moving.</p>
                <div class="orders-summary"><span>{{ ($statusCounts['new'] ?? 0) + ($statusCounts['confirmed'] ?? 0) }} waiting</span><span>{{ $statusCounts['preparing'] ?? 0 }} cooking</span><span>{{ $statusCounts['ready'] ?? 0 }} ready</span></div>
            </div>
            <button type="button" @click="newOrder = true" class="orders-create"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-width="2.2" d="M12 5v14M5 12h14"/></svg><span class="create-label">New order</span></button>
        </header>

        <nav class="order-filter-rail" aria-label="Order status filters">
            <a href="{{ route('orders.index') }}" class="order-filter {{ !request('status') ? 'active' : '' }}">All {{ $statusCounts->sum() }}</a>
            @foreach(\App\Models\Order::WORKFLOW_STATUSES as $status)<a href="{{ route('orders.index', ['status' => $status]) }}" class="order-filter {{ request('status') === $status ? 'active' : '' }}">{{ $status }} {{ $statusCounts[$status] ?? 0 }}</a>@endforeach
        </nav>

        <div class="order-tools"><span class="order-tools-label">{{ $orders->total() }} {{ \Illuminate\Support\Str::plural('order', $orders->total()) }}</span><button type="button" @click="filters = !filters" class="order-filter-toggle"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M7 12h10m-7 6h4"/></svg> Search & filters</button></div>
        <form method="GET" x-show="filters" x-cloak class="order-search">
            @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            <input name="search" value="{{ request('search') }}" placeholder="Order, guest, phone, or table">
            <select name="type"><option value="">All order types</option>@foreach(\App\Models\Order::ORDER_TYPES as $type)<option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>@endforeach</select>
            <button class="min-h-[42px] rounded-xl bg-[#30251f] px-4 text-xs font-black text-white">Apply</button>
        </form>

        <div class="order-board overflow-hidden">
            <div class="hidden grid-cols-[70px_minmax(180px,1.1fr)_minmax(150px,.8fr)_95px_95px_160px] gap-3 border-b border-[#eadfd3] bg-[#fbf7f0] px-4 py-2 text-[9px] font-black uppercase tracking-widest text-slate-500 lg:grid"><span>Order</span><span>Guest / source</span><span>Details</span><span>Waiting</span><span>Total</span><span>Move order</span></div>
            <div class="divide-y divide-[#eee5dc]">
                @forelse($orders as $order)
                    <article class="order-ticket" data-status="{{ $order->workflow_status }}">
                        <div class="flex items-center justify-between lg:block"><a href="{{ route('orders.show', $order) }}" class="text-sm font-black text-[#b95536]">#{{ $order->id }}</a><span class="order-status status-{{ $order->workflow_status }} order-mobile-only">{{ $order->workflow_status }}</span><p class="hidden text-[10px] font-bold text-slate-400 lg:block">{{ $order->created_at->format('H:i') }}</p></div>
                        <div class="order-ticket-main"><div class="min-w-0"><a href="{{ route('orders.show', $order) }}" class="block truncate text-sm font-black text-slate-800">{{ $order->customer_name ?: ($order->table ? 'Table '.$order->table->number : ucfirst(str_replace('_', ' ', $order->order_type))) }}</a><p class="type-pill">{{ str_replace('_', ' ', $order->order_type) }}@if($order->customer_phone) &middot; {{ $order->customer_phone }}@endif</p></div><a href="{{ route('orders.show', $order) }}" class="order-mobile-only flex h-8 w-8 items-center justify-center rounded-lg bg-[#f8eee6] text-lg font-black text-[#b95536]" aria-label="Open order {{ $order->id }}">&rsaquo;</a></div>
                        <div class="order-ticket-meta"><strong class="text-slate-700">{{ $order->items_count }} {{ \Illuminate\Support\Str::plural('item', $order->items_count) }}</strong><span class="order-mobile-only">&middot;</span><span class="truncate">{{ $order->notes ?: 'No notes' }}</span></div>
                        <div class="order-ticket-bottom">
                            <div><label>Waiting</label><strong class="text-xs text-amber-700" data-order-timer="{{ $order->created_at->toIso8601String() }}">&mdash;</strong></div>
                            <div><label>Total</label><p class="text-sm font-black text-slate-800">&euro;{{ number_format($order->total, 2) }}</p><p class="text-[8px] font-black uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">{{ $order->payment_status }}</p></div>
                            <div><label>Move order</label><span class="order-status status-{{ $order->workflow_status }} hidden lg:inline-block">{{ $order->workflow_status }}</span><form method="POST" action="{{ route('orders.status.update', $order) }}" class="lg:mt-1">@csrf @method('PATCH')<select name="workflow_status" onchange="this.form.submit()" aria-label="Change order {{ $order->id }} status">@foreach(\App\Models\Order::WORKFLOW_STATUSES as $status)<option value="{{ $status }}" @selected($order->workflow_status === $status)>{{ ucfirst($status) }}</option>@endforeach</select></form></div>
                        </div>
                    </article>
                @empty
                    <div class="px-6 py-12 text-center"><div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-full bg-[#f8eee6] text-xl text-[#b95536]">&#9782;</div><p class="font-black text-slate-700">No orders found</p><p class="mt-1 text-xs text-slate-400">Create an order or change the filters.</p></div>
                @endforelse
            </div>
        </div>
        {{ $orders->links() }}

        <div x-show="newOrder" x-cloak class="order-modal-wrap fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-950/55 backdrop-blur-sm" @click="newOrder = false"></div>
            <form method="POST" action="{{ route('orders.store') }}" class="order-modal-card relative w-full max-w-xl space-y-3 rounded-3xl bg-white p-5 shadow-2xl">
                @csrf
                <div class="flex items-center justify-between"><div><p class="text-[10px] font-black uppercase tracking-widest text-[#b95536]">Start an order</p><h2 class="text-xl font-black">New Order</h2></div><button type="button" @click="newOrder = false" class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-2xl text-slate-500" aria-label="Close">&times;</button></div>
                <div><label class="text-xs font-bold">Order type</label><select name="order_type" x-model="type" required>@foreach(\App\Models\Order::ORDER_TYPES as $type)<option value="{{ $type }}">{{ ucfirst(str_replace('_', ' ', $type)) }}</option>@endforeach</select></div>
                <div x-show="type === 'dine_in'"><label class="text-xs font-bold">Table</label><select name="restaurant_table_id" :required="type === 'dine_in'"><option value="">Choose a table</option>@foreach($tables as $table)<option value="{{ $table->id }}" @selected(old('restaurant_table_id') == $table->id)>Table {{ $table->number }}</option>@endforeach</select></div>
                <div class="grid grid-cols-2 gap-2"><div><label class="text-xs font-bold">Customer name</label><input name="customer_name" value="{{ old('customer_name') }}" placeholder="Optional"></div><div><label class="text-xs font-bold">Phone</label><input name="customer_phone" value="{{ old('customer_phone') }}" placeholder="Optional"></div></div>
                <div><label class="text-xs font-bold">Order notes</label><textarea name="notes" rows="2" placeholder="Address, allergies, or instructions">{{ old('notes') }}</textarea></div>
                <button class="w-full rounded-xl bg-[#b95536] py-3 text-sm font-black text-white">Create Order</button>
            </form>
        </div>
    </div>

    <script>(()=>{const update=()=>document.querySelectorAll('[data-order-timer]').forEach((el)=>{const minutes=Math.max(0,Math.floor((Date.now()-new Date(el.dataset.orderTimer).getTime())/60000));el.textContent=minutes<60?`${minutes} min`:`${Math.floor(minutes/60)}h ${minutes%60}m`});update();setInterval(update,30000)})();</script>
</x-app-layout>

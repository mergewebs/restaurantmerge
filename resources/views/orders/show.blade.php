<x-app-layout>
    <style>
        .order-detail { --rust:#b95536; --ink:#30241e; --cream:#fbf7f0; --line:#eadfd3; }
        .order-card { border:1px solid var(--line); border-radius:20px; background:#fffdfa; box-shadow:0 10px 28px rgba(57,39,28,.05); }
        .status-step { border:1px solid #e2e8f0; border-radius:10px; padding:.48rem .65rem; background:#fff; color:#64748b; font-size:.62rem; font-weight:900; text-transform:capitalize; }
        .status-step.active { border-color:var(--rust); background:var(--rust); color:#fff; }
    </style>

    <div class="order-detail space-y-4" x-data="{ tab: 'items', payments: [{ method: 'cash', amount: @js(number_format($order->balance_due, 2, '.', '')), reference: '' }], addPayment(){ this.payments.push({method:'card', amount:'', reference:''}) } }">
        @if(session('success'))<div class="hidden" data-page-feedback data-feedback-level="success">{{ session('success') }}</div>@endif

        <header class="order-card p-4">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-3"><a href="{{ route('orders.index') }}" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500">←</a><div><p class="text-[10px] font-black uppercase tracking-widest text-[#b95536]">{{ str_replace('_', ' ', $order->order_type) }} order</p><h1 class="text-xl font-black text-[#30241e]">Order #{{ $order->id }} @if($order->table) · Table {{ $order->table->number }}@endif</h1><p class="text-xs text-slate-400">Started {{ $order->created_at->format('H:i') }} · <span data-order-timer="{{ $order->created_at->toIso8601String() }}">—</span> waiting</p></div></div>
                <div class="flex flex-wrap gap-2"><a href="{{ route('orders.ticket', $order) }}" target="_blank" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-black text-slate-700">Print Ticket</a><a href="{{ route('orders.ticket.download', $order) }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-black text-slate-700">Download Ticket</a></div>
            </div>
            <div class="mt-4 flex gap-1.5 overflow-x-auto pb-1">
                @foreach(\App\Models\Order::WORKFLOW_STATUSES as $status)
                    <form method="POST" action="{{ route('orders.status.update', $order) }}">@csrf @method('PATCH')<input type="hidden" name="workflow_status" value="{{ $status }}"><button class="status-step {{ $order->workflow_status === $status ? 'active' : '' }}">{{ $status }}</button></form>
                @endforeach
            </div>
        </header>

        <div class="grid gap-4 xl:grid-cols-[1.5fr_.8fr]">
            <main class="space-y-4">
                <div class="order-card overflow-hidden">
                    <div class="flex border-b border-[#eadfd3] bg-[#fbf7f0] px-3 pt-2"><button @click="tab='items'" :class="tab==='items' ? 'border-[#b95536] text-[#b95536]' : 'border-transparent text-slate-500'" class="border-b-2 px-3 py-2 text-xs font-black">Order Items ({{ $order->items->count() }})</button><button @click="tab='details'" :class="tab==='details' ? 'border-[#b95536] text-[#b95536]' : 'border-transparent text-slate-500'" class="border-b-2 px-3 py-2 text-xs font-black">Customer & Notes</button></div>

                    <div x-show="tab==='items'" class="p-4">
                        <div class="space-y-2">
                            @forelse($order->items as $item)
                                <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-white p-3"><div class="min-w-0"><p class="truncate text-sm font-black text-slate-800">{{ $item->quantity }} × {{ $item->name }}</p>@if($item->note)<p class="text-[11px] text-amber-700">{{ $item->note }}</p>@endif<p class="text-[10px] text-slate-400">&euro;{{ number_format($item->unit_price, 2) }} each</p></div><div class="flex items-center gap-3"><strong class="text-sm">&euro;{{ number_format($item->line_total, 2) }}</strong>@unless(in_array($order->workflow_status, ['completed','cancelled']))<form method="POST" action="{{ route('orders.items.destroy', [$order, $item]) }}">@csrf @method('DELETE')<button class="text-lg text-rose-500" title="Remove">&times;</button></form>@endunless</div></div>
                            @empty<p class="py-8 text-center text-sm text-slate-400">No items yet. Add dishes below.</p>@endforelse
                        </div>

                        @unless(in_array($order->workflow_status, ['completed','cancelled']))
                            <div class="mt-5 border-t border-[#eadfd3] pt-4"><h2 class="mb-3 text-sm font-black text-[#30241e]">Add Menu Items</h2><div class="space-y-4">@foreach($menuItems as $category => $items)<section><p class="mb-2 text-[9px] font-black uppercase tracking-widest text-[#b95536]">{{ $category }}</p><div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">@foreach($items as $menuItem)<form method="POST" action="{{ route('orders.items.store', $order) }}" class="flex items-center justify-between gap-2 rounded-xl border border-slate-200 bg-white p-2.5">@csrf<input type="hidden" name="menu_item_id" value="{{ $menuItem->id }}"><input type="hidden" name="quantity" value="1"><div class="min-w-0"><p class="truncate text-xs font-black">{{ $menuItem->name }}</p><p class="text-[10px] text-slate-400">&euro;{{ number_format($menuItem->price, 2) }}</p></div><button class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#fbefe7] font-black text-[#b95536]">+</button></form>@endforeach</div></section>@endforeach</div></div>
                        @endunless
                    </div>

                    <div x-show="tab==='details'" x-cloak class="p-4">
                        <form method="POST" action="{{ route('orders.details.update', $order) }}" class="space-y-3">@csrf @method('PUT')
                            <div class="grid gap-3 sm:grid-cols-2"><div><label class="text-xs font-bold">Order type</label><select name="order_type" required>@foreach(\App\Models\Order::ORDER_TYPES as $type)<option value="{{ $type }}" @selected($order->order_type === $type)>{{ ucfirst(str_replace('_',' ',$type)) }}</option>@endforeach</select></div><div><label class="text-xs font-bold">Table</label><select name="restaurant_table_id"><option value="">No table</option>@foreach($tables as $table)<option value="{{ $table->id }}" @selected($order->restaurant_table_id === $table->id)>Table {{ $table->number }}</option>@endforeach</select></div></div>
                            <div class="grid gap-3 sm:grid-cols-2"><div><label class="text-xs font-bold">Customer name</label><input name="customer_name" value="{{ $order->customer_name }}"></div><div><label class="text-xs font-bold">Phone number</label><input name="customer_phone" value="{{ $order->customer_phone }}"></div></div>
                            <div><label class="text-xs font-bold">Order notes</label><textarea name="notes" rows="3">{{ $order->notes }}</textarea></div><button class="rounded-xl bg-[#b95536] px-4 py-2.5 text-xs font-black text-white">Save Details</button>
                        </form>
                    </div>
                </div>
            </main>

            <aside class="space-y-4">
                <section class="order-card p-4"><h2 class="text-sm font-black text-[#30241e]">Bill</h2>@php($subtotal = $order->items->sum('line_total'))<dl class="mt-3 space-y-2 text-xs"><div class="flex justify-between"><dt class="text-slate-500">Subtotal</dt><dd class="font-bold">&euro;{{ number_format($subtotal,2) }}</dd></div><div class="flex justify-between"><dt class="text-slate-500">Discount</dt><dd class="font-bold text-rose-600">−&euro;{{ number_format($order->discount_amount,2) }}</dd></div><div class="flex justify-between"><dt class="text-slate-500">Service charge</dt><dd class="font-bold">+&euro;{{ number_format($order->service_charge,2) }}</dd></div><div class="flex justify-between border-t border-[#eadfd3] pt-2 text-base"><dt class="font-black">Total</dt><dd class="font-black">&euro;{{ number_format($order->total,2) }}</dd></div></dl>
                    <form method="POST" action="{{ route('orders.adjustments.update', $order) }}" class="mt-4 grid grid-cols-2 gap-2">@csrf @method('PUT')<div><label class="text-[10px] font-bold">Discount</label><input type="number" step="0.01" min="0" name="discount_amount" value="{{ $order->discount_amount }}" class="!py-2"></div><div><label class="text-[10px] font-bold">Service charge</label><input type="number" step="0.01" min="0" name="service_charge" value="{{ $order->service_charge }}" class="!py-2"></div><button class="col-span-2 rounded-lg border border-slate-200 py-2 text-[10px] font-black">Update Bill</button></form>
                </section>

                <section class="order-card p-4"><div class="flex items-center justify-between"><h2 class="text-sm font-black text-[#30241e]">Payments</h2><span class="rounded-full px-2 py-1 text-[9px] font-black uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $order->payment_status }}</span></div><div class="mt-3 rounded-xl bg-[#fbf7f0] p-3"><div class="flex justify-between text-xs"><span>Paid</span><strong>&euro;{{ number_format($order->paid_amount,2) }}</strong></div><div class="mt-1 flex justify-between text-sm"><span class="font-black">Balance due</span><strong class="text-[#b95536]">&euro;{{ number_format($order->balance_due,2) }}</strong></div></div>
                    @if($order->payments->isNotEmpty())<div class="mt-3 space-y-1">@foreach($order->payments as $payment)<div class="flex justify-between text-[11px]"><span class="capitalize text-slate-500">{{ str_replace('_',' ',$payment->method) }} · {{ $payment->paid_at->format('H:i') }}</span><strong>&euro;{{ number_format($payment->amount,2) }}</strong></div>@endforeach</div>@endif
                    @if($order->balance_due > 0)<form method="POST" action="{{ route('orders.payments.store', $order) }}" class="mt-4 space-y-2">@csrf<template x-for="(payment,index) in payments" :key="index"><div class="grid grid-cols-[1fr_100px_28px] gap-1.5"><select :name="`payments[${index}][method]`" x-model="payment.method" class="!py-2 !text-xs"><option value="cash">Cash</option><option value="card">Card</option><option value="bank_transfer">Bank transfer</option><option value="voucher">Voucher</option><option value="other">Other</option></select><input :name="`payments[${index}][amount]`" x-model="payment.amount" type="number" min="0.01" step="0.01" class="!py-2 !text-xs" required><button type="button" @click="payments.splice(index,1)" class="text-rose-500">×</button></div></template><button type="button" @click="addPayment()" class="text-[10px] font-black text-[#b95536]">+ Split payment</button><button class="w-full rounded-xl bg-[#b95536] py-2.5 text-xs font-black text-white">Record Payment</button></form>@endif
                </section>
            </aside>
        </div>
    </div>
    <script>(()=>{const el=document.querySelector('[data-order-timer]');if(!el)return;const update=()=>{const m=Math.max(0,Math.floor((Date.now()-new Date(el.dataset.orderTimer).getTime())/60000));el.textContent=m<60?`${m} min`:`${Math.floor(m/60)}h ${m%60}m`};update();setInterval(update,30000)})();</script>
</x-app-layout>

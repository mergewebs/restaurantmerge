<x-app-layout>
    <style>
        .floor-page { --f-ink:#2f241e; --f-rust:#b95536; --f-line:#e8ddd1; --f-cream:#f8f2e9; }
        .floor-shell { border:1px solid var(--f-line); border-radius:24px; background-color:#f5efe6; background-image:linear-gradient(rgba(122,103,87,.055) 1px,transparent 1px),linear-gradient(90deg,rgba(122,103,87,.055) 1px,transparent 1px); background-size:24px 24px; padding:1rem; }
        .floor-table { position:relative; min-height:190px; border:2px solid; border-radius:24px; padding:1rem; background:#fffdfa; box-shadow:0 10px 24px rgba(55,38,27,.07); transition:.2s ease; }
        .floor-table:hover { transform:translateY(-2px); box-shadow:0 15px 30px rgba(55,38,27,.11); }
        .table-available { border-color:#a7c5a0; } .table-occupied { border-color:#d17654; background:#fffaf6; }
        .table-reserved { border-color:#d7ae5b; background:#fffbeb; } .table-cleaning { border-color:#8eb6c6; background:#f0f9ff; }
        .table-merged { border-color:#a78bba; background:#faf5ff; }
        .floor-badge { display:inline-flex;align-items:center;gap:.35rem;border-radius:999px;padding:.3rem .55rem;font-size:.58rem;font-weight:900;letter-spacing:.08em;text-transform:uppercase; }
        .floor-badge::before { content:'';width:.4rem;height:.4rem;border-radius:50%;background:currentColor; }
        .badge-available{background:#ecfdf3;color:#34734b}.badge-occupied{background:#fff0e8;color:#a94728}.badge-reserved{background:#fff7d6;color:#9a6b12}.badge-cleaning{background:#e8f5fa;color:#26738d}.badge-merged{background:#f3e8ff;color:#7e4b9b}
        .table-shape { display:flex;align-items:center;justify-content:center;width:58px;height:58px;border:5px solid #eadfd3;background:#fff;border-radius:18px;color:var(--f-ink);font:700 1.35rem Georgia,serif;box-shadow:inset 0 0 0 1px #fff,0 4px 12px rgba(50,34,25,.08); }
        .table-shape.round { border-radius:50%; }
        summary.table-actions { list-style:none;cursor:pointer } summary.table-actions::-webkit-details-marker{display:none}
    </style>

    @php
        $floorCounts = ['available' => 0, 'occupied' => 0, 'reserved' => 0, 'cleaning' => 0];
        foreach ($tables as $floorTable) { $status = $floorTable->floor_status; if (array_key_exists($status, $floorCounts)) $floorCounts[$status]++; }
        $moveTargets = $tables->filter(fn ($candidate) => !$candidate->floor_order && !$candidate->merged_into_table_id);
        $mergeTargets = $tables->filter(fn ($candidate) => !$candidate->merged_into_table_id);
    @endphp

    <div class="floor-page space-y-4" data-async-region="tables-index" data-async-mutations="true">
        @if(session('success'))<div class="hidden" data-async-feedback data-feedback-level="success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="hidden" data-async-feedback data-feedback-level="error">{{ session('error') }}</div>@endif
        @if(session('info'))<div class="hidden" data-async-feedback data-feedback-level="info">{{ session('info') }}</div>@endif

        <header class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div><p class="text-[10px] font-black uppercase tracking-[.18em] text-[#b95536]">Dining room</p><h1 class="text-2xl font-black text-[#2f241e]">Tables Overview</h1><p class="text-xs text-slate-500">See the room, bills, and table times in one place.</p></div>
            <div class="flex flex-wrap gap-2">
                @foreach(['available','occupied','reserved','cleaning'] as $status)<span class="floor-badge badge-{{ $status }}">{{ $status }} {{ $floorCounts[$status] }}</span>@endforeach
                <a href="{{ route('tables.completed') }}" data-async="off" class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-black text-slate-600">Completed Orders</a>
            </div>
        </header>

        <details class="rounded-2xl border border-[#e8ddd1] bg-white p-3">
            <summary class="cursor-pointer text-xs font-black text-[#b95536]">+ Add a table</summary>
            <form action="{{ route('tables.store') }}" method="POST" class="mt-3 grid gap-2 sm:grid-cols-[1fr_1fr_1.5fr_auto]">@csrf<input type="number" name="number" min="1" max="999" value="{{ old('number') }}" required placeholder="Table number"><input type="number" name="seats" min="1" max="30" value="{{ old('seats',4) }}" placeholder="Seats"><input name="area" value="{{ old('area','Main Room') }}" placeholder="Area, e.g. Terrace"><button class="rounded-xl bg-[#b95536] px-5 text-xs font-black text-white">Add Table</button></form>
        </details>

        @forelse($areas as $area => $areaTables)
            <section class="space-y-2">
                <div class="flex items-center gap-2"><h2 class="text-sm font-black text-[#2f241e]">{{ $area }}</h2><span class="text-[10px] font-bold text-slate-400">{{ $areaTables->count() }} tables</span><div class="h-px flex-1 bg-[#e8ddd1]"></div></div>
                <div class="floor-shell grid gap-3 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                    @foreach($areaTables as $table)
                        @php($order = $table->floor_order)
                        @php($status = $table->floor_status)
                        @php($orderTable = $table->parentTable ?: $table)
                        <article class="floor-table table-{{ $status }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="table-shape {{ $table->seats <= 4 ? 'round' : '' }}">{{ $table->number }}</div>
                                    <div>
                                        <span class="floor-badge badge-{{ $status }}">{{ $status }}</span>
                                        <p class="mt-1 text-[10px] font-bold text-slate-400">
                                            {{ $table->seats }} seats
                                            @if($table->merged_into_table_id)
                                                &middot; with table {{ $table->parentTable?->number }}
                                            @elseif($table->mergedTables->isNotEmpty())
                                                &middot; + table {{ $table->mergedTables->pluck('number')->join(', ') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @if($order)<div class="text-right"><strong class="block text-lg text-[#2f241e]">&euro;{{ number_format($order->total,2) }}</strong><span class="text-[9px] font-black uppercase text-slate-400">current bill</span></div>@endif
                            </div>

                            <div class="mt-3 min-h-[38px]">
                                @if($order)<div class="flex items-center justify-between rounded-xl bg-white/70 px-2.5 py-2"><div><p class="text-xs font-black text-slate-700">Order #{{ $order->id }}</p><p class="text-[10px] text-slate-400">{{ $order->items->sum('quantity') }} items</p></div><div class="text-right"><p class="text-xs font-black text-amber-700" data-table-timer="{{ $order->created_at->toIso8601String() }}">—</p><p class="text-[9px] text-slate-400">occupied</p></div></div>
                                @elseif($status === 'reserved')<p class="rounded-xl bg-white/60 px-3 py-2 text-xs font-bold text-amber-800">Held for arriving guests</p>
                                @elseif($status === 'cleaning')<p class="rounded-xl bg-white/60 px-3 py-2 text-xs font-bold text-sky-800">Waiting to be reset</p>
                                @else<p class="rounded-xl bg-white/60 px-3 py-2 text-xs text-slate-400">Ready for guests</p>@endif
                            </div>

                            <div class="mt-3 flex gap-2">
                                <a href="{{ route('tables.order', $orderTable) }}" data-async="off" class="flex-1 rounded-xl bg-[#b95536] px-3 py-2 text-center text-[10px] font-black uppercase tracking-wide text-white">{{ $order ? 'Open Bill' : 'Start Order' }}</a>
                                <details class="relative">
                                    <summary class="table-actions flex h-full items-center rounded-xl border border-slate-200 bg-white px-3 text-lg font-black text-slate-500">•••</summary>
                                    <div class="absolute bottom-11 right-0 z-20 w-64 space-y-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-2xl">
                                        @if($table->merged_into_table_id)
                                            <form method="POST" action="{{ route('tables.unmerge', $table) }}">@csrf @method('DELETE')<button class="w-full rounded-lg border border-purple-200 py-2 text-xs font-black text-purple-700">Separate Table</button></form>
                                        @else
                                            @unless($order)
                                                <form method="POST" action="{{ route('tables.floor-status.update', $table) }}" class="flex gap-1">@csrf @method('PATCH')<select name="service_status" class="!min-h-0 !py-1.5 !text-xs">@foreach(\App\Models\RestaurantTable::SERVICE_STATUSES as $tableStatus)<option value="{{ $tableStatus }}" @selected($table->service_status === $tableStatus)>{{ ucfirst($tableStatus) }}</option>@endforeach</select><button class="rounded-lg bg-slate-800 px-2 text-[10px] font-black text-white">Set</button></form>
                                                @if($availableOrders->isNotEmpty())<form method="POST" action="{{ route('tables.assign-order', $table) }}" class="flex gap-1">@csrf<select name="order_id" class="!min-h-0 !py-1.5 !text-xs"><option value="">Assign order…</option>@foreach($availableOrders as $freeOrder)<option value="{{ $freeOrder->id }}">#{{ $freeOrder->id }} {{ $freeOrder->customer_name }}</option>@endforeach</select><button class="rounded-lg bg-[#b95536] px-2 text-[10px] font-black text-white">Assign</button></form>@endif
                                            @endunless
                                            @if($table->openOrder)
                                                <form method="POST" action="{{ route('tables.move-order', $table) }}" class="flex gap-1">@csrf<select name="target_table_id" class="!min-h-0 !py-1.5 !text-xs"><option value="">Move to…</option>@foreach($moveTargets->where('id','!=',$table->id) as $target)<option value="{{ $target->id }}">Table {{ $target->number }}</option>@endforeach</select><button class="rounded-lg bg-slate-800 px-2 text-[10px] font-black text-white">Move</button></form>
                                            @endif
                                            <form method="POST" action="{{ route('tables.merge', $table) }}" class="flex gap-1">@csrf<select name="target_table_id" class="!min-h-0 !py-1.5 !text-xs"><option value="">Join with…</option>@foreach($mergeTargets->where('id','!=',$table->id) as $target)<option value="{{ $target->id }}">Table {{ $target->number }}</option>@endforeach</select><button class="rounded-lg bg-purple-700 px-2 text-[10px] font-black text-white">Join</button></form>
                                        @endif
                                    </div>
                                </details>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="floor-shell py-16 text-center"><p class="font-black text-slate-700">No tables yet</p><p class="text-xs text-slate-400">Add the first table above.</p></div>
        @endforelse
    </div>

    <script>(()=>{const update=()=>document.querySelectorAll('[data-table-timer]').forEach(el=>{const m=Math.max(0,Math.floor((Date.now()-new Date(el.dataset.tableTimer).getTime())/60000));el.textContent=m<60?`${m} min`:`${Math.floor(m/60)}h ${m%60}m`});update();setInterval(update,30000)})();</script>
</x-app-layout>

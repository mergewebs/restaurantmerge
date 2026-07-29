<x-app-layout>
    <style>
        .admin-service-heading{display:none!important}.floor-page{--f-ink:#30251f;--f-rust:#b95536;--f-line:#e8ddd1;--f-cream:#f8f2e9;--f-paper:#fffdf9;--f-muted:#88776c;color:var(--f-ink)}
        .floor-hero{position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;gap:.75rem;padding:1rem 1.1rem;border-radius:1.35rem;color:#fffaf1;background:radial-gradient(circle at 88% 12%,rgba(215,166,74,.24),transparent 28%),linear-gradient(135deg,#261b16,#3a2921);box-shadow:0 16px 36px rgba(48,31,23,.16)}.floor-hero:after{content:'';position:absolute;right:-3rem;top:-5rem;width:12rem;height:12rem;border:1px solid rgba(255,255,255,.08);border-radius:50%;pointer-events:none}.floor-hero>*{position:relative;z-index:1}
        .floor-counts{display:flex;gap:.35rem;margin-top:.55rem}.floor-count{border:1px solid rgba(255,255,255,.11);border-radius:999px;background:rgba(255,255,255,.07);padding:.25rem .48rem;color:rgba(255,250,241,.75);font-size:.57rem;font-weight:800}.floor-count strong{color:#fff}
        .floor-history{display:flex;min-height:42px;align-items:center;justify-content:center;border:1px solid rgba(255,255,255,.13);border-radius:.8rem;background:rgba(255,255,255,.08);padding:.5rem .7rem;color:#fff!important;font-size:.67rem;font-weight:900;white-space:nowrap}
        .floor-add{border:1px solid var(--f-line);border-radius:1rem;background:var(--f-paper);box-shadow:0 5px 16px rgba(57,39,28,.035)}.floor-add>summary{display:flex;min-height:44px;cursor:pointer;list-style:none;align-items:center;justify-content:space-between;padding:.55rem .8rem;color:var(--f-rust);font-size:.7rem;font-weight:900}.floor-add>summary::-webkit-details-marker{display:none}.floor-add>summary:after{content:'+';font-size:1.2rem}.floor-add[open]>summary:after{content:'−'}.floor-add form{border-top:1px solid #f0e7de;padding:.65rem}.floor-add input{min-height:42px!important;border-color:#dfd2c6!important;border-radius:.7rem!important;background:#fff!important;font-size:.75rem!important}
        .area-heading{display:flex;align-items:center;gap:.5rem;padding:0 .1rem}.area-heading h2{font-family:Georgia,'Times New Roman',serif;font-size:1rem;font-weight:700;color:var(--f-ink)}.area-heading span{color:#9a897d;font-size:.6rem;font-weight:800}.area-heading:after{content:'';height:1px;flex:1;background:var(--f-line)}
        .floor-shell{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.55rem;border:1px solid var(--f-line);border-radius:1.15rem;background-color:#f5efe6;background-image:linear-gradient(rgba(122,103,87,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(122,103,87,.05) 1px,transparent 1px);background-size:22px 22px;padding:.55rem}
        .floor-table{position:relative;min-width:0;min-height:158px;border:1px solid;border-radius:1rem;background:var(--f-paper);padding:.65rem;box-shadow:0 6px 16px rgba(55,38,27,.055);transition:.2s ease}.floor-table:hover{transform:translateY(-2px);box-shadow:0 12px 25px rgba(55,38,27,.09)}.table-available{border-color:#a7c5a0}.table-occupied{border-color:#d17654;background:#fffaf6}.table-reserved{border-color:#d7ae5b;background:#fffbeb}.table-cleaning{border-color:#8eb6c6;background:#f0f9ff}.table-merged{border-color:#a78bba;background:#faf5ff}
        .floor-table-top{display:flex;align-items:flex-start;justify-content:space-between;gap:.35rem}.table-shape{display:flex;height:42px;width:42px;flex:none;align-items:center;justify-content:center;border:3px solid #eadfd3;border-radius:.75rem;background:#fff;color:var(--f-ink);font:700 1.15rem Georgia,serif;box-shadow:inset 0 0 0 1px #fff,0 3px 8px rgba(50,34,25,.07)}.table-shape.round{border-radius:50%}
        .floor-badge{display:inline-flex;max-width:100%;align-items:center;gap:.28rem;border-radius:999px;padding:.24rem .42rem;font-size:.5rem;font-weight:900;letter-spacing:.05em;text-transform:uppercase}.floor-badge:before{content:'';height:.35rem;width:.35rem;flex:none;border-radius:50%;background:currentColor}.badge-available{background:#ecfdf3;color:#34734b}.badge-occupied{background:#fff0e8;color:#a94728}.badge-reserved{background:#fff7d6;color:#9a6b12}.badge-cleaning{background:#e8f5fa;color:#26738d}.badge-merged{background:#f3e8ff;color:#7e4b9b}
        .table-service{display:flex;min-height:35px;align-items:center;justify-content:space-between;gap:.35rem;margin-top:.55rem;border-radius:.65rem;background:rgba(255,255,255,.66);padding:.4rem .5rem}.table-service p{line-height:1.1}.table-primary{display:flex;min-height:40px;flex:1;align-items:center;justify-content:center;border-radius:.7rem;background:var(--f-rust);padding:.45rem;color:#fff!important;font-size:.59rem;font-weight:900;letter-spacing:.04em;text-align:center;text-transform:uppercase;touch-action:manipulation}
        summary.table-actions{display:flex;min-height:40px;cursor:pointer;list-style:none;align-items:center;border:1px solid var(--f-line);border-radius:.7rem;background:#fff;padding:0 .6rem;color:#6f6055;font-size:1rem;font-weight:900}summary.table-actions::-webkit-details-marker{display:none}.table-menu{position:absolute;right:0;bottom:44px;z-index:30;width:15rem;display:grid;gap:.55rem;border:1px solid var(--f-line);border-radius:1rem;background:#fff;padding:.7rem;box-shadow:0 18px 40px rgba(48,31,23,.2)}.table-menu select{min-height:38px!important;border-color:#ded1c5!important;border-radius:.6rem!important;padding:.35rem 1.6rem .35rem .45rem!important;font-size:.65rem!important}.table-menu button{min-height:38px;border-radius:.6rem;padding:.35rem .55rem;font-size:.61rem;font-weight:900}
        @media(min-width:640px){.floor-shell{grid-template-columns:repeat(3,minmax(0,1fr));gap:.75rem;padding:.75rem}.floor-table{min-height:168px;padding:.75rem}.table-shape{height:48px;width:48px}.floor-add form{grid-template-columns:1fr 1fr 1.5fr auto}.floor-hero{padding:1.15rem 1.25rem}}
        @media(min-width:1100px){.floor-shell{grid-template-columns:repeat(4,minmax(0,1fr));gap:.85rem;padding:.85rem}.floor-table{min-height:174px}.table-primary{font-size:.64rem}}
        @media(max-width:639px){.floor-page{margin-inline:-.25rem}.floor-hero h1{font-size:1.45rem}.floor-hero .hero-copy{display:none}.floor-counts .floor-count:last-child{display:none}.floor-history span{display:none}.floor-history:before{content:'History'}.table-menu{position:fixed;inset:auto .75rem 5.5rem .75rem;z-index:90;width:auto}.table-seats-copy{max-width:5rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}}
    </style>

    @php
        $floorCounts = ['available' => 0, 'occupied' => 0, 'reserved' => 0, 'cleaning' => 0];
        foreach ($tables as $floorTable) { $status = $floorTable->floor_status; if (array_key_exists($status, $floorCounts)) $floorCounts[$status]++; }
        $moveTargets = $tables->filter(fn ($candidate) => !$candidate->floor_order && !$candidate->merged_into_table_id);
        $mergeTargets = $tables->filter(fn ($candidate) => !$candidate->merged_into_table_id);
    @endphp

    <div class="floor-page space-y-3" data-async-region="tables-index" data-async-mutations="true">
        @if(session('success'))<div class="hidden" data-async-feedback data-feedback-level="success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="hidden" data-async-feedback data-feedback-level="error">{{ session('error') }}</div>@endif
        @if(session('info'))<div class="hidden" data-async-feedback data-feedback-level="info">{{ session('info') }}</div>@endif

        <header class="floor-hero">
            <div><p class="text-[9px] font-black uppercase tracking-[.18em] text-[#e7bd72]">Dining room</p><h1 class="text-2xl font-black text-[#fffaf1]">Tables Overview</h1><p class="hero-copy text-xs text-white/55">The whole floor, ready for service.</p><div class="floor-counts"><span class="floor-count"><strong>{{ $floorCounts['available'] }}</strong> free</span><span class="floor-count"><strong>{{ $floorCounts['occupied'] }}</strong> serving</span><span class="floor-count"><strong>{{ $floorCounts['reserved'] }}</strong> reserved</span><span class="floor-count"><strong>{{ $floorCounts['cleaning'] }}</strong> resetting</span></div></div>
            <a href="{{ route('tables.completed') }}" data-async="off" class="floor-history"><span>Completed orders</span></a>
        </header>

        <details class="floor-add" @if($errors->any()) open @endif>
            <summary>Add a table</summary>
            <form action="{{ route('tables.store') }}" method="POST" class="grid gap-2">@csrf<input type="number" name="number" min="1" max="999" value="{{ old('number') }}" required placeholder="Table number"><input type="number" name="seats" min="1" max="30" value="{{ old('seats',4) }}" placeholder="Seats"><input name="area" value="{{ old('area','Main Room') }}" placeholder="Area, e.g. Terrace"><button class="min-h-[42px] rounded-xl bg-[#b95536] px-5 text-xs font-black text-white">Add table</button></form>
        </details>

        @forelse($areas as $area => $areaTables)
            <section class="space-y-2">
                <div class="area-heading"><h2>{{ $area }}</h2><span>{{ $areaTables->count() }} {{ \Illuminate\Support\Str::plural('table', $areaTables->count()) }}</span></div>
                <div class="floor-shell">
                    @foreach($areaTables as $table)
                        @php($order = $table->floor_order)
                        @php($status = $table->floor_status)
                        @php($orderTable = $table->parentTable ?: $table)
                        <article class="floor-table table-{{ $status }}">
                            <div class="floor-table-top">
                                <div class="flex min-w-0 items-center gap-2"><div class="table-shape {{ $table->seats <= 4 ? 'round' : '' }}">{{ $table->number }}</div><div class="min-w-0"><span class="floor-badge badge-{{ $status }}">{{ $status }}</span><p class="table-seats-copy mt-1 text-[9px] font-bold text-slate-400">{{ $table->seats }} seats @if($table->merged_into_table_id)&middot; with {{ $table->parentTable?->number }}@elseif($table->mergedTables->isNotEmpty())&middot; + {{ $table->mergedTables->pluck('number')->join(', ') }}@endif</p></div></div>
                                @if($order)<div class="text-right"><strong class="block text-sm text-[#2f241e]">&euro;{{ number_format($order->total,2) }}</strong><span class="text-[7px] font-black uppercase text-slate-400">current bill</span></div>@endif
                            </div>

                            <div class="table-service">
                                @if($order)<div><p class="text-[10px] font-black text-slate-700">Order #{{ $order->id }}</p><p class="text-[8px] text-slate-400">{{ $order->items->sum('quantity') }} items</p></div><div class="text-right"><p class="text-[10px] font-black text-amber-700" data-table-timer="{{ $order->created_at->toIso8601String() }}">&mdash;</p><p class="text-[7px] text-slate-400">occupied</p></div>
                                @elseif($status === 'reserved')<p class="text-[10px] font-bold text-amber-800">Guests arriving</p>
                                @elseif($status === 'cleaning')<p class="text-[10px] font-bold text-sky-800">Needs resetting</p>
                                @else<p class="text-[10px] text-slate-500">Ready for guests</p>@endif
                            </div>

                            <div class="mt-2 flex gap-1.5">
                                <a href="{{ route('tables.order', $orderTable) }}" data-async="off" class="table-primary">{{ $order ? 'Open bill' : 'Start order' }}</a>
                                <details class="relative"><summary class="table-actions" aria-label="More actions for table {{ $table->number }}">&bull;&bull;&bull;</summary><div class="table-menu">
                                    @if($table->merged_into_table_id)
                                        <form method="POST" action="{{ route('tables.unmerge', $table) }}">@csrf @method('DELETE')<button class="w-full border border-purple-200 text-purple-700">Separate table</button></form>
                                    @else
                                        @unless($order)
                                            <form method="POST" action="{{ route('tables.floor-status.update', $table) }}" class="grid grid-cols-[1fr_auto] gap-1">@csrf @method('PATCH')<select name="service_status">@foreach(\App\Models\RestaurantTable::SERVICE_STATUSES as $tableStatus)<option value="{{ $tableStatus }}" @selected($table->service_status === $tableStatus)>{{ ucfirst($tableStatus) }}</option>@endforeach</select><button class="bg-slate-800 text-white">Set</button></form>
                                            @if($availableOrders->isNotEmpty())<form method="POST" action="{{ route('tables.assign-order', $table) }}" class="grid grid-cols-[1fr_auto] gap-1">@csrf<select name="order_id"><option value="">Assign order...</option>@foreach($availableOrders as $freeOrder)<option value="{{ $freeOrder->id }}">#{{ $freeOrder->id }} {{ $freeOrder->customer_name }}</option>@endforeach</select><button class="bg-[#b95536] text-white">Assign</button></form>@endif
                                        @endunless
                                        @if($table->openOrder)<form method="POST" action="{{ route('tables.move-order', $table) }}" class="grid grid-cols-[1fr_auto] gap-1">@csrf<select name="target_table_id"><option value="">Move to...</option>@foreach($moveTargets->where('id','!=',$table->id) as $target)<option value="{{ $target->id }}">Table {{ $target->number }}</option>@endforeach</select><button class="bg-slate-800 text-white">Move</button></form>@endif
                                        <form method="POST" action="{{ route('tables.merge', $table) }}" class="grid grid-cols-[1fr_auto] gap-1">@csrf<select name="target_table_id"><option value="">Join with...</option>@foreach($mergeTargets->where('id','!=',$table->id) as $target)<option value="{{ $target->id }}">Table {{ $target->number }}</option>@endforeach</select><button class="bg-purple-700 text-white">Join</button></form>
                                    @endif
                                </div></details>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="floor-shell block py-12 text-center"><p class="font-black text-slate-700">No tables yet</p><p class="text-xs text-slate-400">Add your first table above.</p></div>
        @endforelse
    </div>

    <script>(()=>{const update=()=>document.querySelectorAll('[data-table-timer]').forEach(el=>{const m=Math.max(0,Math.floor((Date.now()-new Date(el.dataset.tableTimer).getTime())/60000));el.textContent=m<60?`${m} min`:`${Math.floor(m/60)}h ${m%60}m`});update();setInterval(update,30000)})();</script>
</x-app-layout>

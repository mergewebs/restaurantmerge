<x-app-layout>
@php
    $maxTrend = max(1, (float) $trend->max('sales'));
    $maxCategory = max(1, (float) $categorySales->max('sales'));
    $maxType = max(1, (float) $orderTypeSales->max('sales'));
    $maxHour = max(1, (int) $busyHours->max('orders'));
    $maxEmployee = max(1, (float) $employeePerformance->max('sales'));
@endphp

<style>
    .reports { --ink:#35261d; --muted:#806d60; --rust:#b95738; --cream:#fbf6ef; --line:#e7d9cb; }
    .report-hero { position:relative; overflow:hidden; border-radius:1.35rem; padding:1rem; color:#fff8ee; background:linear-gradient(135deg,#2d2119 0%,#513323 58%,#86462e 100%); box-shadow:0 18px 38px rgba(53,38,29,.16); }
    .report-hero::after { content:""; position:absolute; right:-3rem; top:-4rem; width:11rem; height:11rem; border-radius:999px; background:rgba(237,183,93,.13); border:1px solid rgba(255,255,255,.08); }
    .report-card { background:#fff; border:1px solid var(--line); border-radius:1rem; box-shadow:0 6px 18px rgba(68,44,29,.05); }
    .period-switch { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:.25rem; padding:.25rem; border-radius:.85rem; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.12); }
    .period-switch a { position:relative; z-index:1; border-radius:.65rem; padding:.55rem .65rem; text-align:center; font-size:.68rem; font-weight:900; color:rgba(255,255,255,.67); }
    .period-switch a.active { background:#fff8ee; color:#633a26; box-shadow:0 4px 12px rgba(0,0,0,.14); }
    .kpi-rail { display:grid; grid-auto-flow:column; grid-auto-columns:78%; gap:.55rem; overflow-x:auto; scroll-snap-type:x mandatory; scrollbar-width:none; padding-bottom:.15rem; }
    .kpi-rail::-webkit-scrollbar { display:none; }
    .kpi { min-height:6.3rem; scroll-snap-align:start; padding:.85rem; }
    .kpi-label { font-size:.58rem; font-weight:900; letter-spacing:.13em; text-transform:uppercase; color:#958478; }
    .kpi strong { display:block; margin-top:.25rem; color:var(--ink); font-size:1.35rem; line-height:1.15; }
    .snapshot-strip { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); overflow:hidden; }
    .snapshot { min-width:0; padding:.7rem .55rem; text-align:center; }
    .snapshot + .snapshot { border-left:1px solid #eee4da; }
    .snapshot span { display:block; font-size:.55rem; font-weight:900; text-transform:uppercase; color:#958478; }
    .snapshot strong { display:block; overflow:hidden; text-overflow:ellipsis; color:var(--ink); font-size:.8rem; white-space:nowrap; }
    .snapshot small { display:block; color:#8c7a6d; font-size:.58rem; }
    .report-tabs { display:grid; grid-template-columns:repeat(4,minmax(4.4rem,1fr)); gap:.25rem; overflow-x:auto; padding:.3rem; scrollbar-width:none; }
    .report-tabs::-webkit-scrollbar { display:none; }
    .report-tabs button { min-height:2.65rem; border-radius:.72rem; padding:.45rem .55rem; font-size:.63rem; font-weight:900; line-height:1.1; white-space:nowrap; }
    .reports .tab-on { background:var(--rust); color:#fff; box-shadow:0 5px 12px rgba(185,87,56,.22); }
    .reports .tab-off { color:#776357; }
    .section-head { display:flex; align-items:center; justify-content:space-between; padding:.75rem .85rem; background:linear-gradient(90deg,#fbf6ef,#fff); border-bottom:1px solid #eee4da; }
    .section-head h2 { color:var(--ink); font-size:.8rem; font-weight:900; }
    .section-head span { font-size:.56rem; font-weight:900; letter-spacing:.08em; text-transform:uppercase; color:#a06a50; }
    .data-list { padding:.7rem .85rem; }
    .data-row + .data-row { margin-top:.72rem; }
    .data-copy { display:flex; align-items:baseline; justify-content:space-between; gap:.75rem; font-size:.68rem; }
    .data-copy span { min-width:0; overflow:hidden; color:#655246; font-weight:800; text-overflow:ellipsis; white-space:nowrap; }
    .data-copy strong { flex:none; color:var(--ink); }
    .bar-track { height:.38rem; margin-top:.28rem; overflow:hidden; border-radius:99px; background:#eee5dc; }
    .bar-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,#b95738,#d58a68); }
    .rank-row { display:flex; align-items:center; gap:.55rem; padding:.65rem .8rem; }
    .rank-row + .rank-row { border-top:1px solid #eee4da; }
    .rank-number { display:grid; width:1.35rem; height:1.35rem; flex:none; place-items:center; border-radius:999px; background:#f7ebe2; color:#a94d31; font-size:.58rem; font-weight:900; }
    .rank-copy { min-width:0; flex:1; }
    .rank-copy strong { display:block; overflow:hidden; color:var(--ink); font-size:.7rem; text-overflow:ellipsis; white-space:nowrap; }
    .rank-copy span { display:block; color:#958478; font-size:.55rem; font-weight:800; text-transform:uppercase; }
    .rank-value { flex:none; color:#2f7a56; font-size:.67rem; font-weight:900; }
    .payment-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:.45rem; padding:.7rem; }
    .payment-tile { border-radius:.75rem; padding:.65rem; background:#faf6f1; border:1px solid #f0e5db; }
    .payment-tile span { display:block; color:#958478; font-size:.55rem; font-weight:900; text-transform:uppercase; }
    .payment-tile strong { color:var(--ink); font-size:.8rem; }
    .cost-list { display:grid; gap:.4rem; padding:.7rem; }
    .cost-row { display:flex; justify-content:space-between; border-radius:.7rem; padding:.55rem .65rem; font-size:.68rem; }
    .employee-grid { display:grid; gap:.5rem; padding:.7rem; }
    .employee { border:1px solid #eadfd5; border-radius:.85rem; padding:.7rem; }
    [x-cloak] { display:none!important; }
    @media (min-width:768px) {
        .report-hero { display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.4rem; }
        .period-switch { width:19rem; }
        .kpi-rail { grid-auto-flow:initial; grid-auto-columns:initial; grid-template-columns:repeat(3,minmax(0,1fr)); overflow:visible; }
        .kpi { min-height:6.7rem; }
        .snapshot { padding:.8rem; }
        .snapshot strong { font-size:.95rem; }
        .report-tabs button { font-size:.7rem; }
        .employee-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media (min-width:1280px) {
        .kpi-rail { grid-template-columns:repeat(6,minmax(0,1fr)); }
        .reports-content { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:.75rem; }
        .employee-grid { grid-template-columns:repeat(3,minmax(0,1fr)); }
    }
</style>

<div class="reports space-y-3" x-data="{ tab: 'sales' }">
    <header class="report-hero">
        <div class="relative z-[1] mb-3 md:mb-0">
            <p class="text-[9px] font-black uppercase tracking-[.24em] text-[#efbb77]">Restaurant performance</p>
            <div class="mt-1 flex items-end gap-2">
                <h1 class="font-display text-3xl font-bold leading-none">Reports</h1>
                <span class="pb-0.5 text-[10px] font-bold text-white/60">{{ $periodLabel }}</span>
            </div>
            <p class="mt-2 text-[10px] text-white/55">Sales, menu demand, service patterns and team results.</p>
        </div>
        <nav class="period-switch relative z-[1]" aria-label="Report period">
            @foreach(['day' => 'Today', 'week' => 'Week', 'month' => 'Month'] as $value => $label)
                <a href="{{ route('reports.index', ['period' => $value]) }}" class="{{ $period === $value ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </nav>
    </header>

    <section class="kpi-rail" aria-label="Key performance indicators">
        <article class="report-card kpi"><span class="kpi-label">Net sales</span><strong>€{{ number_format($sales, 2) }}</strong><small class="text-[10px] text-[#8c7a6d]">{{ $orderCount }} completed orders</small></article>
        <article class="report-card kpi"><span class="kpi-label">Estimated profit</span><strong class="{{ $estimatedProfit >= 0 ? '!text-emerald-700' : '!text-red-600' }}">€{{ number_format($estimatedProfit, 2) }}</strong><small class="text-[9px] text-[#9a897d]">After refunds and expenses</small></article>
        <article class="report-card kpi"><span class="kpi-label">Average order</span><strong>€{{ number_format($averageOrderValue, 2) }}</strong><small class="text-[10px] text-[#8c7a6d]">Per completed ticket</small></article>
        <article class="report-card kpi"><span class="kpi-label">Expenses</span><strong class="!text-red-600">€{{ number_format($expenseTotal, 2) }}</strong><small class="text-[10px] text-[#8c7a6d]">Operating costs</small></article>
        <article class="report-card kpi"><span class="kpi-label">Discounts</span><strong class="!text-amber-700">€{{ number_format($discountTotal, 2) }}</strong><small class="text-[10px] text-[#8c7a6d]">Guest incentives</small></article>
        <article class="report-card kpi"><span class="kpi-label">Refunds</span><strong class="!text-red-600">€{{ number_format($refundTotal, 2) }}</strong><small class="text-[10px] text-[#8c7a6d]">Returned payments</small></article>
    </section>

    <section class="report-card snapshot-strip" aria-label="Sales snapshots">
        @foreach(['today' => 'Today', 'week' => 'This week', 'month' => 'This month'] as $key => $label)
            <div class="snapshot"><span>{{ $label }}</span><strong>€{{ number_format($salesSnapshots[$key]['sales'], 2) }}</strong><small>{{ $salesSnapshots[$key]['orders'] }} orders</small></div>
        @endforeach
    </section>

    <nav class="report-card report-tabs" aria-label="Report sections">
        <button @click="tab='sales'" :class="tab === 'sales' ? 'tab-on' : 'tab-off'">Overview</button>
        <button @click="tab='products'" :class="tab === 'products' ? 'tab-on' : 'tab-off'">Menu</button>
        <button @click="tab='operations'" :class="tab === 'operations' ? 'tab-on' : 'tab-off'">Operations</button>
        <button @click="tab='team'" :class="tab === 'team' ? 'tab-on' : 'tab-off'">Team</button>
    </nav>

    <section x-show="tab === 'sales'" class="reports-content space-y-3 xl:space-y-0">
        <article class="report-card overflow-hidden">
            <div class="section-head"><h2>Sales trend</h2><span>{{ $periodLabel }}</span></div>
            <div class="data-list">
                @forelse($trend as $row)
                    <div class="data-row"><div class="data-copy"><span>{{ $row['name'] }}</span><strong>€{{ number_format($row['sales'], 2) }}</strong></div><div class="bar-track"><div class="bar-fill" style="width:{{ max(2, $row['sales'] / $maxTrend * 100) }}%"></div></div></div>
                @empty
                    <p class="py-8 text-center text-xs text-[#958478]">No completed sales in this period.</p>
                @endforelse
            </div>
        </article>
        <article class="report-card overflow-hidden">
            <div class="section-head"><h2>Order channels</h2><span>Sales mix</span></div>
            <div class="data-list">
                @forelse($orderTypeSales as $row)
                    <div class="data-row"><div class="data-copy"><span>{{ $row['name'] }} · {{ $row['orders'] }} orders</span><strong>€{{ number_format($row['sales'], 2) }}</strong></div><div class="bar-track"><div class="bar-fill" style="width:{{ max(2, $row['sales'] / $maxType * 100) }}%"></div></div></div>
                @empty
                    <p class="py-8 text-center text-xs text-[#958478]">No order channel data.</p>
                @endforelse
            </div>
        </article>
        <article class="report-card overflow-hidden">
            <div class="section-head"><h2>Payment methods</h2><span>Collected</span></div>
            <div class="payment-grid">
                @foreach($paymentTotals as $row)
                    <div class="payment-tile"><span>{{ $row['name'] }}</span><strong>€{{ number_format($row['total'], 2) }}</strong></div>
                @endforeach
            </div>
        </article>
    </section>

    <section x-show="tab === 'products'" x-cloak class="reports-content space-y-3 xl:space-y-0">
        <article class="report-card overflow-hidden">
            <div class="section-head"><h2>Guest favourites</h2><span>Best selling</span></div>
            <div>
                @forelse($bestProducts as $i => $row)
                    <div class="rank-row"><span class="rank-number">{{ $i + 1 }}</span><div class="rank-copy"><strong>{{ $row['name'] }}</strong><span>{{ $row['category'] }}</span></div><strong class="rank-value">{{ $row['quantity'] }} · €{{ number_format($row['sales'], 2) }}</strong></div>
                @empty
                    <p class="p-8 text-center text-xs text-[#958478]">No product sales.</p>
                @endforelse
            </div>
        </article>
        <article class="report-card overflow-hidden">
            <div class="section-head"><h2>Needs attention</h2><span>Lowest demand</span></div>
            <div>
                @forelse($worstProducts as $i => $row)
                    <div class="rank-row"><span class="rank-number">{{ $i + 1 }}</span><div class="rank-copy"><strong>{{ $row['name'] }}</strong><span>{{ $row['category'] }}</span></div><strong class="rank-value {{ $row['quantity'] === 0 ? '!text-red-600' : '!text-[#655246]' }}">{{ $row['quantity'] }} sold</strong></div>
                @empty
                    <p class="p-8 text-center text-xs text-[#958478]">No menu items.</p>
                @endforelse
            </div>
        </article>
        <article class="report-card overflow-hidden">
            <div class="section-head"><h2>Menu categories</h2><span>Sales share</span></div>
            <div class="data-list">
                @forelse($categorySales as $row)
                    <div class="data-row"><div class="data-copy"><span>{{ $row['name'] }} · {{ $row['quantity'] }} items</span><strong>€{{ number_format($row['sales'], 2) }}</strong></div><div class="bar-track"><div class="bar-fill" style="width:{{ max(2, $row['sales'] / $maxCategory * 100) }}%"></div></div></div>
                @empty
                    <p class="py-8 text-center text-xs text-[#958478]">No category sales.</p>
                @endforelse
            </div>
        </article>
    </section>

    <section x-show="tab === 'operations'" x-cloak class="reports-content space-y-3 xl:space-y-0">
        <article class="report-card overflow-hidden">
            <div class="section-head"><h2>Peak service hours</h2><span>Kitchen demand</span></div>
            <div class="data-list">
                @forelse($busyHours as $row)
                    <div class="data-row"><div class="data-copy"><span>{{ $row['name'] }}</span><strong>{{ $row['orders'] }} orders</strong></div><div class="bar-track"><div class="bar-fill !bg-amber-500" style="width:{{ max(2, $row['orders'] / $maxHour * 100) }}%"></div></div></div>
                @empty
                    <p class="py-8 text-center text-xs text-[#958478]">No busy-hour data.</p>
                @endforelse
            </div>
        </article>
        <article class="report-card overflow-hidden">
            <div class="section-head"><h2>Busiest days</h2><span>Service volume</span></div>
            <div>
                @forelse($busyDays as $i => $row)
                    <div class="rank-row"><span class="rank-number">{{ $i + 1 }}</span><div class="rank-copy"><strong>{{ $row['name'] }}</strong><span>{{ $row['orders'] }} orders</span></div><strong class="rank-value">€{{ number_format($row['sales'], 2) }}</strong></div>
                @empty
                    <p class="p-8 text-center text-xs text-[#958478]">No daily demand data.</p>
                @endforelse
            </div>
        </article>
        <article class="report-card overflow-hidden">
            <div class="section-head"><h2>Adjustments & costs</h2><span>Profit check</span></div>
            <div class="cost-list">
                <div class="cost-row bg-amber-50"><span>Discounts</span><strong>€{{ number_format($discountTotal, 2) }}</strong></div>
                <div class="cost-row bg-red-50"><span>Refunds</span><strong>€{{ number_format($refundTotal, 2) }}</strong></div>
                <div class="cost-row bg-red-50"><span>Cancellations</span><strong>{{ $cancelledOrders->count() }} · €{{ number_format((float) $cancelledOrders->sum('total'), 2) }}</strong></div>
                <div class="cost-row bg-[#faf6f1]"><span>Expenses</span><strong>€{{ number_format($expenseTotal, 2) }}</strong></div>
                <div class="cost-row bg-emerald-50"><span>Estimated profit</span><strong>€{{ number_format($estimatedProfit, 2) }}</strong></div>
            </div>
        </article>
    </section>

    <section x-show="tab === 'team'" x-cloak class="report-card overflow-hidden">
        <div class="section-head"><h2>Employee performance</h2><span>{{ $employeePerformance->count() }} team members</span></div>
        <div class="employee-grid">
            @forelse($employeePerformance as $i => $row)
                <article class="employee">
                    <div class="flex items-start justify-between gap-3"><div class="min-w-0"><span class="text-[9px] font-black uppercase text-[#958478]">#{{ $i + 1 }} service ranking</span><h3 class="truncate text-sm font-black text-[#392c24]">{{ $row['name'] }}</h3></div><strong class="flex-none text-sm text-emerald-700">€{{ number_format($row['sales'], 2) }}</strong></div>
                    <div class="bar-track mt-2"><div class="bar-fill !bg-emerald-600" style="width:{{ max(2, $row['sales'] / $maxEmployee * 100) }}%"></div></div>
                    <div class="mt-2 flex justify-between text-[10px] text-[#7f6d60]"><span>{{ $row['orders'] }} orders</span><span>Avg €{{ number_format($row['average'], 2) }}</span></div>
                </article>
            @empty
                <p class="p-8 text-center text-xs text-[#958478]">No employee-linked sales in this period.</p>
            @endforelse
        </div>
    </section>
</div>
</x-app-layout>

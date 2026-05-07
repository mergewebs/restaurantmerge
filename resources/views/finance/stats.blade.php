<x-app-layout>
    {{-- Inline styles just for this page --}}
    <style>
        /* Tab button base */
        .filter-tab {
            flex: 1;
            padding: 0.55rem 0;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-align: center;
            color: rgba(255,255,255,0.4);
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .filter-tab.active {
            background: rgba(255,92,0,0.15);
            color: #FF5C00;
            box-shadow: 0 0 0 1px rgba(255,92,0,0.35);
        }

        /* KPI gradient cards */
        .kpi-income  { background: linear-gradient(135deg, rgba(74,222,128,0.12) 0%, rgba(74,222,128,0.03) 100%); }
        .kpi-expense { background: linear-gradient(135deg, rgba(248,113,113,0.12) 0%, rgba(248,113,113,0.03) 100%); }
        .kpi-profit  { background: linear-gradient(135deg, rgba(251,191,36,0.12) 0%, rgba(251,191,36,0.03) 100%); }
        .kpi-profit.negative { background: linear-gradient(135deg, rgba(239,68,68,0.15) 0%, rgba(239,68,68,0.04) 100%); }

        .badge-dot {
            display: inline-block;
            width: 8px; height: 8px;
            border-radius: 50%;
            margin-right: 6px;
        }

        /* Custom range animation */
        #customRange {
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease;
            max-height: 0;
            opacity: 0;
        }
        #customRange.open {
            max-height: 160px;
            opacity: 1;
        }

        canvas { display: block; }
    </style>

    <div x-data="{ 
        showFinanceModal: {{ (($errors->any() && old('amount') && !old('finance_id')) || session('keep_finance_modal') || request()->boolean('finance_modal')) ? 'true' : 'false' }},
        finance_type: '{{ old('finance_type', 'income') }}'
    }" class="space-y-6">

        {{-- ─── Header Section ─────────────────────────── --}}
        <div class="flex items-center justify-between border-b border-white/5 pb-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-green-500 rounded-full shadow-[0_0_15px_rgba(34,197,94,0.4)]"></div>
                <div>
                    <h3 class="text-xl font-black text-white uppercase tracking-tight">Financial Report</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-0.5">Stats & Analytics</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('finance.categories.index') }}" class="w-10 h-10 bg-white/5 border border-white/10 text-white rounded-2xl flex items-center justify-center hover:bg-white/10 transition-all shadow-lg" title="Manage Categories">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </a>
                <a href="{{ route('finance.history') }}" class="w-10 h-10 bg-white/5 border border-white/10 text-white rounded-2xl flex items-center justify-center hover:bg-white/10 transition-all shadow-lg" title="View History">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </a>
                <button @click="showFinanceModal = true" class="w-10 h-10 bg-green-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-green-500/20 hover:scale-110 active:scale-95 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-2xl text-xs font-bold uppercase tracking-wider">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add Finance Modal -->
        <div x-show="showFinanceModal" 
             class="fixed inset-0 z-[100] flex items-center justify-center px-4" 
             style="display: none;"
             x-cloak>
            <div @click="showFinanceModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="glass w-full max-w-2xl rounded-[32px] p-8 relative border border-white/10"
                 x-show="showFinanceModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Add Finance</h3>
                    <button @click="showFinanceModal = false" class="text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                @if (session('success') && (session('keep_finance_modal') || request()->boolean('finance_modal')))
                    <div class="mb-4 bg-emerald-500/20 border border-emerald-500/50 p-3 rounded-xl text-xs text-emerald-200 font-bold uppercase tracking-wider">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any() && old('amount') && !old('finance_id'))
                    <div class="mb-4 bg-red-500/20 border border-red-500/50 p-3 rounded-xl text-xs text-red-200">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('finance.store') }}" method="POST" class="space-y-4 text-left">
                    @csrf
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Date</label>
                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                               class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Amount (€)</label>
                        <input type="number" step="0.01" name="amount" value="{{ (session('keep_finance_modal') || request()->boolean('finance_modal')) ? '' : old('amount') }}" required autofocus
                               class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Type</label>
                            <select name="finance_type" x-model="finance_type" required class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                                <option value="income" {{ old('finance_type') == 'income' ? 'selected' : '' }} class="bg-gray-900">Income</option>
                                <option value="expense" {{ old('finance_type') == 'expense' ? 'selected' : '' }} class="bg-gray-900">Expense</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Method</label>
                            <select name="method" required class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                                <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }} class="bg-gray-900">Cash</option>
                                <option value="card" {{ old('method') == 'card' ? 'selected' : '' }} class="bg-gray-900">Card</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Category</label>
                            <select name="category" class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                                <option value="" {{ old('category') == '' ? 'selected' : '' }} class="bg-gray-900">None</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}" 
                                            x-show="finance_type === '{{ $cat->type }}'"
                                            {{ old('category') == $cat->name ? 'selected' : '' }} 
                                            class="bg-gray-900">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Description (Optional)</label>
                            <input type="text" name="description" value="{{ old('description') }}"
                                   class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full btn-koya py-4 shadow-xl shadow-koya/20 text-white font-bold uppercase tracking-widest">Save Entry</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ─── Filter bar ─────────────────────────────── --}}
        <div class="glass rounded-2xl border border-white/5 p-1.5">
            <form method="GET" action="{{ route('finance.stats') }}" id="filterForm">

                {{-- Mode tabs --}}
                <div class="flex gap-1 mb-1.5">
                    @foreach(['daily' => 'Today', 'weekly' => '7 Days', 'monthly' => 'This Month', 'custom' => 'Custom'] as $key => $label)
                        <button type="button"
                                onclick="setMode('{{ $key }}')"
                                class="filter-tab {{ $mode === $key ? 'active' : '' }}"
                                id="tab-{{ $key }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                <input type="hidden" name="mode" id="modeInput" value="{{ $mode }}">
                <input type="hidden" name="date_from" id="dateFromInput" value="{{ $dateFrom }}">
                <input type="hidden" name="date_to"   id="dateToInput"   value="{{ $dateTo }}">

                {{-- Custom date range --}}
                <div id="customRange" class="{{ $mode === 'custom' ? 'open' : '' }}">
                    <div class="grid grid-cols-2 gap-2 px-1 pb-1.5">
                        <div>
                            <label>From</label>
                            <input type="date" id="pickerFrom"
                                   value="{{ $dateFrom ?? $start->toDateString() }}"
                                   onchange="document.getElementById('dateFromInput').value=this.value">
                        </div>
                        <div>
                            <label>To</label>
                            <input type="date" id="pickerTo"
                                   value="{{ $dateTo ?? $end->toDateString() }}"
                                   onchange="document.getElementById('dateToInput').value=this.value">
                        </div>
                    </div>
                    <button type="submit"
                            class="w-full mb-1.5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider"
                            style="background:rgba(255,92,0,0.15);color:#FF5C00;border:1px solid rgba(255,92,0,0.3);">
                        Apply Range
                    </button>
                </div>

            </form>
        </div>

        {{-- ─── Period label ────────────────────────────── --}}
        <p class="text-xs text-white/30 text-center font-bold uppercase tracking-widest -mt-2">
            {{ $start->format('d M Y') }}
            @if($start->toDateString() !== $end->toDateString())
                → {{ $end->format('d M Y') }}
            @endif
        </p>

        {{-- ─── KPI Cards ───────────────────────────────── --}}
        <div class="grid grid-cols-3 gap-3">
            <div class="glass kpi-income p-4 rounded-2xl border border-white/5">
                <p class="text-[10px] text-green-400/70 uppercase tracking-widest font-bold mb-1">Income</p>
                <h3 class="text-xl font-black text-green-400 leading-tight">€{{ number_format($totalIncome, 2) }}</h3>
            </div>
            <div class="glass kpi-expense p-4 rounded-2xl border border-white/5">
                <p class="text-[10px] text-red-400/70 uppercase tracking-widest font-bold mb-1">Expenses</p>
                <h3 class="text-xl font-black text-red-400 leading-tight">€{{ number_format($totalExpenses, 2) }}</h3>
            </div>
            <div class="glass {{ $netProfit >= 0 ? 'kpi-profit' : 'kpi-profit negative' }} p-4 rounded-2xl border border-white/5">
                <p class="text-[10px] {{ $netProfit >= 0 ? 'text-amber-400/70' : 'text-red-400/70' }} uppercase tracking-widest font-bold mb-1">Profit</p>
                <h3 class="text-xl font-black {{ $netProfit >= 0 ? 'text-amber-400' : 'text-red-400' }} leading-tight">
                    {{ $netProfit >= 0 ? '' : '-' }}€{{ number_format(abs($netProfit), 2) }}
                </h3>
            </div>
        </div>

        {{-- ─── Profit Line Chart ───────────────────────── --}}
        @if(count($chartLabels) > 1)
        <div class="glass p-5 rounded-3xl border border-white/5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-500/15 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 12l3-3 3 3 4-4"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 21l4-4 4 4M3 4h18"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-amber-400/60 uppercase tracking-widest font-bold leading-none mb-0.5">Performance</p>
                        <h4 class="text-base font-black text-amber-400 leading-tight">Profit Trend</h4>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-[10px] font-bold text-white/50">
                    <span><span class="badge-dot" style="background:#4ade80"></span>Income</span>
                    <span><span class="badge-dot" style="background:#f87171"></span>Expenses</span>
                    <span><span class="badge-dot" style="background:#fbbf24"></span>Profit</span>
                </div>
            </div>
            <div style="height:200px;">
                <canvas id="profitLineChart"></canvas>
            </div>
        </div>
        @endif

        {{-- ─── Income vs Expense Bar Chart ────────────── --}}
        <div class="glass p-5 rounded-3xl border border-white/5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-xl bg-blue-500/15 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] text-blue-400/60 uppercase tracking-widest font-bold leading-none mb-0.5">Overview</p>
                    <h4 class="text-base font-black text-blue-400 leading-tight">Income vs Expenses</h4>
                </div>
            </div>
            <div style="height:160px;">
                <canvas id="summaryBarChart"></canvas>
            </div>
        </div>

        {{-- ─── Breakdowns ──────────────────────────────── --}}
        {{-- Breakdown by Type/Category --}}
        @if($incomeByType->isNotEmpty() || $expenseByCategory->isNotEmpty())
        <div class="grid grid-cols-2 gap-3">

            {{-- Income Breakdown --}}
            @if($incomeByType->isNotEmpty())
            <div class="glass p-4 rounded-2xl border border-white/5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2 h-2 rounded-full bg-green-400 inline-block"></span>
                    <p class="text-[10px] text-green-400/80 uppercase tracking-widest font-black">Income by Type</p>
                </div>
                <div class="space-y-2">
                    @foreach($incomeByType as $item)
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-white/60 capitalize">{{ $item->type ?: 'Uncategorized' }}</span>
                        <span class="text-xs font-bold text-green-400">€{{ number_format($item->total, 2) }}</span>
                    </div>
                    <div class="h-1 rounded-full bg-white/5">
                        @if($totalIncome > 0)
                        <div class="h-1 rounded-full bg-green-500" style="width:{{ min(100, ($item->total / $totalIncome) * 100) }}%"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Expense Breakdown --}}
            @if($expenseByCategory->isNotEmpty())
            <div class="glass p-4 rounded-2xl border border-white/5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span>
                    <p class="text-[10px] text-red-400/80 uppercase tracking-widest font-black">Expenses by Cat.</p>
                </div>
                <div class="space-y-2">
                    @foreach($expenseByCategory as $item)
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-white/60 capitalize">{{ $item->category ?: 'Uncategorized' }}</span>
                        <span class="text-xs font-bold text-red-400">€{{ number_format($item->total, 2) }}</span>
                    </div>
                    <div class="h-1 rounded-full bg-white/5">
                        @if($totalExpenses > 0)
                        <div class="h-1 rounded-full bg-red-500" style="width:{{ min(100, ($item->total / $totalExpenses) * 100) }}%"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Breakdown by Method --}}
        @if($incomeByMethod->isNotEmpty() || $expenseByMethod->isNotEmpty())
        <div class="grid grid-cols-2 gap-3">

            {{-- Income by Method --}}
            @if($incomeByMethod->isNotEmpty())
            <div class="glass p-4 rounded-2xl border border-white/5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                    <p class="text-[10px] text-amber-400/80 uppercase tracking-widest font-black">Income by Method</p>
                </div>
                <div class="space-y-2">
                    @foreach($incomeByMethod as $item)
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-white/60 capitalize">{{ $item->method }}</span>
                        <span class="text-xs font-bold text-amber-400">€{{ number_format($item->total, 2) }}</span>
                    </div>
                    <div class="h-1 rounded-full bg-white/5">
                        @if($totalIncome > 0)
                        <div class="h-1 rounded-full bg-amber-500" style="width:{{ min(100, ($item->total / $totalIncome) * 100) }}%"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Expense by Method --}}
            @if($expenseByMethod->isNotEmpty())
            <div class="glass p-4 rounded-2xl border border-white/5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2 h-2 rounded-full bg-blue-400 inline-block"></span>
                    <p class="text-[10px] text-blue-400/80 uppercase tracking-widest font-black">Expenses by Method</p>
                </div>
                <div class="space-y-2">
                    @foreach($expenseByMethod as $item)
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-white/60 capitalize">{{ $item->method }}</span>
                        <span class="text-xs font-bold text-blue-400">€{{ number_format($item->total, 2) }}</span>
                    </div>
                    <div class="h-1 rounded-full bg-white/5">
                        @if($totalExpenses > 0)
                        <div class="h-1 rounded-full bg-blue-500" style="width:{{ min(100, ($item->total / $totalExpenses) * 100) }}%"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- ─── Chart.js scripts ────────────────────────────── --}}
    <script>
        // ── Helper ──────────────────────────────────────
        const chartDefaults = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
        };
        const gridColor   = 'rgba(255,255,255,0.05)';
        const tickColor   = 'rgba(255,255,255,0.25)';
        const axisStyle   = { grid: { color: gridColor }, ticks: { color: tickColor, font: { family: 'Outfit', size: 10 } } };

        const labels   = @json($chartLabels);
        const incomeD  = @json($chartIncome);
        const expenseD = @json($chartExpense);
        const profitD  = @json($chartProfit);

        // ── Profit line chart ──────────────────────────
        @if(count($chartLabels) > 1)
        (function() {
            const ctx = document.getElementById('profitLineChart').getContext('2d');

            // Gradient fill for profit
            const grad = ctx.createLinearGradient(0, 0, 0, 200);
            grad.addColorStop(0, 'rgba(251,191,36,0.25)');
            grad.addColorStop(1, 'rgba(251,191,36,0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Income',
                            data: incomeD,
                            borderColor: '#4ade80',
                            backgroundColor: 'transparent',
                            borderWidth: 1.5,
                            pointRadius: 0,
                            tension: 0.4,
                        },
                        {
                            label: 'Expenses',
                            data: expenseD,
                            borderColor: '#f87171',
                            backgroundColor: 'transparent',
                            borderWidth: 1.5,
                            pointRadius: 0,
                            tension: 0.4,
                        },
                        {
                            label: 'Profit',
                            data: profitD,
                            borderColor: '#fbbf24',
                            backgroundColor: grad,
                            borderWidth: 2.5,
                            pointRadius: labels.length <= 14 ? 3 : 0,
                            pointBackgroundColor: '#fbbf24',
                            tension: 0.4,
                            fill: true,
                        },
                    ]
                },
                options: {
                    ...chartDefaults,
                    scales: {
                        x: { ...axisStyle, grid: { display: false } },
                        y: { ...axisStyle, beginAtZero: false },
                    },
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(10,10,10,0.9)',
                            borderColor: 'rgba(255,255,255,0.1)',
                            borderWidth: 1,
                            titleColor: '#fff',
                            bodyColor: 'rgba(255,255,255,0.6)',
                            callbacks: {
                                label: ctx => ` ${ctx.dataset.label}: €${ctx.parsed.y.toFixed(2)}`
                            }
                        }
                    }
                }
            });
        })();
        @endif

        // ── Summary bar chart ──────────────────────────
        (function() {
            const ctx = document.getElementById('summaryBarChart').getContext('2d');
            const totalIncome   = {{ $totalIncome }};
            const totalExpenses = {{ $totalExpenses }};
            const netProfit     = {{ $netProfit }};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Income', 'Expenses', 'Net Profit'],
                    datasets: [{
                        data: [totalIncome, totalExpenses, Math.abs(netProfit)],
                        backgroundColor: [
                            'rgba(74,222,128,0.7)',
                            'rgba(248,113,113,0.7)',
                            netProfit >= 0 ? 'rgba(251,191,36,0.7)' : 'rgba(239,68,68,0.7)',
                        ],
                        borderRadius: 10,
                        barThickness: 44,
                    }]
                },
                options: {
                    ...chartDefaults,
                    scales: {
                        x: { ...axisStyle, grid: { display: false } },
                        y: { ...axisStyle, beginAtZero: true },
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(10,10,10,0.9)',
                            borderColor: 'rgba(255,255,255,0.1)',
                            borderWidth: 1,
                            callbacks: {
                                label: ctx => ` €${ctx.parsed.y.toFixed(2)}`
                            }
                        }
                    }
                }
            });
        })();

        // ── Tab / mode logic ───────────────────────────
        function setMode(mode) {
            document.getElementById('modeInput').value = mode;

            // Update active tab styles
            ['daily','weekly','monthly','custom'].forEach(k => {
                document.getElementById('tab-' + k).classList.toggle('active', k === mode);
            });

            const range = document.getElementById('customRange');
            if (mode === 'custom') {
                range.classList.add('open');
            } else {
                range.classList.remove('open');
                // Submit immediately for non-custom modes
                document.getElementById('filterForm').submit();
            }
        }
    </script>

</x-app-layout>

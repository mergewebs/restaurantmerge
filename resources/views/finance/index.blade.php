<x-app-layout>
    <div data-async-region="finance-index" data-async-mutations="true">
        @if(session('success'))
            <div class="hidden" data-async-feedback data-feedback-level="success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="hidden" data-async-feedback data-feedback-level="error">{{ session('error') }}</div>
        @elseif($errors->any())
            <div class="hidden" data-async-feedback data-feedback-level="error">{{ $errors->first() }}</div>
        @endif
@php
    $statsRangeOptions = [
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        'this_week' => 'This week',
        'last_week' => 'Last week',
        'this_month' => 'This month',
        'last_month' => 'Last month',
        'this_year' => 'This year',
        'last_year' => 'Last year',
        'last_3_months' => 'Last 3 months',
        'custom' => 'Custom date range',
    ];

@endphp
<div x-data="{ 
    showFinanceModal: {{ (($errors->any() && old('amount') && !old('finance_id')) || session('keep_finance_modal') || request()->boolean('finance_modal')) ? 'true' : 'false' }}, 
    showEditModal: {{ $errors->any() && old('finance_id') ? 'true' : 'false' }}, 
    finance_type: '{{ old('finance_type', 'income') }}', 
    editingFinance: { 
        id: '{{ old('finance_id') }}', 
        amount: '{{ old('amount') }}', 
        date: '{{ old('date') }}', 
        finance_type: '{{ old('finance_type') }}', 
        originalType: '{{ old('type') }}', 
        method: '{{ old('method') }}', 
        category: '{{ old('category') }}', 
        description: '{{ old('description') }}',
        showDesc: {{ old('description') ? 'true' : 'false' }}
    }, 
    editFinance(item) { 
        this.editingFinance = { 
            id: item.id, 
            amount: item.amount, 
            date: item.date ? (typeof item.date === 'string' ? item.date.substring(0, 10) : item.date) : '', 
            finance_type: item.transaction_type, 
            originalType: item.transaction_type, 
            method: item.method, 
            category: item.category || '', 
            description: item.description || '',
            showDesc: item.description ? true : false
        }; 
        this.showEditModal = true; 
    } 
}" class="space-y-6">

    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 bg-indigo-600 rounded-full shadow-sm"></div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Finance Overview</h1>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('finance.categories.index') }}" data-async="off" 
               class="w-10 h-10 bg-gray-50 border border-gray-200 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-100 rounded-xl flex items-center justify-center transition-all shadow-sm active:scale-95" 
               title="Manage Categories">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </a>
            <button @click="showFinanceModal = true" 
                    class="w-10 h-10 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl flex items-center justify-center shadow-md shadow-emerald-600/10 hover:scale-105 active:scale-95 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </button>
        </div>
    </div>


    <section class="glass p-6 rounded-[28px] space-y-4">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-2.5">
                <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                <h2 class="text-lg font-black text-slate-800 uppercase tracking-tight">Summary Overview</h2>
            </div>
            <form method="GET" action="{{ route('finance.index') }}" class="w-full lg:w-auto lg:ml-auto" data-auto-submit-form>
                <input type="hidden" name="history_query" value="{{ $query }}">

                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                    <select
                        name="mode"
                        data-range-toggle-target="finance-summary-custom-range"
                        data-range-toggle-value="custom"
                        data-auto-submit-skip-on="custom"
                        class="w-full sm:w-auto !w-full sm:!w-auto bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-slate-700 text-xs font-bold transition focus:border-indigo-500 outline-none"
                    >
                        @foreach($statsRangeOptions as $value => $label)
                            <option value="{{ $value }}" @selected($mode === $value) class="bg-white text-slate-800">{{ $label }}</option>
                        @endforeach
                    </select>

                    <div
                        data-range-toggle-panel="finance-summary-custom-range"
                        @class(['flex flex-col gap-3 sm:flex-row sm:items-center', 'hidden' => $mode !== 'custom'])
                    >
                        <input type="date" name="date_from" value="{{ $dateFrom }}" class="bg-gray-50 border border-gray-200 px-3 py-2 rounded-xl text-slate-700 text-xs font-bold outline-none focus:border-indigo-500">
                        <input type="date" name="date_to" value="{{ $dateTo }}" class="bg-gray-50 border border-gray-200 px-3 py-2 rounded-xl text-slate-700 text-xs font-bold outline-none focus:border-indigo-500">
                    </div>
                    <a href="{{ route('finance.index') }}" class="w-full sm:w-auto px-5 py-3 bg-gray-50 border border-gray-200 hover:bg-gray-100 text-slate-700 rounded-2xl text-xs font-bold uppercase tracking-widest flex items-center justify-center transition-all shadow-sm active:scale-95">Reset</a>
                </div>
            </form>
        </div>
        <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest leading-none">{{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Income Card -->
            <div class="glass kpi-card kpi-income rounded-[24px] p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-extrabold">Total Income</p>
                        <span class="w-8 h-8 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                        </span>
                    </div>
                    <div class="mt-3 flex items-end justify-between gap-3">
                        <p class="text-2xl font-black text-emerald-600">€{{ number_format($totalIncome, 2) }}</p>
                        @if($incomeByMethod->isNotEmpty())
                            <div data-payment-method-summary="income" class="min-w-[6.5rem] shrink-0 border-l border-emerald-100 pl-3 text-right">
                                <p class="mb-1 text-[8px] font-black uppercase tracking-widest text-slate-400">Payment</p>
                                @foreach($incomeByMethod as $item)
                                    <div class="flex items-center justify-between gap-2 text-[10px] font-bold">
                                        <span class="capitalize text-slate-500">{{ $item->method }}</span>
                                        <span class="font-black text-emerald-700">&euro;{{ number_format($item->total, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($incomeByType->isNotEmpty())
                <div class="mt-4 pt-3 border-t border-emerald-100/50 space-y-1.5 text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                    @foreach($incomeByType as $item)
                        <div class="flex justify-between items-center gap-2">
                            <span class="truncate">{{ $item->type ?: 'Uncategorized' }}</span>
                            <span class="text-emerald-600 font-extrabold shrink-0">€{{ number_format($item->total, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
            <!-- Expense Card -->
            <div class="glass kpi-card kpi-expense rounded-[24px] p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-extrabold">Total Expenses</p>
                        <span class="w-8 h-8 rounded-full bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                        </span>
                    </div>
                    <div class="mt-3 flex items-end justify-between gap-3">
                        <p class="text-2xl font-black text-rose-600">€{{ number_format($totalExpenses, 2) }}</p>
                        @if($expenseByMethod->isNotEmpty())
                            <div data-payment-method-summary="expense" class="min-w-[6.5rem] shrink-0 border-l border-rose-100 pl-3 text-right">
                                <p class="mb-1 text-[8px] font-black uppercase tracking-widest text-slate-400">Payment</p>
                                @foreach($expenseByMethod as $item)
                                    <div class="flex items-center justify-between gap-2 text-[10px] font-bold">
                                        <span class="capitalize text-slate-500">{{ $item->method }}</span>
                                        <span class="font-black text-rose-700">&euro;{{ number_format($item->total, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($expenseByCategory->isNotEmpty())
                <div class="mt-4 pt-3 border-t border-rose-100/50 space-y-1.5 text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                    @foreach($expenseByCategory as $item)
                        <div class="flex justify-between items-center gap-2">
                            <span class="truncate">{{ $item->category ?: 'Uncategorized' }}</span>
                            <span class="text-rose-600 font-extrabold shrink-0">€{{ number_format($item->total, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
            <!-- Profit Card -->
            <div class="glass kpi-card kpi-profit {{ $netProfit < 0 ? 'negative' : '' }} rounded-[24px] p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-extrabold">Net Profit</p>
                        <span class="w-8 h-8 rounded-full {{ $netProfit >= 0 ? 'bg-amber-50 border-amber-100 text-amber-600' : 'bg-rose-50 border-rose-100 text-rose-600' }} flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                    </div>
                    <p class="text-2xl font-black {{ $netProfit >= 0 ? 'text-amber-600' : 'text-rose-600' }} mt-3">{{ $netProfit >= 0 ? '' : '-' }}€{{ number_format(abs($netProfit), 2) }}</p>
                </div>
            </div>
        </div>

    </section>

    <section id="transactions" class="glass p-6 rounded-[28px] space-y-4">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                <div>
                    <h2 class="text-lg font-black text-slate-800 uppercase tracking-tight">Filtered Transactions</h2>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Uses the summary date range above</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-500">
                <span class="px-3 py-2 rounded-2xl border border-gray-200 bg-gray-50">From: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}</span>
                <span class="px-3 py-2 rounded-2xl border border-gray-200 bg-gray-50">To: {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</span>
            </div>
        </div>

        <form action="{{ route('finance.index') }}#transactions" method="GET" class="space-y-3" data-auto-submit-form data-auto-submit-delay="700">
            <input type="hidden" name="mode" value="{{ $mode }}">
            <input type="hidden" name="date_from" value="{{ $dateFrom }}">

            <input type="hidden" name="date_to" value="{{ $dateTo }}">

            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <input type="text" name="history_query" value="{{ $query }}" placeholder="Search description or amount..." data-auto-submit-search class="w-full lg:max-w-xl bg-gray-50 border border-gray-200 text-slate-800 text-sm px-4 py-3 rounded-2xl outline-none focus:border-indigo-500 focus:bg-white transition-all">
            </div>
        </form>

        <div class="space-y-3">
            @forelse($history as $item)
                <div class="glass p-4 rounded-3xl border border-gray-100 flex items-center justify-between hover-lift transition-all duration-300 relative overflow-hidden group">
                    <!-- Left dynamic stripe indicator -->
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $item->transaction_type === 'income' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                    
                    <div class="flex items-center gap-3 pl-2 min-w-0">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center border {{ $item->transaction_type === 'income' ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : 'bg-rose-50 border-rose-100 text-rose-600' }} shrink-0">
                            @if($item->transaction_type === 'income')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                            @endif
                        </div>
                        
                        <div class="text-left min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-sm font-bold text-slate-800 capitalize leading-tight truncate max-w-[140px] sm:max-w-none">
                                    {{ $item->description ?: ($item->transaction_type === 'income' ? 'Income Entry' : 'Expense Entry') }}
                                </p>
                                <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-lg border {{ $item->method === 'cash' ? 'border-amber-200 text-amber-700 bg-amber-50' : 'border-indigo-100 text-indigo-600 bg-indigo-50' }} shrink-0">
                                    {{ $item->method }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                <span class="text-[10px] text-slate-400 font-bold tracking-wider uppercase leading-none">
                                    {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                                </span>
                                @if($item->category)
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider leading-none">
                                    • #{{ $item->category }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-right flex items-center gap-3 shrink-0">
                        <p class="text-sm font-black {{ $item->transaction_type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $item->transaction_type === 'income' ? '+' : '-' }}€{{ number_format($item->amount, 2) }}
                        </p>
                        
                        <button @click="editFinance({{ $item->toJson() }})" 
                                title="Edit Entry"
                                class="w-10 h-10 flex items-center justify-center bg-gray-50 border border-gray-200 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-100 rounded-xl transition-all shadow-sm active:scale-95">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="glass p-12 rounded-3xl border border-gray-100 text-center">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <p class="text-slate-400 font-medium">No transactions match your search filter</p>
                </div>
            @endforelse
        </div>

        @if($history->hasPages())
            <div class="pt-2">{{ $history->links() }}</div>
        @endif
    </section>

    <section class="glass p-6 rounded-[28px] space-y-4">
        <div class="flex items-center gap-2.5">
            <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
            <h2 class="text-lg font-black text-slate-800 uppercase tracking-tight">Latest Transactions</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($latestTransactions as $item)
                <div class="glass p-4 rounded-3xl border border-gray-100 flex items-center justify-between hover-lift transition-all duration-300 relative overflow-hidden group">
                    <!-- Left dynamic stripe indicator -->
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $item->transaction_type === 'income' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                    
                    <div class="flex items-center gap-3 pl-2 min-w-0">
                        <div class="text-left min-w-0">
                            <p class="text-sm font-bold text-slate-800 capitalize leading-tight truncate">
                                {{ $item->description ?: ($item->transaction_type === 'income' ? 'Income Entry' : 'Expense Entry') }}
                            </p>
                            <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mt-1 leading-none">
                                {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }} | {{ $item->method }}
                            </p>
                        </div>
                    </div>
                    
                    <p class="text-sm font-black {{ $item->transaction_type === 'income' ? 'text-emerald-600' : 'text-rose-600' }} shrink-0">
                        {{ $item->transaction_type === 'income' ? '+' : '-' }}€{{ number_format($item->amount, 2) }}
                    </p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Modals moved to root layout wrapper level to overlay top header completely -->
    <!-- Add Finance Modal -->
    <div x-show="showFinanceModal" 
         class="finance-add-modal fixed inset-0 z-[100] flex items-center justify-center px-4"
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
                <h3 class="text-xl font-bold text-slate-800">Add Finance</h3>
                <button @click="showFinanceModal = false" class="text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            @if (($errors->any() || session('error')) && old('amount') && !old('finance_id'))
                <div class="mb-4 bg-red-50 border border-red-200 p-3 rounded-xl text-xs text-red-800">
                    @if(session('error'))
                        <p class="mb-2 font-bold">{{ session('error') }}</p>
                    @endif
                    @if($errors->any())
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            <form action="{{ route('finance.store') }}" method="POST" class="space-y-3 sm:space-y-4 text-left">
                @csrf
                
                <div class="mt-2 mb-6">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 block">Amount (€)</label>
                    <div class="relative flex items-center w-full transition-all duration-300">
                        <span class="absolute left-5 text-2xl font-black transition-colors"
                              :class="finance_type === 'income' ? 'text-emerald-500' : 'text-rose-500'">€</span>
                        <input type="number" step="0.01" name="amount" placeholder="0.00" value="{{ (session('keep_finance_modal') || request()->boolean('finance_modal')) ? '' : old('amount') }}" required autofocus
                            class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 text-3xl font-black outline-none transition-all duration-300"
                            :class="finance_type === 'income' ? 'bg-emerald-50 border-emerald-200 text-emerald-700 focus:border-emerald-500 focus:ring-emerald-500 shadow-lg shadow-emerald-500/15' : 'bg-rose-50 border-rose-200 text-rose-700 focus:border-rose-500 focus:ring-rose-500 shadow-lg shadow-rose-500/15'"
                            style="min-height: 4.5rem !important; padding-left: 3.5rem !important;">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Date</label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                           class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-accent">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Type</label>
                        <select name="finance_type" x-model="finance_type" required class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-accent">
                            <option value="income" {{ old('finance_type') == 'income' ? 'selected' : '' }} class="bg-gray-900">Income</option>
                            <option value="expense" {{ old('finance_type') == 'expense' ? 'selected' : '' }} class="bg-gray-900">Expense</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Method</label>
                        <select name="method" required class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-accent">
                            <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }} class="bg-gray-900">Cash</option>
                            <option value="card" {{ old('method') == 'card' ? 'selected' : '' }} class="bg-gray-900">Card</option>
                        </select>
                    </div>
                </div>
                
                <div x-data="{ showDesc: {{ old('description') ? 'true' : 'false' }} }" class="space-y-3">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Category</label>
                        <select name="category" class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-accent">
                            <option value="" {{ old('category') == '' ? 'selected' : '' }} class="bg-gray-900">None</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->name }}" 
                                        x-show="finance_type === '{{ $cat->type }}'"
                                        {{ old('category') == $cat->name ? 'selected' : '' }} 
                                        class="bg-gray-900">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-1">
                        <button x-show="!showDesc" type="button" @click="showDesc = true" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors flex items-center gap-1">
                            <span>+ Add Description</span>
                        </button>
                        <div x-show="showDesc" x-transition>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Description</label>
                            <input type="text" name="description" value="{{ old('description') }}"
                                   class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-accent">
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full btn-accent py-4 shadow-xl shadow-accent/20 text-white font-bold uppercase tracking-widest">Save Entry</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Finance Modal -->
    <div x-show="showEditModal" 
         class="finance-edit-modal fixed inset-0 z-[100] flex items-center justify-center px-4"
         style="display: none;"
         x-cloak>
        <div @click="showEditModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="glass w-full max-w-2xl rounded-[32px] p-8 relative border border-white/10"
             x-show="showEditModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-slate-800">Edit Finance Entry</h3>
                <button @click="showEditModal = false" class="text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            @if (($errors->any() || session('error')) && old('finance_id'))
                <div class="mb-4 bg-red-50 border border-red-200 p-3 rounded-xl text-xs text-red-800">
                    @if(session('error'))
                        <p class="mb-2 font-bold">{{ session('error') }}</p>
                    @endif
                    @if($errors->any())
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            <form :action="@js(route('finance.update', ['id' => '__FINANCE__'])).replace('__FINANCE__', editingFinance.id)" method="POST" class="space-y-3 sm:space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="finance_id" :value="editingFinance.id">
                <input type="hidden" name="type" :value="editingFinance.originalType">

                <div class="mt-2 mb-6">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 block">Amount (€)</label>
                    <div class="relative flex items-center w-full transition-all duration-300">
                        <span class="absolute left-5 text-2xl font-black transition-colors"
                              :class="editingFinance.finance_type === 'income' ? 'text-emerald-500' : 'text-rose-500'">€</span>
                        <input type="number" step="0.01" name="amount" placeholder="0.00" x-model="editingFinance.amount" required
                            class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 text-3xl font-black outline-none transition-all duration-300"
                            :class="editingFinance.finance_type === 'income' ? 'bg-emerald-50 border-emerald-200 text-emerald-700 focus:border-emerald-500 focus:ring-emerald-500 shadow-lg shadow-emerald-500/15' : 'bg-rose-50 border-rose-200 text-rose-700 focus:border-rose-500 focus:ring-rose-500 shadow-lg shadow-rose-500/15'"
                            style="min-height: 4.5rem !important; padding-left: 3.5rem !important;">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Date</label>
                    <input type="date" name="date" x-model="editingFinance.date" required
                           class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-accent">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Type</label>
                        <select name="finance_type" x-model="editingFinance.finance_type" required class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-accent">
                            <option value="income" class="bg-gray-900">Income</option>
                            <option value="expense" class="bg-gray-900">Expense</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Method</label>
                        <select name="method" x-model="editingFinance.method" required class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-accent">
                            <option value="cash" class="bg-gray-900">Cash</option>
                            <option value="card" class="bg-gray-900">Card</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Category</label>
                        <select name="category" x-model="editingFinance.category" class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-accent">
                            <option value="" class="bg-gray-900">None</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->name }}" 
                                        x-show="editingFinance.finance_type === '{{ $cat->type }}'"
                                        class="bg-gray-900">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-1">
                        <button x-show="!editingFinance.showDesc" type="button" @click="editingFinance.showDesc = true" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors flex items-center gap-1">
                            <span>+ Add Description</span>
                        </button>
                        <div x-show="editingFinance.showDesc" x-transition>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Description</label>
                            <input type="text" name="description" x-model="editingFinance.description"
                                   class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-accent">
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" 
                            @click="if(confirm('Delete this entry?')) $refs.deleteEntryForm.action = @js(route('finance.destroy', ['id' => '__FINANCE__'])).replace('__FINANCE__', editingFinance.id) + '?type=' + editingFinance.originalType; $refs.deleteEntryForm.submit()" 
                            class="flex-1 bg-red-500/10 text-red-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500/20 transition-all border border-red-500/20 active:scale-95">
                        Delete
                    </button>
                    <button type="submit" 
                            class="flex-[3] btn-accent py-4 shadow-lg shadow-accent/20 text-white font-bold uppercase tracking-widest">
                        Save Changes
                    </button>
                </div>
            </form>
            <form method="POST" x-ref="deleteEntryForm" class="hidden">@csrf @method('DELETE')</form>
        </div>
    </div>
</div>
    </div>
</x-app-layout>



<x-app-layout>
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
            description: '{{ old('description') }}'
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
                description: item.description || ''
            };
            this.showEditModal = true;
        }
    }" class="space-y-6">
        {{-- ─── Header Section ─────────────────────────── --}}
        <div class="flex items-center justify-between border-b border-white/5 pb-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-amber-500 rounded-full shadow-[0_0_15px_rgba(245,158,11,0.4)]"></div>
                <div>
                    <h3 class="text-xl font-black text-white uppercase tracking-tight">Finance History</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-0.5">Logs & Transactions</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('finance.categories.index') }}" class="w-10 h-10 bg-white/5 border border-white/10 text-white rounded-2xl flex items-center justify-center hover:bg-white/10 transition-all shadow-lg" title="Manage Categories">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </a>
                <a href="{{ route('finance.stats') }}" class="w-10 h-10 bg-white/5 border border-white/10 text-white rounded-2xl flex items-center justify-center hover:bg-white/10 transition-all shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
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

        {{-- ─── Search & Filters ────────────────────────── --}}
        <div class="glass p-4 rounded-3xl border border-white/5">
            <form action="{{ route('finance.history') }}" method="GET" class="space-y-4">
                <div class="relative">
                    <input type="text" name="query" value="{{ $query }}" placeholder="Search description or amount..." 
                           class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-2xl text-xs font-bold text-white placeholder-white/20 focus:border-koya outline-none transition-all">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] font-black text-white/30 uppercase tracking-widest mb-1.5 block px-1">From</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}" 
                               class="w-full bg-white/5 border border-white/10 rounded-2xl text-xs font-bold text-white p-3">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-white/30 uppercase tracking-widest mb-1.5 block px-1">To</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}"
                               class="w-full bg-white/5 border border-white/10 rounded-2xl text-xs font-bold text-white p-3">
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 btn-koya py-3 text-xs">Apply Filters</button>
                    @if($query || $dateFrom || $dateTo)
                        <a href="{{ route('finance.history') }}" class="px-4 bg-white/5 border border-white/10 text-white rounded-2xl flex items-center justify-center hover:bg-white/10 transition-all text-xs font-bold">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ─── History List ───────────────────────────── --}}
        <div class="space-y-3">
            @forelse($history as $item)
                <div class="glass p-4 rounded-3xl border border-white/5 flex items-center justify-between group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $item->transaction_type === 'income' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}">
                            @if($item->transaction_type === 'income')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-black text-white capitalize">{{ $item->description ?: ($item->transaction_type === 'income' ? 'Income' : 'Expense') }}</p>
                                <span class="text-[8px] font-black uppercase px-1.5 py-0.5 rounded-md border {{ $item->method === 'cash' ? 'border-amber-500/30 text-amber-500 bg-amber-500/5' : 'border-blue-500/30 text-blue-500 bg-blue-500/5' }}">
                                    {{ $item->method }}
                                </span>
                                <span class="text-[8px] font-black uppercase text-white/30">#{{ $item->category }}</span>
                            </div>
                            <p class="text-[10px] text-white/40 font-bold mt-0.5">
                                {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }} • {{ $item->user->name ?? 'System' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="text-right flex items-center gap-3">
                        <div>
                            <p class="text-base font-black {{ $item->transaction_type === 'income' ? 'text-green-400' : 'text-red-400' }}">
                                {{ $item->transaction_type === 'income' ? '+' : '-' }}€{{ number_format($item->amount, 2) }}
                            </p>
                        </div>
                        
                        <button @click="editFinance({{ $item->toJson() }})" class="flex items-center gap-2 px-3 py-2 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 transition-all shadow-sm">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Edit</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="glass p-12 rounded-[32px] border border-white/5 text-center">
                    <div class="w-16 h-16 bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-white/40 uppercase tracking-widest">No transactions found</p>
                </div>
            @endforelse
        </div>

        {{-- ─── Add Finance Modal ──────────────────────── --}}
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

                <form action="{{ route('finance.store') }}" method="POST" class="space-y-4">
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

        {{-- ─── Edit Finance Modal ─────────────────────── --}}
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
                    <h3 class="text-xl font-bold text-white">Edit Finance Entry</h3>
                    <button @click="showEditModal = false" class="text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                @if ($errors->any() && old('finance_id'))
                    <div class="mb-4 bg-red-500/20 border border-red-500/50 p-3 rounded-xl text-xs text-red-200">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form :action="@js(route('finance.update', ['id' => '__FINANCE__'])).replace('__FINANCE__', editingFinance.id)" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="finance_id" :value="editingFinance.id">
                    <input type="hidden" name="type" :value="editingFinance.originalType"> {{-- for controller to find record --}}

                    <div class="mt-2 mb-6">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 block">Amount (&euro;)</label>
                        <div class="relative flex items-center w-full transition-all duration-300">
                            <span class="absolute left-5 text-2xl font-black transition-colors"
                                  :class="editingFinance.finance_type === 'income' ? 'text-emerald-500' : 'text-rose-500'">&euro;</span>
                            <input type="number" step="0.01" name="amount" placeholder="0.00" x-model="editingFinance.amount" required
                                   class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 text-3xl font-black outline-none transition-all duration-300"
                                   :class="editingFinance.finance_type === 'income' ? 'bg-emerald-50 border-emerald-200 text-emerald-700 focus:border-emerald-500 focus:ring-emerald-500 shadow-lg shadow-emerald-500/15' : 'bg-rose-50 border-rose-200 text-rose-700 focus:border-rose-500 focus:ring-rose-500 shadow-lg shadow-rose-500/15'"
                                   style="min-height: 4.5rem !important; padding-left: 3.5rem !important;">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Date</label>
                        <input type="date" name="date" x-model="editingFinance.date" required
                               class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Type</label>
                            <select name="finance_type" x-model="editingFinance.finance_type" required class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                                <option value="income" class="bg-gray-900">Income</option>
                                <option value="expense" class="bg-gray-900">Expense</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Method</label>
                            <select name="method" x-model="editingFinance.method" required class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                                <option value="cash" class="bg-gray-900">Cash</option>
                                <option value="card" class="bg-gray-900">Card</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Category</label>
                            <select name="category" x-model="editingFinance.category" class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                                <option value="" class="bg-gray-900">None</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}" 
                                            x-show="editingFinance.finance_type === '{{ $cat->type }}'"
                                            class="bg-gray-900">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Description (Optional)</label>
                            <input type="text" name="description" x-model="editingFinance.description"
                                   class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" 
                                @click="if(confirm('Delete this entry?')) $refs.deleteEntryForm.action = @js(route('finance.destroy', ['id' => '__FINANCE__'])).replace('__FINANCE__', editingFinance.id) + '?type=' + editingFinance.originalType; $refs.deleteEntryForm.submit()"
                                class="flex-1 bg-red-500/10 text-red-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500/20 transition-all border border-red-500/20 active:scale-95">
                            Delete
                        </button>
                        <button type="submit" class="flex-[3] btn-koya py-4 shadow-lg shadow-koya/20 text-white font-bold uppercase tracking-widest">Save Changes</button>
                    </div>
                </form>

                <form method="POST" x-ref="deleteEntryForm" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>

        {{-- ─── Pagination ────────────────────────────── --}}
        @if($history->hasPages())
        <div class="mt-8 px-2">
            {{ $history->links() }}
        </div>
        @endif
    </div>

    <style>
        /* Custom Pagination Styling */
        nav[role="navigation"] {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        /* Hide "Showing X to Y" on mobile if needed, or style it */
        nav[role="navigation"] div:first-child p {
            color: rgba(255,255,255,0.3) !important;
            font-size: 10px !important;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 0.1em;
        }

        /* Container for numbers */
        nav[role="navigation"] div:last-child span.relative.z-0,
        nav[role="navigation"] div:last-child div.flex.justify-between.flex-1 {
            display: flex;
            gap: 0.4rem;
            border-radius: 20px;
            background: rgba(255,255,255,0.03);
            padding: 0.4rem;
            border: 1px solid rgba(255,255,255,0.05);
        }

        /* Individual Links */
        nav[role="navigation"] a, 
        nav[role="navigation"] span[aria-current="page"] span,
        nav[role="navigation"] span[aria-disabled="true"] span {
            display: flex !important;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            border-radius: 14px !important;
            font-size: 0.75rem !important;
            font-weight: 900 !important;
            transition: all 0.2s ease;
            border: 1px solid transparent !important;
        }

        /* Default Link State */
        nav[role="navigation"] a {
            background: rgba(255,255,255,0.05) !important;
            color: rgba(255,255,255,0.6) !important;
        }
        nav[role="navigation"] a:hover {
            background: rgba(255,255,255,0.1) !important;
            color: white !important;
            transform: translateY(-1px);
        }

        /* Active Page */
        nav[role="navigation"] span[aria-current="page"] span {
            background: #FF5C00 !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(255,92,0,0.3);
        }

        /* Disabled State (Dots) */
        nav[role="navigation"] span[aria-disabled="true"] span {
            background: transparent !important;
            color: rgba(255,255,255,0.2) !important;
        }

        /* Icons (Prev/Next) */
        nav[role="navigation"] svg {
            width: 1.25rem !important;
            height: 1.25rem !important;
        }
    </style>
</x-app-layout>


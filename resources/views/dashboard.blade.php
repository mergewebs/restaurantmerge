<x-app-layout>
    <style>
        /* Fade in animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .delay-75 { animation-delay: 75ms; }
        .delay-150 { animation-delay: 150ms; }
        .delay-225 { animation-delay: 225ms; }



        .btn-koya:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
            box-shadow: none;
        }
    </style>

    <!-- Daily Overview Stats -->
    <div data-page-feedback-wrapper>
        @if(session('success'))
            <div class="hidden" data-page-feedback data-feedback-level="success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="hidden" data-page-feedback data-feedback-level="error">{{ session('error') }}</div>
        @elseif($errors->any())
            <div class="hidden" data-page-feedback data-feedback-level="error">{{ $errors->first() }}</div>
        @endif
    </div>
    <div x-data="{ 
        showFinanceModal: {{ (auth()->user()->isAdmin() && ((($errors->any() || session('error')) && old('amount') && !old('finance_id')) || session('keep_finance_modal') || request()->boolean('finance_modal'))) ? 'true' : 'false' }},
        showReservationModal: {{ ($errors->any() || session('error')) && old('customer_name') && !old('reservation_id') ? 'true' : 'false' }},
        showEditModal: {{ ($errors->any() || session('error')) && old('reservation_id') ? 'true' : 'false' }},
        showEditFinanceModal: {{ (auth()->user()->isAdmin() && ($errors->any() || session('error')) && old('finance_id')) ? 'true' : 'false' }},
        finance_type: '{{ old('finance_type', 'income') }}',
        editingReservation: {
            id: '{{ old('reservation_id') }}',
            customer_name: '{{ old('customer_name') }}',
            party_size: '{{ old('party_size') }}',
            reservation_time: '{{ old('reservation_time') }}',
            customer_phone: '{{ old('customer_phone') }}',
            customer_email: '{{ old('customer_email') }}',
            notes: '{{ old('notes') }}',
            status: '{{ old('status') }}'
        },
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
        editRes(res) {
            this.editingReservation = {
                id: res.id,
                customer_name: res.customer_name,
                party_size: res.party_size,
                reservation_time: res.reservation_time.replace(' ', 'T').substring(0, 16),
                customer_phone: res.customer_phone || '',
                customer_email: res.customer_email || '',
                notes: res.notes || '',
                status: res.status
            };
            this.showEditModal = true;
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
            this.showEditFinanceModal = true;
        }
    }" class="space-y-6">


        <div class="space-y-8 @if(!auth()->user()->isAdmin()) max-w-4xl @endif mx-auto">
            <!-- First Row: Operations & Reservations -->
            <div class="space-y-6 animate-fade-in-up">
                <!-- Reservations Section -->
                <div class="space-y-6">
            @if($isClosedToday && $date == now()->toDateString())
                <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-center font-bold text-sm uppercase tracking-widest animate-pulse">
                    Reservations are STOPPED for today
                </div>
            @endif
            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-indigo-600 rounded-full shadow-sm"></div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 uppercase tracking-tight">Reservations</h3>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mt-0.5">Today's Schedule</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-indigo-50 text-indigo-700 text-[10px] px-3 py-1.5 rounded-full font-bold border border-indigo-100 uppercase tracking-widest">{{ $reservationsCount }} TOTAL</span>
                    
                    @if(auth()->user()->isAdmin())
                    <form action="{{ route('restaurant.toggle-today') }}" method="POST" class="flex">
                        @csrf
                        <button type="submit" 
                                title="{{ $isClosedToday ? 'Reservations are stopped. Click to open.' : 'Click to stop reservations for today' }}"
                                class="w-10 h-10 {{ $isClosedToday ? 'bg-rose-600 hover:bg-rose-700 shadow-rose-600/10' : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/10' }} text-white rounded-2xl flex items-center justify-center shadow-md hover:scale-105 active:scale-95 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($isClosedToday)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                                    @endif
                            </svg>
                        </button>
                    </form>
                    @endif

                    <button @click="showReservationModal = true" class="w-10 h-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl flex items-center justify-center shadow-md shadow-indigo-600/10 hover:scale-105 active:scale-95 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>
                </div>
            </div>


            @if($reservations->isEmpty())
                <div class="glass p-12 rounded-2xl border border-white/5 text-center">
                    <svg class="w-12 h-12 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-400">No reservations for today</p>
                    <button @click="showReservationModal = true" class="mt-4 inline-block text-koya font-bold">Reservation Today +</button>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($reservations as $res)
                    <div class="glass p-4 rounded-[20px] border border-gray-200 flex items-center justify-between gap-4 text-left hover-lift transition-all duration-300 relative overflow-hidden group">
                        <!-- Vertical status strip indicator -->
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 @if(($res->status ?? 'confirmed') === 'completed') bg-blue-500 @elseif(($res->status ?? 'confirmed') === 'cancelled') bg-red-500 @else bg-indigo-600 @endif"></div>
                        
                        <div class="flex-1 pl-3 min-w-0">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="text-indigo-600 font-extrabold text-xl leading-none shrink-0">{{ $res->reservation_time->format('H:i') }}</span>
                                <span class="text-slate-800 font-bold text-lg leading-none capitalize truncate max-w-[180px] sm:max-w-none">{{ $res->customer_name }}</span>
                                <span class="status-pill status-{{ $res->status ?? 'confirmed' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $res->status ?? 'confirmed' }}
                                </span>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <!-- People -->
                                <div class="flex items-center gap-1.5 text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-xl border border-indigo-100/50 text-xs font-bold shadow-sm shrink-0">
                                    <svg class="w-3.5 h-3.5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $res->party_size }} {{ $res->party_size === 1 ? 'Person' : 'People' }}</span>
                                </div>

                                <!-- Phone -->
                                @if($res->customer_phone)
                                <a href="tel:{{ $res->customer_phone }}" class="flex items-center gap-1.5 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-100 transition-all bg-slate-50 px-2.5 py-1 rounded-xl border border-slate-200 text-xs font-medium shadow-sm shrink-0">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span>{{ $res->customer_phone }}</span>
                                </a>
                                @endif

                                <!-- Email -->
                                @php $email = $res->customer_email ?? null; @endphp
                                @if($email)
                                <a href="mailto:{{ $email }}" class="flex items-center gap-1.5 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-100 transition-all bg-slate-50 px-2.5 py-1 rounded-xl border border-slate-200 text-xs font-medium shadow-sm shrink-0">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate max-w-[120px] sm:max-w-none">{{ $email }}</span>
                                </a>
                                @endif

                                <!-- Notes -->
                                @if($res->notes)
                                <div class="flex items-center gap-1.5 text-amber-800 bg-amber-50 px-2.5 py-1 rounded-xl border border-amber-200/60 text-xs font-medium shadow-sm shrink-0">
                                    <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    <span class="truncate max-w-[180px] sm:max-w-[280px]" title="{!! strip_tags($res->notes) !!}">{!! $res->notes !!}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Actions Button (3 Dots) -->
                        <div class="shrink-0">
                            <button @click="editRes({{ $res->toJson() }})" 
                                    title="Reservation Actions"
                                    class="w-10 h-10 flex items-center justify-center bg-gray-50 border border-gray-200 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-100 rounded-xl transition-all shadow-sm active:scale-95">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v.01M12 12v.01M12 19v.01"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div> <!-- Close operations row -->

    @if(auth()->user()->isAdmin())
    <!-- Second Row: Finances & Quick Actions -->
    <div class="space-y-6 animate-fade-in-up delay-75 border-t border-gray-200 pt-6">
        <!-- Finance Stats Section -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-emerald-600 rounded-full shadow-sm"></div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 uppercase tracking-tight">Latest Transactions</h3>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if($recentTransactions->isNotEmpty())
                    <a href="{{ route('finance.index') }}" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-[10px] px-3.5 py-1.5 rounded-full font-bold border border-emerald-100 uppercase tracking-widest transition-all shadow-sm">
                        <span>Show All</span>
                    </a>
                    @endif

                    <button @click="showFinanceModal = true" class="w-10 h-10 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl flex items-center justify-center shadow-md shadow-emerald-600/10 hover:scale-105 active:scale-95 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>
                </div>
            </div>





            <!-- Latest Transactions List (full width, aligned with header) -->
            <div class="space-y-3 w-full">
                <div class="space-y-3 max-h-[480px] overflow-y-auto pr-1 custom-scrollbar">
                    @forelse($recentTransactions as $item)
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
                                        <p class="text-sm font-bold text-slate-800 capitalize leading-tight truncate max-w-[140px] sm:max-w-none">{{ $item->description ?: ($item->transaction_type === 'income' ? 'Income' : 'Expense') }}</p>
                                        <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-lg border {{ $item->method === 'cash' ? 'border-amber-200 text-amber-700 bg-amber-50' : 'border-indigo-100 text-indigo-600 bg-indigo-50' }} shrink-0">
                                            {{ $item->method }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                        <span class="text-[10px] text-slate-400 font-bold tracking-wider uppercase leading-none">
                                            {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}@if($item->created_at) {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}@endif
                                        </span>
                                        @if($item->category)
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider leading-none">
                                            &bull; #{{ $item->category }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-right flex items-center gap-3 shrink-0">
                                <p class="text-sm font-bold {{ $item->transaction_type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $item->transaction_type === 'income' ? '+' : '-' }}&euro;{{ number_format($item->amount, 2) }}
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
                        <div class="glass p-12 rounded-2xl border border-gray-100 text-center">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <p class="text-slate-400 font-medium">No transactions registered for today</p>
                        </div>
                    @endforelse
                </div>


            </div>
        </div>
    </div>
    @endif


    <!-- Modals moved to root layout wrapper level to overlay top header completely -->
            <!-- Add Reservation Modal -->
            <div x-show="showReservationModal" 
                 class="fixed inset-0 z-[100] flex items-center justify-center px-4" 
                 style="display: none;"
                 x-cloak>
                <div @click="showReservationModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="glass w-full max-w-2xl rounded-[32px] p-8 relative border border-white/10"
                     x-show="showReservationModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-90">
                    
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center border border-indigo-100 text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold">New Reservation</h3>
                        </div>
                        <button @click="showReservationModal = false" class="text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    @if (($errors->any() || session('error')) && old('customer_name') && !old('reservation_id'))
                        <div class="mb-4 bg-red-500/20 border border-red-500/50 p-3 rounded-xl text-xs text-red-200">
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

                    <form action="{{ route('reservations.store') }}" method="POST" class="space-y-3 sm:space-y-4 text-left"
                        x-data="{ 
                            date: '{{ old('reservation_time') ? substr(old('reservation_time'), 0, 10) : date('Y-m-d') }}',
                            selectedTime: '{{ old('reservation_time') ? substr(old('reservation_time'), 11, 5) : '' }}',
                            partySize: {{ old('party_size', 2) }},
                            slots: [],
                            taken_per_time: {},
                            closed: false,
                            has_session_3: false,
                            max_capacity: 25,
                            dayOfWeek: null,
                            loading: false,
                            async fetchSlots() {
                                if (this.date) {
                                    this.loading = true;
                                    try {
                                        console.log('Fetching slots for (Add):', this.date);
                                        const response = await fetch('{{ route('restaurant.schedule.data') }}?date=' + this.date);
                                        const data = await response.json();
                                        console.log('Data received (Add):', data);
                                        this.closed = data.closed;
                                        this.slots = data.slots || [];
                                        this.taken_per_time = data.taken_per_time || {};
                                        this.max_capacity = data.max_party_size || 25;
                                        this.dayOfWeek = data.day_of_week;
                                        this.has_session_3 = data.has_session_3;

                                        if (this.partySize > this.max_capacity) {
                                            this.partySize = this.max_capacity;
                                        }

                                        if (this.selectedTime) {
                                            if (this.getAvailableSpots(this.selectedTime) < this.partySize) {
                                                this.selectedTime = '';
                                            }
                                        }
                                    } catch (e) {
                                        console.error('Error fetching slots (Add):', e);
                                    }
                                    this.loading = false;
                                }
                            },
                            getAvailableSpots(timeStr) {
                                if (!timeStr) return this.max_capacity;
                                let taken = this.taken_per_time[timeStr] || 0;
                                return Math.max(0, this.max_capacity - taken);
                            },
                            get availableTimes() {
                                let times = [];
                                this.slots.forEach(slot => {
                                    // Dynamic Seating mode: If Session 3 exists, evening slots (>= 17:00) only show start time
                                    if (this.has_session_3 && parseInt(slot.start.split(':')[0]) >= 17) {
                                        let timeStr = slot.start.substring(0, 5);
                                        let available = this.getAvailableSpots(timeStr);
                                        if (available >= this.partySize) {
                                            const isPartiallyBooked = available < this.max_capacity;
                                            const availabilityText = isPartiallyBooked ? ` (${available} spots left)` : '';
                                            times.push({ 
                                                value: timeStr, 
                                                label: `${slot.start.substring(0, 5)} - ${slot.end.substring(0, 5)}${availabilityText}` 
                                            });
                                        }
                                    } else {
                                        let current = new Date('2000-01-01T' + (slot.start.length === 5 ? slot.start + ':00' : slot.start));
                                        let stop = new Date('2000-01-01T' + (slot.end.length === 5 ? slot.end + ':00' : slot.end));
                                        
                                        while (current < stop) {
                                            let h = current.getHours().toString().padStart(2, '0');
                                            let m = current.getMinutes().toString().padStart(2, '0');
                                            let timeStr = `${h}:${m}`;
                                            
                                            let available = this.getAvailableSpots(timeStr);
                                            if (available >= this.partySize) {
                                                const isPartiallyBooked = available < this.max_capacity;
                                                const availabilityText = isPartiallyBooked ? ` (${available} spots left)` : '';
                                                times.push({ 
                                                    value: timeStr, 
                                                    label: `${timeStr}${availabilityText}` 
                                                });
                                            }
                                            current.setMinutes(current.getMinutes() + 15);
                                        }
                                    }
                                });
                                return times;
                            }
                        }" x-init="fetchSlots()">
                        @csrf
                        <input type="hidden" name="reservation_time" x-bind:value="date + ' ' + selectedTime">
                        
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Customer Name</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" required>
                        </div>

                        <div class="grid grid-cols-3 gap-2 sm:gap-3">
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Date</label>
                                <input type="date" x-model="date" @change="fetchSlots()" required class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                            </div>

                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Time</label>
                                <select name="reservation_time_raw" x-model="selectedTime" required
                                    class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya disabled:opacity-50"
                                    :disabled="loading || closed">
                                    <option value="" disabled selected class="bg-gray-900" x-text="loading ? 'Searching...' : (closed ? 'Closed' : 'Choose...')"></option>
                                    <template x-for="timeOption in availableTimes">
                                        <option :value="timeOption.value" x-text="timeOption.label" class="bg-gray-900"></option>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">People</label>
                                <select name="party_size" x-model.number="partySize" required :disabled="loading" class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                                    <template x-for="i in getAvailableSpots(selectedTime)">
                                        <option :value="i" x-text="i + (i === 1 ? ' Person' : ' People')" :selected="i == partySize" class="bg-gray-900"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <template x-if="slots.length > 0 && availableTimes.length === 0 && !loading && !closed">
                            <div class="w-full py-3 text-center glass rounded-2xl border border-red-500/20 bg-red-500/5">
                                <p class="text-[10px] text-red-500/80 font-bold uppercase tracking-widest">No availability for <span x-text="partySize"></span> people</p>
                            </div>
                        </template>

                        <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-center font-bold text-[10px] uppercase" x-show="closed">
                            Restaurant is Closed on this Day
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label>Phone (Optional)</label>
                                <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}">
                            </div>
                            <div>
                                <label>Email (Optional)</label>
                                <input type="email" name="customer_email" value="{{ old('customer_email') }}">
                            </div>
                        </div>

                        <div>
                            <label>Notes (Optional)</label>
                            <textarea name="notes" rows="2" class="w-full glass p-3 rounded-xl border border-white/10 text-white transition focus:border-koya outline-none text-sm" placeholder="Allergies, special requests...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full btn-koya py-4 shadow-xl shadow-koya/20" x-show="!closed" :disabled="!selectedTime">Create Reservation</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Edit Reservation Modal -->
            <div x-show="showEditModal" 
                 class="fixed inset-0 z-[100] flex items-center justify-center px-4" 
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
                        <h3 class="text-xl font-bold">Edit Reservation</h3>
                        <button @click="showEditModal = false" class="text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    @if (($errors->any() || session('error')) && old('reservation_id'))
                        <div class="mb-4 bg-red-500/20 border border-red-500/50 p-3 rounded-xl text-xs text-red-200">
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

                    <form :action="@js(route('reservations.update', ['reservation' => '__RESERVATION__'])).replace('__RESERVATION__', editingReservation.id)" method="POST" class="space-y-3 sm:space-y-4 text-left"
                        x-data="{ 
                            date: '',
                            selectedTime: '',
                            partySize: 2,
                            slots: [],
                            taken_per_time: {},
                            closed: false,
                            has_session_3: false,
                            max_capacity: 25,
                            dayOfWeek: null,
                            loading: false,
                            async fetchSlots() {
                                if (this.date) {
                                    this.loading = true;
                                    try {
                                        console.log('Fetching slots for (Edit):', this.date);
                                        const response = await fetch('{{ route('restaurant.schedule.data') }}?date=' + this.date + '&exclude_id=' + this.editingReservation.id);
                                        const data = await response.json();
                                        console.log('Data received (Edit):', data);
                                        this.closed = data.closed;
                                        this.slots = data.slots || [];
                                        this.taken_per_time = data.taken_per_time || {};
                                        this.max_capacity = data.max_party_size || 25;
                                        this.dayOfWeek = data.day_of_week;
                                        this.has_session_3 = data.has_session_3;

                                        if (this.selectedTime) {
                                            if (this.getAvailableSpots(this.selectedTime) < this.partySize) {
                                                // Note: Ideally we'd account for the current reservation's size here, 
                                                // but for simplicity and UI consistency we'll just check raw availability.
                                            }
                                        }
                                    } catch (e) {
                                        console.error('Error fetching slots (Edit):', e);
                                    }
                                    this.loading = false;
                                }
                            },
                            getAvailableSpots(timeStr) {
                                if (!timeStr) return this.max_capacity;
                                let taken = this.taken_per_time[timeStr] || 0;
                                return Math.max(0, this.max_capacity - taken);
                            },
                            get availableTimes() {
                                let times = [];
                                this.slots.forEach(slot => {
                                    // Dynamic Seating mode: If Session 3 exists, evening slots (>= 17:00) only show start time
                                    if (this.has_session_3 && parseInt(slot.start.split(':')[0]) >= 17) {
                                        let timeStr = slot.start.substring(0, 5);
                                        let available = this.getAvailableSpots(timeStr);
                                        const isPartiallyBooked = available < this.max_capacity;
                                        const availabilityText = isPartiallyBooked ? ` (${available} spots left)` : '';
                                        times.push({ 
                                            value: timeStr, 
                                            label: `${slot.start.substring(0, 5)} - ${slot.end.substring(0, 5)}${availabilityText}` 
                                        });
                                    } else {
                                        let current = new Date('2000-01-01T' + (slot.start.length === 5 ? slot.start + ':00' : slot.start));
                                        let stop = new Date('2000-01-01T' + (slot.end.length === 5 ? slot.end + ':00' : slot.end));
                                        while (current < stop) {
                                            let h = current.getHours().toString().padStart(2, '0');
                                            let m = current.getMinutes().toString().padStart(2, '0');
                                            let timeStr = `${h}:${m}`;
                                            let available = this.getAvailableSpots(timeStr);
                                            const isPartiallyBooked = available < this.max_capacity;
                                            const availabilityText = isPartiallyBooked ? ` (${available} spots left)` : '';
                                            times.push({ 
                                                value: timeStr, 
                                                label: `${timeStr}${availabilityText}` 
                                            });
                                            current.setMinutes(current.getMinutes() + 15);
                                        }
                                    }
                                });
                                // Ensure current reservation time is in list if editing
                                if (this.selectedTime && !times.find(t => t.value === this.selectedTime)) {
                                    times.push({ value: this.selectedTime, label: this.selectedTime });
                                    times.sort((a,b) => a.value.localeCompare(b.value));
                                }
                                return times;
                            }
                        }" 
                        x-init="
                            $watch('editingReservation.reservation_time', value => {
                                if (value) {
                                    date = value.substring(0, 10);
                                    selectedTime = value.substring(11, 16);
                                    partySize = editingReservation.party_size;
                                    fetchSlots();
                                }
                            })
                        ">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="reservation_id" :value="editingReservation.id">
                        <input type="hidden" name="reservation_time" x-bind:value="date + ' ' + selectedTime">
                        
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Customer Name</label>
                            <input type="text" name="customer_name" x-model="editingReservation.customer_name" required>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Status</label>
                                <select name="status" x-model="editingReservation.status" required class="w-full glass p-3 rounded-xl border border-white/10 text-white transition focus:border-koya outline-none">
                                    <option value="confirmed" class="bg-gray-900">Confirmed</option>
                                    <option value="completed" class="bg-gray-900">Completed</option>
                                    <option value="cancelled" class="bg-gray-900">Cancelled</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Date</label>
                                <input type="date" x-model="date" @change="fetchSlots()" required class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Time</label>
                                <select name="reservation_time_raw" x-model="selectedTime" required
                                    class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya disabled:opacity-50"
                                    :disabled="loading || closed">
                                    <option value="" disabled selected class="bg-gray-900" x-text="loading ? 'Searching...' : (closed ? 'Closed' : 'Choose...')"></option>
                                    <template x-for="timeOption in availableTimes">
                                        <option :value="timeOption.value" x-text="timeOption.label" class="bg-gray-900"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">People</label>
                                <select name="party_size" x-model.number="partySize" required :disabled="loading" class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                                    <template x-for="i in 25">
                                        <option :value="i" x-text="i + (i === 1 ? ' person' : ' people')" :selected="i == partySize" class="bg-gray-900"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <template x-if="slots.length > 0 && availableTimes.length === 0 && !loading && !closed">
                            <div class="w-full py-3 text-center glass rounded-2xl border border-red-500/20 bg-red-500/5">
                                <p class="text-[10px] text-red-500/80 font-bold uppercase tracking-widest">No availability for <span x-text="partySize"></span> people</p>
                            </div>
                        </template>

                        <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-center font-bold text-[10px] uppercase" x-show="closed">
                            Restaurant is Closed on this Day
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label>Phone (Optional)</label>
                                <input type="tel" name="customer_phone" x-model="editingReservation.customer_phone">
                            </div>
                            <div>
                                <label>Email (Optional)</label>
                                <input type="email" name="customer_email" x-model="editingReservation.customer_email">
                            </div>
                        </div>

                        <div>
                            <label>Notes (Optional)</label>
                            <textarea name="notes" x-model="editingReservation.notes" rows="2" class="w-full glass p-3 rounded-xl border border-white/10 text-white transition focus:border-koya outline-none text-sm"></textarea>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="button" 
                                    @click="if(confirm('Delete this reservation?')) $refs.deleteResForm.submit()"
                                    class="flex-1 bg-red-500/10 text-red-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500/20 transition-all border border-red-500/20 active:scale-95">
                                Delete
                            </button>
                            <button type="submit" class="flex-[3] btn-koya py-4 shadow-lg shadow-koya/20" x-show="!closed" :disabled="!selectedTime">Save Changes</button>
                        </div>
                    </form>
                    
                    <form :action="@js(route('reservations.destroy', ['reservation' => '__RESERVATION__'])).replace('__RESERVATION__', editingReservation.id)" method="POST" x-ref="deleteResForm" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>

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
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center border border-emerald-100 text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800">Add Finance</h3>
                        </div>
                        <button @click="showFinanceModal = false" class="text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    @if (session('success') && (session('keep_finance_modal') || request()->boolean('finance_modal')))
                        <div class="mb-4 bg-emerald-50 border border-emerald-200 p-3 rounded-xl text-xs text-emerald-800 font-bold uppercase tracking-wider">
                            {{ session('success') }}
                        </div>
                    @endif

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
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 block">Amount (&euro;)</label>
                            <div class="relative flex items-center w-full transition-all duration-300">
                                <span class="absolute left-5 text-2xl font-black transition-colors"
                                      :class="finance_type === 'income' ? 'text-emerald-500' : 'text-rose-500'">&euro;</span>
                                <input type="number" step="0.01" name="amount" placeholder="0.00" value="{{ (session('keep_finance_modal') || request()->boolean('finance_modal')) ? '' : old('amount') }}" required autofocus
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 text-3xl font-black outline-none transition-all duration-300"
                                    :class="finance_type === 'income' ? 'bg-emerald-50 border-emerald-200 text-emerald-700 focus:border-emerald-500 focus:ring-emerald-500 shadow-lg shadow-emerald-500/15' : 'bg-rose-50 border-rose-200 text-rose-700 focus:border-rose-500 focus:ring-rose-500 shadow-lg shadow-rose-500/15'"
                                    style="min-height: 4.5rem !important; padding-left: 3.5rem !important;">
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Date</label>
                            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
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
                    
                    <div x-data="{ showDesc: {{ old('description') ? 'true' : 'false' }} }" class="space-y-3">
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

                        <div class="pt-1">
                            <button x-show="!showDesc" type="button" @click="showDesc = true" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors flex items-center gap-1">
                                <span>+ Add Description</span>
                            </button>
                            <div x-show="showDesc" x-transition>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Description</label>
                                <input type="text" name="description" value="{{ old('description') }}"
                                       class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full btn-koya py-4 shadow-xl shadow-koya/20 text-white font-bold uppercase tracking-widest">Save Entry</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Finance Modal --}}
        <div x-show="showEditFinanceModal" 
             class="fixed inset-0 z-[100] flex items-center justify-center px-4" 
             style="display: none;"
             x-cloak>
            <div @click="showEditFinanceModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="glass w-full max-w-2xl rounded-[32px] p-8 relative border border-white/10"
                 x-show="showEditFinanceModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-slate-800">Edit Finance Entry</h3>
                    <button @click="showEditFinanceModal = false" class="text-gray-400">
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
                                <option value="class" class="bg-gray-900">None</option>
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
                                       class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" 
                                @click="if(confirm('Delete this entry?')) $refs.deleteFinanceForm.action = @js(route('finance.destroy', ['id' => '__FINANCE__'])).replace('__FINANCE__', editingFinance.id) + '?type=' + editingFinance.originalType; $refs.deleteFinanceForm.submit()"
                                class="flex-1 bg-red-50 text-red-600 hover:bg-red-100 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all border border-red-200 active:scale-95">
                            Delete
                        </button>
                        <button type="submit" class="flex-[3] btn-koya py-4 shadow-lg shadow-koya/20 text-white font-bold uppercase tracking-widest">Save Changes</button>
                    </div>
                </form>

                <form method="POST" x-ref="deleteFinanceForm" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
        </div>
    </div>

    </div>
</div>
</x-app-layout>

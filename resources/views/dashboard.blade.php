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

        /* KPI gradient cards with modern aesthetics */
        .kpi-card {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid rgba(255, 255, 255, 0.05);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.01) 100%);
            box-shadow: 0 10px 30px -15px rgba(0, 0, 0, 0.3);
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .kpi-card:hover {
            transform: translateY(-4px) scale(1.015);
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 20px 40px -20px rgba(0, 0, 0, 0.5);
        }

        .kpi-income::before {
            background: linear-gradient(90deg, #10b981, #34d399);
        }
        .kpi-income:hover {
            box-shadow: 0 20px 40px -20px rgba(16, 185, 129, 0.15), 0 0 0 1px rgba(16, 185, 129, 0.2);
        }

        .kpi-expense::before {
            background: linear-gradient(90deg, #ef4444, #f87171);
        }
        .kpi-expense:hover {
            box-shadow: 0 20px 40px -20px rgba(239, 68, 68, 0.15), 0 0 0 1px rgba(239, 68, 68, 0.2);
        }

        .kpi-profit::before {
            background: linear-gradient(90deg, #fbbf24, #f59e0b);
        }
        .kpi-profit:hover {
            box-shadow: 0 20px 40px -20px rgba(251, 191, 36, 0.15), 0 0 0 1px rgba(251, 191, 36, 0.2);
        }
        
        .kpi-profit.negative::before {
            background: linear-gradient(90deg, #ef4444, #dc2626);
        }
        .kpi-profit.negative:hover {
            box-shadow: 0 20px 40px -20px rgba(239, 68, 68, 0.15), 0 0 0 1px rgba(239, 68, 68, 0.2);
        }

        .badge-dot {
            display: inline-block;
            width: 8px; height: 8px;
            border-radius: 50%;
            margin-right: 6px;
        }

        /* Modernized scrollbars for lists */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.01);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 10px;
        }

        /* Hover lift cards */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.04) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* Neon glows for status and active items */
        .shadow-glow-koya {
            box-shadow: 0 0 15px rgba(200, 155, 60, 0.2);
        }
        .shadow-glow-emerald {
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);
        }

        /* Status pills */
        .status-pill {
            padding: 0.25rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            line-height: 1;
        }
        .status-confirmed {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        .status-completed {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        .status-cancelled {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .btn-koya:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
            box-shadow: none;
        }
    </style>

    <!-- Daily Overview Stats -->
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
            description: '{{ old('description') }}'
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
                description: item.description || ''
            };
            this.showEditFinanceModal = true;
        }
    }" class="space-y-8">




        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-2xl text-sm font-bold uppercase tracking-wider animate-pulse">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-sm font-bold uppercase tracking-wider">
                {{ session('error') }}
            </div>
        @endif

        <div class="@if(auth()->user()->isAdmin()) grid grid-cols-1 lg:grid-cols-3 gap-8 @else space-y-8 max-w-4xl mx-auto @endif">
            <!-- Left/Main Column: Operations -->
            <div class="@if(auth()->user()->isAdmin()) lg:col-span-2 @endif space-y-8 animate-fade-in-up">
                <!-- Reservations Section -->
                <div class="space-y-6">
            @if($isClosedToday && $date == now()->toDateString())
                <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-center font-bold text-sm uppercase tracking-widest animate-pulse">
                    Reservations are STOPPED for today
                </div>
            @endif
            <div class="flex items-center justify-between border-b border-white/5 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-koya rounded-full shadow-[0_0_15px_rgba(255,92,0,0.4)]"></div>
                    <div>
                        <h3 class="text-xl font-black text-white uppercase tracking-tight">Reservations</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-0.5">Today's Schedule</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-koya/10 text-koya text-[10px] px-3 py-1.5 rounded-full font-black border border-koya/10 uppercase tracking-widest">{{ $reservationsCount }} TOTAL</span>
                    
                    @if(auth()->user()->isAdmin())
                    <form action="{{ route('restaurant.toggle-today') }}" method="POST" class="flex">
                        @csrf
                        <button type="submit" 
                                title="{{ $isClosedToday ? 'Reservations are stopped. Click to open.' : 'Click to stop reservations for today' }}"
                                class="w-10 h-10 {{ $isClosedToday ? 'bg-red-500 shadow-red-500/20' : 'bg-emerald-500 shadow-emerald-500/20' }} text-white rounded-2xl flex items-center justify-center shadow-lg hover:scale-110 active:scale-95 transition-all">
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

                    <button @click="showReservationModal = true" class="w-10 h-10 bg-koya text-white rounded-2xl flex items-center justify-center shadow-lg shadow-koya/20 hover:scale-110 active:scale-95 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>
                </div>
            </div>

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
                        <h3 class="text-xl font-bold">New Reservation</h3>
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

                    <form action="{{ route('reservations.store') }}" method="POST" class="space-y-4 text-left"
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
                            <label>Customer Name</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
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

                    <form :action="@js(route('reservations.update', ['reservation' => '__RESERVATION__'])).replace('__RESERVATION__', editingReservation.id)" method="POST" class="space-y-4 text-left"
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
                            <label>Customer Name</label>
                            <input type="text" name="customer_name" x-model="editingReservation.customer_name" required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
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
                        </div>

                        <div class="grid grid-cols-1 gap-3">
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

            @if($reservations->isEmpty())
                <div class="glass p-12 rounded-2xl border border-white/5 text-center">
                    <svg class="w-12 h-12 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-400">No reservations for today</p>
                    <button @click="showReservationModal = true" class="mt-4 inline-block text-koya font-bold">Reservation Today +</button>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($reservations as $res)
                    <div class="glass p-5 rounded-[24px] border border-white/5 flex justify-between items-center text-left hover-lift transition-all duration-300 relative overflow-hidden group">
                        <!-- Vertical status strip indicator -->
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 @if(($res->status ?? 'confirmed') === 'completed') bg-blue-500 @elseif(($res->status ?? 'confirmed') === 'cancelled') bg-red-500 @else bg-koya @endif shadow-[0_0_10px_rgba(200,155,60,0.3)]"></div>
                        
                        <div class="flex-1 pl-3">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="text-koya font-extrabold text-xl leading-none">{{ $res->reservation_time->format('H:i') }}</span>
                                <span class="text-white font-bold text-lg leading-none capitalize">{{ $res->customer_name }}</span>
                                <span class="status-pill status-{{ $res->status ?? 'confirmed' }} ml-auto sm:ml-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $res->status ?? 'confirmed' }}
                                </span>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-2 mt-3">
                                <!-- People -->
                                <div class="flex items-center gap-1.5 text-white/85 bg-koya/5 px-2.5 py-1 rounded-xl border border-koya/10 text-xs font-bold shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-koya/70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $res->party_size }} {{ $res->party_size === 1 ? 'Person' : 'People' }}</span>
                                </div>

                                <!-- Phone -->
                                @if($res->customer_phone)
                                <a href="tel:{{ $res->customer_phone }}" class="flex items-center gap-1.5 text-white/65 hover:text-white hover:bg-koya/10 hover:border-koya/25 transition-all bg-koya/5 px-2.5 py-1 rounded-xl border border-koya/10 text-xs font-medium shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-koya/70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span>{{ $res->customer_phone }}</span>
                                </a>
                                @endif

                                <!-- Email -->
                                @php $email = $res->customer_email ?? null; @endphp
                                @if($email)
                                <a href="mailto:{{ $email }}" class="flex items-center gap-1.5 text-white/65 hover:text-white hover:bg-koya/10 hover:border-koya/25 transition-all bg-koya/5 px-2.5 py-1 rounded-xl border border-koya/10 text-xs font-medium shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-koya/70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate max-w-[120px] sm:max-w-none">{{ $email }}</span>
                                </a>
                                @endif

                                <!-- Notes -->
                                @if($res->notes)
                                <div class="flex items-center gap-1.5 text-amber-400/90 bg-amber-500/5 px-2.5 py-1 rounded-xl border border-amber-500/10 text-xs font-semibold shadow-sm">
                                    <svg class="w-3 h-3 text-amber-400/80 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    <span>{{ $res->notes }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <button @click="editRes({{ $res->toJson() }})" class="p-3 bg-white/5 border border-white/10 text-gray-400 hover:text-white hover:bg-white/10 rounded-2xl transition-all shadow-sm active:scale-95 ml-3 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div> <!-- Close operations left column -->

    @if(auth()->user()->isAdmin())
    <!-- Right Column: Finances & Quick Actions -->
    <div class="space-y-8 animate-fade-in-up delay-75">
        <!-- Finance Stats Section -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-white/5 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-green-500 rounded-full shadow-[0_0_15px_rgba(34,197,94,0.4)]"></div>
                    <div>
                        <h3 class="text-xl font-black text-white uppercase tracking-tight">Finance</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-0.5">Daily Overview stats</p>
                    </div>
                </div>
                <button @click="showFinanceModal = true" class="w-10 h-10 bg-green-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-green-500/20 hover:scale-110 active:scale-95 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </button>
            </div>

            <p class="text-[10px] text-white/30 text-center font-bold uppercase tracking-widest -mt-4 mb-6">
                {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
            </p>

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

                    @if (($errors->any() || session('error')) && old('amount') && !old('finance_id'))
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

        {{-- ─── Edit Finance Modal ─────────────────────── --}}
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
                    <h3 class="text-xl font-bold text-white">Edit Finance Entry</h3>
                    <button @click="showEditFinanceModal = false" class="text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                @if (($errors->any() || session('error')) && old('finance_id'))
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

                <form :action="@js(route('finance.update', ['id' => '__FINANCE__'])).replace('__FINANCE__', editingFinance.id)" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="finance_id" :value="editingFinance.id">
                    <input type="hidden" name="type" :value="editingFinance.originalType">

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Date</label>
                        <input type="date" name="date" x-model="editingFinance.date" required
                               class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Amount (€)</label>
                        <input type="number" step="0.01" name="amount" x-model="editingFinance.amount" required
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

                    <div class="grid grid-cols-2 gap-3">
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
                                @click="if(confirm('Delete this entry?')) $refs.deleteFinanceForm.action = @js(route('finance.destroy', ['id' => '__FINANCE__'])).replace('__FINANCE__', editingFinance.id) + '?type=' + editingFinance.originalType; $refs.deleteFinanceForm.submit()"
                                class="flex-1 bg-red-500/10 text-red-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500/20 transition-all border border-red-500/20 active:scale-95">
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


            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-1 gap-4">
                <div class="glass kpi-card kpi-income p-5 rounded-3xl border border-white/5">
                    <p class="text-[10px] text-green-400/70 uppercase tracking-widest font-black mb-1">Income</p>
                    <h3 class="text-2xl font-black text-green-400 leading-tight">€{{ number_format($dailyIncome, 2) }}</h3>
                    @if($dailyIncome > 0 && count($dailyIncomeByMethod) > 0)
                        <div class="flex flex-wrap gap-2 mt-3">
                            @foreach($dailyIncomeByMethod as $method => $total)
                                <span class="text-[8px] font-black uppercase px-2 py-1 rounded-lg bg-green-500/10 text-green-400 border border-green-500/10">{{ $method }}: €{{ number_format($total, 0) }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                
                <div class="glass kpi-card kpi-expense p-5 rounded-3xl border border-white/5">
                    <p class="text-[10px] text-red-400/70 uppercase tracking-widest font-black mb-1">Expenses</p>
                    <h3 class="text-2xl font-black text-red-400 leading-tight">€{{ number_format($dailyExpenses, 2) }}</h3>
                    @if($dailyExpenses > 0 && count($dailyExpensesByMethod) > 0)
                        <div class="flex flex-wrap gap-2 mt-3">
                            @foreach($dailyExpensesByMethod as $method => $total)
                                <span class="text-[8px] font-black uppercase px-2 py-1 rounded-lg bg-red-500/10 text-red-400 border border-red-500/10">{{ $method }}: €{{ number_format($total, 0) }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                @php $dailyProfit = $dailyIncome - $dailyExpenses; @endphp
                <div class="glass kpi-card {{ $dailyProfit >= 0 ? 'kpi-profit' : 'kpi-profit negative' }} p-5 rounded-3xl border border-white/5">
                    <div class="flex justify-between items-start mb-1">
                        <p class="text-[10px] {{ $dailyProfit >= 0 ? 'text-amber-400/70' : 'text-red-400/70' }} uppercase tracking-widest font-black">Profit</p>
                        @if($dailyIncome > 0)
                            <span class="text-[9px] font-black {{ $dailyProfit >= 0 ? 'text-amber-400/60 bg-amber-500/10' : 'text-red-400/60 bg-red-500/10' }} px-1.5 py-0.5 rounded border border-current/10">
                                {{ number_format(($dailyProfit / $dailyIncome) * 100, 1) }}%
                            </span>
                        @endif
                    </div>
                    <h3 class="text-2xl font-black {{ $dailyProfit >= 0 ? 'text-amber-400' : 'text-red-400' }} leading-tight">
                        {{ $dailyProfit >= 0 ? '' : '-' }}€{{ number_format(abs($dailyProfit), 2) }}
                    </h3>
                </div>
            </div>

            <!-- Daily Summary Chart -->
            <div class="glass p-5 rounded-3xl border border-white/5 mt-8 hover-lift transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-500/15 flex items-center justify-center border border-blue-500/20">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-blue-400/60 uppercase tracking-widest font-black leading-none mb-0.5">Overview</p>
                            <h4 class="text-sm font-black text-blue-400 leading-tight">Daily Summary</h4>
                        </div>
                    </div>
                    @if($dailyIncome > 0 || $dailyExpenses > 0)
                        <div class="flex items-center gap-2 text-[8px] font-bold text-white/30 uppercase tracking-tighter">
                            <span><span class="badge-dot" style="background:#4ade80"></span>Inc</span>
                            <span><span class="badge-dot" style="background:#f87171"></span>Exp</span>
                            <span><span class="badge-dot" style="background:#fbbf24"></span>Net</span>
                        </div>
                    @endif
                </div>
                @if($dailyIncome > 0 || $dailyExpenses > 0)
                    <div style="height:120px;">
                        <canvas id="dailySummaryChart"></canvas>
                    </div>
                @else
                    <div class="h-[120px] flex flex-col items-center justify-center text-center opacity-40">
                        <svg class="w-8 h-8 text-white/40 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-[10px] text-white/60 uppercase tracking-widest font-extrabold">No financial data for today</p>
                    </div>
                @endif
            </div>

            <!-- Recent Transactions -->
            <div class="space-y-3 mt-8">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-1.5 h-4 bg-blue-500 rounded-full shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div>
                    <h4 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Recent Transactions</h4>
                </div>

                <div class="space-y-3 max-h-[360px] overflow-y-auto pr-1 custom-scrollbar">
                    @forelse($recentTransactions as $item)
                        <div class="glass p-4 rounded-3xl border border-white/5 flex items-center justify-between hover-lift transition-all duration-300 relative overflow-hidden group">
                            <!-- Left dynamic stripe indicator -->
                            <div class="absolute left-0 top-0 bottom-0 w-1 {{ $item->transaction_type === 'income' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                            
                            <div class="flex items-center gap-3 pl-2">
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center border {{ $item->transaction_type === 'income' ? 'bg-green-500/10 border-green-500/20 text-green-500' : 'bg-red-500/10 border-red-500/20 text-red-500' }} shrink-0">
                                    @if($item->transaction_type === 'income')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                                    @endif
                                </div>
                                <div class="text-left">
                                    <div class="flex items-center gap-2">
                                        <p class="text-xs font-black text-white capitalize leading-tight">{{ $item->description ?: ($item->transaction_type === 'income' ? 'Income' : 'Expense') }}</p>
                                        <span class="text-[7px] font-black uppercase px-1.5 py-0.5 rounded border {{ $item->method === 'cash' ? 'border-amber-500/30 text-amber-500 bg-amber-500/5' : 'border-blue-500/30 text-blue-500 bg-blue-500/5' }}">
                                            {{ $item->method }}
                                        </span>
                                    </div>
                                    <p class="text-[9px] text-white/30 font-bold uppercase tracking-tighter mt-1">
                                        {{ $item->category ? '#' . $item->category : '' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="text-right flex items-center gap-2 shrink-0">
                                <p class="text-sm font-black {{ $item->transaction_type === 'income' ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $item->transaction_type === 'income' ? '+' : '-' }}€{{ number_format($item->amount, 2) }}
                                </p>
                                
                                <button @click="editFinance({{ $item->toJson() }})" class="flex items-center gap-1.5 px-2.5 py-1.5 bg-white/5 border border-white/10 rounded-xl hover:bg-white/15 transition-all shadow-sm active:scale-95">
                                    <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">Edit</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-[10px] text-white/20 font-bold uppercase tracking-[0.2em] text-center py-6">No transactions today</p>
                    @endforelse
                </div>

                @if($recentTransactions->isNotEmpty())
                    <div class="pt-2">
                        <a href="{{ route('finance.history') }}" class="w-full flex items-center justify-center gap-2 py-3 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition-all group">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] group-hover:text-white transition-colors">Show All Transactions</span>
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <script>
            (function() {
                const el = document.getElementById('dailySummaryChart');
                if (el) {
                    const ctx = el.getContext('2d');
                    const dailyIncome = {{ $dailyIncome }};
                    const dailyExpenses = {{ $dailyExpenses }};
                    const dailyProfit = {{ $dailyIncome - $dailyExpenses }};

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Income', 'Expenses', 'Profit'],
                            datasets: [{
                                data: [dailyIncome, dailyExpenses, Math.abs(dailyProfit)],
                                backgroundColor: [
                                    'rgba(74,222,128,0.7)',
                                    'rgba(248,113,113,0.7)',
                                    dailyProfit >= 0 ? 'rgba(251,191,36,0.7)' : 'rgba(239,68,68,0.7)',
                                ],
                                borderRadius: 8,
                                barThickness: 34,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.2)', font: { size: 9, family: 'Outfit' } } },
                                y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { display: false } }
                            }
                        }
                    });
                }
            })();
        </script>
        @endif
    </div>
</div>
</x-app-layout>


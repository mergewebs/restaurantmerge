<x-app-layout>
    <x-slot name="header">
        New Reservation
    </x-slot>

    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold">New Reservation</h2>
            <a href="{{ route('reservations.index') }}" class="text-gray-400 p-2 bg-white/5 rounded-xl">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
        </div>
        
        @if(session('error') || $errors->any())
            <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-sm font-bold uppercase tracking-wider space-y-2">
                @if(session('error'))
                    <p>{{ session('error') }}</p>
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

        <form action="{{ route('reservations.store') }}" method="POST" class="space-y-4" 
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
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route('restaurant.schedule.data') }}?date=' + this.date);
                        const data = await response.json();
                        this.closed = data.closed;
                        this.slots = data.slots || [];
                        this.taken_per_time = data.taken_per_time || {};
                        this.max_capacity = data.max_party_size || 25;
                        this.dayOfWeek = data.day_of_week;
                        this.has_session_3 = data.has_session_3;
                        
                        // Limit partySize to max_capacity for the new date if needed
                        if (this.partySize > this.max_capacity) {
                            this.partySize = this.max_capacity;
                        }

                        // Reset selected time if current selection exceeds slot availability
                        if (this.selectedTime) {
                            let available = this.getAvailableSpots(this.selectedTime);
                            if (available < this.partySize) {
                                this.selectedTime = '';
                            }
                        }
                    } catch (e) {
                        console.error('Error fetching slots');
                    }
                this.loading = false;
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
                            let available = this.getAvailableSpots(slot.start);
                            if (available >= this.partySize) {
                                const isPartiallyBooked = available < this.max_capacity;
                                const availabilityText = isPartiallyBooked ? ` (${available} spots left)` : '';
                                times.push({ 
                                    value: slot.start, 
                                    label: `${slot.start} - ${slot.end}${availabilityText}` 
                                });
                            }
                        } else {
                            let current = new Date('2000-01-01T' + slot.start);
                            let stop = new Date('2000-01-01T' + slot.end);
                            while (current < stop) {
                                let timeStr = current.toTimeString().substring(0, 5);
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
                },
                async initFlatpickr() {
                    try {
                        const resp = await fetch('{{ route('restaurant.closed-dates') }}');
                        const closedData = await resp.json();
                        
                        flatpickr(this.$refs.dateInput, {
                            minDate: "today",
                            defaultDate: this.date,
                            dateFormat: "Y-m-d",
                            disable: [
                                (date) => {
                                    const day = date.getDay();
                                    if (closedData.weekly_closed.includes(day)) return true;
                                    
                                    const dateStr = date.getFullYear() + '-' + 
                                                  String(date.getMonth() + 1).padStart(2, '0') + '-' + 
                                                  String(date.getDate()).padStart(2, '0');
                                    if (closedData.exceptions.includes(dateStr)) return true;
                                    
                                    return false;
                                }
                            ],
                            onChange: (selectedDates, dateStr) => {
                                this.date = dateStr;
                                this.fetchSlots();
                            },
                            locale: "{{ app()->getLocale() }}" === "es" ? "es" : "en"
                        });
                    } catch (e) {
                        console.error('Error initializing flatpickr:', e);
                    }
                }
            }" x-init="fetchSlots(); initFlatpickr();">
            @csrf
            
            <input type="hidden" name="reservation_time" x-bind:value="date + ' ' + selectedTime">

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-400">Customer Name</label>
                <input type="text" name="customer_name" required class="w-full glass p-4 rounded-xl border border-white/10 text-white">
            </div>

            <div class="grid grid-cols-2 gap-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-400">Date</label>
                    <div class="relative">
                        <input type="text" x-ref="dateInput" x-model="date" readonly required class="w-full glass p-4 rounded-xl border border-white/10 text-white focus:border-accent outline-none cursor-pointer">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-white/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-400">Time</label>
                    <select name="reservation_time_raw" x-model="selectedTime" required
                        class="w-full glass p-4 rounded-xl border border-white/10 text-white outline-none focus:border-accent disabled:opacity-50"
                        :disabled="loading || closed">
                        <option value="" disabled selected class="bg-gray-900" x-text="loading ? 'Searching...' : (closed ? 'Restaurant Closed' : 'Choose time...')"></option>
                        <template x-for="timeOption in availableTimes">
                            <option :value="timeOption.value" x-text="timeOption.label" class="bg-gray-900"></option>
                        </template>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-400">Party Size</label>
                    <select name="party_size" x-model.number="partySize" required :disabled="loading" class="w-full glass p-4 rounded-xl border border-white/10 text-white outline-none focus:border-accent">
                        <template x-for="i in getAvailableSpots(selectedTime)">
                            <option :value="i" x-text="i + (i === 1 ? ' Person' : ' People')" :selected="i == partySize" class="bg-gray-900"></option>
                        </template>
                    </select>
                </div>
            </div>

            <template x-if="slots.length > 0 && availableTimes.length === 0 && !loading && !closed">
                <div class="w-full py-4 text-center glass rounded-2xl border border-red-500/20 bg-red-500/5">
                    <p class="text-xs text-red-500/80 font-bold uppercase tracking-widest">No availability for <span x-text="partySize"></span> people on this date</p>
                </div>
            </template>

            <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-center font-bold" x-show="closed">
                RESTAURANT IS CLOSED ON THIS DATE
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-400">Phone (Optional)</label>
                <input type="tel" name="customer_phone" class="w-full glass p-4 rounded-xl border border-white/10 text-white">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-400">Special Notes / Allergy</label>
                <textarea name="notes" rows="3" class="w-full glass p-4 rounded-xl border border-white/10 text-white"></textarea>
            </div>

            <button type="submit" class="w-full btn-accent py-4 shadow-xl shadow-accent/20 text-lg font-bold" x-show="!closed">
                Confirm Reservation
            </button>
        </form>
    </div>
</x-app-layout>

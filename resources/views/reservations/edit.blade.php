<x-app-layout>
    <x-slot name="header">
        Edit Reservation
    </x-slot>

    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold">Reservation for {{ $reservation->customer_name }}</h2>
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

        <form action="{{ route('reservations.update', $reservation) }}" method="POST" class="space-y-4"
            x-data="{ 
                date: '{{ old('reservation_time') ? substr(old('reservation_time'), 0, 10) : $reservation->reservation_time->format('Y-m-d') }}',
                selectedTime: '{{ old('reservation_time') ? substr(old('reservation_time'), 11, 5) : $reservation->reservation_time->format('H:i') }}',
                partySize: {{ old('party_size', $reservation->party_size) }},
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
                        const response = await fetch('{{ route('restaurant.schedule.data') }}?date=' + this.date + '&exclude_id={{ $reservation->id }}');
                        const data = await response.json();
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
                                // Keep it for now since we're editing
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
                getTimeOptions(slot) {
                    // Dynamic Seating mode: If Session 3 exists, evening slots (>= 17:00) only show start time
                    if (this.has_session_3 && parseInt(slot.start.split(':')[0]) >= 17) {
                        return [slot.start.substring(0, 5)];
                    }
                    let times = [];
                    let current = new Date('2000-01-01T' + slot.start);
                    let stop = new Date('2000-01-01T' + slot.end);
                    while (current < stop) {
                        times.push(current.toTimeString().substring(0, 5));
                        current.setMinutes(current.getMinutes() + 15);
                    }
                    }
                    // Ensure current/old time is in list if editing
                    if (this.selectedTime && !times.find(t => t === this.selectedTime)) {
                        times.push(this.selectedTime);
                        times.sort();
                    }
                    return times;
                },
                isSlotAvailable(slot) {
                    return this.getTimeOptions(slot).some(time => this.getAvailableSpots(time) >= this.partySize);
                }
            }" x-init="fetchSlots()">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="reservation_time" x-bind:value="date + ' ' + selectedTime">

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-400">Customer Name</label>
                <input type="text" name="customer_name" value="{{ $reservation->customer_name }}" required class="w-full glass p-4 rounded-xl border border-white/10 text-white cursor-pointer transition focus:border-accent outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-400">Date</label>
                    <input type="date" x-model="date" @change="fetchSlots()" required class="w-full glass p-4 rounded-xl border border-white/10 text-white">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-400">Party Size</label>
                    <select name="party_size" x-model.number="partySize" required class="w-full glass p-4 rounded-xl border border-white/10 text-white outline-none focus:border-accent">
                        <template x-for="i in 25">
                            <option :value="i" x-text="i + (i === 1 ? ' Person' : ' People')" :selected="i == partySize" class="bg-gray-900"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div class="space-y-4" x-show="!closed">
                <div class="flex justify-between items-center">
                    <label class="text-sm font-semibold text-gray-400">Available Times</label>
                    <span class="text-xs text-white/60 font-black uppercase tracking-wider" x-show="selectedTime">Remaining: <span class="text-accent" x-text="getAvailableSpots(selectedTime)"></span></span>
                </div>
                
                <div class="max-h-64 overflow-y-auto pr-2 flex flex-wrap gap-4 custom-scrollbar">
                    <template x-for="slot in slots">
                        <div x-show="isSlotAvailable(slot)" :class="has_session_3 && parseInt(slot.start.split(':')[0]) >= 17 ? 'flex-1 min-w-[140px]' : 'w-full mb-6'">
                            <h4 class="text-[10px] font-black uppercase tracking-widest text-accent mb-3" 
                                x-show="!has_session_3 || parseInt(slot.start.split(':')[0]) < 17" 
                                x-text="slot.start.substring(0, 5) + ' - ' + slot.end.substring(0, 5)"></h4>
                            <div :class="has_session_3 && parseInt(slot.start.split(':')[0]) >= 17 ? 'flex' : 'grid grid-cols-4 sm:grid-cols-6 gap-2'">
                                <template x-for="time in getTimeOptions(slot)">
                                    <button type="button" 
                                    x-show="getAvailableSpots(time) >= partySize"
                                    @click="selectedTime = (selectedTime === time ? '' : time)"
                                        :class="{
                                            'bg-accent text-white border-accent': selectedTime === time,
                                            'bg-white/5 text-white/60 border-white/10 hover:border-accent/40': selectedTime !== time,
                                            'w-full': has_session_3 && parseInt(slot.start.split(':')[0]) >= 17
                                        }"
                                        class="py-2 px-1 text-xs font-bold rounded-xl border transition-all text-center">
                                        <span x-text="has_session_3 && parseInt(slot.start.split(':')[0]) >= 17 ? slot.start.substring(0, 5) + ' - ' + slot.end.substring(0, 5) : time"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <style>
                .custom-scrollbar::-webkit-scrollbar { width: 4px; }
                .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
                .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 92, 0, 0.2); border-radius: 10px; }
                .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 92, 0, 0.4); }
            </style>

            <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-center font-bold" x-show="closed">
                RESTAURANT IS CLOSED ON THIS DATE
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-400">Phone </label>
                <input type="tel" name="customer_phone" value="{{ $reservation->customer_phone }}" class="w-full glass p-4 rounded-xl border border-white/10 text-white transition focus:border-accent outline-none">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-400">Special Notes</label>
                <textarea name="notes" rows="3" class="w-full glass p-4 rounded-xl border border-white/10 text-white transition focus:border-accent outline-none">{{ $reservation->notes }}</textarea>
            </div>

             <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-400">Status</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['confirmed', 'completed', 'cancelled'] as $status)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="status" value="{{ $status }}" class="peer hidden" {{ $reservation->status == $status ? 'checked' : '' }}>
                            <div class="glass p-3 rounded-xl border border-white/10 text-center text-xs font-bold uppercase tracking-wider peer-checked:border-accent peer-checked:bg-accent/10 peer-checked:text-accent transition">
                                {{ $status }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 btn-accent py-4 shadow-xl shadow-accent/20 text-lg font-bold">
                    Save Changes
                </button>
            </div>
        </form>

        <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('Full deletion will remove this record forever. Continue?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full p-4 text-red-400 font-semibold text-sm">
                Delete Reservation
            </button>
        </form>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        Reservations List
    </x-slot>

    <div x-data="{ 
        showReservationModal: {{ ($errors->any() || session('error')) && old('customer_name') && !old('reservation_id') ? 'true' : 'false' }},
        showEditModal: {{ ($errors->any() || session('error')) && old('reservation_id') ? 'true' : 'false' }},
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
        }
    }" class="space-y-10">

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-2xl text-sm font-bold uppercase tracking-wider">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-sm font-bold uppercase tracking-wider">
                {{ session('error') }}
            </div>
        @endif

        <!-- Header Actions -->
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-white">All Reservations</h2>
            <button @click="showReservationModal = true" class="px-4 py-2 bg-koya text-white flex items-center gap-2 rounded-xl shadow-lg shadow-koya/20 text-sm font-semibold transition-all hover:scale-105 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                New Reservation
            </button>
        </div>

        <!-- Today's Reservations -->
        <div class="space-y-4">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-koya rounded-full"></div>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider text-xs">Today</h3>
            </div>
            @if($todayReservations->isEmpty())
                <div class="glass p-8 rounded-2xl border border-white/5 text-center">
                    <p class="text-gray-400 text-sm">No reservations for today</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($todayReservations as $res)
                        <x-reservation-card :reservation="$res" />
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $todayReservations->links() }}
                </div>
            @endif
        </div>

        <!-- Upcoming Reservations -->
        <div class="space-y-4">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider text-xs">Upcoming</h3>
            </div>
            @if($upcomingReservations->isEmpty())
                <div class="glass p-8 rounded-2xl border border-white/5 text-center">
                    <p class="text-gray-400 text-sm">No upcoming reservations</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($upcomingReservations as $res)
                        <x-reservation-card :reservation="$res" />
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $upcomingReservations->links() }}
                </div>
            @endif
        </div>

        <!-- Past Reservations -->
        <div class="space-y-4">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider text-xs text-gray-400">Past</h3>
            </div>
            @if($pastReservations->isEmpty())
                <div class="glass p-8 rounded-2xl border border-white/5 text-center">
                    <p class="text-gray-400 text-sm">No past reservations</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($pastReservations as $res)
                        <div class="opacity-60 grayscale-[0.5]">
                            <x-reservation-card :reservation="$res" />
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $pastReservations->links() }}
                </div>
            @endif
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
                    <h3 class="text-xl font-bold text-white">New Reservation</h3>
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
                        partySize: {{ old('party_size', 1) }},
                        taken_per_time: {},
                        closed: false,
                        loading: false,
                        max_capacity: 25,
                        dayOfWeek: null,
                        async fetchSlots() {
                            if (this.date) {
                                this.loading = true;
                                try {
                                    const response = await fetch('{{ route('restaurant.schedule.data') }}?date=' + this.date);
                                    const data = await response.json();
                                    this.closed = data.closed;
                                    this.slots = data.slots || [];
                                    this.taken_per_time = data.taken_per_time || {};
                                    this.max_capacity = data.max_party_size || 25;
                                    this.dayOfWeek = data.day_of_week;
                                } catch (e) {
                                    console.error('Error fetching slots');
                                } finally {
                                    this.loading = false;
                                }
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
                            });
                            return times;
                        }
                    }" x-init="fetchSlots()">
                    @csrf
                    <input type="hidden" name="reservation_time" x-bind:value="date + ' ' + selectedTime">
                    
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Customer Name</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required 
                               class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
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

                        <div :class="selectedTime ? 'opacity-100' : 'opacity-40 pointer-events-none'" class="transition-opacity">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">People</label>
                            <select name="party_size" x-model.number="partySize" required :disabled="loading || !selectedTime" class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
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
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Phone (Optional)</label>
                            <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}"
                                   class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Email (Optional)</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                                   class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Notes (Optional)</label>
                        <textarea name="notes" rows="2" class="w-full glass p-3 rounded-xl border border-white/10 text-white transition focus:border-koya outline-none text-sm" placeholder="Allergies, special requests...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full btn-koya py-4 shadow-xl shadow-koya/20 disabled:opacity-50 disabled:cursor-not-allowed" x-show="!closed" :disabled="!selectedTime || loading">Create Reservation</button>
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
                    <h3 class="text-xl font-bold text-white">Edit Reservation</h3>
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
                        partySize: 1,
                        taken_per_time: {},
                        closed: false,
                        loading: false,
                        max_capacity: 25,
                        dayOfWeek: null,
                        async fetchSlots() {
                            if (this.date) {
                                this.loading = true;
                                try {
                                    const response = await fetch('{{ route('restaurant.schedule.data') }}?date=' + this.date + '&exclude_id=' + this.editingReservation.id);
                                    const data = await response.json();
                                    this.closed = data.closed;
                                    this.slots = data.slots || [];
                                    this.taken_per_time = data.taken_per_time || {};
                                    this.max_capacity = data.max_party_size || 25;
                                    this.dayOfWeek = data.day_of_week;
                                } catch (e) {
                                    console.error('Error fetching slots');
                                } finally {
                                    this.loading = false;
                                }
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
                        <input type="text" name="customer_name" x-model="editingReservation.customer_name" required
                               class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
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
                        <div :class="selectedTime ? 'opacity-100' : 'opacity-40 pointer-events-none'" class="transition-opacity">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">People</label>
                            <select name="party_size" x-model.number="partySize" required :disabled="loading || !selectedTime" class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                                <template x-for="i in getAvailableSpots(selectedTime)">
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
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Phone (Optional)</label>
                            <input type="tel" name="customer_phone" x-model="editingReservation.customer_phone"
                                   class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Email (Optional)</label>
                            <input type="email" name="customer_email" x-model="editingReservation.customer_email"
                                   class="w-full glass p-3 rounded-xl border border-white/10 text-white outline-none focus:border-koya">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Notes (Optional)</label>
                        <textarea name="notes" x-model="editingReservation.notes" rows="2" class="w-full glass p-3 rounded-xl border border-white/10 text-white transition focus:border-koya outline-none text-sm"></textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" 
                                @click="if(confirm('Delete this reservation?')) $refs.deleteResForm.submit()"
                                class="flex-1 bg-red-500/10 text-red-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500/20 transition-all border border-red-500/20 active:scale-95">
                            Delete
                        </button>
                        <button type="submit" class="flex-[3] btn-koya py-4 shadow-lg shadow-koya/20 disabled:opacity-50 disabled:cursor-not-allowed" x-show="!closed" :disabled="!selectedTime || loading">Save Changes</button>
                    </div>
                </form>
                
                <form :action="@js(route('reservations.destroy', ['reservation' => '__RESERVATION__'])).replace('__RESERVATION__', editingReservation.id)" method="POST" x-ref="deleteResForm" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>

    </div>
</x-app-layout>


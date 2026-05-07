<x-iframe-layout>




    <form action="{{ route('reservations.public.store') }}" method="POST" x-data="{ 
        date: '{{ old('reservation_time') ? substr(old('reservation_time'), 0, 10) : date('Y-m-d') }}',
        selectedTime: '{{ old('reservation_time') ? substr(old('reservation_time'), 11, 5) : '' }}',
        partySize: {{ old('party_size', 1) }},
        slots: [],
        taken_per_time: {},
        closed: false,
        has_session_3: false,
        max_capacity: 25,
        dayOfWeek: null,
        loading: false,
        isSubmitting: false,
        submissionToken: '',
        showEmail: false,
        showNotes: false,
        createSubmissionToken() {
            if (window.crypto && typeof window.crypto.randomUUID === 'function') {
                return window.crypto.randomUUID();
            }

            return 'token-' + Date.now() + '-' + Math.floor(Math.random() * 1000000);
        },
        initSubmissionToken() {
            this.submissionToken = this.createSubmissionToken();
        },
        handleSubmit(event) {
            if (this.isSubmitting || this.loading || this.closed || !this.selectedTime) {
                event.preventDefault();
                return;
            }

            this.isSubmitting = true;
            event.target.submit();
        },
        async fetchSlots() {
            if (!this.date) return;
            this.loading = true;
            try {
                console.log('Fetching slots for:', this.date);
                const response = await fetch('{{ route('restaurant.schedule.data') }}?date=' + this.date);
                const data = await response.json();
                console.log('Response data:', data);
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
                console.error('Error fetching slots:', e);
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
                const startHour = parseInt(slot.start.split(':')[0]);
                if (this.has_session_3 && startHour >= 17) {
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
        },
        async initFlatpickr() {
            let closedData = { weekly_closed: [], exceptions: [] };
            try {
                const resp = await fetch('{{ route('restaurant.closed-dates') }}');
                if (resp.ok) {
                    closedData = await resp.json();
                }
            } catch (e) {
                console.error('Error fetching closed dates:', e);
            }
            
            flatpickr(this.$refs.dateInput, {
                minDate: 'today',
                defaultDate: this.date,
                dateFormat: 'Y-m-d',
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
                locale: '{{ app()->getLocale() }}' === 'es' ? 'es' : 'en'
            });
        }
    }" @submit.prevent="handleSubmit" x-init="initSubmissionToken(); fetchSlots(); initFlatpickr();" class="space-y-6 pb-8 px-1">
        @csrf


        <input type="hidden" name="reservation_time" x-bind:value="date + ' ' + selectedTime">
        <input type="hidden" name="submission_token" x-bind:value="submissionToken">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label>{{ __('public.full_name') }}</label>
                <input type="text" name="customer_name" required value="{{ old('customer_name') }}">
            </div>
            <div>
                <label>{{ __('public.phone') }}</label>
                <input type="tel" name="customer_phone" required value="{{ old('customer_phone') }}">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label>{{ __('public.date') }}</label>
                <div class="relative">
                    <input type="text" x-ref="dateInput" x-model="date" readonly required class="cursor-pointer !pr-12">
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
            </div>

            <div>
                <label>{{ __('public.arrival_time') }}</label>
                <select x-model="selectedTime" required :disabled="loading || closed">
                    <option value="" disabled selected x-text="loading ? '{{ __('public.loading') }}' : (closed ? '{{ __('public.closed') }}' : '{{ __('public.select_time') }}')"></option>
                    <template x-for="timeOption in availableTimes">
                        <option :value="timeOption.value" x-text="timeOption.label"></option>
                    </template>
                </select>
            </div>
        </div>

        <div>
            <label>{{ __('public.party_size') }}</label>
            <select name="party_size" x-model.number="partySize" required :disabled="loading">
                <template x-for="i in getAvailableSpots(selectedTime)">
                    <option :value="i" x-text="i + (i === 1 ? ' {{ __('public.person') }}' : ' {{ __('public.people') }}')" :selected="i == partySize"></option>
                </template>
            </select>
        </div>

        <template x-if="slots.length > 0 && availableTimes.length === 0 && !loading && !closed">
            <div class="w-full py-12 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">{{ __('public.no_availability', ['size' => '']) }} <span x-text="partySize"></span> {{ __('public.people') }}</p>
            </div>
        </template>

        <div x-show="closed" x-cloak class="p-4 bg-yellow-50 border border-yellow-100 text-yellow-800 rounded-lg text-sm text-center font-bold">
            {{ __('public.closed') }}
        </div>

        <div class="pt-4 border-t border-gray-100 space-y-4">
            <div x-show="!showEmail" class="text-center">
                <button type="button" @click="showEmail = true" class="text-[10px] font-bold text-[#ffc547] hover:brightness-110 transition-all uppercase tracking-[0.2em]">
                    + {{ __('public.email_prompt') }}
                </button>
            </div>
            
            <div x-show="showEmail" x-cloak x-transition>
                <label>{{ __('public.email') }} ({{ __('public.optional') }})</label>
                <input type="email" name="customer_email" value="{{ old('customer_email') }}">
            </div>

            <div x-show="!showNotes" class="text-center">
                <button type="button" @click="showNotes = true" class="text-[10px] font-bold text-[#ffc547] hover:brightness-110 transition-all uppercase tracking-[0.2em]">
                    + {{ __('public.allergy_prompt') }}
                </button>
            </div>

            <div x-show="showNotes" x-cloak x-transition>
                <label>{{ __('public.notes') }}</label>
                <textarea name="notes" rows="2">{{ old('notes') }}</textarea>
            </div>
        </div>



        @if(session('success'))
            <div class="mb-6 p-6 bg-[#fac021] rounded-2xl border border-gray-100 flex flex-col items-center text-center gap-2 animate-bounce-short shadow-sm">
                <div class="text-[#008468]">
                    <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="font-bold text-xs uppercase tracking-[0.2em] text-[#008468] leading-relaxed">
                    {!! session('success') !!}
                </span>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="mb-6 p-6 bg-white rounded-2xl border border-red-100 flex flex-col items-center text-center gap-2 shadow-sm">
                <div class="text-red-500">
                    <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="font-bold text-[10px] uppercase tracking-[0.15em] text-red-500 leading-relaxed">
                    @if(session('error'))
                        <p>{{ session('error') }}</p>
                    @endif
                    @if($errors->any())
                        <ul class="list-none mt-1 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endif

        <button x-ref="submitButton" type="submit" class="btn-reservation" :disabled="closed || !selectedTime || isSubmitting" :class="closed || !selectedTime || isSubmitting ? 'opacity-50 cursor-not-allowed' : ''">
            <span x-show="!isSubmitting">{{ __('public.submit') }}</span>
            <span x-show="isSubmitting" x-cloak>{{ __('public.submitting') }}</span>
        </button>


    </form>
</x-iframe-layout>

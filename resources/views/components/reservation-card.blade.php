@props(['reservation'])

<div class="glass p-4 rounded-[24px] border border-white/5 flex justify-between items-center text-left transition-all hover:border-white/10 {{ $reservation->status == 'cancelled' ? 'opacity-50' : '' }}">
    <div class="flex-1">
        <div class="flex items-center gap-2">
            <div class="flex flex-col">
                <span class="text-koya font-bold text-lg leading-tight">{{ $reservation->reservation_time->format('H:i') }}</span>
                @if(!$reservation->reservation_time->isToday())
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">{{ $reservation->reservation_time->format('d/m') }}</span>
                @endif
            </div>
            <span class="text-white font-bold text-lg leading-tight capitalize">{{ $reservation->customer_name }}</span>
            @if($reservation->status == 'completed')
                <div class="w-5 h-5 bg-green-500/20 text-green-400 rounded-full flex items-center justify-center">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
            @endif
        </div>
        
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 mt-2">
            <!-- People -->
            <div class="flex items-center gap-1 text-gray-400">
                <svg class="w-3.5 h-3.5 text-koya/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-xs font-bold">{{ $reservation->party_size }}</span>
            </div>

            <!-- Phone -->
            @if($reservation->customer_phone)
            <div class="flex items-center gap-1 text-gray-400">
                <svg class="w-3.5 h-3.5 text-koya/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                <span class="text-[10px] font-medium tracking-tight">{{ $reservation->customer_phone }}</span>
            </div>
            @endif

            <!-- Email -->
            @if($reservation->customer_email)
            <div class="flex items-center gap-1 text-gray-400">
                <svg class="w-3.5 h-3.5 text-koya/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span class="text-[10px] font-medium truncate max-w-[120px]">{{ $reservation->customer_email }}</span>
            </div>
            @endif

            <!-- Notes -->
            @if($reservation->notes)
            <div class="flex items-center gap-1 text-emerald-400/80 bg-emerald-500/5 px-2 py-0.5 rounded-lg border border-emerald-500/10">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                <span class="text-[10px] font-bold uppercase tracking-tight">{!! $reservation->notes !!}</span>
            </div>
            @endif
        </div>
    </div>
    <div class="flex gap-2">
        <button @click="editRes({{ $reservation->toJson() }})" class="p-2.5 bg-white/5 rounded-2xl hover:bg-white/10 transition-colors">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
        </button>
    </div>
</div>

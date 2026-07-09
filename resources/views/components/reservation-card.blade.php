@props(['reservation'])

<div class="glass p-5 rounded-2xl border border-slate-100 flex justify-between items-start text-left transition-all duration-200 hover:shadow-md hover:border-slate-200 bg-white {{ $reservation->status == 'cancelled' ? 'opacity-60' : '' }}">
    <div class="flex-1 min-w-0 pr-4">
        <div class="flex flex-wrap items-center gap-2">
            <!-- Time & Date -->
            <div class="flex items-center gap-1 bg-indigo-50/60 text-indigo-700 px-2.5 py-1 rounded-xl border border-indigo-100/50">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-bold text-sm leading-none">{{ $reservation->reservation_time->format('H:i') }}</span>
                @if(!$reservation->reservation_time->isToday())
                    <span class="text-[10px] font-bold opacity-85 leading-none ml-0.5 border-l border-indigo-200 pl-1">{{ $reservation->reservation_time->format('d/m') }}</span>
                @endif
            </div>
            
            <!-- Customer Name -->
            <span class="text-slate-800 font-bold text-base leading-tight capitalize truncate max-w-[150px]" title="{{ $reservation->customer_name }}">{{ $reservation->customer_name }}</span>
            
            <!-- Status Badge -->
            @if($reservation->status == 'confirmed')
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100">
                    Confirmed
                </span>
            @elseif($reservation->status == 'completed')
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100">
                    Completed
                </span>
            @elseif($reservation->status == 'cancelled')
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-red-50 text-red-600 border border-red-100">
                    Cancelled
                </span>
            @endif
        </div>
        
        <!-- Details row -->
        <div class="flex flex-wrap items-center gap-x-2 gap-y-1.5 mt-3">
            <!-- Party size badge -->
            <div class="flex items-center gap-1 text-slate-500 bg-slate-50 border border-slate-100 px-2.5 py-1 rounded-xl">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-xs font-bold">{{ $reservation->party_size }}</span>
            </div>

            <!-- Phone -->
            @if($reservation->customer_phone)
            <a href="tel:{{ $reservation->customer_phone }}" class="px-2.5 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-100 text-slate-500 hover:text-indigo-600 rounded-xl transition duration-150 flex items-center gap-1.5 hover:border-slate-200 text-xs font-medium" title="Call {{ $reservation->customer_name }}: {{ $reservation->customer_phone }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                <span class="leading-none">{{ $reservation->customer_phone }}</span>
            </a>
            @endif

            <!-- Email -->
            @if($reservation->customer_email)
            <a href="mailto:{{ $reservation->customer_email }}" class="px-2.5 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-100 text-slate-500 hover:text-indigo-600 rounded-xl transition duration-150 flex items-center gap-1.5 hover:border-slate-200 text-xs font-medium" title="Email: {{ $reservation->customer_email }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span class="leading-none">{{ $reservation->customer_email }}</span>
            </a>
            @endif
        </div>

        <!-- Notes -->
        @if($reservation->notes)
        <div class="flex items-start gap-1.5 text-amber-700 bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-100/60 mt-3 text-xs font-medium">
            <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
            </svg>
            <span class="leading-relaxed">{!! $reservation->notes !!}</span>
        </div>
        @endif
    </div>
    
    <!-- Actions -->
    <div class="flex shrink-0 self-center">
        <button @click="editRes({{ $reservation->toJson() }})" class="p-2.5 bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-700 rounded-xl transition duration-150 border border-slate-100 shadow-sm" title="Edit Booking">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
        </button>
    </div>
</div>


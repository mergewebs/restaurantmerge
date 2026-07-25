<x-app-layout>
    <div data-async-region="tables-completed" data-async-mutations="true">
        @if(session('success'))
            <div class="hidden" data-async-feedback data-feedback-level="success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="hidden" data-async-feedback data-feedback-level="error">{{ session('error') }}</div>
        @elseif($errors->any())
            <div class="hidden" data-async-feedback data-feedback-level="error">{{ $errors->first() }}</div>
        @endif
    <style>
        summary.completed-summary {
            list-style: none;
        }

        summary.completed-summary::-webkit-details-marker {
            display: none;
        }
    </style>
    <div class="space-y-6">

        <div class="glass rounded-3xl p-5 border border-white/10">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h1 class="text-xl font-black uppercase tracking-wide">Completed Orders</h1>
                    <p class="text-xs text-white/50 mt-1">Closed orders are saved here for review.</p>
                </div>
                <a href="{{ route('tables.index') }}" data-async="off" class="px-3 py-2 rounded-xl border border-white/15 bg-white/5 text-white/80 font-black uppercase text-[10px] tracking-widest">
                    Back To Tables
                </a>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($orders as $order)
                <details class="glass rounded-3xl p-4 border border-white/10">
                    <summary class="completed-summary cursor-pointer flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-accent shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-black">Table #{{ $order->table?->number ?? '-' }}</p>
                                <p class="text-[11px] text-white/50">{{ $order->created_at?->format('d/m/Y H:i') }} - Closed {{ $order->updated_at?->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-black tracking-widest px-3 py-1 rounded-full bg-accent/15 text-accent border border-accent/20">
                            TOTAL: â‚¬{{ number_format((float) $order->total, 2) }}
                        </span>
                    </summary>

                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @forelse($order->items as $item)
                            <div class="rounded-2xl bg-white/5 border border-white/10 p-3">
                                <p class="text-xs font-black text-white truncate">{{ $item->name }}</p>
                                <p class="text-[11px] text-white/60 mt-0.5">{{ $item->quantity }} x â‚¬{{ number_format((float) $item->unit_price, 2) }}</p>
                                <p class="text-[11px] font-black text-accent mt-1">â‚¬{{ number_format((float) $item->line_total, 2) }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-white/50">No items found in this order.</p>
                        @endforelse
                    </div>
                </details>
            @empty
                <div class="glass rounded-3xl p-6 border border-white/10 text-center">
                    <p class="text-sm text-white/60">No completed orders yet.</p>
                </div>
            @endforelse
        </div>

        @if(method_exists($orders, 'links'))
            <div>
                {{ $orders->links() }}
            </div>
        @endif
    </div>
    </div>
</x-app-layout>

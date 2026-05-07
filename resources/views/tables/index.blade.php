<x-app-layout>
    <style>
        summary.order-summary {
            list-style: none;
        }

        summary.order-summary::-webkit-details-marker {
            display: none;
        }

    </style>

    <div class="space-y-6">
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-2xl text-sm font-bold">
                {{ session('error') }}
            </div>
        @endif
        @if(session('info'))
            <div class="p-4 bg-blue-500/10 border border-blue-500/20 text-blue-300 rounded-2xl text-sm font-bold">
                {{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-300 rounded-2xl text-xs">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="glass rounded-3xl p-5 border border-white/10">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h1 class="text-xl font-black uppercase tracking-wide">Tables Overview</h1>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('tables.completed') }}" class="px-3 py-2 rounded-xl border border-white/15 bg-white/5 text-white/80 font-black uppercase text-[10px] tracking-widest">
                        Completed Orders
                    </a>
                </div>
            </div>

            <form action="{{ route('tables.store') }}" method="POST" class="mt-4 flex gap-3">
                @csrf
                <input type="number" name="number" min="1" max="999" value="{{ old('number') }}" required placeholder="Table number">
                <button type="submit" class="btn-koya whitespace-nowrap px-5">Add Table</button>
            </form>
            @error('number')
                <p class="text-xs text-red-400 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-3">
            @forelse($tables as $table)
                @php
                    $order = $table->openOrder;
                    $items = $order?->items ?? collect();
                @endphp
                <div class="glass rounded-3xl p-3 border {{ session('focus_table_id') == $table->id ? 'border-koya shadow-lg shadow-koya/20' : 'border-white/10' }}">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-black">Table #{{ $table->number }}</h2>
                        <span class="text-[11px] font-black tracking-widest px-3 py-1 rounded-full {{ $order ? 'bg-koya/15 text-koya border border-koya/20' : 'bg-white/10 text-white/50 border border-white/10' }}">
                            TOTAL: €{{ number_format((float) ($order?->total ?? 0), 2) }}
                        </span>
                    </div>

                    <h3 class="mt-3 text-[11px] font-black uppercase tracking-widest text-white/60">Ordered Items</h3>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        @forelse($items as $item)
                            <div class="rounded-xl bg-white/5 border border-white/10 px-3 py-2">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-[12px] font-black text-white truncate">{{ $item->name }}</p>
                                    <p class="text-[12px] font-black text-koya whitespace-nowrap">€{{ number_format((float) $item->line_total, 2) }}</p>
                                </div>
                                <p class="text-[10px] text-white/60 mt-0.5">{{ $item->quantity }} x €{{ number_format((float) $item->unit_price, 2) }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-white/50">No items ordered yet.</p>
                        @endforelse
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <a href="{{ route('tables.order', $table) }}"
                           title="{{ $order ? 'View Order' : 'Add Order' }}"
                           class="btn-koya w-full h-10 px-2 text-[10px] tracking-widest text-center">
                            {{ $order ? 'VIEW ORDER' : 'ADD ORDER' }}
                        </a>
                        <form action="{{ route('tables.destroy', $table) }}" method="POST" onsubmit="return confirm('Delete table #{{ $table->number }} and all its orders? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    title="Delete Table"
                                    class="w-full h-10 rounded-xl border border-red-500/30 bg-red-500/10 text-red-300 text-[10px] font-black uppercase tracking-widest flex items-center justify-center">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="glass rounded-3xl p-6 border border-white/10 md:col-span-2 text-center">
                    <p class="text-sm text-white/60">No active tables right now. Empty tables are hidden until they have an order.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

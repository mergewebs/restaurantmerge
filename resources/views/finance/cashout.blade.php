<x-app-layout>
    <x-slot name="header">
        Cashout Close
    </x-slot>

    <div class="space-y-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('finance.index') }}" class="p-4 bg-white/5 rounded-2xl text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="text-xl font-bold">Close Register for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</h2>
        </div>

        <div class="glass p-6 rounded-3xl border border-white/10 space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-4">
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Projected Balances</p>
                    <div class="space-y-4">
                        <div class="flex justify-between border-b border-white/5 pb-2">
                            <span class="text-sm text-gray-500">Opening Cash</span>
                            <span class="text-sm font-bold">€ {{ number_format($openingCash, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-white/5 pb-2">
                            <span class="text-sm text-gray-500">Daily Sales</span>
                            <span class="text-sm font-bold text-green-400">€ + {{ number_format($totalIncome, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-white/5 pb-2">
                            <span class="text-sm text-gray-500">Daily Ops (Expenses)</span>
                            <span class="text-sm font-bold text-red-500">€ - {{ number_format($totalExpense, 2) }}</span>
                        </div>
                        <div class="flex justify-between pt-2">
                            <span class="text-md font-bold text-white">Expected Closing</span>
                            <span class="text-md font-black text-accent shadow-accent shadow-lg">€ {{ number_format($expectedCash, 2) }}</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('finance.cashout.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="opening_cash" value="{{ $openingCash }}">
                    <input type="hidden" name="sales_cash" value="{{ $totalIncome }}">
                    <input type="hidden" name="expenses_cash" value="{{ $totalExpense }}">
                    <input type="hidden" name="expected_cash" value="{{ $expectedCash }}">

                    <div class="space-y-2">
                        <label class="text-[10px] text-gray-400 uppercase font-black tracking-widest">ACTUAL CASH IN REGISTER</label>
                        <input type="number" step="0.01" name="closing_cash" required class="w-full glass p-5 rounded-2xl border-2 border-accent text-3xl font-black text-white outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Differences / Discrepancies</label>
                        <textarea name="notes" rows="3" class="w-full glass p-4 rounded-xl border border-white/10 text-white text-sm outline-none"></textarea>
                    </div>

                    <button type="submit" class="w-full btn-accent py-6 shadow-2xl shadow-accent/40 text-xl font-black tracking-tighter uppercase">
                        Finalize Cashout & Lock
                    </button>
                </form>
            </div>
        </div>

        <div class="p-6 bg-accent/10 rounded-3xl border border-accent/20 text-center">
            <p class="text-accent text-xs font-bold leading-relaxed px-4 italic">Confirming this cashout will sync the official books for this business day. Ensure all physical cash is verified by the owner.</p>
        </div>
    </div>
</x-app-layout>


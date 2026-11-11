<x-app-layout>
    <div class="space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div><a href="{{ route('demo-access.index') }}" class="text-xs font-black uppercase tracking-wider text-[#b95738]">← All demo access</a><h2 class="mt-1 text-2xl font-black text-[#392c24]">{{ $ipAddress->ip_address }}</h2></div>
            <form method="POST" action="{{ route('demo-access.block', $ipAddress) }}">@csrf @method('PATCH')<button class="rounded-xl px-5 py-2.5 text-xs font-black uppercase tracking-wider {{ $ipAddress->is_blocked ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}" onclick="return confirm('{{ $ipAddress->is_blocked ? 'Unblock' : 'Block' }} this IP address?')">{{ $ipAddress->is_blocked ? 'Unblock IP' : 'Block IP' }}</button></form>
        </div>


        <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach([
                'First access' => $ipAddress->first_access_at->format('d M Y H:i:s'), 'Last access' => $ipAddress->last_access_at->format('d M Y H:i:s'),
                'Total visits' => number_format($ipAddress->visit_count), 'Current status' => $ipAddress->isActive() ? 'Active' : 'Inactive',
                'Browser' => $ipAddress->browser ?: 'Unknown', 'Device' => ($ipAddress->device_type ?: 'Unknown').' · '.($ipAddress->operating_system ?: 'Unknown'),
                'Location' => collect([$ipAddress->city, $ipAddress->country])->filter()->join(', ') ?: 'Unavailable', 'Last page' => $ipAddress->last_page ?: '—',
            ] as $label => $value)
                <article class="rounded-2xl border border-[#e5d8cc] bg-white p-4 shadow-sm"><p class="text-[10px] font-black uppercase tracking-wider text-[#8c7a6d]">{{ $label }}</p><strong class="mt-1 block break-words text-sm text-[#392c24]">{{ $value }}</strong></article>
            @endforeach
        </section>

        <section class="overflow-hidden rounded-2xl border border-[#e5d8cc] bg-white shadow-sm">
            <header class="border-b border-[#eee4da] px-5 py-4"><h3 class="font-black text-[#392c24]">Sessions</h3><p class="text-xs text-[#8c7a6d]">Every new authenticated demo session from this IP.</p></header>
            <div class="overflow-x-auto"><table class="w-full min-w-[720px] text-left"><thead class="bg-[#faf5ef] text-[10px] font-black uppercase text-[#8c7a6d]"><tr><th>Session</th><th>Started</th><th>Last activity</th><th>Ended</th><th>Entry page</th><th>Pages</th></tr></thead><tbody class="divide-y divide-[#eee4da] text-sm text-[#655246]">
                @forelse($sessions as $session)<tr><td class="font-black text-[#392c24]">#{{ $session->id }}</td><td>{{ $session->started_at->format('d M Y H:i:s') }}</td><td>{{ $session->last_activity_at->format('d M Y H:i:s') }}</td><td>{{ $session->ended_at?->format('d M Y H:i:s') ?: 'Open' }}</td><td>{{ $session->entry_page ?: '—' }}</td><td>{{ $session->history_count }}</td></tr>@empty<tr><td colspan="6" class="py-10 text-center">No sessions recorded.</td></tr>@endforelse
            </tbody></table></div>
            @if($sessions->hasPages())<div class="px-5 py-3">{{ $sessions->links() }}</div>@endif
        </section>

        <section class="overflow-hidden rounded-2xl border border-[#e5d8cc] bg-white shadow-sm">
            <header class="border-b border-[#eee4da] px-5 py-4"><h3 class="font-black text-[#392c24]">Page access history</h3><p class="text-xs text-[#8c7a6d]">HTML page visits only; assets and AJAX requests are excluded.</p></header>
            <div class="overflow-x-auto"><table class="w-full min-w-[680px] text-left"><thead class="bg-[#faf5ef] text-[10px] font-black uppercase text-[#8c7a6d]"><tr><th>Accessed</th><th>Session</th><th>Page</th><th>Route</th></tr></thead><tbody class="divide-y divide-[#eee4da] text-sm text-[#655246]">
                @forelse($history as $access)<tr><td>{{ $access->accessed_at->format('d M Y H:i:s') }}</td><td>#{{ $access->demo_access_session_id ?: '—' }}</td><td class="font-bold text-[#392c24]">{{ $access->page }}</td><td>{{ $access->route_name ?: '—' }}</td></tr>@empty<tr><td colspan="4" class="py-10 text-center">No page visits recorded.</td></tr>@endforelse
            </tbody></table></div>
            @if($history->hasPages())<div class="px-5 py-3">{{ $history->links() }}</div>@endif
        </section>
    </div>
</x-app-layout>

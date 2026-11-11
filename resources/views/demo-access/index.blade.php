<x-app-layout>
    @php
        $nextDirection = fn (string $column) => $sort === $column && $direction === 'asc' ? 'desc' : 'asc';
        $sortUrl = fn (string $column) => request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDirection($column), 'page' => null]);
    @endphp

    <div class="space-y-5" data-async-region="demo-access-index">
        <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-label="Demo access summary">
            @foreach([
                ['label' => 'Unique IP addresses', 'value' => $summary['unique'], 'note' => 'All recorded demo visitors'],
                ['label' => 'Accesses today', 'value' => $summary['today'], 'note' => 'New demo sessions today'],
                ['label' => 'Returning users', 'value' => $summary['returning'], 'note' => 'IP addresses with 2+ visits'],
                ['label' => 'Active sessions', 'value' => $summary['active'], 'note' => 'Active within 15 minutes'],
            ] as $card)
                <article class="rounded-2xl border border-[#e5d8cc] bg-white p-4 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-[.15em] text-[#8c7a6d]">{{ $card['label'] }}</p>
                    <strong class="mt-1 block text-3xl font-black text-[#392c24]">{{ number_format($card['value']) }}</strong>
                    <p class="mt-1 text-xs text-[#9b8a7d]">{{ $card['note'] }}</p>
                </article>
            @endforeach
        </section>

        <form method="GET" id="demo-access-filters" data-auto-submit-form action="{{ route('demo-access.index') }}" class="rounded-2xl border border-[#e5d8cc] bg-white p-4 shadow-sm">
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                <label class="xl:col-span-2"><span class="mb-1 block text-[10px] font-black uppercase text-[#8c7a6d]">Search</span><input name="search" value="{{ request('search') }}" placeholder="IP, location or last page" class="w-full rounded-xl border-[#dfd2c7] text-sm"></label>
                <label><span class="mb-1 block text-[10px] font-black uppercase text-[#8c7a6d]">From date</span><input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-xl border-[#dfd2c7] text-sm"></label>
                <label><span class="mb-1 block text-[10px] font-black uppercase text-[#8c7a6d]">To date</span><input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-xl border-[#dfd2c7] text-sm"></label>
                <label><span class="mb-1 block text-[10px] font-black uppercase text-[#8c7a6d]">Device</span><select name="device" class="w-full rounded-xl border-[#dfd2c7] text-sm"><option value="">All devices</option>@foreach($filters['devices'] as $value)<option @selected(request('device') === $value)>{{ $value }}</option>@endforeach</select></label>
                <label><span class="mb-1 block text-[10px] font-black uppercase text-[#8c7a6d]">Browser</span><select name="browser" class="w-full rounded-xl border-[#dfd2c7] text-sm"><option value="">All browsers</option>@foreach($filters['browsers'] as $value)<option @selected(request('browser') === $value)>{{ $value }}</option>@endforeach</select></label>
                <label><span class="mb-1 block text-[10px] font-black uppercase text-[#8c7a6d]">Country</span><select name="country" class="w-full rounded-xl border-[#dfd2c7] text-sm"><option value="">All countries</option>@foreach($filters['countries'] as $value)<option @selected(request('country') === $value)>{{ $value }}</option>@endforeach</select></label>
                <label><span class="mb-1 block text-[10px] font-black uppercase text-[#8c7a6d]">Status</span><select name="status" class="w-full rounded-xl border-[#dfd2c7] text-sm"><option value="">Any status</option><option value="active" @selected(request('status') === 'active')>Active</option><option value="inactive" @selected(request('status') === 'inactive')>Inactive</option></select></label>
            </div>
            <div class="mt-4 flex flex-wrap gap-2"><noscript><button class="rounded-xl bg-[#392c24] px-5 py-2.5 text-xs font-black uppercase tracking-wider text-white">Apply filters</button></noscript><a href="{{ route('demo-access.index') }}" class="rounded-xl border border-[#dfd2c7] px-5 py-2.5 text-xs font-black uppercase tracking-wider text-[#655246]">Clear</a></div>
        </form>

        <section class="overflow-hidden rounded-2xl border border-[#e5d8cc] bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-[1200px] w-full text-left">
                    <thead class="border-b border-[#eee4da] bg-[#faf5ef] text-[10px] font-black uppercase tracking-wider text-[#8c7a6d]"><tr>
                        @foreach(['ip_address' => 'IP address', 'first_access_at' => 'First access', 'last_access_at' => 'Last access', 'visit_count' => 'Visits', 'browser' => 'Client', 'country' => 'Location', 'last_page' => 'Last page'] as $column => $label)
                            <th><a class="hover:text-[#b95738]" href="{{ $sortUrl($column) }}">{{ $label }} @if($sort === $column){{ $direction === 'asc' ? '↑' : '↓' }}@endif</a></th>
                        @endforeach
                        <th>Status</th><th class="text-right">Actions</th>
                    </tr></thead>
                    <tbody class="divide-y divide-[#eee4da] text-sm text-[#655246]">
                        @forelse($ipAddresses as $ip)
                            <tr class="hover:bg-[#fffaf5]">
                                <td><a href="{{ route('demo-access.show', $ip) }}" class="font-black text-[#b95738] hover:underline">{{ $ip->ip_address }}</a></td>
                                <td><time datetime="{{ $ip->first_access_at->toIso8601String() }}">{{ $ip->first_access_at->format('d M Y H:i') }}</time></td>
                                <td><time datetime="{{ $ip->last_access_at->toIso8601String() }}">{{ $ip->last_access_at->format('d M Y H:i') }}</time></td>
                                <td class="font-black text-[#392c24]">{{ number_format($ip->visit_count) }}</td>
                                <td><strong class="block text-[#392c24]">{{ $ip->browser ?: 'Unknown' }}</strong><span class="text-xs">{{ $ip->device_type ?: 'Unknown' }} · {{ $ip->operating_system ?: 'Unknown' }}</span></td>
                                <td>{{ collect([$ip->city, $ip->country])->filter()->join(', ') ?: 'Unavailable' }}</td>
                                <td><span class="block max-w-[220px] truncate" title="{{ $ip->last_page }}">{{ $ip->last_page ?: '—' }}</span></td>
                                <td><span class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-black uppercase {{ $ip->isActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">{{ $ip->isActive() ? 'Active' : 'Inactive' }}</span>@if($ip->is_blocked)<span class="ml-1 inline-flex rounded-full bg-red-100 px-2.5 py-1 text-[10px] font-black uppercase text-red-800">Blocked</span>@endif</td>
                                <td><div class="flex justify-end gap-2"><a href="{{ route('demo-access.show', $ip) }}" class="rounded-lg border border-[#dfd2c7] px-3 py-2 text-xs font-bold">Details</a><form method="POST" action="{{ route('demo-access.block', $ip) }}">@csrf @method('PATCH')<button class="rounded-lg px-3 py-2 text-xs font-bold {{ $ip->is_blocked ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}" onclick="return confirm('{{ $ip->is_blocked ? 'Unblock' : 'Block' }} this IP address?')">{{ $ip->is_blocked ? 'Unblock' : 'Block' }}</button></form></div></td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="py-14 text-center"><strong class="block text-[#392c24]">No demo access records found</strong><span class="text-xs text-[#8c7a6d]">New demo sessions will appear here.</span></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
        @if($ipAddresses->hasPages())<div>{{ $ipAddresses->links() }}</div>@endif
    </div>
</x-app-layout>

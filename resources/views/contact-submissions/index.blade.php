<x-app-layout>
    <div class="space-y-4">
        <header>
            <p class="text-[10px] font-black uppercase tracking-[.2em] text-[#b95738]">Website enquiries</p>
            <h2 class="text-2xl font-black text-[#392c24]">Contact submissions</h2>
            <p class="mt-1 text-xs text-[#8c7a6d]">{{ $submissions->total() }} {{ Str::plural('submission', $submissions->total()) }} received</p>
        </header>

        <section class="overflow-hidden rounded-2xl border border-[#e5d8cc] bg-white shadow-[0_6px_18px_rgba(68,44,29,.05)]">
            <div class="hidden grid-cols-[1.25fr_1.4fr_1fr_.8fr] gap-4 border-b border-[#eee4da] bg-[#faf5ef] px-5 py-3 text-[10px] font-black uppercase tracking-wider text-[#8c7a6d] md:grid">
                <span>Full name</span><span>Email address</span><span>Telephone</span><span>Received</span>
            </div>
            <div class="divide-y divide-[#eee4da]">
                @forelse($submissions as $submission)
                    <article class="grid gap-3 px-4 py-4 md:grid-cols-[1.25fr_1.4fr_1fr_.8fr] md:items-center md:gap-4 md:px-5">
                        <div><span class="text-[9px] font-black uppercase text-[#9b8a7d] md:hidden">Full name</span><strong class="block text-sm text-[#392c24]">{{ $submission->full_name }}</strong></div>
                        <div class="min-w-0"><span class="text-[9px] font-black uppercase text-[#9b8a7d] md:hidden">Email address</span><a class="block truncate text-sm font-bold text-[#b95738] hover:underline" href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></div>
                        <div><span class="text-[9px] font-black uppercase text-[#9b8a7d] md:hidden">Telephone</span><a class="block text-sm font-bold text-[#655246] hover:text-[#b95738]" href="tel:{{ preg_replace('/[^+0-9]/', '', $submission->telephone) }}">{{ $submission->telephone }}</a></div>
                        <div><span class="text-[9px] font-black uppercase text-[#9b8a7d] md:hidden">Received</span><time class="block text-xs font-bold text-[#655246]" datetime="{{ $submission->created_at->toIso8601String() }}">{{ $submission->created_at->format('d M Y') }}</time><span class="text-[10px] text-[#9b8a7d]">{{ $submission->created_at->format('H:i') }}</span></div>
                    </article>
                @empty
                    <div class="px-5 py-16 text-center"><p class="text-sm font-black text-[#392c24]">No contact submissions yet</p><p class="mt-1 text-xs text-[#8c7a6d]">New enquiries from the website will appear here.</p></div>
                @endforelse
            </div>
        </section>

        @if($submissions->hasPages())<div>{{ $submissions->links() }}</div>@endif
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        Profile
    </x-slot>

    <div class="space-y-4">
        <div class="glass p-5 sm:p-6 rounded-3xl border border-white/10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-koya rounded-2xl flex items-center justify-center text-white text-xl font-black shadow-lg shadow-koya/30">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-black">{{ auth()->user()->name }}</h1>
                        <p class="text-[11px] text-white/55 uppercase tracking-[0.2em] font-black mt-0.5">{{ auth()->user()->role }}</p>
                        <p class="text-xs text-white/45 mt-1">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto bg-red-500/10 border border-red-500/30 text-red-300 px-5 py-3 rounded-xl font-black uppercase tracking-wider text-xs hover:bg-red-500/15 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-4">
            <div class="xl:col-span-7 glass p-5 sm:p-6 rounded-3xl border border-white/10">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="xl:col-span-5 space-y-4">
                <div class="glass p-5 sm:p-6 rounded-3xl border border-white/10">
                    @include('profile.partials.update-password-form')
                </div>
                <div class="glass p-5 sm:p-6 rounded-3xl border border-red-500/20">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

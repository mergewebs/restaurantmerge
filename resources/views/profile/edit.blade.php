<x-app-layout>
    <x-slot name="header">{{ __('Account') }}</x-slot>
    <style>
        .profile-shell { max-width: 72rem; margin-inline: auto; }
        .profile-hero { position: relative; overflow: hidden; background: linear-gradient(135deg, #312e81 0%, #4f46e5 55%, #6366f1 100%) !important; border: 0 !important; box-shadow: 0 18px 45px -24px rgba(79, 70, 229, .65) !important; }
        .profile-hero::after { content: ''; position: absolute; width: 15rem; height: 15rem; right: -5rem; top: -7rem; border-radius: 9999px; background: rgba(255, 255, 255, .11); }
        .profile-hero h1, .profile-hero p { color: #fff !important; }
        .profile-role { background: rgba(255, 255, 255, .94) !important; color: #3730a3 !important; }
        .profile-logout { background: #312e81 !important; border-color: rgba(255, 255, 255, .3) !important; color: #fff !important; box-shadow: 0 8px 20px -12px rgba(15, 23, 42, .8); }
        .profile-logout:hover { background: #1e1b4b !important; color: #fff !important; }
        .profile-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 1.25rem; box-shadow: 0 8px 28px -20px rgba(15, 23, 42, .32); }
        .profile-icon { display: grid; width: 2.75rem; height: 2.75rem; flex: 0 0 auto; place-items: center; border-radius: .875rem; background: #eef2ff; color: #4f46e5; }
        .profile-save { min-height: 3rem; }
        .profile-passcode { min-height: 3.25rem !important; font-size: 1.15rem !important; }
        @media (max-width: 639px) { .profile-card { border-radius: 1rem; } .profile-save { width: 100% !important; } }
    </style>
    <div class="profile-shell space-y-4 sm:space-y-5">
        <section class="profile-hero rounded-2xl sm:rounded-3xl p-5 sm:p-7" aria-labelledby="profile-name">
            <div class="relative z-10 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex min-w-0 items-center gap-4">
                    <div class="grid h-16 w-16 shrink-0 place-items-center rounded-2xl border border-white/20 bg-white/15 text-2xl font-black text-white shadow-inner sm:h-20 sm:w-20 sm:text-3xl">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="min-w-0">
                        <span class="profile-role inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest">{{ auth()->user()->role }}</span>
                        <h1 id="profile-name" class="mt-2 truncate text-xl font-extrabold text-white sm:text-2xl">{{ auth()->user()->name }}</h1>
                        <p class="mt-1 text-sm text-indigo-100">{{ __('Manage your profile and account security.') }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="sm:shrink-0">
                    @csrf
                    <button type="submit" class="profile-logout flex min-h-12 w-full items-center justify-center gap-2 rounded-xl border px-5 text-sm font-bold transition focus:outline-none focus:ring-2 focus:ring-white/70 sm:w-auto">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        {{ __('Log out') }}
                    </button>
                </form>
            </div>
        </section>
        <div class="grid grid-cols-1 items-start gap-4 sm:gap-5 lg:grid-cols-2">
            <section class="profile-card p-5 sm:p-7">@include('profile.partials.update-profile-information-form')</section>
            <section class="profile-card p-5 sm:p-7">@include('profile.partials.update-passcode-form')</section>
        </div>
        @if ($user->isAdmin())
            <section class="profile-card border-red-200 bg-red-50/50 p-5 sm:p-7">@include('profile.partials.delete-user-form')</section>
        @endif
    </div>
</x-app-layout>

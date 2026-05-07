<section>
    <header>
        <h2 class="text-base font-black uppercase tracking-widest text-koya">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-xs text-white/55">
            {{ __('Use a strong password to keep your account secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-[11px] uppercase tracking-widest text-white/65" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1.5 block w-full rounded-xl border-white/15 bg-white/5 text-white placeholder:text-white/35 focus:border-koya focus:ring-koya/40" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-[11px] uppercase tracking-widest text-white/65" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1.5 block w-full rounded-xl border-white/15 bg-white/5 text-white placeholder:text-white/35 focus:border-koya focus:ring-koya/40" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="lg:col-span-2">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-[11px] uppercase tracking-widest text-white/65" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1.5 block w-full rounded-xl border-white/15 bg-white/5 text-white placeholder:text-white/35 focus:border-koya focus:ring-koya/40" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="lg:col-span-2 flex items-center gap-4">
            <x-primary-button class="px-5 py-2.5 rounded-xl uppercase tracking-widest text-[11px]">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs text-white/65"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

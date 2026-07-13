<section>
    <header>
        <h2 class="text-base font-black uppercase tracking-widest text-koya">
            {{ __('Update Passcode') }}
        </h2>

        <p class="mt-1 text-xs text-white/55">
            {{ __('Choose a new 6-digit passcode for quick admin access from mobile.') }}
        </p>
    </header>

    <form method="post" action="{{ route('passcode.update') }}" class="mt-4 grid grid-cols-1 gap-4">
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_passcode" :value="__('Current Passcode')" class="text-[11px] uppercase tracking-widest text-white/65" />
            <x-text-input id="current_passcode" name="current_passcode" type="password" inputmode="numeric" pattern="[0-9]*" maxlength="6" class="mt-1.5 block w-full rounded-xl border-white/15 bg-white/5 text-center tracking-[0.35em] text-white placeholder:text-white/35 focus:border-koya focus:ring-koya/40" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePasscode->get('current_passcode')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="passcode" :value="__('New Passcode')" class="text-[11px] uppercase tracking-widest text-white/65" />
            <x-text-input id="passcode" name="passcode" type="password" inputmode="numeric" pattern="[0-9]*" maxlength="6" class="mt-1.5 block w-full rounded-xl border-white/15 bg-white/5 text-center tracking-[0.35em] text-white placeholder:text-white/35 focus:border-koya focus:ring-koya/40" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePasscode->get('passcode')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="passcode_confirmation" :value="__('Confirm Passcode')" class="text-[11px] uppercase tracking-widest text-white/65" />
            <x-text-input id="passcode_confirmation" name="passcode_confirmation" type="password" inputmode="numeric" pattern="[0-9]*" maxlength="6" class="mt-1.5 block w-full rounded-xl border-white/15 bg-white/5 text-center tracking-[0.35em] text-white placeholder:text-white/35 focus:border-koya focus:ring-koya/40" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePasscode->get('passcode_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="px-5 py-2.5 rounded-xl uppercase tracking-widest text-[11px]">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'passcode-updated')
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

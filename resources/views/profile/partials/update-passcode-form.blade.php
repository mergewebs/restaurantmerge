<div x-data="{ showPasscodes: false }">
    <header class="flex items-start gap-3">
        <span class="profile-icon" aria-hidden="true"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3V7a3 3 0 10-6 0v1c0 1.657 1.343 3 3 3zm-5 0h10a2 2 0 012 2v6H5v-6a2 2 0 012-2z" /></svg></span>
        <div class="min-w-0 flex-1"><h2 class="text-base font-extrabold text-slate-900 sm:text-lg">{{ __('Security passcode') }}</h2><p class="mt-1 text-sm leading-5 text-slate-500">{{ __('Use a private 6-digit code for quick admin access.') }}</p></div>
    </header>
    <form method="post" action="{{ route('passcode.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('put')
        <div>
            <x-input-label for="current_passcode" :value="__('Current passcode')" />
            <x-text-input id="current_passcode" name="current_passcode" x-bind:type="showPasscodes ? 'text' : 'password'" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" class="profile-passcode mt-1.5 block w-full text-center font-bold tracking-[0.4em]" autocomplete="current-password" placeholder="••••••" required />
            <x-input-error :messages="$errors->updatePasscode->get('current_passcode')" class="mt-2" />
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div><x-input-label for="passcode" :value="__('New passcode')" /><x-text-input id="passcode" name="passcode" x-bind:type="showPasscodes ? 'text' : 'password'" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" class="profile-passcode mt-1.5 block w-full text-center font-bold tracking-[0.4em]" autocomplete="new-password" placeholder="••••••" required /><x-input-error :messages="$errors->updatePasscode->get('passcode')" class="mt-2" /></div>
            <div><x-input-label for="passcode_confirmation" :value="__('Confirm passcode')" /><x-text-input id="passcode_confirmation" name="passcode_confirmation" x-bind:type="showPasscodes ? 'text' : 'password'" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" class="profile-passcode mt-1.5 block w-full text-center font-bold tracking-[0.4em]" autocomplete="new-password" placeholder="••••••" required /><x-input-error :messages="$errors->updatePasscode->get('passcode_confirmation')" class="mt-2" /></div>
        </div>
        <label class="flex min-h-11 cursor-pointer items-center gap-3 rounded-xl bg-slate-50 px-3 normal-case tracking-normal">
            <input type="checkbox" x-model="showPasscodes" class="h-5 w-5 shrink-0 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" style="width: 1.25rem !important; min-height: 1.25rem !important; padding: 0 !important;">
            <span class="text-sm font-medium text-slate-600">{{ __('Show passcodes') }}</span>
        </label>
        <div class="flex flex-col-reverse gap-3 pt-1 sm:flex-row sm:items-center">
            <x-primary-button class="profile-save sm:w-auto sm:px-7">{{ __('Update passcode') }}</x-primary-button>
        </div>
    </form>
</div>

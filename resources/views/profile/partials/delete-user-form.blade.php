<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <header class="flex items-start gap-3">
        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-red-100 text-red-600" aria-hidden="true"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></span>
        <div><h2 class="text-base font-extrabold text-red-900">{{ __('Delete account') }}</h2><p class="mt-1 max-w-xl text-sm leading-5 text-red-700/80">{{ __('Permanently delete your account and access. This cannot be undone.') }}</p></div>
    </header>
    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'confirm-user-deletion')" class="min-h-12 w-full shrink-0 rounded-xl border border-red-200 bg-white px-5 text-sm font-bold text-red-700 transition hover:border-red-300 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500/40 sm:w-auto">{{ __('Delete account') }}</button>
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable maxWidth="md">
        <form method="post" action="{{ route('profile.destroy') }}" class="p-5 sm:p-7">
            @csrf
            @method('delete')
            <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-red-100 text-red-600 sm:mx-0"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-2.98L13.74 4a2 2 0 00-3.48 0L3.33 16.02A2 2 0 005.07 19z" /></svg></div>
            <h2 class="mt-4 text-center text-xl font-extrabold text-slate-900 sm:text-left">{{ __('Delete your account?') }}</h2>
            <p class="mt-2 text-center text-sm leading-6 text-slate-600 sm:text-left">{{ __('All account access will be permanently removed. Enter your 6-digit passcode to confirm.') }}</p>
            <div class="mt-5">
                <x-input-label for="delete_account_passcode" :value="__('Passcode')" />
                <x-text-input id="delete_account_passcode" name="passcode" type="password" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" class="profile-passcode mt-1.5 block w-full text-center font-bold tracking-[0.4em]" placeholder="••••••" required />
                <x-input-error :messages="$errors->userDeletion->get('passcode')" class="mt-2" />
            </div>
            <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <x-secondary-button x-on:click="$dispatch('close')" class="min-h-12 w-full">{{ __('Keep account') }}</x-secondary-button>
                <x-danger-button class="min-h-12 w-full bg-red-600 text-white hover:bg-red-700">{{ __('Yes, delete') }}</x-danger-button>
            </div>
        </form>
    </x-modal>
</div>

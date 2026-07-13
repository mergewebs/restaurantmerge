<section class="space-y-6">
    <header>
        <h2 class="text-base font-black uppercase tracking-widest text-red-300">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-xs text-white/60">
            {{ __('This action is permanent and cannot be undone.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-5 py-2.5 rounded-xl uppercase tracking-widest text-[11px]"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-black text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Please enter your 6-digit passcode to confirm permanent deletion.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="passcode" value="{{ __('Passcode') }}" class="sr-only" />

                <x-text-input
                    id="passcode"
                    name="passcode"
                    type="password"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    maxlength="6"
                    class="mt-1 block w-3/4 text-center tracking-[0.35em]"
                />

                <x-input-error :messages="$errors->userDeletion->get('passcode')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>

<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="passcode" :value="__('6-Digit Passcode')" />
            <x-text-input
                id="passcode"
                class="block mt-1 w-full text-center tracking-[0.35em]"
                type="password"
                inputmode="numeric"
                pattern="[0-9]*"
                maxlength="6"
                name="passcode"
                :value="old('passcode')"
                required
                autofocus
                autocomplete="one-time-code"

            />

            <x-input-error :messages="$errors->get('passcode')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-white/5 border-white/10 text-koya shadow-sm focus:ring-koya/50" name="remember">
                <span class="ms-2 text-sm text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col space-y-4 mt-8">
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>



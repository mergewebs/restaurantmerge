<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="passcode" :value="__('6-Digit Passcode')" />
            <div class="relative mt-1">
                <x-text-input
                    id="passcode"
                    class="block w-full pr-12 text-center tracking-[0.35em]"
                    type="password"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    maxlength="6"
                    name="passcode"
                    :value="old('passcode', '123456')"
                    required
                    autofocus
                    autocomplete="current-password"
                />
                <button
                    id="toggle-passcode"
                    type="button"
                    class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-slate-500 transition hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                    aria-controls="passcode"
                    aria-label="{{ __('Show passcode') }}"
                    aria-pressed="false"
                >
                    <svg class="h-5 w-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z" />
                        <circle cx="12" cy="12" r="2.75" />
                    </svg>
                </button>
            </div>

            <p class="mt-3 text-sm text-slate-600">
                {{ __('Demo passcode:') }} <strong class="font-bold tracking-wider text-slate-900">123456</strong>
            </p>

            <x-input-error :messages="$errors->get('passcode')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-white/5 border-white/10 text-accent shadow-sm focus:ring-accent/50" name="remember">
                <span class="ms-2 text-sm text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col space-y-4 mt-8">
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const passcode = document.getElementById('passcode');
            const toggle = document.getElementById('toggle-passcode');

            toggle.addEventListener('click', () => {
                const isVisible = passcode.type === 'text';

                passcode.type = isVisible ? 'password' : 'text';
                toggle.setAttribute('aria-pressed', String(! isVisible));
                toggle.setAttribute('aria-label', isVisible ? @js(__('Show passcode')) : @js(__('Hide passcode')));
                passcode.focus();
            });
        });
    </script>
</x-guest-layout>

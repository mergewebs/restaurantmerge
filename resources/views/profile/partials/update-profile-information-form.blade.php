<section>
    <header>
        <h2 class="text-base font-black uppercase tracking-widest text-koya">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-xs text-white/55">
            {{ __("Update your basic details and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" class="text-[11px] uppercase tracking-widest text-white/65" />
            <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full rounded-xl border-white/15 bg-white/5 text-white placeholder:text-white/35 focus:border-koya focus:ring-koya/40" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-[11px] uppercase tracking-widest text-white/65" />
            <x-text-input id="email" name="email" type="email" class="mt-1.5 block w-full rounded-xl border-white/15 bg-white/5 text-white placeholder:text-white/35 focus:border-koya focus:ring-koya/40" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-xs mt-2 text-white/65">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-xs text-koya hover:text-koya-light rounded-md focus:outline-none focus:ring-2 focus:ring-koya/30">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-emerald-300">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="lg:col-span-2 flex items-center gap-4">
            <x-primary-button class="px-5 py-2.5 rounded-xl uppercase tracking-widest text-[11px]">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
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

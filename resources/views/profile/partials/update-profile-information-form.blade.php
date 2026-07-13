<section>
    <header>
        <h2 class="text-base font-black uppercase tracking-widest text-koya">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-xs text-white/55">
            {{ __('Update the display name shown around the admin panel.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4 space-y-4">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" class="text-[11px] uppercase tracking-widest text-white/65" />
            <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full rounded-xl border-white/15 bg-white/5 text-white placeholder:text-white/35 focus:border-koya focus:ring-koya/40" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="flex items-center gap-4">
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

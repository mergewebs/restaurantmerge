<div>
    <header class="flex items-start gap-3">
        <span class="profile-icon" aria-hidden="true"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6.586-6.586a2 2 0 112.828 2.828L11.828 13.83a4 4 0 01-1.414.94L7 16l1.23-3.414A4 4 0 019.17 11H9zm-3 9v4h4" /></svg></span>
        <div><h2 class="text-base font-extrabold text-slate-900 sm:text-lg">{{ __('Profile information') }}</h2><p class="mt-1 text-sm leading-5 text-slate-500">{{ __('Update the name shown throughout the admin panel.') }}</p></div>
    </header>
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')
        <div>
            <x-input-label for="name" :value="__('Display name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center">
            <x-primary-button class="profile-save sm:w-auto sm:px-7">{{ __('Save changes') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)" class="flex items-center gap-1.5 text-sm font-semibold text-emerald-700" role="status"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>{{ __('Changes saved') }}</p>
            @endif
        </div>
    </form>
</div>

<div class="pwa-install" data-pwa-install hidden>
    <button type="button" class="pwa-install__button" data-pwa-install-button aria-haspopup="dialog">
        <svg aria-hidden="true" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2">
            <path d="M12 3v12m0 0 4-4m-4 4-4-4M5 19h14" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <span>{{ __('public.pwa_install_app') }}</span>
    </button>
</div>

<dialog class="pwa-ios-dialog" data-pwa-ios-dialog aria-labelledby="pwa-ios-title">
    <div class="pwa-ios-dialog__content">
        <button type="button" class="pwa-ios-dialog__close" data-pwa-ios-close aria-label="{{ __('public.pwa_close') }}">&times;</button>
        <img src="{{ asset('apple-touch-icon.png') }}" alt="" width="64" height="64">
        <h2 id="pwa-ios-title">{{ __('public.pwa_install_app') }}</h2>
        <p>{{ __('public.pwa_ios_instructions') }}</p>
        <button type="button" class="pwa-ios-dialog__done" data-pwa-ios-close>{{ __('public.pwa_done') }}</button>
    </div>
</dialog>

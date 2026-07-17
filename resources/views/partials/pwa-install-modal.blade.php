<dialog class="pwa-ios-dialog" data-pwa-ios-dialog aria-labelledby="pwa-ios-title">
    <div class="pwa-ios-dialog__content">
        <button type="button" class="pwa-ios-dialog__close" data-pwa-ios-close aria-label="{{ __('public.pwa_close') }}">&times;</button>
        <img src="{{ asset('apple-touch-icon.png') }}" alt="" width="64" height="64">
        <h2 id="pwa-ios-title">{{ __('public.pwa_install_app') }}</h2>
        <div class="pwa-install-guide" data-pwa-ios-instructions>
            <p class="pwa-install-guide__summary">{{ __('public.pwa_ios_instructions') }}</p>
            <ol class="pwa-install-steps">
                <li>{{ __('public.pwa_ios_step_safari') }}</li>
                <li>{{ __('public.pwa_ios_step_share') }}</li>
                <li>{{ __('public.pwa_ios_step_home') }}</li>
                <li>{{ __('public.pwa_ios_step_add') }}</li>
            </ol>
        </div>
        <div class="pwa-install-guide" data-pwa-browser-instructions hidden>
            <p class="pwa-install-guide__summary">{{ __('public.pwa_browser_instructions') }}</p>
            <ol class="pwa-install-steps">
                <li>{{ __('public.pwa_browser_step_menu') }}</li>
                <li>{{ __('public.pwa_browser_step_install') }}</li>
                <li>{{ __('public.pwa_browser_step_confirm') }}</li>
            </ol>
        </div>
        <button type="button" class="pwa-ios-dialog__done" data-pwa-ios-close>{{ __('public.pwa_done') }}</button>
    </div>
</dialog>

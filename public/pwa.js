(() => {
    'use strict';

    const isStandalone = () =>
        window.matchMedia('(display-mode: standalone)').matches ||
        window.navigator.standalone === true;

    if ('serviceWorker' in navigator && (window.isSecureContext || location.hostname === 'localhost')) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js', { scope: '/', updateViaCache: 'none' }).catch(() => {
                // PWA support is progressive; a registration failure must not affect the website.
            });
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const container = document.querySelector('[data-pwa-install]');
        const installButton = document.querySelector('[data-pwa-install-button]');
        const iosDialog = document.querySelector('[data-pwa-ios-dialog]');

        if (!container || !installButton || isStandalone()) {
            return;
        }

        let deferredPrompt = null;
        const isIos = /iphone|ipad|ipod/i.test(navigator.userAgent) ||
            (/macintosh/i.test(navigator.userAgent) && navigator.maxTouchPoints > 1);

        const showInstall = () => {
            if (!isStandalone()) {
                container.hidden = false;
            }
        };

        const hideInstall = () => {
            container.hidden = true;
            deferredPrompt = null;
        };

        window.addEventListener('beforeinstallprompt', (event) => {
            event.preventDefault();
            deferredPrompt = event;
            showInstall();
        });

        window.addEventListener('appinstalled', hideInstall);
        window.matchMedia('(display-mode: standalone)').addEventListener?.('change', (event) => {
            if (event.matches) {
                hideInstall();
            }
        });

        if (isIos) {
            showInstall();
        }

        installButton.addEventListener('click', async () => {
            if (deferredPrompt) {
                const prompt = deferredPrompt;
                deferredPrompt = null;
                await prompt.prompt();
                const choice = await prompt.userChoice;

                if (choice.outcome === 'accepted') {
                    hideInstall();
                }
                return;
            }

            if (isIos && iosDialog) {
                if (typeof iosDialog.showModal === 'function') {
                    iosDialog.showModal();
                } else {
                    iosDialog.setAttribute('open', '');
                }
            }
        });

        iosDialog?.querySelectorAll('[data-pwa-ios-close]').forEach((button) => {
            button.addEventListener('click', () => {
                if (typeof iosDialog.close === 'function') {
                    iosDialog.close();
                } else {
                    iosDialog.removeAttribute('open');
                }
            });
        });

        iosDialog?.addEventListener('click', (event) => {
            if (event.target === iosDialog) {
                if (typeof iosDialog.close === 'function') {
                    iosDialog.close();
                } else {
                    iosDialog.removeAttribute('open');
                }
            }
        });
    });
})();

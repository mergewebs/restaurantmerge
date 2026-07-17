(() => {
    'use strict';

    const isStandalone = () =>
        window.matchMedia('(display-mode: standalone)').matches ||
        window.navigator.standalone === true;

    let deferredPrompt = null;

    window.addEventListener('beforeinstallprompt', (event) => {
        event.preventDefault();
        deferredPrompt = event;

        const container = document.querySelector('[data-pwa-install]');
        if (container && !isStandalone()) {
            container.hidden = false;
        }
    });

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
        const iosInstructions = document.querySelector('[data-pwa-ios-instructions]');
        const browserInstructions = document.querySelector('[data-pwa-browser-instructions]');

        if (!container || !installButton || isStandalone()) {
            return;
        }

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

        window.addEventListener('appinstalled', hideInstall);
        window.matchMedia('(display-mode: standalone)').addEventListener?.('change', (event) => {
            if (event.matches) {
                hideInstall();
            }
        });

        showInstall();

        const showInstructions = () => {
            if (!iosDialog) {
                return;
            }

            if (iosInstructions) {
                iosInstructions.hidden = !isIos;
            }
            if (browserInstructions) {
                browserInstructions.hidden = isIos;
            }

            if (typeof iosDialog.showModal === 'function') {
                iosDialog.showModal();
            } else {
                iosDialog.setAttribute('open', '');
            }
        };

        installButton.addEventListener('click', async () => {
            if (deferredPrompt) {
                const prompt = deferredPrompt;
                deferredPrompt = null;
                try {
                    await prompt.prompt();
                    const choice = await prompt.userChoice;

                    if (choice.outcome === 'accepted') {
                        hideInstall();
                    }
                    return;
                } catch {
                    // Fall through to manual browser instructions.
                }
            }

            showInstructions();
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

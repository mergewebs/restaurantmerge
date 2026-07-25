(() => {
    'use strict';

    if ('serviceWorker' in navigator && (window.isSecureContext || location.hostname === 'localhost')) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js', {
                scope: '/admin/',
                updateViaCache: 'none',
            }).catch(() => {
                // PWA support is progressive; registration failures must not affect the admin dashboard.
            });
        });
    }
})();

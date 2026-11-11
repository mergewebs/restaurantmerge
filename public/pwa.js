(() => {
    'use strict';

    // Defense in depth if the shared head is ever reused by another layout.
    if (!/^\/admin(?:\/|$)/.test(location.pathname)) return;

    if ('serviceWorker' in navigator && window.isSecureContext) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js', {
                scope: '/admin/',
                updateViaCache: 'none',
            }).catch(() => { /* Installation is optional; normal operation continues. */ });
        });
    }

    const initialize = () => {
        const status = document.querySelector('[data-admin-connection]');
        const updateConnection = () => {
            if (status) status.hidden = navigator.onLine !== false;
        };
        window.addEventListener('online', updateConnection);
        window.addEventListener('offline', updateConnection);
        updateConnection();

        // Keep modals and drawers within the space above the phone keyboard.
        const viewport = window.visualViewport;
        const updateViewport = () => {
            const mobile = window.matchMedia('(max-width: 1023px)').matches;
            document.documentElement.style.setProperty('--admin-viewport-height', `${viewport.height}px`);
            document.documentElement.style.setProperty('--admin-viewport-top', `${viewport.offsetTop}px`);
            const editing = document.activeElement?.matches('input, textarea, select, [contenteditable="true"]');
            document.body.classList.toggle('admin-keyboard-open', mobile && editing && window.innerHeight - viewport.height > 150);
        };
        if (viewport) {
            viewport.addEventListener('resize', updateViewport);
            viewport.addEventListener('scroll', updateViewport);
            document.addEventListener('focusin', updateViewport);
            document.addEventListener('focusout', () => requestAnimationFrame(updateViewport));
            updateViewport();
        }

        // Compact cards retain table nodes, all data, filters and action handlers.
        const labelTables = (root) => {
            const tables = root.matches?.('table') ? [root] : root.querySelectorAll?.('table') || [];
            tables.forEach((table) => {
                const headers = [...(table.tHead?.rows[0]?.cells || [])];
                if (!headers.length || table.tHead.rows.length !== 1
                    || table.querySelector('[rowspan], thead [colspan]')) return;
                const rows = [...table.tBodies].flatMap((body) => [...body.rows]);
                if (rows.some((row) => [...row.cells].some((cell) => cell.colSpan > 1 && cell.colSpan !== headers.length))) return;
                rows.forEach((row) => [...row.cells].forEach((cell, index) => {
                    if (cell.colSpan === 1) cell.dataset.cellLabel = headers[index]?.textContent.trim() || 'Actions';
                }));
                table.classList.add('admin-mobile-table');
            });
        };
        labelTables(document);
        new MutationObserver((records) => {
            const changedTables = new Set();
            records.forEach((record) => record.addedNodes.forEach((node) => {
                if (node.nodeType !== 1) return;
                const table = node.closest('table');
                if (table) changedTables.add(table);
                else labelTables(node);
            }));
            changedTables.forEach(labelTables);
        }).observe(document.body, { childList: true, subtree: true });

        // Explicit user action only; no banners interrupt restaurant service.
        let installPrompt;
        const installButtons = document.querySelectorAll('[data-pwa-install-button]');
        const installHelp = document.querySelector('[data-pwa-install-help]');
        const standalone = window.matchMedia('(display-mode: standalone)').matches || navigator.standalone === true;
        if (installHelp && !standalone && /iPad|iPhone|iPod/.test(navigator.userAgent)) installHelp.hidden = false;
        window.addEventListener('beforeinstallprompt', (event) => {
            if (standalone || !installButtons.length) return;
            event.preventDefault();
            installPrompt = event;
            installButtons.forEach((button) => { button.hidden = false; });
        });
        installButtons.forEach((button) => button.addEventListener('click', async () => {
            if (!installPrompt) return;
            const prompt = installPrompt;
            installPrompt = null;
            installButtons.forEach((item) => { item.hidden = true; });
            try { await prompt.prompt(); } catch (_) { /* Browser menu remains available. */ }
        }));
        window.addEventListener('appinstalled', () => {
            installPrompt = null;
            installButtons.forEach((button) => { button.hidden = true; });
            if (installHelp) installHelp.hidden = true;
        });
    };

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initialize);
    else initialize();
})();

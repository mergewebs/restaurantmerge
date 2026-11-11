// Run against an isolated LOCAL fixture server (one order, table and reservation).
// PWA_TEST_URL=http://127.0.0.1:8765 PWA_TEST_PASSCODE=... node tests/Browser/admin-pwa.cjs
// Uses an existing Playwright installation, optionally located by PLAYWRIGHT_MODULE.
const { chromium } = require(process.env.PLAYWRIGHT_MODULE || 'playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs/promises');

const base = process.env.PWA_TEST_URL || 'http://127.0.0.1:8765';
assert.ok(['localhost', '127.0.0.1'].includes(new URL(base).hostname), 'Use an isolated local test server');
assert.ok(process.env.PWA_TEST_PASSCODE, 'Set PWA_TEST_PASSCODE for the fixture owner');
const routes = ['dashboard', 'orders', 'orders/1', 'orders/1/ticket', 'reservations',
    'reservations/create', 'reservations/1/edit', 'tables', 'tables/1/order', 'tables/completed',
    'kitchen', 'finance', 'finance/categories', 'menu-items', 'inventory', 'cash-register',
    'reports', 'restaurant/config', 'users', 'profile', 'contact-submissions', 'demo-access'];

(async () => {
    const browser = await chromium.launch({ channel: 'chrome', headless: true });
    let checks = 0;
    try {
        const context = await browser.newContext({ viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true });
        const page = await context.newPage();
        const errors = [];
        page.on('pageerror', (error) => errors.push(`${page.url()}: ${error.message}`));
        const visit = async (route) => {
            const response = await page.goto(`${base}/admin/${route}`, { waitUntil: 'networkidle' });
            assert.equal(response.status(), 200, route);
            assert.match(response.headers()['cache-control'], /no-store/, route);
        };
        await visit('login');
        await page.locator('[name=passcode]').fill(process.env.PWA_TEST_PASSCODE);
        await Promise.all([page.waitForURL('**/admin/dashboard'), page.locator('button[type=submit]').click()]);

        for (const width of process.env.PWA_TEST_INTERACTIONS_ONLY ? [] : [320, 390]) {
            await page.setViewportSize({ width, height: 844 });
            for (const route of routes) {
                await visit(route);
                const layout = await page.evaluate(() => ({
                    scroll: document.documentElement.scrollWidth,
                    width: innerWidth,
                    smallFields: [...document.querySelectorAll('input, select, textarea')].filter((field) => {
                        if (['hidden', 'checkbox', 'radio', 'range', 'color'].includes(field.type) || !field.getClientRects().length) return false;
                        return parseFloat(getComputedStyle(field).fontSize) < 16;
                    }).map((field) => field.name || field.type),
                }));
                assert.ok(layout.scroll <= layout.width + 1, `${route} overflows at ${width}px`);
                assert.deepEqual(layout.smallFields, [], `${route} has zoom-triggering fields at ${width}px`);
                checks++;
            }
            console.log(`Checked all admin screens at ${width}px.`);
        }

        await visit('dashboard');
        const dock = page.locator('.bottom-primary-links');
        assert.equal(await dock.locator(':scope > a:visible').count(), 4);
        await dock.locator('button[title="More tools"]').click();
        await page.locator('.nav-more-dropdown').getByRole('menuitem', { name: 'Reservations', exact: true }).click();
        await page.waitForURL('**/admin/reservations');
        checks++;

        // Exercise real modal triggers and confirm panels, fields and submit actions fit.
        for (const [route, trigger, panel] of [
            ['orders', '.orders-create', '.order-modal-card'],
            ['reservations', '.guest-head button', '.booking-modal-card:visible'],
            ['dashboard', '.restaurant-quick-actions button', '.reservation-add-modal > .glass'],
            ['finance', '.finance-hero-button.primary', '.add-finance-modal'],
            ['inventory', '.inv-action.primary', 'form[action$="/inventory/ingredients"]'],
            ['menu-items', '.menu-hero-button.primary', '.modal-wrap:visible .modal-panel'],
            ['users', '.person-card button', 'form:visible:has(input[name="name"][x-model])'],
        ]) {
            console.log(`Checking ${route} modal.`);
            await visit(route);
            await page.locator(trigger).first().click();
            const modal = page.locator(panel).first();
            await modal.waitFor({ state: 'visible' });
            const rect = await modal.boundingBox();
            assert.ok(rect.x >= 0 && rect.x + rect.width <= 391, `${route}: modal width`);
            assert.ok(rect.height <= 844, `${route}: modal height`);
            const fields = modal.locator('input:not([type=hidden]), select, textarea');
            for (const field of await fields.all()) {
                if (!await field.isVisible()) continue;
                const metrics = await field.evaluate((element) => ({ size: parseFloat(getComputedStyle(element).fontSize), width: element.getBoundingClientRect().width, type: element.type, name: element.name }));
                assert.ok((['checkbox', 'radio'].includes(metrics.type) || metrics.size >= 16) && metrics.width <= rect.width, `${route}: modal field ${JSON.stringify(metrics)}`);
            }
            const submit = modal.locator('button:not([type=button])').last();
            if (await submit.count()) {
                await submit.scrollIntoViewIfNeeded();
                assert.ok(await submit.isVisible(), `${route}: submit reachable`);
            }
            checks++;
        }

        // Table decoration also applies to dynamically inserted filter results.
        await visit('inventory');
        await page.evaluate(() => {
            const host = document.createElement('section');
            host.id = 'pwa-table-test';
            host.innerHTML = '<table><thead><tr><th>Item</th><th>Action</th></tr></thead><tbody><tr><td>Fresh result</td><td><button type="button">Edit</button></td></tr></tbody></table>';
            document.querySelector('.admin-main').append(host);
        });
        await page.waitForSelector('#pwa-table-test .admin-mobile-table');
        assert.equal(await page.locator('#pwa-table-test td').first().getAttribute('data-cell-label'), 'Item');
        assert.equal(await page.locator('#pwa-table-test button').count(), 1);
        checks++;

        await page.evaluate(() => navigator.serviceWorker.ready);
        const scope = await page.evaluate(async () => (await navigator.serviceWorker.getRegistration()).scope);
        assert.equal(new URL(scope).pathname, '/admin/');
        await context.setOffline(true);
        await page.waitForSelector('[data-admin-connection]:not([hidden])');
        const offline = await page.goto(`${base}/admin/orders`, { waitUntil: 'domcontentloaded' });
        assert.equal(offline.status(), 503);
        assert.match(await page.locator('body').innerText(), /Connection unavailable/);
        assert.ok(!await page.locator('input[name=_token]').count());
        await context.setOffline(false);
        await visit('orders'); // The existing session survives offline navigation.
        const cachedUrls = await page.evaluate(async () => {
            const keys = (await caches.keys()).filter((key) => key.startsWith('restaurant-admin-static-'));
            return (await Promise.all(keys.map(async (key) => (await (await caches.open(key)).keys()).map((request) => request.url)))).flat();
        });
        assert.ok(cachedUrls.every((url) => !new URL(url).pathname.startsWith('/admin')));
        checks++;

        await page.locator('.mobile-admin-profile-button').click();
        await page.evaluate(() => {
            const event = new Event('beforeinstallprompt', { cancelable: true });
            event.prompt = async () => { window.installPromptCalls = (window.installPromptCalls || 0) + 1; };
            window.dispatchEvent(event);
        });
        await page.locator('[data-pwa-install-button]').click();
        assert.equal(await page.evaluate(() => window.installPromptCalls), 1);
        checks++;

        await page.goto(base, { waitUntil: 'networkidle' });
        assert.equal(await page.locator('link[rel=manifest], script[src*="pwa.js"], link[href*="pwa-mobile.css"]').count(), 0);
        assert.equal(await page.evaluate(() => navigator.serviceWorker.controller), null);
        checks++;

        // Compare desktop pixels with the original mobile CSS if a baseline is supplied.
        if (process.env.PWA_BASELINE_CSS) {
            const desktop = await browser.newContext({ viewport: { width: 1440, height: 1000 } });
            await desktop.addCookies(await context.cookies());
            const desktopPage = await desktop.newPage();
            await desktopPage.goto(`${base}/admin/dashboard`, { waitUntil: 'networkidle' });
            const current = await desktopPage.screenshot({ animations: 'disabled' });
            const css = await fs.readFile(process.env.PWA_BASELINE_CSS, 'utf8');
            await desktopPage.route('**/pwa-mobile.css*', (route) => route.fulfill({ contentType: 'text/css', body: css }));
            await desktopPage.reload({ waitUntil: 'networkidle' });
            const original = await desktopPage.screenshot({ animations: 'disabled' });
            assert.ok(current.equals(original), 'Desktop dashboard pixels changed');
            checks++;
            await desktop.close();
        }

        assert.deepEqual(errors, [], 'Browser JavaScript errors');
        console.log(`Passed ${checks} mobile/PWA browser checks with no JavaScript errors.`);
        await context.close();
    } finally {
        await browser.close();
    }
})().catch((error) => { console.error(error); process.exitCode = 1; });

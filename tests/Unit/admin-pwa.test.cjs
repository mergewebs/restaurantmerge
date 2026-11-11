const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

const source = fs.readFileSync('public/sw.js', 'utf8');
const origin = 'https://restaurant.test';
const icon = '/pwa/icons/icon-192.png';

function runtime(options = {}) {
    const listeners = {};
    const entries = new Map();
    const deleted = [];
    let writes = 0;
    let claims = 0;
    const cache = {
        match: async (request) => entries.get(request.url)?.clone(),
        put: async (request, response) => {
            if (options.failStorage) throw new Error('Quota exceeded');
            writes++;
            entries.set(request.url, response);
        },
    };
    const context = {
        URL, Response,
        self: {
            location: { origin },
            addEventListener: (name, callback) => { listeners[name] = callback; },
            skipWaiting: async () => {},
            clients: { claim: async () => { claims++; } },
        },
        caches: {
            keys: async () => options.keys || [],
            delete: async (key) => { deleted.push(key); },
            open: async () => {
                if (options.failOpen) throw new Error('Storage unavailable');
                return cache;
            },
        },
        fetch: (...args) => options.fetch(...args),
    };
    vm.runInNewContext(source, context);
    return {
        entries, deleted,
        get writes() { return writes; },
        get claims() { return claims; },
        async dispatch(path, overrides = {}) {
            let result;
            const pending = [];
            listeners.fetch({
                request: {
                    url: path.startsWith('https:') ? path : origin + path,
                    method: 'GET', mode: 'cors', destination: 'image', cache: 'default',
                    headers: new Headers(), ...overrides,
                },
                respondWith: (promise) => { result = promise; },
                waitUntil: (promise) => { pending.push(promise); },
            });
            const response = await result;
            await Promise.all(pending);
            return response;
        },
        async activate() {
            await listeners.activate({ waitUntil: (promise) => { this.activation = promise; } });
            await this.activation;
        },
    };
}

function asset(body = 'icon', headers = {}, extra = {}) {
    const response = new Response(body, { headers: { 'Content-Type': 'image/png', ...headers } });
    Object.defineProperties(response, {
        type: { value: 'basic' },
        redirected: { value: false },
        ...Object.fromEntries(Object.entries(extra).map(([key, value]) => [key, { value }])),
    });
    return response;
}

test('admin HTML always requests fresh data and preserves authentication request', async () => {
    let received;
    const worker = runtime({ fetch: async (request, options) => { received = { request, options }; return new Response('Current order'); } });
    const response = await worker.dispatch('/admin/orders/1', {
        mode: 'navigate', destination: 'document', headers: new Headers({ Accept: 'text/html' }),
    });
    assert.equal(await response.text(), 'Current order');
    assert.equal(received.options.cache, 'no-store');
    assert.equal(received.request.url, origin + '/admin/orders/1');
    assert.equal(worker.writes, 0);
});

test('offline HTML contains no cached private data and does not pretend a save succeeded', async () => {
    const worker = runtime({ fetch: async () => { throw new Error('Offline'); } });
    worker.entries.set(origin + '/admin/orders/1', new Response('PRIVATE ORDER'));
    const response = await worker.dispatch('/admin/orders/1', {
        mode: 'navigate', headers: new Headers({ Accept: 'text/html' }),
    });
    assert.equal(response.status, 503);
    assert.equal(response.headers.get('Cache-Control'), 'no-store');
    const html = await response.text();
    assert.match(html, /Connection unavailable/);
    assert.match(html, /check the current record before trying again/);
    assert.doesNotMatch(html, /PRIVATE ORDER/);
    assert.equal(worker.writes, 0);
});

for (const [name, path, overrides] of [
    ['POST payment', '/admin/orders/1/payments', { method: 'POST' }],
    ['DELETE item', '/admin/orders/1/items/2', { method: 'DELETE' }],
    ['login', '/admin/login', {}],
    ['live kitchen JSON', '/admin/kitchen/snapshot', { destination: '' }],
    ['live kitchen stream', '/admin/kitchen/stream', { destination: '' }],
    ['public navigation', '/', { mode: 'navigate', headers: new Headers({ Accept: 'text/html' }) }],
    ['public API', '/api/orders', {}],
    ['product images', '/products/thumbnails/1/dish.webp', {}],
    ['mutable runtime', '/pwa.js?v=old', { destination: 'script' }],
    ['third-party script', 'https://cdn.example.com/library.js', { destination: 'script' }],
    ['authorized static request', icon, { headers: new Headers({ Authorization: 'Bearer private' }) }],
    ['range request', icon, { headers: new Headers({ Range: 'bytes=0-10' }) }],
    ['no-store request', icon, { cache: 'no-store' }],
    ['AJAX for static-looking URL', icon, { destination: '' }],
]) {
    test(`${name} bypasses worker handling and cache`, async () => {
        const worker = runtime({ fetch: () => { throw new Error('Must not intercept'); } });
        assert.equal(await worker.dispatch(path, overrides), undefined);
        assert.equal(worker.writes, 0);
    });
}

test('only safe assets have a network-first fallback, keyed by the full URL', async () => {
    let offline = false;
    const worker = runtime({ fetch: async () => { if (offline) throw new Error('Offline'); return asset(); } });
    assert.equal(await (await worker.dispatch(icon)).text(), 'icon');
    assert.equal(worker.writes, 1);
    offline = true;
    assert.equal(await (await worker.dispatch(icon)).text(), 'icon');
    await assert.rejects(worker.dispatch(icon + '?v=new'), /Offline/);
});

for (const cacheControl of ['private', 'no-store', 'no-cache']) {
    test(`${cacheControl} responses are never written to CacheStorage`, async () => {
        const worker = runtime({ fetch: async () => asset('private', { 'Cache-Control': cacheControl }) });
        assert.equal(await (await worker.dispatch(icon)).text(), 'private');
        assert.equal(worker.writes, 0);
    });
}

for (const [name, response] of [
    ['redirected login document', () => asset('login', {}, { redirected: true })],
    ['HTML at static URL', () => asset('login', { 'Content-Type': 'text/html' })],
    ['server error', () => new Response('error', { status: 500 })],
]) {
    test(`${name} is returned unchanged without caching`, async () => {
        const worker = runtime({ fetch: async () => response() });
        assert.ok(await worker.dispatch(icon));
        assert.equal(worker.writes, 0);
    });
}

for (const failure of ['failStorage', 'failOpen']) {
    test(`${failure} never fails a successful network request`, async () => {
        const worker = runtime({ [failure]: true, fetch: async () => asset('fresh') });
        assert.equal(await (await worker.dispatch(icon)).text(), 'fresh');
    });
}

test('activation removes only obsolete admin caches and claims without page reloads', async () => {
    const worker = runtime({ keys: ['restaurant-admin-static-old', 'restaurant-admin-static-20261111-1', 'another-app'] });
    await worker.activate();
    assert.deepEqual(worker.deleted, ['restaurant-admin-static-old']);
    assert.equal(worker.claims, 1);
});

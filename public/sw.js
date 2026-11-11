'use strict';

const CACHE_PREFIX = 'restaurant-admin-static-';
const STATIC_CACHE = `${CACHE_PREFIX}20261111-1`;
const ADMIN_PATH = /^\/admin(?:\/|$)/;
// Only application code with content hashes and the bundled, non-sensitive icons.
// Never cache HTML, JSON, product images, third-party resources or mutable scripts.
const SAFE_STATIC_PATH = /^(?:\/build\/assets\/[\w-]+-[\w-]{8,}\.(?:css|js)|\/pwa\/icons\/(?:icon-192|icon-512|icon-maskable-512|apple-touch-icon)\.png)$/;

self.addEventListener('install', (event) => {
    event.waitUntil(self.skipWaiting());
});

self.addEventListener('activate', (event) => {
    event.waitUntil((async () => {
        try {
            const keys = await caches.keys();
            await Promise.all(keys.filter((key) => key.startsWith(CACHE_PREFIX) && key !== STATIC_CACHE)
                .map((key) => caches.delete(key)));
        } catch (_) {
            // Storage can be unavailable in private browsing or under quota pressure.
        }
        await self.clients.claim();
    })());
});

function offlineResponse() {
    // Generated in memory: no authenticated document or submitted data is stored.
    return new Response(`<!doctype html><html lang="en"><head><meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
        <meta name="theme-color" content="#271c16"><title>Connection unavailable | Merge</title>
        <style>body{margin:0;padding: max(24px,env(safe-area-inset-top)) 24px;background:#f5efe4;color:#271c16;font:16px/1.5 system-ui}
        main{max-width:28rem;margin:15vh auto}h1{font-size:1.5rem}a{display:inline-flex;min-height:48px;align-items:center;padding:0 24px;background:#b84f32;color:white;border-radius:12px;text-decoration:none}</style>
        </head><body><main><h1>Connection unavailable</h1><p>Connect to the internet to view current restaurant information.</p>
        <p>No actions are saved or sent from this offline screen. If a connection failed while saving, check the current record before trying again.</p>
        <a href="">Try again</a></main></body></html>`, {
        status: 503,
        headers: { 'Content-Type': 'text/html; charset=utf-8', 'Cache-Control': 'no-store' },
    });
}

self.addEventListener('fetch', (event) => {
    const request = event.request;
    const url = new URL(request.url);
    if (url.origin !== self.location.origin || request.method !== 'GET') return;

    if (request.mode === 'navigate' && ADMIN_PATH.test(url.pathname)
        && request.headers.get('Accept')?.includes('text/html')) {
        event.respondWith(fetch(request, { cache: 'no-store' }).catch(() => offlineResponse()));
        return;
    }

    // Forms, APIs, polling, SSE, uploads and authentication use the normal network.
    if (ADMIN_PATH.test(url.pathname) || !SAFE_STATIC_PATH.test(url.pathname)
        || request.headers.has('Authorization') || request.headers.has('Range')
        || request.cache === 'no-store'
        || !['image', 'script', 'style'].includes(request.destination)) return;

    event.respondWith((async () => {
        let response;
        try {
            response = await fetch(request);
        } catch (error) {
            try {
                const cached = await (await caches.open(STATIC_CACHE)).match(request);
                if (cached) return cached;
            } catch (_) { /* Cache failure must not change network semantics. */ }
            throw error;
        }

        const cacheControl = response.headers.get('Cache-Control') || '';
        const contentType = response.headers.get('Content-Type') || '';
        if (response.status === 200 && response.type === 'basic' && !response.redirected
            && !/no-store|no-cache|private/i.test(cacheControl)
            && /^(?:image\/png|text\/css|(?:application|text)\/javascript)\b/i.test(contentType)) {
            const copy = response.clone();
            // A full/denied CacheStorage must never fail a successful network request.
            event.waitUntil((async () => {
                try {
                    const cache = await caches.open(STATIC_CACHE);
                    await cache.put(request, copy);
                } catch (_) { /* Optional optimization only. */ }
            })());
        }
        return response;
    })());
});

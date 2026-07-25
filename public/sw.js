'use strict';

// Bump this value with every deployment that changes static assets.
const CACHE_VERSION = 'restaurant-admin-static-2026-07-25-v2';
const CACHE_PREFIX = 'restaurant-admin-static-';
const STATIC_CACHE = CACHE_VERSION;

const PRIVATE_PATH = /^\/(?:admin(?:\/|$)|login(?:\/|$)|logout(?:\/|$)|api(?:\/|$)|sanctum(?:\/|$))/i;
const SAFE_STATIC_PATH = /^(?:\/build\/assets\/.*\.(?:css|js)|\/fonts\/.*\.(?:woff2?|ttf|otf)|\/images\/.*\.(?:png|jpe?g|webp|gif|svg)|\/products\/(?:optimized|thumbnails)\/.*\.(?:png|jpe?g|webp|gif)|\/(?:restaurant-mark\.svg|manifest\.webmanifest|pwa-mobile\.css))$/i;

self.addEventListener('install', () => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(
                keys
                    .filter((key) => key.startsWith(CACHE_PREFIX) && key !== STATIC_CACHE)
                    .map((key) => caches.delete(key))
            ))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const request = event.request;

    // Navigations, redirects, forms, uploads, AJAX, APIs and authenticated requests
    // stay entirely on the network and retain normal Laravel/session semantics.
    if (request.method !== 'GET' || request.mode === 'navigate') {
        return;
    }

    const url = new URL(request.url);
    if (url.origin !== self.location.origin || PRIVATE_PATH.test(url.pathname) || !SAFE_STATIC_PATH.test(url.pathname)) {
        return;
    }

    event.respondWith(
        caches.open(STATIC_CACHE).then(async (cache) => {
            try {
                const response = await fetch(request);
                const cacheControl = response.headers.get('Cache-Control') || '';

                if (response.ok && response.type === 'basic' && !/no-store|private/i.test(cacheControl)) {
                    await cache.put(request, response.clone());
                }

                return response;
            } catch (error) {
                const cached = await cache.match(request);
                if (cached) {
                    return cached;
                }

                throw error;
            }
        })
    );
});

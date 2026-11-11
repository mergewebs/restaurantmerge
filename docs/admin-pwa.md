# Admin mobile app

Only `/admin` and `/admin/*` load the PWA manifest, mobile stylesheet and runtime.
The public site keeps its favicon and does not load installation metadata or register
a worker. The worker file remains `/sw.js`, with registration scope `/admin/`.
Routes, database schema, permissions, Laravel sessions and mutation handlers are unchanged.

## Installation and deployment

Serve the application over HTTPS (localhost also supports development). Deploy the
updated views, middleware, `public/sw.js`, `public/pwa.js`, `public/pwa-mobile.css`,
manifest and `public/pwa/icons` together. No new production dependencies or migrations
are required. Clear compiled Blade views through the usual deployment process.

On Android, open an admin page and use the browser's install action or **Install admin
app** in the account menu when the browser offers installation. On iPhone, open an
admin page in Safari, use **Share → Add to Home Screen**, and open the installed app.
If the installed app needs authentication, use the existing passcode login. There is
no alternative token, credential storage, or authentication mechanism.

The PNG icons are rasterizations of `public/restaurant-mark.svg`. To regenerate them,
run `node tests/Browser/generate-admin-icons.cjs` using an existing Playwright/Chrome
installation. `PLAYWRIGHT_MODULE` can point to the installed package; the application
does not depend on Playwright.

## Freshness and privacy

- Admin HTML, login, redirects, JSON, exports, and kitchen streams use
  `Cache-Control: private, no-store, max-age=0`.
- The worker never stores operational pages, API responses, credentials, form
  submissions, uploads, or product images. Mutations, polling and SSE retain their
  normal network behavior. There is no background queue or automatic replay.
- Only the bundled PNG icons and content-hashed files in `/build/assets` are eligible
  for optional network-first caching. Redirects, HTML, private/no-store/no-cache
  responses, authorized requests, and range requests are excluded. Cache failures
  never prevent a successful network response.
- An offline navigation generates a generic 503 screen in memory, without restaurant
  data or a CSRF token. An already-open page shows an offline notice. Live operations
  require a connection; after an interrupted save, check the record before retrying.
- Worker activation removes only obsolete `restaurant-admin-static-*` caches and
  does not reload an open form. It leaves other applications' storage alone.

When changing mutable PWA CSS/JS or the manifest, bump their query versions in
`resources/views/partials/favicon.blade.php`. Bump `STATIC_CACHE` in `public/sw.js`
when replacing icon assets. Content-hashed build assets retain distinct URLs.

## Verification

Run `php artisan test --compact` and `node --test tests/Unit/*.test.cjs`.
The worker tests execute requests, offline responses, exclusions and storage errors;
the Laravel tests cover public isolation, admin routes, icons and cache headers.

For browser checks, create isolated local fixtures. These PowerShell commands use a
separate SQLite database and session cookie; they do not edit `.env`:

```powershell
$env:PWA_TEST_PASSCODE = '765432'
php tests/Browser/setup-admin-pwa.php
$env:APP_ENV = 'testing'
$env:DB_CONNECTION = 'sqlite'
$env:DB_DATABASE = Join-Path (Get-Location) 'storage/framework/testing/admin-pwa/admin-pwa.sqlite'
$env:CACHE_STORE = 'array'
$env:SESSION_DRIVER = 'file'
$env:SESSION_COOKIE = 'admin_pwa_browser_test'
$env:MAIL_MAILER = 'array'
$env:APP_URL = 'http://127.0.0.1:8765'
php artisan serve --host=127.0.0.1 --port=8765 --no-reload
```

In a second shell, set `PWA_TEST_PASSCODE` to the same value, optionally set
`PLAYWRIGHT_MODULE`, and run `node tests/Browser/admin-pwa.cjs`. The fixture creator
refuses to overwrite an existing fixture database. Browser checks cover 320/390px
layouts, modal actions, table labels, install prompting, offline recovery and public
isolation. Set `PWA_BASELINE_CSS` to a file containing the previous PWA stylesheet to
also compare desktop dashboard screenshots. Physical iPhone/Android installation,
keyboard and safe-area behavior should also be checked on the deployed HTTPS host.

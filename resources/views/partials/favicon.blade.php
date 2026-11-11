<link rel="icon" type="image/svg+xml" href="{{ asset('restaurant-mark.svg') }}">
@if(request()->is('admin', 'admin/*'))
<link rel="manifest" href="/manifest.webmanifest?v=20261111-1">
<link rel="apple-touch-icon" sizes="180x180" href="/pwa/icons/apple-touch-icon.png">
<link rel="stylesheet" href="/pwa-mobile.css?v=20261111-1">
<meta name="theme-color" content="#271c16">
<meta name="application-name" content="{{ config('app.name') }}">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
<meta name="format-detection" content="telephone=no">
<script src="/pwa.js?v=20261111-1" defer></script>
@endif

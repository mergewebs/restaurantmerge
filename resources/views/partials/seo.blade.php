@php
    $seoData = \App\Support\Seo::make($seo ?? []);
@endphp
<title>{{ $seoData['title'] }}</title>
<meta name="description" content="{{ $seoData['description'] }}">
@if(!empty($seoData['keywords']))
<meta name="keywords" content="{{ $seoData['keywords'] }}">
@endif
<link rel="canonical" href="{{ $seoData['canonical'] }}">
<meta name="robots" content="{{ $seoData['robots'] }}">
<meta property="og:locale" content="{{ $seoData['locale'] }}">
<meta property="og:type" content="{{ $seoData['type'] }}">
<meta property="og:title" content="{{ $seoData['title'] }}">
<meta property="og:description" content="{{ $seoData['description'] }}">
<meta property="og:url" content="{{ $seoData['canonical'] }}">
<meta property="og:site_name" content="{{ $seoData['site_name'] }}">
<meta property="og:image" content="{{ $seoData['image'] }}">
<meta name="twitter:card" content="{{ $seoData['twitter_card'] }}">
<meta name="twitter:title" content="{{ $seoData['title'] }}">
<meta name="twitter:description" content="{{ $seoData['description'] }}">
<meta name="twitter:image" content="{{ $seoData['image'] }}">
@foreach($seoData['structured_data'] as $schema)
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
@endforeach

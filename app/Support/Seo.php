<?php

namespace App\Support;

use Illuminate\Support\Arr;

class Seo
{
    public static function make(array $overrides = []): array
    {
        $seo = array_replace([
            'title' => config('app.name'),
            'description' => config('app.name').' administration dashboard.',
            'keywords' => '',
            'canonical' => url()->current(),
            'robots' => 'noindex, nofollow, noarchive, nosnippet, max-image-preview:none',
            'image' => asset('images/koya-logo-512.png'),
            'type' => 'website',
            'site_name' => config('app.name'),
            'locale' => str_replace('_', '-', app()->getLocale()),
            'twitter_card' => 'summary',
            'structured_data' => [],
        ], array_filter($overrides, static fn ($value) => $value !== null));

        $seo['structured_data'] = array_values(array_filter(Arr::wrap($seo['structured_data'] ?? [])));

        return $seo;
    }

    public static function noIndex(array $overrides = []): array
    {
        return static::make(array_merge([
            'robots' => 'noindex, nofollow, noarchive, nosnippet, max-image-preview:none',
        ], $overrides));
    }
}

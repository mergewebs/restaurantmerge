<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Seo
{
    public static function make(array $overrides = []): array
    {
        $seo = array_replace([
            'title' => __('public.seo_default_title'),
            'description' => __('public.seo_default_description'),
            'keywords' => __('public.seo_default_keywords'),
            'canonical' => url()->current(),
            'robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            'image' => asset('images/gallery-1.jpg'),
            'type' => 'website',
            'site_name' => __('public.seo_structured_site_name'),
            'locale' => str_replace('_', '-', app()->getLocale()),
            'twitter_card' => 'summary_large_image',
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

    public static function websiteStructuredData(array $overrides = []): array
    {
        return array_replace_recursive([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => __('public.seo_structured_site_name'),
            'url' => url('/'),
            'inLanguage' => str_replace('_', '-', app()->getLocale()),
        ], $overrides);
    }

    public static function restaurantStructuredData(iterable $schedule = [], array $overrides = []): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            'name' => __('public.seo_structured_site_name'),
            'description' => __('public.seo_default_description'),
            'url' => url('/'),
            'image' => asset('images/gallery-1.jpg'),
            'telephone' => __('public.contact_phone'),
            'email' => __('public.contact_email'),
            'priceRange' => __('public.seo_structured_price_range'),
            'servesCuisine' => __('public.seo_structured_serves_cuisine'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => __('public.contact_address'),
                'addressLocality' => "L'Hospitalet de Llobregat",
                'addressRegion' => 'Barcelona',
                'addressCountry' => 'ES',
            ],
            'sameAs' => __('public.seo_structured_same_as'),
        ];

        $openingHours = static::openingHoursSpecification($schedule);
        if ($openingHours !== []) {
            $data['openingHoursSpecification'] = $openingHours;
        }

        return array_replace_recursive($data, $overrides);
    }

    private static function openingHoursSpecification(iterable $schedule): array
    {
        return Collection::make($schedule)
            ->flatMap(function ($entry) {
                if (! $entry || ($entry->is_closed ?? false)) {
                    return [];
                }

                $specifications = [];

                foreach ([1, 2, 3] as $session) {
                    $openField = "open_time_{$session}";
                    $closeField = "close_time_{$session}";

                    if (empty($entry->{$openField}) || empty($entry->{$closeField})) {
                        continue;
                    }

                    $specifications[] = [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => static::schemaDay((int) ($entry->day_of_week ?? 0)),
                        'opens' => substr((string) $entry->{$openField}, 0, 5),
                        'closes' => substr((string) $entry->{$closeField}, 0, 5),
                    ];
                }

                return $specifications;
            })
            ->values()
            ->all();
    }

    private static function schemaDay(int $day): string
    {
        return match ($day) {
            0 => 'https://schema.org/Sunday',
            1 => 'https://schema.org/Monday',
            2 => 'https://schema.org/Tuesday',
            3 => 'https://schema.org/Wednesday',
            4 => 'https://schema.org/Thursday',
            5 => 'https://schema.org/Friday',
            default => 'https://schema.org/Saturday',
        };
    }
}

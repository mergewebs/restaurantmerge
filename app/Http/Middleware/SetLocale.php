<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin') || $request->is('admin/*')) {
            app()->setLocale('en');

            return $next($request);
        }

        $availableLocales = array_keys(config('app.available_locales', ['es' => 'Español', 'en' => 'English']));
        $defaultLocale = config('app.locale', 'en');
        $locale = $request->session()->get('locale');

        if (! is_string($locale) || ! in_array($locale, $availableLocales, true)) {
            $locale = $request->getPreferredLanguage($availableLocales) ?: $defaultLocale;
        }

        if (! in_array($locale, $availableLocales, true)) {
            $locale = $defaultLocale;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}

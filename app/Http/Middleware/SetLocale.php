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
        $defaultLocale = config('app.locale', 'es');
        $locale = $request->session()->get('locale', $defaultLocale);

        if (! in_array($locale, $availableLocales, true)) {
            $locale = $defaultLocale;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}

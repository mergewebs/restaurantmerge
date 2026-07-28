<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureEnabled
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if ($request->user()?->isDemo()) {
            return $next($request);
        }

        abort_unless(config("features.{$feature}", false), 404);

        return $next($request);
    }
}

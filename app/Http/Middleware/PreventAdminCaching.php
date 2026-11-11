<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventAdminCaching
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Include login, redirects, downloads, JSON and live kitchen responses.
        // Public pages and static assets keep their existing caching behavior.
        if ($request->is('admin', 'admin/*')) {
            $response->headers->set('Cache-Control', 'private, no-store, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}

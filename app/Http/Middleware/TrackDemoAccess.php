<?php

namespace App\Http\Middleware;

use App\Models\DemoIpAddress;
use App\Services\DemoAccessTracker;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackDemoAccess
{
    public function __construct(private readonly DemoAccessTracker $tracker) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isDemo()) {
            return $next($request);
        }

        if (DemoIpAddress::where('ip_address', $request->ip())->where('is_blocked', true)->exists()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = 'This IP address has been blocked from accessing the demo. Please contact an administrator.';

            return $request->expectsJson()
                ? response()->json(['message' => $message], 403)
                : redirect()->route('login')->withErrors(['passcode' => $message]);
        }

        // Starting a session is idempotent, including for an AJAX-first client.
        $this->tracker->ensureSession($request);

        if ($request->isMethod('GET') && $request->route() && ! $request->ajax() && ! $request->expectsJson()) {
            $this->tracker->recordPage($request);
        }

        return $next($request);
    }
}

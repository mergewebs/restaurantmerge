<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDemoWrites
{
    public function handle(Request $request, Closure $next): Response
    {
        $isReadRequest = in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true);
        $isLogout = $request->routeIs('logout');
        $isContactSubmission = $request->routeIs('contact-submissions.store');

        if (! $request->user()?->isDemo() || $isReadRequest || $isLogout || $isContactSubmission) {
            return $next($request);
        }

        $message = 'This action is disabled in demo mode. Contact us to unlock the complete restaurant workspace.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'demo_mode' => true,
            ], 423);
        }

        return back()->with('demo_action_blocked', $message);
    }
}

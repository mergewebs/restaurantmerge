<?php

namespace App\Http\Middleware;

use App\Services\DemoDataManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDemoData
{
    public function __construct(private readonly DemoDataManager $demoData) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethodSafe() && config('demo.data_enabled') && $request->user()?->isDemo()) {
            $this->demoData->refreshIfNeeded();
        }

        return $next($request);
    }
}

<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\PreventAdminCaching::class);

        $middleware->alias([
            'feature' => \App\Http\Middleware\EnsureFeatureEnabled::class,
            'noindex' => \App\Http\Middleware\PreventSearchIndexing::class,
            'demo.readonly' => \App\Http\Middleware\PreventDemoWrites::class,
            'demo.data' => \App\Http\Middleware\EnsureDemoData::class,
            'demo.track' => \App\Http\Middleware\TrackDemoAccess::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\PreventDemoWrites::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

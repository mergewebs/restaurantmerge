<?php

use App\Models\MenuItem;
use App\Services\ProductImageService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('products:optimize-images {--force : Regenerate optimized images even if variants already exist}', function () {
    $service = app(ProductImageService::class);
    $optimized = 0;
    $skipped = 0;
    $missing = 0;

    MenuItem::query()
        ->orderBy('id')
        ->each(function (MenuItem $menuItem) use ($service, &$optimized, &$skipped, &$missing) {
            $status = $service->optimizeMenuItem($menuItem, (bool) $this->option('force'));

            match ($status) {
                'optimized' => $optimized++,
                'skipped' => $skipped++,
                default => $missing++,
            };

            $this->line(sprintf(
                '[%s] #%d %s',
                strtoupper($status),
                $menuItem->id,
                $menuItem->name,
            ));
        });

    $this->newLine();
    $this->info("Optimized: {$optimized}");
    $this->line("Skipped: {$skipped}");
    $this->line("Missing source: {$missing}");
})->purpose('Optimize existing product images and generate thumbnail/WebP variants');

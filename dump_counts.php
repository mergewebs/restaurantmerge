<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Schedules: " . \App\Models\RestaurantSchedule::count() . "\n";
echo "Exceptions: " . \App\Models\RestaurantException::count() . "\n";
echo "Reservations: " . \App\Models\Reservation::count() . "\n";

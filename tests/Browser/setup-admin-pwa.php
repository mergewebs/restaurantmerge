<?php

// Creates a NEW, isolated SQLite fixture. Never uses the configured restaurant DB.
if (PHP_SAPI !== 'cli') {
    exit(1);
}

$passcode = getenv('PWA_TEST_PASSCODE');
if (! is_string($passcode) || ! preg_match('/^\d{6}$/', $passcode)) {
    throw new RuntimeException('Set a six-digit PWA_TEST_PASSCODE for the local fixture owner.');
}

$root = dirname(__DIR__, 2);
$directory = $root.'/storage/framework/testing/admin-pwa';
$database = $directory.'/admin-pwa.sqlite';
if (file_exists($database)) {
    throw new RuntimeException('The isolated admin-pwa.sqlite fixture already exists. Use it or choose a fresh test workspace.');
}

require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

if (! is_dir($directory)) {
    mkdir($directory, 0755, true);
}
touch($database);
config([
    'database.default' => 'sqlite',
    'database.connections.sqlite.database' => $database,
    'cache.default' => 'array',
    'mail.default' => 'array',
]);
Illuminate\Support\Facades\DB::purge();
Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);

$user = App\Models\User::factory()->create(['name' => 'Mobile Test Owner', 'role' => 'admin', 'passcode' => $passcode]);
(new Database\Seeders\RestaurantScheduleSeeder)->run();
$table = App\Models\RestaurantTable::create(['number' => 1, 'seats' => 4, 'area' => 'Main Room']);
$menu = App\Models\MenuItem::create(['name' => 'Mobile test dish', 'category' => 'Starters', 'price' => 12, 'is_active' => true, 'stock_quantity' => 20]);
$order = App\Models\Order::create([
    'restaurant_table_id' => $table->id, 'order_type' => 'dine_in', 'status' => 'open',
    'workflow_status' => 'confirmed', 'kitchen_status' => 'pending', 'payment_status' => 'unpaid', 'total' => 12,
]);
App\Models\OrderItem::create([
    'order_id' => $order->id, 'menu_item_id' => $menu->id, 'name' => $menu->name,
    'quantity' => 1, 'unit_price' => 12, 'line_total' => 12, 'status' => 'pending',
]);
App\Models\Reservation::create([
    'customer_name' => 'Mobile Test Guest', 'customer_phone' => '600123456', 'party_size' => 4,
    'reservation_time' => now()->setTime(20, 0), 'user_id' => $user->id, 'status' => 'confirmed',
]);

echo "Isolated admin PWA fixtures created.\n";

<?php

namespace Tests\Feature;

use App\Models\Income;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\StaffAttendance;
use App\Models\User;
use Database\Seeders\ClientDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientDemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_demo_seeds_the_entire_application_without_changing_users(): void
    {
        User::factory()->create(['role' => 'owner', 'name' => 'Existing Client Owner']);
        $before = User::query()->count();
        $this->seed(ClientDemoSeeder::class);

        $this->assertSame($before, User::query()->count());
        $this->assertDatabaseHas('users', ['name' => 'Existing Client Owner']);
        foreach ([
            'menu_items' => 8, 'restaurant_tables' => 8, 'reservations' => 8, 'orders' => 9, 'order_items' => 9,
            'order_payments' => 7, 'suppliers' => 5, 'inventory_ingredients' => 8, 'stock_movements' => 8,
            'cash_register_sessions' => 5, 'incomes' => 8, 'expenses' => 8, 'cashouts' => 5,
            'staff_schedules' => 7, 'staff_attendances' => 5, 'restaurant_exceptions' => 5,
        ] as $table => $minimum) {
            $this->assertGreaterThanOrEqual($minimum, \DB::table($table)->count(), $table.' should contain demo records.');
        }
    }

    public function test_client_demo_is_repeatable_and_keeps_relative_dates_current(): void
    {
        User::factory()->create(['role' => 'owner']);
        $seeder = (new ClientDemoSeeder)->setContainer($this->app);

        $seeder->run();
        $counts = collect(['reservations', 'orders', 'order_items', 'order_payments', 'cash_register_sessions', 'staff_schedules', 'staff_attendances'])
            ->mapWithKeys(fn ($table) => [$table => \DB::table($table)->count()]);

        $seeder->run();

        foreach ($counts as $table => $count) {
            $this->assertSame($count, \DB::table($table)->count(), $table.' should not gain duplicates.');
        }
        $this->assertTrue(Reservation::where('customer_email', 'client.demo1@example.com')->whereDate('reservation_time', today())->exists());
        $this->assertTrue(Reservation::where('customer_email', 'client.demo8@example.com')->whereDate('reservation_time', today()->addDays(3))->exists());
        $this->assertTrue(Order::where('notes', 'Client demo order 1')->whereDate('completed_at', today())->exists());
        $this->assertTrue(Income::where('description', 'Client demo income 5')->whereDate('date', today())->exists());
        $this->assertTrue(StaffAttendance::where('notes', 'Client demo attendance today')->whereDate('clocked_in_at', today())->exists());
    }
}

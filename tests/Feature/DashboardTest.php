<?php

namespace Tests\Feature;

use App\Models\Income;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reservation;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_todays_operational_overview(): void
    {
        $user = User::factory()->create(['role' => 'owner']);
        $occupied = RestaurantTable::create(['number' => 1]);
        RestaurantTable::create(['number' => 2]);

        $order = Order::create([
            'restaurant_table_id' => $occupied->id,
            'status' => Order::STATUS_OPEN,
            'kitchen_status' => Order::KITCHEN_STATUS_IN_PREPARATION,
            'payment_status' => Order::PAYMENT_STATUS_UNPAID,
            'total' => 24,
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'name' => 'House Burger',
            'quantity' => 2,
            'unit_price' => 12,
            'line_total' => 24,
            'status' => OrderItem::STATUS_IN_PREPARATION,
        ]);
        MenuItem::create([
            'name' => 'House Burger',
            'category' => 'Smash Burger',
            'price' => 12,
            'stock_quantity' => 2,
            'low_stock_threshold' => 5,
            'is_active' => true,
        ]);
        Reservation::create([
            'customer_name' => 'Taylor Guest',
            'party_size' => 4,
            'reservation_time' => now()->addHour(),
            'status' => 'confirmed',
        ]);
        Income::create([
            'amount' => 75,
            'type' => 'sale',
            'method' => 'cash',
            'date' => now()->toDateString(),
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Today')
            ->assertSee('Today’s Sales')
            ->assertSee('Kitchen Orders')
            ->assertSee('Low Stock')
            ->assertSee('Best Sellers')
            ->assertSee('House Burger')
            ->assertSee('Latest Updates');
    }
}

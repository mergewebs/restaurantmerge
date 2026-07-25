<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_and_manage_a_delivery_order(): void
    {
        $user = User::factory()->create(['role' => 'owner']);
        $menuItem = MenuItem::create([
            'name' => 'Grilled Sea Bass',
            'category' => 'Starters',
            'price' => 18,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(route('orders.store'), [
            'order_type' => 'delivery',
            'customer_name' => 'Alex Guest',
            'customer_phone' => '600 123 456',
            'notes' => 'Ring the bell once',
        ]);

        $order = Order::firstOrFail();
        $response->assertRedirect(route('orders.show', $order));
        $this->assertNull($order->restaurant_table_id);

        $this->actingAs($user)->post(route('orders.items.store', $order), [
            'menu_item_id' => $menuItem->id,
            'quantity' => 2,
        ])->assertRedirect();

        $this->actingAs($user)->put(route('orders.adjustments.update', $order), [
            'discount_amount' => 4,
            'service_charge' => 2,
        ])->assertRedirect();

        $order->refresh();
        $this->assertSame(34.0, $order->total);

        $this->actingAs($user)->patch(route('orders.status.update', $order), [
            'workflow_status' => 'preparing',
        ])->assertRedirect();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'workflow_status' => 'preparing', 'kitchen_status' => 'in_preparation']);

        $this->actingAs($user)->post(route('orders.payments.store', $order), [
            'payments' => [
                ['method' => 'cash', 'amount' => 14],
                ['method' => 'card', 'amount' => 20, 'reference' => 'CARD-1'],
            ],
        ])->assertRedirect();

        $this->assertDatabaseCount('order_payments', 2);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'payment_status' => 'paid']);
    }

    public function test_orders_screen_and_kitchen_ticket_are_available(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_type' => 'pickup',
            'customer_name' => 'Jamie Pickup',
            'status' => 'open',
            'workflow_status' => 'confirmed',
            'kitchen_status' => 'pending',
            'payment_status' => 'unpaid',
            'total' => 0,
        ]);

        $this->actingAs($user)->get(route('orders.index'))
            ->assertOk()->assertSee('Live order queue')->assertSee('Jamie Pickup');

        $this->actingAs($user)->get(route('orders.ticket', $order))
            ->assertOk()->assertSee('KITCHEN #'.$order->id);

        $this->actingAs($user)->get(route('orders.ticket.download', $order))
            ->assertOk()->assertDownload('order-'.$order->id.'-kitchen-ticket.txt');
    }
}

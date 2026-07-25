<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TableManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('features.tables', true);
        config()->set('features.kitchen', true);
    }

    public function test_authenticated_user_can_view_tables_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('tables.index'));

        $response->assertOk();
        $response->assertSee('Tables Overview');
    }

    public function test_floor_plan_shows_table_states_and_assigns_an_order(): void
    {
        $user = User::factory()->create();
        $table = RestaurantTable::create(['number' => 21, 'seats' => 4, 'area' => 'Terrace']);
        $order = Order::create([
            'restaurant_table_id' => null,
            'order_type' => 'pickup',
            'status' => Order::STATUS_OPEN,
            'workflow_status' => 'confirmed',
            'kitchen_status' => Order::KITCHEN_STATUS_PENDING,
            'payment_status' => Order::PAYMENT_STATUS_UNPAID,
            'total' => 12,
        ]);

        $this->actingAs($user)->patch(route('tables.floor-status.update', $table), [
            'service_status' => RestaurantTable::STATUS_RESERVED,
        ])->assertRedirect();
        $this->assertDatabaseHas('restaurant_tables', ['id' => $table->id, 'service_status' => 'reserved']);

        $this->actingAs($user)->post(route('tables.assign-order', $table), ['order_id' => $order->id])->assertRedirect();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'restaurant_table_id' => $table->id, 'order_type' => 'dine_in']);

        $this->actingAs($user)->get(route('tables.index'))
            ->assertOk()->assertSee('Terrace')->assertSee('current bill')->assertSee('occupied');
    }

    public function test_user_can_move_an_order_to_another_table(): void
    {
        $user = User::factory()->create();
        $source = RestaurantTable::create(['number' => 22]);
        $target = RestaurantTable::create(['number' => 23]);
        $order = $source->orders()->create(['status' => 'open', 'total' => 20]);

        $this->actingAs($user)->post(route('tables.move-order', $source), ['target_table_id' => $target->id])->assertRedirect();

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'restaurant_table_id' => $target->id]);
        $this->assertDatabaseHas('restaurant_tables', ['id' => $source->id, 'service_status' => 'cleaning']);
    }

    public function test_user_can_merge_tables_and_combine_their_orders(): void
    {
        $user = User::factory()->create();
        $source = RestaurantTable::create(['number' => 24]);
        $target = RestaurantTable::create(['number' => 25]);
        $sourceOrder = $source->orders()->create(['status' => 'open', 'total' => 8]);
        $targetOrder = $target->orders()->create(['status' => 'open', 'total' => 10]);
        $sourceOrder->items()->create(['name' => 'Soup', 'quantity' => 1, 'unit_price' => 8, 'line_total' => 8]);
        $targetOrder->items()->create(['name' => 'Pasta', 'quantity' => 1, 'unit_price' => 10, 'line_total' => 10]);

        $this->actingAs($user)->post(route('tables.merge', $source), ['target_table_id' => $target->id])->assertRedirect();

        $this->assertDatabaseHas('restaurant_tables', ['id' => $source->id, 'merged_into_table_id' => $target->id]);
        $this->assertDatabaseHas('order_items', ['order_id' => $targetOrder->id, 'name' => 'Soup']);
        $this->assertSame(18.0, (float) $targetOrder->fresh()->total);

        $this->actingAs($user)->delete(route('tables.unmerge', $source))->assertRedirect();
        $this->assertDatabaseHas('restaurant_tables', ['id' => $source->id, 'merged_into_table_id' => null, 'service_status' => 'cleaning']);
    }

    public function test_user_can_create_and_manage_table_order_items(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('tables.store'), [
            'number' => 15,
        ]);

        $table = RestaurantTable::where('number', 15)->firstOrFail();
        $response->assertRedirect(route('tables.order', $table));

        $this->actingAs($user)->post(route('tables.items.store', $table), [
            'name' => 'Burger Menu',
            'quantity' => 2,
            'unit_price' => 12.50,
        ])->assertRedirect(route('tables.order', $table));

        $order = Order::where('restaurant_table_id', $table->id)->firstOrFail();
        $this->assertEquals(25.00, (float) $order->total);

        $item = OrderItem::where('order_id', $order->id)->firstOrFail();

        $this->actingAs($user)->put(route('tables.items.update', [$table, $item]), [
            'name' => 'Burger Menu XL',
            'quantity' => 1,
            'unit_price' => 15.00,
        ])->assertRedirect(route('tables.order', $table));

        $order->refresh();
        $this->assertEquals(15.00, (float) $order->total);

        $this->actingAs($user)->delete(route('tables.items.destroy', [$table, $item]))->assertRedirect(route('tables.order', $table));

        $order->refresh();
        $this->assertEquals(0.00, (float) $order->total);

        $this->actingAs($user)->post(route('tables.close-order', $table))->assertRedirect(route('tables.index'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_CLOSED,
        ]);
    }

    public function test_store_table_uses_existing_table_number_instead_of_failing(): void
    {
        $user = User::factory()->create();
        $table = RestaurantTable::create(['number' => 1]);

        $response = $this->actingAs($user)->post(route('tables.store'), [
            'number' => 1,
        ]);

        $response->assertRedirect(route('tables.order', $table));
        $response->assertSessionHas('info');
        $this->assertEquals(1, RestaurantTable::where('number', 1)->count());
    }

    public function test_user_can_quick_add_menu_item_without_typing(): void
    {
        $user = User::factory()->create();
        $table = RestaurantTable::create(['number' => 3]);
        $menuItem = MenuItem::create([
            'name' => 'Tap Burger',
            'category' => 'Burgers',
            'price' => 13.50,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($user)->post(route('tables.menu-items.quick-add', $table), [
            'menu_item_id' => $menuItem->id,
            'quantity' => 1,
        ])->assertRedirect(route('tables.order', $table));

        $order = Order::where('restaurant_table_id', $table->id)->firstOrFail();
        $item = OrderItem::where('order_id', $order->id)->firstOrFail();

        $this->assertEquals('Tap Burger', $item->name);
        $this->assertEquals(13.50, (float) $item->unit_price);
        $this->assertEquals(13.50, (float) $order->total);
    }

    public function test_quick_add_increments_quantity_instead_of_creating_duplicate_rows(): void
    {
        $user = User::factory()->create();
        $table = RestaurantTable::create(['number' => 7]);
        $menuItem = MenuItem::create([
            'name' => 'Fries Tap',
            'category' => 'Sides',
            'price' => 4.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $payload = [
            'menu_item_id' => $menuItem->id,
            'quantity' => 1,
        ];

        $this->actingAs($user)->post(route('tables.menu-items.quick-add', $table), $payload)->assertRedirect(route('tables.order', $table));
        $this->actingAs($user)->post(route('tables.menu-items.quick-add', $table), $payload)->assertRedirect(route('tables.order', $table));

        $order = Order::where('restaurant_table_id', $table->id)->firstOrFail();
        $items = OrderItem::where('order_id', $order->id)->get();

        $this->assertCount(1, $items);
        $this->assertEquals(2, (int) $items->first()->quantity);
        $this->assertEquals(8.00, (float) $order->total);
    }

    public function test_user_can_open_dedicated_order_page(): void
    {
        $user = User::factory()->create();
        $table = RestaurantTable::create(['number' => 9]);

        $response = $this->actingAs($user)->get(route('tables.order', $table));

        $response->assertOk();
        $response->assertSee('Order Page');
        $response->assertSee('Table #9');
    }

    public function test_close_order_keeps_order_and_items_in_completed_history(): void
    {
        $user = User::factory()->create();
        $table = RestaurantTable::create(['number' => 10]);

        $this->actingAs($user)->post(route('tables.items.store', $table), [
            'name' => 'Auto Clear Burger',
            'quantity' => 2,
            'unit_price' => 9.50,
        ]);

        $order = Order::where('restaurant_table_id', $table->id)->firstOrFail();
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id]);

        $this->actingAs($user)->post(route('tables.close-order', $table))->assertRedirect(route('tables.index'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_CLOSED,
        ]);
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id]);
    }

    public function test_waiter_sync_endpoint_processes_batch_and_returns_fresh_order(): void
    {
        $user = User::factory()->create();
        $table = RestaurantTable::create(['number' => 11]);
        $menuItem = MenuItem::create([
            'name' => 'Sync Burger',
            'category' => 'Smash Burgers',
            'price' => 11.50,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($user)->postJson(route('tables.order.sync', $table), [
            'device_id' => 'device-a',
            'actions' => [
                [
                    'id' => 'act-1',
                    'type' => 'add_menu_item',
                    'payload' => [
                        'menu_item_id' => $menuItem->id,
                        'quantity' => 1,
                        'local_ref' => 'loc-item-1',
                    ],
                ],
                [
                    'id' => 'act-2',
                    'type' => 'set_item_note',
                    'payload' => [
                        'item_ref' => 'loc-item-1',
                        'note' => 'No onions',
                    ],
                ],
                [
                    'id' => 'act-3',
                    'type' => 'set_item_quantity',
                    'payload' => [
                        'item_ref' => 'loc-item-1',
                        'quantity' => 2,
                    ],
                ],
            ],
        ]);

        $response->assertOk()
            ->assertJsonPath('order.items.0.name', 'Sync Burger')
            ->assertJsonPath('order.items.0.quantity', 2)
            ->assertJsonPath('order.items.0.note', 'No onions')
            ->assertJsonCount(3, 'processed_action_ids');

        $order = Order::where('restaurant_table_id', $table->id)->firstOrFail();
        $this->assertEquals(23.00, (float) $order->total);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'name' => 'Sync Burger',
            'quantity' => 2,
            'note' => 'No onions',
        ]);
    }

    public function test_waiter_sync_endpoint_is_idempotent_for_retried_actions(): void
    {
        $user = User::factory()->create();
        $table = RestaurantTable::create(['number' => 12]);
        $menuItem = MenuItem::create([
            'name' => 'Retry Coke',
            'category' => 'Drinks',
            'price' => 3.25,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $payload = [
            'device_id' => 'device-retry',
            'actions' => [
                [
                    'id' => 'retry-act-1',
                    'type' => 'add_menu_item',
                    'payload' => [
                        'menu_item_id' => $menuItem->id,
                        'quantity' => 1,
                        'local_ref' => 'loc-retry-1',
                    ],
                ],
            ],
        ];

        $this->actingAs($user)->postJson(route('tables.order.sync', $table), $payload)->assertOk();
        $this->actingAs($user)->postJson(route('tables.order.sync', $table), $payload)->assertOk();

        $order = Order::where('restaurant_table_id', $table->id)->firstOrFail();
        $items = OrderItem::where('order_id', $order->id)->get();

        $this->assertCount(1, $items);
        $this->assertEquals(1, (int) $items->first()->quantity);
        $this->assertEquals(3.25, (float) $order->total);
    }
}

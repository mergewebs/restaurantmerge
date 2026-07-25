<?php

namespace Tests\Feature;

use App\Models\InventoryIngredient;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_manage_suppliers_ingredients_and_stock_movements(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $this->actingAs($owner)->post(route('inventory.suppliers.store'), [
            'name' => 'Fresh Foods', 'contact_name' => 'Ana', 'phone' => '555-0100', 'email' => 'ana@example.com',
        ])->assertRedirect();
        $supplier = Supplier::firstOrFail();

        $this->actingAs($owner)->post(route('inventory.ingredients.store'), [
            'name' => 'Avocado', 'unit' => 'kg', 'stock_quantity' => 8, 'low_stock_threshold' => 2, 'supplier_id' => $supplier->id,
        ])->assertRedirect();
        $ingredient = InventoryIngredient::firstOrFail();

        $this->actingAs($owner)->post(route('inventory.movements.store'), [
            'target_type' => 'ingredient', 'target_id' => $ingredient->id, 'movement_type' => 'waste', 'quantity' => 1.5,
            'supplier_id' => $supplier->id, 'notes' => 'Overripe',
        ])->assertRedirect();

        $this->assertSame(6.5, (float) $ingredient->fresh()->stock_quantity);
        $this->assertDatabaseHas('stock_movements', ['target_type' => 'ingredient', 'target_id' => $ingredient->id, 'movement_type' => 'waste', 'quantity' => -1.5]);
        $this->actingAs($owner)->get(route('inventory.index'))->assertOk()->assertSee('Inventory')->assertSee('Avocado')->assertSee('Fresh Foods');
    }

    public function test_completed_order_deducts_product_and_recipe_stock_only_once(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $ingredient = InventoryIngredient::create(['name' => 'Beef', 'unit' => 'kg', 'stock_quantity' => 10, 'low_stock_threshold' => 2]);
        $product = MenuItem::create(['name' => 'House Burger', 'category' => 'Smash Burger', 'price' => 12, 'stock_quantity' => 10, 'low_stock_threshold' => 2, 'is_active' => true]);
        $product->recipeIngredients()->attach($ingredient->id, ['quantity' => 0.25]);
        $order = Order::create(['status' => 'open', 'workflow_status' => 'preparing', 'order_type' => 'takeaway', 'total' => 24]);
        $order->items()->create(['menu_item_id' => $product->id, 'name' => $product->name, 'quantity' => 2, 'unit_price' => 12, 'line_total' => 24]);

        $this->actingAs($owner)->patch(route('orders.status.update', $order), ['workflow_status' => 'completed'])->assertRedirect();
        $this->assertSame(8, $product->fresh()->stock_quantity);
        $this->assertSame(9.5, (float) $ingredient->fresh()->stock_quantity);
        $this->assertNotNull($order->fresh()->inventory_deducted_at);
        $this->assertDatabaseCount('stock_movements', 2);

        $this->actingAs($owner)->patch(route('orders.status.update', $order), ['workflow_status' => 'completed'])->assertRedirect();
        $this->assertSame(8, $product->fresh()->stock_quantity);
        $this->assertSame(9.5, (float) $ingredient->fresh()->stock_quantity);
        $this->assertDatabaseCount('stock_movements', 2);
    }

    public function test_waiter_cannot_open_inventory(): void
    {
        $waiter = User::factory()->create(['role' => 'waiter']);
        $this->actingAs($waiter)->get(route('inventory.index'))->assertForbidden();
    }
}

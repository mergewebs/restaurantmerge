<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuItemManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_menu_item_with_package_fields(): void
    {
        $admin = User::factory()->create([
            'role' => 'owner',
        ]);

        $this->actingAs($admin)->post(route('menu-items.store'), [
            'name' => 'Lunch Package',
            'category' => 'Packages',
            'price' => 18.50,
            'sort_order' => 1,
            'is_active' => 1,
            'is_package' => 1,
            'package_details' => 'Soup + Steak + Drink',
        ])->assertRedirect(route('menu-items.index'));

        $menuItem = MenuItem::where('name', 'Lunch Package')->firstOrFail();
        $this->assertTrue((bool) $menuItem->is_package);
        $this->assertEquals('Soup + Steak + Drink', $menuItem->package_details);

        $this->actingAs($admin)->put(route('menu-items.update', $menuItem), [
            'name' => 'Lunch Package Plus',
            'category' => 'Packages',
            'price' => 19.90,
            'sort_order' => 2,
            'is_active' => 1,
            'is_package' => 1,
            'package_details' => 'Soup + Steak + Dessert + Drink',
        ])->assertRedirect(route('menu-items.index'));

        $menuItem->refresh();
        $this->assertEquals('Lunch Package Plus', $menuItem->name);
        $this->assertEquals(19.90, (float) $menuItem->price);

        $this->actingAs($admin)->delete(route('menu-items.destroy', $menuItem))
            ->assertRedirect(route('menu-items.index'));

        $this->assertDatabaseMissing('menu_items', ['id' => $menuItem->id]);
    }

    public function test_non_admin_cannot_manage_menu_items(): void
    {
        $waiter = User::factory()->create([
            'role' => 'waiter',
        ]);

        $this->actingAs($waiter)->get(route('menu-items.index'))->assertForbidden();

        $this->actingAs($waiter)->post(route('menu-items.store'), [
            'name' => 'Blocked Item',
            'category' => 'General',
            'price' => 5.00,
            'sort_order' => 0,
        ])->assertForbidden();
    }
}

<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\MenuCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MenuItemManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_menu_item_with_image_uploads(): void
    {
        Storage::fake('product-images');

        $admin = User::factory()->create([
            'role' => 'owner',
        ]);

        $createImage = UploadedFile::fake()->image('lunch-special.jpg');

        $this->actingAs($admin)->post(route('menu-items.store'), [
            'name' => 'Lunch Special',
            'category' => 'Smash Burger',
            'description' => 'Owner-managed combo',
            'price' => 18.50,
            'sort_order' => 1,
            'is_active' => 1,
            'image' => $createImage,
        ])->assertRedirect(route('menu-items.index'));

        $menuItem = MenuItem::where('name', 'Lunch Special')->firstOrFail();
        $this->assertNotNull($menuItem->image_path);
        $this->assertNotNull($menuItem->image_display_path);
        $this->assertNotNull($menuItem->image_thumbnail_path);
        Storage::disk('product-images')->assertExists($menuItem->image_path);
        Storage::disk('product-images')->assertExists($menuItem->image_display_path);
        Storage::disk('product-images')->assertExists($menuItem->image_thumbnail_path);

        $originalImagePath = $menuItem->image_path;
        $updatedImage = UploadedFile::fake()->image('lunch-special-plus.jpg');

        $this->actingAs($admin)->put(route('menu-items.update', $menuItem), [
            'name' => 'Lunch Special Plus',
            'category' => 'Smash Burger',
            'description' => 'Updated combo',
            'price' => 19.90,
            'sort_order' => 2,
            'is_active' => 1,
            'image' => $updatedImage,
        ])->assertRedirect(route('menu-items.index'));

        $menuItem->refresh();
        $this->assertEquals('Lunch Special Plus', $menuItem->name);
        $this->assertEquals(19.90, (float) $menuItem->price);
        $this->assertNotSame($originalImagePath, $menuItem->image_path);
        Storage::disk('product-images')->assertMissing($originalImagePath);
        Storage::disk('product-images')->assertExists($menuItem->image_path);
        Storage::disk('product-images')->assertExists($menuItem->image_display_path);
        Storage::disk('product-images')->assertExists($menuItem->image_thumbnail_path);

        $currentImagePath = $menuItem->image_path;
        $currentDisplayPath = $menuItem->image_display_path;
        $currentThumbnailPath = $menuItem->image_thumbnail_path;

        $this->actingAs($admin)->delete(route('menu-items.destroy', $menuItem))
            ->assertRedirect(route('menu-items.index'));

        $this->assertDatabaseMissing('menu_items', ['id' => $menuItem->id]);
        Storage::disk('product-images')->assertMissing($currentImagePath);
        Storage::disk('product-images')->assertMissing($currentDisplayPath);
        Storage::disk('product-images')->assertMissing($currentThumbnailPath);
    }

    public function test_admin_product_page_lists_managed_products(): void
    {
        $admin = User::factory()->create([
            'role' => 'owner',
        ]);

        $menuItem = MenuItem::create([
            'name' => 'Managed Product',
            'category' => 'Smash Burger',
            'description' => 'Visible in admin',
            'price' => 11.50,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('menu-items.index'))
            ->assertOk()
            ->assertSee('Managed Product');

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItem->id,
            'name' => 'Managed Product',
            'is_active' => true,
        ]);
    }

    public function test_non_admin_cannot_manage_menu_items(): void
    {
        $waiter = User::factory()->create([
            'role' => 'waiter',
        ]);

        $this->actingAs($waiter)->get(route('menu-items.index'))->assertForbidden();

        $this->actingAs($waiter)->post(route('menu-items.store'), [
            'name' => 'Blocked Item',
            'category' => 'Smash Burger',
            'price' => 5.00,
            'sort_order' => 0,
        ])->assertForbidden();
    }

    public function test_owner_can_manage_restaurant_menu_details_and_product_choices(): void
    {
        $admin = User::factory()->create(['role' => 'owner']);

        $this->actingAs($admin)->post(route('menu-categories.store'), [
            'name' => 'Chef Specials',
            'sort_order' => 2,
            'is_active' => 1,
        ])->assertRedirect();

        $this->actingAs($admin)->post(route('menu-items.store'), [
            'name' => 'Summer Sea Bass',
            'category' => 'Chef Specials',
            'description' => 'Grilled with lemon butter',
            'ingredients' => 'Sea bass, lemon, butter, herbs',
            'allergens' => 'Fish, milk',
            'price' => 22.00,
            'dine_in_price' => 22.00,
            'takeaway_price' => 23.00,
            'delivery_price' => 24.50,
            'availability_status' => 'sold_out',
            'item_kind' => 'seasonal',
            'is_active' => 1,
        ])->assertRedirect(route('menu-items.index'));

        $item = MenuItem::where('name', 'Summer Sea Bass')->firstOrFail();
        $category = MenuCategory::where('name', 'Chef Specials')->firstOrFail();
        $this->assertSame($category->id, $item->menu_category_id);
        $this->assertSame('sold_out', $item->availability_status);
        $this->assertSame('Fish, milk', $item->allergens);
        $this->assertSame(24.50, (float) $item->delivery_price);
        $this->assertSame(22.00, $item->priceFor('dine_in'));
        $this->assertSame(23.00, $item->priceFor('pickup'));
        $this->assertSame(24.50, $item->priceFor('delivery'));

        $this->actingAs($admin)->post(route('menu-items.options.store', $item), [
            'type' => 'add_on',
            'name' => 'Extra lemon butter',
            'price_adjustment' => 1.50,
            'is_active' => 1,
        ])->assertRedirect();

        $this->assertDatabaseHas('menu_item_options', [
            'menu_item_id' => $item->id,
            'type' => 'add_on',
            'name' => 'Extra lemon butter',
        ]);

        $this->actingAs($admin)->get(route('menu-items.index'))
            ->assertOk()
            ->assertSee('Menu Management')
            ->assertSee('Summer Sea Bass')
            ->assertSee('Sold out');
    }

    public function test_used_menu_category_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'owner']);
        $category = MenuCategory::where('name', 'Starters')->firstOrFail();
        MenuItem::create([
            'name' => 'House Croquette',
            'category' => $category->name,
            'menu_category_id' => $category->id,
            'price' => 3.50,
            'is_active' => true,
        ]);

        $this->actingAs($admin)->delete(route('menu-categories.destroy', $category))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('menu_categories', ['id' => $category->id]);
    }
}

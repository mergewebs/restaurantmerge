<?php

namespace Tests\Feature;

use App\Models\MenuItem;
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
}


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

        $createImage = UploadedFile::fake()->image('lunch-package.jpg');

        $this->actingAs($admin)->post(route('menu-items.store'), [
            'name' => 'Lunch Package',
            'category' => 'Packages',
            'description' => 'Owner-managed combo',
            'price' => 18.50,
            'sort_order' => 1,
            'is_active' => 1,
            'is_package' => 1,
            'package_details' => 'Soup + Steak + Drink',
            'image' => $createImage,
        ])->assertRedirect(route('menu-items.index'));

        $menuItem = MenuItem::where('name', 'Lunch Package')->firstOrFail();
        $this->assertTrue((bool) $menuItem->is_package);
        $this->assertEquals('Soup + Steak + Drink', $menuItem->package_details);
        $this->assertNotNull($menuItem->image_path);
        $this->assertNotNull($menuItem->image_display_path);
        $this->assertNotNull($menuItem->image_thumbnail_path);
        Storage::disk('product-images')->assertExists($menuItem->image_path);
        Storage::disk('product-images')->assertExists($menuItem->image_display_path);
        Storage::disk('product-images')->assertExists($menuItem->image_thumbnail_path);

        $originalImagePath = $menuItem->image_path;
        $updatedImage = UploadedFile::fake()->image('lunch-package-plus.jpg');

        $this->actingAs($admin)->put(route('menu-items.update', $menuItem), [
            'name' => 'Lunch Package Plus',
            'category' => 'Packages',
            'description' => 'Updated combo',
            'price' => 19.90,
            'sort_order' => 2,
            'is_active' => 1,
            'is_package' => 1,
            'package_details' => 'Soup + Steak + Dessert + Drink',
            'image' => $updatedImage,
        ])->assertRedirect(route('menu-items.index'));

        $menuItem->refresh();
        $this->assertEquals('Lunch Package Plus', $menuItem->name);
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

    public function test_public_pages_and_ordering_share_the_same_active_products(): void
    {
        $menuItem = MenuItem::create([
            'name' => 'Shared Product',
            'category' => 'Smash Burger',
            'description' => 'Visible everywhere',
            'price' => 11.50,
            'sort_order' => 3,
            'is_active' => true,
            'is_package' => false,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Shared Product');

        $this->get(route('order.public'))
            ->assertOk()
            ->assertSee('Shared Product');

        $this->get(route('menu.public'))
            ->assertOk()
            ->assertSee('Shared Product');

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItem->id,
            'name' => 'Shared Product',
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

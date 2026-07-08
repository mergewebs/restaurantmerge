<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageOptimizationCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_optimize_images_command_generates_variants_for_existing_uploaded_product_images(): void
    {
        Storage::fake('product-images');

        $menuItem = MenuItem::create([
            'name' => 'Backfill Burger',
            'category' => 'Smash Burger',
            'description' => 'Existing upload',
            'price' => 12.90,
            'sort_order' => 1,
            'is_active' => true,
            'is_package' => false,
            'image_path' => 'originals/backfill-burger.jpg',
        ]);

        Storage::disk('product-images')->makeDirectory('originals');

        $sourceImage = imagecreatetruecolor(1200, 900);
        $background = imagecolorallocate($sourceImage, 250, 192, 33);
        imagefilledrectangle($sourceImage, 0, 0, 1200, 900, $background);

        imagejpeg($sourceImage, Storage::disk('product-images')->path($menuItem->image_path), 90);
        imagedestroy($sourceImage);

        Artisan::call('products:optimize-images');

        $menuItem->refresh();

        $this->assertNotNull($menuItem->image_display_path);
        $this->assertNotNull($menuItem->image_thumbnail_path);
        $this->assertStringEndsWith('.webp', $menuItem->image_display_path);
        $this->assertStringEndsWith('.webp', $menuItem->image_thumbnail_path);

        Storage::disk('product-images')->assertExists($menuItem->image_path);
        Storage::disk('product-images')->assertExists($menuItem->image_display_path);
        Storage::disk('product-images')->assertExists($menuItem->image_thumbnail_path);
    }
}

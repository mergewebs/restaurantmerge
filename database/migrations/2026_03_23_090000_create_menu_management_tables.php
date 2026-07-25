<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        foreach ([
            'Starters', 'Entrantes', "Acompa\u{00F1}amientos", 'Smash Burger', 'Bol de Arroz',
            'Postres de la Casa', 'Bebidas', 'Cafes', 'Cocteles', 'Cervezas', 'Packages',
        ] as $sortOrder => $name) {
            DB::table('menu_categories')->insert([
                'name' => $name,
                'sort_order' => $sortOrder,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->default('Starters');
            $table->foreignId('menu_category_id')->nullable()->constrained('menu_categories')->nullOnDelete();
            $table->text('description')->nullable();
            $table->text('ingredients')->nullable();
            $table->text('allergens')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_display_path')->nullable();
            $table->string('image_thumbnail_path')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('dine_in_price', 10, 2)->nullable();
            $table->decimal('takeaway_price', 10, 2)->nullable();
            $table->decimal('delivery_price', 10, 2)->nullable();
            $table->unsignedInteger('stock_quantity')->nullable();
            $table->unsignedInteger('low_stock_threshold')->default(5);
            $table->string('availability_status')->default('available');
            $table->string('item_kind')->default('regular');
            $table->string('station')->default('kitchen');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('menu_item_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('name');
            $table->decimal('price_adjustment', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_item_options');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menu_categories');
    }
};

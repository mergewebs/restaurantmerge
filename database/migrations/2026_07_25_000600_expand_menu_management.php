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

        $categories = [
            'Starters', 'Entrantes', 'Acompañamientos', 'Smash Burger', 'Bol de Arroz',
            'Postres de la Casa', 'Bebidas', 'Cafes', 'Cocteles', 'Cervezas', 'Packages',
        ];

        foreach ($categories as $index => $name) {
            DB::table('menu_categories')->insert([
                'name' => $name,
                'sort_order' => $index,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('menu_items', function (Blueprint $table) {
            $table->foreignId('menu_category_id')->nullable()->after('category')->constrained('menu_categories')->nullOnDelete();
            $table->text('ingredients')->nullable()->after('description');
            $table->text('allergens')->nullable()->after('ingredients');
            $table->string('availability_status')->default('available')->after('low_stock_threshold');
            $table->string('item_kind')->default('regular')->after('availability_status');
            $table->decimal('dine_in_price', 10, 2)->nullable()->after('price');
            $table->decimal('takeaway_price', 10, 2)->nullable()->after('dine_in_price');
            $table->decimal('delivery_price', 10, 2)->nullable()->after('takeaway_price');
        });

        foreach (DB::table('menu_categories')->get(['id', 'name']) as $category) {
            DB::table('menu_items')->where('category', $category->name)->update(['menu_category_id' => $category->id]);
        }

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

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('menu_category_id');
            $table->dropColumn([
                'ingredients', 'allergens', 'availability_status', 'item_kind',
                'dine_in_price', 'takeaway_price', 'delivery_price',
            ]);
        });

        Schema::dropIfExists('menu_categories');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('station')->default('kitchen')->after('item_kind');
        });

        DB::table('menu_items')->whereIn('category', ['Bebidas', 'Drinks'])->update(['station' => 'drinks']);
        DB::table('menu_items')->whereIn('category', ['Cafes', 'Cocteles', 'Cervezas', 'Bar'])->update(['station' => 'bar']);
        DB::table('menu_items')->whereIn('category', ['Postres de la Casa', 'Desserts'])->update(['station' => 'desserts']);

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('station')->default('kitchen')->after('note');
            $table->text('allergens')->nullable()->after('station');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['station', 'allergens']);
        });
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('station');
        });
    }
};

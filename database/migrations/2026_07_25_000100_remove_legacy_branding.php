<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('menu_items')) {
            DB::table('menu_items')->where('name', 'KOYA SALAD')->update(['name' => 'HOUSE SALAD']);
            DB::table('menu_items')->where('name', 'KOYA SMASH')->update(['name' => 'HOUSE SMASH']);
            DB::table('menu_items')->where('name', 'KOYA SMASH SPECIAL')->update(['name' => 'HOUSE SMASH SPECIAL']);

            foreach (['image_path', 'image_display_path', 'image_thumbnail_path'] as $column) {
                if (! Schema::hasColumn('menu_items', $column)) {
                    continue;
                }

                DB::table('menu_items')
                    ->where($column, 'like', '%koya-salad%')
                    ->orderBy('id')
                    ->eachById(function (object $item) use ($column): void {
                        DB::table('menu_items')
                            ->where('id', $item->id)
                            ->update([$column => Str::replace('koya-salad', 'house-salad', $item->{$column})]);
                    });
            }
        }

        if (Schema::hasTable('order_items')) {
            DB::table('order_items')->where('name', 'KOYA SALAD')->update(['name' => 'HOUSE SALAD']);
            DB::table('order_items')->where('name', 'KOYA SMASH')->update(['name' => 'HOUSE SMASH']);
            DB::table('order_items')->where('name', 'KOYA SMASH SPECIAL')->update(['name' => 'HOUSE SMASH SPECIAL']);
        }
    }

    public function down(): void
    {
        // Legacy branding is intentionally not restored.
    }
};

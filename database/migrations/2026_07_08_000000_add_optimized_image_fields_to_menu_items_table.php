<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('image_display_path')->nullable()->after('image_path');
            $table->string('image_thumbnail_path')->nullable()->after('image_display_path');
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn([
                'image_display_path',
                'image_thumbnail_path',
            ]);
        });
    }
};

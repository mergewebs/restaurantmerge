<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $columnsToDrop = array_values(array_filter([
                Schema::hasColumn('menu_items', 'is_package') ? 'is_package' : null,
                Schema::hasColumn('menu_items', 'package_details') ? 'package_details' : null,
            ]));

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            if (! Schema::hasColumn('menu_items', 'is_package')) {
                $table->boolean('is_package')->default(false)->after('is_active');
            }

            if (! Schema::hasColumn('menu_items', 'package_details')) {
                $table->text('package_details')->nullable()->after('is_package');
            }
        });
    }
};

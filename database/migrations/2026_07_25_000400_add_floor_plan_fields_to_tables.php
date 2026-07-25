<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->string('service_status')->default('available')->after('number');
            $table->unsignedInteger('seats')->default(4)->after('service_status');
            $table->string('area')->default('Main Room')->after('seats');
            $table->foreignId('merged_into_table_id')->nullable()->after('area')->constrained('restaurant_tables')->nullOnDelete();
            $table->index(['area', 'service_status'], 'restaurant_tables_area_status_idx');
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->dropIndex('restaurant_tables_area_status_idx');
            $table->dropConstrainedForeignId('merged_into_table_id');
            $table->dropColumn(['service_status', 'seats', 'area']);
        });
    }
};

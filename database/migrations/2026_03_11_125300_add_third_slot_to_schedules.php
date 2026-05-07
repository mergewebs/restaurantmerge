<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_schedules', function (Blueprint $table) {
            $table->time('open_time_3')->nullable()->after('close_time_2');
            $table->time('close_time_3')->nullable()->after('open_time_3');
        });

        Schema::table('restaurant_exceptions', function (Blueprint $table) {
            $table->time('open_time_3')->nullable()->after('close_time_2');
            $table->time('close_time_3')->nullable()->after('open_time_3');
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_schedules', function (Blueprint $table) {
            $table->dropColumn(['open_time_3', 'close_time_3']);
        });

        Schema::table('restaurant_exceptions', function (Blueprint $table) {
            $table->dropColumn(['open_time_3', 'close_time_3']);
        });
    }
};

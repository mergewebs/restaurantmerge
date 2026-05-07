<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_schedules', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('day_of_week'); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
            $table->boolean('is_closed')->default(false);
            $table->time('open_time_1')->nullable();
            $table->time('close_time_1')->nullable();
            $table->time('open_time_2')->nullable();
            $table->time('close_time_2')->nullable();
            $table->integer('max_party_size')->default(10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_schedules');
    }
};

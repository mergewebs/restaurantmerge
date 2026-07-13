<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_exceptions', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->boolean('is_closed')->default(false);
            $table->time('open_time_1')->nullable();
            $table->time('close_time_1')->nullable();
            $table->time('open_time_2')->nullable();
            $table->time('close_time_2')->nullable();
            $table->time('open_time_3')->nullable();
            $table->time('close_time_3')->nullable();
            $table->integer('max_party_size')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_exceptions');
    }
};

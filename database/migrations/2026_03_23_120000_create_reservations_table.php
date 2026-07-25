<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->integer('party_size');
            $table->dateTime('reservation_time');
            $table->text('notes')->nullable();
            $table->text('allergies')->nullable();
            $table->foreignId('restaurant_table_id')->nullable()->constrained('restaurant_tables')->nullOnDelete();
            $table->string('status')->default('confirmed');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'reservation_time'], 'reservations_status_time_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};

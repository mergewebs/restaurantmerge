<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cashouts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('opening_cash', 10, 2);
            $table->decimal('sales_cash', 10, 2);
            $table->decimal('expenses_cash', 10, 2);
            $table->decimal('closing_cash', 10, 2);
            $table->decimal('expected_cash', 10, 2);
            $table->decimal('difference', 10, 2);
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashouts');
    }
};

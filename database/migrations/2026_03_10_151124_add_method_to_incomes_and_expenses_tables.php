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
        Schema::table('incomes', function (Blueprint $table) {
            $table->enum('method', ['card', 'cash'])->default('cash');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->enum('method', ['card', 'cash'])->default('cash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn('method');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('method');
        });
    }
};

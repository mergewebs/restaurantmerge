<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->text('allergies')->nullable()->after('notes');
            $table->foreignId('restaurant_table_id')->nullable()->after('allergies')->constrained('restaurant_tables')->nullOnDelete();
            $table->index(['status', 'reservation_time'], 'reservations_status_time_idx');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex('reservations_status_time_idx');
            $table->dropConstrainedForeignId('restaurant_table_id');
            $table->dropColumn('allergies');
        });
    }
};

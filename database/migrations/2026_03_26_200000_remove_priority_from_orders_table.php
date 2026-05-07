<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('orders', 'priority')) {
            $this->dropIndexIfExists('orders', 'orders_status_kitchen_priority_idx');
            $this->dropIndexIfExists('orders', 'orders_status_priority_created_idx');

            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('priority');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('orders', 'priority')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedTinyInteger('priority')->default(1)->after('status');
                $table->index(['status', 'priority', 'created_at'], 'orders_status_priority_created_idx');
                $table->index(['status', 'kitchen_status', 'priority'], 'orders_status_kitchen_priority_idx');
            });
        }
    }

    private function dropIndexIfExists(string $table, string $index): void
    {
        try {
            Schema::table($table, function (Blueprint $blueprint) use ($index) {
                $blueprint->dropIndex($index);
            });
        } catch (\Throwable) {
            // Index may not exist in some environments; safe to ignore.
        }
    }
};

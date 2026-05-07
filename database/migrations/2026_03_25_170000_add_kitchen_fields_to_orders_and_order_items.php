<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'priority')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedTinyInteger('priority')->default(1)->after('status');
                $table->index(['status', 'priority', 'created_at'], 'orders_status_priority_created_idx');
            });
        }

        if (! Schema::hasColumn('order_items', 'status')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->string('status')->default('pending')->after('line_total');
                $table->index(['order_id', 'status'], 'order_items_order_status_idx');
            });
        }

        DB::table('order_items')->whereNull('status')->update(['status' => 'pending']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('order_items', 'status')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropIndex('order_items_order_status_idx');
                $table->dropColumn('status');
            });
        }

        if (Schema::hasColumn('orders', 'priority')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex('orders_status_priority_created_idx');
                $table->dropColumn('priority');
            });
        }
    }
};

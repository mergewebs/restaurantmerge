<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'order_type')) {
                $table->string('order_type')->default('table')->after('restaurant_table_id');
                $table->index(['status', 'order_type', 'created_at'], 'orders_status_type_created_idx');
            }

            if (! Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('total');
            }

            if (! Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone', 30)->nullable()->after('customer_name');
            }
        });

        DB::table('orders')
            ->whereNull('order_type')
            ->update(['order_type' => 'table']);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'customer_phone')) {
                $table->dropColumn('customer_phone');
            }

            if (Schema::hasColumn('orders', 'customer_name')) {
                $table->dropColumn('customer_name');
            }

            if (Schema::hasColumn('orders', 'order_type')) {
                $table->dropIndex('orders_status_type_created_idx');
                $table->dropColumn('order_type');
            }
        });
    }
};

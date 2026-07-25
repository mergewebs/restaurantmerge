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
            $table->string('payment_status')->default('unpaid')->after('kitchen_status');
            $table->index(['payment_status', 'created_at'], 'orders_payment_created_idx');
        });

        DB::table('orders')->where('status', 'closed')->update(['payment_status' => 'paid']);

        Schema::table('menu_items', function (Blueprint $table) {
            $table->unsignedInteger('stock_quantity')->nullable()->after('price');
            $table->unsignedInteger('low_stock_threshold')->default(5)->after('stock_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_payment_created_idx');
            $table->dropColumn('payment_status');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn(['stock_quantity', 'low_stock_threshold']);
        });
    }
};

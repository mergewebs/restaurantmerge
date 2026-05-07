<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'kitchen_status')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('kitchen_status')->default('pending')->after('priority');
                $table->index(['status', 'kitchen_status', 'priority'], 'orders_status_kitchen_priority_idx');
            });
        }

        DB::table('orders')
            ->whereNull('kitchen_status')
            ->update(['kitchen_status' => 'pending']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'kitchen_status')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex('orders_status_kitchen_priority_idx');
                $table->dropColumn('kitchen_status');
            });
        }
    }
};

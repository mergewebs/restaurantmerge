<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('reservations', 'submission_token')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->dropUnique('reservations_submission_token_unique');
                $table->dropColumn('submission_token');
            });
        }

        if (Schema::hasColumn('reservations', 'locale')) {
            Schema::table('reservations', fn (Blueprint $table) => $table->dropColumn('locale'));
        }

        if (Schema::hasColumn('orders', 'order_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex('orders_status_type_created_idx');
                $table->dropColumn('order_type');
            });
        }

        foreach (['customer_name', 'customer_phone'] as $column) {
            if (Schema::hasColumn('orders', $column)) {
                Schema::table('orders', fn (Blueprint $table) => $table->dropColumn($column));
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('reservations', 'submission_token')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->string('submission_token', 100)->nullable();
                $table->unique('submission_token', 'reservations_submission_token_unique');
            });
        }

        if (! Schema::hasColumn('reservations', 'locale')) {
            Schema::table('reservations', fn (Blueprint $table) => $table->string('locale', 5)->default('es'));
        }

        if (! Schema::hasColumn('orders', 'order_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('order_type')->default('table');
                $table->index(['status', 'order_type', 'created_at'], 'orders_status_type_created_idx');
            });
        }

        if (! Schema::hasColumn('orders', 'customer_name')) {
            Schema::table('orders', fn (Blueprint $table) => $table->string('customer_name')->nullable());
        }

        if (! Schema::hasColumn('orders', 'customer_phone')) {
            Schema::table('orders', fn (Blueprint $table) => $table->string('customer_phone', 30)->nullable());
        }
    }
};

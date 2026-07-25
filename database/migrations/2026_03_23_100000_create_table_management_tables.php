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
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('number')->unique();
            $table->string('service_status')->default('available');
            $table->unsignedInteger('seats')->default(4);
            $table->string('area')->default('Main Room');
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('merged_into_table_id')->nullable()->constrained('restaurant_tables')->nullOnDelete();
            $table->timestamps();

            $table->index(['area', 'service_status'], 'restaurant_tables_area_status_idx');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_table_id')->nullable()->constrained('restaurant_tables')->cascadeOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('open');
            $table->string('workflow_status')->default('new');
            $table->string('order_type')->default('dine_in');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone', 40)->nullable();
            $table->text('notes')->nullable();
            $table->string('kitchen_status')->default('pending');
            $table->string('payment_status')->default('unpaid');
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('service_charge', 10, 2)->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('inventory_deducted_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index(['workflow_status', 'created_at'], 'orders_workflow_created_idx');
            $table->index(['order_type', 'created_at'], 'orders_type_created_idx');
            $table->index(['payment_status', 'created_at'], 'orders_payment_created_idx');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('note', 300)->nullable();
            $table->string('station')->default('kitchen');
            $table->text('allergens')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('line_total', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index(['order_id', 'status'], 'order_items_order_status_idx');
        });

        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cash_register_session_id')->nullable()->constrained()->nullOnDelete();
            $table->string('method');
            $table->string('transaction_type')->default('payment');
            $table->foreignId('refunded_payment_id')->nullable()->constrained('order_payments')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('reference')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('paid_at');
            $table->timestamps();

            $table->index(['order_id', 'paid_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('restaurant_tables');
    }
};

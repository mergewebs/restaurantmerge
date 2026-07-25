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
            $table->foreignId('restaurant_table_id')->nullable()->change();
            $table->string('workflow_status')->default('new')->after('status');
            $table->string('order_type')->default('dine_in')->after('workflow_status');
            $table->string('customer_name')->nullable()->after('order_type');
            $table->string('customer_phone', 40)->nullable()->after('customer_name');
            $table->text('notes')->nullable()->after('customer_phone');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('total');
            $table->decimal('service_charge', 10, 2)->default(0)->after('discount_amount');
            $table->timestamp('completed_at')->nullable()->after('service_charge');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
            $table->index(['workflow_status', 'created_at'], 'orders_workflow_created_idx');
            $table->index(['order_type', 'created_at'], 'orders_type_created_idx');
        });

        DB::table('orders')->where('status', 'closed')->update([
            'workflow_status' => 'completed',
            'completed_at' => DB::raw('updated_at'),
        ]);

        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('method');
            $table->decimal('amount', 10, 2);
            $table->string('reference')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('paid_at');
            $table->timestamps();

            $table->index(['order_id', 'paid_at']);
        });

        DB::table('orders')->where('status', 'closed')->where('total', '>', 0)->orderBy('id')->each(function ($order) {
            DB::table('order_payments')->insert([
                'order_id' => $order->id,
                'method' => 'cash',
                'amount' => $order->total,
                'reference' => 'Imported closed order',
                'user_id' => null,
                'paid_at' => $order->updated_at,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_payments');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_workflow_created_idx');
            $table->dropIndex('orders_type_created_idx');
            $table->dropColumn([
                'workflow_status', 'order_type', 'customer_name', 'customer_phone', 'notes',
                'discount_amount', 'service_charge', 'completed_at', 'cancelled_at',
            ]);
        });
    }
};

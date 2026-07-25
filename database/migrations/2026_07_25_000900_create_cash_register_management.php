<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_register_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opened_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('opening_float', 10, 2)->default(0);
            $table->decimal('expected_cash', 10, 2)->nullable();
            $table->decimal('actual_cash', 10, 2)->nullable();
            $table->decimal('difference', 10, 2)->nullable();
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->string('status')->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('order_payments', function (Blueprint $table) {
            $table->foreignId('cash_register_session_id')->nullable()->after('order_id')->constrained()->nullOnDelete();
            $table->string('transaction_type')->default('payment')->after('method');
            $table->foreignId('refunded_payment_id')->nullable()->after('transaction_type')->constrained('order_payments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('refunded_payment_id');
            $table->dropConstrainedForeignId('cash_register_session_id');
            $table->dropColumn('transaction_type');
        });
        Schema::dropIfExists('cash_register_sessions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('assigned_area')->nullable()->after('role');
            $table->boolean('can_discount')->default(false)->after('assigned_area');
            $table->boolean('can_refund')->default(false)->after('can_discount');
            $table->boolean('can_cancel_orders')->default(false)->after('can_refund');
        });
        DB::table('users')->whereIn('role', ['owner', 'admin', 'superadmin'])->update(['can_discount' => true, 'can_refund' => true, 'can_cancel_orders' => true]);

        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->foreignId('assigned_user_id')->nullable()->after('area')->constrained('users')->nullOnDelete();
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('assigned_user_id')->nullable()->after('restaurant_table_id')->constrained('users')->nullOnDelete();
        });

        Schema::create('staff_schedules', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('shift_date'); $table->time('starts_at'); $table->time('ends_at');
            $table->string('area')->nullable(); $table->text('notes')->nullable(); $table->timestamps();
            $table->index(['shift_date', 'user_id']);
        });
        Schema::create('staff_attendances', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('clocked_in_at'); $table->timestamp('clocked_out_at')->nullable();
            $table->timestamp('break_started_at')->nullable(); $table->unsignedInteger('break_minutes')->default(0);
            $table->text('notes')->nullable(); $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_attendances'); Schema::dropIfExists('staff_schedules');
        Schema::table('orders', fn (Blueprint $table) => $table->dropConstrainedForeignId('assigned_user_id'));
        Schema::table('restaurant_tables', fn (Blueprint $table) => $table->dropConstrainedForeignId('assigned_user_id'));
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['assigned_area', 'can_discount', 'can_refund', 'can_cancel_orders']));
    }
};

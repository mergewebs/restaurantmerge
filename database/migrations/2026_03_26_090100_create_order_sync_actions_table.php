<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('order_sync_actions')) {
            Schema::create('order_sync_actions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->string('device_id', 100);
                $table->string('action_id', 80);
                $table->timestamp('created_at')->useCurrent();

                $table->unique(['order_id', 'device_id', 'action_id'], 'order_sync_unique_action');
                $table->index(['order_id', 'created_at'], 'order_sync_order_created_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_sync_actions');
    }
};


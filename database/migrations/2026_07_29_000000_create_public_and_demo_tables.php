<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 120);
            $table->string('email');
            $table->string('telephone', 30);
            $table->timestamps();
            $table->index('created_at');
        });

        Schema::create('demo_ip_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->unique();
            $table->timestamp('first_access_at');
            $table->timestamp('last_access_at');
            $table->unsignedInteger('visit_count')->default(1);
            $table->string('browser', 80)->nullable();
            $table->string('device_type', 40)->nullable();
            $table->string('operating_system', 80)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('last_page', 500)->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->timestamp('blocked_at')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();

            $table->index('last_access_at');
            $table->index(['is_blocked', 'last_active_at']);
            $table->index(['country', 'device_type', 'browser']);
        });

        Schema::create('demo_access_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demo_ip_address_id')->constrained()->cascadeOnDelete();
            $table->string('session_hash', 64)->unique();
            $table->timestamp('started_at');
            $table->timestamp('last_activity_at');
            $table->timestamp('ended_at')->nullable();
            $table->string('entry_page', 500)->nullable();
            $table->timestamps();

            $table->index(['demo_ip_address_id', 'started_at']);
            $table->index('last_activity_at');
        });

        Schema::create('demo_access_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demo_ip_address_id')->constrained()->cascadeOnDelete();
            $table->foreignId('demo_access_session_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('accessed_at');
            $table->string('page', 500);
            $table->string('route_name', 160)->nullable();
            $table->timestamps();

            $table->index(['demo_ip_address_id', 'accessed_at']);
            $table->index(['demo_access_session_id', 'accessed_at'], 'demo_history_session_time_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demo_access_history');
        Schema::dropIfExists('demo_access_sessions');
        Schema::dropIfExists('demo_ip_addresses');
        Schema::dropIfExists('contact_submissions');
    }
};

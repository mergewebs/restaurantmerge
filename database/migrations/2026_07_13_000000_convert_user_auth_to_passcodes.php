<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'passcode')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('passcode')->nullable()->after('name');
            });
        }

        DB::table('users')
            ->whereNull('passcode')
            ->update([
                'passcode' => Hash::make((string) env('ADMIN_DEFAULT_PASSCODE', '123456')),
            ]);

        if (Schema::hasColumn('users', 'email')) {
            if (Schema::getConnection()->getDriverName() === 'sqlite') {
                DB::statement('DROP INDEX IF EXISTS users_email_unique');
            } else {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropUnique('users_email_unique');
                });
            }
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }

            if (Schema::hasColumn('users', 'email')) {
                $table->dropColumn('email');
            }

            if (Schema::hasColumn('users', 'password')) {
                $table->dropColumn('password');
            }
        });

        if (Schema::hasTable('password_reset_tokens')) {
            Schema::drop('password_reset_tokens');
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('passcode')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'email')) {
                $table->string('email')->nullable();
            }

            if (! Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }

            if (! Schema::hasColumn('users', 'password')) {
                $table->string('password')->nullable();
            }
        });

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'passcode')) {
                return;
            }

            $table->dropColumn('passcode');
        });

        if (Schema::hasColumn('users', 'email') && Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('email');
            });
        }
    }
};

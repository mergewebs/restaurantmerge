<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminAccessSeeder extends Seeder
{
    public function run(): void
    {
        $name = trim((string) config('access.admin.name', 'Admin'));
        $passcode = config('access.admin.passcode');

        if (blank($passcode) && ! app()->environment('production')) {
            $passcode = '987654';
        }

        if ($name === '') {
            throw new RuntimeException('SEED_ADMIN_NAME must not be empty.');
        }

        if (! is_string($passcode) || preg_match('/^\d{6}$/', $passcode) !== 1) {
            throw new RuntimeException(
                'SEED_ADMIN_PASSCODE must be set to a 6-digit passcode before seeding production.'
            );
        }

        User::updateOrCreate(
            ['name' => $name],
            [
                'passcode' => $passcode,
                'role' => 'admin',
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['role' => 'demo'],
            [
                'name' => 'Demo User',
                'passcode' => '123456',
                'assigned_area' => 'All Areas',
                'can_discount' => true,
                'can_refund' => true,
                'can_cancel_orders' => true,
            ]
        );
    }
}

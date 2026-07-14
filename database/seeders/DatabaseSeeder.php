<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $seeders = [
            AdminAccessSeeder::class,
            RestaurantScheduleSeeder::class,
            CategorySeeder::class,
            MenuItemSeeder::class,
        ];

        if (! app()->environment('production')) {
            $seeders[] = PresentationDemoSeeder::class;
        }

        $this->call($seeders);
    }
}

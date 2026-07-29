<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Food', 'type' => 'expense'],
            ['name' => 'Beverages', 'type' => 'expense'],
            ['name' => 'Payroll', 'type' => 'expense'],
            ['name' => 'Utilities', 'type' => 'expense'],
            ['name' => 'Rent', 'type' => 'expense'],
            ['name' => 'Supplies', 'type' => 'expense'],
            ['name' => 'Marketing', 'type' => 'expense'],
            ['name' => 'Maintenance', 'type' => 'expense'],
            ['name' => 'Other', 'type' => 'expense'],

            ['name' => 'Sales', 'type' => 'income'],
            ['name' => 'Services', 'type' => 'income'],
            ['name' => 'Investments', 'type' => 'income'],
            ['name' => 'Other', 'type' => 'income'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::firstOrCreate($category);
        }
    }
}

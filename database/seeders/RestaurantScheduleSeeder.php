<?php

namespace Database\Seeders;

use App\Models\RestaurantSchedule;
use Illuminate\Database\Seeder;

class RestaurantScheduleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(0, 6) as $day) {
            RestaurantSchedule::firstOrCreate(
                ['day_of_week' => $day],
                [
                    'is_closed' => in_array($day, [0, 1], true),
                    'open_time_1' => match (true) {
                        in_array($day, [2, 3, 4], true) => '18:00',
                        in_array($day, [5, 6], true) => '13:00',
                        default => null,
                    },
                    'close_time_1' => match (true) {
                        in_array($day, [2, 3, 4], true) => '23:00',
                        in_array($day, [5, 6], true) => '16:30',
                        default => null,
                    },
                    'open_time_2' => in_array($day, [5, 6], true) ? '19:30' : null,
                    'close_time_2' => in_array($day, [5, 6], true) ? '23:30' : null,
                    'open_time_3' => null,
                    'close_time_3' => null,
                    'max_party_size' => 25,
                ]
            );
        }
    }
}

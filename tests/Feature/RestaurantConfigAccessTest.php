<?php

namespace Tests\Feature;

use App\Models\RestaurantSchedule;
use App\Models\User;
use Database\Seeders\RestaurantScheduleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantConfigAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_and_admin_can_see_the_weekly_schedule_in_restaurant_config(): void
    {
        RestaurantSchedule::create([
            'day_of_week' => 2,
            'is_closed' => false,
            'open_time_1' => '18:15',
            'close_time_1' => '22:45',
            'max_party_size' => 31,
        ]);

        foreach (['owner', 'admin'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get(route('restaurant.config'))
                ->assertOk()
                ->assertSee('Weekly Schedule')
                ->assertSee('value="18:15"', false)
                ->assertSee('value="22:45"', false)
                ->assertSee('value="31"', false);
        }
    }

    public function test_owner_can_update_the_restaurant_schedule(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $schedule = RestaurantSchedule::create([
            'day_of_week' => 2,
            'is_closed' => false,
            'open_time_1' => '18:00',
            'close_time_1' => '23:00',
            'max_party_size' => 25,
        ]);

        $this->actingAs($owner)
            ->put(route('restaurant.schedule.update'), [
                'schedules' => [
                    $schedule->id => [
                        'open_time_1' => '17:30',
                        'close_time_1' => '22:30',
                        'open_time_2' => null,
                        'close_time_2' => null,
                        'open_time_3' => null,
                        'close_time_3' => null,
                        'max_party_size' => 30,
                    ],
                ],
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $schedule->refresh();

        $this->assertSame('17:30', substr($schedule->open_time_1, 0, 5));
        $this->assertSame(30, $schedule->max_party_size);
    }
    public function test_schedule_seeder_creates_missing_days_without_overwriting_owner_changes(): void
    {
        $seeder = (new RestaurantScheduleSeeder)->setContainer($this->app);
        $seeder->run();

        $this->assertDatabaseCount('restaurant_schedules', 7);

        $tuesday = RestaurantSchedule::where('day_of_week', 2)->firstOrFail();
        $tuesday->update(['open_time_1' => '17:45']);

        $seeder->run();

        $this->assertSame(
            '17:45',
            substr($tuesday->fresh()->open_time_1, 0, 5)
        );
    }
}

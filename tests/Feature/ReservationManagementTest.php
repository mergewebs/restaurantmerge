<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservations_page_shows_today_calendar_contact_and_guest_details(): void
    {
        $user = User::factory()->create();
        $table = RestaurantTable::create(['number' => 7]);
        Reservation::create([
            'customer_name' => 'Morgan Guest',
            'customer_phone' => '+34 600 111 222',
            'party_size' => 4,
            'reservation_time' => now()->setTime(20, 30),
            'notes' => 'Quiet corner',
            'allergies' => 'Peanuts',
            'restaurant_table_id' => $table->id,
            'status' => 'confirmed',
        ]);

        $this->actingAs($user)->get(route('reservations.index'))
            ->assertOk()
            ->assertSee('Morgan Guest')
            ->assertSee('4 guests')
            ->assertSee('Peanuts')
            ->assertSee('Quiet corner')
            ->assertSee('Table 7')
            ->assertSee('wa.me/34600111222', false)
            ->assertSee(now()->format('F Y'));
    }

    public function test_reservation_status_and_table_can_be_changed_quickly(): void
    {
        $user = User::factory()->create();
        $table = RestaurantTable::create(['number' => 8]);
        $reservation = Reservation::create([
            'customer_name' => 'Taylor Guest',
            'party_size' => 2,
            'reservation_time' => now()->addHour(),
            'status' => 'pending',
        ]);

        $this->actingAs($user)->patch(route('reservations.status.update', $reservation), ['status' => 'arrived'])->assertRedirect();
        $this->actingAs($user)->patch(route('reservations.table.update', $reservation), ['restaurant_table_id' => $table->id])->assertRedirect();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'arrived',
            'restaurant_table_id' => $table->id,
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Mail\NewReservationAdminNotification;
use App\Mail\ReservationConfirmationClient;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReservationEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_created_reservation_sends_both_notifications(): void
    {
        Mail::fake();
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('reservations.store'), [
                'customer_name' => 'Admin Booking',
                'customer_phone' => '600 000 000',
                'customer_email' => 'booking@example.test',
                'party_size' => 2,
                'reservation_time' => '2030-01-01 12:00',
                'notes' => null,
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        Mail::assertSent(NewReservationAdminNotification::class);
        Mail::assertSent(
            ReservationConfirmationClient::class,
            fn (ReservationConfirmationClient $mail) => $mail->reservation->customer_email === 'booking@example.test'
        );
    }

    public function test_confirmation_email_subject_includes_reservation_date_and_time(): void
    {
        $reservation = new Reservation([
            'reservation_time' => '2030-01-02 18:30:00',
        ]);

        $englishSubject = (new ReservationConfirmationClient($reservation, 'en'))->envelope()->subject;
        $spanishSubject = (new ReservationConfirmationClient($reservation, 'es'))->envelope()->subject;

        $this->assertSame('Your reservation is confirmed - 02/01/2030 at 18:30', $englishSubject);
        $this->assertSame('Tu reserva está confirmada - 02/01/2030 a las 18:30', $spanishSubject);
    }

    public function test_reservation_show_route_redirects_to_the_admin_editor(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $reservation = Reservation::create([
            'customer_name' => 'Existing Booking',
            'customer_email' => 'existing@example.test',
            'party_size' => 2,
            'reservation_time' => '2030-01-02 18:30:00',
            'status' => 'confirmed',
            'user_id' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('reservations.show', $reservation))
            ->assertRedirect(route('reservations.edit', $reservation));
    }
}

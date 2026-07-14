<?php

namespace Tests\Feature;

use App\Mail\ReservationConfirmationClient;
use App\Mail\NewReservationAdminNotification;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReservationLocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_page_uses_selected_english_locale_instead_of_browser_locale(): void
    {
        $response = $this
            ->withSession(['locale' => 'en'])
            ->withHeader('Accept-Language', 'es-ES,es;q=0.9')
            ->get(route('reservations.public.create'));

        $response->assertOk();
        $response->assertSee('Confirm reservation');
        $response->assertSee('Find us');
        $response->assertSee('Website created by');
        $response->assertDontSee('Página web creada por');
    }

    public function test_reservation_page_uses_selected_spanish_locale_instead_of_browser_locale(): void
    {
        $response = $this
            ->withSession(['locale' => 'es'])
            ->withHeader('Accept-Language', 'en-US,en;q=0.9')
            ->get(route('reservations.public.create'));

        $response->assertOk();
        $response->assertSee('Confirmar reserva');
        $response->assertSee('Encuéntranos');
        $response->assertSee('Página web creada por');
        $response->assertDontSee('Website created by');
    }

    public function test_confirmation_email_uses_selected_english_locale(): void
    {
        $this->assertConfirmationEmailUsesLocale('en', 'es-ES,es;q=0.9', 'english-locale-token');
    }

    public function test_confirmation_email_uses_selected_spanish_locale(): void
    {
        $this->assertConfirmationEmailUsesLocale('es', 'en-US,en;q=0.9', 'spanish-locale-token');
    }

    public function test_confirmation_email_subject_includes_reservation_date_and_time(): void
    {
        $reservation = new Reservation([
            'reservation_time' => '2030-01-02 18:30:00',
        ]);

        $englishSubject = (new ReservationConfirmationClient($reservation, 'en'))->envelope()->subject;
        $spanishSubject = (new ReservationConfirmationClient($reservation, 'es'))->envelope()->subject;

        $this->assertSame('Your KOYA table is confirmed - 02/01/2030 at 18:30', $englishSubject);
        $this->assertSame('Tu mesa en KOYA está confirmada - 02/01/2030 a las 18:30', $spanishSubject);
    }

    private function assertConfirmationEmailUsesLocale(string $locale, string $browserLanguage, string $token): void
    {
        Mail::fake();

        $response = $this
            ->withSession(['locale' => $locale])
            ->withHeader('Accept-Language', $browserLanguage)
            ->post(route('reservations.public.store'), [
                'customer_name' => 'Locale Test',
                'customer_phone' => '600 000 000',
                'customer_email' => 'locale@example.com',
                'party_size' => 2,
                'reservation_time' => '2030-01-01 12:00',
                'notes' => null,
                'submission_token' => $token,
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        Mail::assertSent(
            ReservationConfirmationClient::class,
            fn (ReservationConfirmationClient $mail) => $mail->locale === $locale
        );

        Mail::assertSent(
            NewReservationAdminNotification::class,
            fn (NewReservationAdminNotification $mail) => $mail->locale === 'en'
        );
    }
}

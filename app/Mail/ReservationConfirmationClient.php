<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Lang;

class ReservationConfirmationClient extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $locale;

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation, $locale = 'es')
    {
        $this->reservation = $reservation;
        $this->locale = $locale ?: 'es';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $dt = \Carbon\Carbon::parse($this->reservation->reservation_time);
        
        return new Envelope(
            subject: __('emails.confirmation.subject', [
                'date' => $dt->format('d/m/Y'),
                'time' => $dt->format('H:i')
            ], $this->locale),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservations.confirmation_client',
            with: [
                'locale' => $this->locale
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

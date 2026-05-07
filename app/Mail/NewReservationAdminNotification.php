<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewReservationAdminNotification extends Mailable
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
            subject: __('emails.admin.subject', [
                'datetime' => $dt->format('d/m/Y H:i')
            ], $this->locale),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservations.admin_notification',
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

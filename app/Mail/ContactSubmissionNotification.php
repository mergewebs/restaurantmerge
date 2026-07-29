<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactSubmissionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactSubmission $submission) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->submission->email, $this->submission->full_name)],
            subject: 'New Merge demo enquiry from '.$this->submission->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-submission');
    }

    public function attachments(): array
    {
        return [];
    }
}

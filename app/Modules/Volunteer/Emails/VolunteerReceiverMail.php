<?php

namespace App\Modules\Volunteer\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VolunteerReceiverMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected array $volunteer)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Volunteer Application Received',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.volunteer.receiver',
            with: [
                'applicant_name' => $this->volunteer['name'],
                'applicant_email' => $this->volunteer['email'],
                'applicant_phone' => $this->volunteer['phone'],
                'applicant_address' => $this->volunteer['address'],
                'applicant_message' => $this->volunteer['message']
            ]
        );
    }
}

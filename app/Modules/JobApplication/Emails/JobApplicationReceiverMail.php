<?php

namespace App\Modules\JobApplication\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobApplicationReceiverMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected array $application, protected UploadedFile $resume, protected object $career)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Job Application Submitted for " . $this->career->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.jobApplication.receiver',
            with: [
                'job_title' => $this->career->title,
                'applicant_name' => $this->application['name'],
                'applicant_email' => $this->application['email'],
                'applicant_phone' => $this->application['phone'],
                'applicant_address' => $this->application['address'],
                'applicant_cover_letter' => $this->application['coverLetter']
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
        return [
            Attachment::fromData(fn () => $this->resume->getContent(), $this->resume->getClientOriginalName())
                ->withMime('application/pdf'),
        ];
    }
}

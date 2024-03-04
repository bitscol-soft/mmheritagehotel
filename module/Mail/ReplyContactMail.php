<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ReplyContactMail extends Mailable
{
    use Queueable, SerializesModels;
    public $replymailData;
    /**
     * Create a new message instance.
     */
    public function __construct($replymailData)
    {
        // protected MailData $mailData,
        $this->replymailData = $replymailData;
    }

    /**
     * Get the message envelope.
    */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('supoport@mmheritagehotel.com', 'Reply mail from MM Heritage Hotel'),
            tags: ['MM Heritage Hotel reply contact mail'],
            subject: 'Reply Contact mail from MM Heritage Hotel',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.reply-contact-mail',
        );
    }
}

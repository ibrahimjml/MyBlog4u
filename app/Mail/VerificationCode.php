<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationCode extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $code;
    public function __construct(User $user,$code)
    {
      logger('VerificationCode MAIL CONSTRUCTOR RUNNING');
        $this->user = $user;
        $this->code = $code;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Your Identity',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
       logger('VerificationCode CONTENT RUNNING');
        return new Content(
            markdown: 'email.verificationcode',
            with:[
              'user'=>$this->user,
              'code'=>$this->code,
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

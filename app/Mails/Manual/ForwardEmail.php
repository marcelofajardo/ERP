<?php

namespace App\Mails\Manual;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForwardEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $forwardEmail;
    private $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($forwardEmail, $message)
    {
        $this->forwardEmail = $forwardEmail;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $replyPrefix = 'Re: ';
        $forwardPrefix = 'Fwd: ';
        $subject = $this->forwardEmail->subject;

        if (substr($subject, 0, 4) === $replyPrefix) {
            $subject = substr($subject, 4);
        }

        if (substr($subject, 0, 5) !== $forwardPrefix) {
            $subject = $forwardPrefix . $subject;
        }

        $this->from($this->forwardEmail->from);
        $this->subject($subject);

        $dateCreated = $this->forwardEmail->created_at->format('D, d M Y');
        $timeCreated = $this->forwardEmail->created_at->format('H:i');

        return $this->view('emails.forward-email', [
            'msg' => $this->message,
            'forwardEmail' => $this->forwardEmail,
            'dateCreated' => $dateCreated,
            'timeCreated' => $timeCreated,
        ]);
    }

}

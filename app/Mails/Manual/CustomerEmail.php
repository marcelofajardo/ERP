<?php

namespace App\Mails\Manual;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

	public $subject;
    public $message;
    public $fromEmail;

    public function __construct(string $subject, string $message, string $fromStoreEmail)
    {
      $this->subject = $subject;
      $this->message = $message;
      $this->fromEmail = $fromStoreEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->fromEmail)
                    ->bcc('customercare@sololuxury.co.in')
                    ->subject($this->subject)
                    ->markdown('emails.customers.email');
    }
}

<?php

namespace App\Mails\Manual;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactBlogger extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $subject;
    public $message;

    public function __construct(string $subject, string $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->withSwiftMessage(function ($swiftmessage) {
            Log::channel('customer')->info($swiftmessage->getId());
         });
        $this->from('contact@sololuxury.co.in')
            ->bcc('contact@sololuxury.co.in')
            ->subject($this->subject)
            ->markdown('emails.customers.email');
    }
}

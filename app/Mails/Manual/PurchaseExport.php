<?php

namespace App\Mails\Manual;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PurchaseExport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $path;
    public $subject;
    public $message;

    public function __construct(string $path, string $subject, string $message)
    {
      $this->path = $path;
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
      return $this
                  ->bcc('customercare@sololuxury.co.in')
                  ->subject($this->subject)
                  ->text('emails.purchases.export_plain')->with(['body_message' => $this->message])
                  ->attachFromStorageDisk('files', $this->path);
    }
}

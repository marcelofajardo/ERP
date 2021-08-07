<?php

namespace App\Mails\Manual;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class HourlyReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $path;

    public function __construct($path)
    {
      $this->path = $path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->from('contact@sololuxury.co.in')
                  ->bcc('customercare@sololuxury.co.in')
                  ->subject('Generated Hourly Report')
                  ->markdown('emails.hourly-report')
                  ->attachFromStorageDisk('files', $this->path);
    }
}

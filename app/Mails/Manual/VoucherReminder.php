<?php

namespace App\Mails\Manual;

use App\Voucher;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VoucherReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $voucher;

    public function __construct(Voucher $voucher)
    {
      $this->voucher = $voucher;
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
                  ->subject('Voucher Reminder')
                  ->markdown('emails.vouchers.reminder');
    }
}

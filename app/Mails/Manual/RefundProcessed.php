<?php

namespace App\Mails\Manual;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RefundProcessed extends Mailable
{
    use Queueable, SerializesModels;

    public $order_id;
    public $product_names;

    public function __construct(string $order_id, string $product_names)
    {
      $this->order_id = $order_id;
      $this->product_names = $product_names;
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
                    ->subject('Refund Processed')
                    ->markdown('emails.orders.refund');
    }
}

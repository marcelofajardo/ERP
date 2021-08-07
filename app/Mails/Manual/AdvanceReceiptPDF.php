<?php

namespace App\Mails\Manual;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Order;

class AdvanceReceiptPDF extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $product_names = '';
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
      $this->order = $order;

      $count = count($order->order_product);
      foreach ($order->order_product as $key => $order_product) {
        if ((($count - 1) == $key) && $key != 0) {
          $this->product_names .= ' and ' . $order_product->product->name;
        } elseif (((($count - 1) == $key) && $key == 0) || ((($count - 1) != $key) && $key == 0)) {
          $this->product_names .= $order_product->product->name;
        } else {
          $this->product_names .= ', ' . $order_product->product->name;
        }
      }
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
                    ->markdown('emails.orders.advance-receipt-pdf');
    }
}

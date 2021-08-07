<?php

namespace App\Mails\Manual;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->fromMailer = 'customercare@sololuxury.co.in';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject        = "New Order # " . $this->order->order_id;
        $order          = $this->order;
        $customer       = $order->customer;
        $order_products = $order->order_products;
        
        $this->subject  = $subject;
        $this->fromMailer = "customercare@sololuxury.co.in";

        // check this order is related to store website ?
        $storeWebsiteOrder = $order->storeWebsiteOrder;
        
        // get the template based on store
        if ($storeWebsiteOrder) {
            $emailAddress = \App\EmailAddress::where('store_website_id',$storeWebsiteOrder->website_id)->first();
            if($emailAddress) {
                $this->fromMailer = $emailAddress->from_address;
            }
            $template = \App\MailinglistTemplate::getOrderConfirmationTemplate($storeWebsiteOrder->website_id);
        } else {
            $template = \App\MailinglistTemplate::getOrderConfirmationTemplate();
        }

        if ($template) {
            if (!empty($template->mail_tpl)) {
                // need to fix the all email address
                return $this->from($this->fromMailer)
                    ->subject($template->subject)
                    ->view($template->mail_tpl, compact(
                        'order', 'customer', 'order_products'
                    ));
            } else {
                $content = $template->static_template;
                return $this->from($this->fromMailer)
                    ->subject($template->subject)
                    ->view('emails.blank_content', compact(
                        'order', 'customer', 'order_products', 'content'
                    ));
            }
        }
        
        if(!$storeWebsiteOrder) {
            return $this->view('emails.orders.confirmed-solo', compact(
                'order', 'customer', 'order_products'
            ));
        }
    }
}

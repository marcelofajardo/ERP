<?php

namespace App\Mails\Manual;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCancellationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->order = $data;
        $this->fromMailer = 'customercare@sololuxury.co.in';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject        = "Order # " . $this->order->order_id ." has been cancelled";
        $order          = $this->order;
        
        $customer       = $order->customer;
        $order_products = $order->order_products;
        $email          = "customercare@sololuxury.co.in";

        $content        = "Your order request has been cancelled";

        $this->subject  = $subject;
        $this->fromMailer = "customercare@sololuxury.co.in";


        // check this order is related to store website ?
        $storeWebsiteOrder = $order->storeWebsiteOrder;

        if ($storeWebsiteOrder) {
            $emailAddress = \App\EmailAddress::where('store_website_id',$storeWebsiteOrder->website_id)->first();
            if($emailAddress) {
                $this->fromMailer = $emailAddress->from_address;
            }
            $template = \App\MailinglistTemplate::getOrderCancellationTemplate($storeWebsiteOrder->website_id);
        } else {
            $template = \App\MailinglistTemplate::getOrderCancellationTemplate();
        }

        if ($template) {
            $this->subject = $template->subject;
            if (!empty($template->mail_tpl)) {
                // need to fix the all email address
                return $this->from($email)
                    ->subject($this->subject)
                    ->view($template->mail_tpl, compact(
                        'order', 'customer', 'order_products'
                    ));
            } else {

                $content = str_replace([
                    '{FIRST_NAME}','{ORDER_STATUS}','{ORDER_ID}'],
                    [$order->customer->name,$order->order_status,$order->order_id],
                    $template->static_template
                );
            }
        }

        return $this->from($email)
        ->subject($this->subject)
        ->view('emails.blank_content', compact(
            'order', 'customer', 'order_products', 'content'
        ));
    }
}

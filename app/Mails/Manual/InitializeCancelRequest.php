<?php

namespace App\Mails\Manual;

use App\Customer;
use App\ReturnExchange;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InitializeCancelRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $return;

    public function __construct(ReturnExchange $return)
    {
        $this->return = $return;
        $this->fromMailer = 'customercare@sololuxury.co.in';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $subject    = "Cancellation Request Initialized";
        $return     = $this->return;
        $customer   = $return->customer;

        $this->subject  = $subject;
        $this->fromMailer = "customercare@sololuxury.co.in";

        if ($customer) {
            if ($customer->store_website_id > 0) {
                $emailAddress = \App\EmailAddress::where('store_website_id',$customer->store_website_id)->first();
                if($emailAddress) {
                    $this->fromMailer = $emailAddress->from_address;
                }
                $template = \App\MailinglistTemplate::getIntializeCancellation($customer->store_website_id);
            } else {
                $template = \App\MailinglistTemplate::getIntializeCancellation();
            }
            if ($template) {
                if (!empty($template->mail_tpl)) {
                    // need to fix the all email address
                    $this->subject  = $template->subject;
                    return $this->subject($this->subject)
                        ->view($template->mail_tpl, compact(
                            'customer','return'
                        ));
                }
            }
        }

        return $this->subject($this->subject)->markdown('emails.customers.blank');
    }
}

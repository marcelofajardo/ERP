<?php

namespace App\Mails\Manual;

use App\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class IssueCredit extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $customer;

    public function __construct(Customer $customer)
    {
      $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->from('customercare@sololuxury.co.in')
                  ->bcc('customercare@sololuxury.co.in')
                  ->subject("Customer Credit Issued")
                  ->markdown('emails.customers.issue-credit');
    }
}

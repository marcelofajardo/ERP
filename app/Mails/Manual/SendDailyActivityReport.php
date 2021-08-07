<?php

namespace App\Mails\Manual;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDailyActivityReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public $user;
     public $time_slots;

     public function __construct(User $user, array $time_slots)
     {
       $this->user = $user;
       $this->time_slots = $time_slots;
     }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('contact@sololuxury.co.in')
                    // ->bcc('customercare@sololuxury.co.in')
                    ->subject('Daily Planner Report')
                    ->markdown('emails.daily-activity-report');
    }
}

<?php

namespace App\Listeners;

use App\Events\BloggerPaymentCreated;

class CreateBloggerCashFlow
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(BloggerPaymentCreated $event)
    {
        $blogger = $event->blogger;
        $payment = $event->payment;
        $status = $event->status;
        $user_id = auth()->id();
        $cash_flow = $blogger->cashFlows()->where('order_status','payment_id:'.$payment->id)->first();
        if(!$cash_flow){
            $cash_flow = $blogger->cashFlows()->create([
                'user_id' => $user_id,
            ]);
        }
        $cash_flow->fill([
            'date' => $payment->paid_date ?: $payment->payment_date,
            'expected' => $payment->payable_amount,
            'actual' => $payment->paid_amount,
            'type' => 'paid',
            'currency' => $payment->currency,
            'status' => $status,
            'order_status' => 'payment_id:'.$payment->id,//to know which of the payment's record while updating later
            'updated_by' => $user_id,
            'description' => 'Blogger Payment '. ($status ? 'Paid' : 'Due'),
        ])->save();

    }
}

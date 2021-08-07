<?php

namespace App\Listeners;

use App\Events\CaseBilled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateCaseCashFlow
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
    public function handle(CaseBilled $event)
    {
        $case = $event->case;
        $bill = $event->bill;
        $user_id = auth()->id();
        $case->cashFlows()->create([
            'date' => $bill->paid_date ?: $bill->billed_date,
            'expected' => $bill->amount,
            'actual' => $bill->amount_paid ?: 0 ,
            'type' => 'paid',
            'currency' => '',
            'status' => ($bill->paid_date && $bill->amount_paid) ? 1 : 0,
            'order_status' => 'bill_id:'.$bill->id,//to know which of the payment's record while updating later
            'user_id' => $user_id,
            'updated_by' => $user_id,
            'description' => 'Case Cost Billed',
        ]);
    }
}

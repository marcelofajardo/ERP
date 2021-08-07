<?php

namespace App\Listeners;

use App\Events\CaseBillPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateCaseCashFlow
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
    public function handle(CaseBillPaid $event)
    {
        $case = $event->case;
        $bill = $event->bill;
        $user_id = auth()->id();
        $cash_flow = $case->cashFlows()->where('order_status','bill_id:'.$bill->id)->first();
        if(!$cash_flow){
            $cash_flow = $case->cashFlows()->create([
                'user_id' => $user_id,
            ]);
        }
        $cash_flow->fill([
            'date' => $bill->paid_date ?: $bill->billed_date,
            'expected' => $bill->amount,
            'actual' => $bill->amount_paid ?: 0 ,
            'type' => 'paid',
            'currency' => '',
            'status' => ($bill->paid_date && $bill->amount_paid) ? 1 : 0,
            'order_status' => 'bill_id:'.$bill->id,//to know which of the payment's record while updating later
            'updated_by' => $user_id,
            'description' => 'Case Cost Billed and Paid',
        ])->save();
    }
}

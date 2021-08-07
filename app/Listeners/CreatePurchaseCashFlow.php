<?php

namespace App\Listeners;

use App\Events\ProformaConfirmed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreatePurchaseCashFlow
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
    public function handle(ProformaConfirmed $event)
    {
        $purchase = $event->purchase;
        $purchase_amount = $event->purchase_amount;
        $cash_flow = $purchase->cashFlows()->first();
        $user_id = auth()->id();
        if(!$cash_flow){
            $cash_flow = $purchase->cashFlows()->create([
                'user_id' => $user_id,
            ]);
        }
        $cash_flow->fill([
            'date' => $purchase->proforma_date,
            'expected' => $purchase_amount,
            'actual' => $purchase_amount,
            'type' => 'paid',
            'currency' => '',
            'status' => 1,
            'order_status' => '',
            'updated_by' => $user_id,
            'description' => 'Purchase proforma confirmed. Proforma id '.$purchase->proforma_id,
        ])->save();
    }
}

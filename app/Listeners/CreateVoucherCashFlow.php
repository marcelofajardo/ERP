<?php

namespace App\Listeners;

use App\Events\VoucherApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateVoucherCashFlow
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
    public function handle(VoucherApproved $event)
    {
        $voucher = $event->voucher;
        $user_id = auth()->id();
        $cash_flow = $voucher->cashFlows()->first();
        if(!$cash_flow){
            $cash_flow = $voucher->cashFlows()->create([
                'user_id' => $user_id,
            ]);
        }
        $cash_flow->fill([
            'date' => $voucher->date,
            'expected' => $voucher->amount,
            'actual' => $voucher->paid,
            'type' => 'paid',
            'currency' => 1,
            'status' => 1,
            'order_status' => '',
            'updated_by' => $user_id,
            'description' => '',
        ])->save();

    }
}

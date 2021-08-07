<?php

namespace App\Listeners;

use App\Events\RefundCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateRefundCashFlow
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
     * @param  RefundCreated  $event
     * @return void
     */
    public function handle(RefundCreated $event)
    {
        $refund = $event->refund;
        $user_id = auth()->id();
        $refund->cashFlows()->create([
            'date' => $refund->date_of_issue,
            'expected' => optional(optional($refund->order)->customer)->credit ?: 0,
            'actual' => 0,
            'type' => 'paid',
            'currency' => '',
            'status' => 0,
            'order_status' => 'Refund to be processed',
            'user_id' => $user_id,
            'updated_by' => $user_id,
            'description' => 'Refund to be processed ',
        ]);
    }
}

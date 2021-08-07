<?php

namespace App\Listeners;

use App\Events\CaseReceivableCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateCaseReceivableCashFlow
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
    public function handle(CaseReceivableCreated $event)
    {
        $case = $event->case;
        $receivable = $event->receivable;
        $status = $event->status;
        $user_id = auth()->id();
        $cash_flow = $case->cashFlows()->where('order_status','receivable_id:'.$receivable->id)->first();
        if(!$cash_flow){
            $cash_flow = $case->cashFlows()->create([
                'user_id' => $user_id,
            ]);
        }
        $cash_flow->fill([
            'date' => $receivable->received_date ?: $receivable->receivable_date,
            'expected' => $receivable->receivable_amount,
            'actual' => $receivable->received_amount,
            'type' => 'received',
            'currency' => $receivable->currency,
            'status' => $status,
            'order_status' => 'receivable_id:'.$receivable->id,//to know which of the receivable's record while updating later
            'updated_by' => $user_id,
            'description' => 'Case Receivable '. ($status ? 'Received' : 'Due'),
        ])->save();
    }
}

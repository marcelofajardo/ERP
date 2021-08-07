<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateReturnStatusMessageTpl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    private $returnId;
    private $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($returnId, $message = NULL)
    {
        $this->returnId = $returnId;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $return = \App\ReturnExchange::where('id', $this->returnId)->first();
        if ($return) {
            $statusModal       = \App\ReturnExchangeStatus::where("id", $return->status)->first();
            // $defaultMessageTpl = \App\Order::ORDER_STATUS_TEMPLATE;
            // if ($statusModal && !empty($statusModal->message_text_tpl)) {
            //     $defaultMessageTpl = $statusModal->message_text_tpl;
            // }
            if(!$this->message || $this->message == "") {
                $defaultMessageTpl = \App\ReturnExchangeStatus::STATUS_TEMPLATE;
                if ($statusModal && !empty($statusModal->message)) {
                    $defaultMessageTpl = $statusModal->message;
                }
                $msg = str_replace(["#{id}", "#{status}"], [$return->id, $statusModal->status_name], $defaultMessageTpl);
            }
            else {
                $msg = $this->message;
            }
            // start update the order status
            $requestData = new Request();
            $requestData->setMethod('POST');
            $requestData->request->add([
                'customer_id' => $return->customer_id,
                'message'     => $msg,
                'status'      => 0
            ]);

            app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'customer');
        }

    }
}

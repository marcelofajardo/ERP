<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateOrderStatusMessageTpl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    private $orderId;
    private $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderId, $message = NULL)
    {
        $this->orderId = $orderId;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = \App\Order::where('id', $this->orderId)->first();
        if ($order) {
            $statusModal       = \App\OrderStatus::where("id", $order->order_status_id)->first();
            // $defaultMessageTpl = \App\Order::ORDER_STATUS_TEMPLATE;
            // if ($statusModal && !empty($statusModal->message_text_tpl)) {
            //     $defaultMessageTpl = $statusModal->message_text_tpl;
            // }
            if(!$this->message || $this->message == "") {
                $msg = \App\Order::ORDER_STATUS_TEMPLATE;
                if ($statusModal && !empty($statusModal->message_text_tpl)) {
                    $msg = $statusModal->message_text_tpl;
                }
                if($statusModal && !empty($statusModal->status)) {
                    $msg = str_replace(["#{order_id}", "#{order_status}"], [$order->order_id, $statusModal->status], $msg);
                }
            }
            else {
                $defaultMessageTpl = $this->message; 
                $msg = $this->message;
            }
            // start update the order status
            $requestData = new Request();
            $requestData->setMethod('POST');
            $requestData->request->add([
                'customer_id' => $order->customer_id,
                'message'     => $msg,
                'status'      => 0,
                'order_id'    => $order->id,
            ]);

            app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'customer');
        }

    }
}

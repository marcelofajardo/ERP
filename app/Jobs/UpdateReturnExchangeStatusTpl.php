<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Http\Request;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateReturnExchangeStatusTpl implements ShouldQueue
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
    public function __construct($returnId, $message)
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
			$product 	 = \App\ReturnExchangeProduct::where("return_exchange_id", $this->returnId)->first();   
			
			$msg = $this->message;
			
            // start update the order status
            $requestData = new Request();
            $requestData->setMethod('POST');
            $requestData->request->add([
                'customer_id' => $return->customer_id,
                'message'     => $msg,
                'status'      => 0,
                'order_id'    => $product->order_product_id,
            ]);
            app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'customer');
        }
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Email;
use App\Customer;
use App\Order;
use App\OrderProduct;
use App\ReturnExchangeProduct;
use App\ReturnExchange;
use App\StoreWebsiteOrder;
use App\AutoReply;
use Illuminate\Validation\Rule;


class BuyBackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @SWG\Post(
     *   path="/return-exchange-buyback/create",
     *   tags={"Orders"},
     *   summary="Create return exchange buyback",
     *   operationId="create-return-exchange-buy-back",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validationsarr = [
            'order_id' => 'required',
            'website' => 'required',
            'type' => 'required|in:refund,exchange,buyback,return,cancellation'
        ];

        //if order type is not cancellation the add validation for product sku
        if($request->type != "cancellation") {
            $validationsarr['product_sku'] = 'required|exists:order_products,sku';
        }

        $validator = Validator::make($request->all(), $validationsarr);

        if ($validator->fails()) {
            $message = $this->generate_erp_response("$request->type.failed.validation",0, $default = 'Please check validation errors !', request('lang_code'));
            return response()->json(['status' => 'failed', 'message' => $message, 'errors' => $validator->errors()], 400);
        }


        $storeWebsite = \App\StoreWebsite::where("website","like",$request->website)->first();
        $skus = [];
        if($storeWebsite) {
            if($request->type == "cancellation") {
                $storewebisteOrder = StoreWebsiteOrder::where('platform_order_id', $request->order_id)->where("website_id",$storeWebsite->id)->first();
                if($storewebisteOrder) {
                    $skus = \App\OrderProduct::where("order_id",$storewebisteOrder->order_id)->get()->pluck("sku")->toArray();
                    $order = \App\Order::find($storewebisteOrder->order_id);
                    if($order) {
                        $order->order_status = 'Cancel';
                        $order->order_status_id = 11;
                        $order->save();
                        $storewebisteOrder->status_id = 11;
                        $storewebisteOrder->save();
                    }
                }
            }else{
                $skus[] = $request->product_sku;
            }

            $isSuccess = false;

            if(!empty($skus)) {
                foreach($skus as $sk) {
                    $getCustomerOrderData = StoreWebsiteOrder::Where('platform_order_id', $request->order_id)
                        ->where('op.sku', $sk)->where('store_website_orders.website_id', $storeWebsite->id)
                        ->join('orders as od', 'od.id', 'store_website_orders.order_id')
                        ->join('order_products as op', 'op.order_id', 'od.id')
                        ->join('products as p', 'p.id', 'op.product_id')
                        ->select('p.name as product_name', 'op.product_price', 'op.sku', 'op.order_id','op.id as order_product_id', 'op.product_id','od.customer_id')
                        ->first();

                    if (!isset($getCustomerOrderData) || empty($getCustomerOrderData)) {
                        continue;

                        $message = $this->generate_erp_response("$request->type.failed.no_order_found",0, $default = 'No order found for the customer', request('lang_code'));
                        return response()->json(['status' => 'failed', 'message' => $message], 404);
                    }
                    
                    $return_exchange_products_data = [
                        "status_id" => 1, //Return request received from customer 
                        "product_id" => $getCustomerOrderData->product_id,
                        "order_product_id" => $getCustomerOrderData->order_product_id,
                        "name" => $getCustomerOrderData->product_name
                    ];
                    $return_exchanges_data = [
                        'customer_id' => $getCustomerOrderData->customer_id,
                        'website_id' => $storeWebsite->id,
                        'type' => $request->type,
                        'reason_for_refund' => $request->get('reason',''.ucwords($request->type).' of product from '.$storeWebsite->website),
                        'refund_amount' => $getCustomerOrderData->product_price,
                        'status' => 1,
                        'date_of_request' => date('Y-m-d H:i:s')
                    ];
                    $success = ReturnExchange::create($return_exchanges_data);
                    
                    if (!$success) {
                        $message = $this->generate_erp_response("$request->type.failed",$storeWebsite->id, $default = 'Unable to create '.ucwords($request->type).' request!', request('lang_code'));
                        return response()->json(['status' => 'failed', 'message' => $message], 500);
                    }
                    $return_exchange_products_data['return_exchange_id'] = $success->id;
                    $isSuccess = true;
                    ReturnExchangeProduct::create($return_exchange_products_data);

                    // send emails 
                    if($request->type == "refund") {
                        
                        $emailClass = (new \App\Mails\Manual\InitializeRefundRequest($success))->build();

                        $email             = Email::create([
                            'model_id'         => $success->id,
                            'model_type'       => \App\ReturnExchange::class,
                            'from'             => $emailClass->fromMailer,
                            'to'               => $success->customer->email,
                            'subject'          => $emailClass->subject,
                            'message'          => $emailClass->render(),
                            'template'         => 'refund-request',
                            'additional_data'  => $success->id,
                            'status'           => 'pre-send',
                            'store_website_id' => null,
                            'is_draft'         => 1
                        ]);

                        \App\Jobs\SendEmail::dispatch($email);

                         // start a request to send message for refund 
                         $auto_reply = AutoReply::where('type', 'auto-reply')->where('keyword', 'order-refund')->first();
                         if($auto_reply) {
                             $auto_message = preg_replace("/{order_id}/i", $getCustomerOrderData->order_id, $auto_reply->reply); 
                             $auto_message = preg_replace("/{product_names}/i", $getCustomerOrderData->product_name, $auto_message); 
                             $requestData = new Request(); 
                             $requestData->setMethod('POST'); 
                             $requestData->request->add(['customer_id' => $getCustomerOrderData->customer_id, 'message' => $auto_message, 'status' => 1]); 
                             app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'customer');
                         }

                    }else if ($request->type == "return") {
                        
                        $emailClass = (new \App\Mails\Manual\InitializeReturnRequest($success))->build();

                        $email             = Email::create([
                            'model_id'         => $success->id,
                            'model_type'       => \App\ReturnExchange::class,
                            'from'             => $emailClass->fromMailer,
                            'to'               => $success->customer->email,
                            'subject'          => $emailClass->subject,
                            'message'          => $emailClass->render(),
                            'template'         => 'return-request',
                            'additional_data'  => $success->id,
                            'status'           => 'pre-send',
                            'store_website_id' => null,
                            'is_draft'         => 1
                        ]);

                        \App\Jobs\SendEmail::dispatch($email);

                        // start a request to send message for refund 
                         $auto_reply = AutoReply::where('type', 'auto-reply')->where('keyword', 'order-return')->first();
                         if($auto_reply) {
                             $auto_message = preg_replace("/{order_id}/i", $getCustomerOrderData->order_id, $auto_reply->reply); 
                             $auto_message = preg_replace("/{product_names}/i", $getCustomerOrderData->product_name, $auto_message); 
                             $requestData = new Request(); 
                             $requestData->setMethod('POST'); 
                             $requestData->request->add(['customer_id' => $getCustomerOrderData->customer_id, 'message' => $auto_message, 'status' => 1]); 
                             app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'customer');
                         }

                    }else if ($request->type == "exchange") {
                        
                        $emailClass = (new \App\Mails\Manual\InitializeExchangeRequest($success))->build();

                        $email             = Email::create([
                            'model_id'         => $success->id,
                            'model_type'       => \App\ReturnExchange::class,
                            'from'             => $emailClass->fromMailer,
                            'to'               => $success->customer->email,
                            'subject'          => $emailClass->subject,
                            'message'          => $emailClass->render(),
                            'template'         => 'exchange-request',
                            'additional_data'  => $success->id,
                            'status'           => 'pre-send',
                            'store_website_id' => null,
                            'is_draft'         => 1
                        ]);

                        \App\Jobs\SendEmail::dispatch($email);

                        // start a request to send message for refund 
                         $auto_reply = AutoReply::where('type', 'auto-reply')->where('keyword', 'order-exchange')->first();
                         if($auto_reply) {
                             $auto_message = preg_replace("/{order_id}/i", $getCustomerOrderData->order_id, $auto_reply->reply); 
                             $auto_message = preg_replace("/{product_names}/i", $getCustomerOrderData->product_name, $auto_message); 
                             $requestData = new Request(); 
                             $requestData->setMethod('POST'); 
                             $requestData->request->add(['customer_id' => $getCustomerOrderData->customer_id, 'message' => $auto_message, 'status' => 1]); 
                             app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'customer');
                         }

                    }else if ($request->type == "cancellation") {
                        
                        $emailClass = (new \App\Mails\Manual\InitializeCancelRequest($success))->build();

                        $email             = Email::create([
                            'model_id'         => $success->id,
                            'model_type'       => \App\ReturnExchange::class,
                            'from'             => $emailClass->fromMailer,
                            'to'               => $success->customer->email,
                            'subject'          => $emailClass->subject,
                            'message'          => $emailClass->render(),
                            'template'         => 'cancellation',
                            'additional_data'  => $success->id,
                            'status'           => 'pre-send',
                            'store_website_id' => null,
                            'is_draft'         => 1
                        ]);

                        \App\Jobs\SendEmail::dispatch($email);

                        // start a request to send message for refund 
                         $auto_reply = AutoReply::where('type', 'auto-reply')->where('keyword', 'order-cancellation')->first();
                         if($auto_reply) {
                             $auto_message = preg_replace("/{order_id}/i", $getCustomerOrderData->order_id, $auto_reply->reply); 
                             $auto_message = preg_replace("/{product_names}/i", $getCustomerOrderData->product_name, $auto_message); 
                             $requestData = new Request(); 
                             $requestData->setMethod('POST'); 
                             $requestData->request->add(['customer_id' => $getCustomerOrderData->customer_id, 'message' => $auto_message, 'status' => 1]); 
                             app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'customer');
                         }

                    }
                }
            }

            if($isSuccess) {

                $message = $this->generate_erp_response("$request->type.success",$storeWebsite->id, $default = ucwords($request->type).' request created successfully', request('lang_code'));
                return response()->json(['status' => 'success', 'message' => $message], 200);

            }else{
                $message = $this->generate_erp_response("$request->type.failed.no_order_found",$storeWebsite->id, $default = 'No order found for the customer', request('lang_code'));
                return response()->json(['status' => 'failed', 'message' => $message], 404);
            }


        }else{
            return response()->json(['status' => 'failed', 'message' => 'Please check website is not exist'], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @SWG\Get(
     *   path="/orders/products",
     *   tags={"Orders"},
     *   summary="Check product for buyback",
     *   operationId="check-product-for-buy-back",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    
    public function checkProductsForBuyback(request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_email' => 'required|email',
            'website' => 'required'
        ]);
        if ($validator->fails()) {
            $message = $this->generate_erp_response("buyback.failed.validation",0, $default = "Please check validation errors !", request('lang_code'));
            return response()->json(['status' => 'failed', 'message' => $message, 'errors' => $validator->errors()], 400);
        }

        $responseData = [];

        $storeWebsite = \App\StoreWebsite::where("website","like",$request->website)->first();
        if($storeWebsite) {
            $checkCustomer = Customer::where('email', $request->customer_email)
            ->where('store_website_id',$storeWebsite->id)
            ->first();

            if (!$checkCustomer) {
                $message = $this->generate_erp_response("buyback.failed",$storeWebsite->id, $default = "Customer not found with this email !", request('lang_code'));
                return response()->json(['status' => 'failed', 'message' => $message], 404);
            }

            $customer_id = $checkCustomer->id;
            $getCustomerOrderData = Order::Where('customer_id', $customer_id)->where('swo.website_id',$storeWebsite->id)
                ->join('order_products as op', 'op.order_id', 'orders.id')
                ->join('products as p', 'p.id', 'op.product_id')
                ->join('store_website_orders as swo', 'swo.order_id', 'op.order_id');
                
            if($request->order_id !=  null) {
               $getCustomerOrderData = $getCustomerOrderData->where("swo.platform_order_id",$request->order_id);
            }

            $getCustomerOrderData = $getCustomerOrderData->select('p.name as product_name', 'op.product_price', 'op.sku',"op.id as order_product_id", 'op.product_id','swo.platform_order_id as order_id')
                ->get()->makeHidden(['action']);

            if (count($getCustomerOrderData) == 0) {
                $message = $this->generate_erp_response("buyback.failed.no_order_found",0, $default = "No order found for the customer!", request('lang_code'));
                return response()->json(['status' => 'failed', 'message' => $message], 404);
            }
            $responseData = [];
            foreach($getCustomerOrderData as $getCustomerOrder){
                $responseData[$getCustomerOrder->order_id][] = $getCustomerOrder;
            }
        }

        return response()->json(['status' => 'success', 'orders' => $responseData], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Customer;
use App\EmailAddress;
use App\Events\RefundDispatched;
use App\Jobs\UpdateReturnStatusMessageTpl;
use App\MailinglistTemplate;
use App\MailinglistTemplateCategory;
use App\Order;
use App\Product;
use App\Reply;
use App\ReturnExchange;
use App\ReturnExchangeHistory;
use App\ReturnExchangeStatus;
use App\Email;
use App\AutoReply;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCouponRequest;
use Dompdf\Dompdf;
use Qoraiche\MailEclipse\MailEclipse;


class ReturnExchangeController extends Controller
{
    public function getOrders($id)
    {
        if (!empty($id)) {
            $customer  = Customer::find($id);
            $orderData = [];

            if (!empty($customer)) {
                $orders = $customer->orders;
                if (!empty($orders)) {
                    foreach ($orders as $order) {
                        $orderProducts = $order->order_product;

                        if (!empty($orderProducts)) {
                            foreach ($orderProducts as $orderProduct) {
                                $orderData[] = ['id' => $orderProduct->id];
                            }
                        }
                    }
                }
            }
        }

        $status   = ReturnExchangeStatus::pluck('status_name', 'id');
        $response = (string) view("partials.return-exchange", compact('id', 'orderData', 'status'));

        return response()->json(["code" => 200, "html" => $response]);
    }

    /**
     * save the exchange result
     * @param Request
     * @param $id
     *
     **/
    public function save(Request $request, $id)
    {
        $params    = $request->all();
        $sendEmail = $params['send_email'];
        unset($params['send_email']);
        $returnExchange = \App\ReturnExchange::create($params);

        if ($returnExchange) {

            // check if the order has been setup
            if (!empty($params["order_product_id"])) {
                $orderProduct = \App\OrderProduct::find($params["order_product_id"]);
                if (!empty($orderProduct) && !empty($orderProduct->product)) {
                    $product = $orderProduct->product;
                }
            }

            // check if the product id is not stroed with order produc then
            // check with product id
            if (empty($product)) {
                $product = \App\Product::find($params["product_id"]);
            }

            if (!empty($product)) {
                $returnExchangeProduct                     = new \App\ReturnExchangeProduct;
                $returnExchangeProduct->product_id         = $product->id;
                $returnExchangeProduct->order_product_id   = $params["order_product_id"];
                $returnExchangeProduct->name               = $product->name;
                $returnExchangeProduct->return_exchange_id = $returnExchange->id;
                $returnExchangeProduct->save();
            }
            // once return exchange created send message if request is for the return
            $returnExchange->notifyToUser();
            $returnExchange->updateHistory();
            if ($request->type == "refund") {
                // start a request to send message for refund 
                 $auto_reply = AutoReply::where('type', 'auto-reply')->where('keyword', 'order-refund')->first();
                 if($auto_reply) {
                     $auto_message = preg_replace("/{order_id}/i", !empty($orderProduct) ? $orderProduct->order_id : "N/A", $auto_reply->reply); 
                     $auto_message = preg_replace("/{product_names}/i", !empty($product) ? $product->name : "N/A", $auto_message); 
                     $requestData = new Request(); 
                     $requestData->setMethod('POST'); 
                     $requestData->request->add(['customer_id' => $returnExchange->customer->id, 'message' => $auto_message, 'status' => 1]); 
                     app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'customer');
                 }

            } else if ($request->type == "return") {
                // start a request to send message for return 
                 $auto_reply = AutoReply::where('type', 'auto-reply')->where('keyword', 'order-return')->first();
                 if($auto_reply) {
                     $auto_message = preg_replace("/{order_id}/i", !empty($orderProduct) ? $orderProduct->order_id : "N/A", $auto_reply->reply); 
                     $auto_message = preg_replace("/{product_names}/i", !empty($product) ? $product->name : "N/A", $auto_message); 
                     $requestData = new Request(); 
                     $requestData->setMethod('POST'); 
                     $requestData->request->add(['customer_id' => $returnExchange->customer->id, 'message' => $auto_message, 'status' => 1]); 
                     app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'customer');
                 }

            } else if ($request->type == "exchange") {
                // start a request to send message for exchange 
                 $auto_reply = AutoReply::where('type', 'auto-reply')->where('keyword', 'order-exchange')->first();
                 if($auto_reply) {
                     $auto_message = preg_replace("/{order_id}/i", !empty($orderProduct) ? $orderProduct->order_id : "N/A", $auto_reply->reply); 
                     $auto_message = preg_replace("/{product_names}/i", !empty($product) ? $product->name : "N/A", $auto_message); 
                     $requestData = new Request(); 
                     $requestData->setMethod('POST'); 
                     $requestData->request->add(['customer_id' => $returnExchange->customer->id, 'message' => $auto_message, 'status' => 1]); 
                     app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'customer');
                 }
            }    

            // send emails
            if ($sendEmail == 'yes') {
                if ($request->type == "refund") {

                    $emailClass = (new \App\Mails\Manual\InitializeRefundRequest($returnExchange))->build();

                    $email = Email::create([
                        'model_id'         => $returnExchange->id,
                        'model_type'       => \App\ReturnExchange::class,
                        'from'             => $emailClass->fromMailer,
                        'to'               => $returnExchange->customer->email,
                        'subject'          => $emailClass->subject,
                        'message'          => $emailClass->render(),
                        'template'         => 'refund-request',
                        'additional_data'  => $returnExchange->id,
                        'status'           => 'pre-send',
                        'store_website_id' => null,
                    ]);

                    \App\Jobs\SendEmail::dispatch($email);

                } else if ($request->type == "return") {
                    
                    $emailClass = (new \App\Mails\Manual\InitializeReturnRequest($returnExchange))->build();

                    $email = Email::create([
                        'model_id'         => $returnExchange->id,
                        'model_type'       => \App\ReturnExchange::class,
                        'from'             => $emailClass->fromMailer,
                        'to'               => $returnExchange->customer->email,
                        'subject'          => $emailClass->subject,
                        'message'          => $emailClass->render(),
                        'template'         => 'return-request',
                        'additional_data'  => $returnExchange->id,
                        'status'           => 'pre-send',
                        'store_website_id' => null,
                        'is_draft'        => 1,
                    ]);

                    \App\Jobs\SendEmail::dispatch($email);
                    
                } else if ($request->type == "exchange") {
                    
                    $emailClass = (new \App\Mails\Manual\InitializeExchangeRequest($returnExchange))->build();

                    $email = Email::create([
                        'model_id'         => $returnExchange->id,
                        'model_type'       => \App\ReturnExchange::class,
                        'from'             => $emailClass->fromMailer,
                        'to'               => $returnExchange->customer->email,
                        'subject'          => $emailClass->subject,
                        'message'          => $emailClass->render(),
                        'template'         => 'exchange-request',
                        'additional_data'  => $returnExchange->id,
                        'status'           => 'pre-send',
                        'store_website_id' => null,
                        'is_draft'        => 1,
                    ]);

                    \App\Jobs\SendEmail::dispatch($email);
                    
                }
            }
        }

        return response()->json(["code" => 200, "data" => $returnExchange, "message" => "Request stored succesfully"]);
    }

    public function index(Request $request)
    {

        $returnExchange = ReturnExchange::latest('created_at')->paginate(10);
        $quickreply     = Reply::where('model', 'Order')->get();
        return view("return-exchange.index", compact('returnExchange', 'quickreply'));
    }

    public function records(Request $request)
    {
        $params         = $request->all();
        $limit          = !empty($params["limit"]) ? $params["limit"] : 10;
        $returnExchange = ReturnExchange::leftJoin("return_exchange_products as rep", "rep.return_exchange_id", "return_exchanges.id")
            ->leftJoin("order_products as op", "op.id", "rep.order_product_id")
            ->leftJoin("customers as c", "c.id", "return_exchanges.customer_id")
            ->leftJoin("products as p", "p.id", "rep.product_id")
            ->leftJoin("orders as o", "o.id", "rep.order_product_id")
            ->leftJoin("store_website_orders as wo", "wo.id", "o.order_id")
            ->leftJoin("store_websites as w", "w.id", "wo.website_id")
            ->leftJoin("return_exchange_statuses as stat", "stat.id", "return_exchanges.status")
            ->latest('return_exchanges.created_at');
        if (!empty($params["customer_name"])) {
            $returnExchange = $returnExchange->where("c.name", "like", "%" . $params["customer_name"] . "%");
        }

        if (!empty($params["customer_email"])) {
            $returnExchange = $returnExchange->where("c.email", "like", "%" . $params["customer_email"] . "%");
        }

        if (!empty($params["customer_id"])) {
            $returnExchange = $returnExchange->where("c.id", $params["customer_id"]);
        }

        if (!empty($params["order_id"])) {
            $returnExchange = $returnExchange->where("o.order_id", $params["order_id"]);
        }

        if (!empty($params["order_number"])) {
            $returnExchange = $returnExchange->where("o.order_id", $params["order_number"]);
        }

        if (!empty($params["status"])) {
            $returnExchange = $returnExchange->where("return_exchanges.status", $params["status"]);
        }

        if (!empty($params["type"])) {
            $returnExchange = $returnExchange->where("return_exchanges.type", $params["type"]);
        }

        if (!empty($params["est_completion_date"])) {
            $returnExchange = $returnExchange->where("return_exchanges.est_completion_date", '<=', $params["est_completion_date"]);
        }

        if (!empty($params["product"])) {
            $returnExchange = $returnExchange->where(function ($q) use ($params) {
                $q->orWhere("p.name", "like", "%" . $params["product"] . "%")
                    ->orWhere("p.id", "like", "%" . $params["product"] . "%")
                    ->orWhere("p.sku", "like", "%" . $params["product"] . "%");
            });
        }

        if (!empty($params["website"])) {
            $returnExchange = $returnExchange->where("w.title", "like", "%" . $params["website"] . "%");
        }

        $loggedInUser = auth()->user();
        $isInCustomerService = $loggedInUser->isInCustomerService();
        if($isInCustomerService) {
            $returnExchange = $returnExchange->where('c.user_id',$loggedInUser->id);
        }

        $returnExchange = $returnExchange->select([
            "return_exchanges.*",
            "c.name as customer_name",
            "rep.product_id", "rep.name",
            "stat.status_name as status_name",
            "w.title as website",
        ])->paginate($limit);

        // update items for status
        $items = $returnExchange->items();
        foreach ($items as &$item) {
            $item["created_at_formated"]      = date('d-m', strtotime($item->created_at));
            $item["date_of_refund_formated"]  = !empty($item->date_of_refund) ? date('d-m-Y', strtotime($item->date_of_refund)) : '-';
            $item["dispatch_date_formated"]   = !empty($item->dispatch_date) ? date('d-m-Y', strtotime($item->dispatch_date)) : '-';
            $item["date_of_request_formated"] = !empty($item->date_of_request) ? date('d-m-Y', strtotime($item->date_of_request)) : '-';
            $item["date_of_issue_formated"]   = !empty($item->date_of_issue) ? date('d-m-Y', strtotime($item->date_of_issue)) : '-';

        }

        return response()->json([
            "code"       => 200,
            "data"       => $items,
            "pagination" => (string) $returnExchange->links(),
            "total"      => $returnExchange->total(),
            "page"       => $returnExchange->currentPage(),
        ]);
    }

    public function detail(Request $request, $id)
    {
        $returnExchange = ReturnExchange::find($id);
        //check error return exist
        if (!empty($returnExchange)) {
            $data["return_exchange"] = $returnExchange;
            $data["status"]          = ReturnExchangeStatus::pluck('status_name', 'id');
            if ($request->from == 'erp-customer') {
                return view('ErpCustomer::partials.edit-return-summery', compact('data'));
            }
            return response()->json(["code" => 200, "data" => $data]);
        }
        // if not found then add error response
        return response()->json(["code" => 500, "data" => []]);
    }

    public function update(Request $request, $id)
    {
        $params = $request->all();

        $returnExchange = \App\ReturnExchange::find($id);
        $status =  ReturnExchangeStatus::find($request->status);

        if (!empty($returnExchange)) {
            $returnExchange->fill($params);
            $returnExchange->save();
            
            if(  isset( $status->status_name ) && $status->status_name == 'approve' ){
                
                $storeList = \App\Website::where('store_website_id', $returnExchange->customer->storeWebsite->id)->get();
                // dd($returnExchange->customer->storeWebsite->id);

                $code = 'REFUND-'.date('Ym').'-'.rand(1000,9999);
                $requestData = new Request();
                $requestData->setMethod('POST');
                $requestData->request->add([
                    'name'             => $code,
                    'store_website_id' => $returnExchange->customer->storeWebsite->id,
                    'website_ids'      => array($storeList[0]['platform_id'] ?? 0),
                    'start'            => date('Y-m-d H:i:s'),
                    'active'           => '1',
                    'uses_per_coustomer' => 1,
                    'customer_groups' => [0],
                    'coupon_type' => 'SPECIFIC_COUPON',
                    'code' => $code,
                    'simple_action' => 'by_fixed',
                    'discount_amount' => $request->refund_amount,
                ]);
                
                try {
                    $response = app('App\Http\Controllers\CouponController')->addRules($requestData);
                    $emailClass = (new \App\Mails\Manual\StatusChangeRefund($returnExchange))->build();
                    $email = Email::create([
                        'model_id'         => $returnExchange->id,
                        'model_type'       => \App\ReturnExchange::class,
                        'from'             => $emailClass->fromMailer,
                        'to'               => $returnExchange->customer->email,
                        'subject'          => $emailClass->subject,
                        'message'          => 'Your refund coupon :'.$code,
                        'template'         => 'refund-coupon',
                        'additional_data'  => $returnExchange->id,
                        'status'           => 'pre-send',
                        'store_website_id' => null,
                        'is_draft'        => 1,
                    ]);
                    
                    $response = json_decode($response->getContent());
                    if( $response->type == 'error' ){
                        return response()->json(["code" => 500, "data" => [], "message" => json_decode($response->getContent())->message,"error" => json_decode($response->getContent())->error]);
                    }
                    if($response->type == 'error'){
                        \App\Jobs\SendEmail::dispatch($email);
                    }
                } catch (Exception $e) {
                    return response()->json(["code" => 500, "data" => [], "message" => "Something went wrong"]);
                }
            }

            //Sending Mail on changing of order status
            if (isset($request->send_message) && $request->send_message == '1') {
                
                //sending order message to the customer
                UpdateReturnStatusMessageTpl::dispatch($returnExchange->id, request('message', null))->onQueue("customer_message");
                try {
                    if ($returnExchange->type == "refund") {

                        $emailClass = (new \App\Mails\Manual\StatusChangeRefund($returnExchange))->build();
                        $email = App\Email::create([
                            'model_id'         => $returnExchange->id,
                            'model_type'       => \App\ReturnExchange::class,
                            'from'             => $emailClass->fromMailer,
                            'to'               => $returnExchange->customer->email,
                            'subject'          => $emailClass->subject,
                            'message'          => $emailClass->render(),
                            'template'         => 'refund-request',
                            'additional_data'  => $returnExchange->id,
                            'status'           => 'pre-send',
                            'store_website_id' => null,
                            'is_draft'        => 1,
                        ]);
                        \App\Jobs\SendEmail::dispatch($email);

                    } else if ($returnExchange->type == "return") {

                        $emailClass = (new \App\Mails\Manual\StatusChangeReturn($returnExchange))->build();
                        $email = App\Email::create([
                            'model_id'         => $returnExchange->id,
                            'model_type'       => \App\ReturnExchange::class,
                            'from'             => $emailClass->fromMailer,
                            'to'               => $returnExchange->customer->email,
                            'subject'          => $emailClass->subject,
                            'message'          => $emailClass->render(),
                            'template'         => 'return-request',
                            'additional_data'  => $returnExchange->id,
                            'status'           => 'pre-send',
                            'store_website_id' => null,
                            'is_draft'        => 1,
                        ]);
                        \App\Jobs\SendEmail::dispatch($email);

                    } else if ($returnExchange->type == "exchange") {

                        $emailClass = (new \App\Mails\Manual\StatusChangeExchange($returnExchange))->build();
                        $email = App\Email::create([
                            'model_id'         => $returnExchange->id,
                            'model_type'       => \App\ReturnExchange::class,
                            'from'             => $emailClass->fromMailer,
                            'to'               => $returnExchange->customer->email,
                            'subject'          => $emailClass->subject,
                            'message'          => $emailClass->render(),
                            'template'         => 'exchange-request',
                            'additional_data'  => $returnExchange->id,
                            'status'           => 'pre-send',
                            'store_website_id' => null,
                            'is_draft'        => 1,
                        ]);

                        \App\Jobs\SendEmail::dispatch($email);
                    }
                } catch (\Exception $e) {
                    \Log::channel('productUpdates')->info("Sending mail issue at the returnexchangecontroller #158 ->" . $e->getMessage());
                }
            }

            $returnExchange->updateHistory();
        }

        return response()->json(["code" => 200, "data" => [], "message" => "Request updated succesfully!!"]);
    }

    public function regenerateCoupon(Request $request, $id)
    {
        $returnExchange = \App\ReturnExchange::find($id);
        $requestData = new CreateCouponRequest();
        $requestData->setMethod('POST');
        $code = 'REFUND-'.date('Ym').'-'.rand(1000,9999);
        
        $storeList = \App\Website::where('store_website_id', $returnExchange->customer->storeWebsite->id)->get();

        $requestData->request->add([
            'name'             => $code,
            'store_website_id' => $returnExchange->customer->storeWebsite->id,
            'customer_group_ids' => $returnExchange->customer_id,
            'website_ids'      => array($storeList[0]['platform_id'] ?? 0),
            'start'            => date('Y-m-d H:i:s'),
            'active'           => '1',
            'uses_per_coustomer' => 1,
            'simple_action' => 'by_fixed',
            'discount_amount' => $request->refund_amount,
        ]);
        
        try {
            $response = app('App\Http\Controllers\CouponController')->addRules($requestData);
            return response()->json(["code" => 200, "data" => [], "message" => json_decode($response->getContent())->message]);
        } catch (Exception $e) {
            return response()->json(["code" => 500, "data" => [], "message" => $e->getMessage()]);
        }
    }
    public function delete(Request $request, $id)
    {
        $ids = explode(",", $id);
        foreach ($ids as $id) {
            $returnExchange = \App\ReturnExchange::find($id);
            if (!empty($returnExchange)) {
                // start to delete from here
                $returnExchange->returnExchangeProducts()->delete();
                $returnExchange->returnExchangeHistory()->delete();
                $returnExchange->delete();
            }
        }
        return response()->json(["code" => 200, "data" => [], "message" => "Request deleted succesfully!!"]);
    }

    public function history(Request $request, $id)
    {
        $result = \App\ReturnExchangeHistory::where("return_exchange_id", $id)->where("history_type", 'status')->leftJoin("users as u", "u.id", "return_exchange_histories.user_id")
            ->select(["return_exchange_histories.*", "u.name as user_name"])
            ->orderby("return_exchange_histories.created_at", "desc")
            ->get();

        $history = [];
        if (!empty($result)) {
            foreach ($result as $res) {
                $res["status"] = ReturnExchangeStatus::where('id', $res->status_id)->first()->status_name;
                $history[]     = $res;
            }
        }

        return response()->json(["code" => 200, "data" => $history, "message" => ""]);
    }

    public function getProducts($id)
    {
        if (!empty($id)) {
            $product = \App\Product::find($id);
            if (!empty($product)) {

                $data['dnf']               = $product->dnf;
                $data['id']                = $product->id;
                $data['name']              = $product->name;
                $data['short_description'] = $product->short_description;
                $data['activities']        = $product->activities;
                $data['scraped']           = $product->scraped_products;

                $data['measurement_size_type'] = $product->measurement_size_type;
                $data['lmeasurement']          = $product->lmeasurement;
                $data['hmeasurement']          = $product->hmeasurement;
                $data['dmeasurement']          = $product->dmeasurement;

                $data['size']       = $product->size;
                $data['size_value'] = $product->size_value;

                $data['composition'] = $product->composition;
                $data['sku']         = $product->sku;
                $data['made_in']     = $product->made_in;
                $data['brand']       = $product->brand;
                $data['color']       = $product->color;
                $data['price']       = $product->price;
                $data['status']      = $product->status_id;

                $data['euro_to_inr']       = $product->euro_to_inr;
                $data['price_inr']         = $product->price_inr;
                $data['price_inr_special'] = $product->price_inr_special;

                $data['isApproved']    = $product->isApproved;
                $data['rejected_note'] = $product->rejected_note;
                $data['isUploaded']    = $product->isUploaded;
                $data['isFinal']       = $product->isFinal;
                $data['stock']         = $product->stock;
                $data['reason']        = $product->rejected_note;

                $data['product_link']     = $product->product_link;
                $data['supplier']         = $product->supplier;
                $data['supplier_link']    = $product->supplier_link;
                $data['description_link'] = $product->description_link;
                $data['location']         = $product->location;

                $data['suppliers']      = '';
                $data['more_suppliers'] = [];

                foreach ($product->suppliers as $key => $supplier) {
                    if ($key == 0) {
                        $data['suppliers'] .= $supplier->supplier;
                    } else {
                        $data['suppliers'] .= ", $supplier->supplier";
                    }
                }

                $image = $product->getMedia(config('constants.media_tags'))->first();

                if ($image !== null) {
                    $data['images'] = $image->getUrl();
                } else {
                    $data['images'] = "#";
                }

                $data['categories'] = $product->category ? CategoryController::getCategoryTree($product->category) : '';
                $data['product']    = $product;

                $response = (string) view("return-exchange.templates.productview", $data);
            }
        }
        return response()->json(["code" => 200, "html" => $response]);
    }

    public function product(Request $request, $id)
    {
        if (!empty($id)) {
            $product = \App\Product::where("products.id", $id)
                ->leftJoin("order_products as op", "op.product_id", "products.id")
                ->leftJoin("orders", "orders.id", "op.order_id")
                ->leftJoin("brands", "brands.id", "products.brand")
                ->select(["orders.order_id as order_number", "brands.name as product_brand", "products.name as product_name",
                    "products.image as product_image", "products.price as product_price",
                    "products.supplier as product_supplier", "products.short_description as about_product"])
                ->get();
        }
        return response()->json(["code" => 200, "data" => $product, "message" => ""]);
    }

    public function updateCustomer(Request $request)
    {
        if ($request->update_type == 1) {
            $ids = explode(",", $request->selected_ids);
            foreach ($ids as $id) {
                $return = \App\ReturnExchange::where("id", $id)->first();
                if ($return && $request->customer_message && $request->customer_message != "") {
                    \App\Jobs\UpdateReturnExchangeStatusTpl::dispatch($return->id, $request->customer_message);
                }
            }
        } else {
            $ids = explode(",", $request->selected_ids);
            foreach ($ids as $id) {
                if (!empty($id) && $request->customer_message && $request->customer_message != "" && $request->status) {
                    $return  = \App\ReturnExchange::where("id", $id)->first();
                    $statuss = \App\ReturnExchangeStatus::where("id", $request->status)->first();
                    if ($return) {
                        $return->status = $request->status;
                        $return->save();
                        \App\Jobs\UpdateReturnExchangeStatusTpl::dispatch($return->id, $request->customer_message);
                    }
                }
            }
        }
        return response()->json(['message' => 'Successful'], 200);
    }

    public function createStatus(Request $request)
    {
        $this->validate($request, [
            'status_name' => 'required',
        ]);
        $input   = $request->except('_token');
        $isExist = \App\ReturnExchangeStatus::where('status_name', $request->status_name)->first();
        if (!$isExist) {
            \App\ReturnExchangeStatus::create([
                'status_name' => $request->status_name,
            ]);
            return response()->json(['message' => 'Successful'], 200);
        } else {
            return response()->json(['message' => 'Fail'], 401);
        }
    }

    public function createRefund(Request $request)
    {
        $this->validate($request, [
            'customer_id'        => 'required|integer',
            'refund_amount'      => 'required',
            'refund_amount_mode' => 'required|string',
        ]);

        $data                  = $request->except('_token');
        $data['date_of_issue'] = Carbon::parse($request->date_of_request)->addDays(10);

        if ($request->credited) {
            $data['credited'] = 1;
        }
        ReturnExchange::create($data);
        //create entry in table cash_flows
        \DB::table('cash_flows')->insert(
            [
                'cash_flow_able_id'   => $request->input('user_id'),
                'description'         => 'Vendor paid',
                'date'                => ('Y-m-d'),
                'amount'              => $request->input('refund_amount'),
                'type'                => 'paid',
                'cash_flow_able_type' => 'App\ReturnExchange',

            ]
        );

        /// start a request to send message for refund 
         $auto_reply = AutoReply::where('type', 'auto-reply')->where('keyword', 'order-refund-manual')->first();
         if($auto_reply) {
             $requestData = new Request(); 
             $requestData->setMethod('POST'); 
             $requestData->request->add(['customer_id' => $request->customer_id, 'message' => $auto_reply->reply, 'status' => 1]); 
             app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'customer');
         }

        return response()->json(['message' => 'You have successfully added refund!'], 200);
    }

    public function getRefundInfo($id)
    {
        $returnExchange = ReturnExchange::find($id);
        $response       = (string) view("return-exchange.templates.update-refund", compact('returnExchange', 'id'));

        return response()->json(["code" => 200, "html" => $response]);
        // return view('',compact('returnExchange'));
    }

    public function updateRefund(Request $request)
    {
        $this->validate($request, [
            'customer_id'        => 'required|integer',
            'refund_amount'      => 'required',
            'id'                 => 'required',
            'refund_amount_mode' => 'required|string',
        ]);

        $data           = $request->except('_token', 'id', 'customer_id');
        $returnExchange = ReturnExchange::find($request->id);
        if (!$returnExchange->date_of_issue) {
            $data['date_of_issue'] = Carbon::parse($request->date_of_request)->addDays(10);
        }
        if ($returnExchange) {
            $returnExchange->update($data);
        }

        //Sending Mail on edit of return and exchange
        $mailingListCategory = MailinglistTemplateCategory::where('title', 'Refund and Exchange')->first();
        $templateData        = MailinglistTemplate::where('store_website_id', $returnExchange->customer->store_website_id)->where('category_id', $mailingListCategory->id)->first();

        $arrToReplace = ['{FIRST_NAME}', '{REFUND_TYPE}', '{CHQ_NUMBER}', '{REFUND_AMOUNT}', '{DATE_OF_REFUND}', '{DETAILS}'];

        $valToReplace = [$returnExchange->customer->name, $returnExchange->type, $returnExchange->chq_number, $returnExchange->amount, $returnExchange->date_of_request, $returnExchange->details];
        $bodyText     = str_replace($arrToReplace, $valToReplace, $templateData->static_template);

        $storeEmailAddress = EmailAddress::where('store_website_id', $returnExchange->customer->store_website_id)->first();

        $emailData['subject']         = $templateData->subject;
        $emailData['static_template'] = $bodyText;
        $emailData['from']            = $storeEmailAddress->from_address;
        Mail::to($returnExchange->customer->email)->send(new ReturnExchangeEmail($emailData));
        //Sending Mail on edit of return and exchange

        $updateOrder = 0;
        if (!$request->dispatched) {
            $data['dispatch_date'] = $returnExchange->dispatch_date;
            $data['awb']           = $returnExchange->awb;
        } else {
            $order_products = ReturnExchange::join('return_exchange_products', 'return_exchanges.id', 'return_exchange_products.return_exchange_id')
                ->join('order_products', 'order_products.id', 'return_exchange_products.order_product_id')->select('order_products.*')->first();
            if ($order_products) {
                $order = Order::find($order_products->order_id);
                if ($order) {
                    $updateOrder            = 1;
                    $order->order_status    = 'Refund Dispatched';
                    $order->order_status_id = \App\Helpers\OrderHelper::$refundDispatched;
                    event(new RefundDispatched($returnExchange));
                }
            }
        }

        if ($request->credited) {
            $data['credited'] = 1;
            if ($updateOrder == 1) {
                $order->order_status    = 'Refund Credited';
                $order->order_status_id = \App\Helpers\OrderHelper::$refundCredited;
            }
        }

        $data['date_of_issue'] = Carbon::parse($request->date_of_request)->addDays(10);
        if ($returnExchange) {
            if ($updateOrder == 1) {
                $order->save();
            }
        }
        return response()->json(['message' => 'You have successfully added refund!'], 200);
    }

    public function updateEstmatedDate(Request $request)
    {

        $returnExchange = ReturnExchange::find($request->exchange_id);
        if ($returnExchange) {
            if ($request->estimate_date && $request->estimate_date != "") {
                $oldDate                             = $returnExchange->est_completion_date;
                $returnExchange->est_completion_date = $request->estimate_date;
                $returnExchange->save();

                ReturnExchangeHistory::create([
                    "return_exchange_id" => $request->exchange_id,
                    "status_id"          => 0,
                    "user_id"            => Auth::user()->id,
                    "history_type"       => 'est_date',
                    "old_value"          => $oldDate,
                    "new_value"          => $request->estimate_date,
                ]);

                return response()->json(['code' => 200, 'message' => 'Successfull']);
            }
        }
        return response()->json(['code' => 500, 'message' => 'Return/exchange not found']);
    }

    public function estimationHistory(Request $request, $id)
    {
        $result = \App\ReturnExchangeHistory::where("return_exchange_id", $id)->where("history_type", 'est_date')->leftJoin("users as u", "u.id", "return_exchange_histories.user_id")
            ->select(["return_exchange_histories.*", "u.name as user_name"])
            ->get();

        $history = [];
        if (!empty($result)) {
            foreach ($result as $res) {
                $history[] = $res;
            }
        }

        return response()->json(["code" => 200, "data" => $history, "message" => ""]);
    }
    public function addNewReply(request $request)
    {
        if ($request->reply) {
            $replyData                = [];
            $html                     = '';
            $replyData['reply']       = $request->reply;
            $replyData['model']       = 'Order';
            $replyData['category_id'] = 1;
            $success                  = Reply::create($replyData);
            if ($success) {
                $replies = Reply::where('model', 'Order')->get();
                if ($replies) {
                    $html .= "<option value=''>Select Order Status</option>";
                    foreach ($replies as $reply) {
                        $html .= '<option value="' . $reply->id . '">' . $reply->reply . '</option>';
                    }
                }

                return response()->json(['message' => 'reply added successfully', 'html' => $html, 'status' => 200]);
            }
            return response()->json(['message' => 'unable to add reply', 'status' => 500]);
        }
        return response()->json(['message' => 'please enter a reply', 'status' => 400]);
    }

    public function resendEmail(Request $request)
    {
        $returnExchange = \App\ReturnExchange::find($request->id);
        if ($returnExchange) {
            try {
                if ($request->type == "refund") {
                    
                    $emailClass = (new \App\Mails\Manual\InitializeRefundRequest($returnExchange))->build();
                    
                    $email = Email::create([
                        'model_id'         => $returnExchange->id,
                        'model_type'       => \App\ReturnExchange::class,
                        'from'             => $emailClass->fromMailer,
                        'to'               => $returnExchange->customer->email,
                        'subject'          => $emailClass->subject,
                        'message'          => $emailClass->render(),
                        'template'         => 'refund-request',
                        'additional_data'  => $returnExchange->id,
                        'status'           => 'pre-send',
                        'store_website_id' => null,
                        'is_draft'        => 1,
                    ]);

                    \App\Jobs\SendEmail::dispatch($email);


                } else if ($request->type == "return") {

                    $emailClass = (new \App\Mails\Manual\InitializeReturnRequest($returnExchange))->build();
                    
                    $email = Email::create([
                        'model_id'         => $returnExchange->id,
                        'model_type'       => \App\ReturnExchange::class,
                        'from'             => $emailClass->fromMailer,
                        'to'               => $returnExchange->customer->email,
                        'subject'          => $emailClass->subject,
                        'message'          => $emailClass->render(),
                        'template'         => 'return-request',
                        'additional_data'  => $returnExchange->id,
                        'status'           => 'pre-send',
                        'store_website_id' => null,
                        'is_draft'        => 1,
                    ]);

                    \App\Jobs\SendEmail::dispatch($email);


                } else if ($request->type == "exchange") {
                    
                    $emailClass = (new \App\Mails\Manual\InitializeExchangeRequest($returnExchange))->build();
                    
                    $email = Email::create([
                        'model_id'         => $returnExchange->id,
                        'model_type'       => \App\ReturnExchange::class,
                        'from'             => $emailClass->fromMailer,
                        'to'               => $returnExchange->customer->email,
                        'subject'          => $emailClass->subject,
                        'message'          => $emailClass->render(),
                        'template'         => 'exchange-request',
                        'additional_data'  => $returnExchange->id,
                        'status'           => 'pre-send',
                        'store_website_id' => null,
                        'is_draft'        => 1,
                    ]);

                    \App\Jobs\SendEmail::dispatch($email);

                }
            } catch (\Exception $e) {
                \Log::channel('productUpdates')->info("Sending mail issue at the returnexchangecontroller #694 ->" . $e->getMessage());
            }
        }

        return response()->json(['message' => 'Return request send successfully', 'status' => 200]);
    }

    public function status(Request $request)
    {
        $status = ReturnExchangeStatus::query();

        if ($request->search != null) {
            $status = $status->where("status_name", "like", "%" . $request->search . "%");
        }

        $status = $status->get();

        return view("return-exchange.status", compact('status'));
    }

    public function saveStatusField(Request $request)
    {
        if ($request->id != null) {
            $status = ReturnExchangeStatus::find($request->id);
            if ($status) {
                $status->{$request->field} = $request->value;
                $status->save();

                return response()->json(["code" => 200, "data" => $status, "message" => "Added successfully"]);
            }
        }

        return response()->json(["code" => 500, "data" => [], "message" => "No data found"]);
    }

    public function deleteStatus(Request $request)
    {
        if ($request->id != null) {
            $status = ReturnExchangeStatus::find($request->id);
            if ($status) {
                $status->delete();
                return response()->json(["code" => 200, "data" => $status, "message" => "Added successfully"]);
            }
        }

        return response()->json(["code" => 500, "data" => [], "message" => "No data found"]);
    }


    public function downloadRefundPdf(Request $request)
    {
        $return = \App\ReturnExchange::findOrFail($request->id);

        $customer   = $return->customer;

            if($customer){
                            $html_temp = view('maileclipse::templates.initializeRefundRequetDefault', compact(
                                'customer','return'
                            ));
                            $pdf = new Dompdf();
                            $pdf->loadHtml($html_temp);
                            $pdf->render();
                            $pdf->stream('refund.pdf');
            }
    }
}

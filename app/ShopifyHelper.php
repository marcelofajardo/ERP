<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use App\Customer;
use App\Helpers\OrderHelper;
use App\Http\Controllers\GoogleTranslateController;
use App\LandingPageProduct;
use App\Library\Shopify\Client as ShopifyClient;
use App\Order;
use App\Product;
use App\Services\Products\GraphqlService;
use App\StoreWebsiteOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use seo2websites\MagentoHelper\MagentoHelperv2 as MagentoHelper;
use App\Loggers\LogListMagento;
use App\ProductPushErrorLog;

class ShopifyHelper
{

    public function __construct()
    {

    }

    public function pushProduct(Product $product, $website)
    {
        // Check for product and session
        if ($product === null) {
            return false;
        }

        $landingpageProduct = new LandingPageProduct;
        $productData        = $landingpageProduct->getShopifyPushData($product, $website);
        echo "<pre>"; print_r($productData);  echo "</pre>";die;
        LogListMagento::log($product->id, "Product started to push" . $product->id, 'info', $website->store_website_id, "success");
        
        ProductPushErrorLog::log(null,$product->id, "Product push data not found", 'error',$website->id);
        if ($productData == false) {
            return false;
        }

        $client = new ShopifyClient();

        $shopifyID = \App\StoreWebsiteProduct::where("store_website_id", $website->id)
        ->where("product_id", $product->id)
        ->first();

        if ($shopifyID) {
            $response = $client->updateProduct($shopifyID->platform_id, $productData,null, $website->id);
        } else {
            $response = $client->addProduct($productData, $website->id);
        }

        if (!empty($response->product)) {
            $storeWebsiteProduct = \App\StoreWebsiteProduct::updateOrCreate([
                "store_website_id" => $website->id,
                "product_id"       => $product->id,
            ], [
                "store_website_id" => $website->id,
                "product_id"       => $product->id,
                "platform_id"      => $response->product->id,
            ]);
            LogListMagento::log($product->id, "success " . $product->id, 'info', $website->id, "success");
        }else{
            LogListMagento::log($product->id, "error " . $product->id, 'info', $website->id, "error");
        }

        $errors = [];
        if (!empty($response->errors)) {
            foreach ((array) $response->errors as $key => $message) {
                if (is_array($message)) {
                    foreach ($message as $msg) {
                        $errors[] = ucwords($key) . " " . $msg;
                    }
                } else {
                    $errors[] = ucwords($key) . " " . $message;
                }
            }
        }

        if (!empty($errors)) {
            \Log::channel('productUpdates')->info(json_encode(["code" => 500, "data" => $response, "message" => implode("<br>", $errors)]));
            return false;
        }

        if(empty($response->product)) {
            \Log::channel('productUpdates')->info(json_encode(["code" => 500, "data" => $response, "message" => "Response is missing"]));
            return false;
        }


        GoogleTranslateController::translateProductDetails($product);
        GraphqlService::sendTranslationByGrapql($response->product->id, $product->id, $website->magento_url, $website->magento_password , $website);

        return true;
    }

    /**
     * Method to sync shopify orders to ERP orders. We'll receive shopify order though a webhook
     * Ref: https://shopify.dev/docs/admin-api/rest/reference/events/webhook?api[version]=2020-07
     *
     * @author Sukhwinder Singh
     * @param [type] $store_id
     * @param [type] $order
     * @return void
     */
    public static function syncShopifyOrders($store_id, $order)
    {

        // \Log::info(print_r($order,true));

        //Checking in order table
        $shopify_order_id  = $order["id"];
        $checkIfOrderExist = StoreWebsiteOrder::where('platform_order_id', $shopify_order_id)->where('website_id', $store_id)->first();

        //Checking in Website Order Table
        if ($checkIfOrderExist) {
            return;
        }

        $balance_amount = 0;

        // Check for customer details out of order
        $firstName = isset($order["customer"]) ? (isset($order["customer"]["first_name"]) ? $order["customer"]["first_name"] : "N/A") : "N/A";
        $lastName  = isset($order["customer"]) ? (isset($order["customer"]["last_name"]) ? $order["customer"]["last_name"] : "N/A") : "N/A";

        $full_name      = $firstName . ' ' . $lastName;
        $customer_phone = isset($order["customer"]) ? (isset($order["customer"]["phone"]) ? $order["customer"]["phone"] : '') : '';

        $customer = Customer::where('email', $store_customer["email"])->where("store_website_id", $store_id)->first();

        // Create a customer if doesn't exists
        if (!$customer) {
            $customer = new Customer;
        }

        $customer->name             = $full_name;
        $customer->email            = $order["customer"]["email"];
        $customer->address          = $order["billing_address"]["address1"];
        $customer->city             = $order["billing_address"]["city"];
        $customer->country          = $order["billing_address"]["country"];
        $customer->pincode          = $order["billing_address"]["zip"];
        $customer->phone            = $order["billing_address"]["phone"];
        $customer->store_website_id = $store_id;
        $customer->save();

        $customer_id    = $customer->id;
        $order_status   = '';
        $payment_method = '';

        // For shopify payment method will always by shopify_payments
        $payment_method = 'shopify_payments';

        // check the processing method and convert it to generic method name used by ERP
        if ($order["financial_status"] == 'paid') {
            $order_status = OrderHelper::$purchaseComplete;
        } else {
            $order_status = OrderHelper::$pendingPurchase;
        }

        $id = \DB::table('orders')->insertGetId(
            array(
                'customer_id'     => $customer_id,
                'order_id'        => $order["id"],
                'order_type'      => 'online',
                'order_status'    => $order_status,
                'order_status_id' => $order_status,
                'payment_mode'    => $payment_method,
                'order_date'      => $order["created_at"],
                'client_name'     => $full_name,
                'city'            => $order["billing_address"]["city"],
                'advance_detail'  => 0,
                'contact_detail'  => $order["billing_address"]["phone"],
                'balance_amount'  => $balance_amount,
                'created_at'      => $order["created_at"],
                'updated_at'      => $order["created_at"],
            ));

        //create entry in table cash_flows
        \DB::table('cash_flows')->insert(
            [
                'cash_flow_able_id'=>$customer_id,
                'description'=>'Order recieved full pre payment for orderid '.$order["id"],
                'date'=>date('Y-m-d'),
                'amount'=>$balance_amount,
                'type'=>'received',
                'cash_flow_able_type'=>'App\Order',
                'status' => $order_status,
                'order_status' => $order_status,
                'expected'=>$balance_amount,
                'actual'=>$balance_amount,
            ]
        );    

        $items = $order["line_items"];
        foreach ($items as $item) {
            if (round($item["price"]) > 0) {

                //
                $size = '';

                // We already have a helper function to get the product attributes
                $skuAndColor = MagentoHelper::getSkuAndColor($item["sku"]);

                // Store products per order
                DB::table('order_products')->insert(
                    array(
                        'order_id'      => $id,
                        'product_id'    => !empty($skuAndColor['product_id']) ? $skuAndColor['product_id'] : null,
                        'sku'           => $skuAndColor['sku'],
                        'product_price' => round($item["price"]),
                        'qty'           => round($item["quantity"]),
                        'size'          => $size,
                        'color'         => $skuAndColor['color'],
                        'created_at'    => $order["created_at"],
                        'updated_at'    => $order["created_at"],
                    )
                );
            }
        }
        $orderSaved = Order::find($id);

        //Store Order Id Website ID and Shopify ID

        $websiteOrder                    = new StoreWebsiteOrder();
        $websiteOrder->website_id        = $store_id;
        $websiteOrder->status_id         = $order_status;
        $websiteOrder->order_id          = $orderSaved->id;
        $websiteOrder->platform_order_id = $shopify_order_id;
        $websiteOrder->save();

        \Log::channel('productUpdates')->info("Saved order: " . $orderSaved->id);

    }

    /**
     * Method to sync shopify customers to ERP customers. We'll receive shopify customer though a webhook
     * Ref: https://shopify.dev/docs/admin-api/rest/reference/events/webhook?api[version]=2020-07
     *
     * @author Sukhwinder Singh
     * @param [type] $store_id
     * @param [type] $customer
     * @return void
     */
    public static function syncShopifyCustomers($store_id, $store_customer)
    {

        // \Log::info(print_r($store_customer,true));

        // Extract customer details from the payload
        $firstName = isset($store_customer) ? (isset($store_customer["first_name"]) ? $store_customer["first_name"] : "N/A") : "N/A";
        $lastName  = isset($store_customer) ? (isset($store_customer["last_name"]) ? $store_customer["last_name"] : "N/A") : "N/A";

        $full_name        = $firstName . ' ' . $lastName;
        $customer_phone   = isset($store_customer) ? (isset($store_customer["phone"]) ? $store_customer["phone"] : '') : '';
        $customer_address = isset($store_customer["addresses"]["address1"]) ? (isset($store_customer["addresses"]["address1"]) ? $store_customer["phone"] : '') : '';
        $customer_city    = isset($store_customer["address1"]) ? (isset($store_customer["address1"]["city"]) ? $store_customer["address1"]["city"] : '') : '';
        $customer_country = isset($store_customer["address1"]) ? (isset($store_customer["address1"]["country"]) ? $store_customer["address1"]["country"] : '') : '';
        $customer_zip     = isset($store_customer["address1"]) ? (isset($store_customer["address1"]["zip"]) ? $store_customer["address1"]["zip"] : '') : '';
        $customer_phone   = isset($store_customer) ? (isset($store_customer["phone"]) ? $store_customer["phone"] : '') : '';

        $customer = Customer::where('email', $store_customer["email"])->where("store_website_id", $store_id)->first();

        // Create a customer if doesn't exists
        if (!$customer) {
            $customer = new Customer;
        }

        $customer->name             = $full_name;
        $customer->email            = $store_customer["email"];
        $customer->address          = $customer_address;
        $customer->city             = $customer_city;
        $customer->country          = $customer_country;
        $customer->pincode          = $customer_zip;
        $customer->phone            = $customer_phone;
        $customer->store_website_id = $store_id;
        $customer->save();

        \Log::channel('customer')->info("Saved customer: " . $customer->id);

    }

    public static function validateShopifyWebhook($data, $secret, $hmac_header)
    {

        //$calculated_hmac = base64_encode(hash_hmac('sha256', $data, $secret, true));
        return true; //hash_equals($hmac_header, $calculated_hmac);

    }

}

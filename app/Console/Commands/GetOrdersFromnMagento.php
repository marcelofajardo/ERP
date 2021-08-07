<?php

namespace App\Console\Commands;

use App\AutoReply;
use App\ChatMessage;
use App\Colors;
use App\CommunicationHistory;
use App\Customer;
use App\Helpers\OrderHelper;
use App\Order;
use App\OrderProduct;
use App\Product;
use App\Setting;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Validator;

class GetOrdersFromnMagento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getorders:magento';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Orders From Magento And Store In Database Running Every Fifteen Minutes For Now';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $report = \App\CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);
            $options = array(
                'trace'              => true,
                'connection_timeout' => 120,
                'wsdl_cache'         => WSDL_CACHE_NONE,
            );
            $size      = '';
            $proxy     = new \SoapClient(config('magentoapi.url'), $options);
            $sessionId = $proxy->login(config('magentoapi.user'), config('magentoapi.password'));
            $lastid    = Setting::get('lastid');
            $filter    = array(
                'complex_filter' => array(
                    array(
                        'key'   => 'order_id',
                        'value' => array('key' => 'gt', 'value' => $lastid),
                    ),
                ),
            );
            $orderlist = $proxy->salesOrderList($sessionId, $filter);

            for ($j = 0; $j < sizeof($orderlist); $j++) {
                $results = json_decode(json_encode($proxy->salesOrderInfo($sessionId, $orderlist[$j]->increment_id)), true);

                $atts = unserialize($results['items'][0]['product_options']);

                if (!empty($results['total_paid'])) {
                    $paid = $results['total_paid'];
                } else {
                    $paid = 0;
                }

                $balance_amount = $results['base_grand_total'] - $paid;

                $full_name = $results['billing_address']['firstname'] . ' ' . $results['billing_address']['lastname'];

                $customer_phone = (int) str_replace(' ', '', $results['billing_address']['telephone']);
                $final_phone    = null;

                if ($customer_phone != null) {
                    if ($results['billing_address']['country_id'] == 'IN') {
                        if (strlen($customer_phone) <= 10) {
                            $customer_phone = '91' . $customer_phone;
                        }
                    }

                    $customer = Customer::where('phone', $customer_phone)->first();
                } else {
                    $customer = Customer::where('name', 'LIKE', "%$full_name%")->first();
                }

                if ($customer) {
                    $customer_id = $customer->id;

                    if ($customer_phone != null) {
                        $final_phone = $customer_phone;
                    }

                    if ($customer->credit > 0) {
                        if (($balance_amount - $customer->credit) < 0) {
                            $left_credit      = ($balance_amount - $customer->credit) * -1;
                            $balance_amount   = 0;
                            $customer->credit = $left_credit;
                        } else {
                            $balance_amount -= $customer->credit;
                            $customer->credit = 0;
                        }
                    }

                    $customer->name    = $full_name;
                    $customer->email   = $results['customer_email'];
                    $customer->address = $results['billing_address']['street'];
                    $customer->city    = $results['billing_address']['city'];
                    $customer->country = $results['billing_address']['country_id'];
                    $customer->pincode = $results['billing_address']['postcode'];
                    $customer->phone   = $final_phone;

                    $customer->save();
                } else {
                    $customer          = new Customer;
                    $customer->name    = $full_name;
                    $customer->email   = $results['customer_email'];
                    $customer->address = $results['billing_address']['street'];
                    $customer->city    = $results['billing_address']['city'];
                    $customer->country = $results['billing_address']['country_id'];
                    $customer->pincode = $results['billing_address']['postcode'];
                    $temp_number       = [];

                    if ($customer_phone != null) {
                        $temp_number['phone'] = $customer_phone;
                    } else {
                        $temp_number['phone'] = self::generateRandomString();
                    }

                    $final_phone     = self::validatePhone($temp_number);
                    $customer->phone = $final_phone;

                    $customer->save();

                    $customer_id = $customer->id;
                }

                $order_status   = '';
                $payment_method = '';

                if ($results['payment']['method'] == 'paypal') {
                    if ($results['state'] == 'processing') {
                        $order_status = OrderHelper::$prepaid;
                    } else {
                        $order_status = OrderHelper::$followUpForAdvance;
                    }

                    $payment_method = 'paypal';
                } elseif ($results['payment']['method'] == 'banktransfer') {
                    if ($results['state'] == 'processing') {
                        $order_status = OrderHelper::$prepaid;
                    } else {
                        $order_status = OrderHelper::$followUpForAdvance;
                    }
                    $payment_method = 'banktransfer';
                } elseif ($results['payment']['method'] == 'cashondelivery') {
                    if ($results['state'] == 'processing') {
                        $order_status = OrderHelper::$prepaid;
                    } else {
                        $order_status = OrderHelper::$followUpForAdvance;
                    }
                    $payment_method = 'cashondelivery';
                }

                $id = DB::table('orders')->insertGetId(
                    array(
                        'customer_id'     => $customer_id,
                        'order_id'        => $results['increment_id'],
                        'order_type'      => 'online',
                        'order_status'    => $order_status,
                        'order_status_id' => $order_status,
                        'payment_mode'    => $payment_method,
                        'order_date'      => $results['created_at'],
                        'client_name'     => $results['billing_address']['firstname'] . ' ' . $results['billing_address']['lastname'],
                        'city'            => $results['billing_address']['city'],
                        'advance_detail'  => $paid,
                        'contact_detail'  => $final_phone,
                        'balance_amount'  => $balance_amount,
                        'created_at'      => $results['created_at'],
                        'updated_at'      => $results['created_at'],
                    ));

                $noproducts = sizeof($results['items']);
                for ($i = 0; $i < $noproducts; $i++) {

                    if (round($results['items'][$i]['price']) > 0) {

                        if ($results['items'][$i]['product_type'] == 'configurable' && !empty($atts['attributes_info'][0]['label'])) {
                            if ($atts['attributes_info'][0]['label'] == 'Sizes') {
                                $size = $atts['attributes_info'][0]['value'];
                            }
                        } else {
                            $size = '';
                        }
                        $skuAndColor = self::getSkuAndColor($results['items'][$i]['sku']);

                        DB::table('order_products')->insert(
                            array(
                                'order_id'      => $id,
                                'product_id'    => !empty($skuAndColor['product_id']) ? $skuAndColor['product_id'] : null,
                                'sku'           => $skuAndColor['sku'],
                                'product_price' => round($results['items'][$i]['price']),
                                'qty'           => round($results['items'][$i]['qty_ordered']),
                                'size'          => $size,
                                'color'         => $skuAndColor['color'],
                                'created_at'    => $results['created_at'],
                                'updated_at'    => $results['created_at'],
                            ));
                    }
                }
                Setting::add('lastid', $orderlist[$j]->order_id, 'int');

                $order = Order::find($id);
                if ($results['payment']['method'] == 'cashondelivery') {
                    $product_names = '';
                    foreach (OrderProduct::where('order_id', $id)->get() as $order_product) {
                        $product_names .= $order_product->product ? $order_product->product->name . ", " : '';
                    }

                    $delivery_time = $order->estimated_delivery_date ? Carbon::parse($order->estimated_delivery_date)->format('d \of\ F') : Carbon::parse($order->order_date)->addDays(15)->format('d \of\ F');

                    $auto_reply = AutoReply::where('type', 'auto-reply')->where('keyword', 'cod-online-confirmation')->first();

                    $auto_message = preg_replace("/{product_names}/i", $product_names, $auto_reply->reply);
                    $auto_message = preg_replace("/{delivery_time}/i", $delivery_time, $auto_message);

                    $params = [
                        'number'      => null,
                        'user_id'     => 6,
                        'approved'    => 1,
                        'status'      => 2,
                        'customer_id' => $order->customer->id,
                        'message'     => $auto_message,
                    ];

                    $chat_message = ChatMessage::create($params);

                    $whatsapp_number = $order->customer->whatsapp_number != '' ? $order->customer->whatsapp_number : null;

                    $params['message'] = AutoReply::where('type', 'auto-reply')->where('keyword', 'cod-online-followup')->first()->reply;

                    $chat_message = ChatMessage::create($params);

                    CommunicationHistory::create([
                        'model_id'   => $order->id,
                        'model_type' => Order::class,
                        'type'       => 'initial-advance',
                        'method'     => 'whatsapp',
                    ]);
                } elseif ($order->order_status_id == \App\Helpers\OrderHelper::$prepaid && $results['state'] == 'processing') {
                    $params = [
                        'number'      => null,
                        'user_id'     => 6,
                        'approved'    => 1,
                        'status'      => 2,
                        'customer_id' => $order->customer->id,
                        'message'     => AutoReply::where('type', 'auto-reply')->where('keyword', 'prepaid-order-confirmation')->first()->reply,
                    ];

                    $chat_message = ChatMessage::create($params);

                    $whatsapp_number = $order->customer->whatsapp_number != '' ? $order->customer->whatsapp_number : null;

                    CommunicationHistory::create([
                        'model_id'   => $order->id,
                        'model_type' => Order::class,
                        'type'       => 'online-confirmation',
                        'method'     => 'whatsapp',
                    ]);
                }

                if ($results['state'] != 'processing' && $results['payment']['method'] != 'cashondelivery') {
                    $params = [
                        'number'      => null,
                        'user_id'     => 6,
                        'approved'    => 1,
                        'status'      => 2,
                        'customer_id' => $order->customer->id,
                        'message'     => AutoReply::where('type', 'auto-reply')->where('keyword', 'order-payment-not-processed')->first()->reply,
                    ];

                    $chat_message = ChatMessage::create($params);

                    $whatsapp_number = $order->customer->whatsapp_number != '' ? $order->customer->whatsapp_number : null;

                }
            }
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }

    public static function generateRandomString($length = 10)
    {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function validatePhone($phone)
    {
        $validator = Validator::make($phone, [
            'phone' => 'unique:customers,phone',
        ]);

        if ($validator->fails()) {
            $phone['phone'] = self::generateRandomString();

            self::validatePhone($phone);
        }

        return $phone['phone'];
    }

    public static function getSkuAndColor($original_sku)
    {

        $result = [];
        $colors = (new Colors())->all();

        $splitted_sku = explode('-', $original_sku);

        foreach ($colors as $color) {

            if (strpos($splitted_sku[0], $color)) {

                $result['color'] = $color;
                $sku             = str_replace($color, '', $splitted_sku[0]);

                $product = Product::where('sku', 'LIKE', "%$sku%")->first();

                if ($product) {
                    $result['product_id']   = $product->id;
                    $result['sku']          = $product->sku;
                } else {
                    $result['sku'] = $sku;
                }

                return $result;
            }
        }

        $result['color'] = null;
        $sku             = $splitted_sku[0];

        $product = Product::where('sku', 'LIKE', "%$sku%")->first();

        if ($product) {
            $result['product_id']   = $product->id;
            $result['sku']          = $product->sku;
        } else {
            $result['sku'] = $sku;
        }

        return $result;
    }
}

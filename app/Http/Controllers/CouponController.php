<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\CouponCodeRules;
use App\Helpers\SSP;
use App\Http\Requests\CreateCouponRequest;
use App\Order;
use App\StoreWebsite;
use App\Website;
use App\WebsiteStore;
use App\WebsiteStoreViewValue;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CouponController extends Controller
{
    private $DATA_COLUMN_KEY = -99;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $coupons        = Coupon::orderBy('id', 'DESC')->get();
        $websites       = Website::all();
        $website_stores = WebsiteStore::with('storeView')->get();

        $store_websites = StoreWebsite::all();

        // $url = "https://sololuxury.com/rest/V1/salesRulesList/";
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $response = curl_exec($ch);
        // $rule_lists = json_decode($response);
        // curl_close($ch); // Close the connection

        $rule_lists = CouponCodeRules::orderBy('id', 'desc')->get();
        foreach ($rule_lists as $rules) {
            $websites           = Website::whereIn('platform_id', explode(',', $rules->website_ids))->where('store_website_id', $rules->store_website_id)->pluck('name')->toArray();
            $rules->website_ids = implode(',', $websites);
        }

        return view('coupon.index', compact('coupons', 'websites', 'website_stores', 'rule_lists', 'store_websites'));
    }

    public function loadData()
    {
        $tableName  = with(new Coupon)->getTable();
        $primaryKey = 'id';
        $columns    = array(
            array('db' => 'id', 'dt' => $this->DATA_COLUMN_KEY),
            ///array('db' => 'discount_fixed', 'dt' => -1),
            //array('db' => 'discount_percentage', 'dt' => -1),
            array('db' => 'start', 'dt' => 1),
            array('db' => 'code', 'dt' => 0),
            //array('db' => 'description',  'dt' => 1),
            array('db' => 'expiration', 'dt' => 2),
            array(
                'db'        => 'currency',
                'dt'        => 3,
                'formatter' => function ($d, $row) {
                    $discount = '';
                    if ($row['currency']) {
                        $discount .= $row['currency'] . ' ';
                    }
                    // if ($row['discount_fixed']) {
                    //     $discount .= $row['discount_fixed'] . ' fixed plus ';
                    // }
                    // if ($row['discount_percentage']) {
                    //     $discount .= $row['discount_percentage'] . '% discount';
                    // }
                    return $discount;
                },
            ),
            //array('db' => 'minimum_order_amount',   'dt' => 4),
            //array('db' => 'maximum_usage',   'dt' => 5),
            array('db' => 'uses', 'dt' => 3),
            array('db' => 'usage_count', 'dt' => 4),
            //array('db' => 'initial_amount',   'dt' => 7),
            //array('db' => 'email',   'dt' => 8),
            array(
                'db'        => 'id',
                'dt'        => 9,
                'formatter' => function ($d, $row) {

                    $id   = $row['id'];
                    $code = $row['code'];
                    //$description = $row['description'];
                    $start = date('Y-m-d H:i', strtotime($row['start']));
                    if ($row['expiration']) {
                        $expiration = date('Y-m-d H:i', strtotime($row['expiration']));
                    } else {
                        $expiration = '';
                    }
                    $currency = $row['currency'];
                    //$discountFixed = $row['discount_fixed'];
                    //$discountPercentage = $row['discount_percentage'];
                    //$minimumOrderAmount = $row['minimum_order_amount'];
                    //$maximumUsage = $row['maximum_usage'];
                    //$initialAmount = $row['initial_amount'];
                    //$email = $row['email'];

                    $functionCall = "(
                        '$id',
                        '$code',
                        '$start',
                        '$expiration'
                    )";

                    // return '<button title="edit" onclick="editCoupon' . $functionCall . '" class="btn btn-default">
                    //     <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    // </button>

                    // <button title="copy" onclick="copyCoupon' . $functionCall . '" class="btn btn-default">
                    //     <span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span>
                    // </button>

                    // <button title="report" onclick="showReport' . $functionCall . '" class="btn btn-default">
                    //     <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
                    // </button>

                    // <button title="delete" onclick="deleteCoupon' . $functionCall . '" class="btn btn-default">
                    //     <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    // </button>';
                },
            ),
        );

        $sql_details = array(
            'user' => config('database.connections.mysql.username'),
            'pass' => config('database.connections.mysql.password'),
            'db'   => config('database.connections.mysql.database'),
            'host' => config('database.connections.mysql.host'),
        );

        $tableArray = SSP::simple($_GET, $sql_details, $tableName, $primaryKey, $columns);

        $couponIds = array_map(
            function ($data) {
                return $data[$this->DATA_COLUMN_KEY];
            },
            $tableArray['data']
        );

        $couponCounts = Coupon::usageCount($couponIds);

        $dataArray = array_map(
            function ($data) use ($couponCounts) {

                foreach ($couponCounts as $couponCount) {
                    if ($couponCount->coupon_id == $data[$this->DATA_COLUMN_KEY]) {
                        $data['6'] = $couponCount->count;
                    }
                }
                return $data;
            },
            $tableArray['data']
        );

        $tableArray['data'] = $dataArray;

        return response(
            json_encode($tableArray)
        );
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCouponRequest $request)
    {

        $httpClient = new Client;

        //name=my3+second+rule&description=my+first+rule&code=abc-xyz-123&start=2020-02-17&expiration=2020-02-17&fixed_discount=&percentage_discount=10&minimum_order=23&maximum_usage=10

        $data = array(
            'name'                => $request->get('code'),
            'description'         => $request->get('description'),
            'code'                => $request->get('code'),
            'start'               => $request->get('start'),
            'expiration'          => $request->get('expiration'),
            'fixed_discount'      => $request->get('discount_fixed'),
            'percentage_discount' => $request->get('discount_percentage'),
            'minimum_order'       => $request->get('minimum_order_amount'),
            'maximum_usage'       => $request->get('maximum_usage'),

        );
        $queryString = http_build_query($data);

        try {
            $url      = 'https://devsite.sololuxury.com/contactcustom/index/createCoupen?' . $queryString;
            $response = $httpClient->get($url);

            Coupon::create($request->all());
            return response(
                json_encode([
                    'message' => 'Created new coupon',
                    'body'    => $response->getBody(),
                    'code'    => $response->getStatusCode(),
                    'url'     => $url,
                ])
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Unable to create coupon',
                    'error'   => $e->getMessage(),
                ],
                500
            );
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

        $request->validate([
            'code'                 => 'required',
            'description'          => 'required',
            'start'                => 'required|date_format:Y-m-d H:i',
            'expiration'           => 'sometimes|nullable|date_format:Y-m-d H:i|after:start',
            'discount_fixed'       => 'nullable|numeric',
            'discount_percentage'  => 'sometimes|nullable|numeric',
            'minimum_order_amount' => 'sometimes|nullable|integer',
            'maximum_usage'        => 'sometimes|nullable|integer',
        ]);

        $validated = $request->all();

        //
        try {
            $coupon                       = Coupon::findOrFail($id);
            $coupon->code                 = $validated['code'];
            $coupon->description          = $validated['description'];
            $coupon->start                = $validated['start'];
            $coupon->expiration           = $validated['expiration'];
            $coupon->discount_fixed       = $validated['discount_fixed'];
            $coupon->discount_percentage  = $validated['discount_percentage'];
            $coupon->minimum_order_amount = $validated['minimum_order_amount'];
            $coupon->maximum_usage        = $validated['maximum_usage'];
            $coupon->initial_amount       = $validated['initialAmount'];
            $coupon->email                = $validated['email'];
            $coupon->save();

            return response(
                json_encode([
                    'message' => 'Updated coupon',
                ])
            );
        } catch (ModelNotFoundException $e) {

            return response(
                json_encode([
                    'message' => 'Did not find coupon with id: ' . $id,
                ]),
                404
            );
        }
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
        $count = Coupon::destroy($id);
        if ($count == 1) {
            return response(
                json_encode([
                    'message' => 'Deleted the coupon',
                ])
            );
        } else {
            return response(
                json_encode([
                    'message' => 'Failed to delete coupon. It might be not present',
                ]),
                404
            );
        }
    }

    public function showReport($couponId = null)
    {

        $start = Input::get('start');
        $end   = Input::get('end');

        if (isset($couponId)) {
            $orders = Order::where('coupon_id', $couponId)
                ->where('order_date', '>=', Carbon::parse($start))
                ->where('order_date', '<=', Carbon::parse($end))
                ->get();
        } else {
            $orders = Order::where('order_date', '>=', Carbon::parse($start))
                ->where('order_date', '<=', Carbon::parse($end))
                ->get();
        }

        $couponWithOrders = array();

        foreach ($orders as $order) {
            $couponId = $order->coupon_id;

            if (isset($couponWithOrders[$couponId])) {
                $couponWithOrders[$couponId][] = $order->toArray();
            } else {
                $couponWithOrders[$couponId] = array($order->toArray());
            }
        }

        $response = array();
        foreach ($couponWithOrders as $couponId => $orders) {
            $response[] = array(
                'coupon_id' => $couponId,
                'orders'    => $orders,
            );
        }

        return response(
            json_encode($response)
        );
    }

    public function addRules(Request $request)
    {   
        $store_website = StoreWebsite::where('id', $request->store_website_id)->first();
        $store_lables  = [];
        if(!empty($request->store_labels)) {
            foreach ($request->store_labels as $key => $lables) {
                array_push($store_lables, ['store_id' => $key, 'store_label' => $lables, 'extension_attributes' => '{}']);
            }
        }

        $startDate = !empty($request->start) ? $request->start : date("Y-m-d");
        $endDate   = $request->expiration;
        $timeUsed  = 6;

        $authorization      = "Authorization: Bearer " . $store_website->api_token;
        $parameters         = [];
        $parameters['rule'] = [
            "name"                  => $request->name,
            "store_labels"          => $store_lables,
            "description"           => $request->description,
            "website_ids"           => array_unique($request->website_ids),
            "customer_group_ids"    => $request->customer_groups,
            "from_date"             => $startDate,
            "to_date"               => $endDate,
            "uses_per_customer"     => $request->uses_per_coustomer,
            "is_active"             => $request->active == "1" ? true : false,
            "condition"             => [
                "condition_type"  => "",
                "conditions"      => [
                    [
                        "condition_type" => "",
                        "operator"       => "",
                        "attribute_name" => "",
                        "value"          => "",
                    ],
                ],
                "aggregator_type" => "",
                "operator"        => null,
                "value"           => "",
            ],
            "action_condition"      => [
                "condition_type"  => "",
                "conditions"      => [
                    [
                        "condition_type"  => "",
                        "conditions"      => [

                        ],
                        "aggregator_type" => "",
                        "operator"        => null,
                        "value"           => "",
                    ],
                ],
                "aggregator_type" => "",
                "operator"        => null,
                "value"           => "",
            ],
            "stop_rules_processing" => $request->stop_rules_processing,
            "is_advanced"           => true,
            "sort_order"            => 0,
            "simple_action"         => $request->simple_action,
            "discount_amount"       => $request->discount_amount,
            "discount_step"         => $request->discount_step,
            "discount_qty"          => $request->discount_qty,
            "apply_to_shipping"     => $request->apply_to_shipping,
            "times_used"            => $timeUsed,
            "is_rss"                => $request->rss,
            "coupon_type"           => $request->coupon_type, // or "coupon_type" => "SPECIFIC_COUPON",
            "use_auto_generation"   => isset($request->auto_generate) ? true : false, // use true if want to generate multiple codes for same rule
            "uses_per_coupon"       => $request->uses_per_coupon,
            "simple_free_shipping"  => "0",
            //"store_website_id" => $request->store_website_id
        ];


        $url = $store_website->magento_url . "/rest/V1/salesRules/";
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
        \Log::channel('listMagento')->info(print_r([$url,$store_website->api_token,json_encode($parameters)],true));
        $response = curl_exec($ch);
        $result   = json_decode($response);
        curl_close($ch); // Close the connection
        if (isset($result->code)) {
            return response()->json(['type' => 'error', 'message' => $result->message, 'data' => $result], 200);
        }

        if ($request->coupon_type == "SPECIFIC_COUPON" && !isset($request->auto_generate)) {
            $response = $this->geteratePrimaryCouponCode($request->code, $result->rule_id, $request->uses_per_coustomer, $request->expiration, '', $store_website);
            if(!isset($response->coupon_id)) {
                $this->deleteCouponCodeRuleByWebsiteId($result->rule_id,$request->store_website_id);
                if(isset($response->message) && isset($response->parameters)) {
                    return response()->json(['type' => 'error', 'message' => str_replace("%1", $response->parameters[0], $response->message), 'data' => $result], 200);
                }else{
                    return response()->json(['type' => 'error', 'message' => "Coupon code cannot be create please check request with magento log", 'data' => $response], 200);
                }
            }
        }

        $local_rules                      = new CouponCodeRules();
        $local_rules->magento_rule_id     = $result->rule_id;
        $local_rules->name                = $request->name;
        $local_rules->description         = $request->description;
        $local_rules->is_active           = $request->active == "1" ? true : false;
        $local_rules->times_used          = $timeUsed;
        $local_rules->website_ids         = implode(',', $request->website_ids);
        $local_rules->customer_group_ids  = implode(',', $request->customer_groups);
        $local_rules->coupon_type         = $request->coupon_type;
        $local_rules->coupon_code         = $request->code;
        $local_rules->use_auto_generation = isset($request->auto_generate) ? 1 : 0;

        $local_rules->uses_per_coupon    = !empty($request->uses_per_coupon) ? $request->uses_per_coupon : 0;
        $local_rules->uses_per_coustomer = !empty($request->uses_per_coustomer) ? $request->uses_per_coustomer : 0;

        $local_rules->store_website_id      = $request->store_website_id;
        $local_rules->is_rss                = isset($request->rss) ? $request->rss : 0;
        $local_rules->priority              = isset($request->priority) ? $request->priority : 0;
        $local_rules->from_date             = $startDate;
        $local_rules->to_date               = $endDate;
        $local_rules->stop_rules_processing = $request->stop_rules_processing;
        $local_rules->simple_action         = $request->simple_action;
        $local_rules->discount_amount       = $request->discount_amount;
        $local_rules->discount_step         = $request->discount_step;
        $local_rules->discount_qty          = $request->discount_qty;
        $local_rules->apply_to_shipping     = $request->apply_to_shipping;
        $local_rules->simple_free_shipping  = 0;
        if ($local_rules->save()) {
            if(!empty($request->store_labels)) {
                foreach ($request->store_labels as $key => $label) {
                    $store_view_value                = new WebsiteStoreViewValue();
                    $store_view_value->rule_id       = $local_rules->id;
                    $store_view_value->store_view_id = $key;
                    $store_view_value->value         = $label;
                    $store_view_value->save();
                }
            }
        }
        return response()->json(['type' => "success", 'data' => $result, 'message' => "Added successfully"], 200);
    }

    public function geteratePrimaryCouponCode($code, $magento_rule_id, $uses_per_customer, $end_date, $laravel_rule_id = "", $store_website_id)
    {

        $authorization        = "Authorization: Bearer " . $store_website_id->api_token;
        $parameters           = [];
        $parameters['coupon'] = [
            "code"                 => $code,
            "rule_id"              => $magento_rule_id,
            "usage_limit"          => 0,
            "usage_per_customer"   => $uses_per_customer,
            "expiration_date"      => $end_date,
            "is_primary"           => true,
            "times_used"           => 6,
            "created_at"           => date('Y-m-d H:i:s'),
            "type"                 => 0,
            "extension_attributes" => json_encode('{}'),
        ];

        $url = $store_website_id->magento_url . "/rest/V1/coupons";
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
        $response = curl_exec($ch);
        $result   = json_decode($response);
        curl_close($ch); // Close the connection

        \Log::channel('listMagento')->info(print_r([$url,$store_website_id->api_token,json_encode($parameters)],true));

        return $result;
    }

    public function getCouponCodeRuleById(Request $request)
    {
        // $authorization = "Authorization: Bearer u75tnrg0z2ls8c4yubonwquupncvhqie";
        // $url = "https://sololuxury.com/rest/V1/salesRules/".$request->rule_id;
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $response = curl_exec($ch);
        // $result = json_decode($response);
        // curl_close($ch); // Close the connection

        $result  = CouponCodeRules::with(['store_labels'])->where('id', $request->rule_id)->first();
        $codes   = Coupon::where('rule_id', $result->magento_rule_id)->orderBy('created_at', 'desc')->get();
        $web_ids = explode(',', $result->website_ids);
        if (isset($result->id)) {
            $websites       = Website::where('store_website_id', $result->store_website_id)->get();
            $website_stores = WebsiteStore::with('storeView')->get();
            $store_websites = StoreWebsite::all();
            $returnHTML     = view('coupon.editModal')->with('result', $result)->with('websites', $websites)->with('website_stores', $website_stores)->with('web_ids', $web_ids)->with('store_websites', $store_websites)->with('codes', $codes)->render();
            return response()->json(['status' => 'success', 'data' => ['html' => $returnHTML], 'message' => "Rule details"], 200);
        } else {
            return response()->json(['status' => 'error', 'data' => $result, 'message' => 'Something went wrong!'], 200);
        }
    }

    public function deleteCouponCodeRuleByWebsiteId($id,$storeWebsiteID)
    {
        $store_website = StoreWebsite::where('id', $storeWebsiteID)->first();
        $authorization = "Authorization: Bearer " . $store_website->api_token;
        $url           = $store_website->magento_url . "/rest/V1/salesRules/salesRules/" . $id;
        $ch            = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response   = curl_exec($ch);
        $result     = json_decode($response);
        
        return true;
    }

    public function deleteCouponCodeRuleById($id)
    {
        $rule_lists    = CouponCodeRules::where('id', $id)->first();
        $store_website = StoreWebsite::where('id', $rule_lists->store_website_id)->first();
        $authorization = "Authorization: Bearer " . $store_website->api_token;
        $url           = $store_website->magento_url . "/rest/V1/salesRules/salesRules/" . $rule_lists->magento_rule_id;
        $ch            = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response   = curl_exec($ch);
        $result     = json_decode($response);
        $coupon     = Coupon::where('rule_id', $rule_lists->magento_rule_id)->delete();
        $rule_lists = CouponCodeRules::where('id', $id)->delete();
        return redirect()->route('coupons.index');
    }

    public function generateCouponCode(Request $request)
    {

        $rule_id = CouponCodeRules::where('id', $request->rule_id)->first();
        $format  = "alphanum";

        if ($request->format == 1) {
            $format = "alphanum";
        }

        if ($request->format == 2) {
            $format = "alpha";
        }

        if ($request->format == 3) {
            $format = "num";
        }

        $parameters = [
            "couponSpec" => [
                "rule_id"            => $rule_id->magento_rule_id,
                "format"             => $format,
                "quantity"           => $request->qty,
                "length"             => $request->length,
                "prefix"             => $request->prefix,
                "suffix"             => $request->suffix,
                "delimiter_at_every" => $request->dash,
                "delimiter"          => "-",
            ],
        ];

        $store_website = StoreWebsite::where('id', $rule_id->store_website_id)->first();
        $authorization = "Authorization: Bearer " . $store_website->api_token;
        $url           = $store_website->magento_url . "/rest/V1/coupons/generate";
        $ch            = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
        $response = curl_exec($ch);
        $result   = json_decode($response);
        curl_close($ch); // Close the connection
        if (isset($result->message)) {
            return response()->json(['type' => 'error', 'message' => $result->message, 'data' => $result], 200);
        }

        foreach ($result as $re) {
            $coupon                       = new Coupon();
            $coupon->start                = date('Y-m-d H:i:s');
            $coupon->magento_id           = 0;
            $coupon->rule_id              = $rule_id->magento_rule_id;
            $coupon->code                 = $re;
            $coupon->usage_count          = $rule_id->times_used;
            $coupon->minimum_order_amount = 0;
            $coupon->discount_fixed       = 0.0;
            $coupon->discount_percentage  = 0.0;
            $coupon->uses                 = $rule_id->uses_per_coupon;
            $coupon->save();
        }

        return response()->json(['type' => "success", 'data' => $result, 'message' => "Added successfully"], 200);
    }

    public function updateRules(Request $request)
    {
        $store_lables = [];
        if(!empty($request->store_labels)) {
            foreach ($request->store_labels as $key => $lables) {
                array_push($store_lables, ['store_id' => $key, 'store_label' => $lables, 'extension_attributes' => '{}']);
            }
        }

        $local_rules        = CouponCodeRules::where('id', $request->rule_id)->first();
        $store_website      = StoreWebsite::where('id', $local_rules->store_website_id)->first();
        $authorization      = "Authorization: Bearer " . $store_website->api_token;
        $parameters         = [];
        $parameters['rule'] = [
            "name"                  => $request->name_edit,
            "store_labels"          => $store_lables,
            "description"           => $request->description_edit,
            "website_ids"           => array_unique($request->website_ids_edit),
            "customer_group_ids"    => $request->customer_groups_edit,
            "from_date"             => $request->start_edit,
            "to_date"               => $request->expiration_edit,
            "uses_per_customer"     => $request->uses_per_coustomer_edit,
            "is_active"             => $request->active_edit == 1 ? true : false,
            "condition"             => [
                "condition_type"  => "",
                "conditions"      => [
                    [
                        "condition_type" => "",
                        "operator"       => "",
                        "attribute_name" => "",
                        "value"          => "",
                    ],
                ],
                "aggregator_type" => "",
                "operator"        => null,
                "value"           => "",
            ],
            "action_condition"      => [
                "condition_type"  => "",
                "conditions"      => [
                    [
                        "condition_type"  => "",
                        "conditions"      => [

                        ],
                        "aggregator_type" => "",
                        "operator"        => null,
                        "value"           => "",
                    ],
                ],
                "aggregator_type" => "",
                "operator"        => null,
                "value"           => "",
            ],
            "stop_rules_processing" => $request->stop_rules_processing,
            "is_advanced"           => true,
            "sort_order"            => 0,
            "simple_action"         => $request->simple_action,
            "discount_amount"       => $request->discount_amount,
            "discount_step"         => $request->discount_step,
            "discount_qty"          => $request->discount_qty,
            "apply_to_shipping"     => $request->apply_to_shipping,
            "times_used"            => 6,
            "is_rss"                => isset($request->rss_edit) ? true : false,
            "coupon_type"           => $request->coupon_type_edit, // or "coupon_type" => "SPECIFIC_COUPON",
            "use_auto_generation"   => isset($request->auto_generate_edit) ? true : false, // use true if want to generate multiple codes for same rule
            "uses_per_coupon"       => $request->uses_per_coupon_edit,
            "simple_free_shipping"  => "0",
        ];

        $url = $store_website->magento_url . "/rest/V1/salesRules/" . $local_rules->magento_rule_id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
        $response = curl_exec($ch);
        $result   = json_decode($response);

        \Log::channel('listMagento')->info(print_r([$url,$store_website->api_token,json_decode($response)],true));

        curl_close($ch); // Close the connection
        if (isset($result->code) || isset($result->message)) {
            return response()->json(['type' => 'error', 'message' => $result->message, 'data' => $result], 200);
        }

        if ($request->coupon_type == "SPECIFIC_COUPON" && !isset($request->use_auto_generation)) {
            $this->geteratePrimaryCouponCode($request->code, $result->rule_id, $request->uses_per_coustomer, $request->expiration, $store_website);
        }

        $local_rules->name                = $request->name_edit;
        $local_rules->description         = $request->description;
        $local_rules->is_active           = $request->active == "1" ? true : false;
        $local_rules->times_used          = 6;
        $local_rules->website_ids         = implode(',', array_unique($request->website_ids_edit));
        $local_rules->customer_group_ids  = implode(',', array_unique($request->customer_groups_edit));
        $local_rules->coupon_type         = $request->coupon_type_edit;
        $local_rules->use_auto_generation = isset($request->auto_generate_edit) ? 1 : 0;

        $local_rules->uses_per_coupon    = !empty($request->uses_per_coupon_edit) ? $request->uses_per_coupon_edit : 0;
        $local_rules->uses_per_coustomer = !empty($request->uses_per_coustomer_edit) ? $request->uses_per_coustomer_edit : 0;

        $local_rules->store_website_id      = $request->store_website_id_edit;
        $local_rules->is_rss                = isset($request->rss_edit) ? 1 : 0;
        $local_rules->coupon_code           = $request->code_edit;
        $local_rules->priority              = $request->priority_edit;
        $local_rules->from_date             = !empty($request->start_edit) ? $request->start_edit : date('Y-m-d H:i:s');
        $local_rules->to_date               = !empty($request->expiration_edit) ? $request->expiration_edit : null;
        $local_rules->stop_rules_processing = $request->stop_rules_processing;
        $local_rules->simple_action         = $request->simple_action;
        $local_rules->discount_amount       = $request->discount_amount;
        $local_rules->discount_step         = $request->discount_step;
        $local_rules->discount_qty          = $request->discount_qty;
        $local_rules->apply_to_shipping     = $request->apply_to_shipping;
        $local_rules->simple_free_shipping  = 0;
        if ($local_rules->save()) {
            WebsiteStoreViewValue::where('rule_id', $request->rule_id)->delete();
            if(!empty($request->store_labels)) {
                foreach ($request->store_labels as $key => $label) {
                    $store_view_value                = new WebsiteStoreViewValue();
                    $store_view_value->rule_id       = $local_rules->id;
                    $store_view_value->store_view_id = $key;
                    $store_view_value->value         = $label;
                    $store_view_value->save();
                }
            }
        }

        return response()->json(['type' => "success", 'data' => $result, 'message' => "Data updated successfully"], 200);
    }

    public function getWebsiteByStore(Request $request)
    {
        $store_id   = $request->store_id;
        $websites   = Website::where('store_website_id', $store_id)->get();
        $returnHTML = view('coupon.wepsiteDrpDwn')->with('websites', $websites)->render();
        return response()->json(['type' => "success", 'data' => $returnHTML, 'message' => ""], 200);
    }

    public function deleteCouponByCode(Request $request)
    {

        $coupon        = Coupon::where('id', $request->id)->first();
        $local_rules   = CouponCodeRules::where('id', $coupon->rule_id)->first();
        $store_website = StoreWebsite::where('id', $local_rules->store_website_id)->first();

        $authorization = "Authorization: Bearer " . $store_website->api_token;
        $parameters    = ["codes" => array($coupon->code), "ignoreInvalidCoupons" => true];
        $url           = $store_website->magento_url . "/rest/V1/coupons/deleteByCodes";

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

        $response = curl_exec($ch);
        $result   = json_decode($response);
        $coupon   = Coupon::where('id', $request->id)->delete();
        curl_close($ch); // Close the connection

        // if(isset($result->missing_items) && !empty($result->missing_items)){
        //     return response()->json(['type' => 'error','message' => $result->message,'data' => $result],200);
        // }

        return response()->json(['type' => "success", 'data' => [], 'message' => "Coupon deleted successfully"], 200);
    }
}

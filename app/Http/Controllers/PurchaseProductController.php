<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Brand;
use App\Order;
use App\Helpers;
use App\StoreWebsite;
use App\User;
use App\Helpers\OrderHelper;
use App\Customer;
use App\SupplierDiscountInfo;
use App\ProductSupplier;
use App\OrderProduct;
use Excel;
use App\Supplier;
use Carbon\Carbon;
use App\Exports\EnqueryExport;
use Storage;
use App\Mails\Manual\PurchaseExport;
use Mail;
use App\Email;
use App\InventoryStatus;
use App\ChatMessage;
use App\Product;
use App\SupplierOrderInquiryData;
use App\PurchaseProductOrder;
use App\PurchaseProductOrderLog;
use App\PurchaseProductOrderImage;
use App\Setting;
use App\SupplierOrderTemplate;
use App\PurchaseProductOrderExcelFile;
use App\Imports\CustomerNumberImport;
use File;
use App\Exports\FileExcelExport;
use App\PurchaseProductOrderExcelFileVersion;
use Illuminate\Support\Facades\Log;

class PurchaseProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
		$filter_customer = $request->input('filter_customer');
        $filter_supplier = $request->filter_supplier ?? '';
        $filter_selling_price = $request->input('filter_selling_price');
		$filter_order_date = $request->input('filter_order_date');
		$filter_date_of_delivery = $request->input('filter_date_of_delivery');
        $filter_inventory_status_id = $request->input('filter_inventory_status_id');
        $order_status = $request->status ?? [''];
		$date = $request->date ?? '';
		$brandList = \App\Brand::all()->pluck("name","id")->toArray();
		$brandIds = array_filter($request->get("brand_id",[]));
        $registerSiteList = StoreWebsite::pluck('website', 'id')->toArray();
        //$product_suppliers_list=ProductSupplier::all();
        //$product_suppliers_list=array();
        $product_suppliers_list = Supplier::where(function ($query) {
            $query->whereNotNull('email')->orWhereNotNull('default_email');
        })->get();

		if($request->input('orderby') == '')
				$orderby = 'DESC';
		else
				$orderby = 'ASC';


		switch ($request->input('sortby')) {
			case 'type':
					 $sortby = 'order_type';
					break;
			case 'date':
					 $sortby = 'order_date';
					break;
			case 'estdeldate':
					$sortby = 'estimated_delivery_date';
					   break;
			case 'order_handler':
					 $sortby = 'sales_person';
					break;
			case 'client_name':
					 $sortby = 'client_name';
					break;
			case 'status':
					 $sortby = 'order_status_id';
					break;
			case 'advance':
					 $sortby = 'advance_detail';
					break;
			case 'balance':
					 $sortby = 'balance_amount';
					break;
			case 'action':
					 $sortby = 'action';
					break;
			case 'due':
					 $sortby = 'due';
					break;
			case 'communication':
					 $sortby = 'communication';
					break;
			default :
					 $sortby = 'order_date';
		}
		$orders = (new Order())->newQuery()->with('customer')->leftJoin("store_website_orders as swo","swo.order_id","orders.id");
		if(empty($term))
			$orders = $orders;
		else{
			/* $orders = $orders->whereHas('customer', function($query) use ($term) {
				return $query->where('name', 'LIKE', '%'.$term.'%')
							->orWhere('id', 'LIKE', '%'.$term.'%')
							->orWhere('email', 'LIKE', '%'.$term.'%');
			})
           ->orWhere('orders.order_id','like','%'.$term.'%')
           ->orWhere('order_type',$term)
           ->orWhere('sales_person',Helpers::getUserIdByName($term))
           ->orWhere('received_by',Helpers::getUserIdByName($term))
           ->orWhere('client_name','like','%'.$term.'%')
           ->orWhere('city','like','%'.$term.'%')
           ->orWhere('order_status_id',(new \App\ReadOnly\OrderStatus())->getIDCaseInsensitive($term)); */

           /* $orders = $orders->whereHas('customer', function($query) use ($filter_customer) {
               if($filter_customer!=''){
                return $query->where('name', 'LIKE', '%'.$filter_customer.'%');
               }
                        //->orWhere('id', 'LIKE', '%'.$filter_customer.'%')
                        //->orWhere('email', 'LIKE', '%'.$filter_customer.'%');
        }); */
       /* ->orWhere('orders.order_id','like','%'.$term.'%')
       ->orWhere('order_type',$term)
       ->orWhere('sales_person',Helpers::getUserIdByName($term))
       ->orWhere('received_by',Helpers::getUserIdByName($term))
       ->orWhere('client_name','like','%'.$term.'%')
       ->orWhere('city','like','%'.$term.'%') */
       //$orders->orWhere('order_status_id',(new \App\ReadOnly\OrderStatus())->getIDCaseInsensitive($term));
       
        }
            $orders = $orders->whereHas('customer', function($query) use ($filter_customer) {
                if($filter_customer!=''){
                return $query->where('name', 'LIKE', '%'.$filter_customer.'%');
                }
                        //->orWhere('id', 'LIKE', '%'.$filter_customer.'%')
                        //->orWhere('email', 'LIKE', '%'.$filter_customer.'%');
            });
        if ($filter_order_date != '') {
			$orders = $orders->where('order_date', $filter_order_date);
        }
        if ($filter_date_of_delivery != '') {
			$orders = $orders->where('date_of_delivery', $filter_date_of_delivery);
		}
		if ($order_status[0] != '') {
			$orders = $orders->whereIn('order_status_id', $order_status);
		}

		if ($date != '') {
			$orders = $orders->where('order_date', $date);
		}

		if ($store_site = $request->store_website_id) {
		    $orders = $orders->where('swo.website_id', $store_site);
		}

		$statusFilterList =  clone($orders);
		
		$orders = $orders->leftJoin("order_products as op","op.order_id","orders.id")
		->leftJoin("customers as cs","cs.id","orders.customer_id")
        ->leftJoin("products as p","p.id","op.product_id")
        ->leftJoin("product_suppliers as ps","ps.product_id","op.product_id");

		if(!empty($brandIds)) {
			$orders = $orders->whereIn("p.brand",$brandIds);
		}
		$orders = $orders->groupBy("op.id");
		$orders = $orders->select(["orders.*","op.id as order_product_id","op.product_price","op.product_id as product_id","op.supplier_discount_info_id","op.inventory_status_id"]);
        if($filter_selling_price!=''){
            $orders->where('op.product_price',$filter_selling_price);
        }
        if($filter_inventory_status_id!=''){
            $orders->where('op.inventory_status_id',$filter_inventory_status_id);
        }

        if($filter_supplier!=''){
            //$typeWhereClause .= ' AND suppliers.id IN (' . implode(",", $supplier_filter) . ')';
            $orders->whereIn('ps.supplier_id',$filter_supplier);
            //$filter_supplier=implode(",",$filter_supplier);
        }

		$users  = Helpers::getUserArray( User::all() );
		$order_status_list = OrderHelper::getStatus();

		if ($sortby != 'communication' && $sortby != 'action' && $sortby != 'due') {
			$orders = $orders->orderBy('is_priority', 'DESC')->orderBy($sortby, $orderby);
		} else {
			$orders = $orders->orderBy('is_priority', 'DESC')->orderBy('created_at', 'DESC');
		}

		$statusFilterList = $statusFilterList->leftJoin("order_statuses as os","os.id","orders.order_status_id")
		->where("order_status","!=", '')->groupBy("order_status")->select(\DB::raw("count(*) as total"),"os.status as order_status","swo.website_id")->get()->toArray();
		$totalOrders = sizeOf($orders->get());
        $orders_array = $orders->paginate(10);
        
        $inventoryStatusQuery = InventoryStatus::query();
        $inventoryStatus=$inventoryStatusQuery->pluck('name','id');
        //echo'<pre>'.print_r($orders_array,true).'</pre>'; exit;
        return view('purchase-product.index', compact('orders_array', 'users', 'orderby', 
        'order_status_list', 'order_status', 'date','statusFilterList','brandList', 'registerSiteList', 'store_site','totalOrders','inventoryStatus','product_suppliers_list','filter_supplier','filter_customer','filter_selling_price','filter_order_date','filter_date_of_delivery','filter_inventory_status_id') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCustomerDetails($type, $order_id) {
        $data = null;
        if($type == 'customer') {
            $data = Customer::join('orders','orders.customer_id','customers.id')->where('orders.id',$order_id)->select('customers.*')->first();
            return view('purchase-product.partials.customer_info',compact('data'));
        }
        if($type == 'order') {
            $data = Order::leftJoin("order_products as op","op.order_id","orders.id")->where('orders.id',$order_id)->leftJoin("products as p","p.id","op.product_id")
            ->leftJoin("brands as b","b.id","p.brand")->select(["orders.*",\DB::raw("group_concat(b.name) as brand_name_list")])->first();
            return view('purchase-product.partials.order_info',compact('data'));
        }
    }
    public function getSupplierDetails($order_product_id, Request $request)
    {
        $order_product = OrderProduct::find($order_product_id);

        $suppliers = \App\ProductSupplier::join('suppliers','suppliers.id','product_suppliers.supplier_id')->where('product_suppliers.product_id',$order_product->product_id)->select('suppliers.*','product_suppliers.id as ps_id','product_suppliers.price as product_price','suppliers.id as supplier_id','product_suppliers.product_id','product_suppliers.supplier_link')->get();
        return view('purchase-product.partials.supplier_info',compact('suppliers','order_product'));
    }

    public function saveDiscount(Request $request)
    {
        $discount = SupplierDiscountInfo::where('product_id', $request->product_id)->where('supplier_id',$request->supplier_id)->first();
        if($discount) {
            $discount->discount = $request->discount;
            $discount->save();
        }
        else {
            $discount = new SupplierDiscountInfo;
            $discount->discount = $request->discount;
            $discount->product_id = $request->product_id;
            $discount->supplier_id = $request->supplier_id;
            $discount->save();
        }

        $order_product = OrderProduct::find($request->order_product_id);
        $suppliers = \App\ProductSupplier::join('suppliers','suppliers.id','product_suppliers.supplier_id')->where('product_suppliers.product_id',$request->product_id)->select('suppliers.*','product_suppliers.id as ps_id','product_suppliers.price as product_price','suppliers.id as supplier_id','product_suppliers.product_id')->get();

        $html  = (string)view('purchase-product.partials.supplier_info',compact('suppliers','order_product'));

        return response()->json(['message' => 'Successfull','html' => $html,'code' => 200]);
    }

    

    public function saveFixedPrice(Request $request)
    {
        $fixed_price = SupplierDiscountInfo::where('product_id', $request->product_id)->where('supplier_id',$request->supplier_id)->first();
        if($fixed_price) {
            $fixed_price->fixed_price = $request->fixed_price;
            $fixed_price->save();
        }
        else {
            $fixed_price = new SupplierDiscountInfo;
            $fixed_price->fixed_price = $request->fixed_price;
            $fixed_price->product_id = $request->product_id;
            $fixed_price->supplier_id = $request->supplier_id;
            $fixed_price->save();
        }

        $order_product = OrderProduct::find($request->order_product_id);
        $suppliers = \App\ProductSupplier::join('suppliers','suppliers.id','product_suppliers.supplier_id')->where('product_suppliers.product_id',$request->product_id)->select('suppliers.*','product_suppliers.id as ps_id','product_suppliers.price as product_price','suppliers.id as supplier_id','product_suppliers.product_id')->get();

        $html  = (string)view('purchase-product.partials.supplier_info',compact('suppliers','order_product'));

        return response()->json(['message' => 'Successfull','html'=> $html,'code' => 200]);
    }

    public function saveDefaultSupplier(Request $request) {
        if(!$request->product_id || !$request->order_product || !$request->supplier_id) {
            return response()->json(['message' => 'Supplier not found','code' => 500]);
        }
        $discount_info = SupplierDiscountInfo::where('product_id', $request->product_id)->where('supplier_id',$request->supplier_id)->first();
        if($discount_info) {
            $discount_info->save();
            $order_product = OrderProduct::find($request->order_product);
            if($order_product) {
                $order_product->supplier_discount_info_id = $discount_info->id;
                $order_product->save();
            }
        }
        else {
            $discount_info = new SupplierDiscountInfo;
            $discount_info->product_id = $request->product_id;
            $discount_info->supplier_id = $request->supplier_id;
            $discount_info->save();
            $order_product = OrderProduct::find($request->order_product);
            if($order_product) {
                $order_product->supplier_discount_info_id = $discount_info->id;
                $order_product->save();
            }
        }
        return response()->json(['message' => 'Successfull','code' => 200]);
    }

    public function getSuppliers(Request $request) {
        // START - Purpose : Comment Code - DEVTASK-4048
        // $term = $request->term;
        // $suppliers =  ProductSupplier::join('suppliers','suppliers.id','product_suppliers.supplier_id')
        // ->join('order_products','order_products.product_id','product_suppliers.product_id');
        // if($request->term) {
        //     $suppliers =  $suppliers->where('suppliers.supplier' ,'like', '%'.$request->term.'%');
        // }
        // $suppliers = $suppliers->groupBy('product_suppliers.supplier_id')->select('suppliers.*')->get();
        // return view('purchase-product.partials.suppliers',compact('suppliers','term'));
        // END - DEVTASK-4048

        // START - Purpose : Code with Product Inquiry Count - DEVTASK-4048
        $term = $request->term;
        $suppliers =  Supplier::withcount('inquiryproductdata')->join('product_suppliers','suppliers.id','product_suppliers.supplier_id')
        ->join('order_products','order_products.product_id','product_suppliers.product_id');
        
        if($request->term) {
            $suppliers =  $suppliers->where('suppliers.supplier' ,'like', '%'.$request->term.'%');
        }
        $suppliers = $suppliers->groupBy('product_suppliers.supplier_id')->orderBy('inquiryproductdata_count','desc')
        ->get();
        // END - DEVTASK-4048

        return view('purchase-product.partials.suppliers',compact('suppliers','term'));
    }

    public function getProducts($type, $supplier_id) {
        if($type == 'inquiry') {
            // $products = ProductSupplier::
            // join('products','products.id','product_suppliers.product_id')
            // ->join('order_products as op','op.product_id','products.id')
            // // ->where('product_suppliers.supplier_id',$supplier_id)
            // ->groupBy('product_id')
            // ->select('product_suppliers.price as product_price','products.*','products.id as product_id','product_suppliers.id as ps_id', 'product_suppliers.supplier_id as sup_id')
            // ->get();

            $products = OrderProduct::leftjoin('supplier_discount_infos','supplier_discount_infos.id','order_products.supplier_discount_info_id')
            ->join('products','products.id','order_products.product_id')
            ->join('product_suppliers','product_suppliers.product_id','products.id')
            ->where('product_suppliers.supplier_id',$supplier_id)
            ->orderBy('order_products.id', 'desc')
            /*->groupBy('supplier_discount_infos.id')*/
            ->select('product_suppliers.price as product_price','products.*','supplier_discount_infos.*','product_suppliers.id as ps_id','order_products.id as order_product_id','products.id as id')->get();

            return view('purchase-product.partials.products',compact('products','type','supplier_id'));
        }
        if($type == 'order') {
            $products = OrderProduct::leftjoin('supplier_discount_infos','supplier_discount_infos.id','order_products.supplier_discount_info_id')
            ->join('products','products.id','order_products.product_id')
            ->join('product_suppliers','product_suppliers.product_id','products.id')
            ->where('product_suppliers.supplier_id',$supplier_id)
            ->orderBy('order_products.id', 'desc')
            /*->groupBy('supplier_discount_infos.id')*/
            ->select('product_suppliers.price as product_price','products.*','supplier_discount_infos.*','product_suppliers.id as ps_id','order_products.id as order_product_id','products.id as id')->get();
           
            return view('purchase-product.partials.products',compact('products','type','supplier_id'));
        }
    }

    public function sendProducts($type,$supplier_id,Request $request)
    {
        if($type == 'inquiry') {

            // ChatMessage::sendWithChatApi('919825282', null, $message);

            $supplier = Supplier::find($supplier_id);            
            $path = "inquiry_exports/" . Carbon::now()->format('Y-m-d-H-m-s') . "_enquiry_exports.xlsx";
            $subject = 'Product Inquiry';
            $message = 'Please check below products';
            $product_ids = json_decode($request->product_ids, true);
            $order_ids = json_decode($request->order_ids, true);

            Excel::store(new EnqueryExport($product_ids,$order_ids,$path), $path, 'files');
            
            $emailClass = (new PurchaseExport($path, $subject, $message))->build();

            $email             = Email::create([
                'model_id'         => $supplier_id,
                'model_type'       => Supplier::class,
                'from'             => 'buying@amourint.com',
                'to'               => $supplier->email,
                'subject'          => $subject,
                'message'          => $message,
                'template'         => 'purchase-simple',
                'additional_data'  => json_encode(['attachment' => [$path]]),
                'status'           => 'pre-send',
                'is_draft'         => 0,
            ]);

            \App\Jobs\SendEmail::dispatch($email);

            // START - Purpose : Add Record for Inquiry - DEVTASK-4048

            $products_data = Product::whereIn('id',$product_ids)->get()->toArray();
            $product_names = array_column($products_data, 'name');
            $products_str = implode(", ",$product_names);
            $message = 'Please check Product enquiry : '.$products_str;

            $number = ($supplier->phone ? $supplier->phone : '971569119192' );

            $send_whatsapp = app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($number,$supplier->whatsapp_number, $message);

             //START - purpose : Add in ChatMessage -DEVTASK-4236
             $message_chat = ' Inquiry WhatsApp Message : '.$message;
             $params = [
                 'message' => $message_chat,
                 'supplier_id' => $supplier_id,
                 'additional_data' => json_encode(['attachment' => [$path]]),//Purpose : Add Excel sheet path - DEVTASK-4236
                 'user_id' => \Auth::id(),
             ];
             $chatMessage = ChatMessage::create($params);
             //END -DEVTASK-4236

            $getInquiryData = SupplierOrderInquiryData::where('type',$type)->get()->toArray();

            $pro_data_arr = array();
            foreach($getInquiryData as $key => $value){
                $pro_data_arr[$value['type']][$value['product_id']] = $value;
            }   


            $product_id = array_column($getInquiryData, 'product_id');

            $pro_arr = [];
            foreach ($product_ids as $key => $val)
            {
                if (!in_array($val, $product_id))
                {
                    $pro_arr[] = [
                        'supplier_id' => $supplier_id,
                        'product_id' => $val,
                        'type' => $type,
                        'count_number' => '1'
                    ];

                }
            }

            SupplierOrderInquiryData::insert($pro_arr);
            // END - DEVTASK-4048
            
            return response()->json(['message' => 'Successfull','code' => 200]);
        }

        if($type == 'order') {
            
            $supplier = Supplier::find($supplier_id);            
            $path = "order_exports/" . Carbon::now()->format('Y-m-d-H-m-s') . "_order_exports.xlsx";
            $subject = 'Product order';
            $message = 'Please check below product order request';
            $product_ids = json_decode($request->product_ids, true);
            $order_ids = json_decode($request->order_ids, true);//Purpose: Get order id - DEVTASK-4236
            
            Excel::store(new EnqueryExport($product_ids,$path), $path, 'files');
           
            $emailClass = (new PurchaseExport($path, $subject, $message))->build();

            $email             = Email::create([
                'model_id'         => $supplier_id,
                'model_type'       => Supplier::class,
                'from'             => 'buying@amourint.com',
                'to'               => $supplier->email,
                'subject'          => $subject,
                'message'          => $message,
                'template'         => 'purchase-simple',
                'additional_data'  => json_encode(['attachment' => [$path]]),
                'status'           => 'pre-send',
                'is_draft'         => 0,
            ]);

            \App\Jobs\SendEmail::dispatch($email);

            // START - Purpose : Add Record for Inquiry - DEVTASK-4048
            $products_data = Product::whereIn('id',$product_ids)->get()->toArray();
            $product_names = array_column($products_data, 'name');
            $products_str = implode(", ",$product_names);
            $message = 'Please check Product Order : '.$products_str;

            $number = ($supplier->phone ? $supplier->phone : '971569119192' );

            $send_whatsapp = app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($number,$supplier->whatsapp_number, $message);

            //START - purpose : Add in ChatMessage -DEVTASK-4236
            $message_chat = ' Order WhatsApp Message : '.$message;
            $params = [
                'message' => $message_chat,
                'supplier_id' => $supplier_id,
                'additional_data' => json_encode(['attachment' => [$path]]),//Purpose : Add Excel sheet path - DEVTASK-4236
                'user_id' => \Auth::id(),
            ];
            $chatMessage = ChatMessage::create($params);
            //END -DEVTASK-4236

            $getInquiryData = SupplierOrderInquiryData::where('type',$type)->get()->toArray();

            $pro_data_arr = array();
            foreach($getInquiryData as $key => $value){
                $pro_data_arr[$value['type']][$value['product_id']] = $value;
            }   


            $product_id = array_column($getInquiryData, 'product_id');

            $pro_arr = [];
            foreach ($product_ids as $key => $val)
            {
                if (!in_array($val, $product_id))
                {
                    $pro_arr[] = [
                        'supplier_id' => $supplier_id,
                        'product_id' => $val,
                        'type' => $type,
                        'count_number' => '1'
                    ];

                }
            }

            SupplierOrderInquiryData::insert($pro_arr);
            // END - DEVTASK-4048

            //START - Purpose : Get Order data - DEVTASK-4236
            
            $order_products_data = OrderProduct::whereIn('id',$order_ids)->get();

            $order_products_data_arr = OrderProduct::whereIn('id',$order_ids)->get()->toArray();
            $all_product_id = array_column($order_products_data_arr, 'product_id');

            $order_data = OrderProduct::
            // leftjoin('products','order_products.product_id','products.id')
            // ->leftjoin('brands','brands.id','products.brand')
            join('product_suppliers','product_suppliers.product_id','order_products.product_id')
            ->whereIn('order_products.id',$order_ids)
            ->where('product_suppliers.supplier_id',$supplier_id)
            ->select('product_suppliers.price as mrp','product_suppliers.price_special as price_special','product_suppliers.price_discounted as price_discounted','order_products.order_id as order_id')
            ->get();
            

            
            $order_data_total_mrp = 0;
            $order_data_total_price_discount = 0;
            $order_data_total_price_special = 0;
            foreach($order_data as $key => $val)
            {
                $order_data_total_mrp += $val->mrp;
                $order_data_total_price_discount += $val->price_discounted;
                $order_data_total_price_special += $val->price_special;
            }

            $order_pro_arr = [];

            // foreach ($order_products_data as $key => $val)
            // {
                $rand_order_no = rand(999,9999).'0'.rand(99,999);
                
                $order_pro_arr[] = [
                    'product_id' => '',
                    'order_products_id' => $request->product_ids,
                    'order_id'      => $rand_order_no,
                    'supplier_id' => $supplier_id,
                    'mrp_price' => $order_data_total_mrp,
                    'discount_price' => $order_data_total_price_discount,
                    'special_price' => $order_data_total_price_special,
                    'created_by' => \Auth::id(),
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'order_products_order_id' => $request->order_ids,
                ];
            // }
            

            PurchaseProductOrder::insert($order_pro_arr); 

            //END - DEVTASK-4236
    
            return response()->json(['message' => 'Successfull','code' => 200]);
        }
    }

    public function createStatus(Request $request) {
        $inventory_status = InventoryStatus::where('name',$request->status)->first();
        if(!$inventory_status) {
            $inventory_status = new InventoryStatus;
            $inventory_status->name = $request->status;
            $inventory_status->save();
            return response()->json(['message' => 'Successfull' , 'code' => 200]);

        }
        else {
            return response()->json(['message' => 'Already exist' , 'code' => 500]);
        }
    }

    public function changeStatus($id, Request $request) {
        $order_product = OrderProduct::find($id);
        if($request->status && $order_product) {
            $order_product->update(['inventory_status_id' => $request->status]);
            return response()->json(['message' => 'Successfull' ,'code' => 200]);
        }
        return response()->json(['message' => 'Status not changed' ,'code' => 500]);
    }
    
    public function insert_suppliers_product(Request $request){
       
        $product_data = Product::find($request->product_id);
        $suppliers = $request->supplier_id;

        $isexist = ProductSupplier::where('product_id',$product_data->id)->whereIn('supplier_id',$suppliers)->exists();


        if($isexist == true)
        {
            return response()->json(['message' => 'This Supplier Alreday Added For this Product.' ,'code' => 400]);
        }

        foreach($suppliers as $key => $val)
        {
            $add_product_supplier             = ProductSupplier::create([
                'product_id' => $product_data->id,
                'supplier_id' => $val,
                'sku' => $product_data->sku,
                'title' => $product_data->name,
                'description' => $product_data->short_description,
                'supplier_link' => $product_data->supplier_link,
                'price'         => $product_data->price,
                'stock'         => $product_data->stock,
                'price'         => $product_data->price,
                'price_special' => $product_data->price_eur_special,
                'price_discounted' => $product_data->price_eur_discounted,
                'size'          => $product_data->size,
                'color'         => $product_data->color,
                'composition'   => $product_data->composition
            ]);
        }

        return response()->json(['message' => 'Supplier Added successfully' ,'code' => 200]);
    }

    //START - Purpose : Create function for Purchase Product Order Data - DEVTASK-4236
    public function purchaseproductorders(Request $request)
    {
        try{
            $suppliers_all = '';
            // $purchar_product_order = PurchaseProductOrder::
            // join('order_products','purchase_product_orders.order_products_id','order_products.id')
            // ->join('products','products.id','order_products.product_id')
            // ->join('product_suppliers','product_suppliers.product_id','products.id')
            // ->join('suppliers','suppliers.id','purchase_product_orders.supplier_id')
            // ->leftjoin('brands','brands.id','products.brand')
            // ->select('order_products.*','products.*','product_suppliers.*','purchase_product_orders.*','purchase_product_orders.id as pur_pro_id','product_suppliers.price as mrp','brands.name as brand_name','purchase_product_orders.order_products_id as order_pro_order_id','suppliers.supplier as supplier_name');
           
            $purchar_product_order = PurchaseProductOrder::join('suppliers','purchase_product_orders.supplier_id','suppliers.id');
           
            if($request->order_id) {
                $purchar_product_order =  $purchar_product_order->where('purchase_product_orders.order_id',$request->order_id);
            }

            if($request->supplier_id) {
                $purchar_product_order =  $purchar_product_order->where('purchase_product_orders.supplier_id',$request->supplier_id);
                $suppliers_all = Supplier::where('id',$request->supplier_id)->first();
            }

            $purchar_product_order =  $purchar_product_order->select('purchase_product_orders.*','purchase_product_orders.status as purchase_status','suppliers.*','suppliers.id as supplier_id','purchase_product_orders.id as pur_pro_id','purchase_product_orders.created_at as created_at_date');
            $purchar_product_order =  $purchar_product_order->orderBy('purchase_product_orders.id','DESC')->paginate(Setting::get('pagination'));

            return view('purchase-product.partials.purchase-product-order',compact('purchar_product_order','request','suppliers_all'));
        }catch(\Exception $e){
            
        }
    }

    public function purchaseproductorders_update(Request $request)
    {
        try{
            $from = $request->from;
            $purchase_pro_id = $request->purchase_pro_id;

            $get_data = PurchaseProductOrder::where('id',$purchase_pro_id)->first();
            $params['purchase_product_order_id'] = $purchase_pro_id;
            $params['created_by'] = \Auth::id();

            if($from == 'invoice')
            {
                $message = $request->message;
                $update = [
                    'invoice' => $message,
                ];
                PurchaseProductOrder::where('id',$purchase_pro_id)->update($update);

                $params['header_name'] = 'Invoice';
                $params['replace_from'] = $get_data->invoice;
                $params['replace_to'] = $message;

                $log = PurchaseProductOrderLog::create($params);

                return response()->json(['messages' => 'Invoice Updated successfully' ,'code' => 200]);
            }
            else if($from == 'payment_details'){

                $payment_currency = $request->payment_currency;
                $payment_amount = $request->payment_amount;
                $payment_mode = $request->payment_mode;

                $update = [
                    'payment_currency' => $payment_currency,
                    'payment_amount' => $payment_amount,
                    'payment_mode' => $payment_mode,
                ];
                PurchaseProductOrder::where('id',$purchase_pro_id)->update($update);

                $params['header_name'] = 'Payment Details';
                $params['replace_from'] = 'Payment Currency : '.$get_data->payment_currency.'<br/> Payment Amount : '.$get_data->payment_amount.' <br/> Payment Mode : '.$get_data->payment_mode;
                $params['replace_to'] = 'Payment Currency : '.$payment_currency.' <br/> Payment Amount : '.$payment_amount.' <br/> Payment Mode : '.$payment_mode;

                $log = PurchaseProductOrderLog::create($params);

                return response()->json(['messages' => 'Payment Details Updated successfully' ,'code' => 200]);
            }
            else if($from == 'costs'){
                $shipping_cost = $request->shipping_cost;
                $duty_cost = $request->duty_cost;
                $update = [
                    'shipping_cost' => $shipping_cost,
                    'duty_cost' => $duty_cost,
                ];
                PurchaseProductOrder::where('id',$purchase_pro_id)->update($update);

                $params['header_name'] = 'Cost';
                $params['replace_from'] = 'Shipping Cost : '.$get_data->shipping_cost.' Duty Cost : '.$get_data->duty_cost;
                $params['replace_to'] = 'Shipping Cost : '.$shipping_cost.' Duty Cost : '.$duty_cost;

                $log = PurchaseProductOrderLog::create($params);

                $purchase_pro_order_data = PurchaseProductOrder::where('id',$purchase_pro_id)->first();

                $mrp_price = $purchase_pro_order_data->mrp_price;
                $discount_price = $purchase_pro_order_data->discount_price;
                $special_price = $purchase_pro_order_data->special_price;
                $final_special_price = ($special_price - $discount_price);

                $purchase_price = ($mrp_price - $discount_price  / 1.22);
                $landed_cost = round($purchase_price + $purchase_pro_order_data->shipping_cost + $purchase_pro_order_data->duty_cost,2);


                return response()->json(['messages' => 'Costs Updated successfully' ,'code' => 200, 'landed_cost' => $landed_cost]);
            }
            else if($from == 'status'){
                $status = $request->status;
                $update = [
                    'status' => $status,
                ];
                PurchaseProductOrder::where('id',$purchase_pro_id)->update($update);

                $params['header_name'] = 'Status';
                $params['replace_from'] = $get_data->status;
                $params['replace_to'] = $status;

                $log = PurchaseProductOrderLog::create($params);

                return response()->json(['messages' => 'Status Updated successfully' ,'code' => 200]);
            }
            else if($from == 'mrp'){
                $mrp = $request->mrp;
                $update = [
                    'mrp_price' => $mrp,
                ];
                PurchaseProductOrder::where('id',$purchase_pro_id)->update($update);

                $params['header_name'] = 'MRP';
                $params['replace_from'] = $get_data->mrp_price;
                $params['replace_to'] = $mrp;

                $log = PurchaseProductOrderLog::create($params);

                return response()->json(['messages' => 'MRP Updated successfully' ,'code' => 200]);
            }
            else if($from == 'discount_price'){
                $discount_price = $request->discount_price;
                $update = [
                    'discount_price' => $discount_price,
                ];
                PurchaseProductOrder::where('id',$purchase_pro_id)->update($update);

                $params['header_name'] = 'Discounted Price';
                $params['replace_from'] = $get_data->discount_price;
                $params['replace_to'] = $discount_price;

                $log = PurchaseProductOrderLog::create($params);

                return response()->json(['messages' => 'Discount Updated successfully' ,'code' => 200]);
            }
            else if($from == 'special_price'){
                $special_price = $request->special_price;
                $update = [
                    'special_price' => $special_price,
                ];
                PurchaseProductOrder::where('id',$purchase_pro_id)->update($update);

                $params['header_name'] = 'Special Price';
                $params['replace_from'] = $get_data->special_price;
                $params['replace_to'] = $special_price;

                $log = PurchaseProductOrderLog::create($params);

                return response()->json(['messages' => 'Special Price Updated successfully' ,'code' => 200]);
            }
            else if($from == 'product_order_mrp')
            {
                $product_order_mrp = $request->product_order_mrp;
                $product_order_mrp_old  = $request->product_order_mrp_old;
                $order_products_id  = $request->order_products_id;
                $product_order_mrp_total = $request->product_order_mrp_total;

                $get_data = PurchaseProductOrderLog::where('purchase_product_order_id',$purchase_pro_id)
                ->where('order_products_id',$order_products_id)
                ->where('header_name','Product Order MRP')
                ->orderBy('id','DESC')->first();

                if($get_data)
                    $old_amt = $get_data->replace_to;
                else
                    $old_amt = $product_order_mrp_old;

                $params['header_name'] = 'Product Order MRP';
                $params['order_products_id'] = $order_products_id;
                $params['replace_from'] = $old_amt;
                $params['replace_to'] = $product_order_mrp;

                $log = PurchaseProductOrderLog::create($params);

                if($product_order_mrp_total >= 1)
                {
                    $update = [
                        'mrp_price' => $product_order_mrp_total,
                    ];
                    PurchaseProductOrder::where('id',$purchase_pro_id)->update($update);
                }

                $purchase_pro_order_data = PurchaseProductOrder::where('id',$purchase_pro_id)->first();

                $mrp_price = $purchase_pro_order_data->mrp_price;
                $discount_price = $purchase_pro_order_data->discount_price;
                $special_price = $purchase_pro_order_data->special_price;
                $final_special_price = ($special_price - $discount_price);

                $purchase_price = ($mrp_price - $discount_price  / 1.22);
                $landed_cost = round($purchase_price + $purchase_pro_order_data->shipping_cost + $purchase_pro_order_data->duty_cost,2);


                return response()->json(['messages' => 'MRP Updated successfully' ,'code' => 200,'mrp_price' => $mrp_price, 'discount_price' => $discount_price, 'special_price' => $final_special_price , 'landed_cost' => $landed_cost]);
            }
            else if($from == 'product_order_discounted_price')
            {
                
                $product_order_mrp = $request->product_order_mrp;
                $product_order_mrp_old  = $request->product_order_mrp_old;
                $order_products_id  = $request->order_products_id;
                $product_order_mrp_total = $request->product_order_mrp_total;

                $get_data = PurchaseProductOrderLog::where('purchase_product_order_id',$purchase_pro_id)
                ->where('order_products_id',$order_products_id)
                ->where('header_name','Product Order Discounted Price')
                ->orderBy('id','DESC')->first();

                if($get_data)
                    $old_amt = $get_data->replace_to;
                else
                    $old_amt = $product_order_mrp_old;

                $params['header_name'] = 'Product Order Discounted Price';
                $params['order_products_id'] = $order_products_id;
                $params['replace_from'] = $old_amt;
                $params['replace_to'] = $product_order_mrp;

                $log = PurchaseProductOrderLog::create($params);

                if($product_order_mrp_total >= 1)
                {
                    $update = [
                        'discount_price' => $product_order_mrp_total,
                    ];
                    PurchaseProductOrder::where('id',$purchase_pro_id)->update($update);
                }

                $purchase_pro_order_data = PurchaseProductOrder::where('id',$purchase_pro_id)->first();

                $mrp_price = $purchase_pro_order_data->mrp_price;
                $discount_price = $purchase_pro_order_data->discount_price;
                $special_price = $purchase_pro_order_data->special_price;
                $final_special_price = ($special_price - $discount_price);

                $purchase_price = ($mrp_price - $discount_price  / 1.22);
                $landed_cost = round($purchase_price + $purchase_pro_order_data->shipping_cost + $purchase_pro_order_data->duty_cost,2);

                return response()->json(['messages' => 'Discounted Price Updated successfully' ,'code' => 200,'mrp_price' => $mrp_price, 'discount_price' => $discount_price, 'special_price' => $final_special_price , 'landed_cost' => $landed_cost]);
            }
            else if($from == 'product_order_special_price')
            {
                
                $product_order_mrp = $request->product_order_mrp;
                $product_order_mrp_old  = $request->product_order_mrp_old;
                $order_products_id  = $request->order_products_id;
                $product_order_mrp_total = $request->product_order_mrp_total;

                $get_data = PurchaseProductOrderLog::where('purchase_product_order_id',$purchase_pro_id)
                ->where('order_products_id',$order_products_id)
                ->where('header_name','Product Order Special Price')
                ->orderBy('id','DESC')->first();

                if($get_data)
                    $old_amt = $get_data->replace_to;
                else
                    $old_amt = $product_order_mrp_old;

                $params['header_name'] = 'Product Order Special Price';
                $params['order_products_id'] = $order_products_id;
                $params['replace_from'] = $old_amt;
                $params['replace_to'] = $product_order_mrp;

                $log = PurchaseProductOrderLog::create($params);

                if($product_order_mrp_total >= 1)
                {
                    $update = [
                        'special_price' => $product_order_mrp_total,
                    ];
                    PurchaseProductOrder::where('id',$purchase_pro_id)->update($update);
                }

                $purchase_pro_order_data = PurchaseProductOrder::where('id',$purchase_pro_id)->first();

                $mrp_price = $purchase_pro_order_data->mrp_price;
                $discount_price = $purchase_pro_order_data->discount_price;
                $special_price = $purchase_pro_order_data->special_price;
                $final_special_price = ($special_price - $discount_price);

                $purchase_price = ($mrp_price - $discount_price  / 1.22);
                $landed_cost = round($purchase_price + $purchase_pro_order_data->shipping_cost + $purchase_pro_order_data->duty_cost,2);

                return response()->json(['messages' => 'Special Price Updated successfully' ,'code' => 200,'mrp_price' => $mrp_price, 'discount_price' => $discount_price, 'special_price' => $final_special_price , 'landed_cost' => $landed_cost]);
            }

            
        }catch(\Exception $e){
            
        }
    }

    public function purchaseproductorders_logs(Request $request)
    {
        try{

            $log_data = PurchaseProductOrderLog::where('purchase_product_order_id',$request->purchase_pro_id)
            ->join('users','purchase_product_order_logs.created_by','users.id')
            ->where('header_name',$request->header_name);

            if(isset($request->purchase_order_products_id) && $request->purchase_order_products_id != '')
                $log_data = $log_data->where('order_products_id',$request->purchase_order_products_id);

            $log_data = $log_data->orderBy('purchase_product_order_logs.id','DESC')
            ->select('purchase_product_order_logs.*','users.*','purchase_product_order_logs.created_at as log_created_at')
            ->get();

            return response()->json(['log_data' => $log_data ,'code' => 200]);
            
        }catch(\Exception $e){
            
        }
    }

    public function purchaseproductorders_orderdata(Request $request)
    {
        try{
            $purchase_pro_id =  $request->purchase_pro_id;
            $order_products_order_id_arr = explode(",",$request->order_products_order_id);

            $order_data = OrderProduct::join('products','order_products.product_id','products.id')
            ->leftjoin('brands','brands.id','products.brand')
            ->join('product_suppliers','product_suppliers.product_id','products.id')
            ->whereIn('order_products.id',$order_products_order_id_arr)
            ->where('product_suppliers.supplier_id',$request->supplier_id)
            ->select('products.*','order_products.id as order_products_id','brands.name as brands_name','product_suppliers.price as mrp_price','product_suppliers.price_discounted as mrp_price_discounted','product_suppliers.price_special as mrp_price_special','product_suppliers.supplier_id as supplier_id')
            ->get();

            $purchase_pro_order_log = PurchaseProductOrderLog::where('purchase_product_order_id',$purchase_pro_id)->where('order_products_id','!=',null)->get();

            $purchase_pro_arr = array();

            if(!empty($purchase_pro_order_log))
            {
                foreach($purchase_pro_order_log as $key => $val)
                {
                    if($val->header_name == 'Product Order MRP')
                        $header = 'order_product_mrp';
                    else if($val->header_name == 'Product Order Discounted Price')
                        $header = 'order_product_discount';
                    else if($val->header_name == 'Product Order Special Price')
                        $header = 'order_product_special';

                    $purchase_pro_arr[$val->purchase_product_order_id][$val->order_products_id][$header] = $val->replace_to;
                }
            }

            return response()->json(['order_data' => $order_data ,'code' => 200,'purchase_pro_id' => $purchase_pro_id,'purchase_pro_arr' => $purchase_pro_arr]);
            
        }catch(\Exception $e){
            
        }
    }

    public function purchaseproductorders_saveuploads(Request $request)
    {
        // dd($request->all());
        try{
           
            $order_product_id = $request->order_product_id;
            $order_id = $request->order_id;
            $files = $request->file('file');
            $fileNameArray = array();
            foreach($files as $key=>$file){
                //echo $file->getClientOriginalName();
                $fileName = time().$key.'.'.$file->extension();
                $fileNameArray[] = $fileName;

                $params['order_product_id'] = $order_product_id;
                $params['order_id'] = $order_id;
                $params['file_name'] = $fileName;
                $params['user_id'] = \Auth::id();

                $log = PurchaseProductOrderImage::create($params);
                
                $file->move(public_path('purchase_product_orders'), $fileName);
            }
            return response()->json(["code" => 200, "msg" => "files uploaded successfully","data"=>$fileNameArray]);
            
        }catch(\Exception $e){

        }
    }
    
    public function getexcel(Request $request)
    { 
        $supplier_id = $request->supplier_id;
        $product_ids = $request->product_id;
        $order_ids = $request->order_id;

        $supplier = Supplier::find($supplier_id);            
        $path = Carbon::now()->format('Y-m-d-H-m-s') . "_order_exports.xlsx";
        $subject = 'Product order';
        $message = 'Please check below product order request';
        $product_ids = explode(",",$product_ids);
        $order_ids = explode(",",$order_ids);
        
        return Excel::download(new EnqueryExport($product_ids,$order_ids,$path),Carbon::now()->format('Y-m-d-H-m-s') . "_order_exports.xlsx");
       
        
    }

    public function getallproducts(Request $request)
    {
        $supplier_id = $request->supplier_id;
        // $product_ids = explode(",",$request->product_id);
        $product_ids = json_decode($request->product_id, true);
        $order_ids = json_decode($request->order_id, true);
       
        $template_data = SupplierOrderTemplate::where('supplier_id',$supplier_id)->first();

        if(isset($template_data) && $template_data->template)
            $template = $template_data->template;
        else
            $template = '{product_data}';
        // $products_data = Product::leftjoin('product_suppliers','product_suppliers.product_id','products.id')
        // ->select('product_suppliers.price as product_price','products.*')
        // ->whereIn('products.id',$product_ids)->groupBy("product_suppliers.sku")->get()->toArray();

        $products_data = Product::join('order_products','order_products.product_id','products.id')
        ->join('product_suppliers','product_suppliers.product_id','products.id')
        ->join('brands','brands.id','products.brand')
        ->select('product_suppliers.price as product_price','products.*','brands.name as brand_name')
        ->whereIn('products.id',$product_ids)->whereIn('order_products.id',$order_ids)->groupBy("order_products.sku")->get()->toArray();
        
        // $product_names = array_column($products_data, 'name');
        // $products_str = implode(", ",$product_names);
        $products_str = '';
        foreach($products_data as $key => $val)
        {
            $products_str .= "\n".' => Product Name : '.$val['name'].', Brand : '.$val['brand_name'].', SKU : '.$val['sku'].' , Price : '.$val['product_price'];
        }
        $message = str_replace("{product_data}",$products_str,$template);
        // $message = 'Please check Product Order ::  '.$products_str;

        // $message = str_replace("=>","=>",$message);

        return response()->json(["code" => 200, "data"=>$message]);
    }

    public function send_Products_Data(Request $request)
    {
        $type = $request->type;
        $supplier_id = $request->supplier_id;
        $product_id = $request->product_id;
        $order_id = $request->order_id;
        $content = $request->content;
        $send_options = $request->send_options;

        if($type == 'order' && $send_options != '') {
            
            $supplier = Supplier::find($supplier_id);            
            $path = "order_exports/" . Carbon::now()->format('Y-m-d-H-m-s') . "_order_exports.xlsx";
            $subject = 'Product order';
            $message_chat_data = ($content ? $content : 'Please check below product order request');

           

            $product_ids = explode(",",$product_id);
            $order_ids = explode(",",$order_id);

            if($send_options == 'email' || $send_options == 'both')
            {
                $message = ($content ? $content : 'Please check below product order request');

                $message = str_replace("=>","<br/>"." =>",$message);
                $message = str_replace("\n","<br/>",$message);
            
                Excel::store(new EnqueryExport($product_ids,$order_ids,$path), $path, 'files');
            
                $emailClass = (new PurchaseExport($path, $subject, $message))->build();

                $email             = Email::create([
                    'model_id'         => $supplier_id,
                    'model_type'       => Supplier::class,
                    'from'             => 'buying@amourint.com',
                    'to'               => $supplier->email,
                    'subject'          => $subject,
                    'message'          => $message,
                    'template'         => 'purchase-simple',
                    'additional_data'  => json_encode(['attachment' => [$path]]),
                    'status'           => 'pre-send',
                    'is_draft'         => 0,
                ]);

                \App\Jobs\SendEmail::dispatch($email);
            }

            if($send_options == 'whatsapp' || $send_options == 'both')
            {
                $products_data = Product::whereIn('id',$product_ids)->get()->toArray();
                $product_names = array_column($products_data, 'name');
                $products_str = implode(", ",$product_names);
                // $message = 'Please check Product Order : '.$products_str;
                $message = ($content ? $content : 'Please check below product order request');

                $message = str_replace("=>","\n"." =>",$message);

                $number = ($supplier->phone ? $supplier->phone : '971569119192' );

                $send_whatsapp = app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($number,$supplier->whatsapp_number, $message);
            }
            
            $message_chat = ' Order WhatsApp Message : '.$message_chat_data;
            $params = [
                'message' => $message_chat,
                'supplier_id' => $supplier_id,
                'additional_data' => json_encode(['attachment' => [$path]]),
                'user_id' => \Auth::id(),
            ];
            $chatMessage = ChatMessage::create($params);
           

            $getInquiryData = SupplierOrderInquiryData::where('type',$type)->get()->toArray();

            $pro_data_arr = array();
            foreach($getInquiryData as $key => $value){
                $pro_data_arr[$value['type']][$value['product_id']] = $value;
            }   


            $product_id = array_column($getInquiryData, 'product_id');

            $pro_arr = [];
            foreach ($product_ids as $key => $val)
            {
                if (!in_array($val, $product_id))
                {
                    $pro_arr[] = [
                        'supplier_id' => $supplier_id,
                        'product_id' => $val,
                        'type' => $type,
                        'count_number' => '1'
                    ];

                }
            }

            SupplierOrderInquiryData::insert($pro_arr);
            
            $order_products_data = OrderProduct::whereIn('id',$order_ids)->get();

            $order_products_data_arr = OrderProduct::whereIn('id',$order_ids)->get()->toArray();
            $all_product_id = array_column($order_products_data_arr, 'product_id');

            $order_data = OrderProduct::
            // leftjoin('products','order_products.product_id','products.id')
            // ->leftjoin('brands','brands.id','products.brand')
            join('product_suppliers','product_suppliers.product_id','order_products.product_id')
            ->whereIn('order_products.id',$order_ids)
            ->where('product_suppliers.supplier_id',$supplier_id)
            ->select('product_suppliers.price as mrp','product_suppliers.price_special as price_special','product_suppliers.price_discounted as price_discounted','order_products.order_id as order_id')
            ->get();
            

            
            $order_data_total_mrp = 0;
            $order_data_total_price_discount = 0;
            $order_data_total_price_special = 0;
            foreach($order_data as $key => $val)
            {
                $order_data_total_mrp += $val->mrp;
                $order_data_total_price_discount += $val->price_discounted;
                $order_data_total_price_special += $val->price_special;
            }

            $order_pro_arr = [];

            // foreach ($order_products_data as $key => $val)
            // {
                $rand_order_no = rand(999,9999).'0'.rand(99,999);
                
                $order_pro_arr[] = [
                    'product_id' => '',
                    'order_products_id' => $request->product_id,
                    'order_id'      => $rand_order_no,
                    'supplier_id' => $supplier_id,
                    'mrp_price' => $order_data_total_mrp,
                    'discount_price' => $order_data_total_price_discount,
                    'special_price' => $order_data_total_price_special,
                    'created_by' => \Auth::id(),
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'order_products_order_id' => $request->order_id,
                ];
            // }
            
           

            PurchaseProductOrder::insert($order_pro_arr); 

            //END - DEVTASK-4236
    
            return response()->json(['message' => 'Successfull','code' => 200]);
        }
    }

    public function edit_excel_file(Request $request)
    {
        $type = $request->type;
        $supplier_id = $request->supplier_id;
        $product_id = $request->product_id;
        $order_id = $request->order_id;

        $supplier = Supplier::find($supplier_id);            
        $path = "order_exports/" . Carbon::now()->format('Y-m-d-H-m-s') . "_order_exports.xlsx";
       
        $product_ids = explode(",",$product_id);
        $order_ids = explode(",",$order_id);

        Excel::store(new EnqueryExport($product_ids,$order_ids,$path), $path, 'files');

        $params = [
            'excel_path' => $path,
            'supplier_id' => $supplier_id,
            'created_by' => \Auth::id(),
        ];

        $add_rec = PurchaseProductOrderExcelFile::create($params);

        $add_rec_id = $add_rec->id;

        return response()->json(['message' => 'Successfull','code' => 200,'excel_id' => $add_rec_id]);
    }

    public function open_excel_file(Request $request,$excel_id,$version = null)
    {

        if($version)
        {
            $log = PurchaseProductOrderExcelFileVersion::where('excel_id',$excel_id)->where('file_version',$version)->first();
            $id          = $log->excel_id;
            $name = $log->file_name;
        }else{
            $log = PurchaseProductOrderExcelFile::find($excel_id);
            $id = $excel_id;
            $name        = $log->excel_path;
        }

       

        $isExists = File::exists(storage_path('app/files/') . $name);
        $info  = '';
        if( $isExists == true)
        {
            $info        = new \SplFileInfo(storage_path('app/files/') . $name);
        }
        

        $file_found = '';
        

        if($info == ''){
            $file_found = 'not_found';
           
        }

        $excel_array = Excel::toArray(new CustomerNumberImport, $info);


        $filter_arr = array();

        foreach ($excel_array as $key => $item)
        {
            foreach ($item as $kk => $vv)
            {
               
                foreach ($vv as $k => $v)
                {
                    if($kk != 0)
                    $filter_arr['sheet_'.$key.'_'.$kk][$k] = $v;
                    
                }
            }
        }


        $version_excel = PurchaseProductOrderExcelFileVersion::where('excel_id',$id)->orderBy('id', 'ASC')->get();

        $version_excel_data = array();
        if(!empty($version_excel))
        {
            foreach ($version_excel as $key => $item)
            {
                $version_excel_data[] = $item;
            }
        }

        return view('purchase-product.partials.open_excel_file',compact('excel_array','filter_arr','file_found','name','id','version_excel_data','version'));

    }

    public function update_excel_sheet(Request $request)
    {
        $form_data = json_decode($request->form_data, true);	
       
        $file_name = $request->file_name;

        $log_excel_imports_id = $request->log_excel_imports_id;
        
        $file_name_data = explode("/",$file_name);

        if(count($file_name_data) > 1)
            $location = $file_name_data[0].'/';
        else
            $location = '';

        
        if($form_data){
            $head  = 0;
            $excel_data = array();
            $checkbox_arr = array();
            foreach ($form_data as $key => $item)
            {
                if($item['name'] == "checkbox")
                {
                    $checkbox_arr[] = $item['value'];
                }else{
                    if($item['name'] == '0' || $item['name'] == 'head_0')
                    {
                        $head++;
                    }
                    $data = explode("_",$item['name']);
                    if($data[0] == "head")
                    {
                        $excel_data[$head][] =  $item['value'];
                    }else{
                        $excel_data[$head][] =  $item['value'];
                    }
                }
            }
        }

        foreach($checkbox_arr as $k1 => $v1)
        {
            unset($excel_data[$v1]);
        }

        $path = $location . Carbon::now()->format('Y-m-d-H-m-s') . "_order_exports.xlsx";

        $total_sheet = array();
        
        Excel::store(new FileExcelExport($excel_data,$total_sheet,$path), $path, 'files');


        $get_record = PurchaseProductOrderExcelFileVersion::where('excel_id',$log_excel_imports_id)->count();

        
        $data['excel_id'] = $log_excel_imports_id;

        if($get_record == 0){
            $first_v_data['file_name'] = $file_name;
            $first_v_data['file_version'] = 1;
            $first_v_data['excel_id'] = $log_excel_imports_id;
            $create_first_version = PurchaseProductOrderExcelFileVersion::create($first_v_data);

            // $path_data = explode(".",$path);

            $data['file_name'] = $path;
            $data['file_version'] = 2;
            $create_version = PurchaseProductOrderExcelFileVersion::create($data);
        }
        else{
            // $path_data = explode(".",$path);
            $data['file_name'] = $path;
            $data['file_version'] = $get_record + 1;

            $create_version = PurchaseProductOrderExcelFileVersion::create($data);
        }

        return response()->json(['message' => 'Excel File Updated Successfully','code' => 200], 200);
    }

    public function get_excel_data_supplier_wise(Request $request)
    {
        $supplier_id =  $request->supplier_id;

        $get_data = PurchaseProductOrderExcelFile::where('supplier_id',$supplier_id)->orderBy('id', 'DESC')->get();

        $get_version_data = PurchaseProductOrderExcelFileVersion::get();

        $get_version_data_arr = array();
        foreach($get_version_data as $key => $val){
            $get_version_data_arr[$val['excel_id']][] = $val['file_version'];
        }


        $get_final_arr = array();
        foreach($get_data as $key => $val){
            
            $get_final_arr[$key]['excel_name'] = $val['id'];
            
            if (array_key_exists($val['id'],$get_version_data_arr))
            {
                $get_final_arr[$key]['excel_version'] = $get_version_data_arr[$val['id']];
            }
        }

        // dd($get_final_arr);


        return response()->json(['code' => 200,'get_final_arr' => $get_final_arr ], 200);
    }
    
    public function send_excel_file(Request $request)
    {
        $supplier_id =  $request->supplier_id;
        $excel_id =  $request->excel_id;
        $version =  $request->version;
        

        $supplier = Supplier::find($supplier_id);
        
        if($version != 'no'){
            $data = PurchaseProductOrderExcelFileVersion::where('excel_id',$excel_id)->where('file_version',$version)->first();
            $path = $data->file_name;
        }else{
            $data = PurchaseProductOrderExcelFile::where('id',$excel_id)->first();
            $path = $data->excel_path;
        }
        
        $subject = 'Product order';
        $message = 'Please check below product order request';
    
        $emailClass = (new PurchaseExport($path, $subject, $message))->build();

        $email             = Email::create([
            'model_id'         => $supplier_id,
            'model_type'       => Supplier::class,
            'from'             => 'buying@amourint.com',
            'to'               => $supplier->email,
            'subject'          => $subject,
            'message'          => $message,
            'template'         => 'purchase-simple',
            'additional_data'  => json_encode(['attachment' => [$path]]),
            'status'           => 'pre-send',
            'is_draft'         => 0,
        ]);

        \App\Jobs\SendEmail::dispatch($email);
        
        return response()->json(['code' => 200,'message' => 'Email Send Successfully' ], 200);
    }

    public function download_excel_file(Request $request)
    {
        $file = $request->filename;
        return response()->download(storage_path('/app/files/'. $file));
    }

    public function get_template(Request $request)
    {
        $template_data = SupplierOrderTemplate::where('supplier_id',$request->supplier_id)->first();
        
        if(isset($template_data) && $template_data->template)
            $template_content = $template_data->template;
        else
            $template_content = '{product_data}';

        return response()->json(['message' => 'Successfull','code' => 200,'template_data' => $template_content]);    
    }

    public function set_template(Request $request)
    {
        // $params = [
        //     'supplier_id' => $request->supplier_id,
        //     'template' => $request->template_data,
        //     'user_id' => \Auth::id(),
        // ];

        // $set_template = SupplierOrderTemplate::create($params);

        $storeWebsiteProduct = \App\SupplierOrderTemplate::updateOrCreate([
            "supplier_id" => $request->supplier_id,
        ], [
            'supplier_id' => $request->supplier_id,
            'template' => $request->template_data,
            'user_id' => \Auth::id(),
        ]);

        return response()->json(['message' => 'Template Updated Successfully','code' => 200]);
    }
    //END - DEVTASK-4236
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd("aa");
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
    
    //START -Purpose : Get Data not maping suppliers data - DEVTASK-19941
    public function not_mapping_product_supplier_list(Request $request)
    {
        $product_data = Product::get();

        $product_suppliers_data = ProductSupplier::get();

        $supplier_data = Supplier::get();
        $supplier_arr  = array();
        foreach ($supplier_data as $key => $value) {
            $supplier_arr[$value->id] = $value->supplier;
        }

        $product_suppliers_arr = array();
        foreach ($product_suppliers_data as $key => $value) {
            $product_suppliers_arr[$value->product_id][$value->supplier_id] =  ($supplier_arr[$value->supplier_id] ?? $value->supplier_id );
        }

        $product_not_available_product_supplier_table = array();
        $supplier_exist_product_supplier_table = array();
        $supplier_not_exist_product_supplier_table = array();

        foreach($product_data as $key => $value) {

            if($value->supplier_id != '' && $value->supplier_id != null)
            {
                $supplier_id = $value->supplier_id;
                $product_id = $value->id;
                if (array_key_exists($product_id,$product_suppliers_arr))
                {
                    if (array_key_exists($supplier_id,$product_suppliers_arr[$product_id]))
                    {
                        $supplier_exist_product_supplier_table[$key]['product_id'] = $product_id;
                        $supplier_exist_product_supplier_table[$key]['product_name'] = ($value->name ?? '');
                        $supplier_exist_product_supplier_table[$key]['supplier_id'] = $supplier_id;
                        $supplier_exist_product_supplier_table[$key]['supplier_name'] = ($product_suppliers_arr[$product_id][$supplier_id] ?? '-' );

                    }else{
                        $supplier_not_exist_product_supplier_table[$key]['product_id'] = $product_id;
                        $supplier_not_exist_product_supplier_table[$key]['product_name'] = ($value->name ?? '');
                        $supplier_not_exist_product_supplier_table[$key]['supplier_id'] = $supplier_id;
                    }
                }
                else
                {
                    $product_not_available_product_supplier_table[$key]['product_id'] = $product_id;
                    $product_not_available_product_supplier_table[$key]['product_name'] = ($value->name ?? '');
                }
            }
        }

        $chatFileData = '';
        $chatFileData .= html_entity_decode("Product Supplier Data", ENT_QUOTES, 'UTF-8');
        $chatFileData .= "\n" . "\n";

        foreach ($supplier_not_exist_product_supplier_table as $k => $v) {
            $chatFileData .= html_entity_decode("Product Id : " . $v['product_id'], ENT_QUOTES, 'UTF-8');
            $chatFileData .= "\n";
            $chatFileData .= html_entity_decode("Prodcuct Name : " . $v['product_name'], ENT_QUOTES, 'UTF-8');
            $chatFileData .= "\n";
            $chatFileData .= html_entity_decode("Supplier Id : " . $v['supplier_id'], ENT_QUOTES, 'UTF-8');
            $chatFileData .= "\n" . "\n";
        }
        
        $date = date('Y_m_d_H_i_s');
        $storagelocation = storage_path() . '/logs/not_mapping_product_supplier';
        if (!is_dir($storagelocation)) {
            mkdir($storagelocation, 0777, true);
        }
        $filename = "not_mapping_supplier_".$date.".txt";
        $file = $storagelocation . '/' . $filename;
        $txt = fopen($file, "w") or die("Unable to open file!");
      
        fwrite($txt, $chatFileData);
        fclose($txt);

        return response()->json([
            'downloadUrl' => $file
        ]);
    }
    //END - DEVTASK-19941
}

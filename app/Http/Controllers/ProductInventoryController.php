<?php

namespace App\Http\Controllers;

use App\Product;
use App\Setting;
use App\Stage;
use App\Brand;
use App\Category;
use App\Helpers;
use App\Mediables;
use App\ProductStatusHistory;
use App\ReadOnly\LocationList;
use Dompdf\Css\Style;
use Dompdf\Css\Stylesheet;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InventoryImport;
use Carbon\Carbon;
use App\ColorReference;
use Illuminate\Support\Facades\Validator;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use \App\Jobs\UpdateFromSizeManager;
use DB;
use Illuminate\Support\Facades\Input;
use App\Imports\DiscountFileImport;
use App\ProductSupplier;
use App\Supplier;
use App\SupplierBrandDiscount;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ProductInventoryController extends Controller
{
	public function __construct() {

//		$this->middleware('permission:inventory-list',['only' => ['index']]);
//		$this->middleware('permission:inventory-edit',['only' => ['edit','stock']]);
	}


	public function index(Stage $stage){

		$products = Product::latest()
											->where('stock', '>=', 1)
//		                   ->where('stage','>=',$stage->get('Approver') )
		                   ->whereNull('dnf')
											 ->select(['id','name', 'sku', 'size', 'price_inr_special', 'brand', 'supplier', 'isApproved', 'stage', 'status', 'is_scraped', 'created_at','category','color']);
											 //->limit(6);

        

        $products_count = $products->count();
		$products = $products->paginate(Setting::get('pagination'));

		$roletype = 'Inventory';

		$category_selection = Category::attr(['name' => 'category[]','class' => 'form-control'])
		                                        ->selected(1)
		                                        ->renderAsDropdown();

		$categoryAll = Category::where('parent_id',0)->get();
        foreach ($categoryAll as $category) {
            $categoryArray[] = array('id' => $category->id , 'value' => $category->title); 
            $childs = Category::where('parent_id',$category->id)->get();
            foreach ($childs as $child) {
                $categoryArray[] = array('id' => $child->id , 'value' => $category->title.' '.$child->title);
                $grandChilds = Category::where('parent_id',$child->id)->get();
                if($grandChilds != null){
                    foreach ($grandChilds as $grandChild) {
                        $categoryArray[] = array('id' => $grandChild->id , 'value' => $category->title.' '.$child->title .' '.$grandChild->title);
                    }
                } 
            }
        }



        $sampleColors = ColorReference::select('erp_color')->groupBy('erp_color')->get(); 

        $categoryArray=array();
        return view('partials.grid',compact('products', 'products_count', 'roletype', 'category_selection','categoryArray','sampleColors'))
			->with('i', (request()->input('page', 1) - 1) * 10);

	}

	public function list(Request $request, Stage $stage)
	{
		$category_tree = [];

		foreach (Category::all() as $category) {
			if ($category->parent_id != 0) {
				$parent = $category->parent;
				if ($parent->parent_id != 0) {
					if(isset($category_tree[$parent->parent_id][$parent->id])) {
						$category_tree[$parent->parent_id][$parent->id][$category->id];
					}
				} else {
					$category_tree[$parent->id][$category->id] = 0;
				}
			}
		}

		// dd($category_tree);

		$brands_array = Brand::getAll();
		$products_brands = Product::latest()
		                   ->where('stage','>=',$stage->get('Approver') )
											 ->whereNull('dnf')
											 ->where('stock', '>=', 1)->get()
											 ->groupBy([function ($query) use ($brands_array) {
												 if (isset($brands_array[$query->brand])) {
													 return $brands_array[$query->brand];
												 }

												 return 'Unknown Brand';
											 }, 'supplier', 'category']);

		// dd($products_brands);

		$inventory_data = [];

		foreach ($products_brands as $brand_name => $suppliers) {
			foreach ($suppliers as $supplier_name => $categories) {
				$tree = [];
				$inventory_data[$brand_name][$supplier_name] = $category_tree;

				foreach ($categories as $category_id => $products) {
					$category = Category::find($category_id);
					if ($category !== NULL && $category->parent_id != 0) {
						$parent = $category->parent;
						if (isset($parent->parent_id) && $parent->parent_id != 0) {
							$inventory_data[$brand_name][$supplier_name][$parent->parent_id][$parent->id] += count($products);
						} else {
							$inventory_data[$brand_name][$supplier_name][$parent->id][$category->id] += count($products);
						}
					}
				}
			}
		}

		// dd($inventory_data);

		$categories_array = [];
		$categories = Category::all();

		foreach ($categories as $category) {
			$categories_array[$category->id] = $category->title;
		}

 		return view('products.list',compact('inventory_data', 'categories_array'))
 			->with('i', (request()->input('page', 1) - 1) * 10);
	}

	public function edit(Product $productlister){

		return redirect( route('products.show',$productlister->id) );
	}

	public function stock(Product $product,Request $request,Stage $stage){


		$this->validate($request,[
			'stock' => 'required|numeric|min:0'
		]);

		$result = $this->magentoSoapUpdateStock($product,$request->input( 'stock' ));
		$product->stock = $request->input( 'stock' );
		$product->stage = $stage->get( 'Inventory' );
		$product->save();

		if( $result ) {

			//		NotificaitonContoller::store('has Final Approved',['Admin'],$product->id);
			ActivityConroller::create( $product->id, 'inventory', 'create' );

			return back()->with( 'success', 'Product inventory has been updated' );
		}

		return back()->with('error','Error Occured while uploading stock');
	}

	public function instock(Request $request)
	{
		$data     = [];
		$term     = $request->input( 'term' );
		$data['term']     = $term;

		$productQuery = Product::latest()->with(['brands','product_category']);

        if (isset($request->brand) && $request->brand[0] != null) {
            $productQuery = $productQuery->whereIn('brand', $request->brand);
            $data['brand'] = $request->brand[0];
        }
        if (isset($request->color) && is_array($request->color) && $request->color[0] != null) {

                $productQuery = $productQuery->whereIn('color', $request->color);
            $data['color'] = $request->color;
        }

		if (!empty($request->category) && $request->category[0] != 1) {

			$category   = Category::with('childs.childLevelSencond')->find($request->category[0]);
			$category_children = [];
			if($category->childs->count()){
				$childs  = $category->childs;
				foreach ($childs as $child) {
					// $category_children[] =  $child->id;
					if ($child->childLevelSencond->count()) {
					$grandChilds     = $child->childLevelSencond;
						foreach ($grandChilds as $grandChild) {
							$category_children[] =  $grandChild->id;
						}
					}else{
						$category_children[] =  $child->id;
					}
				}
			}else{
				$category_children[] =  $category->id;

			}
				$productQuery->whereIn('category', $category_children);
			$data['category'] = $request->category[0];
		}

		if (isset($request->location) && $request->location[0] != null) {
		
				$productQuery->whereIn('location', $request->location);

			$data['location'] = $request->location;
		}

		if ( isset($request->no_locations) && $request->no_locations) {

				$productQuery->whereNull('location');

		
			$data['no_locations'] = true;
		}

				$productQuery->when(!empty($term),function($e)  use ($term){
							$e->where(function($q) use ($term){
								
								$q->where('sku','LIKE',"%$term%")
								   ->orWhereHas('brands',function	($a) use($term){
									$a->where( 'name', 'LIKE', "%$term%");
								})->orwhereHas('product_category',function	($q) use($term){
									$q->where( 'title', 'LIKE', "%$term%");
								})
								->orWhere(function($q) use ($term){
									 $arr_id = Product::STOCK_STATUS;	
									$key = array_search(ucwords($term), $arr_id);
									 $q->where('stock_status',$key);
								});
							})
							;
				});
	
		$selected_brand = null;
		if($request->brand){
		$selected_brand = Brand::select('id','name')->whereIn('id',$request->brand)->get();
		}
		$data['selected_brand']= $selected_brand;
	
		$selected_categories = $request->category ? $request->category : 1;

		$data['category_selection'] = Category::attr(['name' => 'category[]','class' => 'form-control'])
		                                        ->selected($selected_categories)
		                                        ->renderAsDropdown();

//	
		$stockStatus = $request->get('stock_status', "");					
		if (!empty($stockStatus)) {
			$productQuery->where('stock_status',$stockStatus);
		}

		if ($request->get('shoe_size', false)) {
            $productQuery->where('products.size', 'like', "%".$request->get('shoe_size')."%");
        }

        $productQuery->where(function($query){
        	$query->where("purchase_status","!=","Delivered")->orWhereNull("purchase_status");
        });
      
        if ($request->get('in_pdf') === 'on') {
            $data[ 'products' ] = $productQuery->whereRaw( "(products.id IN (SELECT product_id FROM product_suppliers WHERE supplier_id = 11) OR (location IS NOT NULL AND location != ''))" )->get();
        } else {

			// $sub_q = ProductSupplier::select('product_id')->where('supplier_id',11)->get()->pluck('product_id')->toArray();
	            $data[ 'products' ] = $productQuery->whereRaw( "(products.id IN (SELECT product_id FROM product_suppliers WHERE supplier_id = 11) OR (location IS NOT NULL AND location != ''))" )->paginate( Setting::get( 'pagination' ) );

					// $data[ 'products' ] = $productQuery->where(function($j) use($sub_q){
					// 	$j->whereIn('products.id',$sub_q)->orWhere(function($q){
					// 		$q->whereNotNull('location')->where('location','<>','');
					// 	});
					// })->paginate( Setting::get( 'pagination' ) );
					
        }

        $data['date'] = $request->date ? $request->date : '';
		$data['type'] = $request->type ? $request->type : '';
		$data['customer_id'] = $request->customer_id ? $request->customer_id : '';
		$data['locations'] = (new \App\ProductLocation())->pluck('name')->toArray() + ["In-Transit" => "In-Transit"];

		$data['new_category_selection'] = Category::attr(['name' => 'category','class' => 'form-control', 'id' => 'product-category'])
		                                        ->renderAsDropdown();

		$data['category_tree'] = [];
		$data['categories_array'] = [];

		foreach (Category::with('parent')->get() as $category) {
			if ($category->parent_id != 0) {
				$parent = $category->parent;
				if($parent) {
					if ($parent->parent_id != 0) {
						@$data['category_tree'][$parent->parent_id][$parent->id][$category->id];
					} else {
						$data['category_tree'][$parent->id][$category->id] = $category->id;
					}
				}
			}

			$data['categories_array'][$category->id] = $category->parent_id;
		}


        if ($request->get('in_pdf') === 'on') {
		    set_time_limit(0);
            $html = view( 'instock.instock_pdf', $data );

            $pdf = new Dompdf();
            $pdf->loadHtml($html);
            $pdf->render();
            $pdf->stream('instock.pdf');
            return;
        }
        return view( 'instock.index', $data );
	}

	public function inDelivered(Request $request)
	{
		$data     = [];
		$term     = $request->input( 'term' );
		$data['term']     = $term;

		$productQuery = ( new Product() )->newQuery()->latest();
		if ($request->brand[0] != null) {
			$productQuery = $productQuery->whereIn('brand', $request->brand);
			$data['brand'] = $request->brand[0];
		}

		if ($request->color[0] != null) {
			$productQuery = $productQuery->whereIn('color', $request->color);
			$data['color'] = $request->color[0];
		}

		if (isset($request->category) && $request->category[0] != 1) {
			$is_parent = Category::isParent($request->category[0]);
			$category_children = [];

			if ($is_parent) {
				$childs = Category::find($request->category[0])->childs()->get();

				foreach ($childs as $child) {
					$is_parent = Category::isParent($child->id);

					if ($is_parent) {
						$children = Category::find($child->id)->childs()->get();

						foreach ($children as $chili) {
							array_push($category_children, $chili->id);
						}
					} else {
						array_push($category_children, $child->id);
					}
				}
			} else {
				array_push($category_children, $request->category[0]);
			}

			$productQuery = $productQuery->whereIn('category', $category_children);

			$data['category'] = $request->category[0];
		}

		if (isset($request->price) && $request->price != null) {
			$exploded = explode(',', $request->price);
			$min = $exploded[0];
			$max = $exploded[1];

			if ($min != '0' || $max != '10000000') {
				$productQuery = $productQuery->whereBetween('price_inr_special', [$min, $max]);
			}

			$data['price'][0] = $min;
			$data['price'][1] = $max;
		}


		if (trim($term) != '') {
			$productQuery = $productQuery->where(function ($query) use ($term){
 	    		$query->orWhere( 'sku', 'LIKE', "%$term%" )
					  ->orWhere( 'id', 'LIKE', "%$term%" );
			});


			if ( $term == - 1 ) {
				$productQuery = $productQuery->where(function ($query){
				 															return $query->orWhere( 'isApproved', - 1 );
									 });
			}

			if ( Brand::where('name', 'LIKE' ,"%$term%")->first() ) {
				$brand_id = Brand::where('name', 'LIKE' ,"%$term%")->first()->id;
				$productQuery = $productQuery->where(function ($query) use ($brand_id){
																			return $query->orWhere( 'brand', 'LIKE', "%$brand_id%" );});
			}

			if ( $category = Category::where('title', 'LIKE' ,"%$term%")->first() ) {
				$category_id = $category = Category::where('title', 'LIKE' ,"%$term%")->first()->id;
				$productQuery = $productQuery->where(function ($query) use ($term){
								return $query->orWhere( 'category', CategoryController::getCategoryIdByName( $term ));} );
			}

		}

		$selected_categories = $request->category ? $request->category : 1;

		$data['category_selection'] = Category::attr(['name' => 'category[]','class' => 'form-control select-multiple2'])
		                                        ->selected($selected_categories)
		                                        ->renderAsDropdown();


//		$data['products'] = $productQuery->paginate( Setting::get( 'pagination' ) );

		if ($request->get('shoe_size', false)) {
            $productQuery = $productQuery->where('products.size', 'like', "%".$request->get('shoe_size')."%");
        }

        $data[ 'products' ] = $productQuery->where('products.purchase_status', '=', 'Delivered')->paginate( Setting::get( 'pagination' ) );

		return view( 'indelivered.index', $data );
	}

	public function magentoSoapUpdateStock($product,$stockQty){

		$options = array(
			'trace' => true,
			'connection_timeout' => 120,
			'wsdl_cache' => WSDL_CACHE_NONE,
		);
		$proxy = new \SoapClient(config('magentoapi.url'), $options);
		$sessionId = $proxy->login(config('
		api.user'), config('magentoapi.password'));

		$sku = $product->sku . $product->color;
		$result = false;

//		$result = $proxy->catalogProductUpdate($sessionId, $sku , array('visibility' => 4));

		if(!empty($product->size)){

			$sizes_array = explode( ',', $product->size );

			foreach ($sizes_array as $size) {
				$error_message = '';

				try {
					$result = $proxy->catalogInventoryStockItemUpdate( $sessionId, $sku . '-' . $size, array(
						'qty'         => $stockQty,
						'is_in_stock' => $stockQty ? 1 : 0
					) );
				} catch (\Exception $e) {
					$error_message = $e->getMessage();
				}

				if ($error_message == 'Product not exists.') {
                      $product->isUploaded = 0;
                      $product->isFinal = 0;
					$product->save();
				}
			}

			$error_message = '';
			try {
				$result = $proxy->catalogInventoryStockItemUpdate( $sessionId, $sku, array(
	//				'qty'         => 0,
					'is_in_stock' => $stockQty ? 1 : 0
				) );
			} catch (\Exception $e) {
				$error_message = $e->getMessage();
			}

			if ($error_message == 'Product not exists.') {
				$product->isUploaded = 0;
				$product->isFinal = 0;
				$product->save();
			}
		}
		else {
			$error_message = '';

			try {
				$result = $proxy->catalogInventoryStockItemUpdate( $sessionId, $sku, array(
					'qty'         => $stockQty,
					'is_in_stock' => $stockQty ? 1 : 0
				) );
			} catch (\Exception $e) {
				$error_message = $e->getMessage();
			}

			if ($error_message == 'Product not exists.') {
				$product->isUploaded = 0;
				$product->isFinal = 0;
				$product->save();
			}
		}

		return $result;
	}

	public function import(Request $request)
	{
		$this->validate($request, [
			'file'	=> 'required'
		]);

		$array = (new InventoryImport)->toArray($request->file('file'));

		$new_array = [];
		$brands_array = Helpers::getUserArray(Brand::all());

		foreach ($array[0] as $key => $item) {
			$new_array[$item['modellovariante']][] = $item;
		}

		foreach ($new_array as $sku => $items) {
			$formatted_sku = str_replace(' ', '', $sku);

			if ($product = Product::where('sku', $formatted_sku)->first()) {
				if (in_array($items[0]['brand'], $brands_array)) {
					if (count($items) > 1) {
						$sizes = '';
						$product->stock = 1;
						$product->import_date = Carbon::now();
						$product->status = 3; // Import Update status

						foreach ($items as $key => $item) {
							$size = str_replace('½', '.5', $item['taglia']);

							if ($key == 0) {
								$sizes .= $size;
							} else {
								$sizes .= "," . $size;
							}
						}

						if (!preg_match('/UNI/', $sizes)) {
							$product->size = $sizes;
						}

						$product->save();
					} else {
						$product->stock = 1;
						$product->import_date = Carbon::now();
						$product->status = 3; // Import Update status

						foreach ($items as $key => $item) {
							$size = str_replace('½', '.5', $item['taglia']);
						}

						if (!preg_match('/UNI/', $size)) {
							$product->size = $size;
						}

						$product->save();
					}
				}
			} else {
				if (in_array($items[0]['brand'], $brands_array)) {
					if (count($items) > 1) {
						$sizes = '';
						$product = new Product;
						$product->sku = $formatted_sku;
						$product->brand = array_search($items[0]['brand'], $brands_array);
						$product->stage = 3;
						$product->stock = 1;
						$product->import_date = Carbon::now();
						$product->status = 2; // Import Create status

						foreach ($items as $key => $item) {
							$size = str_replace('½', '.5', $item['taglia']);

							if ($key == 0) {
								$sizes .= $size;
							} else {
								$sizes .= "," . $size;
							}
						}

						if (!preg_match('/UNI/', $sizes)) {
							$product->size = $sizes;
						}

						$product->save();
					} else {
						$product = new Product;
						$product->sku = $formatted_sku;
						$product->brand = array_search($items[0]['brand'], $brands_array);
						$product->stage = 3;
						$product->stock = 1;
						$product->import_date = Carbon::now();
						$product->status = 2; // Import Create status

						foreach ($items as $key => $item) {
							$size = str_replace('½', '.5', $item['taglia']);
						}

						if (!preg_match('/UNI/', $size)) {
							$product->size = $sizes;
						}

						$product->save();
					}
				}
			}
		}

		return back()->with('success', 'You have successfully imported Inventory');
	}

	public function instructionCreate()
	{

		$productId = request()->get("product_id",0);
		$users = \App\User::all()->pluck("name","id");
		$product = \App\Product::where("id",$productId)->first();
		$locations = \App\ProductLocation::all()->pluck("name","name");
		$couriers = \App\Courier::all()->pluck("name","name");
		$order = [];
		if($product) {
		   $order = \App\OrderProduct::where("product_id",$product->id)
		   ->join("orders as o","o.id","order_products.order_id")
		   ->select(["o.id",\DB::raw("concat(o.id,' => ',o.client_name) as client_name")])->pluck("client_name",'id');
		}

		$reply_categories = \App\ReplyCategory::whereHas('product_dispatch')->get();

		return view("instock.instruction_create",compact(['productId','users','order','locations','couriers', 'reply_categories']));

	}

	public function instruction()
	{
		$params =  request()->all();

		// validate incoming request

        $validator = Validator::make($params, [
           'product_id' => 'required',
           'location_name' => 'required',
           'instruction_type' => 'required',
           'instruction_message' => 'required',
           'courier_name' => 'required',
           'courier_details' => 'required',
           'date_time' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(["code" => 0, "errors" => $validator->messages()]);
        }

        // start to store first location as per the request
		$product = \App\Product::where("id",$params["product_id"])->first();
		$instruction = new \App\Instruction();

		if($params['instruction_type'] == "dispatch") {
			$orderId = request()->get("order_id",0);
			if($orderId > 0) {
				$order = \App\Order::where("id",$params["order_id"])->first();
				if($order) {

				  	$instruction->customer_id = $order->customer_id;
				  	$order->order_status = "Delivered";
				  	$order->order_status_id = \App\Helpers\OrderHelper::$delivered;
				  	$order->save();

				  	if($order->customer) {
				  		$customer = $order->customer;
				  		//$product->location =  null;
					    //$product->save();
				  	}
				}
			}else{
				$instruction->customer_id = request()->get("customer_id",0);
			}

			$customer = ($instruction->customer) ? $instruction->customer->name : "";

			$assign_to = request()->get("assign_to",0);

			if($assign_to > 0) {
				$user = \App\User::where('id',$assign_to)->first();
			}
			// if customer object found then send message
			if(!empty($user)) {

				$extraString = "";

				// check if any date time set
				if(!empty($params["date_time"])) {
					$extraString = " on ".$params["date_time"];
				}

				// set for pending amount
				if(!empty($params["pending_amount"])) {
					$extraString .= " and ".$params["pending_amount"]." to be collected";
				}
				// send message
				$messageData = implode("\n",[
			  		"{$product->name} to be delivered to {$customer} {$extraString}",
			  		$params["courier_name"],
			  		$params["courier_details"]
			  	]);

			    $params['approved'] = 1;
			    $params['message']  = $messageData;
			    $params['status']   = 2;
			    $params['user_id'] = $user->id;

			    app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone,$user->whatsapp_number,$messageData);
			    $chat_message = \App\ChatMessage::create($params);
			    if ($product->hasMedia(config('constants.media_tags'))) {
	                foreach ($product->getMedia(config('constants.media_tags')) as $image) {
	                	app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone,$user->whatsapp_number,null, $image->getUrl());
	                    $chat_message->attachMedia($image, config('constants.media_tags'));
	                }
	            }
			}

		}elseif ($params['instruction_type'] == "location") {
			if($product) {
				$product->location = "In-Transit";//$params["location_name"];
				$product->save();

				$params["location_name"] = "In-Transit - ".$params["location_name"];

				$user = \App\User::where("id",$params["assign_to"])->first();
				if($user) {
					// send location message
					$pendingAmount = (!empty($params["pending_amount"])) ? " and Pending amount : ".$params["pending_amount"] : "";
					$messageData = implode("\n",[
				  		"Pls. Despatch {$product->name} to ".$params["location_name"].$pendingAmount,
				  		$params['instruction_message'],
				  		$params["courier_name"],
				  		$params["courier_details"]
				  	]);

				    $params['approved'] = 1;
				    $params['message']  = $messageData;
				    $params['status']   = 2;
				    $params['user_id'] = $user->id;

				    app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone,$user->whatsapp_number,$messageData);
				    $chat_message = \App\ChatMessage::create($params);
				    if ($product->hasMedia(config('constants.media_tags'))) {
		                foreach ($product->getMedia(config('constants.media_tags')) as $image) {
		                	app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone,$user->whatsapp_number,null, $image->getUrl());
		                    $chat_message->attachMedia($image, config('constants.media_tags'));
		                }
		            }
				}
			}
		}

		$instruction->category_id = 7;
		$instruction->instruction = $params["instruction_message"];
		$instruction->assigned_from = \Auth::user()->id;
		$instruction->assigned_to = $params["assign_to"];
		$instruction->product_id = $params["product_id"];
		// $instruction->order_id = isset($params["order_id"]) ? $params["order_id"] : 0;
		$instruction->order_id = isset($params["order_id"]) ? $params["order_id"] : null;
		$instruction->save();


		$productHistory = new \App\ProductLocationHistory();
		$productHistory->fill($params);
		$productHistory->created_by = \Auth::user()->id;
		$productHistory->instruction_message = $params["instruction_message"];
		$productHistory->save();


		return response()->json(["code" => 1, "message" => "Done"]);


	}

	public function locationHistory()
	{
		$productId = request()->get("product_id",0);
		$locations = (new \App\ProductLocation())->pluck('name')->toArray();
		$product   = \App\Product::where("id" , $productId)->First();
		$history = \App\ProductLocationHistory::where("product_id",$productId)
		->orderBy("date_time","desc")
		->get();
		return view("instock.history_list",compact(['history','locations','product']));
	}

	public function dispatchCreate()
	{

		$productId = request()->get("product_id",0);
		//$users = \App\User::all()->pluck("name","id");
		//$product = \App\Product::where("id",$productId)->first();

		return view("instock.dispatch_create",compact(['productId','users','order']));

	}

	public function dispatchStore(Request $request)
	{
		$validator = Validator::make($request->all(), [
           'product_id' => 'required',
           'modeof_shipment' => 'required',
           'delivery_person' => 'required',
           'awb' => 'required',
           'eta' => 'required',
           //'date_time' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(["code" => 0, "errors" => $validator->messages()]);
        }

        $productDispatch = new \App\ProductDispatch;
        $productDispatch->fill($request->all());
        $productDispatch->save();

        $uploaded_images = [];

        if ($request->hasFile('file')) {
            try{
                foreach ($request->file('file') as $image) {
                    $media = MediaUploader::fromSource($image)->toDirectory('dispatch-images')->upload();
                    array_push($uploaded_images, $media);
                    $productDispatch->attachMedia($media,config('constants.media_tags'));
                }
            }catch (\Exception $exception){
               // return response($exception->getMessage(), $exception->getCode());
            }
        }

        if ($request->get('product_id') > 0 ) {
        	$product = \App\Product::where("id",$request->get('product_id'))->first();
	  		$product->purchase_status =  'Delivered';
	  		$product->location =  null;
		    $product->save();
        	$instruction = \App\Instruction::where('product_id', $request->get('product_id'))->where('customer_id', '>', '0')->orderBy('id', 'desc')->first();
        	if ($instruction) {

				$customer = \App\Customer::where('id',$instruction->customer_id)->first();

				// if customer object found then send message
				if(!empty($customer)) {
					$params = [];
					$messageData = implode("\n",[
				  		"We have Despatched your {$product->name} by {$productDispatch->delivery_person}",
				  		"AWB : {$request->awb}",
				  		"Mode Of Shipment  : {$request->modeof_shipment}"
				  	]);

				    $params['approved'] = 1;
				    $params['message']  = $messageData;
				    $params['status']   = 2;
				    $params['customer_id'] = $customer->id;
					$chat_message = \App\ChatMessage::create($params);

					// if product has image then send message with image otherwise send with photo
				    if ($productDispatch->hasMedia(config('constants.media_tags'))) {
		                foreach ($productDispatch->getMedia(config('constants.media_tags')) as $image) {
		                	$url = createProductTextImage($image->getAbsolutePath(),"product-dispatch",$messageData,$color = "000000", $fontSize = "15" , $needAbs = false);
		                	if(!empty($url)) {
		                	 	app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($customer->phone,$customer->whatsapp_number,null, $url);
		                	}
		                    $chat_message->attachMedia($image, config('constants.media_tags'));
		                }
		            }else{
		            	app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($customer->phone,$customer->whatsapp_number,$messageData);
		            }

				}
			}
		}



        return response()->json(["code" => 1, "message" => "Done"]);

	}

	public function locationChange(Request $request)
	{
		$product = \App\Product::where("id",$request->get("product_id",0))->first();

		if($product) {
			$product->location = $request->get("location",$product->location);
			$product->save();

			$productHistory = new \App\ProductLocationHistory();
			$params = [
				"location_name" => $product->location,
				"product_id" => $product->id,
				"date_time" => date("Y-m-d H:i:s")
			];
			$productHistory->fill($params);
			$productHistory->created_by = \Auth::user()->id;
			$productHistory->save();

		}

		return response()->json(["code" => 1, "productHistory" => $productHistory,"userName" => $productHistory->user->name]);

	}

	public function updateField(Request $request)
	{
		$id = $request->get("id");
		$fieldName = $request->get("field_name","");
		$fieldValue = $request->get("field_value","");

		if($id > 0 && !empty($fieldValue) && !empty($fieldName)) {
			$product = \App\Product::where("id", $id)->first();
			if($product) {
				$product->$fieldName = $fieldValue;
				$product->save();
				return response()->json(["code"=> 200,"message" => $fieldName." updated successfully"]);
			}
		}

		return response()->json(["code" => 500,"message" => "Oops, Required field is missing"]);

	}

	public function inventoryList(Request $request)
    {
    	ini_set("memory_limit", -1);
    	$filter_data = $request->input();
		$inventory_data = \App\Product::getProducts($filter_data);

		$query = DB::table('products as p')
				->selectRaw('
				   sum(CASE WHEN p.category = ""
			           OR p.category IS NULL THEN 1 ELSE 0 END) AS missing_category,
			       sum(CASE WHEN p.color = ""
			           OR p.color IS NULL THEN 1 ELSE 0 END) AS missing_color,
			       sum(CASE WHEN p.composition = ""
			           OR p.composition IS NULL THEN 1 ELSE 0 END) AS missing_composition,
			       sum(CASE WHEN p.name = ""
			           OR p.name IS NULL THEN 1 ELSE 0 END) AS missing_name,
			       sum(CASE WHEN p.short_description = ""
			           OR p.short_description IS NULL THEN 1 ELSE 0 END) AS missing_short_description,
			       sum(CASE WHEN p.price = ""
			           OR p.price IS NULL THEN 1 ELSE 0 END) AS missing_price,
			       sum(CASE WHEN p.size = ""
			           OR p.size IS NULL AND p.measurement_size_type IS NULL THEN 1 ELSE 0 END) AS missing_size,
			       sum(CASE WHEN p.measurement_size_type = ""
			           OR p.measurement_size_type AND p.size = "" OR p.size IS NULL THEN 1 ELSE 0 END) AS missing_measurement,
			       `p`.`supplier`
				')
				->where('p.supplier','<>','');
				$query = $query->groupBy('p.supplier')->havingRaw("missing_category > 1 or missing_color > 1 or missing_composition > 1 or missing_name > 1 or missing_short_description >1 ");

		$reportData = $query->get();

		$scrapped_query = DB::table('scraped_products as p')
				->selectRaw('
				   sum(CASE WHEN p.category = ""
			           OR p.category IS NULL THEN 1 ELSE 0 END) AS missing_category,
			       sum(CASE WHEN p.color = ""
			           OR p.color IS NULL THEN 1 ELSE 0 END) AS missing_color,
			       sum(CASE WHEN p.composition = ""
			           OR p.composition IS NULL THEN 1 ELSE 0 END) AS missing_composition,
			       sum(CASE WHEN p.title = ""
			           OR p.title IS NULL THEN 1 ELSE 0 END) AS missing_name,
			       sum(CASE WHEN p.description = ""
			           OR p.description IS NULL THEN 1 ELSE 0 END) AS missing_short_description,
			       sum(CASE WHEN p.price = ""
			           OR p.price IS NULL THEN 1 ELSE 0 END) AS missing_price,
			       sum(CASE WHEN p.size = ""
			           OR p.size IS NULL THEN 1 ELSE 0 END) AS missing_size,
			       `p`.`supplier`,
			       `p`.`website`
				')
				->where('p.website','<>','');
				$scrapped_query = $scrapped_query->groupBy('p.website')->havingRaw("missing_category > 1 or missing_color > 1 or missing_composition > 1 or missing_name > 1 or missing_short_description >1 ");

		$scrappedReportData = $scrapped_query->get();
		//dd($inventory_data);
        $inventory_data_count = $inventory_data->total();
        $status_list = \App\Helpers\StatusHelper::getStatus();
        // $supplier_list = \App\Supplier::pluck('supplier','id')->toArray();

        foreach ($inventory_data as $product) {
            $product['medias'] =  \App\Mediables::getMediasFromProductId($product['id']);
//			$product_history   =  \App\ProductStatusHistory::getStatusHistoryFromProductId($product['id']);
            $product_history   =  $product->productstatushistory;

            foreach ($product_history as $each) {
                $each['old_status'] = isset($status_list[$each['old_status']]) ? $status_list[$each['old_status']]  : 0;
                $each['new_status'] = isset($status_list[$each['new_status']]) ? $status_list[$each['new_status']] : 0;
            }
			$product['status_history'] = $product_history;
		
        }

        //for filter

        $sku = [];
        $pname = [];
        $brandsArray = [];
        $arr = DB::table('products')->select('name', 'sku')->get();
        foreach ($arr as $a){
            $sku[$a->sku]= $a->sku;
            $pname[$a->name]= $a->name;
        }

        $brands = DB::table('brands')->select('id', 'name')->get();
        foreach ( $brands as $brand ) {
            $brandsArray[$brand->id] = $brand->name;
        }

		$selected_brand = null;
		if($request->brand_names){
		$selected_brand = Brand::select('id','name')->whereIn('id',$request->brand_names)->get();
		}
	
		$selected_supplier = null;
		if($request->supplier){
		$selected_supplier = Supplier::select('id','supplier')->whereIn('id',$request->supplier)->get();
		}

		
		$selected_categories = null;
		if($request->product_categories){
		$selected_categories = Category::select('id','title')->whereIn('id',$request->product_categories)->get();
		}



		//        dd($brandsArray, $brandsArray);

//        $brands_names        = \App\Brand::getAll();
//        $products_names      = \App\Product::getPruductsNames();
//        $products_sku        = \App\Product::getPruductsSku();

        $brands_names        = $brandsArray;
        $products_names      = $pname;
        $products_sku        = $sku;

        asort($products_names);
        asort($products_sku);
        //$products_categories = \App\Product::getPruductsCategories();
        $products_categories = Category::attr(['name' => 'product_categories[]','data-placeholder' => 'Select a Category','class' => 'form-control select-multiple2', 'multiple' => true])->selected(request('product_categories',[]))->renderAsDropdown();

        if (request()->ajax()) return view("product-inventory.inventory-list-partials.load-more", compact('inventory_data'));
        return view('product-inventory.inventory-list',compact('inventory_data','products_names','products_categories','products_sku','status_list','inventory_data_count','reportData','scrappedReportData','selected_brand','selected_supplier','selected_categories'));
    }

    public function inventoryListNew( Request $request ){
    	$filter_data = $request->input();
		// $inventory_data = \App\Product::getProducts($filter_data);

		$inventory_data = \App\Product::join("store_website_product_attributes as swp", "swp.product_id", "products.id")->paginate(20);		
    	
    	$inventory_data_count = $inventory_data->total();
		

        if (request()->ajax()) return view("product-inventory.inventory-list-partials.load-more-new", compact('inventory_data'));

        return view('product-inventory.inventory-list-new',compact('inventory_data','inventory_data_count'));
    }

    public function downloadReport() {
    	
		$query = DB::table('products as p')
				->selectRaw('
				   sum(CASE WHEN p.category = ""
			           OR p.category IS NULL THEN 1 ELSE 0 END) AS missing_category,
			       sum(CASE WHEN p.color = ""
			           OR p.color IS NULL THEN 1 ELSE 0 END) AS missing_color,
			       sum(CASE WHEN p.composition = ""
			           OR p.composition IS NULL THEN 1 ELSE 0 END) AS missing_composition,
			       sum(CASE WHEN p.name = ""
			           OR p.name IS NULL THEN 1 ELSE 0 END) AS missing_name,
			       sum(CASE WHEN p.short_description = ""
			           OR p.short_description IS NULL THEN 1 ELSE 0 END) AS missing_short_description,
			       sum(CASE WHEN p.price = ""
			           OR p.price IS NULL THEN 1 ELSE 0 END) AS missing_price,
			       sum(CASE WHEN p.size = ""
			           OR p.size IS NULL AND p.measurement_size_type IS NULL THEN 1 ELSE 0 END) AS missing_size,
			       sum(CASE WHEN p.measurement_size_type = ""
			           OR p.measurement_size_type AND p.size = "" OR p.size IS NULL THEN 1 ELSE 0 END) AS missing_measurement,
			       `p`.`supplier`
				')
				->where('p.supplier','<>','');
				$query = $query->groupBy('p.supplier')->havingRaw("missing_category > 1 or missing_color > 1 or missing_composition > 1 or missing_name > 1 or missing_short_description >1 ");

		$reportDatas = $query->get();

    	return \Excel::download(new \App\Exports\ReportExport($reportDatas), 'exports.xls');
    }

    public function downloadScrapReport() {
    	
		$query = DB::table('scraped_products as p')
				->selectRaw('
				   sum(CASE WHEN p.category = ""
			           OR p.category IS NULL THEN 1 ELSE 0 END) AS missing_category,
			       sum(CASE WHEN p.color = ""
			           OR p.color IS NULL THEN 1 ELSE 0 END) AS missing_color,
			       sum(CASE WHEN p.composition = ""
			           OR p.composition IS NULL THEN 1 ELSE 0 END) AS missing_composition,
			       sum(CASE WHEN p.title = ""
			           OR p.title IS NULL THEN 1 ELSE 0 END) AS missing_name,
			       sum(CASE WHEN p.description = ""
			           OR p.description IS NULL THEN 1 ELSE 0 END) AS missing_short_description,
			       sum(CASE WHEN p.price = ""
			           OR p.price IS NULL THEN 1 ELSE 0 END) AS missing_price,
			       sum(CASE WHEN p.size = ""
			           OR p.size IS NULL THEN 1 ELSE 0 END) AS missing_size,
			       `p`.`supplier`
				')
				->where('p.supplier','<>','');
				$query = $query->groupBy('p.supplier')->havingRaw("missing_category > 1 or missing_color > 1 or missing_composition > 1 or missing_name > 1 or missing_short_description >1 ");

		$reportDatas = $query->get();

    	return \Excel::download(new \App\Exports\ReportExport($reportDatas), 'exports.xls');
    }
  
  public function inventoryHistory($id) {
		$inventory_history   =  \App\InventoryStatusHistory::getInventoryHistoryFromProductId($id);
			
		foreach ($inventory_history as $each) {
			$supplier = \App\Supplier::find($each['supplier_id']);
			if($supplier) {
				$each['supplier'] = $supplier->supplier;
			}
			else {
				$each['supplier'] = '';
			}
		}
		return response()->json(['data' => $inventory_history]);;
	}
	
	public function getSuppliers($id) {
		$suppliers   =  Product::with(['suppliers_info','suppliers_info.supplier'])->find($id);
		return response()->json(['data' => $suppliers->suppliers_info]);;
	}
  
	public function getProductImages($id) {
	  $product = Product::find($id);
	  $urls = [];
      if($product) {
        $medias =  \App\Mediables::getMediasFromProductId($id);
		 $medias = $product->getMedia(config('constants.attach_image_tag'));
		 foreach($medias as $media) {
			$urls[] = $media->getUrl();
		 }
	  }
	  return response()->json(['urls' => $urls]);
	}

	public function changeSizeSystem(Request $request) 
	{
		$product_ids = $request->get("product_ids");
		$size_system = $request->get("size_system");
		$messages = [];
		$errorMessages = [];
		if(!empty($size_system) && !empty($product_ids)) {
			$products = \App\Product::whereIn("id",$product_ids)->get();
			if(!$products->isEmpty()) {
				foreach($products as $product) {
					$productSupplier = \App\ProductSupplier::where("product_id",$product->id)->where("supplier_id",$product->supplier_id)->first();
					if($productSupplier) {
						$productSupplier->size_system = $size_system;
						$allSize =  explode(",",$product->size);
						$euSize = \App\Helpers\ProductHelper::getEuSize($product, $allSize, $productSupplier->size_system);
		                $product->size_eu = implode(',', $euSize);
		                if(empty($euSize)) {
		                	//$product->size_system = "";
		                    $product->status_id = \App\Helpers\StatusHelper::$unknownSize;
		                    $errorMessages[] = "$product->sku has issue with size";
		                }else{
		                	$messages[] = "$product->sku updated successfully";
		                	foreach($euSize as $es) {
		                        \App\ProductSizes::updateOrCreate([
		                           'product_id' =>  $product->id,'supplier_id' => $product->supplier_id, 'size' => $es 
		                        ],[
		                           'product_id' =>  $product->id,'quantity' => 1,'supplier_id' => $product->supplier_id, 'size' => $es
		                        ]);
		                    }
		                }
		                $productSupplier->save();
		                $product->save();
					}
				}
			}
		}

		return response()->json(["code" => 200 , "data" => [],"message" => implode("</br>", $messages),"error_messages" => implode("</br>", $errorMessages)]);
	}

	public function changeErpSize(Request $request)
	{
		$sizes = $request->sizes;
		$erpSizes = $request->erp_size;
		$sizeSystemStr = $request->size_system;
		$categoryId = $request->category_id;


		if(!empty($sizes) && !empty($erpSizes) && !empty($sizeSystemStr)) {
			/// check first size system exist or not
			$sizeSystem = \App\SystemSize::where("name",$sizeSystemStr)->first();

			if(!$sizeSystem) {
				$sizeSystem = new \App\SystemSize;
				$sizeSystem->name = $sizeSystem;
				$sizeSystem->save();
			}

			// check size exist or not
			if(!empty($erpSizes)) {
				foreach($erpSizes as  $k => $epSize) {
					$existSize  = \App\SystemSizeManager::where("category_id", $categoryId)->where("erp_size",$epSize)->first();

					if(!$existSize) {
						$existSize = new \App\SystemSizeManager;
						$existSize->category_id = $categoryId;
						$existSize->erp_size = $epSize;
						$existSize->status = 1;
						$existSize->save();
					}

					if(isset($sizes[$k])) {
						$checkMainSize = \App\SystemSizeRelation::where("system_size_manager_id", $sizeSystem->id)
						->where("system_size",$existSize->id)
						->where("size",$sizes[$k])
						->first();

						if(!$checkMainSize) {
							$checkMainSize = new \App\SystemSizeRelation;
							$checkMainSize->system_size_manager_id = $existSize->id;
							$checkMainSize->system_size = $sizeSystem->id;
							$checkMainSize->size = $sizes[$k];
							$checkMainSize->save();
						}

					}

				}

				UpdateFromSizeManager::dispatch([
				 	"category_id" => $categoryId,
				 	"size_system" => $sizeSystemStr
				])->onQueue("mageone");
			}
		}

		return response()->json(["code" => 200 , "data" => [], "message" => "Your request has been send to the jobs"]);

	}

	public function updateStatus(Request $request) 
	{
		$product_ids 	= $request->get("product_ids");
		$product_status = $request->get("product_status");
		
		$messages = [];
		$errorMessages = [];
		if(!empty($product_status) && !empty($product_ids)) {

			$products = \App\Product::whereIn("id",$product_ids)->get();
			if(!$products->isEmpty()) {
					foreach($products as $product) {
						if( $product->status_id != $product_status ){
							$product->status_id = $product_status;
							$product->save();
							$messages[] = "$product->name updated successfully";
						}
					}
			}else{
				$messages[] = 'Something went wrong. Please try again later.';
			}
		}

		return response()->json(["code" => 200 , "data" => [],"message" => implode("</br>", $messages),"error_messages" => implode("</br>", $errorMessages)]);
	}

	public function supplierProductSummary(Request $request,int $supplier_id)
	{
		

		$inventory=\App\InventoryStatusHistory::whereDate('created_at','>', Carbon::now()->subDays(7))->where('supplier_id',$supplier_id)->orderBy('in_stock','desc');
		

		if($request->search)
		{
			$inventory->where('product_id','like','%'.$request->search)->orWhereHas('product', function ($query) use($request) {

           $query->where('name', 'like','%'.$request->search.'%');

             });
		}
       
		$total_rows=$inventory->count();


		$inventory=$inventory->paginate(Setting::get('pagination'));

		
       $allHistory=[];

    
		foreach ($inventory as $key => $history) {

			$row=array('id'=>$history->id,'product_name'=>$history->product->name??'','supplier_name'=>$history->supplier->supplier??'','product_id'=>$history->product_id,'brand_name'=>$history->product->brands->name??'');


          $dates=\App\InventoryStatusHistory::whereDate('created_at','>', Carbon::now()->subDays(7))->where('supplier_id',$history->supplier_id)->where('product_id',$history->product_id)->get();

          $row['dates']=$dates;

          $allHistory[]=(object)$row;

			
		}
  

		return view('product-inventory.supplier-inventory-history',compact('allHistory','inventory','total_rows','request'));


	}

	public function supplierProductHistory(Request $request)
	{
		$suppliers = \App\Supplier::all();
		$inventory = \App\InventoryStatusHistory::select('created_at','supplier_id',DB::raw('count(distinct product_id) as product_count_count,GROUP_CONCAT(product_id) as brand_products'))
			->whereDate('created_at','>=', Carbon::now()->subDays(7))
			->where('in_stock','>',0)
			->groupBy('supplier_id');


		if($request->supplier) {
			$inventory = $inventory->where('supplier_id',$request->supplier);
		}

		$inventory = $inventory->orderBy('product_count_count','desc')->paginate(24);

		$total_rows = $inventory->total();

		$allHistory = [];
		$date = date('Y-m-d', strtotime(date("Y-m-d") . ' -6 day'));
		$extraDates = $date;
		$columnData = [];
		for ($i=1; $i < 8 ; $i++) { 
			$columnData[] = $extraDates;
			$extraDates   = date('Y-m-d', strtotime($extraDates . ' +1 day'));
		}


		foreach ($inventory as $key => $row) {
            
            $newRow = [];
			$newRow['supplier_name'] = $row->supplier->supplier;
			$brandCount = \App\InventoryStatusHistory::join("products as p","p.id","inventory_status_histories.product_id")->whereDate('inventory_status_histories.created_at','>', Carbon::now()->subDays(7))->where("inventory_status_histories.supplier_id",$row->supplier_id)
			->where('in_stock','>',0)
			->groupBy("p.brand")
			->select(\DB::raw("count(p.brand) as total"))
			->get()
			->count();

			$newRow['brands'] = $brandCount;
			$newRow['products'] = $row->product_count_count;
			$newRow['supplier_id'] = $row->supplier_id;

			foreach ($columnData as $c) { 
				# code...
				$totalProduct = \App\InventoryStatusHistory::whereDate('created_at',$c)->where('supplier_id',$row->supplier_id)->select(\DB::raw("count(distinct product_id) as total_product"))->first();

				$newRow['dates'][$c] = ($totalProduct) ? $totalProduct->total_product : 0;
			}

			array_push($allHistory,$newRow);
		}

		return view('product-inventory.supplier-product-history',compact('allHistory','inventory','total_rows','suppliers','request','columnData'));


	}


	public function supplierProductHistoryBrand (Request $request) 
	{
		$inventory = \App\InventoryStatusHistory::join("products as p","p.id","inventory_status_histories.product_id")
			->leftjoin("brands as b","b.id","p.brand")
			->whereDate('inventory_status_histories.created_at','>', Carbon::now()->subDays(7))->where("inventory_status_histories.supplier_id",$request->supplier_id)
			->where('in_stock','>',0)
			->groupBy("p.brand")
			->select([\DB::raw("count(distinct p.id) as total"),"p.brand","b.name"])
			->orderBy("total","desc")
			->get();

			return view("product-inventory.brand-history",compact('inventory'));
	}

	public function mergeScrapBrand(Request $request)
	{
		$scraperBrand 	= $request->get("scraper_brand");
		$originalBrand  = $request->get("product_brand");

		if(!empty($scraperBrand) && !empty($originalBrand)) {
			$updateQuery = \DB::statement('update products join scraped_products as sp on sp.sku = products.sku 
						join brands as b1 on b1.id = products.brand
						join brands as b2 on b2.id = sp.brand_id
						set products.brand = sp.brand_id , products.last_brand = products.brand
						where b1.name = ? and b2.name = ?',[$originalBrand,$scraperBrand]);

		}else{
			return redirect()->back()->with('error', 'Please enter product brand and scraper brand');
		}

		return redirect()->back()->with('message', 'Product(s) updated successfully');

	}


	public function supplierDiscountFiles (Request $request) 
	{
		$suppliers = \App\Supplier::all();
		$rows = \App\SupplierBrandDiscount::with('supplier', 'brand')->paginate(30);

		return view('product-inventory.discount-files',compact('suppliers','rows'));
	}

	public function exportExcel(Request $request){

		$this->validate($request, [
            'excel' => 'required|file',
        ]);

        $file = $request->file('excel');

        if ($file->getClientOriginalExtension() == 'xlsx') {
            $reader = new Xlsx();
        } else {
            if ($file->getClientOriginalExtension() == 'xls') {
                $reader = new Xls();
            }
        }

        try{
			$spreadsheet = $reader->load($file->getPathname()); 

			$rows = $spreadsheet->getActiveSheet()->toArray();

			if($rows[1][0] == 'Brand'){
				foreach($rows as $key => $row){
					if($key == 0 || $key == 1) continue;
					$brand = trim($row[0]);

					if ($brand == "TOD'S") {
						$brand = 'TODS';
					}
					if ($brand == 'VALENTINO') {
						$brand = 'VALENTINO GARAVANI';
					}
					if ($brand == 'SAINT LAURENT') {
						$brand = 'YVES SAINT LAURENT';
					}
					if ($brand == 'MOSCHINO LOVE') {
						$brand = 'MOSCHINO';
					}
					if ($brand == 'DIOR') {
						$brand = 'CHRISTIAN DIOR';
					}
					if ($brand == "CHLOE'") {
						$brand = 'CHLOE';
					}

					$brand = Brand::where('name', $brand)->first();
					if(!$brand) {
						continue;
					}

					$discount = new SupplierBrandDiscount();
					$exist_row = SupplierBrandDiscount::where('brand_id', $brand->id)->where('supplier_id', $request->supplier)->where('gender', $row[1])->where('category', $row[2])->whereNull('generic_price')->where('condition_from_retail', $row[4])->where('condition_from_retail_exceptions', $row[5])->first();
					if($exist_row) continue;
					$discount->supplier_id = $request->supplier;
					$discount->brand_id = $brand->id;
					$discount->gender = $row[1];
					$discount->category = $row[2];
					// $discount->generic_price = $row['generic_price'];
					$discount->exceptions = $row[3];
					$discount->condition_from_retail = $row[4];
					$discount->condition_from_retail_exceptions = $row[5];
					$discount->save();	
				}  
			return redirect()->back()->with('success', 'Excel Imported Successfully!');

			}
			
			if($rows[0][1] == 'SS21'){
				$array1 = $array2 = []; $first_time1 = 1;
				foreach($rows as $key => $row){
					if($row[1] == 'SS21' || $row[1] == 'ST' || $key == 2 ) continue;

					$array1[] = [$row[1], $row[2]];
					$array2[] = [$row[4], $row[5]];
					
				}
				$categories = [];
				$cat = [];
				foreach($array1 as $key => $row){
					if($row[0] == null && $row[1] == null){
						if($cat[0][0] == null && $cat[0][1] == null){
							unset($cat[0]);
						}
						$categories[] = $cat;
						$cat = [];
					}	
					$cat[] = $row;		
				}
				if($cat[0][0] == null && $cat[0][1] == null){
					unset($cat[0]);
				}
				$categories[] = $cat; 
				$cat = [];
				foreach($array2 as $key => $row){
					if($row[0] == null && $row[1] == null){
						if($cat[0][0] == null && $cat[0][1] == null){
							unset($cat[0]);
						}
						$categories[] = $cat;
						$cat = [];
					}	
					$cat[] = $row;		
				}
				if($cat[0][0] == null && $cat[0][1] == null){
					unset($cat[0]);
				}
				$categories[] = $cat; 
				$total = 1; 
				foreach($categories as $key_ => $cats){
					if(isset($cats[0])){
						array_unshift($cats, []);
					}
					foreach($cats as $key => $cat){
						if($key == 1) {
							$category = trim($cat[0]);
							$gender = strpos($category, 'WOMAN') !== false ? 'WOMAN' : 'MAN';
							$category = str_replace(' + ACC', '', $category);
							continue;
						}else if($key == 2) {
							$gen_price = $cat[0];
							$generic_price = trim(str_replace('GENERIC PRICE: COST +', '', $gen_price));
							$generic_price = trim(str_replace('GENERIC PRICE: COST+', '', $generic_price));
							continue;
						}else if($key == 3 || $key == 0){
							continue;
						}else{
							$brand = trim($cat[0]);
							$condition_from_retail = $cat[1] !== null ? str_replace('C+', '', $cat[1]) : $condition_from_retail;
							if($brand == "TOD'S") {
								$brand = 'TODS';
							}
							else if($brand == 'VALENTINO') {
								$brand = 'VALENTINO GARAVANI';
							}
							else if($brand == 'SAINT LAURENT') {
								$brand = 'YVES SAINT LAURENT';
							}
							else if($brand == 'MOSCHINO LOVE') {
								$brand = 'MOSCHINO';
							}
							else if($brand == 'DIOR') {
								$brand = 'CHRISTIAN DIOR';
							}
							else if($brand == "CHLOE'") {
								$brand = 'CHLOE';
							}
			
							$brand = Brand::where('name', $brand)->first();
			
							if(!$brand) {
								continue;
							}
			
							$discount = new SupplierBrandDiscount();
							$exist_row = SupplierBrandDiscount::where('brand_id', $brand->id)->where('supplier_id', $request->supplier)->where('gender', $gender)->where('category', $category)->where('generic_price', $generic_price)->first();
							if($exist_row) continue;
							$discount->supplier_id = $request->supplier;
							$discount->brand_id = $brand->id;
							$discount->gender = $gender;
							$discount->category = $category;
							$discount->generic_price = $generic_price; 
							$discount->condition_from_retail = $condition_from_retail; 
							$discount->save();	
						}
					}
				}
			return redirect()->back()->with('success', 'Excel Imported Successfully!');

			}

		}catch(\Exception $e){

			return redirect()->back()->with('error', 'Something went wrong, please check your file!');

		}

		return redirect()->back()->with('error', 'Something went wrong, please check your file!');

	}

}

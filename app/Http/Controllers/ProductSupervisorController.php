<?php

namespace App\Http\Controllers;

use App\Product;
use App\Setting;
use App\Stage;
use App\Category;
use App\Brand;
use Illuminate\Http\Request;

class ProductSupervisorController extends Controller
{
    public function __construct() {

	  //  $this->middleware('permission:supervisor-list',['only' => ['index']]);
	  //  $this->middleware('permission:supervisor-edit',['only' => ['edit','approve']]);
    }


	public function index(Stage $stage){

		$products = Product::where('stock', '>=', 1)->latest()
//		                   ->where('stage','>=', $stage->get('Attribute') )
		                   ->whereNull('dnf')
                       ->select(['id', 'sku', 'size', 'price_inr_special', 'brand', 'supplier', 'isApproved', 'stage', 'status', 'is_scraped', 'created_at'])
		                   ->paginate(Setting::get('pagination'));

		$roletype = 'Supervisor';

    $category_selection = Category::attr(['name' => 'category[]','class' => 'form-control select-multiple'])
		                                        ->selected(1)
		                                        ->renderAsDropdown();

		return view('partials.grid',compact('products','roletype', 'category_selection'))
			->with('i', (request()->input('page', 1) - 1) * 10);

	}

	public function edit(Product $productsupervisor){

		return redirect( route('products.show',$productsupervisor->id) );
	}

	public function approve(Product $product,Stage $stage){

		$product->isApproved = 1;
		$product->stage = $stage->get('Supervisor');
		$product->save();

		NotificaitonContoller::store('has Approved',['ImageCropers'],$product->id);
		ActivityConroller::create($product->id,'supervisor','create');

		return back()->with('success', 'Product has been approved');

//		return ['msg'=>'success', 'isApproved'  => $product->isApproved ];
	}

	public function reject(Product $product,Request $request){

    	$this->validate($request,[
    		'role' => 'required',
		    'reason' => 'required',
	    ]);


		$role = $request->input('role');
		$reason = $request->input('reason');

		$product->rejected_note = $reason;
		$product->isApproved = -1;
		$product->save();


		NotificaitonContoller::store('has Rejected due to '.$reason,[$role],$product->id);
		ActivityConroller::create($product->id,'supervisor','reject');


		return back()->with( 'rejected', 'Product has been rejected' );

	}
}

<?php

namespace App\Http\Controllers;

use App\Product;
use App\Setting;
use App\Stage;
use App\Brand;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductSearcherController extends Controller
{
	//
	public function __construct() {

//		$this->middleware('permission:searcher-list',['only' => ['sList','index']]);
//		$this->middleware('permission:searcher-create', ['only' => ['create','store']]);
//		$this->middleware('permission:searcher-edit', ['only' => ['edit','update']]);
//
//
//		$this->middleware('permission:searcher-delete', ['only' => ['destroy']]);
	}


	public function index(){

		$products = Product::where('stock', '>=', 1)->latest()
											->withMedia(config('constants.media_tags'))
											->select(['id', 'sku', 'size', 'price_inr_special', 'brand', 'supplier', 'isApproved', 'stage', 'status', 'is_scraped', 'created_at'])
											->paginate(Setting::get('pagination'));

		$roletype = 'Searcher';

		$category_selection = Category::attr(['name' => 'category[]','class' => 'form-control select-multiple'])
		                                        ->selected(1)
		                                        ->renderAsDropdown();

		return view('partials.grid',compact('products','roletype', 'category_selection'))
			->with('i', (request()->input('page', 1) - 1) * 10);

	}


	public function edit(Product $productsearcher)
	{
		if( $productsearcher->isApproved == 1)
			return redirect(route('products.show',$productsearcher->id));

		return view('productsearcher.edit',compact('productsearcher'));
	}

	public function update(Request $request, Product $productsearcher,Stage $stage)
	{
		$validations  = [
			'sku'   => 'required_without:dnf|unique:products,sku,'.$productsearcher->id,
		];

		if( $request->input('oldImage') != 0)
			$validations['image'] = 'required_without:dnf | mimes:jpeg,bmp,png,jpg';

		$this->validate( $request,  $validations);

		$productsearcher->dnf = $request->input('dnf');
		$productsearcher->sku = $request->input('sku');
		$productsearcher->size = $request->input('size');
		$productsearcher->size_eu = $request->input('size_eu');
		$productsearcher->price = $request->input('price');
		$productsearcher->product_link = $request->input('product_link');
		$productsearcher->supplier = $request->input('supplier');
		$productsearcher->supplier_link = $request->input('supplier_link');
		$productsearcher->description_link = $request->input('description_link');
		$productsearcher->stage = $stage->get('Searcher');
		$productsearcher->last_searcher = Auth::id();

		self::replaceImage($request,$productsearcher);
//		$product->update($request->all());

		$productsearcher->save();

		NotificaitonContoller::store('has searched',['Attribute'], $productsearcher->id);
		ActivityConroller::create($productsearcher->id,'searcher','create');

		return redirect()->route('productsearcher.index')
		                 ->with('success','Searcher updated successfully');
	}

	public function replaceImage($request,$productsearcher){


		if( $request->input('oldImage') != 0) {

			$results = Media::where('id' , $request->input('oldImage') )->get();

			$results->each(function($media) {
				Image::trashImage($media->basename);
				$media->delete();
			});

			if( !empty($request->file('image') ) ) {

				$media = MediaUploader::fromSource( $request->file( 'image' ) )
										->toDirectory('product/'.floor($productsearcher->id / config('constants.image_per_folder')))
										->upload();
				$productsearcher->attachMedia( $media, config( 'constants.media_tags' ) );
			}
		}

	}

}

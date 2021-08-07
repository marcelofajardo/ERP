<?php

namespace App\Http\Controllers;

use App\ApiKey;
use App\Customer;
use App\ProductQuicksellGroup;
use App\QuickSellGroup;
use Illuminate\Http\Request;
use App\Product;
use App\Setting;
use App\Category;
use App\Brand;
use App\Supplier;
use App\ReadOnly\LocationList;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Response;

class QuickSellController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $category_dropdown = (new \App\Category)->attr([
        'name' => 'category[]', 
        'class' => 'form-control select-multiple2', 
        'multiple' => 'multiple',
        'data-placeholder' => 'Select Category'
      ])->selected(request('category'))->renderAsDropdown();

      $products = Product::where('quick_product',1)->where('is_pending',0)->latest();
      $totalProduct = $products->count();
      $products = $products->paginate(Setting::get('pagination'));

      $allSize  = Product::where('quick_product',1)->where('is_pending',0)->groupBy("size")->select("size")->pluck("size")->toArray();

      $brands_all = Brand::all();
      $categories_all = Category::all();
      $brands = [];
      $categories = [];

      foreach ($brands_all as $brand) {
        $brands[$brand->id] = $brand->name;
      }

      foreach ($categories_all as $category) {
        $categories[$category->id] = $category->title;
      }

      $category_selection = Category::attr(['name' => 'category','class' => 'form-control', 'id'  => 'category_selection'])
  		                                        ->renderAsDropdown();

      $selected_categories = $request->category ? $request->category : 1;

  		$filter_categories_selection = Category::attr(['name' => 'category','class' => 'form-control', 'id' => 'filter_categories_selection'])
  		                                        ->selected($selected_categories)
  		                                        ->renderAsDropdown();

      $locations = \App\ProductLocation::pluck("name","name");
      $suppliers = Supplier::select(['id', 'supplier'])->where('supplier_status_id', 1)->orderby('supplier','asc')->get();

      $category_tree = [];
  		$categories_array = [];

  		foreach (Category::all() as $category) {
  			if ($category->parent_id != 0) {
  				$parent = $category->parent;
  				if ($parent->parent_id != 0) {
  					$category_tree[$parent->parent_id][$parent->id][$category->id];
  				} else {
  					$category_tree[$parent->id][$category->id] = $category->id;
  				}
  			}

  			$categories_array[$category->id] = $category->parent_id;
  		}

      $new_category_selection = Category::attr(['name' => 'category','class' => 'form-control', 'id' => 'product-category'])->renderAsDropdown();
        $api_keys = ApiKey::select('number')->get();
        $customers = Customer::orderBy('name','asc')->get();

      return view('quicksell.index', [
        'products'  => $products,
        'brands'  => $brands,
        'categories'  => $categories,
        'category_selection'  => $category_selection,
        'brand'         => $brand,
        'category'      => $category,
        'location'      => $location ?? '',
        'suppliers'      => $suppliers,
        'filter_categories_selection'  => $filter_categories_selection,
        'locations'  => $locations,
        'category_tree'  => $category_tree,
        'categories_array'  => $categories_array,
        'new_category_selection'  => $new_category_selection,
        'api_keys' =>  $api_keys,
        'customers' => $customers,
        'totalProduct' => $totalProduct,
        'category_dropdown' => $category_dropdown
      ]);
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
    public function store(Request $request)
    {

      $this->validate($request,[
  			'sku'    => 'required|unique:products',
  			'images.*' => 'required | mimes:jpeg,bmp,png,jpg',
  		]);

        $product = new Product;

        $product->name = $request->name;
    		$product->sku = $request->sku;
    		$product->size = $request->size ? implode(',', $request->size) : $request->other_size;
        	$product->size_eu = $request->get("size_eu",null);
    		$product->brand = $request->brand;
    		$product->color = $request->color;
    		$product->supplier = $request->supplier;
    		$product->location = $request->location;
    		$product->category = $request->category;
    		$product->price = $request->price;
    		$product->stock = 1;
        $product->quick_product = 1;

  		$brand = Brand::find($request->brand);

  		if ($request->price) {
  			if(isset($request->brand) && !empty($brand->euro_to_inr))
  				$product->price_inr = $brand->euro_to_inr * $product->price;
  			else
  				$product->price_inr = Setting::get('euro_to_inr') * $product->price;

  			$product->price_inr = round($product->price_inr, -3);
  			$product->price_inr_special = $product->price_inr - ($product->price_inr * $brand->deduction_percentage) / 100;

  			$product->price_inr_special = round($product->price_inr_special, -3);
  		}

  		$product->save();

  		if ($request->supplier != '') {
        $supplier = Supplier::where('supplier', $request->supplier)->first();
  			$product->suppliers()->attach($supplier); // In-stock ID
  		}

      if ($request->hasfile('images')) {
        foreach ($request->file('images') as $image) {
          $filename = str_slug($image->getClientOriginalName());
      		$media = MediaUploader::fromSource($image)->useFilename($filename)
                                ->toDirectory('product/'.floor($product->id / config('constants.image_per_folder')))
                                ->upload();
      		$product->attachMedia($media,config('constants.media_tags'));
        }
      }

      return redirect()->route('quicksell.index')->with('success', 'You have successfully uploaded image');
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
    public function update(Request $request)
    {
      $id = $request->id;
      $this->validate($request,[
  			'images.*' => 'sometimes | mimes:jpeg,bmp,png,jpg',
  		]);

      $product = Product::find($id);

      $product->supplier = $request->supplier;
      $product->price = $request->price;
      $product->size = $request->size;
      $product->size_eu = $request->get("size_eu");
      $product->brand = $request->brand;
      $product->location = $request->location;
      $product->category = $request->category;

      if(!empty($product->brand) && !empty($product->price)) {
  			$product->price_inr     = app('App\Http\Controllers\ProductSelectionController')->euroToInr($product->price, $product->brand);
  			$product->price_inr_special = app('App\Http\Controllers\ProductSelectionController')->calculateSpecialDiscount($product->price_inr, $product->brand);
  		} else {
  			$product->price_inr_special = $request->price_special;
  		}

      if($request->is_pending !== null) {
        $product->is_pending = $request->is_pending;
      }

      $product->update();
      //dd($request);
      if($request->group_old != null){
          ProductQuicksellGroup::where('product_id',$product->id)->delete();
          $edit = new ProductQuicksellGroup();
          $edit->quicksell_group_id = $request->group_old;
          $edit->product_id = $product->id;
          $edit->save();

      }elseif($request->group_new != null){
          ProductQuicksellGroup::where('product_id',$product->id)->delete();
           $group = QuickSellGroup::orderBy('id', 'desc')->first();

           $group_create =  new QuickSellGroup();
           $incrementId = ($group->group+1);
           $group_create->group = $incrementId;
           $group_create->name = $request->group_new;
           $group_create->save();

           $edit = new ProductQuicksellGroup();
           $edit->quicksell_group_id =  $group_create->group;
           $edit->product_id = $product->id;
           $edit->save();
      }

      if ($request->hasfile('images')) {
        foreach ($request->file('images') as $image) {
          $filename = str_slug($image->getClientOriginalName());
      		$media = MediaUploader::fromSource($image)
                                ->useFilename($filename)
                                ->toDirectory('product/'.floor($product->id / config('constants.image_per_folder')))
                                ->upload();
      		$product->attachMedia($media,config('constants.media_tags'));
        }
      }

      if(isset($product->supplier)){
        $supplier = $product->supplier;
      }else{
        $supplier = '';
      }

      if(isset($product->price)){
        $price = $product->price;
      }else{
        $price = '';
      }

       if(isset($product->brands->name)){
        $brand = $product->brands->name;
      }else{
        $brand = '';
      }

      if(isset($product->product_category->title)){
        $title = $product->product_category->title;
      }else{
        $title = '';
      }

      if(isset($product->size)){
        $size = $product->size;
      }else{
        $size = '';
      }

      if($request->group_new == null && $request->group_old == null){
        $input =  '<input type="checkbox" name="blank" class="group-checkbox checkbox" data-id='.$product->id.'>';
        $data = [$supplier,$price,$brand,$title,$input,$size];
      }
      if($request->group_new != null){
         $data = [$supplier,$price,$brand,$title,$request->group_new,$size];
      }
      if($request->group_old != null){
         $data = [$supplier,$price,$brand,$title,$request->group_old,$size];
      }
      return Response::json(array(
        'success' => true,
        'data'   => $data));
      //return redirect()->back()->with('success', 'You have successfully updated Quick Product');
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

    public function generateRandomSku()
    {
      $sku = Product::where('sku', 'LIKE', "%QCKPRO%")->latest()->select(['sku'])->first();

      if ($sku) {
        $exploded = explode('-', $sku->sku);
        $new_sku = 'QCKPRO-' . (intval( $exploded[1] ) + 1);

  			return $new_sku;
      }

      return 'QCKPRO-000001';
    }
    public function saveGroup(Request $request){
        if($request->type != null && $request->products){
        if($request->type == 1){
            foreach ($request->products as $id){
                $group = new ProductQuicksellGroup();
                $group->product_id = $id;
                $group->quicksell_group_id = $request->group;
                $group->save();
            }
        }else{
            $group = QuickSellGroup::orderBy('id', 'desc')->first();
            if ($group != null) {
                $group_create =  new QuickSellGroup();
                $incrementId = ($group->group+1);
                $group_create->group = $incrementId;
                $group_create->save();
                $group_id = $group_create->group;
            } else {
                $group =  new QuickSellGroup();
                $group->group = 1;
                $group->save();
                $group_id = $group->group;
            }
            foreach ($request->products as $id){
                $group = new ProductQuicksellGroup();
                $group->product_id = $id;
                $group->quicksell_group_id = $group_id;
                $group->save();
            }
        }
        }else{
            return redirect()->route('quicksell.index')->with('success', 'Failed saving Quick Product Group');
        }


        return redirect()->route('quicksell.index')->with('success', 'You have successfully saved Quick Product Group');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending(Request $request)
    {

      if($request->selected_products || $request->term  || $request->category || $request->brand || $request->color || $request->supplier ||
            $request->location || $request->size || $request->price ){

            $query  = Product::query();
            if (request('term') != null) {
                $query->where('sku', '=', request('term',0))
                    ->orWhere('supplier', 'LIKE', request('term',0))
                    ->orWhereHas('brands', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->term}%");
                    })
                    ->orWhereHas('product_category', function ($qu) use ($request) {
                    $qu->where('title', 'like', "%{$request->term}%");
                    });
            }
            if (request('category') != null) {
                $query->whereIn('category', request('category',0));
            }
            if (request('brand') != null) {
                $query->whereIn('brand', request('brand'));
            }
            if (request('color') != null) {
                $query->whereIn('color', request('color'));
            }
            if (request('supplier') != null) {
                $query->whereIn('supplier', request('supplier'));
            }
            if (request('location') != null) {
                $query->where('location','LIKE', request('location',0));
            }
            if (request('size') != null) {
                $query->where('size','LIKE', request('size'));
            }

            if (request('group') != null) {
                $query->orWhereHas('groups', function ($qu) use ($request) {
                    $qu->whereIn('quicksell_group_id',$request->group);
                    });
            }

            if (request('price') != null) {
                $price = (explode(",",$request->price));
                $from = $price[0];
                $to = $price[1];
                $query->whereBetween('price',[ $from , $to ]);
            }

            if(request('per_page') != null){
                $per_page = request('per_page');
            }else{
                $per_page = Setting::get('pagination');
            }

            $products = $query->where('quick_product',1)->where('is_pending',1)->paginate($per_page);

        }else{
            $products = Product::where('is_pending',1)->latest()->paginate(Setting::get('pagination'));
        }

        $brands_all = Brand::all();
        $categories_all = Category::all();
        $brands = [];
        $categories = [];

        foreach ($brands_all as $brand) {
            $brands[$brand->id] = $brand->name;
        }

        foreach ($categories_all as $category) {
            $categories[$category->id] = $category->title;
        }

        $category_selection = Category::attr(['name' => 'category','class' => 'form-control', 'id'  => 'category_selection'])
            ->renderAsDropdown();

        $selected_categories = $request->category ? $request->category : 1;

        $filter_categories_selection = Category::attr(['name' => 'category','class' => 'form-control', 'id' => 'filter_categories_selection'])
            ->selected($selected_categories)
            ->renderAsDropdown();

        $locations = \App\ProductLocation::pluck("name","name");
        $suppliers = Supplier::select(['id', 'supplier'])->get();

        $category_tree = [];
        $categories_array = [];

        foreach (Category::all() as $category) {
            if ($category->parent_id != 0) {
                $parent = $category->parent;
                if ($parent->parent_id != 0) {
                    $category_tree[$parent->parent_id][$parent->id][$category->id];
                } else {
                    $category_tree[$parent->id][$category->id] = $category->id;
                }
            }

            $categories_array[$category->id] = $category->parent_id;
        }

        $new_category_selection = Category::attr(['name' => 'category','class' => 'form-control', 'id' => 'product-category'])
            ->renderAsDropdown();

        return view('quicksell.pending', [
            'products'  => $products,
            'brands'  => $brands,
            'categories'  => $categories,
            'category_selection'  => $category_selection,
            'brand'         => $brand,
            'category'      => $category,
            'location'      => $location ?? '',
            'suppliers'      => $suppliers,
            'filter_categories_selection'  => $filter_categories_selection,
            'locations'  => $locations,
            'category_tree'  => $category_tree,
            'categories_array'  => $categories_array,
            'new_category_selection'  => $new_category_selection,
        ]);
    }

    public function activate(Request $request){
      //dd($request);
        $ids = explode(',',$request->checkbox_value);

        if($request->id == null){
           foreach ($ids as $id) {
          $product = Product::findorfail($id);
          $product->is_pending = 0;
          $product->update();
        }


        }else{

          $product = Product::findorfail($request->id);
          $product->is_pending = 0;
          $product->update();


        }

        return redirect()->route('quicksell.pending')->with('success', 'You have activated Quick Product');
    }

    public function search(Request $request)
    {
        $category_dropdown = (new \App\Category)->attr([
          'name' => 'category[]', 
          'class' => 'form-control select-multiple2', 
          'multiple' => 'multiple',
          'data-placeholder' => 'Select Category'
        ])->selected(request('category'))->renderAsDropdown();

        if($request->selected_products || $request->term  || $request->category || $request->brand || $request->color || $request->supplier ||
            $request->location || $request->size || $request->price ){

            $query  = Product::query();
            if (request('term') != null) {
                $query->where('sku', '=', request('term',0))
                    ->orWhere('supplier', 'LIKE', request('term',0))
                    ->orWhereHas('brands', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->term}%");
                    })
                    ->orWhereHas('product_category', function ($qu) use ($request) {
                    $qu->where('title', 'like', "%{$request->term}%");
                    });
            }
            if (request('category') != null) {
                $query->whereIn('category', request('category',0));
            }
            if (request('brand') != null) {
                $query->whereIn('brand', request('brand'));
            }
            if (request('color') != null) {
                $query->whereIn('color', request('color'));
            }
            if (request('supplier') != null) {
                $query->whereIn('supplier', request('supplier'));
            }
            if (request('location') != null) {
                $query->where('location','LIKE', request('location',0));
            }
            if (request('size') != null) {
                $query->where('size','LIKE', request('size'));
            }

            if (request('group') != null) {
            //  dd('hello');
                $query->orWhereHas('groups', function ($qu) use ($request) {
                    $qu->whereIn('quicksell_group_id',$request->group);
                    });
            }

            if (request('price') != null) {
                $price = (explode(",",$request->price));
                $from = $price[0];
                $to = $price[1];
                if($from == 0) {
                  $query->where(function($q) use($from , $to) {
                       $q->whereNull("price")->orWhereBetween('price',[ $from , $to ]);
                  });  
                }else{
                  $query->whereBetween('price',[ $from , $to ]);
                }
            }

            if(request('per_page') != null){
                $per_page = request('per_page');
            }else{
                $per_page = Setting::get('pagination');
            }

            $products = $query->where('quick_product',1)->where('is_pending',0);
            $totalProduct = $products->count();
            $products = $products->paginate($per_page);

        }else{
            $products = Product::where('is_pending',0)->latest();
            $totalProduct = $products->count();
            $products = $products->paginate(Setting::get('pagination'));
        }


        $brands_all = Brand::all();
        $categories_all = Category::all();
        $brands = [];
        $categories = [];

        foreach ($brands_all as $brand) {
            $brands[$brand->id] = $brand->name;
        }

        foreach ($categories_all as $category) {
            $categories[$category->id] = $category->title;
        }

        $category_selection = Category::attr(['name' => 'category','class' => 'form-control', 'id'  => 'category_selection'])
            ->renderAsDropdown();

        $selected_categories = $request->category ? $request->category : 1;

        $filter_categories_selection = Category::attr(['name' => 'category','class' => 'form-control', 'id' => 'filter_categories_selection'])
            ->selected($selected_categories)
            ->renderAsDropdown();

        $locations = \App\ProductLocation::pluck("name","name");
        $suppliers = Supplier::select(['id', 'supplier'])->get();

        $category_tree = [];
        $categories_array = [];

        foreach (Category::all() as $category) {
            if ($category->parent_id != 0) {
                $parent = $category->parent;
                if ($parent->parent_id != 0) {
                    $category_tree[$parent->parent_id][$parent->id][$category->id];
                } else {
                    $category_tree[$parent->id][$category->id] = $category->id;
                }
            }

            $categories_array[$category->id] = $category->parent_id;
        }

        $new_category_selection = Category::attr(['name' => 'category','class' => 'form-control', 'id' => 'product-category'])
            ->renderAsDropdown();
        $api_keys = ApiKey::select('number')->get();
        $customers = Customer::orderBy('name','asc')->get();

        return view('quicksell.index', [
            'products'  => $products,
            'brands'  => $brands,
            'categories'  => $categories,
            'category_selection'  => $category_selection,
            'brand'         => $brand,
            'category'      => $category,
            'location'      => $location ?? '',
            'suppliers'      => $suppliers,
            'filter_categories_selection'  => $filter_categories_selection,
            'locations'  => $locations,
            'category_tree'  => $category_tree,
            'categories_array'  => $categories_array,
            'new_category_selection'  => $new_category_selection,
            'api_keys' =>  $api_keys,
            'customers' => $customers,
            'totalProduct' => $totalProduct,
            'category_dropdown' => $category_dropdown
        ]);
    }

    public function groupUpdate(Request $request)
    {
      //dd($request);
      if($request->groups != null){
           ProductQuicksellGroup::where('product_id',$request->product_id)->delete();
           $product = new ProductQuicksellGroup();
           $product->product_id = $request->product_id;
           $product->quicksell_group_id = $request->groups;
           $product->save();

           $group = QuickSellGroup::findorfail($request->groups);
           $group->suppliers = json_encode($request->suppliers);
           $group->brands = json_encode($request->brands);
           $group->price = $request->buying_price;
           $group->special_price = $request->special_price;
           $group->categories =  json_encode($request->categories);
           $group->update();


      }else{
         // dd($request);
            $group = QuickSellGroup::orderBy('id', 'desc')->first();
                if ($group != null) {
                    $group_create =  new QuickSellGroup();
                    $incrementId = ($group->group+1);
                    $group_create->group = $incrementId;
                    $group_create->name = $request->group_id;
                    $group_create->suppliers = json_encode($request->suppliers);
                    $group_create->brands = json_encode($request->brands);
                    $group_create->price = $request->buying_price;
                    $group_create->special_price = $request->special_price;
                    $group_create->categories =  json_encode($request->categories);
                    $group_create->save();
                    $group_id = $group_create->group;
                } else {
                   $group =  new QuickSellGroup();
                   $group->group = 1;
                   $group->save();
                   $group_id = $group->group;
                }
                if($group_id != null && $group_id != 0){
                $product = new ProductQuicksellGroup();
                $product->product_id = $request->product_id;
                $product->quicksell_group_id = $group_id;
                $product->save();
              }

      }

      return redirect()->back()->with('success', 'Group Got Updated');



    }


    public function quickSellGroupProductsList(Request $request){
      $current_group=array();
     $productArray=array();
      $list=QuickSellGroup::query();
      $list->with('getProductsIds');
      $list->whereHas('getProductsIds');
      if($request->group_id){
        $list->where('group',$request->group_id);
      } 
      $productsList=$list->orderBy('id','desc')->first();
      if($productsList){
      $current_group=array(
        'group_id'=>$productsList->group,
        'name'=>$productsList->name,
      );
    }
      $product_list=array();
      if($productsList && $productsList->getProductsIds){
        foreach($productsList->getProductsIds as $pl){
          $product_list[]=$pl->product_id;
        }
      }
      
      $products = Product::where('quick_product', 1)
          ->leftJoin("brands as b", "b.id", "products.brand")
          ->leftJoin("categories as c", "c.id", "products.category")
          ->select([
              "products.id",
              "products.name as product_name",
              "b.name as brand_name",
              "c.title as category_name",
              "products.supplier",
              "products.status_id",
              "products.created_at",
              "products.supplier_link",
              "products.composition",
              "products.size",
              "products.lmeasurement",
              "products.hmeasurement",
              "products.dmeasurement",
              "products.color",
          ]);
      
      $products->whereIn('products.id',$product_list);

      if ($request->category != null && $request->category != 1) {
          $products = $products->where("products.category", $request->category);
      }

      if ($request->brand_id != null) {
          $products = $products->where("products.brand", $request->brand_id);
      }

      if ($request->supplier_id != null) {
          $products = $products->where("products.supplier", $request->supplier_id);
      }

      if ($request->status_id != null) {
          $products = $products->where("products.status_id", $request->status_id);
      }

      $products = $products->orderby("products.created_at", "desc")->paginate()->appends(request()->except(['page']));

      return view('quicksell.quick-sell-list', compact('products','current_group'));      
   }

   public function quickSellGroupProductDelete(Request $request){
      $group_id=$request->group_id;
      $product_id=$request->product_id;
      $delete=ProductQuicksellGroup::where('quicksell_group_id',$group_id)->where('product_id',$product_id)->delete();
      if($delete){
        return response()->json([
          'status' => 1,
          'message' => 'Products deleted from group successfully!'
        ]);
      }else{
        return response()->json([
          'status' => 0,
          'message' => 'Invalid group id or product id'
        ]);    
      }
   }  

}

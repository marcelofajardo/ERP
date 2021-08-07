<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Brand;
use App\Sale;
use App\Setting;
use App\Stage;
use Cache;
use Auth;
use App\ColorReference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function __construct()
    {
        //$this->middleware( 'permission:product-list' );
    }

    public function search(Stage $stage, Request $request)
    {
        $data = [];
        $term = $request->input('term');
        $roletype = $request->input('roletype');
        $model_type = $request->input('model_type');
        $data[ 'customer_id' ] = $request->input('customer_id');

        $data[ 'term' ] = $term;
        $data[ 'roletype' ] = $roletype;
        $perPageLimit = $request->get("per_page");
        if (empty($perPageLimit)) {
            $perPageLimit = Setting::get('pagination');
        }
        $sourceOfSearch = $request->get("source_of_search", "na");

        // start add fixing for the price range since the one request from price is in range
        // price  = 0 , 100

        $priceRange = $request->get("price", null);

        if ($priceRange && !empty($priceRange)) {
            @list($minPrice, $maxPrice) = explode(",", $priceRange);
            // adding min price
            if (isset($minPrice)) {
                $request->request->add(['price_min' => (float)$minPrice]);
            }
            // addin max price
            if (isset($maxPrice)) {
                $request->request->add(['price_max' => (float)$maxPrice]);
            }
        }


        $doSelection = $request->input('doSelection');

        if (!empty($doSelection)) {

            $data[ 'doSelection' ] = true;
            $data[ 'model_id' ] = $request->input('model_id');
            $data[ 'model_type' ] = $request->input('model_type');

            $data[ 'selected_products' ] = ProductController::getSelectedProducts($data[ 'model_type' ], $data[ 'model_id' ]);
        }

        $products = (new Product())->newQuery()->latest();

        Cache::forget('filter-brand-' . Auth::id());
        if ($request->brand[ 0 ] != null) {
            $products = $products->whereIn('brand', $request->brand);
            $data[ 'brand' ] = $request->brand[ 0 ];
            Cache::put('filter-brand-' . Auth::id(), $data[ 'brand' ], 120);
        }

        Cache::forget('filter-color-' . Auth::id());
        if ($request->color[ 0 ] != null) {
            $products = $products->whereIn('color', $request->color);
            $data[ 'color' ] = $request->color[ 0 ];
            Cache::put('filter-color-' . Auth::id(), $data[ 'color' ], 120);
        }

        Cache::forget('filter-category-' . Auth::id());
        if ($request->category[ 0 ] != null && $request->category[ 0 ] != 1) {
            $category_children = [];

            foreach ($request->category as $category) {
                $is_parent = Category::isParent($category);

                if ($is_parent) {
                    $childs = Category::find($category)->childs()->get();

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
                    array_push($category_children, $category);
                }
            }

            $products = $products->whereIn('category', $category_children);
            $data[ 'category' ] = $request->category[ 0 ];
            Cache::put('filter-category-' . Auth::id(), $data[ 'category' ], 120);
        }

        Cache::forget('filter-price-min-' . Auth::id());
        if ($request->price_min != null) {
            $products = $products->where('price_inr_special', '>=', $request->price_min);
            Cache::put('filter-price-min-' . Auth::id(), $request->price_min, 120);
        }

        Cache::forget('filter-price-max-' . Auth::id());
        if ($request->price_max != null) {
            $products = $products->where('price_inr_special', '<=', $request->price_max);
            Cache::put('filter-price-max-' . Auth::id(), $request->price_max, 120);
        }

        Cache::forget('filter-supplier-' . Auth::id());
        if ($request->supplier[ 0 ] != null) {
            $suppliers_list = implode(',', $request->supplier);

            $products = $products->whereRaw("products.id in (SELECT product_id FROM product_suppliers WHERE supplier_id IN ($suppliers_list))");
            $data[ 'supplier' ] = $request->supplier;
            Cache::put('filter-supplier-' . Auth::id(), $data[ 'supplier' ], 120);
        }

        Cache::forget('filter-size-' . Auth::id());
        if (trim($request->size) != '') {
            $products = $products->whereNotNull('size')->where(function ($query) use ($request) {
                $query->where('size', $request->size)->orWhere('size', 'LIKE', "%$request->size,")->orWhere('size', 'LIKE', "%,$request->size,%");
            });
            $data[ 'size' ] = $request->size;
            Cache::put('filter-size-' . Auth::id(), $data[ 'size' ], 120);
        }

        if ($request->location[ 0 ] != null) {
            $products = $products->whereIn('location', $request->location);
            $data[ 'location' ] = $request->location[ 0 ];
        }

        if ($request->type[ 0 ] != null) {
            if (count($request->type) > 1) {
                $products = $products->where(function ($query) use ($request) {
                    $query->where('is_scraped', 1)->orWhere('status', 2);
                });
            } else {
                if ($request->type[ 0 ] == 'scraped') {
                    $products = $products->where('is_scraped', 1);
                } elseif ($request->type[ 0 ] == 'imported') {
                    $products = $products->where('status', 2);
                } else {
                    $products = $products->where('isUploaded', 1);
                }
            }
            $data[ 'type' ] = $request->type[ 0 ];
        }

        Cache::forget('filter-date-' . Auth::id());
        if ($request->date != '') {
            if (isset($products)) {
                if ($request->type[ 0 ] != null && $request->type[ 0 ] == 'uploaded') {
                    $products = $products->where('is_uploaded_date', 'LIKE', "%$request->date%");
                } else {
                    $products = $products->where('created_at', 'LIKE', "%$request->date%");
                }
            }
            $data[ 'date' ] = $request->date;
            Cache::put('filter-date-' . Auth::id(), $data[ 'date' ], 120);
        }

        if (trim($term) != '') {
            $products = $products->where(function ($query) use ($term) {
                $query->where('sku', 'LIKE', "%$term%")
                      ->orWhere('id', 'LIKE', "%$term%");

                if ($term == -1) {
                    $query = $query->orWhere('isApproved', -1);
                }

                $brand_id = \App\Brand::where('name', 'LIKE', "%$term%")->value('id');
                if ($brand_id) {
                    $query = $query->orWhere('brand', 'LIKE', "%$brand_id%");
                }

                $category_id = $category = Category::where('title', 'LIKE', "%$term%")->value('id');
                if ($category_id) {
                    $query = $query->orWhere('category', $category_id);
                }

            });

            if ($roletype != 'Selection' && $roletype != 'Searcher') {

                $products = $products->whereNull('dnf');
            }
        }

        if ($request->ids[ 0 ] != null) {
            $products = $products->whereIn('id', $request->ids);
        }


        $selected_categories = $request->category ? $request->category : 1;

        $data[ 'category_selection' ] = Category::attr(['name' => 'category[]', 'class' => 'form-control'])
            ->selected($selected_categories)
            ->renderAsDropdown();

        if ($request->quick_product === 'true') {
            $products = $products->where('quick_product', 1);
        }

        // assing product to varaible so can use as per condition for join table media
        if ($request->quick_product !== 'true') {
            $products = $products->whereRaw("(stock > 0 OR (supplier LIKE '%In-Stock%'))");
        }

        // if source is attach_media for search then check product has image exist or not
        if ($sourceOfSearch == "attach_media") {
            $products = $products->join("mediables", function ($query) {
                $query->on("mediables.mediable_id", "products.id")->where("mediable_type", "App\Product");
            })->groupBy('products.id');
        }

        if (!empty($request->quick_sell_groups) && is_array($request->quick_sell_groups)) {
            $products = $products->whereRaw("(id in (select product_id from product_quicksell_groups where quicksell_group_id in (".implode(",", $request->quick_sell_groups).") ))");
        }

        // select fields..
        $products = $products->select(['id', 'sku', 'size', 'price_inr_special', 'brand', 'supplier', 'purchase_status', 'isApproved', 'stage', 'status', 'is_scraped', 'created_at','category' , 'color']);

        $data[ 'is_on_sale' ] = 0;
        if ($request->get('is_on_sale') == 'on') {
            $data[ 'is_on_sale' ] = 1;
            $products = $products->where('is_on_sale', 1);
        }

        // start for the expoert file in excel
        $export = request()->get("export",null);
        if($export) {
            return \Excel::download(new \App\Exports\ProductExport($products->get()), 'export.xlsx');
        }

        $products_count = $products->get()->count();
        $data[ 'products_count' ] = $products_count;
        $data[ 'all_products_ids' ] = $products->pluck('id')->toArray();

        if($request->has("limit")) {
            $perPageLimit = ($request->get("limit") == "all") ? $products_count : $request->get("limit");
        }

        $data[ 'products' ] = $products->paginate($perPageLimit);

        if ($request->model_type == 'broadcast-images') {
            $data[ 'attachImages' ] = true;
            $data[ 'doSelection' ] = false;
        }

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

        if ($request->ajax()) {
            $html = view('partials.image-load', ['products' => $data[ 'products' ], 'data' => $data, 'selected_products' => ($request->selected_products ? json_decode($request->selected_products) : []), 'model_type' => $model_type])->render();

            return response()->json(['html' => $html, 'products_count' => $products_count, 'all_product_ids' => $data[ 'all_products_ids' ]]);
        }
        $data['categoryArray'] = $categoryArray;
        $data['sampleColors'] = $sampleColors;
        return view('partials.grid', $data);
    }


    public function getPendingProducts($roletype)
    {

        $stage = new Stage();
        $stage_no = intval($stage->getID($roletype));

        $products = Product::latest()
            ->where('stage', $stage_no - 1)
            ->where('isApproved', '!=', -1)
            ->whereNull('dnf')
            ->whereNull('deleted_at')
            ->paginate(Setting::get('pagination'));

        $category_selection = Category::attr(['name' => 'category[]', 'class' => 'form-control'])
            ->renderAsDropdown();

        return view('partials.grid', compact('products', 'roletype', 'category_selection'));
    }
}
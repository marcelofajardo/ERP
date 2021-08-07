<?php


namespace App\Http\Controllers;


use App\Category;
use App\ColorReference;
use App\CroppedImageReference;
use App\Http\Requests\Products\ProductTranslationRequest;
use App\Jobs\PushToMagento;
use App\ListingHistory;
use App\Order;
use App\ErpLeads;
use App\OrderProduct;
use App\Product;
use App\RejectedImages;
use App\ScrapedProducts;
use App\Sale;
use App\Setting;
use App\SiteCroppedImages;
use App\Sizes;
use App\Sop;
use App\Stage;
use App\Brand;
use App\TranslationLanguage;
use App\User;
use App\Language;
use App\ChatMessage;
use App\Supplier;
use App\PurchaseProductOrderLog;
use App\PurchaseProductOrder;

use App\Stock;
use App\Colors;
use App\ReadOnly\LocationList;
use App\UserProduct;
use App\UserProductFeedback;
use App\Helpers\QueryHelper;
use App\Helpers\StatusHelper;
use Cache;
use Auth;
use Carbon\Carbon;
use Chumper\Zipper\Zipper;
use Dompdf\Exception;
use FacebookAds\Object\ProductFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use App\SimplyDutyCategory;
use App\HsCodeGroup;
use App\HsCodeGroupsCategoriesComposition;
use App\HsCode;
use App\HsCodeSetting;
use App\SimplyDutyCountry;
use App\Product_translation;
use App\GoogleTranslate;
use seo2websites\GoogleVision\LogGoogleVision;
use App\Helpers\ProductHelper;
use App\Loggers\LogListMagento;
use App\StoreWebsite;
use App\Task;
use seo2websites\MagentoHelper\MagentoHelper;
use App\ProductTranslationHistory;
use App\Translations;
use App\ProductPushErrorLog;
use App\ProductStatusHistory;
use App\Status;
use App\ProductSupplier;
use Qoraiche\MailEclipse\MailEclipse;
use Illuminate\Support\Facades\Artisan;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
//      $this->middleware( 'permission:product-list', [ 'only' => [ 'show' ]]);
//      $this->middleware('permission:product-lister', ['only' => ['listing']]);
        $this->middleware('permission:product-lister', ['only' => ['listing']]);
//      $this->middleware('permission:product-create', ['only' => ['create','store']]);
//      $this->middleware('permission:product-edit', ['only' => ['edit','update']]);

//      $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->archived == 'true') {
            $products = Product::onlyTrashed()->latest()->select(['id', 'sku', 'name']);
        } else {
            $products = Product::latest()->select(['id', 'sku', 'name']);
        }
        $term = $request->term;
        $archived = $request->archived;

        if (!empty($term)) {
            $products = $products->where(function ($query) use ($term) {
                return $query
                    ->orWhere('id', 'like', '%' . $term . '%')
                    ->orWhere('name', 'like', '%' . $term . '%')
                    ->orWhere('sku', 'like', '%' . $term . '%');
            });
        }

        $products = $products->paginate(Setting::get('pagination'));
        $websiteList = \App\Helpers\ProductHelper::storeWebsite();

        return view('products.index', compact('products', 'term', 'archived', 'websiteList'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }


    public function approvedListing(Request $request,$pageType = "")
    {
        
        if(!Setting::has('auto_push_product')){
            $auto_push_product = Setting::add('auto_push_product',0,'int');
        }else{
            $auto_push_product = Setting::get('auto_push_product');
        }
       // dd(Setting::get('auto_push_product'));
        $cropped = $request->cropped;
        $colors = (new Colors)->all();
        $categories = Category::all();
        $category_tree = [];
        $categories_array = [];
        $brands = Brand::getAll();

        $suppliers = DB::select('
                SELECT id, supplier
                FROM suppliers

                INNER JOIN (
                    SELECT supplier_id FROM product_suppliers GROUP BY supplier_id
                    ) as product_suppliers
                ON suppliers.id = product_suppliers.supplier_id
        ');

        foreach (Category::all() as $category) {
            if ($category->parent_id != 0) {
                $parent = $category->parent;
                if ($parent->parent_id != 0) {
                    if (!isset($category_tree[$parent->parent_id])) {
                        $category_tree[$parent->parent_id] = [];
                    }
                    $category_tree[$parent->parent_id][$parent->id] = $category->id;
                } else {
                    $category_tree[$parent->id][$category->id] = $category->id;
                }
            }

            $categories_array[$category->id] = $category->parent_id;
        }

        if (auth()->user()->isReviwerLikeAdmin('final_listing')) {
            $newProducts = Product::query();
        } else {
            $newProducts = Product::query()->where('assigned_to', auth()->user()->id);
        }

        if ($request->get('status_id') != null) {
            $statusList = is_array($request->get('status_id')) ? $request->get('status_id') : [$request->get('status_id')];
            $newProducts = $newProducts->whereIn('status_id', $statusList);
        } else {
            if ($request->get('submit_for_approval') == "on") {
                $newProducts = $newProducts->where('status_id', StatusHelper::$submitForApproval);
            } else {
                $newProducts = $newProducts->where('status_id', StatusHelper::$finalApproval);
            }
        }


        // Run through query helper
        $newProducts = QueryHelper::approvedListingOrderFinalApproval($newProducts);
        $term = $request->input('term');
        $brand = '';
        $category = '';
        $color = '';
        $supplier = [];
        $type = '';
        $assigned_to_users = '';

        if (is_array($request->brand) && $request->brand[0] != null) {
            $newProducts = $newProducts->whereIn('brand', $request->get('brand'));
        }

        if (is_array($request->color) && $request->color[0] != null) {
            $newProducts = $newProducts->whereIn('color', $request->get('color'));
        }
        if (is_array($request->category) && $request->category[0] != null && $request->category[0] != 1) {
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

            $newProducts = $newProducts->whereIn('category', $category_children);
            $category = $request->category[0];
        }
        if ($request->type != '') {
            if ($request->type == 'Not Listed') {
                $newProducts = $newProducts->where('isFinal', 0)->where('isUploaded', 0);
            } else {
                if ($request->type == 'Listed') {
                    $newProducts = $newProducts->where('isUploaded', 1);
                } else {
                    if ($request->type == 'Approved') {
                        $newProducts = $newProducts->where('is_approved', 1);
                    } else {
                        if ($request->type == 'Image Cropped') {
                            $newProducts = $newProducts->where('is_image_processed', 1);
                        }
                    }
                }
            }

            $type = $request->get('type');
        }

        if($request->crop_status == "Not Matched"){
            $newProducts = $newProducts->whereDoesntHave('croppedImages');
        }
        if($request->crop_status == "Matched"){
            $newProducts = $newProducts->whereHas('croppedImages');
        }


        if (trim($term) != '') {

            $newProducts->where(function ($query) use ($term) {
                $query->where('short_description', 'LIKE', "%" . $term . "%")
                    ->orWhere('color', 'LIKE', "%" . $term . "%")
                    ->orWhere('name', 'LIKE', "%" . $term . "%")
                    ->orWhere('products.sku', 'LIKE', "%" . $term . "%")
                    ->orWhere('products.id', 'LIKE', "%" . $term . "%")
                    ->orWhereHas('brands', function($q) use($term){
                        $q->where('name', 'LIKE', "%" . $term . "%");
                })
                ->orWhereHas('product_category', function($q) use($term){
                    $q->where('title', 'LIKE', "%" . $term . "%");
                });
            });
        }

//        if(!empty($request->term)){
//            $newProducts = $newProducts->where(function ($q) use ($request) {
//                $q->where('color', 'LIKE', '%' . $request->term . '%')
//                    ->orWhere('short_description', 'LIKE', '%' . $request->term . '%')
//                    ->orWhere('category', 'LIKE', '%' . $request->term . '%')
//                    ->orWhere('brand', 'LIKE', '%' . $request->term . '%')
//                    ->orWhere('sku', 'LIKE', '%' . $request->term . '%') ;
//            });
//        }
//        if(!empty($request->color)){
//            $newProducts = $newProducts->where(function ($q) use ($request) {
//                $q->WhereIN('color', $request->color);
//            });
//        }
//        if(!empty($request->category)){
//            $newProducts = $newProducts->where(function ($q) use ($request) {
//                $q->WhereIN('category', $request->category);
//            });
//        }


        if ($request->get('user_id') > 0) {
            $newProducts = $newProducts->where('approved_by', $request->get('user_id'));
        }


        $selected_categories = $request->category ? $request->category : [1];
        $category_array = Category::renderAsArray();
        $users = User::all();

        $newProducts = $newProducts->leftJoin("product_verifying_users as pvu", function ($join) {
            $join->on("pvu.product_id", "products.id");
            $join->where("pvu.user_id", "!=", auth()->user()->id);
        });

        if($request->without_title != null) {
           $newProducts = $newProducts->where("products.name","");
        }

        if($request->without_size != null) {
           $newProducts = $newProducts->where("products.size","");
        }

        if($request->without_composition != null) {
           $newProducts = $newProducts->where("products.composition","");
        }

        if (!auth()->user()->isAdmin()) {
            $newProducts = $newProducts->whereNull("pvu.product_id");
        }
        $newProducts = $newProducts->where('isUploaded',0);

        $newProducts = $newProducts->select(["products.*"])->paginate(20);
        if (!auth()->user()->isAdmin()) {

            if (!$newProducts->isEmpty()) {
                $i = 1;
                foreach ($newProducts as $product) {
                    $productVerify = \App\ProductVerifyingUser::firstOrNew(array(
                        'product_id' => $product->id
                    ));
                    $productVerify->product_id = $product->id;
                    $productVerify->user_id = auth()->user()->id;
                    $productVerify->save();
                    $i++;
                    // if more then 15 records then break
                    if ($i > 25) {
                        break;
                    }
                }
            }
        }
//here
        if($request->ajax()) {

            // view path for images
            $viewpath = ($pageType == "images") ? 'products.final_listing_image_ajax' : 'products.final_listing_ajax';
            return view($viewpath, [
                'products' => $newProducts,
                'products_count' => $newProducts->total(),
                'colors' => $colors,
                'brands' => $brands,
                'suppliers' => $suppliers,
                'categories' => $categories,
                'category_tree' => $category_tree,
                'categories_array' => $categories_array,
                'term' => $term,
                'brand' => $brand,
                'category' => $category,
                'color' => $color,
                'supplier' => $supplier,
                'type' => $type,
                'users' => $users,
                'assigned_to_users' => $assigned_to_users,
                'cropped' => $cropped,
                'category_array' => $category_array,
                'selected_categories' => $selected_categories,
                'store_websites' => StoreWebsite::all(),
                'type' => $pageType
            ]);
        }

        $viewpath = 'products.final_listing'; 

        return view($viewpath, [
            'products' => $newProducts,
            'products_count' => $newProducts->total(),
            'colors' => $colors,
            'brands' => $brands,
            'suppliers' => $suppliers,
            'categories' => $categories,
            'category_tree' => $category_tree,
            'categories_array' => $categories_array,
            // 'category_selection' => $category_selection,
            // 'category_search'    => $category_search,
            'term' => $term,
            'brand' => $brand,
            'category' => $category,
            'color' => $color,
            'supplier' => $supplier,
            'type' => $type,
            'users' => $users,
            'assigned_to_users' => $assigned_to_users,
            'cropped' => $cropped,
//            'left_for_users'  => $left_for_users,
            'category_array' => $category_array,
            'selected_categories' => $selected_categories,
            'store_websites' => StoreWebsite::all(),
            'pageType' => $pageType,
            'auto_push_product' => $auto_push_product
            //'store_website_count' => StoreWebsite::count(),
        ]);
    }

    public function getFinalApporvalImages(Request $request){
        
        $cropped = $request->cropped;
        $colors = (new Colors)->all();
        $categories = Category::all();
        $category_tree = [];
        $categories_array = [];
        $brands = Brand::getAll();

        $suppliers = DB::select('
                SELECT id, supplier
                FROM suppliers

                INNER JOIN (
                    SELECT supplier_id FROM product_suppliers GROUP BY supplier_id
                    ) as product_suppliers
                ON suppliers.id = product_suppliers.supplier_id
        ');

        foreach (Category::all() as $category) {
            if ($category->parent_id != 0) {
                $parent = $category->parent;
                if ($parent->parent_id != 0) {
                    if (!isset($category_tree[$parent->parent_id])) {
                        $category_tree[$parent->parent_id] = [];
                    }
                    $category_tree[$parent->parent_id][$parent->id] = $category->id;
                } else {
                    $category_tree[$parent->id][$category->id] = $category->id;
                }
            }

            $categories_array[$category->id] = $category->parent_id;
        }

        // if ((int)$request->get('status_id') > 0) {
        //     $newProducts = Product::where('status_id', (int)$request->get('status_id'));
        // } else {
        //     if ($request->get('submit_for_approval') == "on") {
        //         $newProducts = Product::where('status_id', StatusHelper::$submitForApproval);
        //     }else{
        //         $newProducts = Product::where('status_id', StatusHelper::$finalApproval);
        //     }
        // }
        if (auth()->user()->isReviwerLikeAdmin('final_listing')) {
            $newProducts = Product::query();
        } else {
            $newProducts = Product::query()->where('assigned_to', auth()->user()->id);
        }

        if ($request->get('status_id') != null) {
            $statusList = is_array($request->get('status_id')) ? $request->get('status_id') : [$request->get('status_id')];
            $newProducts = $newProducts->whereIn('status_id', $statusList);
        } else {
            if ($request->get('submit_for_approval') == "on") {
                $newProducts = $newProducts->where('status_id', StatusHelper::$submitForApproval);
            } else {
                $newProducts = $newProducts->where('status_id', StatusHelper::$finalApproval);
            }
        }


        // Run through query helper
        $newProducts = QueryHelper::approvedListingOrder($newProducts);
        $term = $request->input('term');
        $brand = '';
        $category = '';
        $color = '';
        $supplier = [];
        $type = '';
        $assigned_to_users = '';

        if (is_array($request->brand) && $request->brand[0] != null) {
            $newProducts = $newProducts->whereIn('brand', $request->get('brand'));
        }

        if (is_array($request->color) && $request->color[0] != null) {
            $newProducts = $newProducts->whereIn('color', $request->get('color'));
        }
        if (is_array($request->category) && $request->category[0] != null && $request->category[0] != 1) {
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

            $newProducts = $newProducts->whereIn('category', $category_children);
            $category = $request->category[0];
        }
        if ($request->type != '') {
            if ($request->type == 'Not Listed') {
                $newProducts = $newProducts->where('isFinal', 0)->where('isUploaded', 0);
            } else {
                if ($request->type == 'Listed') {
                    $newProducts = $newProducts->where('isUploaded', 1);
                } else {
                    if ($request->type == 'Approved') {
                        $newProducts = $newProducts->where('is_approved', 1);
                    } else {
                        if ($request->type == 'Image Cropped') {
                            $newProducts = $newProducts->where('is_image_processed', 1);
                        }
                    }
                }
            }

            $type = $request->get('type');
        }

        if($request->crop_status == "Not Matched"){
            $newProducts = $newProducts->whereDoesntHave('croppedImages');
        }
        if($request->crop_status == "Matched"){
            $newProducts = $newProducts->whereHas('croppedImages');
        }


        if (trim($term) != '') {

            $newProducts->where(function ($query) use ($term) {
                $query->where('short_description', 'LIKE', "%" . $term . "%")
                    ->orWhere('color', 'LIKE', "%" . $term . "%")
                    ->orWhere('name', 'LIKE', "%" . $term . "%")
                    ->orWhere('products.sku', 'LIKE', "%" . $term . "%")
                    ->orWhere('products.id', 'LIKE', "%" . $term . "%")
                    ->orWhereHas('brands', function($q) use($term){
                        $q->where('name', 'LIKE', "%" . $term . "%");
                })
                ->orWhereHas('product_category', function($q) use($term){
                    $q->where('title', 'LIKE', "%" . $term . "%");
                });
            });
        }

//        if(!empty($request->term)){
//            $newProducts = $newProducts->where(function ($q) use ($request) {
//                $q->where('color', 'LIKE', '%' . $request->term . '%')
//                    ->orWhere('short_description', 'LIKE', '%' . $request->term . '%')
//                    ->orWhere('category', 'LIKE', '%' . $request->term . '%')
//                    ->orWhere('brand', 'LIKE', '%' . $request->term . '%')
//                    ->orWhere('sku', 'LIKE', '%' . $request->term . '%') ;
//            });
//        }
//        if(!empty($request->color)){
//            $newProducts = $newProducts->where(function ($q) use ($request) {
//                $q->WhereIN('color', $request->color);
//            });
//        }
//        if(!empty($request->category)){
//            $newProducts = $newProducts->where(function ($q) use ($request) {
//                $q->WhereIN('category', $request->category);
//            });
//        }


        if ($request->get('user_id') > 0) {
            $newProducts = $newProducts->where('approved_by', $request->get('user_id'));
        }


        $selected_categories = $request->category ? $request->category : [1];
        $category_array = Category::renderAsArray();
        $users = User::all();

        $newProducts = $newProducts->leftJoin("product_verifying_users as pvu", function ($join) {
            $join->on("pvu.product_id", "products.id");
            $join->where("pvu.user_id", "!=", auth()->user()->id);
        });

        if($request->without_title != null) {
           $newProducts = $newProducts->where("products.name","");
        }

        if($request->without_size != null) {
           $newProducts = $newProducts->where("products.size","");
        }

        if($request->without_composition != null) {
           $newProducts = $newProducts->where("products.composition","");
        }

        if (!auth()->user()->isAdmin()) {
            $newProducts = $newProducts->whereNull("pvu.product_id");
        }

        $newProducts = $newProducts->select(["products.*"])->paginate(20);
        if (!auth()->user()->isAdmin()) {

            if (!$newProducts->isEmpty()) {
                $i = 1;
                foreach ($newProducts as $product) {
                    $productVerify = \App\ProductVerifyingUser::firstOrNew(array(
                        'product_id' => $product->id
                    ));
                    $productVerify->product_id = $product->id;
                    $productVerify->user_id = auth()->user()->id;
                    $productVerify->save();
                    $i++;
                    // if more then 15 records then break
                    if ($i > 25) {
                        break;
                    }
                }
            }
        }
        //echo'<pre>'.print_r($cropped,true).'</pre>'; exit;
//here
        if($request->ajax()) {
            return view('products.final_listing_ajax', [
                'products' => $newProducts,
                'products_count' => $newProducts->total(),
                'colors' => $colors,
                'brands' => $brands,
                'suppliers' => $suppliers,
                'categories' => $categories,
                'category_tree' => $category_tree,
                'categories_array' => $categories_array,
                'term' => $term,
                'brand' => $brand,
                'category' => $category,
                'color' => $color,
                'supplier' => $supplier,
                'type' => $type,
                'users' => $users,
                'assigned_to_users' => $assigned_to_users,
                'cropped' => $cropped,
                'category_array' => $category_array,
                'selected_categories' => $selected_categories,
                'store_websites' => StoreWebsite::all(),
            ]);
        }

        return view('products.final_approval_images', [
            'products' => $newProducts,
            'products_count' => $newProducts->total(),
            'colors' => $colors,
            'brands' => $brands,
            'suppliers' => $suppliers,
            'categories' => $categories,
            'category_tree' => $category_tree,
            'categories_array' => $categories_array,
            // 'category_selection' => $category_selection,
            // 'category_search'    => $category_search,
            'term' => $term,
            'brand' => $brand,
            'category' => $category,
            'color' => $color,
            'supplier' => $supplier,
            'type' => $type,
            'users' => $users,
            'assigned_to_users' => $assigned_to_users,
            'cropped' => $cropped,
//            'left_for_users'  => $left_for_users,
            'category_array' => $category_array,
            'selected_categories' => $selected_categories,
            'store_websites' => StoreWebsite::all(),
            //'store_website_count' => StoreWebsite::count(),
        ]);

    }   

    public function approvedListingCropConfirmation(Request $request)
    {
        $colors = (new Colors)->all();
        $categories = Category::all();
        $category_tree = [];
        $categories_array = [];
        $brands = Brand::getAll();

        $suppliers = DB::select('
                SELECT id, supplier
                FROM suppliers

                INNER JOIN (
                    SELECT supplier_id FROM product_suppliers GROUP BY supplier_id
                    ) as product_suppliers
                ON suppliers.id = product_suppliers.supplier_id
        ');

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

        // Prioritize suppliers
        $newProducts = Product::where('status_id', StatusHelper::$cropApprovalConfirmation);

        $newProducts = QueryHelper::approvedListingOrder($newProducts);

        $term = $request->input('term');
        $brand = '';
        $category = '';
        $color = '';
        $supplier = [];
        $type = '';
        $assigned_to_users = '';

        if ($request->brand[0] != null) {
            $newProducts = $newProducts->whereIn('brand', $request->get('brand'));
        }

        if ($request->color[0] != null) {
            $newProducts = $newProducts->whereIn('color', $request->get('color'));
        }
        if ($request->category[0] != null && $request->category[0] != 1) {
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

            $newProducts = $newProducts->whereIn('category', $category_children);
            $category = $request->category[0];
        }
        if ($request->type != '') {
            if ($request->type == 'Not Listed') {
                $newProducts = $newProducts->where('isFinal', 0)->where('isUploaded', 0);
            } else {
                if ($request->type == 'Listed') {
                    $newProducts = $newProducts->where('isUploaded', 1);
                } else {
                    if ($request->type == 'Approved') {
                        $newProducts = $newProducts->where('is_approved', 1);
                    } else {
                        if ($request->type == 'Image Cropped') {
                            $newProducts = $newProducts->where('is_image_processed', 1);
                        }
                    }
                }
            }

            $type = $request->get('type');
        }
        //
        if (trim($term) != '') {
            $newProducts = $newProducts->where(function ($query) use ($term) {
                $query->where('id', 'LIKE', "%$term%")->orWhere('sku', 'LIKE', "%$term%");
            });
        }


        if ($request->get('user_id') > 0) {
            $newProducts = $newProducts->where('approved_by', $request->get('user_id'));
        }

        $selected_categories = $request->category ? $request->category : [1];
        $category_array = Category::renderAsArray();
        $users = User::all();

        $newProducts = QueryHelper::approvedListingOrder($newProducts);

        $newProducts = $newProducts->with(['media', 'brands', 'log_scraper_vs_ai'])->paginate(50);

        return view('products.final_crop_confirmation', [
            'products' => $newProducts,
            'products_count' => $newProducts->total(),
            'colors' => $colors,
            'brands' => $brands,
            'suppliers' => $suppliers,
            'categories' => $categories,
            'category_tree' => $category_tree,
            'categories_array' => $categories_array,
            // 'category_selection' => $category_selection,
            // 'category_search'    => $category_search,
            'term' => $term,
            'brand' => $brand,
            'category' => $category,
            'color' => $color,
            'supplier' => $supplier,
            'type' => $type,
            'users' => $users,
            'assigned_to_users' => $assigned_to_users,
//            'cropped' => $cropped,
//            'left_for_users'  => $left_for_users,
            'category_array' => $category_array,
            'selected_categories' => $selected_categories,
        ]);
    }

    public function approvedMagento(Request $request)
    {
        // Get queue count
        $queueSize = Queue::size('listMagento');

        $colors = (new Colors)->all();
        $categories = Category::all();
        $category_tree = [];
        $categories_array = [];
        $brands = Brand::getAll();

        $suppliers = DB::select('
                SELECT id, supplier
                FROM suppliers

                INNER JOIN (
                    SELECT supplier_id FROM product_suppliers GROUP BY supplier_id
                    ) as product_suppliers
                ON suppliers.id = product_suppliers.supplier_id
        ');

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

        $newProducts = Product::where('isUploaded', 1)->orderBy('listing_approved_at', 'DESC');

        $term = $request->input('term');
        $brand = '';
        $category = '';
        $color = '';
        $supplier = [];
        $type = '';
        $assigned_to_users = '';

        if ($request->brand[0] != null) {
            $newProducts = $newProducts->whereIn('brand', $request->get('brand'));
        }

        if ($request->color[0] != null) {
            $newProducts = $newProducts->whereIn('color', $request->get('color'));
        }
        if ($request->category[0] != null && $request->category[0] != 1) {
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

            $newProducts = $newProducts->whereIn('category', $category_children);
            $category = $request->category[0];
        }
        if ($request->type != '') {
            if ($request->type == 'Not Listed') {
                $newProducts = $newProducts->where('isFinal', 0)->where('isUploaded', 0);
            } else {
                if ($request->type == 'Listed') {
                    $newProducts = $newProducts->where('isUploaded', 1);
                } else {
                    if ($request->type == 'Approved') {
                        $newProducts = $newProducts->where('is_approved', 1);
                    } else {
                        if ($request->type == 'Image Cropped') {
                            $newProducts = $newProducts->where('is_image_processed', 1);
                        }
                    }
                }
            }

            $type = $request->get('type');
        }
        //
        if (trim($term) != '') {
            $newProducts = $newProducts->where(function ($query) use ($term) {
                $query->where('id', 'LIKE', "%$term%")->orWhere('sku', 'LIKE', "%$term%");
            });
        }


        if ($request->get('user_id') > 0) {
            $newProducts = $newProducts->where('approved_by', $request->get('user_id'));
        }


        $selected_categories = $request->category ? $request->category : [1];
        $category_array = Category::renderAsArray();
        $users = User::all();

        $newProducts = $newProducts->with(['media', 'brands'])->paginate(50);


        return view('products.in_magento', [
            'products' => $newProducts,
            'products_count' => $newProducts->total(),
            'colors' => $colors,
            'brands' => $brands,
            'suppliers' => $suppliers,
            'categories' => $categories,
            'category_tree' => $category_tree,
            'categories_array' => $categories_array,
            // 'category_selection' => $category_selection,
            // 'category_search'    => $category_search,
            'term' => $term,
            'brand' => $brand,
            'category' => $category,
            'color' => $color,
            'supplier' => $supplier,
            'type' => $type,
            'users' => $users,
            'assigned_to_users' => $assigned_to_users,
//            'cropped' => $cropped,
//            'left_for_users'  => $left_for_users,
            'category_array' => $category_array,
            'selected_categories' => $selected_categories,
            'queueSize' => $queueSize
        ]);
    }

    public function showListigByUsers(Request $request)
    {
        $whereFirst = '';
        if ($request->get('date')) {
            $whereFirst = ' AND DATE(created_at) = "' . $request->get('date') . '"';
        }
        $users = UserProduct::groupBy(['user_id'])
            ->select(DB::raw('
            user_id,
            COUNT(product_id) as total_assigned,
            (SELECT COUNT(DISTINCT(listing_histories.product_id)) FROM listing_histories WHERE listing_histories.user_id = user_products.user_id AND action IN ("LISTING_APPROVAL", "LISTING_REJECTED") ' . $whereFirst . ') as total_acted'));

        if ($request->get('date')) {
            $users = $users->whereRaw('DATE(created_at) = "' . $request->get('date') . '"');
        }

        $users = $users->with('user')->get();
        return view('products.assigned_products', compact('users'));

    }


    public function listing(Request $request, Stage $stage)
    {
        $colors = (new Colors)->all();
        $categories = Category::all();
        $category_tree = [];
        $categories_array = [];
        $brands = Brand::getAll();

        $suppliers = DB::select('
                SELECT id, supplier
                FROM suppliers

                INNER JOIN (
                    SELECT supplier_id FROM product_suppliers GROUP BY supplier_id
                    ) as product_suppliers
                ON suppliers.id = product_suppliers.supplier_id
        ');

        // dd($suppliers);

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


        $term = $request->input('term');
        $brand = '';
        $category = '';
        $color = '';
        $supplier = [];
        $type = '';
        $assigned_to_users = '';

        $brandWhereClause = '';
        $colorWhereClause = '';
        $categoryWhereClause = '';
        $supplierWhereClause = '';
        $typeWhereClause = '';
        $termWhereClause = '';
        $croppedWhereClause = '';
        $stockWhereClause = ' AND stock >= 1';

        $userWhereClause = '';

        // if (Auth::user()->hasRole('Products Lister')) {
        //  $products = Auth::user()->products();
        // } else {
        //  $products = (new Product)->newQuery();
        // }


        if (is_array($request->brand) && $request->brand[0] != null) {
            // $products = $products->whereIn('brand', $request->brand);
            $brands_list = implode(',', $request->brand);

            $brand = $request->brand[0];
            $brandWhereClause = " AND brand IN ($brands_list)";
        }

        if (is_array($request->color) && $request->color[0] != null) {
            // $products = $products->whereIn('color', $request->color);
            $colors_list = implode(',', $request->color);

            $color = $request->color[0];
            $colorWhereClause = " AND color IN ($colors_list)";
        }
        //
        if (is_array($request->category) && $request->category[0] != null && $request->category[0] != 1) {
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

            // $products = $products->whereIn('category', $category_children);
            $category_list = implode(',', $category_children);

            $category = $request->category[0];
            $categoryWhereClause = " AND category IN ($category_list)";
        }
        //
        if (is_array($request->supplier) && $request->supplier[0] != null) {
            $suppliers_list = implode(',', $request->supplier);

            // $products = $products->with('Suppliers')
            // ->whereRaw("products.id IN (SELECT product_id FROM product_suppliers WHERE supplier_id IN ($suppliers_list))");

            $supplier = $request->supplier;
            $supplierWhereClause = " AND products.id IN (SELECT product_id FROM product_suppliers WHERE supplier_id IN ($suppliers_list))";
        }
        //
        if ($request->type != '') {
            if ($request->type == 'Not Listed') {
                // $products = $products->newQuery()->where('isFinal', 0)->where('isUploaded', 0);
                $typeWhereClause = ' AND isFinal = 0 AND isUploaded = 0';
            } else {
                if ($request->type == 'Listed') {
                    // $products = $products->where('isUploaded', 1);
                    $typeWhereClause = ' AND isUploaded = 1';
                } else {
                    if ($request->type == 'Approved') {
                        // $products = $products->where('is_approved', 1)->whereNull('last_imagecropper');
                        $typeWhereClause = ' AND is_approved = 1 AND last_imagecropper IS NULL';
                    } else {
                        if ($request->type == 'Image Cropped') {
                            // $products = $products->where('is_approved', 1)->whereNotNull('last_imagecropper');
                            $typeWhereClause = ' AND is_approved = 1 AND last_imagecropper IS NOT NULL';
                        }
                    }
                }
            }

            $type = $request->type;
        }
        //
        if (trim($term) != '') {
            // $products = $products
            // ->orWhere( 'sku', 'LIKE', "%$term%" )
            // ->orWhere( 'id', 'LIKE', "%$term%" )//                                        ->orWhere( 'category', $term )
            // ;

            $termWhereClause = ' AND (sku LIKE "%' . $term . '%" OR id LIKE "%' . $term . '%")';

            // if ($term == - 1) {
            //  $products = $products->orWhere( 'isApproved', - 1 );
            // }

            // if ( Brand::where('name', 'LIKE' ,"%$term%")->first() ) {
            //  $brand_id = Brand::where('name', 'LIKE' ,"%$term%")->first()->id;
            //  $products = $products->orWhere( 'brand', 'LIKE', "%$brand_id%" );
            // }
            //
            // if ( $category = Category::where('title', 'LIKE' ,"%$term%")->first() ) {
            //  $category_id = $category = Category::where('title', 'LIKE' ,"%$term%")->first()->id;
            //  $products = $products->orWhere( 'category', CategoryController::getCategoryIdByName( $term ) );
            // }
            //
            // if (!empty( $stage->getIDCaseInsensitive( $term ) ) ) {
            //  $products = $products->orWhere( 'stage', $stage->getIDCaseInsensitive( $term ) );
            // }
        }
        //  else {
        //  if ($request->brand[0] == null && $request->color[0] == null && ($request->category[0] == null || $request->category[0] == 1) && $request->supplier[0] == null && $request->type == '') {
        //      $products = $products;
        //  }
        // }


        // $products = $products->where('is_scraped', 1)->where('stock', '>=', 1);
        $cropped = $request->cropped == "on" ? "on" : '';
        if ($request->get('cropped') == 'on') {
            // $products = $products->where('is_image_processed', 1);
            $croppedWhereClause = ' AND is_crop_approved = 1';
        }

        if ($request->users == 'on') {
            $users_products = User::role('Products Lister')->pluck('id');
            // dd($users_products);
            $users = [];
            foreach ($users_products as $user) {
                $users[] = $user;
            }
            $users_list = implode(',', $users);

            $userWhereClause = " AND products.id IN (SELECT product_id FROM user_products WHERE user_id IN ($users_list))";
            $stockWhereClause = '';
            $assigned_to_users = 'on';
        }

        $left_for_users = '';
        if ($request->left_products == 'on') {
            // $users_products = User::role('Products Lister')->pluck('id');
            //
            // $users_list = implode(',', $users_products);

            $userWhereClause = " AND products.id NOT IN (SELECT product_id FROM user_products)";
            $stockWhereClause = " AND stock >= 1 AND is_crop_approved = 1 AND is_crop_ordered = 1 AND is_image_processed = 1 AND isUploaded = 0 AND isFinal = 0";
            $left_for_users = 'on';
        }

        // if (Auth::user()->hasRole('Products Lister')) {
        //  // dd('as');
        //  $products_count = Auth::user()->products;
        //  $products = Auth::user()->products()->get()->toArray();

        // $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // $perPage = Setting::get('pagination');
        // $currentItems = array_slice($products, $perPage * ($currentPage - 1), $perPage);
        //
        // $products = new LengthAwarePaginator($currentItems, count($products), $perPage, $currentPage, [
        //   'path'  => LengthAwarePaginator::resolveCurrentPath()
        // ]);

        // dd($products);
        // } else {
        // $products_count = $products->take(5000)->get();
        // $products = $products->take(5000)->orderBy('is_image_processed', 'DESC')->orderBy('created_at', 'DESC')->get()->toArray();

        $messages = UserProductFeedback::where('action', 'LISTING_APPROVAL_REJECTED')->where('user_id', Auth::id())->with('product')->get();

        if (Auth::user()->hasRole('Products Lister')) {
            $sql = '
                                            SELECT *, user_products.user_id as product_user_id,
                                            (SELECT mm1.created_at FROM remarks mm1 WHERE mm1.id = remark_id) AS remark_created_at
                                            FROM products

                                            LEFT JOIN (
                                                SELECT user_id, product_id FROM user_products
                                                ) as user_products
                                            ON products.id = user_products.product_id

                                            LEFT JOIN (
                                                SELECT MAX(id) AS remark_id, taskid FROM remarks WHERE module_type = "productlistings" GROUP BY taskid
                                                ) AS remarks
                                            ON products.id = remarks.taskid

                                            WHERE stock>=1 AND is_approved = 0 AND is_listing_rejected = 0 AND is_crop_approved = 1 AND is_crop_ordered = 1 ' . $brandWhereClause . $colorWhereClause . $categoryWhereClause . $supplierWhereClause . $typeWhereClause . $termWhereClause . $croppedWhereClause . $stockWhereClause . ' AND id IN (SELECT product_id FROM user_products WHERE user_id = ' . Auth::id() . ')
                                             AND id NOT IN (SELECT product_id FROM product_suppliers WHERE supplier_id = 60)
                                            ORDER BY listing_approved_at DESC, category, is_crop_ordered DESC, remark_created_at DESC, created_at DESC
                ';
        } else {
            $sql = '
                SELECT *, user_products.user_id as product_user_id,
                (SELECT mm1.created_at FROM remarks mm1 WHERE mm1.id = remark_id) AS remark_created_at
                FROM products

                LEFT JOIN (
                    SELECT user_id, product_id FROM user_products
                    ) as user_products
                ON products.id = user_products.product_id

                LEFT JOIN (
                    SELECT MAX(id) AS remark_id, taskid FROM remarks WHERE module_type = "productlistings" GROUP BY taskid
                    ) AS remarks
                ON products.id = remarks.taskid
                WHERE stock>=1 AND is_approved = 0 AND is_listing_rejected = 0  AND is_crop_approved = 1 AND is_crop_ordered = 1  ' . $stockWhereClause . $brandWhereClause . $colorWhereClause . $categoryWhereClause . $supplierWhereClause . $typeWhereClause . $termWhereClause . $croppedWhereClause . $userWhereClause . '
                ORDER BY listing_approved_at DESC, category, is_crop_ordered DESC, remark_created_at DESC, products.updated_at DESC
                ';
        }
        $new_products = DB::select($sql);

//          dd($new_products);
        $products_count = count($new_products);
        //
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = Setting::get('pagination');
        $currentItems = array_slice($new_products, $perPage * ($currentPage - 1), $perPage);

        $new_products = new LengthAwarePaginator($currentItems, count($new_products), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);
        // }
        // dd($products);

        $selected_categories = $request->category ? $request->category : [1];
        // $category_search = Category::attr(['name' => 'category[]','class' => 'form-control'])
        //                                         ->selected($selected_categories)
        //                                         ->renderAsDropdown();

        $category_array = Category::renderAsArray();

        $userStats = [];
        $userStats['approved'] = ListingHistory::where('action', 'LISTING_APPROVAL')->where('user_id', Auth::user()->id)->count();
        $userStats['rejected'] = ListingHistory::where('action', 'LISTING_REJECTED')->where('user_id', Auth::user()->id)->count();

        // dd($category_array);

        return view('products.listing', [
            'products' => $new_products,
            'products_count' => $products_count,
            'colors' => $colors,
            'brands' => $brands,
            'suppliers' => $suppliers,
            'categories' => $categories,
            'category_tree' => $category_tree,
            'categories_array' => $categories_array,
            'term' => $term,
            'brand' => $brand,
            'category' => $category,
            'color' => $color,
            'supplier' => $supplier,
            'type' => $type,
            'assigned_to_users' => $assigned_to_users,
            'cropped' => $cropped,
            'left_for_users' => $left_for_users,
            'category_array' => $category_array,
            'selected_categories' => $selected_categories,
            'messages' => $messages,
            'userStatus' => $userStats
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Product $product
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, Sizes $sizes)
    {
        $data = [];

        $data['dnf'] = $product->dnf;
        $data['id'] = $product->id;
        $data['name'] = $product->name;
        $data['short_description'] = $product->short_description;
        $data['activities'] = $product->activities;
        $data['scraped'] = $product->scraped_products;

        $data['measurement_size_type'] = $product->measurement_size_type;
        $data['lmeasurement'] = $product->lmeasurement;
        $data['hmeasurement'] = $product->hmeasurement;
        $data['dmeasurement'] = $product->dmeasurement;

        $data['size'] = $product->size;
        $data['size_value'] = $product->size_value;
        $data['sizes_array'] = $sizes->all();

        $data['composition'] = $product->composition;
        $data['sku'] = $product->sku;
        $data['made_in'] = $product->made_in;
        $data['brand'] = $product->brand;
        $data['color'] = $product->color;
        $data['price'] = $product->price;
        $data['status'] = $product->status_id;
//      $data['price'] = $product->inr;
        $data['euro_to_inr'] = $product->euro_to_inr;
        $data['price_inr'] = $product->price_inr;
        $data['price_inr_special'] = $product->price_inr_special;

        $data['isApproved'] = $product->isApproved;
        $data['rejected_note'] = $product->rejected_note;
        $data['isUploaded'] = $product->isUploaded;
        $data['isFinal'] = $product->isFinal;
        $data['stock'] = $product->stock;
        $data['reason'] = $product->rejected_note;

        $data['product_link'] = $product->product_link;
        $data['supplier'] = $product->supplier;
        $data['supplier_link'] = $product->supplier_link;
        $data['description_link'] = $product->description_link;
        $data['location'] = $product->location;

        $data['suppliers'] = '';
        $data['more_suppliers'] = [];

        foreach ($product->suppliers as $key => $supplier) {
            if ($key == 0) {
                $data['suppliers'] .= $supplier->supplier;
            } else {
                $data['suppliers'] .= ", $supplier->supplier";
            }
        }

        /*foreach ($product->suppliers_info as $key => $pr) {
            if($pr->stock > 0) {
                $data[ 'more_suppliers' ][] = [
                    "name" => $pr->supplier->supplier,
                    "link" => $pr->supplier_link
                ] ;
            }
        }*/

        $data['more_suppliers'] = DB::select('SELECT sp.url as link,s.supplier as name
                            FROM `scraped_products` sp
                            JOIN scrapers sc on sc.scraper_name=sp.website
                            JOIN suppliers s ON s.id=sc.supplier_id
                            WHERE last_inventory_at > DATE_SUB(NOW(), INTERVAL sc.inventory_lifetime DAY) and sp.sku = :sku', ['sku' => $product->sku]);


        $data['images'] = $product->getMedia(config('constants.media_tags'));


        $data['categories'] = $product->category ? CategoryController::getCategoryTree($product->category) : '';

        $data['has_reference'] = ScrapedProducts::where('sku', $product->sku)->first() ? true : false;

        $data['product'] = $product;

        return view('partials.show', $data);
    }

    public function bulkUpdate(Request $request)
    {
        $selected_products = json_decode($request->selected_products, true);
        $category = $request->category[0];

        foreach ($selected_products as $id) {
            $product = Product::find($id);
            $product->category = $category;
            $product->save();

            $lh = new ListingHistory();
            $lh->user_id = Auth::user()->id;
            $lh->product_id = $id;
            $lh->content = ['Category updated', $category];
            $lh->save();

        }

        return redirect()->back()->withSuccess('You have successfully bulk updated products!');
    }

    public function updateName(Request $request, $id)
    {
        $product = Product::find($id);
        $product->name = $request->name;
        $product->save();

        $lh = new ListingHistory();
        $lh->user_id = Auth::user()->id;
        $lh->product_id = $id;
        $lh->content = ['Name updated', $request->get('name')];
        $lh->save();

        return response('success');
    }

    public function updateDescription(Request $request, $id)
    {
        $product = Product::find($id);
        $product->short_description = $request->description;
        $product->save();

        $lh = new ListingHistory();
        $lh->user_id = Auth::user()->id;
        $lh->product_id = $id;
        $lh->content = ['Description updated', $request->get('description')];
        $lh->save();

        return response('success');
    }

    public function updateComposition(Request $request, $id)
    {
        $product = Product::find($id);
        $product->composition = $request->composition;
        $product->save();

        $lh = new ListingHistory();
        $lh->user_id = Auth::user()->id;
        $lh->product_id = $id;
        $lh->content = ['Composition updated', $request->get('composition')];
        $lh->save();

        return response('success');
    }

    public function updateColor(Request $request, $id)
    {
        $product = Product::find($id);

        if ($product) {
            $productColHis = new \App\ProductColorHistory;
            $productColHis->user_id = \Auth::user()->id;
            $productColHis->color = $request->color;
            $productColHis->old_color = $product->color;
            $productColHis->product_id = $product->id;
            $productColHis->save();
        }

        $originalColor = $product->color;
        $product->color = $request->color;
        $product->save();


        \App\ProductStatus::pushRecord($product->id, "MANUAL_COLOR");

        $lh = new ListingHistory();
        $lh->user_id = Auth::user()->id;
        $lh->product_id = $id;
        $lh->content = ['Color updated', $request->get('color')];
        $lh->save();

        if (!$originalColor) {
            return response('success');
        }

        $color = (new Colors)->getID($originalColor);
        if ($color) {
            return response('success');
        }


        $colorReference = ColorReference::where('original_color', $originalColor)->first();
        if ($colorReference) {
            return response('success');
        }

        $colorReference = new ColorReference();
        $colorReference->original_color = $originalColor;
        $colorReference->brand_id = $product->brand;
        $colorReference->erp_color = $request->get('color');
        $colorReference->save();

        return response('success');

    }

    public function updateCategory(Request $request, $id)
    {

        $product = Product::find($id);

        if ($product) {
            $productCatHis = new \App\ProductCategoryHistory;
//            dd($productCatHis);

            $productCatHis->user_id = \Auth::user()->id;
            $productCatHis->category_id = $request->category;
            $productCatHis->old_category_id = $product->category;
            $productCatHis->product_id = $product->id;
            $productCatHis->save();

            \App\ProductStatus::pushRecord($product->id, "MANUAL_CATEGORY");
        }
//        dd($product);

        $product->category = $request->category;
        $product->save();
        $lh = new ListingHistory();
        $lh->user_id = Auth::user()->id;
        $lh->product_id = $id;
        $lh->content = ['Category updated', $request->get('category')];
        $lh->save();

        return response('success');
    }

    public function updateSize(Request $request, $id)
    {
        $product = Product::find($id);
        $product->size = is_array($request->size) && count($request->size) > 0 ? implode(',', $request->size) : '';
        $product->lmeasurement = $request->lmeasurement;
        $product->hmeasurement = $request->hmeasurement;
        $product->dmeasurement = $request->dmeasurement;
        $product->save();

        $lh = new ListingHistory();
        $lh->user_id = Auth::user()->id;
        $lh->product_id = $id;
        $lh->content = ['Sizes updated', $request->get('lmeasurement') . ' X ' . $request->get('hmeasurement') . ' X ' . $request->get('dmeasurement')];
        $lh->save();

        return response('success');
    }

    public function updatePrice(Request $request, $id)
    {
        $product = Product::find($id);
        $product->price = $request->price;

        if (!empty($product->brand)) {
            $product->price_inr = $this->euroToInr($product->price, $product->brand);
            $product->price_inr_special = $this->calculateSpecialDiscount($product->price_inr_special, $product->brand);
        }

        $product->save();

        $l = new ListingHistory();
        $l->user_id = Auth::user()->id;
        $l->product_id = $id;
        $l->content = ['Price updated', $product->price];

        return response()->json([
            'price_inr' => $product->price_inr,
            'price_inr_special' => $product->price_inr_special
        ]);
    }

    public function quickDownload($id)
    {
        $product = Product::find($id);

        $products_array = [];

        if ($product->hasMedia(config('constants.media_tags'))) {
            foreach ($product->getMedia(config('constants.media_tags')) as $image) {
                $path = public_path('uploads') . '/' . $image->filename . '.' . $image->extension;
                array_push($products_array, $path);
            }
        }


        \Zipper::make(public_path("$product->sku.zip"))->add($products_array)->close();

        return response()->download(public_path("$product->sku.zip"))->deleteFileAfterSend();
    }

    public function quickUpload(Request $request, $id)
    {
        $product = Product::find($id);
        $image_url = '';

        if ($request->hasFile('images')) {
            $product->detachMediaTags(config('constants.media_tags'));

            foreach ($request->file('images') as $key => $image) {
                $media = MediaUploader::fromSource($image)
                    ->toDirectory('product/' . floor($product->id / config('constants.image_per_folder')))
                    ->upload();
                $product->attachMedia($media, config('constants.media_tags'));

                if ($key == 0) {
                    $image_url = $media->getUrl();
                }
            }

            $product->last_imagecropper = Auth::id();
            $product->save();
        }

        return response()->json([
            'image_url' => $image_url,
            'last_imagecropper' => $product->last_imagecropper
        ]);
    }

    public function calculateSpecialDiscount($price, $brand)
    {
        $dis_per = BrandController::getDeductionPercentage($brand);
        $dis_price = $price - ($price * $dis_per) / 100;

        return round($dis_price, -3);
    }

    public function euroToInr($price, $brand)
    {
        $euro_to_inr = BrandController::getEuroToInr($brand);

        if (!empty($euro_to_inr)) {
            $inr = $euro_to_inr * $price;
        } else {
            $inr = Setting::get('euro_to_inr') * $price;
        }

        return round($inr, -3);
    }

    public function listMagento(Request $request, $id)
    {
        try {
            //code...
            $queueName = [
                "1" => "mageone",
                "2" => "magetwo",
                "3" => "magethree"
            ];
            // Get product by ID
            $product = Product::find($id);
            //check for hscode
            $hsCode = $product->hsCode($product->category, $product->composition);
            $hsCode = true;
            if ($hsCode) {
                // If we have a product, push it to Magento
                if ($product !== null) {
                    // Dispatch the job to the queue
                    //PushToMagento::dispatch($product)->onQueue('magento');
                    $category = $product->category;
                    $brand = $product->brand;
                    //website search
                    $websiteArrays = ProductHelper::getStoreWebsiteName($product->id);
                    if(count($websiteArrays) == 0){
                        \Log::info("Product started ".$product->id." No website found");
                        $msg = 'No website found for  Brand: '. $product->brand. ' and Category: '. $product->category;
                        $logId = LogListMagento::log($product->id, "Start push to magento for product id " . $product->id, 'info');
                        ProductPushErrorLog::log("",$product->id, $msg, 'error',$logId->store_website_id,"","",$logId->id);
                        $this->updateLogUserId($logId);
                    }else{
                        $i = 1;
                        foreach ($websiteArrays as $websiteArray) {
                            $website = StoreWebsite::find($websiteArray);
                            if($website){
                                \Log::info("Product started website found For website".$website->website);
                                LogListMagento::log($product->id, "Start push to magento for product id " . $product->id, 'info',$website->id);
                                //currently we have 3 queues assigned for this task.
                                if($i > 3) {
                                   $i = 1;
                                }
                                PushToMagento::dispatch($product,$website)->onQueue($queueName[$i]);
                                $i++;
                            }else{
                                $msg = 'Website not exist ';

                                $logId = LogListMagento::log($product->id, $msg, 'info');
                                ProductPushErrorLog::log("",$product->id, $msg, 'error',$logId->store_website_id,"","",$logId->id);
                                $this->updateLogUserId($logId);
                            }
                        }
                    }
                    // if (class_exists('\\seo2websites\\MagentoHelper\\MagentoHelper')) {
                    //     $result = MagentoHelper::uploadProduct($product);
                    //     if ( !$result ) {
                    //         // Log alert
                    //         \Log::channel('listMagento')->alert( "[Queued job result] Pushing product with ID " . $product->id . " to Magento failed" );
    
                    //         // Set product to isListed is 0
                    //         $product->isListed = 0;
                    //         $product->save();
                    //     } else {
                    //         // Log info
                    //         \Log::channel('listMagento')->info( "[Queued job result] Successfully pushed product with ID " . $product->id . " to Magento" );
                    //     }
                    // }
    
                    // Update the product so it doesn't show up in final listing
                    $product->isUploaded = 1;
                    $product->save();
                    // Return response
                    return response()->json([
                        'result' => 'queuedForDispatch',
                        'status' => 'listed'
                    ]);
                }
            }
            
            $msg = 'Hs Code not found of product id '.$id.'. Parameters where category_id: '. $product->category. ' and composition: '. $product->composition;

            $logId = LogListMagento::log($product->id, $msg, 'info');
            ProductPushErrorLog::log("",$product->id, $msg, 'error',$logId->store_website_id,"","",$logId->id);
            $this->updateLogUserId($logId);
            // Return error response by default
            return response()->json([
                'result' => 'productNotFound',
                'status' => 'error'
            ]);
        } catch(Exception $e) {
            //throw $th;
            $msg = $e->getMessage();

            $logId = LogListMagento::log($id, $msg, 'info');
            ProductPushErrorLog::log("",$id, $msg, 'error',$logId->store_website_id,"","",$logId->id);
            $this->updateLogUserId($logId);
            // Return error response by default
            return response()->json([
                'result' => 'productNotFound',
                'status' => 'error'
            ]);
        }
        
    }

    public function updateLogUserId($logId)
    {
        $updateLogUser = LogListMagento::find($logId->id);
        if($updateLogUser)
        {
            $updateLogUser->user_id = Auth::id();
            $updateLogUser->save();
        }
    }

    public function unlistMagento(Request $request, $id)
    {
        $product = Product::find($id);
        $magentoHelper = new MagentoHelper;
        $result = $magentoHelper->magentoUnlistProduct($product);
        // $result = app('App\Http\Controllers\ProductApproverController')->magentoSoapUnlistProduct($product);

        return response()->json([
            'result' => $result,
            'status' => 'unlisted'
        ]);
    }

    public function approveMagento(Request $request, $id)
    {
        $product = Product::find($id);
        $magentoHelper = new MagentoHelper;
        $result = $magentoHelper->magentoUpdateStatus($product);
        // $result = app('App\Http\Controllers\ProductApproverController')->magentoSoapUpdateStatus($product);

        return response()->json([
            'result' => $result,
            'status' => 'approved'
        ]);
    }

    public function updateMagento(Request $request, $id)
    {
        $product = Product::find($id);
        $magentoHelper = new MagentoHelper;
        $result = $magentoHelper->magentoProductUpdate($product);

        // $result = app('App\Http\Controllers\ProductAttributeController')->magentoProductUpdate($product);

        return response()->json([
            'result' => $result[1],
            'status' => 'updated'
        ]);
    }

    public function updateMagentoProduct(Request $request)
    {
        $product = Product::find($request->update_product_id);

        //////      Update Local Product    //////
        $product->name = $request->name;
        $product->price = $request->price;
        $product->price_eur_special = $request->price_eur_special;
        $product->price_eur_discounted = $request->price_eur_discounted;
        $product->price_inr = $request->price_inr;
        $product->price_inr_special = $request->price_inr_special;
        $product->price_inr_discounted = $request->price_inr_discounted;
        $product->measurement_size_type = $request->measurement_size_type;
        $product->lmeasurement = $request->lmeasurement;
        $product->hmeasurement = $request->hmeasurement;
        $product->dmeasurement = $request->dmeasurement;
        $product->composition = $request->composition;
        $product->size = $request->size;
        $product->short_description = $request->short_description;
        $product->made_in = $request->made_in;
        $product->brand = $request->brand;
        $product->category = $request->category;
        $product->supplier = $request->supplier;
        $product->supplier_link = $request->supplier_link;
        $product->product_link = $request->product_link;
        $product->updated_at = time();

        //echo "<pre>";print_r($request->all());exit;
        if ($product->update()) {
            if ($product->status_id == 12) {
                ///////     Update Magento Product  //////
                $options = array(
                    'trace' => true,
                    'connection_timeout' => 120,
                    'wsdl_cache' => WSDL_CACHE_NONE,
                );

                $proxy = new \SoapClient(config('magentoapi.url'), $options);
                $sessionId = $proxy->login(config('magentoapi.user'), config('magentoapi.password'));

                $sku = $product->sku . $product->color;
                try {
                    $magento_product = json_decode(json_encode($proxy->catalogProductInfo($sessionId, $sku)), true);
                    if ($magento_product) {
                        if (!empty($product->size)) {
                            $associated_skus = [];
                            $new_variations = 0;
                            $sizes_array = explode(',', $product->size);
                            $categories = CategoryController::getCategoryTreeMagentoIds($product->category);

                            //////      Add new Variations  //////
                            foreach ($sizes_array as $key2 => $size) {
                                $error_message = '';

                                try {
                                    $simple_product = json_decode(json_encode($proxy->catalogProductInfo($sessionId, $sku . '-' . $size)), true);
                                    //echo "<pre>";print_r($simple_product);
                                } catch (\Exception $e) {
                                    $error_message = $e->getMessage();
                                }

                                if ($error_message == 'Product not exists.') {
                                    // CREATE VARIATION
                                    $productData = array(
                                        'categories' => $categories,
                                        'name' => $product->name,
                                        'description' => '<p></p>',
                                        'short_description' => $product->short_description,
                                        'website_ids' => array(1),
                                        // Id or code of website
                                        'status' => $magento_product['status'],
                                        // 1 = Enabled, 2 = Disabled
                                        'visibility' => 1,
                                        // 1 = Not visible, 2 = Catalog, 3 = Search, 4 = Catalog/Search
                                        'tax_class_id' => 2,
                                        // Default VAT
                                        'weight' => 0,
                                        'stock_data' => array(
                                            'use_config_manage_stock' => 1,
                                            'manage_stock' => 1,
                                        ),
                                        'price' => $product->price_eur_special,
                                        // Same price than configurable product, no price change
                                        'special_price' => $product->price_eur_discounted,
                                        'additional_attributes' => array(
                                            'single_data' => array(
                                                array('key' => 'msrp', 'value' => $product->price,),
                                                array('key' => 'composition', 'value' => $product->composition,),
                                                array('key' => 'color', 'value' => $product->color,),
                                                array('key' => 'sizes', 'value' => $size,),
                                                array('key' => 'country_of_manufacture', 'value' => $product->made_in,),
                                                array('key' => 'brands', 'value' => BrandController::getBrandName($product->brand),),
                                            ),
                                        ),
                                    );
                                    // Creation of product simple
                                    $result = $proxy->catalogProductCreate($sessionId, 'simple', 14, $sku . '-' . $size, $productData);
                                    $new_variations = 1;


                                } else {
                                    // SIMPLE PRODUCT EXISTS
                                    $status = $simple_product['status'];
                                    // 1 = Enabled, 2 = Disabled

                                    if ($status == 2) {
                                        // $product->isFinal = 0;
                                    } else {
                                        // $product->isFinal = 1;
                                    }
                                }
                                $associated_skus[] = $sku . '-' . $size;
                            }

                            if ($new_variations == 1) {
                                // IF THERE WAS NEW VARIATION CREATED, UPDATED THE MAIN PRODUCT
                                /**
                                 * Configurable product
                                 */
                                $productData = array(
                                    'associated_skus' => $associated_skus,
                                );
                                // Creation of configurable product
                                $result = $proxy->catalogProductUpdate($sessionId, $sku, $productData);
                            }
                            $messages = "Product updated successfully";
                            return Redirect::Back()
                                ->with('success', $messages);
                        } else {
                            $messages[] = "Sorry! No sizes found for magento update";
                            return Redirect::Back()
                                ->withErrors($messages);
                        }
                    } else {
                        $messages[] = "Sorry! Product not found in magento";
                        return Redirect::Back()
                            ->withErrors($messages);
                    }
                } catch (\Exception $e) {
                    $messages[] = $e->getMessage();
                    return Redirect::Back()
                        ->withErrors($messages);
                }
            } else {
                $messages = "Product updated successfuly";
                return Redirect::Back()
                    ->with('success', $messages);
            }
        } else {
            $messages[] = "Sorry! Please try again";
            return Redirect::Back()
                ->withErrors($messages);
        }

        return Redirect::Back();
    }

    public function approveProduct(Request $request, $id = null)
    {
        if ($id !== null) {
            $product = Product::find($id);

            $product->is_approved = 1;
            $product->approved_by = Auth::user()->id;
            $product->listing_approved_at = Carbon::now()->toDateTimeString();
            $product->save();

            $l = new ListingHistory();
            $l->user_id = Auth::user()->id;
            $l->product_id = $product->id;
            $l->action = 'LISTING_APPROVAL';
            $l->content = ['action' => 'LISTING_APPROVAL', 'message' => 'Listing approved!'];
            $l->save();

            // once product approved the remove from the edititing list
            $productVUser = \App\ProductVerifyingUser::where("product_id", $id)->first();
            if ($productVUser) {
                $productVUser->delete();
            }

            ActivityConroller::create($product->id, 'productlister', 'create');

        } else {
            $ids = $request->ids;
            $products = Product::whereIn('id', explode(",", $ids))->get();
            foreach ($products as $product) {
                $product->is_approved = 1;
                $product->approved_by = Auth::user()->id;
                $product->listing_approved_at = Carbon::now()->toDateTimeString();
                $product->save();

                $l = new ListingHistory();
                $l->user_id = Auth::user()->id;
                $l->product_id = $product->id;
                $l->action = 'LISTING_APPROVAL';
                $l->content = ['action' => 'LISTING_APPROVAL', 'message' => 'Listing approved!'];
                $l->save();

                // once product approved the remove from the edititing list
                $productVUser = \App\ProductVerifyingUser::where("product_id", $id)->first();
                if ($productVUser) {
                    $productVUser->delete();
                }
            }

            // once product approved the remove from the edititing list
            $productVUser = \App\ProductVerifyingUser::where("product_id", $id)->first();
            if ($productVUser) {
                $productVUser->delete();
            }


            ActivityConroller::create($product->id, 'productlister', 'create');

    //      if (Auth::user()->hasRole('Products Lister')) {
    //          $products_count = Auth::user()->products()->count();
    //          $approved_products_count = Auth::user()->approved_products()->count();
    //          if (($products_count - $approved_products_count) < 100) {
    //              $requestData = new Request();
    //              $requestData->setMethod('POST');
    //              $requestData->request->add(['amount_assigned' => 100]);
    //
    //              app('App\Http\Controllers\UserController')->assignProducts($requestData, Auth::id());
    //          }
    //      }

        }
        return response()->json([
            'result' => true,
            'status' => 'is_approved',
            'success' => 'Products Approved successfully'
        ]);
    }

    public function submitForApproval(Request $request, $id)
    {
        $product = Product::find($id);
        $product->status = StatusHelper::$submitForApproval;
        $product->save();

        $l = new ListingHistory();
        $l->user_id = Auth::user()->id;
        $l->product_id = $product->id;
        $l->action = 'SUBMIT_FOR_APPROVAL';
        $l->content = ['action' => 'SUBMIT_FOR_APPROVAL', 'message' => 'User has submitted for approval!'];
        $l->save();

        return response()->json([
            'result' => true,
            'status' => 'submit_for_approval'
        ]);
    }

    public function archive($id)
    {
        $product = Product::find($id);
        $product->delete();

        return redirect()->back()
            ->with('success', 'Product archived successfully');
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->find($id);
        $product->restore();

        return redirect()->back()
            ->with('success', 'Product restored successfully');
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->forceDelete();

        return redirect()->back()
            ->with('success', 'Product deleted successfully');
    }

    public function originalCategory($id)
    {
        $product = Product::find($id);
        $referencesCategory = "";

        if (isset($product->scraped_products)) {
            // starting to see that howmany category we going to update
            if (isset($product->scraped_products->properties) && isset($product->scraped_products->properties['category']) != null) {
                $category = $product->scraped_products->properties['category'];
                if (is_array($category)) {
                    $referencesCategory = implode(' > ', $category);
                }
            }

            $scrapedProductSkuArray = [];

            if (!empty($referencesCategory)) {
                $productSupplier = $product->supplier;
                $supplier = Supplier::where('supplier', $productSupplier)->first();
                if ($supplier && $supplier->scraper) {
                    $scrapedProducts = ScrapedProducts::where('website', $supplier->scraper->scraper_name)->get();
                    foreach ($scrapedProducts as $scrapedProduct) {
                        $products = $scrapedProduct->properties['category'];
                        if (is_array($products)) {
                            $list = implode(' > ', $products);
                            if (strtolower($referencesCategory) == strtolower($list)) {
                                $scrapedProductSkuArray[] = $scrapedProduct->sku;
                            }
                        }
                    }
                }
            }

            if (isset($product->scraped_products->properties) && isset($product->scraped_products->properties['category']) != null) {
                return response()->json(['success', $referencesCategory, count($scrapedProductSkuArray)]);
            } else {
                return response()->json(['message', 'Category Is Not Present']);
            }

        } else {
            return response()->json(['message', 'Category Is Not Present']);
        }

    }

    public function changeAllCategoryForAllSupplierProducts(Request $request, $id)
    {
        \App\Jobs\UpdateScrapedCategory::dispatch([
            "product_id" => $id,
            "category_id" => $request->category,
            "user_id" => Auth::user()->id
        ])->onQueue("supplier_products");

        return response()->json(['success', 'Product category has been sent for the update']);
    }

    public function attachProducts($model_type, $model_id, $type = null, $customer_id = null, Request $request)
    {

        $roletype = $request->input('roletype') ?? 'Sale';
        $products = Product::where('stock', '>=', 1)
            ->select(['id', 'sku', 'size', 'price_inr_special', 'brand', 'isApproved', 'stage', 'created_at'])
            ->orderBy("created_at", "DESC")
            ->paginate(Setting::get('pagination'));

        $doSelection = true;
        $customer_id = $customer_id ?? null;

        if ($type == 'images') {
            $attachImages = true;
        } else {
            $attachImages = false;
        }

        if ($model_type == 'broadcast-images') {
            $attachImages = true;
            $doSelection = false;
        }

        if (Order::find($model_id)) {
            $selected_products = self::getSelectedProducts($model_type, $model_id);
        } else {
            $selected_products = [];
        }

        $category_selection = Category::attr(['name' => 'category[]', 'class' => 'form-control'])
            ->selected(1)
            ->renderAsDropdown();


        return view('partials.grid', compact('products', 'roletype', 'model_id', 'selected_products', 'doSelection', 'model_type', 'category_selection', 'attachImages', 'customer_id'));
    }

    public function attachImages($model_type, $model_id = null, $status = null, $assigned_user = null, Request $request)
    {
        // ->where('composition', 'LIKE', '%' . request('keyword') . '%')
        // dd($request->all());
        if ($model_type == 'customer') {
            $customerId = $model_id;
        } else {
            $customerId = null;
        }
        //\DB::enableQueryLog();
        $roletype = $request->input('roletype') ?? 'Sale';
        $term = $request->input('term');
        if ($request->total_images) {
            $perPageLimit = $request->total_images;
        } else {
            $perPageLimit = $request->get("per_page");
        }


        if (Order::find($model_id)) {
            $selected_products = self::getSelectedProducts($model_type, $model_id);
        } else {
            $selected_products = [];
        }
        if (empty($perPageLimit)) {
            $perPageLimit = Setting::get('pagination');
        }

        $sourceOfSearch = $request->get("source_of_search", "na");

        // start add fixing for the price range since the one request from price is in range
        // price  = 0 , 100

        // $priceRange = $request->get("price", null);

        // if ($priceRange && !empty($priceRange)) {
        //     @list($minPrice, $maxPrice) = explode(",", $priceRange);
        //     // adding min price
        //     if (isset($minPrice)) {
        //         $request->request->add(['price_min' => $minPrice]);
        //     }
        //     // addin max price
        //     if (isset($maxPrice)) {
        //         $request->request->add(['price_max' => $maxPrice]);
        //     }
        // }

        $products = (new Product())->newQuery()->latest();
        $products->where("has_mediables", 1);



        if (isset($request->brand[0])) {
            if ($request->brand[0] != null) {
                $products = $products->whereIn('brand', $request->brand);
            }
        }

        if (isset($request->color[0])) {
            if ($request->color[0] != null) {
                $products = $products->whereIn('color', $request->color);
            }
        }

        if (isset($request->category[0])) {
            if ($request->category[0] != null && $request->category[0] != 1) {

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


            }
        }

        if ($request->price_min != null && $request->price_min != 0) {
            $products = $products->where('price_inr_special', '>=', $request->price_min);
        }

        if ($request->price_max != null) {
            $products = $products->where('price_inr_special', '<=', $request->price_max);
        }

        if ($request->discounted_percentage_min != null && $request->discounted_percentage_min != 0) {
            $products = $products->where('discounted_percentage', '>=', $request->discounted_percentage_min);
        }

        if ($request->discounted_percentage_max != null) {
            $products = $products->where('discounted_percentage', '<=', $request->discounted_percentage_max);
        }

        if (isset($request->supplier[0])) {
            if ($request->supplier[0] != null) {
                $suppliers_list = implode(',', $request->supplier);

                $products = $products->whereRaw("products.id in (SELECT product_id FROM product_suppliers WHERE supplier_id IN ($suppliers_list))");
            }
        }

        if (trim($request->size) != '') {
            $products = $products->whereNotNull('size')->where(function ($query) use ($request) {
                $query->where('size', $request->size)->orWhere('size', 'LIKE', "%$request->size,")->orWhere('size', 'LIKE', "%,$request->size,%");
            });
        }

        if (isset($request->location[0])) {
            if ($request->location[0] != null) {
                $products = $products->whereIn('location', $request->location);
            }
        }

        if (isset($request->type[0])) {
            if ($request->type[0] != null && is_array($request->type)) {
                if (count($request->type) > 1) {
                    $products = $products->where(function ($query) use ($request) {
                        $query->where('is_scraped', 1)->orWhere('status', 2);
                    });
                } else {
                    if ($request->type[0] == 'scraped') {
                        $products = $products->where('is_scraped', 1);
                    } elseif ($request->type[0] == 'imported') {
                        $products = $products->where('status', 2);
                    } else {
                        $products = $products->where('isUploaded', 1);
                    }
                }
            }
        }

        if ($request->date != '') {
            if (isset($products)) {
                if ($request->type[0] != null && $request->type[0] == 'uploaded') {
                    $products = $products->where('is_uploaded_date', 'LIKE', "%$request->date%");
                } else {
                    $products = $products->where('created_at', 'LIKE', "%$request->date%");
                }
            }
        }

        if (trim($term) != '') {
            $products = $products->where(function ($query) use ($term) {
                $query->where('sku', 'LIKE', "%$term%")
                    ->orWhere('id', 'LIKE', "%$term%")
                    ->orWhere('name', 'LIKE', "%$term%")
                    ->orWhere('short_description', 'LIKE', "%$term%");
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


        if (isset($request->ids[0])) {
            if ($request->ids[0] != null) {
                $products = $products->whereIn('id', $request->ids);
            }
        }


        $selected_categories = $request->category ? $request->category : 1;

        if ($request->quick_product === 'true') {
            $products = $products->where('quick_product', 1);
        }

        // assing product to varaible so can use as per condition for join table media
        if ($request->quick_product !== 'true') {
            $products = $products->whereRaw("(stock > 0 OR (supplier ='In-Stock'))");
        }

        if($request->drafted_product == "on") {
            $products = $products->whereRaw("quick_product = 1");
        }

        // if source is attach_media for search then check product has image exist or not
        if ($request->get("unsupported", null) != "") {

            $products = $products->join("mediables", function ($query) {
                $query->on("mediables.mediable_id", "products.id")->where("mediable_type", \App\Product::class);
            });

            $mediaIds = \DB::table("media")->where("aggregate_type", "image")->join("mediables", function ($query) {
                $query->on("mediables.media_id", "media.id")->where("mediables.mediable_type", \App\Product::class);
            })->whereNotIn("extension", config("constants.gd_supported_files"))->select("id")->pluck("id")->toArray();

            $products = $products->whereIn("mediables.media_id", $mediaIds);
            $products = $products->groupBy('products.id');
        }


        if (!empty($request->quick_sell_groups) && is_array($request->quick_sell_groups)) {
            $products = $products->whereRaw("(id in (select product_id from product_quicksell_groups where quicksell_group_id in (" . implode(",", $request->quick_sell_groups) . ") ))");
        }

        // brand filter count start
        $brandGroups = clone($products);
        $brandGroups = $brandGroups->groupBy("brand")->select([\DB::raw("count(id) as total_product"), "brand"])->pluck("total_product", "brand")->toArray();
        $brandIds = array_values(array_filter(array_keys($brandGroups)));

        $brandsModel = \App\Brand::whereIn("id", $brandIds)->pluck("name", "id")->toArray();

        $countBrands = [];
        if (!empty($brandGroups) && !empty($brandsModel)) {
            foreach ($brandGroups as $key => $count) {
                $countBrands[] = [
                    "id" => $key,
                    "name" => !empty($brandsModel[$key]) ? $brandsModel[$key] : "N/A",
                    "count" => $count,
                ];
            }
        }
        if ($request->category) {
            try {
                $filtered_category = $request->category;
            } catch (\Exception $e) {
                $filtered_category = [1];
            }
        } else {
            $filtered_category = [1];
        }

        $category_selection = Category::attr(['name' => 'category[]', 'class' => 'form-control select-multiple-cat-list input-lg select-multiple', 'multiple' => true, 'data-placeholder' => 'Select Category..'])
            ->selected($filtered_category)
            ->renderAsDropdown();


        // category filter start count
        $categoryGroups = clone($products);
        $categoryGroups = $categoryGroups->groupBy("category")->select([\DB::raw("count(id) as total_product"), "category"])->pluck("total_product", "category")->toArray();
        $categoryIds = array_values(array_filter(array_keys($categoryGroups)));

        $categoryModel = \DB::table('categories')->whereIn("id", $categoryIds)->pluck("title", "id")->toArray();
        $countCategory = [];
        if (!empty($categoryGroups) && !empty($categoryModel)) {
            foreach ($categoryGroups as $key => $count) {
                $countCategory[] = [
                    "id" => $key,
                    "name" => !empty($categoryModel[$key]) ? $categoryModel[$key] : "N/A",
                    "count" => $count,
                ];
            }
        }

        // suppliers filter start count/
        $suppliersGroups = clone($products);
        $all_product_ids = $suppliersGroups->pluck('id')->toArray();
        $countSuppliers = [];
        if (!empty($all_product_ids)) {
            $suppliersGroups = \App\Product::leftJoin('product_suppliers', 'product_id', '=', 'products.id')
                ->where('products.id', $all_product_ids)
                ->groupBy("product_suppliers.supplier_id")
                ->select([\DB::raw("count(products.id) as total_product"), "product_suppliers.supplier_id"])
                ->pluck("total_product", "supplier_id")
                ->toArray();
            $suppliersIds = array_values(array_filter(array_keys($suppliersGroups)));
            $suppliersModel = \App\Supplier::whereIn("id", $suppliersIds)->pluck("supplier", "id")->toArray();

            if (!empty($suppliersGroups)) {
                foreach ($suppliersGroups as $key => $count) {
                    $countSuppliers[] = [
                        "id" => $key,
                        "name" => !empty($suppliersModel[$key]) ? $suppliersModel[$key] : "N/A",
                        "count" => $count,
                    ];
                }
            }
        }

        // select fields..
        $products = $products->select(['products.id', 'name', 'short_description', 'color', 'sku', 'products.category', 'products.size', 'price_eur_special', 'price_inr_special', 'supplier', 'purchase_status', 'products.created_at']);

        if ($request->get('is_on_sale') == 'on') {
            $products = $products->where('is_on_sale', 1);
        }


        if ($request->has("limit")) {
            $perPageLimit = ($request->get("limit") == "all") ? $products->get()->count() : $request->get("limit");
        }
        // $categoryAll = Category::where('parent_id', 0)->get();
        // foreach ($categoryAll as $category) {
        //     // dump('fitst cat');
        //     $categoryArray1[] = array('id' => $category->id, 'value' => $category->title);
        //     $childs = Category::where('parent_id', $category->id)->get();
        //     foreach ($childs as $child) {
        //         // dump('first  child');
        //         $categoryArray1[] = array('id' => $child->id, 'value' => $category->title . ' ' . $child->title);
        //         $grandChilds = Category::where('parent_id', $child->id)->get();
        //         if ($grandChilds != null) {

        //             foreach ($grandChilds as $grandChild) {
        //                 // dump('first grand child');

        //                 $categoryArray1[] = array('id' => $grandChild->id, 'value' => $category->title . ' ' . $child->title . ' ' . $grandChild->title);
                        
        //             }
        //         }
        //     }
        // }

        $categoryAll = Category::with('childs.childLevelSencond')->where('parent_id', 0)->get();

        foreach ($categoryAll as $category) {
            $categoryArray[] = array('id' => $category->id, 'value' => $category->title);
            // $childs = Category::where('parent_id', $category->id)->get();
            foreach ($category->childs as $child) {
                $categoryArray[] = array('id' => $child->id, 'value' => $category->title . ' ' . $child->title);
                // $grandChilds = Category::where('parent_id', $child->id)->get();
                if ($child->childLevelSencond != null) {
                    foreach ($child->childLevelSencond as $grandChild) {
                        $categoryArray[] = array('id' => $grandChild->id, 'value' => $category->title . ' ' . $child->title . ' ' . $grandChild->title);
                    }
                }
            }
        }


//         dump($categoryArray1);
//         dump($categoryArray);
// dd('ds');

        if ($request->total_images) {
            $products = $products->limit($request->total_images)->get();
            $products = new LengthAwarePaginator($products, count($products), $request->total_images, 1, [
                'path' => LengthAwarePaginator::resolveCurrentPath()
            ]);
        } else {
            $products = $products->paginate($perPageLimit);
        }

        $brand = $request->brand;
        $products_count = $products->total();
        $all_product_ids = [];
        $from = request("from", "");
        if($request->submit_type == 'send-to-approval') {
            $products_ids_cloned = clone($products);
            $product_ids = $products_ids_cloned->pluck('id');
            $inserted = 0;
            if(count($product_ids) > 0 && $customerId) {
                $json_brands = json_encode($request->brand);
                $json_categories = json_encode($request->category);
                $json_supplier = json_encode($request->supplier);
                $json_color = json_encode($request->color);
                $json_location = json_encode($request->location);
                $size = $request->size;
                $suggestedProducts = new \App\SuggestedProduct;
                $suggestedProducts->customer_id = $customerId;
                if($json_brands != 'null' && $json_brands != '') {
                    $suggestedProducts->brands = $json_brands;
                }
                if($json_categories != 'null' && $json_categories != '') {
                    $suggestedProducts->categories = $json_categories;
                }
                if($json_color != 'null' && $json_color != '') {
                    $suggestedProducts->color = $json_color;
                }
                if($json_supplier != 'null' && $json_supplier != '') {
                    $suggestedProducts->supplier = $json_supplier;
                }
                if($json_location != 'null' && $json_location != '') {
                    $suggestedProducts->location = $json_location;
                }                
                $suggestedProducts->size = $size;
                $suggestedProducts->total = $perPageLimit;
                $suggestedProducts->save();
                $suggestedProductId=$suggestedProducts->id;

                

                $data_to_insert = [];
                foreach($product_ids as $id){
                    $exists = \App\SuggestedProductList::where('customer_id',$customerId)->where('product_id',$id)->where('date',date('Y-m-d'))->first();
                    if(!$exists) {
                        $pr = Product::find($id);
                        if($pr->hasMedia(config('constants.attach_image_tag'))) {
                            $data_to_insert[] = [
                                'suggested_products_id'=>$suggestedProductId,
                                'customer_id' => $customerId,
                                'product_id' => $id,
                                'date' => date('Y-m-d')
                            ];
                        }
                        $category_brand_count = ErpLeads::where('category_id',$pr->category)->where('brand_id',$pr->brand)->count();
                        if($category_brand_count === 0){

                            $erp_lead = new ErpLeads;
                            $erp_lead->lead_status_id = 1;
                            $erp_lead->customer_id = $customerId;
                            $erp_lead->product_id = $id;
                            $erp_lead->category_id = $pr->category;
                            $erp_lead->brand_id = $pr->brand;
                            $erp_lead->min_price = !empty($request->price_min) ? $request->price_min : 0;
                            $erp_lead->max_price = !empty($request->price_max) ? $request->price_max : 0;
                            $erp_lead->save();
                        }
                        
                    }
                }
                $inserted = count($data_to_insert);
                if($inserted > 0) {
                    \App\SuggestedProductList::insert($data_to_insert);
                } 
            }

            //
            if($request->need_to_send_message == 1) {
               \App\ChatMessage::create([
                    "message" => "Total product found '".count($product_ids)."' for the keyword message : {$request->keyword_matched}",
                    "customer_id" => $model_id,
                    "status" => 2,
                    "approved" => 1,
               ]);
               
               return ["total_product" => count($product_ids)];
            }


            // $message_body = '';
            // $sending_time = '';
    
            // $locations = \App\ProductLocation::pluck("name", "name");
            // $suppliers = Supplier::select(['id', 'supplier'])->whereIn('id', DB::table('product_suppliers')->selectRaw('DISTINCT(`supplier_id`) as suppliers')->pluck('suppliers')->toArray())->get();
    
            // $quick_sell_groups = \App\QuickSellGroup::select('id', 'name')->orderBy('id', 'desc')->get(); 
            // return view('partials.attached-image-grid', compact(
            //                 'suggestedProducts', 'products_count', 'roletype', 'model_id', 'selected_products', 'model_type', 'status', 'assigned_user', 'category_selection', 'brand', 'filtered_category', 'message_body', 'sending_time', 'locations', 'suppliers', 'all_product_ids', 'quick_sell_groups', 'countBrands', 'countCategory', 'countSuppliers', 'customerId', 'categoryArray', 'term'
            // ));
            // $route = '/attached-images-grid/customer?customer_id='.$customerId;
            // return redirect($route);
            $msg = $inserted.' Products attached successfully';
            return response()->json(['code' => 200, 'message' => $msg]);
        }

        $mailEclipseTpl = mailEclipse::getTemplates()->where('template_dynamic',FALSE);;
        $rViewMail      = [];
        if (!empty($mailEclipseTpl)) {
            foreach ($mailEclipseTpl as $mTpl) {
                $rViewMail[$mTpl->template_slug] = $mTpl->template_name . " [" . $mTpl->template_description . "]";
            }
        }

        if ($request->ajax()) {
            $html = view('partials.image-load', [
                'products' => $products,
                'all_product_ids' => $all_product_ids,
                'selected_products' => $request->selected_products ? json_decode($request->selected_products) : [],
                'model_type' => $model_type,
                'countBrands' => $countBrands,
                'countCategory' => $countCategory,
                'countSuppliers' => $countSuppliers,
                'customerId' => $customerId,
                'categoryArray' => $categoryArray,
                'rViewMail' => $rViewMail
            ])->render();

            if (!empty($from) && $from == "attach-image") {
                return $html;
            }

            return response()->json(['html' => $html, 'products_count' => $products_count]);
        }

        $message_body = $request->message ? $request->message : '';
        $sending_time = $request->sending_time ?? '';

        $locations = \App\ProductLocation::pluck("name", "name");
        $suppliers = Supplier::select(['id', 'supplier'])->whereIn('id', DB::table('product_suppliers')->selectRaw('DISTINCT(`supplier_id`) as suppliers')->pluck('suppliers')->toArray())->get();

        $quick_sell_groups = \App\QuickSellGroup::select('id', 'name')->orderBy('id', 'desc')->get();
        //\Log::info(print_r(\DB::getQueryLog(),true));
        return view('partials.image-grid', compact(
            'products',
            'products_count',
            'roletype',
            'model_id',
            'selected_products',
            'model_type',
            'status',
            'assigned_user',
            'category_selection',
            'brand',
            'filtered_category',
            'message_body',
            'sending_time',
            'locations',
            'suppliers',
            'all_product_ids',
            'quick_sell_groups',
            'countBrands',
            'countCategory',
            'countSuppliers',
            'customerId',
           // 'categoryArray',
            'term',
            'rViewMail'
        ));
    }


    public function attachProductToModel($model_type, $model_id, $product_id)
    {

        switch ($model_type) {
            case 'order':
                $action = OrderController::attachProduct($model_id, $product_id);

                break;

            case  'sale':
                $action = SaleController::attachProduct($model_id, $product_id);
                break;
            case 'stock':
                $stock = Stock::find($model_id);
                $product = Product::find($product_id);

                $stock->products()->attach($product);
                $action = 'Attached';
                break;
        }


        return ['msg' => 'success', 'action' => $action];
    }

    public static function getSelectedProducts($model_type, $model_id)
    {
        $selected_products = [];

        switch ($model_type) {
            case 'order':
                $order = Order::find($model_id);
                if (!empty($order)) {
                    $selected_products = $order->order_product()->with('product')->get()->pluck('product.id')->toArray();
                }
                break;

            case 'sale':
                $sale = Sale::find($model_id);
                if (!empty($sale)) {
                    $selected_products = json_decode($sale->selected_product, true) ?? [];
                }
                break;

            default :
                $selected_products = [];
        }

        return $selected_products;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'sku' => 'required|unique:products'
        ]);

        $product = new Product;

        // return response()->json(['ok' => $request->file('image')->getClientOriginalExtension()]);

        $product->name = $request->name;
        $product->sku = $request->sku;
       // $size_array = implode(',', $request->size) ;
        $size = !is_array($request->size) ? [$request->size] : $request->size;
        $product->size = implode(',', $size);
        $product->brand = $request->brand;
        $product->color = $request->color;
        $product->supplier = $request->supplier;
        $product->location = $request->location;
        $product->category = $request->category ?? 1;
        if($request->price) {
           $product->price = $request->price;
        }
        if($request->price_inr_special) {
           $product->price_inr_special = $request->price_inr_special;
        }
        $product->stock = 1;

        $brand = Brand::find($request->brand);

        if ($request->price) {
            if (isset($request->brand) && !empty($brand->euro_to_inr)) {
                $product->price_inr = $brand->euro_to_inr * $product->price;
            } else {
                $product->price_inr = Setting::get('euro_to_inr') * $product->price;
            }

            $deduction_percentage = $brand && $brand->deduction_percentage ? $brand->deduction_percentage : 1;
            $product->price_inr = round($product->price_inr, -3);
            $product->price_inr_special = $product->price_inr - ($product->price_inr * $deduction_percentage) / 100;

            $product->price_inr_special = round($product->price_inr_special, -3);
        }else if($request->price_inr_special) {
            if (isset($request->brand) && !empty($brand->euro_to_inr)) {
                $product->price = $request->price_inr_special / $brand->euro_to_inr;
            } else {
                $product->price = $request->price_inr_special / Setting::get('euro_to_inr');
            }
            $product->price_inr = $request->price_inr_special;
        }

        $product->save();

        if ($request->supplier == 'In-stock') {
            $product->suppliers()->attach(11); // In-stock ID
        }

        if($request->hasFile('image')) {
            $product->detachMediaTags(config('constants.media_tags'));
            $media = MediaUploader::fromSource($request->get('is_image_url') ? $request->get('image') : $request->file('image'))
                ->toDirectory('product/' . floor($product->id / config('constants.image_per_folder')) . '/' . $product->id)
                ->upload();
            $product->attachMedia($media, config('constants.media_tags'));
        }


        $product_image = $product->getMedia(config('constants.media_tags'))->first() ? $product->getMedia(config('constants.media_tags'))->first()->getUrl() : '';

        if ($request->order_id) {
            $order_product = new OrderProduct;

            $order_product->order_id = $request->order_id;
            $order_product->sku = $request->sku;
            $order_product->product_price = $product->price_inr_special;
            $order_product->size = $request->size;
            $order_product->color = $request->color;
            $order_product->qty = $request->quantity;
            $order_product->product_id = $product->id;
            $order_product->save();

            // return response($product);

            return response(['product' => $product, 'order' => $order_product, 'quantity' => $request->quantity, 'product_image' => $product_image]);
        } elseif ($request->stock_id) {
            $stock = Stock::find($request->stock_id);
            $stock->products()->attach($product);

            return response(['product' => $product, 'product_image' => $product_image]);
        }

        if ($request->ajax()) {
            return response()->json(['msg' => 'success']);
        }

        return redirect()->back()->with('success', 'You have successfully uploaded product!');
    }

    /**
     * @SWG\Get(
     *   path="/crop",
     *   tags={"Scraper"},
     *   summary="Return images array where the product status = auto crop",
     *   operationId="scraper-get-product-img",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="product_id",
     *          in="path",
     *          required=false, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="supplier_id",
     *          in="path",
     *          required=false, 
     *          type="string" 
     *      ),
     * )
     *
     */
    
    public function giveImage()
    {
        $productId = request("product_id", null);
        $supplierId = request("supplier_id", null);
        if ($productId != null) {
            $product = Product::where('id', $productId)->where('status_id', StatusHelper::$autoCrop)
                ->where('category', '>', 3)->first();
        }elseif($supplierId != null) {
            $product = Product::join("product_suppliers as ps","ps.product_id", "products.id")
            ->where('ps.supplier_id', $supplierId)
            ->where('products.status_id', StatusHelper::$autoCrop)
            ->where('products.category', '>', 3)
            ->where('products.stock', '>=', 1)
            ->orderBy('products.scrap_priority', 'DESC')
            ->select("products.*")
            ->first();
        } else {
            // Get next product
            $product = Product::where('status_id', StatusHelper::$autoCrop)
                ->where('category', '>', 3);
            // Add order
            $product = QueryHelper::approvedListingOrder($product);
            //get priority
            $product = $product->with('suppliers_info.supplier')->whereHas('suppliers_info.supplier',function($query){
               // $query->where('priority','!=',null);
            })->whereHasMedia('original')->get()->transform(function($productData){
                $productData->priority = isset($productData->suppliers_info->first()->supplier->priority) ? $productData->suppliers_info->first()->supplier->priority : 5;
                return $productData;
            });
            $product = $product->sortBy('priority')->first();
            unset($product->priority);
            // return response()->json([
            //     'status' => $product
            // ]);
            // Get first product
           // $product = $product->whereHasMedia('original')->first();
           
        }

        
        if (!$product) {
            // Return JSON
            return response()->json([
                'status' => 'no_product'
            ]);
        }


        $mediables = DB::table('mediables')->select('media_id')->where('mediable_id', $product->id)->where('mediable_type', 'App\Product')->where('tag', 'original')->get();

        //deleting old images 

        $oldImages = DB::table('mediables')->select('media_id')->where('mediable_id', $product->id)->where('mediable_type', 'App\Product')->where('tag','!=','original')->get();

        //old scraped products 
        if($oldImages){
            foreach ($oldImages as $img) {

                $media = Media::where('id',$img->media_id)->first();
                if($media){
                    $image_path = $media->getAbsolutePath();
                    if (\File::exists($image_path)) {
                        \File::delete($image_path);
                    }
                    $media->delete();                    
                }

            }
        }

        foreach ($mediables as $mediable) {
            $mediableArray[] = $mediable->media_id;
        }

        if (!isset($mediableArray)) {
            return response()->json([
                'status' => 'no_product'
            ]);
        }

        $images = Media::select('id', 'filename', 'extension', 'mime_type', 'disk', 'directory')->whereIn('id', $mediableArray)->get();

        foreach ($images as $image) {
            $output['media_id'] = $image->id;
            $image->setAttribute('pivot', $output);
        }

        //WIll use in future to detect Images removed to fast the query for now
        //foreach ($images as $image) {
        //$link = $image->getUrl();


        //$link = 'https://erp.theluxuryunlimited.com/uploads/15d428fb0c6944.jpg';
        // $vision = LogGoogleVision::where('image_url','LIKE','%'.$link.'%')->first();
        // if($vision != null){
        //    $keywords = preg_split('/[\n,]+/',$vision->response);
        //    $countKeywords = count($keywords);
        //    for ($i=0; $i < $countKeywords; $i++) {
        //         if (strpos($keywords[$i], 'Object') !== false) {
        //                 $key = str_replace('Object: ','',$keywords[$i]);
        //                 $value = str_replace('Score (confidence): ','',$keywords[$i+1]);
        //                 $output[] = array($key => $value);
        //         }
        //    }
        // }
        // if(isset($output)){
        //    $image->setAttribute('objects', json_encode($output));
        // }else{
        //   $image->setAttribute('objects', '');
        // }

        //}

        // Get category
        $category = $product->product_category;

        // Get other information related to category
        $cat = $category->title;
        $parent = '';
        $child = '';

        try {
            if ($cat != 'Select Category') {
                if ($category->isParent($category->id)) {
                    $parent = $cat;
                    $child = $cat;
                } else {
                    $parent = $category->parent()->first()->title;
                    $child = $cat;
                }
            }
        } catch (\ErrorException $e) {
            //
        }

        //Getting Website Color
        $websiteArrays = ProductHelper::getStoreWebsiteName($product->id);
        if (count($websiteArrays) == 0) {
            $colors = [];
        } else {
            foreach ($websiteArrays as $websiteArray) {

                $website = StoreWebsite::find($websiteArray);
                if ($website) {
                    $isCropped = SiteCroppedImages::where('website_id', $websiteArray)
                    ->where('product_id', $product->id)->exists();
                    if(!$isCropped) {
                        list($r, $g, $b) = sscanf($website->cropper_color, "#%02x%02x%02x");
                        $hexcode = '(' . $r . ',' . $g . ',' . $b . ')';
                        $colors[] = array('code' => $hexcode, 'color' => $website->cropper_color_name,'size' => $website->cropping_size);
                    }
                }
            }
        }
        if (!isset($colors)) {
            $colors = [];
        }
        if ($parent == null && $parent == '') {
            // Set new status
            $product->status_id = StatusHelper::$attributeRejectCategory;
            $product->save();

            // Return JSON
            return response()->json([
                'status' => 'no_product'
            ]);

        } else {
            // Set new status
            $debug = request("debug",false);
            if(empty($debug)) {
                $product->status_id = StatusHelper::$isBeingCropped;
                $product->save();
            }

            // Return product
            return response()->json([
                'product_id' => $product->id,
                'image_urls' => $images,
                'l_measurement' => $product->lmeasurement,
                'h_measurement' => $product->hmeasurement,
                'd_measurement' => $product->dmeasurement,
                'category' => "$parent $child",
                'colors' => $colors,
            ]);
        }
    }

    /**
     * @SWG\Post(
     *   path="/link/image-crop",
     *   tags={"Crop"},
     *   summary="Save cropped image for product",
     *   operationId="crop-save-product-img",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="product_id",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="file",
     *          in="formData",
     *          required=true, 
     *          type="file" 
     *      ),
     *      @SWG\Parameter(
     *          name="color",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="media_id",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="filename",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="time",
     *          in="formData",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    public function saveImage(Request $request)
    {
        // Find the product or fail
        $product = Product::findOrFail($request->get('product_id'));
        // Check if this product is being cropped
        if ($product->status_id != StatusHelper::$isBeingCropped) {
            return response()->json([
                'status' => 'unknown product'
            ], 400);
        }

        // Check if we have a file
        if ($request->hasFile('file')) {
            $image = $request->file('file');

            //Get the last image of the product.
            $allMediaIds = [];
            $pMedia = $product->getMedia(config('constants.media_original_tag'));
            if(!$pMedia->isEmpty()) {
                foreach($pMedia as $m) {
                    $allMediaIds[] = $m->id;
                }
            }

            $productMediacount = count($allMediaIds);

            $media = MediaUploader::fromSource($image)
                ->useFilename('CROPPED_' . time() . '_' . rand(555, 455545))
                ->toDirectory('product/' . floor($product->id / config('constants.image_per_folder')) . '/' . $product->id)
                ->upload();
            $colorName =  null;    
            if ($request->get('color')) {
                $colorCode = str_replace(['(', ')'], '', $request->get('color'));
                $rgbarr = explode(",", $colorCode, 3);
                $hex = sprintf("#%02x%02x%02x", $rgbarr[0], $rgbarr[1], $rgbarr[2]);
                $colorName = $hex;
                $tag = 'gallery_' . $hex;

                // check the store website count is existed with the total image
                $storeWebCount = $product->getMedia($tag)->count();
                if($productMediacount <= $storeWebCount) {
                    $store_websites = StoreWebsite::where('cropper_color', $request->get('color'))->first();
                    if ($store_websites !== null) {
                        $exist = SiteCroppedImages::where('website_id', $store_websites->id)
                            ->where('product_id', $product->id)->exists();
                        if (!$exist) {
                            SiteCroppedImages::create([
                                'website_id' => $store_websites->id,
                                'product_id' => $product->id
                            ]);
                        }
                    }
                }

            } else {
                $tag = config('constants.media_gallery_tag');
            }
            $product->attachMedia($media, $tag);
            $product->crop_count = $product->crop_count + 1;
            $product->save();


            $imageReference = new CroppedImageReference();
            $imageReference->original_media_id = $request->get('media_id');
            $imageReference->new_media_id = $media->id;
            $imageReference->original_media_name = $request->get('filename');
            $imageReference->new_media_name = $media->filename . '.' . $media->extension;
            $imageReference->speed = $request->get('time');
            $imageReference->product_id = $product->id;
            $imageReference->color = $colorName;
            $imageReference->save();


            
            //CHeck number of products in Crop Reference Grid
            $cropCount = CroppedImageReference::where('product_id', $product->id)->whereIn('original_media_id', $allMediaIds)->count();

            //check website count using Product
            $websiteArrays = ProductHelper::getStoreWebsiteName($product->id);
            try {
                if (count($websiteArrays) == 0) {
                    $multi = 1;
                } else {
                    $multi = count($websiteArrays);
                }
            } catch (\Exception $e) {
                $multi = 1;
            }
            $totalM = $productMediacount;

            $productMediacount = ($productMediacount * $multi);

            //\Log::info(json_encode(["Media Crop",$product->id,$multi,$totalM,$productMediacount,$cropCount]));

            if ($productMediacount <= $cropCount) {
                $product->cropped_at = Carbon::now()->toDateTimeString();
                //check final approval
                if($product->checkPriceRange()){
                    $product->status_id = StatusHelper::$finalApproval;
                }else{
                    $product->status_id = StatusHelper::$priceCheck;
                }
                
                $product->scrap_priority = 0;
                $product->save();
            } else {
                $product->cropped_at = Carbon::now()->toDateTimeString();
                $product->save();
            }


            // get the status as per crop
            if ($product->category > 0) {
                $category = \App\Category::find($product->category);
                if (!empty($category) && $category->status_after_autocrop > 0) {
                    \App\Helpers\StatusHelper::updateStatus($product, $category->status_after_autocrop);
                }
            }

        } else {
            $product->status_id = StatusHelper::$cropSkipped;
            $product->save();
        }

        $exitCode = Artisan::call('RejectDuplicateImages', [
            'media_id' => $request->get('media_id'), 'product_id' => $request->get('product_id')
        ]);
        
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function rejectedListingStatistics()
    {
        $products = DB::table('products')->where('is_listing_rejected', 1)->groupBy(['listing_remark', 'supplier'])->selectRaw('COUNT(*) as total_count, supplier, listing_remark')->orderBy('total_count', 'DESC')->get();


        return view('products.rejected_stats', compact('products'));
    }

    public function addListingRemarkToProduct(Request $request)
    {
        $productId = $request->get('product_id');
        $remark = $request->get('remark');

        $product = Product::find($productId);
        if ($product) {
            $product->listing_remark = $remark;
            $product->is_listing_rejected = $request->get('rejected');
            $product->listing_rejected_by = Auth::user()->id;
            $product->is_approved = 0;
            $product->listing_rejected_on = date('Y-m-d');
            $product->save();
        }

        if ($request->get('senior') && $product) {
            $s = new UserProductFeedback();
            $s->user_id = $product->approved_by;
            $s->senior_user_id = Auth::user()->id;
            $s->action = 'LISTING_APPROVAL_REJECTED';
            $s->content = ['action' => 'LISTING_APPROVAL_REJECTED', 'previous_action' => 'LISTING_APPROVAL', 'current_action' => 'LISTING_REJECTED', 'message' => 'Your listing has been rejected because of : ' . $remark];
            $s->message = "Your listing approval has been discarded by the Admin because of this issue: $remark. Please make sure you check these details before approving any future product.";
            $s->product_id = $product->id;
            $s->save();
        }

        if ($request->get('rejected') && $product) {
            $l = new ListingHistory();
            $l->action = 'LISTING_REJECTED';
            $l->content = ['action' => 'LISTING_REJECTED', 'page' => 'LISTING'];
            $l->user_id = Auth::user()->id;
            $l->product_id = $product->id;
            $l->save();
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function showAutoRejectedProducts()
    {
        $totalRemaining = Product::where('stock', '>=', 1)->where('is_listing_rejected_automatically', 1)->count();
        $totalDone = Product::where('stock', '>=', 1)->where('was_auto_rejected', 1)->count();

        return view('products.auto_rejected_stats', compact('totalDone', 'totalRemaining'));
    }

    public function affiliateProducts(Request $request)
    {
        $colors = (new Colors)->all();
        $category_tree = [];
        $brands = Brand::all();
        $brand = null;
        $price = null;
        $color = [];
        $products = Product::where('is_without_image', 0);

        if ($request->get('sku')) {
            $products = $products->where(function ($query) use ($request) {
                $sku = $request->get('sku');
                $query->where('sku', $sku)
                    ->orWhere('name', 'LIKE', "%$sku%")
                    ->orWhere('short_description', 'LIKE', "%$sku%");
            });
        }

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

        if ($request->get('brand') > 0) {
            $brand = $request->get('brand');
            $products = $products->where('brand', $brand);
        }

        $selected_categories = $request->get('category') ?: [1];
        if ($request->get('category')[0] != null && $request->get('category')[0] != 1) {
            $category_children = [];

            foreach ($request->get('category') as $category) {
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
        }

        if ($request->color[0] != null) {
            $products = $products->whereIn('color', $request->color);
            $color = $request->color;
        }

        if ($request->get('price')[0] !== null) {
            $price = $request->get('price');
            $price = explode(',', $price);
            $products = $products->whereBetween('price_inr_special', [$price[0], $price[1]]);
        }

        $category_array = Category::renderAsArray();

        $products = $products->paginate(20);

        $c = $color;


        return view('products.affiliate', compact('products', 'request', 'brands', 'categories_array', 'category_array', 'selected_categories', 'brand', 'colors', 'c', 'price'));

    }

    public function showRejectedListedProducts(Request $request)
    {
        $products = new Product;
        $products = $products->where('stock', '>=', 1);
        $reason = '';
        $supplier = [];
        $selected_categories = [];

        if ($request->get('reason') !== '') {
            $reason = $request->get('reason');
            $products = $products->where('listing_remark', 'LIKE', "%$reason%");
        }

        if ($request->get('date') !== '') {
            $date = $request->get('date');
            $products = $products->where('listing_rejected_on', 'LIKE', "%$date%");
        }

        if ($request->get('id') !== '') {
            $id = $request->get('id');
            $products = $products->where('id', $id)->orWhere('sku', 'LIKE', "%$id%");
        }

        if ($request->get('user_id') > 0) {
            $products = $products->where('listing_rejected_by', $request->get('user_id'));
        }

        if ($request->get('type') === 'accepted') {
            $products = $products->where('is_listing_rejected', 0)->where('listing_remark', '!=', '');
        } else {
            $products = $products->where('is_listing_rejected', 1);
        }

        $suppliers = DB::select('
                SELECT id, supplier
                FROM suppliers

                INNER JOIN (
                    SELECT supplier_id FROM product_suppliers GROUP BY supplier_id
                    ) as product_suppliers
                ON suppliers.id = product_suppliers.supplier_id
        ');

        if ($request->supplier[0] != null) {

            $supplier = $request->get('supplier');
            $products = $products->whereIn('id', DB::table('product_suppliers')->whereIn('supplier_id', $supplier)->pluck('product_id'));
        }


        if ($request->category[0] != null && $request->category[0] != 1) {
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
            $selected_categories = [$request->get('category')[0]];
        }
        $users = User::all();

        $category_array = Category::renderAsArray();

        $products = $products->with('log_scraper_vs_ai')->where('stock', '>=', 1)->where('is_listing_rejected', 1)->orderBy('listing_rejected_on', 'DESC')->orderBy('updated_at', 'DESC')->paginate(25);

        $rejectedListingSummary = DB::table('products')->where('stock', '>=', 1)->selectRaw('DISTINCT(listing_remark) as remark, COUNT(listing_remark) as issue_count')->where('is_listing_rejected', 1)->groupBy('listing_remark')->orderBy('issue_count', 'DESC')->get();

        return view('products.rejected_listings', compact('products', 'reason', 'category_array', 'selected_categories', 'suppliers', 'supplier', 'request', 'users', 'rejectedListingSummary'));
    }

    public function updateProductListingStats(Request $request)
    {

        $product = Product::find($request->get('product_id'));
        if ($product) {
            $product->is_corrected = $request->get('is_corrected');
            $product->is_script_corrected = $request->get('is_script_corrected');
            $product->save();
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function deleteProduct(Request $request)
    {
        if ($request->has('product_id')) {
            $product = Product::find($request->get('product_id'));

            if ($product) {
                $product->forceDelete();
            }
        } else {
            $ids = $request->ids;
            $delete_products = Product::whereIn('id', explode(",", $ids))->get();
            foreach ($delete_products as $delete_product) {
//                $delete_product->forceDelete();
                $delete_product->deleted_at = date('Y-m-d H:i:s');
                $delete_product->save();
            }
        }
//
//        return json_encode('done');
//        if(!empty($request->product_id_array)){
//            $product_ids = $request->product_id_array ;
//            foreach ($product_ids as $product_id) {
//                $product = Product::where('id', $product_id);
//                $product->forceDelete();
//            }
//        }


        return response()->json([
            'status' => 'Products Deleted successfully',
            'success' => 'Products Deleted successfully'
        ]);
    }

    public function relistProduct(Request $request)
    {
        $product = Product::find($request->get('product_id'));

        if ($product) {
            $product->is_listing_rejected = $request->get('rejected');
            $product->save();
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function productDescription(Request $request)
    {
        $query = ProductSupplier::with('supplier','product');
        if($request->get('product_id') != ''){
             $products = $query->where('product_id',$request->get('product_id'));
        }
        if($request->get('supplier') != ''){
            $products = $query->where('supplier_id',$request->get('supplier'));
       }
       if($request->get('sku') != ''){
            $products = $query->whereHas('product',function($query) use($request){
                $query->where('sku',$request->get('sku'));
            });
        }
        $products_count = $query->count();
        $products = $query->orderBy('product_id')->paginate(50);
        $supplier = Supplier::all();
        return view('products.description', compact('products','products_count','request','supplier'));
       // dd($products);
    }

    public function productScrapLog(Request $request)
    {
        //dd($request->input());
        $products = Product::orderBy('updated_at', 'DESC');

        if($request->get('product_id') != ''){
           // $products = Product::where('id',$request->get('product_id'))->get();
        }
        if ($request->get('select_date') != '') {
            $date = $request->get('select_date');
        }else{
            $date = date('Y-m-d');
        }
        $statusarray = array();
        if ($request->get('status') != '') {
            $statusarray = [$request->get('status')];
        }else{
            $statusarray = [2,4,9,15,20,33,35,36,38,39,40];
        }

        $products = $products->whereHas('productstatushistory',function($query) use($date,$statusarray,$request){
            $query->whereDate('created_at',$date);
            $query->whereIn('new_status',$statusarray);
            if($request->get('product_id') != ''){
             $query->where('product_id',$request->get('product_id'));
            }
        })->with(['productstatushistory'=>function($query) use($date,$statusarray,$request){
            $query->whereDate('created_at',$date);
            $query->whereIn('new_status',$statusarray);
            if($request->get('product_id') != ''){
                $query->where('product_id',$request->get('product_id'));
               }
        }]);

        $products_count = $products->count();
        
        $products = $products->paginate(50);

        $products->getCollection()->transform(function($getproduct){
            $getproduct->total_count = $getproduct->productstatushistory->count();
            $history_log = array();
            foreach($getproduct->productstatushistory->toArray() as $key=>$history){
                $history["created_at"]= Carbon::parse($history["created_at"])->format('H:i');
                $history_log[$history['new_status']][] = $history;
            }
            $getproduct->alllog_status = $history_log;
            return $getproduct;
        });
        //$allproduct = Product::select('name','id')->get();

        $status = \App\Helpers\StatusHelper::getStatus();
        //dd($status);
        //echo "<pre>";
       //  print_r($products->toArray());
         return view('products.statuslog', compact('products', 'request','status','products_count','request'));
    }

    public function productStats(Request $request)
    {
        $products = Product::orderBy('updated_at', 'DESC');

        if ($request->get('status') != '') {
            $status = $request->get('status') == 'approved' ? 1 : 0;
            $products = $products->where('is_approved', $status);
        }
        if ($request->has('user_id') >= 1) {
            $products = $products->where(function ($query) use ($request) {
                $query->where('approved_by', $request->get('user_id'))
                    ->orWhere('crop_approved_by', $request->get('user_id'))
                    ->orWhere('listing_rejected_by', $request->get('user_id'))
                    ->orWhere('crop_rejected_by', $request->get('user_id'))
                    ->orWhere('crop_ordered_by', $request->get('user_id'));
            });
        }
        $sku = '';

        if ($request->get('sku') != '') {
            $sku = $request->get('sku');
            $products = $products->where('sku', 'LIKE', "%$sku%");
        }

        if ($request->get('range_start') != '') {
            $products = $products->where(function ($query) use ($request) {
                $query->where('crop_approved_at', '>=', $request->get('range_start'))
                    ->orWhere('listing_approved_at', '>=', $request->get('range_start'))
                    ->orWhere('listing_rejected_on', '>=', $request->get('range_start'))
                    ->orWhere('crop_ordered_at', '>=', $request->get('range_start'))
                    ->orWhere('crop_rejected_at', '>=', $request->get('range_start'));
            });
        }
        if ($request->get('range_end') != '') {
            $products = $products->where(function ($query) use ($request) {
                $query->where('crop_approved_at', '<=', $request->get('range_end'))
                    ->orWhere('listing_approved_at', '<=', $request->get('range_end'))
                    ->orWhere('listing_rejected_on', '<=', $request->get('range_end'))
                    ->orWhere('crop_ordered_at', '<=', $request->get('range_end'))
                    ->orWhere('crop_rejected_at', '<=', $request->get('range_end'));
            });
        }

        $products = $products->paginate(50);
        $users = User::all();

        return view('products.stats', compact('products', 'sku', 'users', 'request'));
    }

    public function showSOP(Request $request)
    {
        $sopType = $request->get('type');
        $sop = Sop::where('name', $sopType)->first();

        if (!$sop) {
            $sop = new Sop();
            $sop->name = $request->name;
            $sop->content = $request->content;
         
            $sop->save();
        }

        return view('products.sop', compact('sop'));
        
    }

    function getdata(Request $request){
        
         $usersop = Sop::with('purchaseProductOrderLogs');
  
    if($request->search){

        $usersop = $usersop->where('name', 'like', '%'.$request->search.'%');
    }

         $usersop = $usersop->paginate(15);

         $total_record = $usersop->total();
        
        return view('products.sop', compact('usersop','total_record'));

    }

    function sopnamedata_logs(Request $request){
      
        $log_data = PurchaseProductOrderLog::where('purchase_product_order_id',$request->id)
        ->join('users','purchase_product_order_logs.created_by','users.id')
        ->where('header_name',$request->header_name);

        $log_data = $log_data->orderBy('purchase_product_order_logs.id','ASC')
        ->select('purchase_product_order_logs.*','users.*','purchase_product_order_logs.created_at as log_created_at')
        ->get();

        return response()->json(['log_data' => $log_data ,'code' => 200]);

   }


    public function destroyname($id){
        $usersop =Sop::findOrFail($id);
        $usersop->delete();

        return response()->json([
            'message' => 'Data deleted Successfully!'
        ]);
        
     }
    
    public function saveSOP(Request $request)
    {
       
        $sopType = $request->get('type');
        $sop = Sop::where('name', $sopType)->first();

        if (!$sop) {
            $sop = new Sop();
            $sop->name = $request->get('name');
            $sop->content = $request->get('content');
            $sop->save();

           
            $params['purchase_product_order_id'] = $sop->id;
            $params['header_name'] = 'SOP Listing Approve Logs';
            $params['replace_from'] = '-';
            $params['replace_to'] = $request->get('name');
            $params['created_by'] = \Auth::id();

            $log = PurchaseProductOrderLog::create($params);
        }
       
        $only_date = $sop->created_at->todatestring();

          return response()->json(['only_date' => $only_date,'sop' => $sop, 'params' => $params]);
      }

    public function edit(Request $request)
    {
        
        $sopedit = Sop::findOrFail($request->id);
      
       return response()->json(['sopedit' => $sopedit]);
    }
    public function update(Request $request)
    {
        $sopedit =  Sop::findOrFail($request->id);

        $sopedit->name    = $request->get("name", "");
        $sopedit->content    = $request->get("content", "");
        $updatedSop =    $sopedit->save();
          
        $params['purchase_product_order_id'] = $request->id;
        $params['header_name'] = 'SOP Listing Approve Logs';
        $params['replace_from'] = $request->get("sop_old_name", "");
        $params['replace_to'] = $request->get("name", "");
        $params['created_by'] = \Auth::id();

        $log = PurchaseProductOrderLog::create($params);
     
        if ($sopedit) {
            return response()->json([
                'sopedit' => $sopedit,
                'params' => $params
            ]);
        }
    }

    public function searchsop(Request $request){
        
            $searchsop = $request->get('search');
            $usersop = DB::table('sops')->where('name', 'like', '%'.$searchsop.'%')->paginate(10);

        return view('products.sop', compact('usersop'));
    }

    public function getSupplierScrappingInfo(Request $request)
    {
        return View('scrap.supplier-info');
    }

    public function deleteImage()
    {
        $productId = request("product_id", 0);
        $mediaId = request("media_id", 0);
        $mediaType = request("media_type", "gallery");

        $cond = Db::table("mediables")->where([
            "media_id" => $mediaId,
            "mediable_id" => $productId,
            //"tag" => $mediaType,
            "mediable_type" => \App\Product::class
        ])->delete();

        if ($cond) {
            return response()->json(["code" => 1, "data" => []]);
        }

        return response()->json(["code" => 0, "data" => [], "message" => "No media found"]);

    }

    public function sendMessageSelectedCustomer(Request $request)
    {
        $params = request()->all();
        $params["user_id"] = \Auth::id();
        //$params["is_queue"] = 1;
        $params["status"] = \App\ChatMessage::CHAT_AUTO_BROADCAST;

        $token = request("customer_token", "");

        if (!empty($token)) {
            $customerIds = json_decode(session($token));
            if (empty($customerIds)) {
                $customerIds = [];
            }
        }
        // if customer is not available then choose what it is before
        if (empty($customerIds)) {
            $customerIds = $request->get('customers_id', '');
            $customerIds = explode(',', $customerIds);
        }

        $params["customer_ids"] = $customerIds;

        $groupId = \DB::table('chat_messages')->max('group_id');
        $params["group_id"] = ($groupId > 0) ? $groupId + 1 : 1;
        $params["is_queue"] = request("is_queue", 0);

        \App\Jobs\SendMessageToCustomer::dispatch($params)->onQueue("customer_message");

        if ($request->ajax()) {
            return response()->json(['msg' => 'success']);
        }

        if ($request->get('return_url')) {
            return redirect("/" . $request->get('return_url'));
        }

        return redirect('/erp-leads');


        /*$token = request("customer_token","");

        if(!empty($token)) {
            $customerIds = json_decode(session($token));
            if(empty($customerIds)) {
                $customerIds = [];
            }
        }
        // if customer is not available then choose what it is before
        if(empty($customerIds)) {
            $customerIds = $request->get('customers_id', '');
            $customerIds = explode(',', $customerIds);
        }

        $brand = request()->get("brand", null);
        $category = request()->get("category", null);
        $numberOfProduts = request()->get("number_of_products", 10);
        $quick_sell_groups = request()->get("quick_sell_groups", []);

        $product = new \App\Product;

        $toBeRun = false;
        if (!empty($brand)) {
            $toBeRun = true;
            $product = $product->where("brand", $brand);
        }

        if (!empty($category) && $category != 1) {
            $toBeRun = true;
            $product = $product->where("category", $category);
        }

        if (!empty($quick_sell_groups)) {
            $toBeRun = true;
            $quick_sell_groups = rtrim(ltrim($quick_sell_groups,","),",") ;
            $product = $product->whereRaw("(products.id in (select product_id from product_quicksell_groups where quicksell_group_id in (" . $quick_sell_groups . ") ))");
        }

        $extraParams = [];

        if ($toBeRun) {
            $limit = (!empty($numberOfProduts) && is_numeric($numberOfProduts)) ? $numberOfProduts : 10;
            $imagesQuery = $product->join("mediables as m", "m.mediable_id", "products.id")->select("media_id")->groupBy("products.id")
                ->limit($limit)
                ->get()->pluck("media_id")->toArray();
            if (!empty($imagesQuery)) {
                $extraParams[ "images" ] = json_encode(array_unique($imagesQuery));
            }
        }


        // get the status for approval
        $approveMessage = \App\Helpers\DevelopmentHelper::needToApproveMessage();

        $is_queue = 0;
        if ($approveMessage == 1) {
            $is_queue = 1;
        }

        $groupId = \DB::table('chat_messages')->max('group_id');
        $groupId = ($groupId > 0) ? $groupId : 1;

        foreach ($customerIds as $k => $customerId) {
            $requestData = new Request();
            $requestData->setMethod('POST');
            $params = $request->except(['_token', 'customers_id', 'return_url']);
            $params[ 'customer_id' ] = $customerId;
            $params[ 'is_queue' ] = $is_queue;
            $params[ 'group_id' ] = $groupId;
            $requestData->request->add($params + $extraParams);

            app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'customer');
        }

        \Log::info(print_r(\DB::getQueryLog(),true));*/


    }

    public function queueCustomerAttachImages(Request $request)
    {
        $data['_token'] = $request->_token;
        $data['send_pdf'] = $request->send_pdf;
        $data['pdf_file_name'] = !empty($request->pdf_file_name) ? $request->pdf_file_name : "";
        $data['images'] = $request->images;
        $data['image'] = $request->image;
        $data['screenshot_path'] = $request->screenshot_path;
        $data['message'] = $request->message;
        $data['customer_id'] = $request->customer_id;
        $data['status'] = $request->status;
        $data['type'] = $request->type;
        \App\Jobs\AttachImagesSend::dispatch($data)->onQueue("customer_message");

        $json = request()->get("json", false);

        if ($json) {
            return response()->json(["code" => 200]);
        }
        if ($request->get('return_url')) {
            return redirect($request->get('return_url'));
        }

        return redirect()->route('customer.post.show', $request->customer_id)->withSuccess('Message Send For Queue');
    }

    public function cropImage(Request $request)
    {
        $id = $request->id;
        $img = $request->img;
        $style = $request->style;
        $style = explode(' ', $style);
        $name = str_replace(['scale(', ')'], '', $style[4]);
        $newHeight = (($name * 3.333333) * 1000);
        list($width, $height) = getimagesize($img);
        $thumb = imagecreatetruecolor($newHeight, $newHeight);
        try {
            $source = imagecreatefromjpeg($img);
        } catch (\Exception $e) {
            $source = imagecreatefrompng($img);
        }


        // Resize
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newHeight, $newHeight, $width, $height);

        $thumbWidth = imagesx($thumb);
        $thumbHeight = imagesy($thumb);


        $canvasImage = imagecreatetruecolor(1000, 1000); // Creates a black image

        // Fill it with white (optional)
        $gray = imagecolorallocate($canvasImage, 227, 227, 227);
        imagefill($canvasImage, 0, 0, $gray);

        imagecopy($canvasImage, $thumb, (1000 - $thumbWidth) / 2, (1000 - $thumbHeight) / 2, 0, 0, $thumbWidth, $thumbHeight);
        // $url = env('APP_URL');
        $url = config('env.APP_URL');
        $path = str_replace($url, '', $img);

        imagejpeg($canvasImage, public_path() . '/' . $path);
        $product = Product::find($id);
        return response()->json(['success' => 'success', 200]);
    }

    public function hsCodeIndex(Request $request)
    {

        if ($request->category || $request->keyword) {
            $products = Product::select('composition', 'category')->where('composition', 'LIKE', '%' . request('keyword') . '%')->where('category', $request->category[0])->groupBy('composition')->get();

            foreach ($products as $product) {

                if ($product->category != null) {
                    $categoryTree = CategoryController::getCategoryTree($product->category);
                    if (is_array($categoryTree)) {

                        $childCategory = implode(' > ', $categoryTree);
                    }

                    $cat = Category::findOrFail($request->category[0]);
                    $parentCategory = $cat->title;

                    if ($product->composition != null) {
                        if ($request->group == 'on') {
                            $composition = strip_tags($product->composition);
                            $compositions[] = str_replace(['&nbsp;', '/span>'], ' ', $composition);
                        } else {
                            if ($product->isGroupExist($product->category, $product->composition, $parentCategory, $childCategory)) {
                                $composition = strip_tags($product->composition);
                                $compositions[] = str_replace(['&nbsp;', '/span>'], ' ', $composition);

                            }
                        }

                    }

                }

            }
            if (!isset($compositions)) {
                $compositions = [];
                $childCategory = '';
                $parentCategory = '';
            }
            $keyword = $request->keyword;
            $groupSelected = $request->group;

        } else {
            $keyword = '';
            $compositions = [];
            $childCategory = '';
            $parentCategory = '';
            $groupSelected = '';
        }
        $selected_categories = $request->category ? $request->category : 1;

        $category_selection = Category::attr(['name' => 'category[]', 'class' => 'form-control select-multiple2', 'id' => 'category_value'])
            ->selected($selected_categories)
            ->renderAsDropdown();
        $hscodes = SimplyDutyCategory::all();
        $categories = Category::all();
        $groups = HsCodeGroup::all();
        $cate = HsCodeGroupsCategoriesComposition::groupBy('category_id')->pluck('category_id')->toArray();
        $pendingCategory = Category::all()->except($cate);
        $pendingCategoryCount = $pendingCategory->count();
        $setting = HsCodeSetting::first();
        $countries = SimplyDutyCountry::all();

        return view('products.hscode', compact('keyword', 'compositions', 'childCategory', 'parentCategory', 'category_selection', 'hscodes', 'categories', 'groups', 'groupSelected', 'pendingCategoryCount', 'setting', 'countries'));
    }

    public function saveGroupHsCode(Request $request)
    {

        $name = $request->name;
        $compositions = $request->compositions;
        $key = HsCodeSetting::first();
        if ($key == null) {
            return response()->json(['Please Update the Hscode Setting']);
        }
        $api = $key->key;
        $fromCountry = $key->from_country;
        $destinationCountry = $key->destination_country;
        if ($api == null || $fromCountry == null || $destinationCountry == null) {
            return response()->json(['Please Update the Hscode Setting']);
        }
        $category = Category::select('id', 'title')->where('id', $request->category)->first();
        $categoryId = $category->id;


        if ($request->composition) {
            $hscodeSearchString = str_replace(['&gt;', '>'], '', $name . ' ' . $category->title . ' ' . $request->composition);
        } else {
            $hscodeSearchString = str_replace(['&gt;', '>'], '', $name);
        }

        $hscode = HsCode::where('description', $hscodeSearchString)->first();

        if ($hscode != null) {
            return response()->json(['error' => 'HsCode Already exist']);
        }

        $hscodeSearchString = urlencode($hscodeSearchString);

        $searchString = 'https://www.api.simplyduty.com/api/classification/get-hscode?APIKey=' . $api . '&fullDescription=' . $hscodeSearchString . '&originCountry=' . $fromCountry . '&destinationCountry=' . $destinationCountry . '&getduty=false';

        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $searchString);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        $categories = json_decode($output);

        if (!isset($categories->HSCode)) {

            return response()->json(['error' => 'Something is wrong with the API. Please check the balance.']);

        } else {


            if ($categories->HSCode != null) {

                $hscode = new HsCode();
                $hscode->code = $categories->HSCode;
                $hscode->description = urldecode($hscodeSearchString);
                $hscode->save();

                if ($request->existing_group != null) {
                    $group = HsCodeGroup::find($request->existing_group);
                } else {
                    $group = new HsCodeGroup();
                    $group->hs_code_id = $hscode->id;
                    $group->name = $name . ' > ' . $category->title;
                    $group->composition = $request->composition;
                    $group->save();
                }

                $id = $group->id;
                if ($request->compositions) {
                    foreach ($compositions as $composition) {
                        $comp = new HsCodeGroupsCategoriesComposition();
                        $comp->hs_code_group_id = $id;
                        $comp->category_id = $categoryId;
                        $comp->composition = $composition;
                        $comp->save();
                    }
                }

            }
        }


        return response()->json(['Hscode Generated successfully'], 200);
    }

    public function editGroup(Request $request)
    {

        $group = HsCodeGroup::find($request->id);
        $group->hs_code_id = $request->hscode;
        $group->name = $request->name;
        $group->composition = $request->composition;
        $group->save();

        return response()->json(['success' => 'success'], 200);
    }

    public function productTranslation(Request $request)
    {
        $term = $request->term;
        $language = $request->language;
        $is_rejected = $request->input('is_rejected', '0');
        //$query = Product_translation::where('locale','en'); //OLD
        $query = new Product_translation();
        if(!empty($term)){
            $query = $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->term . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->term . '%') ;
            });
        }
        if(!empty($language)){
            $query = $query->where(function ($q) use ($request) {
                $q->Where('locale', 'LIKE', '%' . $request->language . '%');
            });
        }

//        if ($is_rejected !== null) {
        if ($request->has('is_rejected')) {
            $query = $query->where(function ($q) use ($is_rejected) {
                $q->Where('is_rejected',$is_rejected);
            });
        }

        $product_translations = $query->orderBy('product_id', 'desc')->paginate(10)->appends(request()->except(['page']));//catch 2

        $product_translation_history = ProductTranslationHistory::get();

        $languages = TranslationLanguage::get();

        $all_languages = Language::get();

        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('products.translations.product-search', compact('product_translations', 'term', 'language', 'is_rejected'))->with('i', ($request->input('page', 1) - 1) * 5)->render(),
                'links' => (string)$product_translations->render()
            ], 200);
        }
        return view('products.translations.product-list', compact('product_translations', 'term', 'language', 'languages', 'all_languages', 'product_translation_history'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function translationLanguage(ProductTranslationRequest $request)
    {
        $this->validate($request, [
            'locale'   => 'sometimes|nullable|string|max:255',
            'code'       => 'required'
        ]);

        $data = $request->except('_token');
        Language::create($data);

//        return redirect()->route('products.product-translation')->withSuccess('You have successfully stored language');

//        TranslationLanguage::create([
//            'locale' => $request->input('locale')
//        ]);
//
        return response()->json([
            'message' => 'Successfully updated the data'
        ]);
    }

    public function productTranslationRejection(Request $request){

        $product_translation = Product_translation::find($request->product_translation_id);
        $product_translation->is_rejected = $request->value;
        $product_translation->save();
        $product_translation_history = new ProductTranslationHistory;
        $product_translation_history->is_rejected = $request->value;
        $product_translation_history->user_id = Auth::user()->id;
        $product_translation_history->product_translation_id = $request->product_translation_id;
        $product_translation_history->save();

        return response()->json([
            'message' => 'Rejected Successfully',
            'value' => $request->value,
        ]);
    }

    public function viewProductTranslation($id)
    {
        $locales = Product_translation::groupBy('locale')->pluck('locale');
        $languages = Language::get();
        $sites = StoreWebsite::get();
        $product_translation = Product_translation::find($id);
        return view('products.translations.view-or-edit', [
            'product_translation' => $product_translation,
            'locales' => $locales,
            'sites' => $sites,
            'languages' => $languages
        ]);
    }


    public function getProductTranslationDetails($id, $locale)
    {
        $product_translation = Product_translation::where('product_id', $id)->where('locale', $locale)->first();
        return response()->json([
            'product_translation' => $product_translation
        ]);
    }

    public function editProductTranslation($id, Request $request)
    {
        Product_translation::where('id', $id)->update(['locale' => $request->language, 'title' => $request->title, 'description' => $request->description, 'site_id' => $request->site_id]);
        ProductTranslationHistory::insert([
            'user_id' => Auth::user()->id,
            'product_translation_id' => $id,
            'locale' => $request->language,
            'title' => $request->title,
            'description' => $request->description
        ]);
        return response()->json([
            'message' => 'Successfully updated the data'
        ]);
    }

    public function published(Request $request)
    {
        $id = $request->get("id");
        $website = $request->get("website", []);

        \App\WebsiteProduct::where("product_id", $id)->delete();

        if (!empty($website)) {
            foreach ($website as $web) {
                $website = new \App\WebsiteProduct;
                $website->product_id = $id;
                $website->store_website_id = $web;
                $website->save();
            }
        }

        return response()->json(["code" => 200]);

    }

    public function originalColor($id)
    {
        $product = Product::find($id);
        $referencesColor = "";
        if (isset($product->scraped_products)) {

            // starting to see that howmany color we going to update
            if (isset($product->scraped_products->properties) && isset($product->scraped_products->properties['colors']) != null) {
                $color = $product->scraped_products->properties['colors'];
                if (is_array($color)) {
                    $referencesColor = implode(' > ', $color);
                } else {
                    $referencesColor = $color;
                }
            }

            // starting to see that howmany color we going to update
            if (isset($product->scraped_products->properties) && isset($product->scraped_products->properties['color']) != null) {
                $color = $product->scraped_products->properties['color'];
                if (is_array($color)) {
                    $referencesColor = implode(' > ', $color);
                } else {
                    $referencesColor = $color;
                }
            }

            $scrapedProductSkuArray = [];

            if (!empty($referencesColor)) {
                $productSupplier = $product->supplier;
                $supplier = Supplier::where('supplier', $productSupplier)->first();
                if ($supplier && $supplier->scraper) {
                    $scrapedProducts = ScrapedProducts::where('website', $supplier->scraper->scraper_name)->get();
                    foreach ($scrapedProducts as $scrapedProduct) {
                        if (isset($scrapedProduct->properties['color'])) {
                            $products = $scrapedProduct->properties['color'];
                            if (!empty($products)) {
                                $scrapedProductSkuArray[] = $scrapedProduct->sku;
                            }
                        }

                        if (isset($scrapedProduct->properties['colors'])) {
                            $products = $scrapedProduct->properties['colors'];
                            if (!empty($products)) {
                                $scrapedProductSkuArray[] = $scrapedProduct->sku;
                            }
                        }

                    }
                }
            }

            if (isset($product->scraped_products->properties) && isset($product->scraped_products->properties['colors']) != null) {
                return response()->json(['success', $referencesColor, count($scrapedProductSkuArray)]);
            } else {
                return response()->json(['message', 'Color Is Not Present']);
            }

        } else {
            return response()->json(['message', 'Color Is Not Present']);
        }
    }

    public function changeAllColorForAllSupplierProducts(Request $request, $id)
    {
        \App\Jobs\UpdateScrapedColor::dispatch([
            "product_id" => $id,
            "color" => $request->color,
            "user_id" => \Auth::user()->id
        ])->onQueue("supplier_products");

        return response()->json(['success', 'Product color has been sent for the update']);
    }

    public function storeWebsiteDescription(Request $request)
    {
        $websites = $request->store_wesites;
        if (is_array($websites) && $request->product_id != null && $request->description != null) {
            foreach ($websites as $website) {
                $storeWebsitePA = \App\StoreWebsiteProductAttribute::where("product_id", $request->product_id)->where("store_website_id", $website)->first();
                if (!$storeWebsitePA) {
                    $storeWebsitePA = new \App\StoreWebsiteProductAttribute;
                    $storeWebsitePA->product_id = $request->product_id;
                    $storeWebsitePA->store_website_id = $website;
                }
                $storeWebsitePA->store_website_id = $website;
                $storeWebsitePA->description = $request->description;
                $storeWebsitePA->save();

                return response()->json(["code" => 200, "data" => [], "message" => "Store website description stored successfully"]);
            }
        }

        return response()->json(["code" => 500, "data" => [], "message" => "Required field is missing"]);
    }

    public function changeAutoPushValue(Request $request)
    {
        if(Setting::get('auto_push_product') == 0){
            $val = 1;
        }else{
            $val = 0;
        }
        $settings = Setting::set('auto_push_product', $val, 'int');
        return response()->json(["code" => 200, "data" => $settings, "message" => "Status changed"]);
    }

    public function pushProduct()
    {

        /*$webData = StoreWebsite::select(['store_websites.id', DB::raw('store_website_brands.brand_id as brandId'), 'store_website_categories.*'])
            ->join('store_website_brands', 'store_websites.id', 'store_website_brands.store_website_id')
            ->join('store_website_categories', 'store_websites.id', 'store_website_categories.store_website_id')
            ->where("website_source", "!=", "")
            ->get();

        $brandIds = array_unique($webData->pluck('brandId')->toArray());
        $categoryIds = array_unique($webData->pluck('category_id')->toArray());*/
        
        $products = Product::select('*')->where("short_description", "!=", "")->where("name", "!=", "")->where("status_id", StatusHelper::$finalApproval)
        ->groupBy("brand", "category")
        ->limit(100)
        ->get();

        $queueName = [
            "1" => "mageone",
            "2" => "magetwo",
            "3" => "magethree"
        ];


        foreach ($products as $key => $product) {
            $websiteArrays = ProductHelper::getStoreWebsiteName($product->id);
            if(!empty($websiteArrays)) {
                $i = 1;
                foreach ($websiteArrays as $websiteArray) {
                    $website = StoreWebsite::find($websiteArray);
                    if($website){
                        \Log::info("Product started website found For website".$website->website);
                        $log = LogListMagento::log($product->id, "Start push to magento for product id " . $product->id, 'info',$website->id);
                        //currently we have 3 queues assigned for this task.
                        if($i > 3) {
                           $i = 1;
                        }
                        PushToMagento::dispatch($product,$website, $log)->onQueue($queueName[$i]);
                        $i++;
                    }
                }
            }
        }
        return response()->json(["code" => 200, "message" => "Push product successfully"]);

    }

    public function getPreListProducts()
    {

        $newProducts = Product::where('status_id', StatusHelper::$finalApproval);
        $newProducts = QueryHelper::approvedListingOrder($newProducts);

        $newProducts = $newProducts->select(DB::raw("brand,category,assigned_to,count(*) as total"))
            ->groupBy('brand', 'category', 'assigned_to')->paginate(50);
        foreach ($newProducts as $product) {
            if ($product->brand) {
                $brand = Brand::find($product->brand);
                if ($brand) {
                    $product->brandName = $brand->name;
                } else {
                    $product->brandName = '';
                }
            } else {
                $product->brandName = '';
            }
            if ($product->category) {
                $category = Category::find($product->category);
                if ($category) {
                    $product->categoryName = $category->title;
                } else {
                    $product->categoryName = '';
                }
            } else {
                $product->categoryName = '';
            }
            if ($product->assigned_to) {
                $product->assignTo = User::find($product->assigned_to)->name;
            } else {
                $product->assignTo = '';
            }
        }
        $users = User::all()->pluck('name', 'id')->toArray();
        return view('products.assign-products', compact('newProducts', 'users'));
    }

    public function assignProduct(Request $request)
    {

        $category = $request->category;
        $brand = $request->brand;
        $assigned_to = $request->assigned_to;
        if (!$assigned_to) {
            return response()->json(['message' => 'Select one user'], 500);
        }
        $products = Product::where('status_id', StatusHelper::$finalApproval)->where('category', $category)->where('brand', $brand);

        $products = QueryHelper::approvedListingOrder($products);
        $products = $products->get();
        foreach ($products as $product) {
            $product->update(['assigned_to' => $assigned_to]);
        }


        $data['assign_from'] = Auth::id();
        $data['is_statutory'] = 2;
        $data['task_details'] = 'Final Approval Assignment';
        $data['task_subject'] = 'Final Approval Assignment';
        $data['assign_to'] = $assigned_to;


        $task = Task::create($data);
        if (!empty($task)) {
            $task->users()->attach([$data['assign_to'] => ['type' => User::class]]);
        }

        if ($task->is_statutory != 1) {
            $message = "#" . $task->id . ". " . $task->task_subject . ". " . $task->task_details;
        } else {
            $message = $task->task_subject . ". " . $task->task_details;
        }

        $params = [
            'number' => NULL,
            'user_id' => Auth::id(),
            'approved' => 1,
            'status' => 2,
            'task_id' => $task->id,
            'message' => $message
        ];

        // if ($task->assign_from == Auth::id()) {
        //          if ($key == 0) {
        //              $params['erp_user'] = $user->id;
        //          } else {
        //              app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message']);
        //          }
        //  }
        $user = User::find($assigned_to);
        $params['erp_user'] = $assigned_to;
        app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message']);

        $chat_message = ChatMessage::create($params);

        $myRequest = new Request();
        $myRequest->setMethod('POST');
        $myRequest->request->add(['messageId' => $chat_message->id]);
        app('App\Http\Controllers\WhatsAppController')->approveMessage('task', $myRequest);

        $username = $user->name;
        return response()->json(['message' => 'Successful', 'user' => $username]);
    }

    public function draftedProducts(Request $request)
    {
        \Log::info("action started");
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

        \Log::info("Page Displayed here");

        return view('drafted-supplier-product.index', compact('products'));
    }

    public function editDraftedProduct(Request $request)
    {
        $product = Product::where("id", $request->id)->first();
        return view('drafted-supplier-product.edit-modal', ['product' => $product]);
    }

    public function deleteDraftedProducts(Request $request)
    {
        $productIds = $request->products;
        if (!empty($productIds)) {
            $products = \App\Product::whereIn("id", $productIds)->get();
            if (!$products->isEmpty()) {
                foreach ($products as $product) {
                    $product->delete();
                }
            }
        }
        return response()->json(["code" => 200, "data" => [], "message" => 'Successfully deleted!']);
    }

    public function editDraftedProducts(Request $request)
    {
        $draftedProduct = Product::where("id", $request->id)->first();

        if ($draftedProduct) {
            $draftedProduct->fill($request->all());
            $draftedProduct->save();
            return response()->json(["code" => 200, "data" => $draftedProduct, "message" => 'Successfully edited!']);
        }

        return response()->json(["code" => 500, "error" => "Wrong row id!"]);
    }

    public function updateApprovedBy(Request $request, $product_id)
    {

        $product = Product::find($product_id);

        if ($product) {
            $product->update([
                'is_approved' => 1,
                'approved_by' => $request->user_id
            ]);
        }

        return response()->json([
            "code" => 200
        ]);

    }
    
    public function createTemplate(Request $request)
    {   

        $this->validate($request, [
            'template_no'   => 'required',
            'product_media_id'   => 'required',
            'background'   => 'required',
            'text'   => 'required',
        ]);

        $product_media_id = explode(',', $request->product_media_id);
        $template = new \App\ProductTemplate;
        $template->template_no = $request->template_no;
        $template->text = $request->text;
        $template->background_color = $request->background;
        $template->template_status = 'python';

        if ($template->save()) {

            if (!empty($request->get('product_media_id')) && is_array($request->get('product_media_id'))) {
                foreach ($request->get('product_media_id') as $mediaid) {
                    $media = Media::find($mediaid);
                    $template->attachMedia($media, ['template-image-attach']);
                    $template->save();
                    $imagesArray[]=$media->getUrl();
                }
            }

            return redirect()->back()->with('success','Template has been created successfully');
        }
        return redirect()->back()->with('error','Something went wrong, Please try again!');
    }

    public function attachedImageGrid($model_type = null, $model_id = null, $status = null, $assigned_user = null, Request $request) {
        $model_type = 'customer';
        if ($model_type == 'customer') {
            $customerId = $model_id;
        } else {
            $customerId = null;
        }
        if($request->customer_id) {
            $explode = explode('/',$request->customer_id);
            if(count($explode) > 1) {
                $customerId =  $explode[1];
            }
        }

        if ($request->category) {
            try {
                $filtered_category = $request->category;
            } catch (\Exception $e) {
                $filtered_category = [];
            }
        } else {
            $filtered_category = [];
        }
        $category_selection = Category::attr(['name' => 'category[]', 'class' => 'form-control select-multiple-cat-list input-lg select-multiple', 'multiple' => true, 'data-placeholder' => 'Select Category..'])
                ->selected($filtered_category)
                ->renderAsDropdown();



        //\DB::enableQueryLog();
        $roletype = $request->input('roletype') ?? 'Sale';
        $term = $request->input('term');
        if ($request->total_images) {
            $perPageLimit = $request->total_images;
        } else {
            $perPageLimit = $request->get("per_page");
        }


        // if (Order::find($model_id)) {
        //     $selected_products = self::getSelectedProducts($model_type, $model_id);
        // } else {
        //     $selected_products = [];
        // }

        if (empty($perPageLimit)) {
            $perPageLimit = Setting::get('pagination');
        }

        // $sourceOfSearch = $request->get("source_of_search", "na");

        // start add fixing for the price range since the one request from price is in range
        // price  = 0 , 100

        // $priceRange = $request->get("price", null);

        // if ($priceRange && !empty($priceRange)) {
        //     @list($minPrice, $maxPrice) = explode(",", $priceRange);
        //     // adding min price
        //     if (isset($minPrice)) {
        //         $request->request->add(['price_min' => $minPrice]);
        //     }
        //     // addin max price
        //     if (isset($maxPrice)) {
        //         $request->request->add(['price_max' => $maxPrice]);
        //     }
        // }
        $suggestedProducts = \App\SuggestedProduct::with('customer')->leftJoin("suggested_product_lists as spl","spl.suggested_products_id", "suggested_products.id");
        $suggestedProducts = $suggestedProducts->leftJoin("products as p","spl.product_id", "p.id");
        $suggestedProducts = $suggestedProducts->leftJoin("customers as c","c.id", "suggested_products.customer_id");
        if($customerId) {
            $suggestedProducts = $suggestedProducts->where('suggested_products.customer_id',$customerId);
        }


        if($request->category != null) {
            $suggestedProducts = $suggestedProducts->whereIn("p.category",$request->category);
        }

        if($request->brand != null) {
            $suggestedProducts = $suggestedProducts->whereIn("p.brand",$request->brand);
        }

        if(!empty($term)) {
            $suggestedProducts = $suggestedProducts->where(function($q) use($term){
                $q->orWhere("p.sku","LIKE","%".$term."%")->orWhere("p.id","LIKE","%".$term."%");
            });
        }

        $loggedInUser = auth()->user();
        $isInCustomerService = $loggedInUser->isInCustomerService();
        if($isInCustomerService) {
            $suggestedProducts = $suggestedProducts->where('c.user_id',$loggedInUser->id);
        }

        // $perPageLimit
        //$suggestedProducts = $suggestedProducts->select(DB::raw('*, max(created_at) as created_at'))->orderBy('created_at','DESC')->groupBy('customer_id')->paginate($perPageLimit);
        $suggestedProducts = $suggestedProducts->select(DB::raw('suggested_products.*, max(suggested_products.created_at) as created_at'))->orderBy('suggested_products.created_at','DESC')->groupBy('suggested_products.id')->paginate($perPageLimit);
        
        foreach($suggestedProducts as $suggestion) {
            //$last_attached = \App\SuggestedProductList::where('customer_id',$suggestion->customer_id)->orderBy('date','desc')->first();
            $last_attached = \App\SuggestedProductList::where('suggested_products_id',$suggestion->id)->orderBy('date','desc')->first();
            if($last_attached) {
                $suggestion->last_attached = $last_attached->date;
            }
            else {
                $suggestion->last_attached = $suggestion->created_at;
            }
            $brandIds = \App\SuggestedProductList::join('products','suggested_product_lists.product_id','products.id')->where('suggested_product_lists.customer_id',$suggestion->customer_id)->where('suggested_products_id',$suggestion->id)->groupBy('products.brand')->pluck('products.brand');
            if(count($brandIds) > 0) {
                $suggestion->brdNames = Brand::whereIn('id',$brandIds)->get();
            }
            else {
                $suggestion->brdNames = []; 
            }

            $catIds = \App\SuggestedProductList::join('products','suggested_product_lists.product_id','products.id')->where('suggested_product_lists.customer_id',$suggestion->customer_id)->where('suggested_products_id',$suggestion->id)->groupBy('products.category')->pluck('products.category');
            if(count($catIds) > 0) {
                $suggestion->catNames = Category::whereIn('id',$catIds)->get();
            }
            else {
                $suggestion->catNames = []; 
            }
        }

        $templateArr = \App\Template::all();

        $all_product_ids = [];
        $model_type = 'customer';
        $countBrands = 0;
        $countCategory = 0;
        $countSuppliers = 0;
        $categoryArray = [];
        $from = '';
        $products_count = 0;
        $selected_products = [];
        $brand = $request->brand;
        if ($request->ajax()) {
            $html = view('partials.attached-image-load', [
                'suggestedProducts' => $suggestedProducts,
                'all_product_ids' => $all_product_ids,
                'brand' => $brand,
                'selected_products' => $request->selected_products ? json_decode($request->selected_products) : [],
                'model_type' => $model_type,
                'countBrands' => $countBrands,
                'countCategory' => $countCategory,
                'countSuppliers' => $countSuppliers,
                'customerId' => $customerId,
                'categoryArray' => $categoryArray,
                    ])->render();
            if (!empty($from) && $from == "attach-image") {
                return $html;
            }

            // return response()->json(['html' => $html, 'products_count' => $products_count]);

            $selected_products = $request->selected_products ? json_decode($request->selected_products) : [];
            return view('partials.attached-image-load', compact(
                'suggestedProducts','all_product_ids','brand','selected_products','model_type','countBrands','countCategory','countSuppliers','customerId','categoryArray'));
        }

        $message_body = $request->message ? $request->message : '';
        $sending_time = $request->sending_time ?? '';

        $locations = \App\ProductLocation::pluck("name", "name");
        $suppliers = Supplier::select(['id', 'supplier'])->whereIn('id', DB::table('product_suppliers')->selectRaw('DISTINCT(`supplier_id`) as suppliers')->pluck('suppliers')->toArray())->get();

        $quick_sell_groups = \App\QuickSellGroup::select('id', 'name')->orderBy('id', 'desc')->get();
        $customers = \App\Customer::pluck('name','id');
        return view('partials.attached-image-grid', compact(
                        'suggestedProducts', 'templateArr', 'products_count', 'roletype', 'model_id', 'selected_products', 'model_type', 'status', 'assigned_user', 'category_selection', 'brand', 'filtered_category', 'message_body', 'sending_time', 'locations', 'suppliers', 'all_product_ids', 'quick_sell_groups', 'countBrands', 'countCategory', 'countSuppliers', 'customerId', 'categoryArray', 'term','customers'
        ));
    }
    public function crop_rejected_status(Request $request)
    {
        if($request->status == 'reject') {
            $lastPriorityScrap = Product::orderBy('scrap_priority','desc')->first();
            if($lastPriorityScrap) {
                if($lastPriorityScrap->scrap_priority) {
                    $lastPriority = $lastPriorityScrap->scrap_priority + 1;
                }
                else {
                    $lastPriority = 1;
                }  
            }
            else {
                $lastPriority = 1;
            }
            
            Product::where('id', $request->product_id)->update(['status_id' => StatusHelper::$autoCrop, 'scrap_priority' => $lastPriority]);
            SiteCroppedImages::where('product_id', $request->product_id)->where('website_id' , $request->site_id)->delete();
        }
        RejectedImages::updateOrCreate(
            ['website_id' => $request->site_id, 'product_id' => $request->product_id],
            ['status' => $request->status = "approve" ? 1 : 0]
        );
        return response()->json(['code' => 200, 'message' => 'Successfully rejected']);
    }

    public function all_crop_rejected_status(Request $request)
    {
        if($request->status == 'reject') {
            $lastPriorityScrap = Product::orderBy('scrap_priority','desc')->first();
            if($lastPriorityScrap) {
                if($lastPriorityScrap->scrap_priority) {
                    $lastPriority = $lastPriorityScrap->scrap_priority + 1;
                }
                else {
                    $lastPriority = 1;
                }  
            }
            else {
                $lastPriority = 1;
            }
            $sites = SiteCroppedImages::where('product_id', $request->product_id)->get();
            foreach($sites as $site) {
                RejectedImages::updateOrCreate(
                    ['website_id' => $site->website_id, 'product_id' => $request->product_id],
                    ['status' => $request->status = "approve" ? 1 : 0]
                );
            }
            
            Product::where('id', $request->product_id)->update(['status_id' => StatusHelper::$autoCrop, 'scrap_priority' => $lastPriority]);
            SiteCroppedImages::where('product_id', $request->product_id)->delete();
        }
        return response()->json(['code' => 200, 'message' => 'Successfully rejected']);
    }
    
    public function attachMoreProducts($suggested_products_id)
    {
        
        /* $lastSuggestion = \App\SuggestedProduct::where('customer_id',$customerId)->orderBy('created_at','desc')->first(); */

        $lastSuggestion = \App\SuggestedProduct::where('id',$suggested_products_id)->orderBy('created_at','desc')->first();
        $customerId=$lastSuggestion->customer_id; 
        $brands = [];
        $categories = [];
        $term = '';
        $limit = 10;
        if($lastSuggestion) {
            if($lastSuggestion->brands) {
                $brands = json_decode($lastSuggestion->brands);
            }
            if($lastSuggestion->categories) {
                $categories = json_decode($lastSuggestion->categories);
            }
            $term = $lastSuggestion->keyword;
            $limit = $lastSuggestion->total;
        }
        /* $remove_ids = \App\SuggestedProductList::where('customer_id',$customerId)->pluck('product_id as id'); */

        $remove_ids = \App\SuggestedProductList::where('suggested_products_id',$suggested_products_id)->pluck('product_id as id');

        $products = (new Product())->newQuery()->latest();

        if (count($brands) > 0) {
            $products = $products->whereIn('brand', $brands);
        }

        if (count($categories) > 0) {

                $category_children = [];

                foreach ($categories as $category) {

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

                        array_push($category_children, $category);

                    } else {
                        array_push($category_children, $category);
                    }
                }

                $products = $products->whereIn('category', $category_children);
        }

        if (trim($term) != '') {
            $products = $products->where(function ($query) use ($term) {
                $query->where('sku', 'LIKE', "%$term%")
                    ->orWhere('id', 'LIKE', "%$term%")
                    ->orWhere('name', 'LIKE', "%$term%")
                    ->orWhere('short_description', 'LIKE', "%$term%");
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
        }
        if(count($remove_ids) > 0) {
            $products = $products->whereNotIn("products.id", $remove_ids);
        }
        
        // select fields..
        $products = $products->select(['products.id', 'name', 'short_description', 'color', 'sku', 'products.category', 'products.size', 'price_eur_special', 'price_inr_special', 'supplier', 'purchase_status', 'products.created_at']);

        $products = $products->paginate($limit);

            if(count($products) > 0) {
                $data_to_insert = [];
                foreach($products as $product){
                    $exists = \App\SuggestedProductList::where('suggested_products_id',$suggested_products_id)->where('customer_id',$customerId)->where('product_id',$product->id)->where('date',date('Y-m-d'))->first();
                    if(!$exists) {
                        $pr = Product::find($product->id);
                        if($pr->hasMedia(config('constants.attach_image_tag'))) {
                        $data_to_insert[] = [
                            'suggested_products_id'=>$suggested_products_id,
                            'customer_id' => $customerId,
                            'product_id' => $product->id,
                            'date' => date('Y-m-d')
                        ];
                    }
                    }
                }
                if(count($data_to_insert) > 0) {
                    \App\SuggestedProductList::insert($data_to_insert);
                } 
            }
            $url = '/attached-images-grid/customer?customer_id='.$customerId;
        return response()->json(['message' => 'Successfull','url' => $url, 'code' => 200]);
    }

    
    public function suggestedProducts($model_type = null, $model_id = null, $status = null, $assigned_user = null, Request $request)
    {
        $model_type = 'customer';
        $customerId = null;
        if($request->customer_id) {
            $explode = explode('/',$request->customer_id);
            if(count($explode) > 1) {
                $customerId =  $explode[1];
            }
        }
        $roletype = $request->input('roletype') ?? 'Sale';
        $term = $request->input('term');
        if ($request->total_images) {
            $perPageLimit = $request->total_images;
        } else {
            $perPageLimit = $request->get("per_page");
        }
        if (empty($perPageLimit)) {
            $perPageLimit = Setting::get('pagination');
        }
        $suggestedProducts = \App\SuggestedProduct::join('suggested_product_lists','suggested_products.customer_id','suggested_product_lists.customer_id')->where('suggested_product_lists.chat_message_id','!=',NULL);
        if($customerId) {
            $suggestedProducts = $suggestedProducts->where('suggested_products.customer_id',$customerId);
        }
        // $suggestedProducts = $suggestedProducts->groupBy('suggested_products.customer_id')->select('suggested_products.*')->paginate($perPageLimit);


        $suggestedProducts = $suggestedProducts->select(DB::raw('suggested_products.*, suggested_products.created_at as created_at'))->orderBy('created_at','DESC')->groupBy('suggested_products.id')->paginate($perPageLimit);

        foreach($suggestedProducts as $suggestion) {
            $suggestion->last_attached = \App\SuggestedProduct::where('customer_id',$suggestion->customer_id)->orderBy('created_at','desc')->first()->created_at;

            $brandIds = \App\SuggestedProductList::join('products','suggested_product_lists.product_id','products.id')->where('suggested_product_lists.customer_id',$suggestion->customer_id)->where('suggested_products_id',$suggestion->id)->groupBy('products.brand')->pluck('products.brand');
            if(count($brandIds) > 0) {
                $suggestion->brdNames = Brand::whereIn('id',$brandIds)->get();
            }
            else {
                $suggestion->brdNames = [];
            }

            $catIds = \App\SuggestedProductList::join('products','suggested_product_lists.product_id','products.id')->where('suggested_product_lists.customer_id',$suggestion->customer_id)->where('suggested_products_id',$suggestion->id)->groupBy('products.category')->pluck('products.category');
            if(count($catIds) > 0) {
                $suggestion->catNames = Category::whereIn('id',$catIds)->get();
            }
            else {
                $suggestion->catNames = [];
            }
        }

        if ($request->category) {
            try {
                $filtered_category = $request->category;
            } catch (\Exception $e) {
                $filtered_category = [1];
            }
        } else {
            $filtered_category = [1];
        }

        $category_selection = Category::attr(['name' => 'category[]', 'class' => 'form-control select-multiple-cat-list input-lg select-multiple', 'multiple' => true, 'data-placeholder' => 'Select Category..'])
                ->selected($filtered_category)
                ->renderAsDropdown();


        $all_product_ids = [];
        $model_type = 'customer';
        $countBrands = 0;
        $countCategory = 0;
        $countSuppliers = 0;
        $categoryArray = [];
        $from = '';
        $products_count = 0;
        $selected_products = [];
        $brand =$request->brand;
        if ($request->ajax()) {
            $html = view('partials.suggested-image-load', [
                'suggestedProducts' => $suggestedProducts,
                'all_product_ids' => $all_product_ids,
                'selected_products' => $request->selected_products ? json_decode($request->selected_products) : [],
                'model_type' => $model_type,
                'countBrands' => $countBrands,
                'countCategory' => $countCategory,
                'countSuppliers' => $countSuppliers,
                'customerId' => $customerId,
                'categoryArray' => $categoryArray,
                'brand' => $brand
                ])->render();

            if (!empty($from) && $from == "attach-image") {
                return $html;
            }

            // return response()->json(['html' => $html, 'products_count' => $products_count]);
            $selected_products =  $request->selected_products ? json_decode($request->selected_products) : [];
            return view('partials.suggested-image-load', compact(
                'suggestedProducts','all_product_ids','selected_products','model_type','countBrands','countCategory','countSuppliers','customerId','categoryArray','brand'
));
        }

        $message_body = $request->message ? $request->message : '';
        $sending_time = $request->sending_time ?? '';

        $locations = \App\ProductLocation::pluck("name", "name");
        $suppliers = Supplier::select(['id', 'supplier'])->whereIn('id', DB::table('product_suppliers')->selectRaw('DISTINCT(`supplier_id`) as suppliers')->pluck('suppliers')->toArray())->get();

        $quick_sell_groups = \App\QuickSellGroup::select('id', 'name')->orderBy('id', 'desc')->get();
        //\Log::info(print_r(\DB::getQueryLog(),true));

        $customers = \App\Customer::pluck('name','id');
        return view('partials.suggested-image-grid', compact(
                        'suggestedProducts', 'products_count', 'roletype', 'model_id', 'selected_products', 'model_type', 'status', 'assigned_user', 'category_selection', 'brand', 'filtered_category', 'message_body', 'sending_time', 'locations', 'suppliers', 'all_product_ids', 'quick_sell_groups', 'countBrands', 'countCategory', 'countSuppliers', 'customerId', 'categoryArray', 'term','customers'
        ));
    }
    public function removeProducts($suggested_products_id, Request $request) {
            $products = json_decode($request->products,true);
            foreach($products as $product_list_id) {
                $suggested = \App\SuggestedProductList::find($product_list_id);
                if($suggested) {
                    if($suggested->chat_message_id) {
                     $suggested->remove_attachment = 1;
                     $suggested->save();
                     }
                    else {
                     $suggested->delete();
                    }
                }
            }
            $remains = \App\SuggestedProductList::where('suggested_products_id',$suggested_products_id)->count();
                if(!$remains) {
                    \App\SuggestedProduct::where('id',$suggested_products_id)->delete();
                }
            return response()->json(['code' => 200, 'message' => 'Successfull']);
    }

    public function removeSingleProduct($customer_id, Request $request) {
        $suggested = \App\SuggestedProductList::find($request->product_id);
       if($suggested) {
           if($suggested->chat_message_id) {
            $suggested->remove_attachment = 1;
            $suggested->save();
            }
           else {
            $suggested->delete(); 

            $remains = \App\SuggestedProductList::where('customer_id',$customer_id)->count();
            if(!$remains) {
                \App\SuggestedProduct::where('customer_id',$customer_id)->delete();
            }
           }
       }
        return response()->json(['code' => 200, 'message' => 'Successfull']);
    }

    


    public function forwardProducts(Request $request) {
        $customerId = 0;
        if ($request->customer_id) {
            $explode = explode('/', $request->customer_id);
            if (count($explode) > 1) {
                $customerId = $explode[1];
            }
        }
        /*if (!$request->forward_suggestedproductid) {
            $msg = 'Entry not found';
            return response()->json(['code' => 500, 'message' => $msg]);
        }*/
        
        $forward_suggestedproductid = $request->forward_suggestedproductid;
        
        if (!$customerId) {
            $msg = ' Customer not found';
            return response()->json(['code' => 500, 'message' => $msg]);
        }
        
        $suggestedProducts = false;
        if($forward_suggestedproductid) {
            $suggestedProducts = \App\SuggestedProduct::where('customer_id', $customerId)->where('id', $forward_suggestedproductid)->orderBy('created_at', 'desc')->first();
        }

        $products          = json_decode($request->products, true);
        $total             = count($products);
        
        if ($suggestedProducts) {
            $suggestedProducts->touch();
            $new_suggestedproductid = $suggestedProducts->id;
        } else {
            $suggestedProducts              = new \App\SuggestedProduct;
            $suggestedProducts->customer_id = $customerId;
            $suggestedProducts->total       = $total;
            $suggestedProducts->save();
            $new_suggestedproductid = $suggestedProducts->id;
        }


        $listIds        = json_decode($request->products, true);
        $data_to_insert = [];
        $inserted       = 0;

        if (!empty($listIds) && is_array($listIds)) {
            foreach ($listIds as $listedImage) {

                $productList  = \App\SuggestedProductList::find($listedImage);
                $product      = Product::find($productList->product_id);
                $imageDetails = $product->getMedia(config('constants.attach_image_tag'))->first();
                $image_key    = $imageDetails->getKey();
                $media        = Media::find($image_key);
                if ($media) {
                    $mediable = \App\Mediables::where('media_id', $media->id)->where('mediable_type', 'App\Product')->first();
                    if ($mediable) {
                        $exists = \App\SuggestedProductList::where('suggested_products_id', $new_suggestedproductid)->where('customer_id', $customerId)->where('product_id', $mediable->mediable_id)->where('date', date('Y-m-d'))->first();
                        if (!$exists) {
                            $pr = Product::find($mediable->mediable_id);
                            if ($pr->hasMedia(config('constants.attach_image_tag'))) {
                                $data_to_insert[] = [
                                    'suggested_products_id' => $new_suggestedproductid,
                                    'customer_id'           => $customerId,
                                    'product_id'            => $mediable->mediable_id,
                                    'date'                  => date('Y-m-d'),
                                ];
                            }
                        }
                    }
                }
            }

            $inserted = count($data_to_insert);
            if ($inserted > 0) {
                \App\SuggestedProductList::insert($data_to_insert);
            }

            if ($request->type == 'forward') {
                $data['_token']          = $request->_token;
                $data['send_pdf']        = 0;
                $data['pdf_file_name']   = "";
                $data['images']          = $request->products;
                $data['image']           = null;
                $data['screenshot_path'] = null;
                $data['message']         = null;
                $data['customer_id']     = $customerId;
                $data['status']          = 2;
                $data['type']            = 'customer-attach';
                \App\Jobs\AttachImagesSend::dispatch($data)->onQueue("customer_message");
            }
        }
        $msg = $inserted . ' Products added successfully';
        return response()->json(['code' => 200, 'message' => $msg]);
    }


    public function resendProducts($suggestedproductid, Request $request) {
                $suggestedProducts = \App\SuggestedProduct::where('id', $suggestedproductid)->orderBy('created_at','desc')->first();
                $customer_id=$suggestedProducts->customer_id;
                $products = json_decode($request->products, true);
                $suggestedProducts->touch();
                

                            $data['_token'] = $request->_token;
                            $data['send_pdf'] = 0;
                            $data['pdf_file_name'] = "";
                            $data['images'] = $request->products;
                            $data['image'] = null;
                            $data['screenshot_path'] = null;
                            $data['message'] = null;
                            $data['customer_id'] = $customer_id;
                            $data['status'] = 2;
                            $data['type'] = 'customer-attach';
                            \App\Jobs\AttachImagesSend::dispatch($data)->onQueue("customer_message");
                $msg = ' Images Resend successfully';
                return response()->json(['code' => 200, 'message' => $msg]);
    }

    public function getCustomerProducts($type,$suggested_products_id,$customer_id,Request $request) {
        $term = null;
        //$suggested_products_id=3;
      
        if($type == 'attach') {
            $productsLists = \App\SuggestedProductList::where('suggested_products_id',$suggested_products_id)->where('customer_id',$customer_id)->where('remove_attachment',0)
            ->select('suggested_product_lists.*')->orderBy('date','desc')->get()->unique('date');
        }
        else {
            $productsLists = \App\SuggestedProductList::where('customer_id',$customer_id)->where('chat_message_id','!=',NULL)
            ->select('suggested_product_lists.*')->orderBy('date','desc')->get()->unique('date');
        }
        $customer = \App\Customer::find($customer_id);

        foreach($productsLists as $suggestion) {
            if($type == 'attach') {
                $products = \App\SuggestedProductList::join('products','suggested_product_lists.product_id','products.id')
            ->where('suggested_product_lists.customer_id',$customer_id)
            ->where('suggested_product_lists.suggested_products_id',$suggested_products_id)
            ->where('remove_attachment',0)
            ->where('suggested_product_lists.date', $suggestion->date);
            }
            else {
                $products = \App\SuggestedProductList::join('products','suggested_product_lists.product_id','products.id')
            ->where('suggested_product_lists.customer_id',$customer_id)
            ->where('suggested_product_lists.suggested_products_id',$suggested_products_id)
            ->where('chat_message_id','!=',NULL)
            ->where('suggested_product_lists.date', $suggestion->date);
            }

            if (isset($request->brand[0])) {
                if ($request->brand[0] != null) {
                    $products = $products->whereIn('products.brand', $request->brand);
                }
            }

            if (isset($request->category[0])) {
                if ($request->category[0] != null && $request->category[0] != 1) {

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
                }
            }

            if (trim($term) != '') {
                $products = $products->where(function ($query) use ($term) {
                    $query->where('sku', 'LIKE', "%$term%")
                            ->orWhere('products.id', 'LIKE', "%$term%")
                            ->orWhere('name', 'LIKE', "%$term%")
                            ->orWhere('short_description', 'LIKE', "%$term%");
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
            }
             $suggestion->products = $products->select('products.*','suggested_product_lists.created_at as sort','suggested_product_lists.id as suggested_product_list_id')->orderBy('sort')->get();
        }
        $selected_products = [];
        $model_type = 'customer';
        if($type == 'attach') {
            return view('partials.attached-image-products',compact('productsLists','customer_id','selected_products','model_type','suggested_products_id','customer'));

        }
        else {
            return view('partials.suggested-image-products',compact('productsLists','customer_id','selected_products','model_type','suggested_products_id','customer'));

        }
    }

    public function addDraftProductsToQuickSell(Request $request){
        $post=$request->all();
        
        $group = \App\QuickSellGroup::orderBy('id', 'desc')->first();
            if ($group != null) {
                $group_create =  new \App\QuickSellGroup();
                $incrementId = ($group->group+1);
                $group_create->group = $incrementId;
                $group_create->name = $post['groupName'];
                $group_create->save();
                $group_id = $group_create->group;
            } else {
                $group =  new \App\QuickSellGroup();
                $group->group = 1;
                $group->name = $post['groupName'];
                $group->save();
                $group_id = $group->group;
            }
            foreach ($request->products as $id){
                $group = new \App\ProductQuicksellGroup();
                $group->product_id = $id;
                $group->quicksell_group_id = $group_id;
                $group->save();
            }
            $msg = 'Products are added into group successfully';
            return response()->json(['code' => 200, 'message' => $msg]);
     }

    public function test(Request $request){

        $product = Product::where('id', $request->productid)->first();
        if($product !== null){
            $image = $product->getMedia(config('constants.attach_image_tag'))->first();

//        $data = [
//            "title" => "PRODUCT TITLE",
//            "url" => "https://picsum.photos/200/300",
//            "amount" => "25.25 $",
//            "description" => "Nishit You have done it!"
//        ];
            $data = [
                "title" => $product->name,
                "url" => $image->getUrl(),
                "amount" => $product->price,
                "description" => $product->short_description
            ];
        }else{
            $data = [
                "status" => false,

            ];
        }


        return response()->json($data);
    }



    public function add_product_def_cust($product_id,Request $request)
    {
        $product = Product::find($product_id);
        
        // $def_cust_id = getenv('DEFAULT_CUST_ID');
        $def_cust_id = config('env.DEFAULT_CUST_ID');

        $customers = \App\Customer::find($def_cust_id);

        $statement = \DB::select("SHOW TABLE STATUS LIKE 'orders'");
        $nextId    = 0;
        if (!empty($statement)) {
            $nextId = $statement[0]->Auto_increment;
        }

        $order_id = "OFF-" . date('Ym') .'-'. $nextId;
       
        $order_data = array(
            'customer_id' => $def_cust_id,
            'order_id' => $order_id,
            'order_date' => date('Y-m-d'),
            'client_name' => $customers->name,
        );
        $order = Order::create($order_data);

        $order_id = $order->id;

        $orderproduct_data = array(
            'order_id' => $order_id,
            'sku' => $product->sku,
            'product_id' => $product->id,
            'product_price' => $product->price,
        );
        
        $order_products = OrderProduct::create($orderproduct_data);

        return response()->json(['code' => 200, 'message' => 'Purchase Products Added successfully']);
    }

}



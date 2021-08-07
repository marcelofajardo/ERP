<?php

namespace Modules\StoreWebsite\Http\Controllers;

use App\Category;
use App\Language;
use App\GoogleTranslate;
use App\StoreWebsite;
use Illuminate\Http\Request;
use App\StoreWebsiteCategory;
use Illuminate\Http\Response;
use App\StoreWebsiteCategorySeo;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use seo2websites\MagentoHelper\MagentoHelper;

class CategorySeoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $title = "Category SEO | Store Website";
        $languages = Language::pluck('name', 'id')->toArray();
        $storeWebsites = StoreWebsite::all()->pluck("website", "id");
        $categories = Category::all();
        $categories_list = Category::pluck('title', 'id')->toArray();
        $store_list = StoreWebsite::pluck('title', 'id')->toArray();
        $categroy_seos_list = StoreWebsiteCategorySeo::select('meta_title','id')->orderBy('id','desc')->get();
        return view('storewebsite::category-seo.index',[
            'title' => $title,
            'storeWebsites' => $storeWebsites,
            'categories' => $categories,
            'categories_list' => $categories_list,
            'languages' => $languages,
            'store_list' => $store_list,
            'categroy_seos_list' => $categroy_seos_list,
        ]);
    }

    public function records(Request $request)
    {
        $storewebsite_category_seos = StoreWebsiteCategorySeo::join("categories as cat", "cat.id", "store_website_category_seos.category_id")
            ->leftjoin("categories as sub_cat", "sub_cat.id", "cat.parent_id")
            ->leftjoin("categories as main_cat", "main_cat.id", "sub_cat.parent_id")
            ->leftjoin("store_websites as store", "store.id", "store_website_category_seos.store_website_id")
            ->join("languages", "languages.id", "store_website_category_seos.language_id");

        if ($request->has('category_id') && !empty($request->category_id)) {
            $storewebsite_category_seos = $storewebsite_category_seos->where(function ($q) use ($request) {
                $q->where("cat.id",$request->category_id);
            });
        }

        if ($request->has('store_website_id') && !empty($request->store_website_id)) {
            $storewebsite_category_seos = $storewebsite_category_seos->where(function ($q) use ($request) {
                $q->where("store_website_category_seos.store_website_id",$request->store_website_id);
            });
        }

        // Check for keyword search
        if ($request->has('keyword')) {
            $storewebsite_category_seos = $storewebsite_category_seos->where(function ($q) use ($request) {
                $q->where("cat.title", "like", "%" . $request->keyword . "%")->orWhere('store_website_category_seos.meta_title', "like", "%" . $request->keyword . "%");
            });
        }

        $storewebsite_category_seos = $storewebsite_category_seos->orderBy("store_website_category_seos.id","DESC")->select(["languages.name", "cat.title", "sub_cat.title as sub_category", "main_cat.title as main_category","store.title as store_name", "store_website_category_seos.*"])->paginate();

        $items = $storewebsite_category_seos->items();

        $recItems = [];
        foreach($items as $item) {
            $attributes = $item->getAttributes();
            $attributes['store_small'] = strlen($attributes['name']) > 15 ? substr($attributes['name'],0,15) : $attributes['name'];
            $attributes['category'] = $attributes['title'];
            if(!empty($attributes['sub_category']))
            {
                $attributes['category'] = $attributes['sub_category']." > ".$attributes['category'];
            }
            if(!empty($attributes['main_category']))
            {
                $attributes['category'] = $attributes['main_category']." > ".$attributes['category'];
            }
            $recItems[] = $attributes;

        }

        return response()->json(["code" => 200, "data" => $recItems, "total" => $storewebsite_category_seos->total(),
            "pagination"  => (string) $storewebsite_category_seos->links(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('storewebsite::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $post = $request->all();
        $id   = $request->get("id", 0);

        $params = [
            'meta_title'    => 'required',
            'category_id'  => 'required',
            'store_website_id'  => 'required',
            'language_id'  => 'required',
        ];

        $validator = Validator::make($post, $params);

        if ($validator->fails()) {
            $outputString = "";
            $messages     = $validator->errors()->getMessages();
            foreach ($messages as $k => $errr) {
                foreach ($errr as $er) {
                    $outputString .= "$k : " . $er . "<br>";
                }
            }
            return response()->json(["code" => 500, "error" => $outputString]);
        }

        $records = StoreWebsiteCategorySeo::find($id);

        if (!$records) {
            $records = new StoreWebsiteCategorySeo;
        }

        $records->fill($post);

        // if records has been save then call a request to push
        if ($records->save()) {

        }

        return response()->json(["code" => 200, "data" => $records]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('storewebsite::show');
    }

    public function edit(Request $request, $id)
    {
        $storewebsite_category_seo = StoreWebsiteCategorySeo::where("id", $id)->first();

        if ($storewebsite_category_seo) {
            if( empty($storewebsite_category_seo->meta_title) ){
                $request->category;
                if( $request->category ){
                    $category = explode( '>', $request->category );
                    end($category);
                    $storewebsite_category_seo->meta_title = prev( $category ).end($category) ;
                }
            }
            
            return response()->json(["code" => 200, "data" => $storewebsite_category_seo]);
        }

        return response()->json(["code" => 500, "error" => "Wrong category seo id!"]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $storewebsite_category_seo = StoreWebsiteCategorySeo::where("id", $id)->first();

        if ($storewebsite_category_seo) {
            $storewebsite_category_seo->delete();
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "error" => "Wrong category seo id!"]);
    }

    public function translateForOtherLanguage(Request $request, $id)
    {
        $store_website_category_seo = StoreWebsiteCategorySeo::find($id);

        if (!empty($store_website_category_seo)) {
            $languages = \App\Language::where("status", 1)->get();
            foreach ($languages as $lang) {
                if ($lang->id != $store_website_category_seo->language_id) {
                    $categoryExist = \App\StoreWebsiteCategorySeo::where("category_id", $store_website_category_seo->category_id)->where("language_id", $lang->id)->first();
                    if (empty($categoryExist)) {
                        $newStoreCategorySeo = new StoreWebsiteCategorySeo();
                        
                        $meta_title = \App\Http\Controllers\GoogleTranslateController::translateProducts(
                            new GoogleTranslate,
                            $lang->locale,
                            [$store_website_category_seo->meta_title]
                        );
                        $meta_description = \App\Http\Controllers\GoogleTranslateController::translateProducts(
                            new GoogleTranslate,
                            $lang->locale,
                            [$store_website_category_seo->meta_description]
                        );
                        $meta_keyword = \App\Http\Controllers\GoogleTranslateController::translateProducts(
                            new GoogleTranslate,
                            $lang->locale,
                            [$store_website_category_seo->meta_keyword]
                        );

                        $newStoreCategorySeo->category_id = $store_website_category_seo->category_id;
                        $newStoreCategorySeo->meta_title = $meta_title;
                        $newStoreCategorySeo->meta_description = $meta_description;
                        $newStoreCategorySeo->meta_keyword = $meta_keyword;
                        $newStoreCategorySeo->language_id = $lang->id;
                        $newStoreCategorySeo->save();
                    }
                }
            }
            return response()->json(["code" => 200, "data" => [], "message" => "Records copied succesfully"]);
        }
        return response()->json(["code" => 500, "data" => [], "message" => "Category does not exist"]);
    }
    public function push($id){
        $SeoCategory = StoreWebsiteCategorySeo::where("id", $id)->first();
        $stores = StoreWebsiteCategory::where('category_id',$SeoCategory->category_id)->pluck('store_website_id')->toArray();
        if ($SeoCategory) {
            \App\Jobs\PushCategorySeoToMagento::dispatch([$SeoCategory->category_id],array_unique($stores));
            return response()->json(["code" => 200, 'message' => "category send for push"]);
        }

        return response()->json(["code" => 500, "message" => "Wrong site id!"]);
    }
    public function pushWebsiteInLive($id){
        $categories = StoreWebsiteCategory::where('store_website_id',$id)->pluck('category_id')->toArray();
        // print_r($categories);
        // exit();
        if ($categories) {
            \App\Jobs\PushCategorySeoToMagento::dispatch($categories,[$id]);
            return response()->json(["code" => 200, 'message' => "category send for push"]);
        }

        return response()->json(["code" => 500, "message" => "Wrong site id!"]);
    }

    public function history($id)
    {
    	$histories = \App\StoreWebsiteCategorySeosHistories::leftJoin("users as u","u.id","store_website_category_seos_histories.user_id")
    	->where("store_website_cate_seos_id",$id)
    	->orderBy("store_website_category_seos_histories.created_at","desc")
    	->select(["store_website_category_seos_histories.*","u.name as user_name"])
    	->get();

        return response()->json(["code" => 200 , "data" => $histories]);
    }

    public function loadPage(Request $request, $id)
    {
        $page = \App\StoreWebsiteCategorySeo::find($id);

        if ($page) {
            return response()->json(["code" => 200, "content" => $page->content, "meta_title" => $page->meta_title, "meta_keyword" => $page->meta_keyword, "meta_desc" => $page->meta_description]);
        }

    }

    public function copyTo(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'page'    => 'required',
            'to_page' => 'different:page',
        ]);

        if (!$validator->passes()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $page = StoreWebsiteCategorySeo::where("id", $request->page )->first();

        if ($page) {

            if( ! empty( $request->to_page ) || ! empty( $request->entire_category ) ){
                $updateData = [];

                if($request->cttitle == 'true'){
                    $updateData['meta_title'] = $page->meta_title;
                }
                if($request->ctkeyword  == 'true'){
                    $updateData['meta_keyword'] = $page->meta_keyword;
                }
                if($request->ctdesc  == 'true'){
                    $updateData['meta_description'] = $page->meta_description;
                }
                dump( $updateData );
                if( $updateData ){
                    if( $request->to_page ){
                        StoreWebsiteCategorySeo::where("id", $request->to_page )->update( $updateData );
                    }
                    if( $request->entire_category == 'true' ){
                        StoreWebsiteCategorySeo::where("category_id", $page->category_id )->update( $updateData );
                    }
                    return response()->json(["code" => 200, "success" => 'Success']);
                }
            }
        }
        return response()->json(["code" => 200, "error" => 'Page not found']);
    }
}

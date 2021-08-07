<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Helpers\StatusHelper;
use App\LandingPageProduct;
use App\LandingPageStatus;
use App\Language;
use App\Library\Shopify\Client as ShopifyClient;
use App\Product;
use App\Services\Products\GraphqlService;
use App\StoreWebsite;
use App\StoreWiseLandingPageProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Plank\Mediable\Media;
use App\Loggers\LogListMagento;
use App\ProductPushErrorLog;
use Google\Cloud\Translate\V3\Translation;
use App\Helpers\ProductHelper;
use App\Jobs\PushToMagento;
use App\HsCodeGroup;
use App\HsCode;
use App\HsCodeGroupsCategoriesComposition;

class LandingPageController extends Controller
{
    const GALLERY_TAG_NAME = "gallery";

    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $title  = "Landing Page";
        $status = \App\LandingPageProduct::STATUS;
        $store_websites = null;
        $statuses = \App\LandingPageStatus::all()->pluck('name')->toArray();

        return view("landing-page.index", compact(['title', 'status','store_websites','statuses']));
    }

    public function records(Request $request)
    {
        $brandName = request("brand");
        if($brandName != null) {
            $brand = Brand::where('name', 'like', "%$brandName%")->first();
            $brandId = isset($brand) ? $brand->id : null;

            $records = \App\LandingPageProduct::with(['landing_page_status'])->join('products as p', function ($query) use ($brandId) {
                $query->on("p.id", "=", "landing_page_products.product_id");
                $query->where('p.brand', $brandId);
            });

        } else {
            $records = \App\LandingPageProduct::with(['landing_page_status'])->join("products as p","p.id","landing_page_products.product_id");
        }

        $keyword = request("keyword");
        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("product_id", "LIKE", "%$keyword%");
            });
        }

        $stockStatus = request("stock_status");
        if($stockStatus != null) {
            $records = $records->where("landing_page_products.stock_status", $stockStatus);
        }

        $productStatus = request("product_status");

        if($productStatus != null) {
            $records = $records->where("p.status_id", $productStatus);
        }

        $status = request("status");
        if($status != null) {
            $records = $records->where("landing_page_products.status", $status);
        }

        $dateFrom = request("date_from");
        if($dateFrom != null) {
            $records = $records->where("landing_page_products.created_at", '>=', $dateFrom);
        }

        $dateTo = request("date_to");
        if($dateTo != null) {
            $records = $records->where("landing_page_products.created_at", '<=', $dateTo);
        }

        $records = $records->select(["landing_page_products.*","p.status_id","p.stock"])->latest()->paginate();
        $store_websites = StoreWebsite::where('website_source','=','shopify')->get();

        $items = [];
        $allStatus = StatusHelper::getStatus();
        foreach ($records->items() as &$rec) {
            $landingPageProduct = $rec->product;
            if(!$landingPageProduct) {
                continue;
            }
            if($landingPageProduct) {
                if(array_key_exists($landingPageProduct->status_id, $allStatus)){
                    $rec->productStatus = $allStatus[$landingPageProduct->status_id];
                }else{
                    $rec->productStatus = '';
                }
            }else {
                $rec->productStatus = '';
            }

            $productData['images'] = [];
            if ($landingPageProduct->hasMedia(config('constants.attach_image_tag'))) {
                $c = 0;
                foreach ($landingPageProduct->getAllMediaByTag() as $medias) {
                    foreach($medias as $image) {
                        $temp = false;
                        if($c == 0){
                            $temp = true;
                        }
                        array_push($productData['images'], ['url' =>$image->getUrl(),'id'=>$image->id,'product_id'=>$landingPageProduct->id,'show' => $temp]);
                        $c++;
                    }
                }
            }
            $rec->images = $productData['images'];
//            $rec->status_name = isset(\App\LandingPageProduct::STATUS[$rec->status]) ? \App\LandingPageProduct::STATUS[$rec->status] : $rec->status;
            $previousVal = isset(\App\LandingPageProduct::STATUS[$rec->status]) ? \App\LandingPageProduct::STATUS[$rec->status] : $rec->status;
            $rec->status_name = isset($rec->landing_page_status) ? $rec->landing_page_status->name : $previousVal;
            $rec->brand_name = isset($rec->product->brands->name) ? $rec->product->brands->name : null;
            $rec['stores'] = $store_websites;
            $rec->short_dec   = (strlen($rec->description) > 15) ? substr($rec->description, 0, 15).".." : $rec->description;
            $rec->short_dec   = utf8_encode($rec->short_dec);
            $rec->created = explode(' ', $rec->created_at)[0];
            $rec->start_date  = Carbon::parse($rec->start_date)->format('Y-m-d');
            $rec->end_date    = Carbon::parse($rec->end_date)->format('Y-m-d');
            $items[]          = $rec;
        }


        return response()->json(["code" => 200, "data" => $items, "total" => $records->total(), "pagination" => (string) $records->render()]);
    }

    public function save(Request $request)
    {
        $params     = $request->all();
        $productIds = json_decode($request->get("images"), true);

        $errorMessage = [];

        if (!empty($productIds)) {
            foreach ($productIds as $productId) {
                $product = \App\Product::find($productId);
                if ($product) {
                    if($product->category > 3 && $product->hasMedia(config('constants.media_original_tag'))) {
                        // check status if not cropped then send to the cropper first
                        foreach ($product->getAllMediaByTag() as $tag => $medias) {
                            // if there is specific color then only send the images
                            if (strpos($tag, self::GALLERY_TAG_NAME) !== false) {
                                foreach ($medias as $image) {
                                    $image->delete();
                                }
                            }
                        }
                        $product->status_id = StatusHelper::$autoCrop;
                        $product->scrap_priority = 1;
                        // save product
                        $product->save();
                        \App\LandingPageProduct::updateOrCreate(
                            ["product_id" => $productId],
                            ["product_id" => $productId, "name" => $product->name, "description" => $product->short_description, "price" => $product->price]
                        );
                    }else{
                        $errorMessage[] = "Product has no category or images : ".$productId;
                    }
                }else{
                    $errorMessage[] = "Product not found : {$productId}";
                }
            }
        }

        if(count($errorMessage) > 0) {
            return redirect()->route('landing-page.index')->withError('There was some issue for given products : '.implode("<br>",$errorMessage));
        }

        return redirect()->route('landing-page.index')->withSuccess('You have successfully added landing page!');
    }

    public function store(Request $request)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'product_id' => 'required',
            'start_date' => 'required',
            'end_date'   => 'required',
        ]);

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

        $id = $request->get("id", 0);

        $records = LandingPageProduct::find($id);

        if (!$records) {
            $records = new LandingPageProduct;
        }

        if(!Auth::user()->isAdmin) {
           $userUploadedStatus = LandingPageStatus::where('name', LandingPageProduct::STATUS['USER_UPLOADED'])->first();
            $post['landing_page_status_id'] = isset($userUploadedStatus) ? $userUploadedStatus->id : null;
        } else {
            $post['landing_page_status_id'] = $post['status'] + 1; //plus 1 because of comes with array keys, which equal key + 1
        }

        unset($post['status']);

        $records->fill($post);
        $records->save();

        return response()->json(["code" => 200, "data" => $records]);

    }

    /**
     * Edit Page
     * @param  Request $request [description]
     * @return
     */

    public function edit(Request $request, $id)
    {
        $landingPage = LandingPageProduct::where("id", $id)->first();

        if ($landingPage) {
            return response()->json(["code" => 200, "data" => $landingPage]);
        }

        return response()->json(["code" => 500, "error" => "Wrong row id!"]);
    }

    /**
     * delete Page
     * @param  Request $request [description]
     * @return
     */

    public function delete(Request $request, $id)
    {
        $landingPage = LandingPageProduct::where("id", $id)->first();

        if ($landingPage) {
            $landingPage->delete();
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "error" => "Wrong row id!"]);
    }

    public function pushToShopify(Request $request, $id)
    {
    
        $landingPage = LandingPageProduct::where("id", $id)->first();
        if (!empty($landingPage) && $landingPage->store_website_id > 0) {

            // if stock status exist then store it
            if ($request->stock_status != null) {
                $landingPage->stock_status = $request->stock_status;
                if ($landingPage->stock_status == 1) {
                    $landingPage->start_date = date("Y-m-d H:i:s");
                    $landingPage->end_date   = date("Y-m-d H:i:s", strtotime($landingPage->start_date . ' + 1 days'));
                }
                $landingPage->save();
            }

            // Set data for Shopify
            $landingPageProduct = $landingPage->product;
            $productData = $landingPage->getShopifyPushData();
            LogListMagento::log($landingPageProduct->id, "Product started to push" . $landingPageProduct->id, 'info', $landingPage->store_website_id, "started");
            
            if ($productData == false) {
                return response()->json(["code" => 500, "data" => "", "message" => "Pushing Failed: product is not approved"]);
            }


            $client = new ShopifyClient();
            if ($landingPage->shopify_id) {
                $response = $client->updateProduct($landingPage->shopify_id, $productData,null, $landingPage->store_website_id);
            } else {
                $response = $client->addProduct($productData, $landingPage->store_website_id);
            }

            $errors = [];
            if (!empty($response->errors)) {
                foreach ((array)$response->errors as $key => $message) {
                    if (is_array($message)) {
                        foreach ($message as $msg) {
                            $errors[] = ucwords($key) . " " . $msg;
                        }
                    } else {
                        $errors[] = ucwords($key) . " " . $message;
                    }
                }
            }

            if (!empty($errors)) {
                LogListMagento::log($landingPageProduct->id, "error " . $landingPageProduct->id, 'info', $landingPage->store_website_id, "error");
                return response()->json(["code" => 500, "data" => $response, "message" => implode("<br>", $errors)]);
            }

            if (!empty($response->product)) {
                LogListMagento::log($landingPageProduct->id, "success " . $landingPageProduct->id, 'info', $landingPage->store_website_id, "success");
                $landingPage->shopify_id = $response->product->id;
                $landingPage->save();

                $selfProduct = Product::find($landingPage->product_id);
                $selfCategory = Category::find($selfProduct->category);
                
                if ($selfProduct) {
                    $storeWebsiteUrl = StoreWebsite::find($landingPage->store_website_id);

                    if ($storeWebsiteUrl) {
                        GoogleTranslateController::translateProductDetails($selfProduct);
                        if(!empty($selfCategory->title)) {
                            GoogleTranslateController::translateGeneralDetails(['text'=>$selfCategory->title]); //DEVTASK-3272
                        }
                        GraphqlService::sendTranslationByGrapql($landingPage->shopify_id, $landingPage->product_id,$storeWebsiteUrl->magento_url, $storeWebsiteUrl->magento_password,$storeWebsiteUrl);
//                    GraphqlService::testGetDataByCurl($landingPage->shopify_id);//check translations exist
                    }

                    return response()->json(["code" => 200, "data" => $response->product, "message" => "Success!"]);
                } else {
                    return response()->json(["code" => 500, "data" => [], "message" => "Product not found."]);
                }
            }

        }

        return response()->json(["code" => 500, "data" => [], "message" => "Records not found or not store website assigned"]);

    }


    public function pushToMagentoPro(Request $request, $id)
    {
        $landingPage = LandingPageProduct::where("id", $id)->first();
        $queueName = [
            "1" => "mageone",
            "2" => "magetwo",
            "3" => "magethree"
        ];
        $i = 1;
        if(!empty($landingPage) && $landingPage->store_website_id > 0){
            // Get product by ID
            $product = Product::find($landingPage->product_id);
            $websiteArrays = StoreWebsite::where('id', $landingPage->store_website_id)->first();
            if($websiteArrays){
                \Log::info("Product started website found For website".$websiteArrays->website);
                LogListMagento::log($product->id, "Start push to magento for product id " . $product->id, 'info',$websiteArrays->id);
                PushToMagento::dispatch($product,$websiteArrays)->onQueue($queueName[$i]);
                return response()->json(["code" => 200, "data" => "", "message" => "Success!"]);
            }else{
                return response()->json(["code" => 500, "data" => "", "message" => "Please select the store website!"]);
            }
        }
    }

    public function updateMagentoStock(Request $request, $id){
        $landingPage = LandingPageProduct::where("id", $id)->first();
        // if stock status exist then store it
        if ($request->stock_status != null) {
            $landingPage->stock_status = $request->stock_status;
            if ($landingPage->stock_status == 1) {
                $landingPage->start_date = date("Y-m-d H:i:s");
                $landingPage->end_date   = date("Y-m-d H:i:s", strtotime($landingPage->start_date . ' + 1 days'));
            }
            $landingPage->save();
            return response()->json(["code" => 200, "data" => "", "message" => "Success!"]);
        }else{
            return response()->json(["code" => 500, "data" => "", "message" => "Please select the store website!"]);
        }


    }
    public function updateTime(Request $request)
    {
        $productIds = explode(',', $request->product_id);
        foreach ($productIds as $productId) {
            LandingPageProduct::where('product_id','=',$productId)->update(['start_date' => $request->start_date, 'end_date' => $request->end_date]);
        }
        return redirect()->back();
    }


    public function deleteImage($id, $productId)
    {
        \DB::table('mediables')->where('mediable_type', 'App\Product')
            ->where('media_id', $id)
            ->where('mediable_id', $productId)
            ->delete();
        return response()->json(["code" => 200, "data" => "", "message" => "Success!"]);
    }

    public function changeStore(Request $request, $id)
    {
        $landing = \App\LandingPageProduct::find($id);

        if($landing && $request->get("store_website_id") != null) {
            $landing->store_website_id = $request->get("store_website_id");
            $landing->shopify_id = null;
            $landing->save();
            return response()->json(["code" => 200, "data" => "", "message" => "Success!"]);
        }else {
            return response()->json(["code" => 500, "data" => "", "message" => "Please select the store website!"]);
        }

    }

    public function createStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(["code" => 422, "message" => "Incorrect input!"]);
        } else {

         $status = new LandingPageStatus();
         $status->name = $request->status;
         $status->save();

         return response()->json(["code" => 200, "data" => "", "message" => "Success!"]);
        }
    }

    public function approveStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(["code" => 422, "message" => "Incorrect input!"]);
        } else {
            if ($request->approve) {
                $lpProduct = LandingPageProduct::find($request->id);
                if ($lpProduct){
                    $lpProduct->landing_page_status_id = LandingPageProduct::STATUS['APPROVED'];
                    $lpProduct->save();

                    return response()->json(["code" => 200,  "data" => "", "message" => "Success!"]);
                }
            }

            return response()->json(["code" => 500,  "data" => "", "message" => "Not Approved!"]);
        }
    }
}

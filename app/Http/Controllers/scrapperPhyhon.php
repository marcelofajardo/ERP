<?php


namespace App\Http\Controllers;


use App\Category;
use App\ColorReference;
use App\CroppedImageReference;
use App\Http\Requests\Products\ProductTranslationRequest;
use App\Jobs\PushToMagento;
use App\ListingHistory;
use App\Order;
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

use seo2websites\MagentoHelper\MagentoHelper;
use App\ProductTranslationHistory;
use App\Translations;
use App\ProductPushErrorLog;
use App\ProductStatusHistory;
use App\Status;
use App\ProductSupplier;
use App\Website;
use App\WebsiteStore;
use App\scraperImags;
use Validator;
use Illuminate\Support\Facades\Log;


class scrapperPhyhon extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // $this->middleware('permission:product-lister', ['only' => ['listing']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $query=$request->search;

         $websites=new Website;
         if($request->website)
         {
           $websites= $websites->where('id',$request->website);
         }

         if($request->store)
         {
            $websites=$websites->WhereHas('stores', function ($query) use ($request) {

                         $query->where('id', 'like', $request->store);

                        });     
         }


         if($request->search)
         {
            $search='%'.$request->search.'%';

            

           $websites= $websites->where('name','like',$search);



           $websites= $websites->orWhereHas('stores', function ($query) use ($search) {

                         $query->where('name', 'like', $search);

                        });     


            //$websites=$websites->paginate(Setting::get('pagination'));

         }

         
             $websites=$websites->paginate(Setting::get('pagination'));
         

         $allWebsites=Website::select('name','id')->get();

       //  echo '<pre>';print_r($websites->toArray());die;

       
       // $websiteList = WebsiteStore::with('scrapperImage')->latest();
       //  $websiteListRow = $websiteList->paginate(Setting::get('pagination'));
       // $websiteList = $websiteList->get()->toArray();
        // dd( $websiteList[0]['scrapper_image'] );

      //  echo '<pre>';print_r($websites->toArray());die;
        return view('scrapper-phyhon.list', compact('websites','query','allWebsites','request'));
    }



    public function listImages(Request $request){

        $store_id = $request->id;
//        $list =  Website::where('id',$website_id)->first();
//dd($list, $website_id);
        $oldDate = null;
        $count   = 0;
        $images = [];
        // dd( $list->store_website_id );

            $webStore = \App\WebsiteStore::where('id',$store_id)->first();
                $list =  Website::where('id',$webStore->website_id)->first();
                $website_id = $list->id;
        if( $webStore ){
            $website_store_views = \App\WebsiteStoreView::where('website_store_id',$webStore->id)->first();
//            dd($list->store_website_id);
//            dd($list->store_website_id);
                if( $website_store_views ){
                    $images = \App\scraperImags::where('store_website',$list->store_website_id)->where('website_id',$request->code)->get()->toArray();
                }
            }


        return view('scrapper-phyhon.list-image-products', compact('images', 'website_id'));

    }

    public function setDefaultStore(int $website=0,int $store=0,$checked=0)
    {
        if($website && $store)
        {
           try {

            if($checked)
            {

              WebsiteStore::where('website_id',$website)->update(['is_default'=>0]);

              
            }


           $store=WebsiteStore::find($store);

           $store->is_default=$checked;

           $store->save();

           $response=array('status'=>1,'message'=>'The store state is changed.');
               
           } catch (Exception $e) {

           $response=array('status'=>0,'message'=>$e->getMessage());
            
               
           }

           return $response;

        }
    }

    public function websiteStoreList(int $website=0)
    {
        try {

            if($website)
            {
                $stores=WebsiteStore::where('website_id',$website)->select('name','id')->get();

                $response=array('status'=>1,'list'=>$stores);
            }

            


            
        } catch (Exception $e) {

            $response=array('status'=>0,'message'=>$e->getMessage());
            
        }

        return $response;
    }

    public function imageSave(Request $request)
    {
        // dd(123);
        $validator = Validator::make($request->all(), [
           'country_code'   => 'required',
           'image'          => 'required',
           'image_name'     => 'required',
           'store_website'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(["code" => 500, "message" => 'Invalid request',"error" => $validator->errors()]);
        }

        $StoreWebsite = \App\StoreWebsite::where('website',$request->store_website)->first();
        if( $this->saveBase64Image( $request->image_name,  $request->image ) ){

            $newImage = array(
                'website_id' => $request->country_code,
                'store_website' => $StoreWebsite->id ?? 0,
                'img_name'   => $request->image_name,
                'img_url'    => $request->image_name,
            );

            scraperImags::insert( $newImage );

            return response()->json(["code" => 200, "message" => 'Image successfully saved']);
        }else{
            
            return response()->json(["code" => 500, "message" => 'Something went wrong!']);
        }
    }


    public function saveBase64Image( $file_name, $base64Image )
    {   
        try {
            $base64Image = trim($base64Image);
            $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
            $base64Image = str_replace('data:image/jpg;base64,', '', $base64Image);
            $base64Image = str_replace('data:image/jpeg;base64,', '', $base64Image);
            $base64Image = str_replace('data:image/gif;base64,', '', $base64Image);
            $base64Image = str_replace(' ', '+', $base64Image);
            $imageData = base64_decode( $base64Image );
    
            // //Set image whole path here 
            $filePath = public_path('scrappersImages').'/' . $file_name;
            file_put_contents($filePath, $imageData);
            return true;
        } catch (\Throwable $th) {
            dd( $th->getMessage() ) ;
            \Log::error('scrapper_images :: ' .$th->getMessage());
            return false;
        }
    }
}



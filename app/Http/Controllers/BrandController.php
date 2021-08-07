<?php

namespace App\Http\Controllers;
use App\Brand;
use App\Product;
use App\Setting;
use App\CategorySegment;
use App\ScrapedProducts;
use App\Activity;
use \App\StoreWebsiteBrand;
use Illuminate\Http\Request;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Auth;


use App\Exports\ScrapRemarkExport;
use App\Scraper;
use App\ScrapHistory;
use App\ScrapRemark;
use App\ScrapStatistics;
use App\Supplier;
use App\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Zend\Diactoros\Response\JsonResponse;
use \Carbon\Carbon;
use App\BrandLogo;
use App\BrandWithLogo;
use App\Category;
use App\CategorySegmentDiscount;

class BrandController extends Controller
{
    //

    public function __construct()
    {
      //  $this->middleware('permission:brand-edit', ['only' => 'index', 'create', 'store', 'destroy', 'update', 'edit']);
    }

    public function index()
    {   
        $brands = Brand::leftJoin("store_website_brands as swb","swb.brand_id","brands.id")
        ->leftJoin("store_websites as sw","sw.id","swb.store_website_id")
        ->select(["brands.*",\DB::raw("group_concat(sw.id) as selling_on"),\DB::raw("LOWER(trim(brands.name)) as lower_brand")])
        ->groupBy("brands.id")
        ->orderBy('lower_brand',"asc")->whereNull('brands.deleted_at')->whereNull('sw.id');

        $keyword = request('keyword');
        if(!empty($keyword)) {
            $brands = $brands->where("name","like","%".$keyword."%");
        }

        $brands = $brands->paginate(Setting::get('pagination'));

        $category_segments = CategorySegment::where('status',1)->get();

        $storeWebsite = \App\StoreWebsite::all()->pluck("website","id")->toArray();
        $brandsData = \App\Brand::select("name","references","id")->get()->toArray();
        $attachedBrands = \App\StoreWebsiteBrand::groupBy("store_website_id")->select(
            [\DB::raw("count(brand_id) as total_brand"),"store_website_id"]
        )->get()->toArray();


        return view('brand.index', compact('brands','storeWebsite','attachedBrands', 'category_segments'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function scrap_brand(Request $request)
    {
        // Set dates
        $keyWord    = $request->get("term", "");
        $madeby     = $request->get("scraper_made_by", 0);
        $scrapeType = $request->get("scraper_type", 0);
 
        // $brands = Brand::leftJoin("store_website_brands as swb","swb.brand_id","brands.id")
        // ->leftJoin("store_websites as sw","sw.id","swb.store_website_id")
        // ->leftJoin("products as p","p.brand","brands.id")
        // ->select(["brands.*",\DB::raw("group_concat(sw.id) as selling_on"),\DB::raw("LOWER(trim(brands.name)) as lower_brand"), \DB::raw('COUNT(p.id) as total_products')])
        // ->groupBy("brands.id")
        // ->orderBy('total_products',"desc")->whereNull('brands.deleted_at');

        $brands = Brand::leftJoin("products as p","p.brand","brands.id")
        ->select(["brands.*",\DB::raw("LOWER(trim(brands.name)) as lower_brand"), \DB::raw('COUNT(p.id) as total_products')])
        ->groupBy("brands.id")
        ->orderBy('total_products',"desc")->whereNull('brands.deleted_at');

        $keyword = request('keyword');
        if (!empty($keyWord)) {
            $brands->where(function ($q) use ($keyWord) {
                $q->where("brands.name", "like", "%{$keyWord}%");
            });
        }

        $brands = $brands->paginate(Setting::get('pagination'));

        $filters = $request->all();

        return view('brand.scrap_brand', compact('brands','filters'));
    }

    private static function get_times($default = '19:00', $interval = '+60 minutes')
    {

        $output = [];

        $current = strtotime('00:00');
        $end     = strtotime('23:59');

        while ($current <= $end) {
            $time          = date('G', $current);
            $output[$time] = date('h.i A', $current);
            $current       = strtotime($interval, $current);
        }

        return $output;
    }

    public function create()
    {
        $data[ 'name' ] = '';
        $data[ 'euro_to_inr' ] = '';
        $data[ 'deduction_percentage' ] = '';
        $data[ 'magento_id' ] = '';
        $data[ 'brand_segment' ] = '';
        $data[ 'brand_segment' ] = '';
        $data[ 'brand_segment' ] = '';
        $data[ 'category_segments'] = CategorySegment::where('status', 1)->get();
        $data[ 'amount' ] = '';
        $data[ 'modify' ] = 0;

        return view('brand.form', $data);
    }


    public function edit(Brand $brand)
    {
        $data = $brand->toArray();
        $data[ 'category_segments'] = CategorySegment::where('status', 1)->get();
        $category_segment_discount = DB::table('category_segment_discounts')->where('brand_id', $brand->id)->first();
        if($category_segment_discount) {
            $data[ 'category_segment_id'] = $category_segment_discount->id;
            $data[ 'amount' ] = $category_segment_discount->amount;
        } else {
            $data[ 'category_segment_id'] = '';
            $data['amount'] = '';
        }
        $data[ 'modify' ] = 1;

        return view('brand.form', $data);
    }


    public function store(Request $request, Brand $brand)
    {

        $this->validate($request, [
            'name' => 'required',
            'euro_to_inr' => 'required|numeric',
            'deduction_percentage' => 'required|numeric',
            'magento_id' => 'required|numeric',
        ]);

        $data = $request->except('_token', '_method', 'category_segment_id', 'amount');

        $brand = $brand->create($data);

        DB::table('category_segment_discounts')->insert([
            ['brand_id' => $brand->id, 'category_segment_id' => $request->category_segment_id, 'amount' => $request->amount, 'amount_type' => 'percentage', 'created_at' => now(), 'updated_at' => now()]
        ]);

        return redirect()->route('brand.index')->with('success', 'Brand added successfully');
    }
    /*
    public function update(Request $request, Brand $brand)
    {

        $this->validate($request, [
            'name' => 'required',
            'euro_to_inr' => 'required|numeric',
            'deduction_percentage' => 'required|numeric',
            'magento_id' => 'required|numeric',
        ]);

        DB::table('category_segment_discounts')->where('brand_id', $brand->id)->update([
            'category_segment_id' => $request->category_segment_id,
            'amount' => $request->amount,
            'amount_type' => 'percentage',
            'updated_at' => now() 
        ]);

        $data = $request->except(['_token', '_method','references', 'category_segment_id', 'amount']);

        foreach ($data as $key => $value) {
            $brand->$key = $value;
        }
        $brand->references = $request->references; 
        $brand->update();

        $products = Product::where('brand', $brand->id)->get();

        if (count($products) > 0) {
            foreach ($products as $product) {
                if (!empty($brand->euro_to_inr)) {
                    $product->price_inr = $brand->euro_to_inr * $product->price;
                } else {
                    $product->price_inr = Setting::get('euro_to_inr') * $product->price;
                }

                $product->price_inr = round($product->price_inr, -3);
                $product->price_inr_special = $product->price_inr - ($product->price_inr * $brand->deduction_percentage) / 100;

                $product->price_inr_special = round($product->price_inr_special, -3);

                $product->save();
            }
        }

        // $uploaded_products = Product::where('brand', $brand->id)->where('isUploaded', 1)->get();
        $uploaded_products = [];

        if (count($uploaded_products) > 0) {
            foreach ($uploaded_products as $product) {
                $this->magentoSoapUpdatePrices($product);
            }
        }

        return redirect()->route('brand.index')->with('success', 'Brand updated successfully');
    }
    */
    public function destroy(Brand $brand)
    {
        $brand->scrapedProducts()->delete();
        $brand->products()->delete();
        $brand->delete();
        return redirect()->route('brand.index')->with('success', 'Brand Deleted successfully');

    }

    public static function getBrandName($id)
    {

        $brand = new Brand();
        $brand_instance = $brand->find($id);

        return $brand_instance ? $brand_instance->name : '';
    }

    public static function getBrandIds($term)
    {

        $brand = Brand::where('name', '=', $term)->first();

        return $brand ? $brand->id : 0;
    }

    public static function getEuroToInr($id)
    {

        $brand = new Brand();
        $brand_instance = $brand->find($id);

        return $brand_instance ? $brand_instance->euro_to_inr : 0;
    }

    public static function getDeductionPercentage($id)
    {

        $brand = new Brand();
        $brand_instance = $brand->find($id);

        return $brand_instance ? $brand_instance->deduction_percentage : 0;
    }

    public function magentoSoapUpdatePrices($product)
    {

        $options = array(
            'trace' => true,
            'connection_timeout' => 120,
            'wsdl_cache' => WSDL_CACHE_NONE,
            'exceptions' => 0,
        );
        $proxy = new \SoapClient(config('magentoapi.url'), $options);
        $sessionId = $proxy->login(config('magentoapi.user'), config('magentoapi.password'));

        $sku = $product->sku . $product->color;
//      $result = $proxy->catalogProductUpdate($sessionId, $sku , array('visibility' => 4));
        $data = [
            'price' => $product->price_eur_special,
            'special_price' => $product->price_eur_discounted
        ];

        $result = $proxy->catalogProductUpdate($sessionId, $sku, $data);


        return $result;
    }

    /**
     * @SWG\Get(
     *   path="/brands",
     *   tags={"Scraper"},
     *   summary="List all brands and reference for scraper",
     *   operationId="scraper-get-brands-reference",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    public function brandReference()
    {
        $brands = Brand::select('name','references')->get();
        foreach ($brands as $brand) {
            $referenceArray[] = $brand->name;
            if(!empty($brand->references)){
                $references = explode(';', $brand->references);
                if(is_array($references)){
                    foreach($references as $reference){
                        if($reference != null && $reference != ''){
                         $referenceArray[] = $reference;
                        }
                    }
                }
                
            }
        }

        
       return json_encode($referenceArray);

    }

    public function attachWebsite(Request $request)
    {
        $website = $request->get("website");
        $brandId = $request->get("brand_id");

        if(!empty($website) && !empty($brandId)) {
             
             if(is_array($website)) {
                StoreWebsiteBrand::where("brand_id",$brandId)->whereNotIn("store_website_id",$website)->delete();
                foreach ($website as $key => $web) {
                    $sbrands = StoreWebsiteBrand::where("brand_id",$brandId)
                     ->where("store_website_id",$web)
                     ->first();

                    if(!$sbrands)  {
                        $sbrands = new StoreWebsiteBrand;
                        $sbrands->brand_id = $brandId;
                        $sbrands->store_website_id = $web;
                        $sbrands->save();
                    }
                }

                return response()->json(["code" => 200 , "data" => [], "message" => "Website attached successfully"]);
             }else{
                return response()->json(["code" => 500 , "data" => [], "message" => "There is no website selected"]);
             }
        }

        return response()->json(["code" => 500 , "data" => [], "message" => "Oops, something went wrong"]);
    }
    /*
    public function updateReference(Request $request)
    {
        $reference = $request->get("reference");
        $brandId = $request->get("brand_id");
        if(is_array($reference)){
            $reference = implode(',', $reference);
        }
        if(!empty($brandId)) {
                $success = Brand::where("id",$brandId)->update(['references'=>$reference]);
                return response()->json(["code" => 200 , "data" => [], "message" => "Reference updated successfully"]);
        }

        return response()->json(["code" => 500 , "data" => [], "message" => "Oops, something went wrong"]);
    }
    */
    public function createRemoteId(Request $request, $id)
    {
        $brand = \App\Brand::where("id",$id)->first();
        
        if(!empty($brand)) {
            if($brand->magento_id == '' || $brand->magento_id <= 0) {
                $brand->magento_id = 10000 + $brand->id;
                $brand->save(); 
                return response()->json(["code" => 200, "data" => $brand, "message" => "Remote id created successfully"]);
            }else{
                return response()->json(["code" => 500, "data" => $brand, "message" => "Remote id already exist"]);
            }
        }

        return response()->json(["code" => 500, "data" => $brand, "message" => "Brand not found"]);

    }

    public function changeSegment(Request $request) 
    {
        $id = $request->get("brand_id",0);
        $brand = \App\Brand::where("id",$id)->first();
        $segment = $request->get("segment");

        if($brand) {
           $brand->brand_segment = $segment;
           $brand->save();
           return response()->json(["code" => 200 , "data" => []]);
        }

        return response()->json(["code" => 500 , "data" => []]);
    }

    public function mergeBrand(Request $request)
    {
        if($request->from_brand && $request->to_brand) {
            $fromBrand  = \App\Brand::find($request->from_brand);
            $toBrand    = \App\Brand::find($request->to_brand);

            if($fromBrand && $toBrand) {
                $product = \App\Product::where("brand",$fromBrand->id)->get();
                if(!$product->isEmpty()) {
                    foreach($product as $p) {
                         $p->brand =  $toBrand->id;  
                         $p->save();
                    }
                }

                // now store the all brands
                $freferenceBrand = explode(",", $fromBrand->references);
                $treferenceBrand = explode(",", $toBrand->references);


                $mReference =  array_merge($freferenceBrand,$treferenceBrand);
                $toBrand->references = implode(",",array_unique($mReference));
                $toBrand->save();
                $fromBrand->delete();
                Activity::create([
                    'subject_type' => "Brand",
                    'subject_id' => $fromBrand->id,
                    'causer_id' => Auth::user()->id,
                    'description' => Auth::user()->name ." has merged ".$fromBrand->name. " to ".$toBrand->name
                ]);
                return response()->json(["code" => 200 , "data" => []]);
            }
        }

        return response()->json(["code" => 500 , "data" => [],"message" => "Please check valid brand exist"]);

    }

    public function unMergeBrand(Request $request)
    {
        $this->validate($request, [
            'brand_name' => 'required',
            'from_brand_id' => 'required'
        ]);
        
        $fromBrand = \App\Brand::find($request->from_brand_id);

        if($fromBrand) {
            // now store the all brands
            $freferenceBrand = explode(",", $fromBrand->references);

            if (($key = array_search($request->brand_name, $freferenceBrand)) !== false) {
                unset($freferenceBrand[$key]);
            }

            $fromBrand->references = implode(',', $freferenceBrand);
            $fromBrand->save();

            $brand_count = Brand::where('name', '=', $request->brand_name)->count();
            if($brand_count == 0) {
                $oldBrand = Brand::where('name', '=', $request->brand_name)->onlyTrashed()->latest()->first();
                if($oldBrand) {
                    $oldBrand->references = null;
                    $oldBrand->deleted_at = null;
                    $oldBrand->save();
                    $scrapedProducts = ScrapedProducts::where('brand_id', $oldBrand->id)->get();
                    foreach($scrapedProducts as $scrapedProduct) {
                        $product = \App\Product::where("id", $scrapedProduct->product_id)->first();
                        if($product) {
                            $product->brand = $oldBrand->id;
                            $product->save();
                        }
                    }
                }else{
                    $newBrand = new Brand();
                    $newBrand->name = $request->brand_name;
                    $newBrand->euro_to_inr = 0;
                    $newBrand->deduction_percentage = 0;
                    $newBrand->magento_id = 0;
                    $newBrand->save();
                }
            } else {
                return response()->json(['message' => 'Brand unmerged successfully'], 200);
            }
            Activity::create([
                'subject_type' => "Brand",
                'subject_id' => $fromBrand->id,
                'causer_id' => Auth::user()->id,
                'description' => Auth::user()->name ." has unmerged ".$fromBrand->name. " to ".$request->brand_name
            ]);
            return response()->json(['message' => 'Brand unmerged successfully',  "data" => []], 200);
        }

        return response()->json(["code" => 500 , "data" => [],"message" => "Please check valid brand exist"]);

    }

    public function storeCategorySegmentDiscount(Request $request) {
        $category_segment = DB::table('category_segment_discounts')->where('brand_id', $request->brand_id)->where('category_segment_id', $request->category_segment_id)->first();
        if($category_segment) {
            return DB::table('category_segment_discounts')->where('brand_id', $request->brand_id)->where('category_segment_id', $request->category_segment_id)->update([
                'amount' => $request->amount,
                'amount_type' => 'percentage',
                'updated_at' => now() 
            ]);
        } else {
            return DB::table('category_segment_discounts')->insert([
                ['brand_id' => $request->brand_id, 'category_segment_id' => $request->category_segment_id, 'amount' => $request->amount, 'amount_type' => 'percentage', 'created_at' => now(), 'updated_at' => now()]
            ]);
        }
    }

    public function activites(Request $request, $id) {
        $activites = Activity::where('subject_id',$id)->where('subject_type','Brand')->get();
        return view()->make('brand.activities', compact('activites'));
    }

    public function priority(Request $request)
    {
        $brand = Brand::find($request->id);
      $brand->priority = $request->priority;
      if($brand->save())
      {
        return response()->json(['message' => 'Brand priority updated'], 200);
      }
      
    }

    public function fetchNewBrands(Request $request){
        $path = public_path('brands');
        $files = File::allFiles($path);
        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $files) {
                $image_name = $files->getClientOriginalName();
                $brand_name = strtoupper(pathinfo($image_name, PATHINFO_FILENAME));
                $brand_found = Brand::where('name',$brand_name)->get();
                if(!$brand_found->isEmpty()){
                    $media = MediaUploader::fromSource($files)
                    ->toDirectory('brands')
                    ->upload();
                    // Brand::where('id', $brand_found[0]->id)->update(['brand_image' => env('APP_URL').'/brands/'.$image_name]);
                    Brand::where('id', $brand_found[0]->id)->update(['brand_image' => config('env.APP_URL').'/brands/'.$image_name]);
                }
            }
            return response()->json(["code" => 200, "success" => "Brand images updated"]);
        }else{
            return response()->json(["code" => 500, "error" => "Oops, Please fillup required fields"]);
        }
    }
    
    //START - Purpose : Fetch data - DEVTASK-4278
    public function fetchlogos(Request $request)
    {
        try{
            // $brand_data = Brand::paginate(Setting::get('pagination'));
            $brand_data = Brand::leftjoin('brand_with_logos','brands.id','brand_with_logos.brand_id')
            ->leftjoin('brand_logos','brand_with_logos.brand_logo_image_id','brand_logos.id')
            ->select('brands.id as brands_id','brands.name as brands_name','brand_logos.logo_image_name as brand_logos_image')
            ->orderBy('brands.name','asc');

            if($request->brand_name)
            {
                $search='%'.$request->brand_name.'%';
                $brand_data= $brand_data->where('brands.name','like',$search);
            }
            $brand_data = $brand_data->paginate(Setting::get('pagination'));
            return view('brand.brand_logo', compact('brand_data'))->with('i', (request()->input('page', 1) - 1) * 10);
        }catch(\Exception $e){
            
        }
    }

    public function uploadlogo(Request $request)
    {
        try{
         
            $files = $request->file('file');
            $fileNameArray = array();
            foreach($files as $key=>$file){
                //echo $file->getClientOriginalName();
                // $fileName = time().$key.'.'.$file->extension();
                $fileName = $file->getClientOriginalName();
                $fileNameArray[] = $fileName;

                $params['logo_image_name'] = $fileName;
                $params['user_id'] = Auth::id();

                $log = BrandLogo::create($params);
                
                $file->move(public_path('brand_logo'), $fileName);
            }
            return response()->json(["code" => 200, "msg" => "files uploaded successfully","data"=>$fileNameArray]);
        }catch(\Exception $e){
            
        }
    }

    public function get_all_images(Request $request)
    {
        try{
            // $brand_data = BrandLogo::get();
            $brand_data = BrandLogo::leftjoin('brand_with_logos','brand_logos.id','brand_with_logos.brand_logo_image_id')
            ->select('brand_logos.id as brand_logos_id','brand_logos.logo_image_name as brand_logo_image_name','brand_with_logos.id as brand_with_logos_id','brand_with_logos.brand_logo_image_id as brand_with_logos_brand_logo_image_id','brand_with_logos.brand_id as brand_with_logos_brand_id')
            ->where('brand_logos.logo_image_name','like','%'.$request->brand_name.'%')
            ->get();
            return response()->json(["code" => 200, "brand_logo_image"=>$brand_data]);
        }catch(\Exception $e){
            
        }
    }

    public function set_logo_with_brand(Request $request){
        try{
            $brand_id = $request->logo_id;
            $logo_image_id = $request->logo_image_id;

            $brand_logo_data = BrandWithLogo::updateOrCreate(
                [
                    'brand_id' => $brand_id,
                ],
                [
                    'brand_id'   => $brand_id,
                    'brand_logo_image_id'   => $logo_image_id,
                    'user_id' => Auth::id(),
                ]
            );

            $brand_logo_image = BrandLogo::where('id',$brand_logo_data->brand_logo_image_id)->select('logo_image_name')->first();

            return response()->json(["code" => 200, "message"=>'Logo Set Sucessfully for this Brand.',"brand_logo_image" => $brand_logo_image->logo_image_name]);

        }catch(\Exception $e){
            
        }
    }

    public function remove_logo(Request $request){
        try{
            $brand_id = $request->brand_id;

            $record = BrandWithLogo::where('brand_id',$brand_id);
            $record->delete();  

            return response()->json(["code" => 200, "message"=>'Logo has been Removed Sucessfully.']);

        }catch(\Exception $e){
            
        }
    }
    //END - DEVTASK-4278
}

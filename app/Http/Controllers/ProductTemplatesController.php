<?php

namespace App\Http\Controllers;

use App\BroadcastImage;
use File;
use Illuminate\Http\Request;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use App\Setting;
use App\ProductTemplate;
use App\Template;
use App\Category;
use App\Product;
use App\StoreWebsite;

class ProductTemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$productTemplates = \App\ProductTemplate::orderBy("id", "desc")->paginate(10);
        $images = $request->get('images', false);
        $productArr = null;
        if ($images) {
            $productIdsArr = \DB::table('mediables')
                                ->whereIn('media_id', json_decode($images))
                                ->where('mediable_type', 'App\Product')
                                ->pluck('mediable_id')
                                ->toArray();
            
            if (!empty($productIdsArr)) {
                $productArr = \App\Product::select('id', 'name', 'sku', 'brand')->whereIn('id', $productIdsArr)->get();
            }
        }

        //echo '<pre>';print_r($templateArr->toArray());die;

        $texts = \App\ProductTemplate::where('text',"!=" ,"")->groupBy('text')->pluck('text','text')->toArray();
        $backgroundColors = \App\ProductTemplate::where('background_color',"!=" ,"")->groupBy('background_color')->pluck('background_color','background_color')->toArray();

        $templateArr = \App\Template::all();

        $templatesJSON=\App\Template::with('modifications')->get()->toArray();

       // echo json_encode($templateArr);die;


        return view("product-template.index", compact('templateArr', 'productArr', 'texts' , 'backgroundColors','templatesJSON'));
    }

    public function response()
    {
        $keyword = request('keyword');

        $records = \App\ProductTemplate::leftJoin('brands as b','b.id','product_templates.brand_id')->leftJoin("store_websites as sw","sw.id","product_templates.store_website_id"); 

        if(!empty($keyword)) {
            $records = $records->where(function($q) use($keyword) {
                $q->orWhere('product_templates.product_title','like','%'.$keyword.'%')->orWhere('product_templates.text','like','%'.$keyword.'%')->orWhere('product_templates.product_id','like','%'.$keyword.'%');
            });
        }
        $records = $records->orderBy("id", "desc")
        ->select(["product_templates.*","b.name as brand_name","sw.title as website_name"])
        ->paginate(Setting::get('pagination')); 

        $array = [];
        foreach($records as $record) {
            if($record->hasMedia('template-image')) {
                $media = $record->getMedia('template-image')->first();
                 if(!empty($media))  {
                    $record->image_url = $media->getUrl();
                 }
            }
        }

        return response()->json([
            "code" => 1,
            "result" => $records,
            "pagination" => (string)$records->appends(request()->except('page')),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * function is renamed from create to previous_create after implement bearbanner api
     */
    public function previous_create(Request $request)
    {
       


        $template = new \App\ProductTemplate;
        $params = request()->all();
        if(empty($params['product_id'])) {
           $params['product_id'] = [];
        }
        $params['product_id'] = implode(',', (array)$params['product_id']);
        if(isset($params['background_color']) && is_array($params['background_color'])) {
            $params['background_color'] = implode(',', (array)$params['background_color']);
        }

        $template->fill($params);

        if ($template->save()) {

            if (!empty($request->get('product_media_list')) && is_array($request->get('product_media_list'))) {
                foreach ($request->get('product_media_list') as $mediaid) {
                    $media = Media::find($mediaid);
                    $template->attachMedia($media, ['template-image']);
                }
            }

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $image) {
                    $media = MediaUploader::fromSource($image)->toDirectory('product-template-images')->upload();
                    $template->attachMedia($media,['template-image']);
                }
            }
        }

        return response()->json(["code" => 1, "message" => "Product Template Created successfully!"]);
    }

   




    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $template = \App\ProductTemplate::where("id", $id)->first();

        if ($template) {
            $template->delete();
        }

        return response()->json(["code" => 1, "message" => "Product Template Deleted successfully!"]);
    }

    /**
     * @SWG\Get(
     *   path="/product-template",
     *   tags={"Product Template"},
     *   summary="Get Product Template",
     *   operationId="get-product-template",
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
    public function apiIndex(Request $request)
    {
        $record = \App\ProductTemplate::where('is_processed','!=',2)->where('template_status','python')->latest()->first();


        if(!$record) {
            $data = ['message' => 'Template not found'];
            return response()->json($data);
        }
        
        if($record->category) {
            $category = $record->category;
            // Get other information related to category
            $cat = $category->title;
        }

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
        $productCategory = $parent.' '.$child;

        $data = [];
        //check if template exist
        $templateProductCount = $record->template->no_of_images;
        
        // if($record->getMedia('template-image')->count() <= $templateProductCount && $templateProductCount > 0){
        //     $data = ['message' => 'Template Product Doesnt have Proper Images'];
        //     return response()->json($data);
        // }

        $record->is_processed = 2;
        $record->save();
        
        if ($record) {
            $data = [
                "id" => $record->id,
                "templateNumber" => $record->template_no,
                "productTitle" => $record->product_title,
                "productBrand" => ($record->brand) ? $record->brand->name : "",
                "productCategory" => $productCategory,
                "productPrice" => $record->price,
                "productDiscountedPrice" => $record->discounted_price,
                "productCurrency" => $record->currency,
                "text" => $record->text,
                "fontStyle" => $record->font_style,
                "fontSize" => $record->font_size,
                "backgroundColor" => explode(",", $record->background_color),
                "color" => $record->color,
                "logo" => ($record->storeWebsite) ? $record->storeWebsite->title : ""
            ];

            if ($record->hasMedia('template-image-attach')) {
                $images = [];
                foreach ($record->getMedia('template-image-attach') as $i => $media) {
                    $images[] = $media->getUrl();
                }
                $data[ "image" ] = $images;
            }
        }

        return response()->json($data);

    }

    /**
     * @SWG\Post(
     *   path="/product-template",
     *   tags={"Product Template"},
     *   summary="Save Product Template",
     *   operationId="save-product-template",
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
    public function apiSave(Request $request)
    {
        // Try to get ID from 'product_id' (this will be changed to id)
        $id = $request->post("product_id", 0);

        // Try to get ID from 'id' if no id is set
        if ( (int) $id == 0 ) {
            $id = $request->post("id", 0);
        }

        // Only do queries if we have an id
        if ( (int) $id > 0 ) {
            $template = \App\ProductTemplate::where("id", $id)->first();

            if ($template) {
                if ($request->post('image')) {
                    $image = base64_decode($request->post('image'));
                    $media = MediaUploader::fromString($image)->toDirectory(date('Y/m/d'))->useFilename('product-template-' . $id)->upload();
                    $template->attachMedia($media,'template-image');
                    $template->is_processed = 1;
                    $template->save();

                    // Store as broadcast image
                    $broadcastImage = new BroadcastImage();
                    $broadcastImage->products = '[' . $template->product_id . ']';
                    $broadcastImage->save();
                    $broadcastImage->attachMedia($media, config('constants.media_tags'));

                    //Save Product For Image In Mediable
                    if($template->product_id != null){
                        $product = Product::find($template->product_id);
                        $tag = 'template_'.$template->template_no;
                        $product->attachMedia($media, $tag);
                    }
                    
                    return response()->json(["code" => 1, "message" => "Product template updated successfully"]);
                }
            } else {
                return response()->json(["code" => 0, "message" => "Sorry, can not find product template in record"]);
            }
        }

        return response()->json(["code" => 0, "message" => "An unknown error has occured"]);

    }

    public function NewApiSave(Request $request)
    {
        $validator = \Validator::make($request->all(), [
           'text'       =>  'required',
           'backgroundColor' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(["code" => 500, "message" => 'Invalid request',"error" => $validator->errors()]);
        }

        $new = array(
            'text' => request('text'),
            'background_color' => request('backgroundColor'),
        );

        $template = ProductTemplate::insertGetId($new);
        $template = \App\ProductTemplate::where("id", $template)->first();
        if( $template ){
            return response()->json($template);
        }
        
        return response()->json(["code" => 0, "message" => "An unknown error has occured"]);

    }

    /**
     * Show the image for selecting product id.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectProductId(Request $request)
    {
        $html = '';
        $productId = $request->get('product_ids');
        if ($productId) {
            $productArr = \App\Product::whereIn('id', $productId)->get();
            if ($productArr) {
                foreach ($productArr as $product) {
                    foreach ($product->media as $k => $media) {
                        $html .= '<div class="col-sm-3" style="padding-bottom: 10px;">
                                    <div class="imagePreview">
                                        <img src="' . $media->getUrl() . '" width="100%" height="100%">
                                    </div>
                                    <label class="btn btn-primary">
                                        <input type="checkbox" name="product_media_list[]" value="' . $media->id . '" class="product_media_list"> Select
                                    </label>
                                </div>';
                    }
                }
            }
        }
        return response()->json(["data" => $html]);
    }

    public function imageIndex(Request $request)
    {
        $temps = Template::all();
        if($request->template || $request->brand || $request->category){
            
            $query = ProductTemplate::query();

            if(!empty($request->template)){
                $query->where('template_no',$request->template);
            }
            
            if(!empty($request->brand)){
                $query->whereIn('brand_id',$request->brand);
            }
            
            if(!empty($request->category && $request->category[0] != 1)){
                $query->whereIn('category_id',$request->category);
            }

            $range = explode(' - ', request('date_range'));

            if($range[0] == end($range)){
                $query->whereDate('updated_at', end($range));
            }else{
                $start = str_replace('/', '-', $range[0]);
                $end = str_replace('/', '-', end($range));
                $query->whereBetween('updated_at', array($start,$end));
            }
            
            $templates = $query->where('is_processed',1)->orderBy('updated_at','desc')->paginate(Setting::get('pagination'))->appends(request()->except(['page']));
        }else{
           $templates = ProductTemplate::where('is_processed',1)->orderBy('updated_at','desc')->paginate(Setting::get('pagination')); 
        }
        
        // if ($request->ajax()) {
        //     return response()->json([
        //         'tbody' => view('product-template.partials.type-list-template', compact('templates','temps'))->render(),
        //         'links' => (string)$templates->render(),
        //         'total' => $templates->total(),
        //     ], 200);
        // }

        $selected_categories = $request->category ? $request->category : 1;

        $category_selection = Category::attr(['name' => 'category[]', 'class' => 'form-control select-multiple2'])
            ->selected($selected_categories)
            ->renderAsDropdown();

        return view('product-template.image',compact('templates','temps','category_selection'));
    }


     public function create(Request $request)
    {
        // dd( $request->store_website_id );
        $template = new \App\ProductTemplate;
        $params = request()->all();
        $imagesArray=[];
        if(empty($params['product_id'])) {
           $params['product_id'] = [];
        }

        $params['product_id'] = implode(',', (array)$params['product_id']);
        if( $request->modifications_array ){
            $params['background_color']  = $request->modifications_array[0]['background'] ?? null;
            $params['text']  = $request->modifications_array[0]['text'] ?? null;
            $params['color']  = $request->modifications_array[0]['color'] ?? null;
        }

        $template->fill($params);

        if ($template->save()) {

            $StoreWebsite = StoreWebsite::where('id',$request->store_website_id)->first();

            if (!empty($request->get('product_media_list')) && is_array($request->get('product_media_list'))) {
                foreach ($request->get('product_media_list') as $mediaid) {
                    $media = Media::find($mediaid);
                    $template->attachMedia($media, ['template-image-attach']);
                    $template->save();

                    $StoreWebsite->attachMedia($media, ['website-image-attach']);

                    $imagesArray[]=$media->getUrl();
                }
            }

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $image) {
                    $media = MediaUploader::fromSource($image)->toDirectory('product-template-images')->upload();

                    $template->attachMedia($media,['template-image-attach']);
                    $template->save();
                    $imagesArray[]=$media->getUrl();

                    $StoreWebsite->attachMedia($media, ['website-image-attach']);
                }
            }

            if( $request->generate_image_from == 'banner-bear' ){
                return $res = $this->makeBearBannerImage($request,$imagesArray,$template);
            }else{
                $template->template_status = 'python';
                $template->save();

                //call here the api
                if($template->category) {
                    $category = $template->category;
                    // Get other information related to category
                    $cat = $category->title;
                }

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
                $productCategory = $parent.' '.$child;

                $data = [];
                //check if template exist
                $templateProductCount = $template->template->no_of_images;
                
                // if($record->getMedia('template-image')->count() <= $templateProductCount && $templateProductCount > 0){
                //     $data = ['message' => 'Template Product Doesnt have Proper Images'];
                //     return response()->json($data);
                // }

                $template->is_processed = 2;
                $template->save();
                
                if ($template) {
                    try {
                        $data = [
                            "id" => $template->id,
                            "templateNumber" => $template->template_no,
                            "productTitle" => $template->product_title,
                            "productBrand" => ($template->brand) ? $template->brand->name : "",
                            "productCategory" => $productCategory,
                            "productPrice" => $template->price,
                            "productDiscountedPrice" => $template->discounted_price,
                            "productCurrency" => $template->currency,
                            "text" => $template->text,
                            "fontStyle" => $template->font_style,
                            "fontSize" => $template->font_size,
                            "backgroundColor" => explode(",", $template->background_color),
                            "color" => $template->color,
                            "logo" => ($template->storeWebsite) ? $template->storeWebsite->title : ""
                        ];

                        if ($template->hasMedia('template-image-attach')) {
                            $images = [];
                            foreach ($template->getMedia('template-image-attach') as $i => $media) {
                                $images[] = $media->getUrl();
                            }
                            $data[ "image" ] = $images;
                        }
                        \Log::info(json_encode($data,true));
                        $response = \App\Helpers\GuzzleHelper::post(env("PYTHON_PRODUCT_TEMPLATES")."/api/product-template", $data,[]);
                    }catch(\Exception $e) {
                        \Log::info("Product Templates controller : 541 ".$e->getMessage());
                    }
                    
                }

            }
        }

        return response()->json(["code" => 1, "message" => "Product Template Created successfully!"]);
    }


    public function makeBearBannerImage($request,$imagesArray,$template)
    {

       // echo '<pre>';print_r(json_encode($request->modifications_array));die;

        try {
            
       
            $modifications=[];

            if($request->modifications_array)
            {
               foreach ($request->modifications_array as $key => $value) {
                   array_push($modifications, $value);
               }
            }

           if(count($imagesArray))
           {
                 foreach ($imagesArray as $key => $image_url) {
                  $key=$key+1;
                    //$row=$image_url;
                    array_push($modifications,array('name'=>'product_'.$key,'image_url'=>$image_url));

                 }
           }

            $body=array('template'=>$template->template->uid,'modifications'=>$modifications,'webhook_url'=>route('api.product.update.webhook'),'metadata'=>$template->id);


            $url=env('BANNER_API_LINK').'/images';
            $api_key=env('BANNER_API_KEY');

            $headers=   [
                    'Authorization' => 'Bearer ' . $api_key,
                    'Content-Type' => 'application/json'
                ];

            $response = \App\Helpers\GuzzleHelper::post($url,$body,$headers);
            
            if( isset( $response->uid ) ){
                ProductTemplate::where('id',$template->id)->update([ 'uid' => $response->uid, 'is_processed' => 2, 'template_status' => $response->status ]);
            }
            return response()->json(["code" => 1, "message" => "Product Template Created successfully!"]);

        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return response()->json(["code" => 0, "message" => json_decode($e->getResponse()->getBody()->getContents())->message]);
        }
        
    }

    public function updateWebhook(Request $request)
    {
         $header = $request->header('Authorization', 'default');

        if($header=='Bearer '.env('BANNER_WEBHOOK_KEY'))
        {
             if($request->metadata)
             {
                $template=ProductTemplate::find($request->metadata);

                $template->template_status=$request->status;

                $contents = $this->getImageByCurl($request->image_url_png);

               $media= MediaUploader::fromString($contents)->useFilename('profile')->toDirectory('product-template-images')->upload();


              $template->attachMedia($media,['template-image']);

              $template->template_status=$request->status;
              
              $template->save();


             }
        }
    }

    public function fetchImage(Request $request)
    {   
        try {
            $url=env('BANNER_API_LINK').'/images/'.$request->uid;
            $api_key=env('BANNER_API_KEY');

            $headers=   [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json'
            ];

            $response = \App\Helpers\GuzzleHelper::get($url,$headers);
            
            if( isset( $response->uid ) ){

                $template = ProductTemplate::where('id',$response->metadata)->first();
                
                $path = $response->image_url_png;
                $filename = basename($path);
                $media = MediaUploader::fromSource($image)->toDirectory(date('Y/m/d'))->useFilename($filename)->upload();
                $template->attachMedia($media,'template-image');
                $template->save();

                ProductTemplate::where('id',$response->metadata)->where( 'uid', $response->uid )->update([ 'template_status' => $response->status, 'is_processed' => 1, 'image_url' => $response->image_url_png ]);
            }
            return response()->json(["code" => 1, "message" => "Image fetched successfully!" , "image" => $response->image_url_png ]);

        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return response()->json(["code" => 0, "message" => json_decode($e->getResponse()->getBody()->getContents())->message]);
        }
    }

    public function getImageByCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

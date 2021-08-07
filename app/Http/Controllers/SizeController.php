<?php

namespace App\Http\Controllers;

use App\Size;
use Illuminate\Http\Request;
use App\UnknownSize;

class SizeController extends Controller
{
    //

    public function __construct()
    {
        //  $this->middleware('permission:brand-edit', ['only' => 'index', 'create', 'store', 'destroy', 'update', 'edit']);
    }

    public function index()
    {
        $title         = "Size";
        $storeWebsites = \App\StoreWebsite::pluck('website', 'id')->toArray();

        return view('size.index', compact('title', 'storeWebsites'));
    }

    public function records(Request $request)
    {
        $records = Size::query();

        if ($request->keyword != null) {
            $records = $records->where("name", "like", "%" . $request->keyword . "%");
        }

        $records = $records->paginate(25);

        $items = [];
        foreach ($records->items() as &$item) {
            $item->store_wesites = [];
            $stores              = [];
            if (!$item->storeWebsitSize->isEmpty()) {
                foreach ($item->storeWebsitSize as $sws) {
                    $stores[] = $sws->storeWebsite->title . "#" . $sws->platform_id;
                }
            }
            $item->store_wesites = $stores;
            $items[]             = $item;
        }

        return response()->json(["code" => 200, "data" => $items, "total" => $records->total(), "pagination" => (string) $records->render()]);
    }

    public function store(Request $request)
    {
        $params = $request->all();
        if (!empty($params['name'])) {

            $size = \App\Size::find($request->get("id", 0));
            if (!$size) {
                $size = new \App\Size;
            }

            $size->fill($params);

            if ($size->save()) {
                \App\StoreWebsiteSize::where("size_id", $size->id)->delete();
                $websites = array_filter($request->get('store_website'));
                if (!empty($websites)) {
                    foreach ($websites as $k => $p) {
                        $sws                   = new \App\StoreWebsiteSize;
                        $sws->platform_id      = $p;
                        $sws->store_website_id = $k;
                        $sws->size_id          = $size->id;
                        $sws->save();
                    }
                }
            }

            return response()->json(["code" => 200, "data" => $size]);
        }
        return response()->json(["code" => 500, "data" => [], "message" => "Name is required"]);
    }

    public function edit($id)
    {

        $size = \App\Size::where("id", $id)->first();

        if ($size) {

            $stores = $size->storeWebsitSize;

            $arr = [];
            if (!$stores->isEmpty()) {
                foreach ($stores as $store) {
                    $arr["store_" . $store->store_website_id] = $store->platform_id;
                }
            }

            return response()->json(["code" => 200, "data" => $size, 'stores' => $arr]);
        }

        return response()->json(["code" => 500, "error" => "Wrong row id!"]);

    }

    public function delete(Request $request, $id)
    {
        $size = \App\Size::where("id", $id)->first();

        if ($size) {
            \App\StoreWebsiteSize::where("size_id", $size->id)->delete();
            $size->delete();
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "error" => "Wrong row id!"]);
    }

    public function pushToStore(Request $request)
    {
        $id = $request->get("id", 0);

        if ($id > 0) {
            $website = \App\StoreWebsite::where("website_source", "magento")->where("api_token", "!=", "")->get();
            $size    = \App\Size::where("id", $id)->first();

            if (!$website->isEmpty()) {
                foreach ($website as $web) {
                    // check we set the size already or not first and then push for store
                    $checkSite = \App\StoreWebsiteSize::where("size_id", $size->id)->where("store_website_id", $web->id)->where("platform_id",">",0)->first();
                    if (!$checkSite) {
                        \App\StoreWebsiteSize::where("size_id", $size->id)->where("store_website_id", $web->id)->delete();
                        $id                    = \seo2websites\MagentoHelper\MagentoHelper::addSize($size, $web);
                        $sws                   = new \App\StoreWebsiteSize;
                        $sws->size_id          = $size->id;
                        $sws->store_website_id = $web->id;
                        $sws->platform_id      = $id;
                        $sws->save();
                    }
                }
            }

            return response()->json(["code" => 200, "data" => $size]);

        }

        return response()->json(["code" => 500, "error" => "Wrong row id!"]);

    }

    public function sizeReference(Request $request)
    {

        $sizes = Size::all();
        $unknownSizes = UnknownSize::query();
        if($request->search){
            $unknownSizes = $unknownSizes->where('size','LIKE','%'.$request->search.'%');
        }
        $unknownSizes = $unknownSizes->paginate(50);
        return view('size.reference', compact('sizes', 'unknownSizes'));
    }

    public function referenceAdd(Request $request)
    {
        $size = UnknownSize::where('size',$request->from)->first();
        $sizeFrom = new Size;
        $sizeFrom->name = $size->size;
        $sizeFrom->save();
        $size->delete();
        return response()->json(["code" => 200, "data" => 'Its changed']);
    }

    public function usedProducts(Request $request)
    {
        $q = $request->id;
            $UnknownSize = \App\UnknownSize::find($q);
        if($q) {
            // check the type and then 
           $q = '"'.$q.'"';
           $products = \App\ScrapedProducts::where("properties","like",'%'.$UnknownSize->size.'%')->latest()->limit(5)->get();

           $view = (string)view("compositions.preview-products",compact('products'));
           return response()->json(["code" => 200, "html" => $view]);
        }

        return response()->json(["code" => 200, "html" => ""]);
    }

    public function affectedProduct(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        if (!empty($from) && !empty($to)) {
            // check the type and then
            $q     = '"'.$from.'"';
            $total = \App\ScrapedProducts::where("properties", "like", '%' . $q . '%')
                ->join("products as p", "p.sku", "scraped_products.sku")
                ->where("p.composition", "")
                ->groupBy("p.id")
                ->get()->count();

            $view = (string) view("size.partials.affected-products", compact('total', 'from', 'to'));

            return response()->json(["code" => 200, "html" => $view]);
        }
    }

    public function updateSizes(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;
        
        $to = \App\Size::find($to);
        
        $oldReference = $to->references;

        $updateWithProduct = $request->with_product;

        if ($updateWithProduct == "yes") {
            \App\Jobs\UpdateSizeFromErp::dispatch([
                "from"    => $from,
                "to"      => $to->name,
                "user_id" => \Auth::user()->id,
            ])->onQueue("supplier_products");
        }

        //$c = Size::where("name",$to)->first();
        if(empty($oldReference)) {
            $to->references = $from;
            $to->save();
        }else{
            $to->references = $oldReference.','.$from;
            $to->save(); 
        }

        //removing from unknown sizes
        $si = UnknownSize::where('size',$from)->first();
        $si->delete();

        return response()->json(["code" => 200, "message" => "Your request has been pushed successfully"]);
    }
}

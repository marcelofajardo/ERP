<?php

namespace Modules\StoreWebsite\Http\Controllers;

use App\Http\Controllers\Controller;
use App\StoreWebsite;
use App\WebsiteStore;
use App\WebsiteStoreView;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class WebsiteStoreViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "Website Store View | Store Website";

        $websiteStores = WebsiteStore::all()->pluck("name", "id")->all();
        $languages = \App\Language::all()->pluck("name", "name");

        return view('storewebsite::website-store-view.index', [
            'title'         => $title,
            'websiteStores' => $websiteStores,
            'languages' => $languages,
        ]);
    }

    public function records(Request $request)
    {
        $websiteStoreViews = WebsiteStoreView::leftJoin('website_stores as ws', 'ws.id', 'website_store_views.website_store_id');

        // Check for keyword search
        if ($request->keyword != null) {
            $websiteStoreViews = $websiteStoreViews->where(function ($q) use ($request) {
                $q->where("website_store_views.name", "like", "%" . $request->keyword . "%")
                    ->orWhere("website_store_views.code", "like", "%" . $request->keyword . "%");
            });
        }

        if($request->website_store_id != null) {
            $websiteStoreViews = $websiteStoreViews->where('website_store_id',$request->website_store_id);
        }

        $websiteStoreViews = $websiteStoreViews->select(["website_store_views.*", "ws.name as website_store_name"])->orderBy('website_store_views.id',"desc")->paginate();

        return response()->json(["code" => 200, "data" => $websiteStoreViews->items(), "total" => $websiteStoreViews->total(), "pagination" => (string) $websiteStoreViews->render()]);
    }

    public function store(Request $request)
    {
        $post      = $request->all();
        $validator = Validator::make($post, [
            'name'             => 'required',
            'code'             => 'required',
            'website_store_id' => 'required',
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

        $records = WebsiteStoreView::find($id);

        if (!$records) {
            $records = new WebsiteStoreView;
        }

        $post["code"] = replace_dash($post["code"]);

        $records->fill($post);
        if ($records->save()) {
            // check that store store has the platform id exist
            
        }

        return response()->json(["code" => 200, "data" => $records]);
    }

    /**
     * Edit Page
     * @param  Request $request [description]
     * @return
     */

    public function edit(Request $request, $id)
    {
        $websiteStoreView = WebsiteStoreView::where("id", $id)->first();

        if ($websiteStoreView) {
            return response()->json(["code" => 200, "data" => $websiteStoreView]);
        }

        return response()->json(["code" => 500, "error" => "Wrong site id!"]);
    }

    /**
     * delete Page
     * @param  Request $request [description]
     * @return
     */

    public function delete(Request $request, $id)
    {
        $websiteStoreView = WebsiteStoreView::where("id", $id)->first();

        if ($websiteStoreView) {
            $websiteStoreView->delete();
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "error" => "Wrong site id!"]);
    }

    public function push(Request $request, $id)
    {
        $website = WebsiteStoreView::where("id", $id)->first();

        if ($website) {
            // check that store store has the platform id exist
            if ($website->websiteStore && $website->websiteStore->platform_id > 0 && $website->websiteStore->website->platform_id > 0) {

                $id = \seo2websites\MagentoHelper\MagentoHelper::pushWebsiteStoreView([
                    "type"       => "store_view",
                    "name"       => $website->name,
                    "code"       => replace_dash(strtolower($website->code)),
                    //"website_id" => $website->websiteStore->website->platform_id,
                    "group_id"   => $website->websiteStore->platform_id,
                ], $website->websiteStore->website->storeWebsite);

                if (!empty($id) && is_numeric($id)) {
                    $website->platform_id = $id;
                    $website->save();
                }else{
                   return response()->json(["code" => 200, "data" => $website , "error" => "Website-Store-View push failed"]);
                }

                return response()->json(["code" => 200, 'message' => "Website-Store-View pushed successfully"]);
            }else{
                return response()->json(["code" => 500, "error" => "Website-Store platform id is not available!"]);
            }
        }

        return response()->json(["code" => 500, "error" => "Wrong site id!"]);
    }

}

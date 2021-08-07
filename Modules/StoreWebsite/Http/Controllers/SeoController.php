<?php

namespace Modules\StoreWebsite\Http\Controllers;

use App\StoreWebsite;
use App\StoreWebsiteSeoFormat;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class SeoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, $id)
    {
        $title = "Seo | Store Website";
        $seoFormat = StoreWebsiteSeoFormat::where("store_website_id",$id)->first();
        if(!$seoFormat) {
            $seoFormat = new StoreWebsiteSeoFormat;
            $seoFormat->store_website_id = $id;
        }

        return response()->json(["code" => 200 , "data" => $seoFormat]);
    }

    public function records(Request $request, $id)
    {
        /*$keyword = request("keyword");

        // send response into the json
        $records = StoreWebsiteGoal::join("store_websites as sw", "sw.id", "store_website_goals.store_website_id")
            ->leftJoin('store_website_goal_remarks', function ($query) {
                $query->on('store_website_goals.id', '=', 'store_website_goal_remarks.store_website_goal_id')
                    ->whereRaw('store_website_goal_remarks.id IN (select MAX(a2.id) from store_website_goal_remarks as a2 join store_website_goals as u2 on u2.id = a2.store_website_goal_id group by u2.id)');
            })

            ->where("store_website_id", $id)
            ->select(["store_website_goals.*", "sw.website", "store_website_goal_remarks.remark"]);
        //->get();

        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("store_website_goals.goal", "LIKE", "%$keyword%")
                    ->orWhere("store_website_goals.solution", "LIKE", "%$keyword%");
            });
        }

        $records = $records->get();

        return response()->json([
            "code"             => 200,
            "store_website_id" => $id,
            "data"             => $records,
        ]);*/
    }

    public function save(Request $request, $storeWebsiteId)
    {
        $post = $request->all();

        $records = StoreWebsiteSeoFormat::where("store_website_id",$storeWebsiteId)->first();

        if (!$records) {
            $records = new StoreWebsiteSeoFormat;
        }

        $records->fill($post);
        $records->store_website_id = $storeWebsiteId;
        $records->save();

        return response()->json(["code" => 200, "data" => $records]);

    }

    /**
     * Edit Page
     * @param  Request $request [description]
     * @return
     */

    public function edit(Request $request, $storeWebsiteId)
    {
        $storeWebsiteSeo = StoreWebsiteSeoFormat::where("store_website_id", $storeWebsiteId)->first();

        if ($storeWebsiteSeo) {
            return response()->json(["code" => 200, "data" => $storeWebsiteSeo]);
        }

        return response()->json(["code" => 500, "error" => "Wrong seo id!"]);
    }

}

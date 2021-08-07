<?php

namespace Modules\StoreWebsite\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\StoreDevelopment;
use App\SiteDevelopmentStatus;
use App\StoreWebsite;
class SiteDevelopmentStatusController extends Controller
{

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Site Development Status";
        
        return view("storewebsite::site-development-status.index",compact('title'));

    }

    public function records()
    {
        $records = \App\SiteDevelopmentStatus::query();

        $keyword = request("keyword");
        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("name", "LIKE", "%$keyword%");
            });
        }

        $records = $records->get();

        return response()->json(["code" => 200, "data" => $records, "total" => count($records)]);
    }

    public function save(Request $request)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'name'    => 'required'
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

        $records = SiteDevelopmentStatus::find($id);

        if (!$records) {
            $records = new SiteDevelopmentStatus;
        }

        $records->fill($post);
        $records->save();

        return response()->json(["code" => 200, "data" => $records]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'name' => 'required|string'
      ]);

      $data = $request->except('_token');

      SiteDevelopmentStatus::create($data);

      return redirect()->route('site-development-status.index')->withSuccess('You have successfully created a Site development status!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Edit Page
     * @param  Request $request [description]
     * @return
     */

    public function edit(Request $request, $id)
    {
        $modal = SiteDevelopmentStatus::where("id", $id)->first();

        if ($modal) {
            return response()->json(["code" => 200, "data" => $modal]);
        }

        return response()->json(["code" => 500, "error" => "Id is wrong!"]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * delete Page
     * @param  Request $request [description]
     * @return
     */

    public function delete(Request $request, $id)
    {
        $vendorCategory = SiteDevelopmentStatus::where("id", $id)->first();

        $isExist = \App\SiteDevelopment::where("status",$id)->first();
        if($isExist) {
            return response()->json(["code" => 500, "error" => "Status is attached to Site Development , Please update site development before delete."]);
        }

        if ($vendorCategory) {
            $vendorCategory->delete();
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "error" => "Wrong id!"]);
    }

    public function mergeStatus(Request $request) 
    {
        $toStatus     = $request->get("to_status");
        $fromStatus   = $request->get("from_status");

        if(empty($toStatus)) {
            return response()->json(["code" => 500 , "error" => "Merge status is missing"]);
        }

        if(empty($fromStatus)) {
            return response()->json(["code" => 500 , "error" => "Please select status before select merge status"]);
        }

        if(in_array($toStatus,$fromStatus)) {
           return response()->json(["code" => 500 , "error" => "Merge status can not be same"]);
        }

        $status = \App\SiteDevelopmentStatus::where("id",$toStatus)->first();
        $allMergeStatus = \App\SiteDevelopment::whereIn("status",$fromStatus)->get();

        if($status) {
            // start to merge first
            if(!$allMergeStatus->isEmpty()) {
                foreach($allMergeStatus as $amc) {
                    $amc->status_id = $status->id;
                    $amc->save();
                }
            }
            // once all merged category store then delete that category from table
            \App\SiteDevelopmentStatus::whereIn("id",$fromStatus)->delete();
        }

        return response()->json(["code" => 200 , "data" => [], "messages" => "Status has been merged successfully"]);
    }

    public function statusStats() {
        $storeWebsites = StoreWebsite::all();

        foreach($storeWebsites as $website) {
            $statusStats = \App\SiteDevelopment::join("site_development_statuses as sds","sds.id","site_developments.status")
            ->where('site_developments.website_id', $website->id)
            ->groupBy("sds.id")
            ->select(["sds.name",\DB::raw("count(sds.id) as total")])
            ->get();
            $website->statusStats = $statusStats;
        }
        $title = 'Multi site status';
        return view("storewebsite::site-development-status.status-stats",compact('title','storeWebsites'));
    }
}
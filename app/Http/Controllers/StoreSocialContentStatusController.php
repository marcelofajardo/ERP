<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
// use App\StoreDevelopment;
use App\StoreSocialContentStatus;

class StoreSocialContentStatusController extends Controller
{

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Content management Status";
        $records = \App\StoreSocialContentStatus::query();

        $keyword = request("keyword");
        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("name", "LIKE", "%$keyword%");
            });
        }

        $statuses = $records->get();
        return response()->json(['statuses' => $statuses]);
    }



    public function save(Request $request)
    {
        $post = $request->except('_token');

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
        if($id) {
            $isExtst = StoreSocialContentStatus::where('name',$request->name)->first();
            if($isExtst){
                return redirect()->back();
            }
        }

        $records = StoreSocialContentStatus::find($id);

        if (!$records) {
            $records = new StoreSocialContentStatus;
        }

        $records->fill($post);
        $records->save();

        return redirect()->back();

    }



    public function statusEdit(Request $request)
    {
        $id = $request->get("id", 0);
        if($id) {
            $isExtst = StoreSocialContentStatus::where('name',$request->name)->first();
            if($isExtst){
                return response()->json(['message' => 'Already exists'], 500);
            }
        }
        $records = StoreSocialContentStatus::find($id);
        $records->update(['name' => $request->name]);

        return response()->json(['message' => 'Successfull'], 200);

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
    if(!$request->name) {
        return redirect()->back()->with('error','Name required');
    }
    $name = $request->name;
    $name = ucfirst($name);
    $isExtst = StoreSocialContentStatus::where('name',$name)->first();
    if(!$isExtst) {
        $status = new StoreSocialContentStatus;
        $status->name = $name;
        $status->save();
    }
      

      return redirect()->back()->with('success','You have successfully created a content management status!');
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
        $modal = StoreSocialContentStatus::where("id", $id)->first();

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
        $status = StoreSocialContentStatus::where("id", $id)->first();

        $isExist = \App\StoreSocialContent::where("store_social_content_status_id",$id)->first();
        if($isExist) {
            return response()->json(["code" => 500, "error" => "Status is attached to store social contents , Please update content before delete."]);
        }

        if ($status) {
            $status->delete();
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
}

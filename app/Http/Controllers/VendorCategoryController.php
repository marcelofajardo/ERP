<?php

namespace App\Http\Controllers;

use App\VendorCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
class VendorCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Vendor Category";
        
        return view("vendor-category.index",compact('title'));

    }

    public function records()
    {
        $records = \App\VendorCategory::query();

        $keyword = request("keyword");
        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("title", "LIKE", "%$keyword%");
            });
        }

        $records = $records->get();

        return response()->json(["code" => 200, "data" => $records, "total" => count($records)]);
    }

    public function save(Request $request)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'title'    => 'required'
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

        $records = VendorCategory::find($id);

        if (!$records) {
            $records = new VendorCategory;
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
        'title' => 'required|string'
      ]);

      $data = $request->except('_token');

      VendorCategory::create($data);

      return redirect()->route('vendors.index')->withSuccess('You have successfully created a vendor category!');
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
        $modal = VendorCategory::where("id", $id)->first();

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
        $vendorCategory = VendorCategory::where("id", $id)->first();

        $isExist = \App\Vendor::where("category_id",$id)->first();
        if($isExist) {
            return response()->json(["code" => 500, "error" => "Category is attached to vendor , Please update vendor category before delete."]);
        }

        if ($vendorCategory) {
            $vendorCategory->delete();
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "error" => "Wrong id!"]);
    }

    public function mergeCategory(Request $request) 
    {
        $toCategory     = $request->get("to_category");
        $fromCategory   = $request->get("from_category");

        if(empty($toCategory)) {
            return response()->json(["code" => 500 , "error" => "Merge category is missing"]);
        }

        if(empty($fromCategory)) {
            return response()->json(["code" => 500 , "error" => "Please select category before select merge category"]);
        }

        if(in_array($toCategory,$fromCategory)) {
           return response()->json(["code" => 500 , "error" => "Merge category can not be same"]);
        }

        $category = \App\VendorCategory::where("id",$toCategory)->first();
        $allMergeCategory = \App\Vendor::whereIn("category_id",$fromCategory)->get();

        if($category) {
            // start to merge first
            if(!$allMergeCategory->isEmpty()) {
                foreach($allMergeCategory as $amc) {
                    $amc->category_id = $category->id;
                    $amc->save();
                }
            }
            // once all merged category store then delete that category from table
            \App\VendorCategory::whereIn("id",$fromCategory)->delete();
        }

        return response()->json(["code" => 200 , "data" => [], "messages" => "Category has been merged successfully"]);
    }


    public function usersPermission(Request $request)
    {
        $users = User::where('is_active',1)->orderBy('name','asc')->with('vendorCategoryPermission')->get();
        $categories = VendorCategory::orderBy('title','asc')->get();
        return view('vendors.category-permission',compact('users','categories'))->with('i', ($request->input('page', 1) - 1) * 10);
    }

    
    public function  updatePermission(Request $request){
        $user_id =  $request->user_id;
        $category_id = $request->category_id;
        $check = $request->check;
        $user = User::findorfail($user_id);
        //ADD PERMISSION
        if($check == 1){
            $user->vendorCategoryPermission()->attach($category_id);
            $message = "Permission added Successfully";
        }
        //REMOVE PERMISSION
        if($check == 0){
            $user->vendorCategoryPermission()->detach($category_id);
            $message = "Permission removed Successfully";
        }

        $data = [
            'success' => true,
            'message'=> $message
        ] ;
        return response()->json($data);
    }


}

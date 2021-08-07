<?php

namespace Modules\StoreWebsite\Http\Controllers;

use App\StoreWebsite;
use App\StoreWebsiteGoal;
use App\StoreWebsiteGoalRemark;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, $id)
    {
        $title = "Goals | Store Website";

        return view('storewebsite::goal.index', compact('title', 'id'));
    }

    public function records(Request $request, $id)
    {
        $keyword = request("keyword");
        
        // send response into the json
        $records = StoreWebsiteGoal::join("store_websites as sw", "sw.id", "store_website_goals.store_website_id")
            ->leftJoin('store_website_goal_remarks', function($query) {
                $query->on('store_website_goals.id','=','store_website_goal_remarks.store_website_goal_id')
                    ->whereRaw('store_website_goal_remarks.id IN (select MAX(a2.id) from store_website_goal_remarks as a2 join store_website_goals as u2 on u2.id = a2.store_website_goal_id group by u2.id)');
            })

            ->where("store_website_id", $id)
            ->select(["store_website_goals.*", "sw.website","store_website_goal_remarks.remark"]);
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
        ]);
    }

    public function save(Request $request, $storeWebsiteId)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'goal'     => 'required',
            'solution' => 'required',
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

        $id = $request->get("goal_id", 0);

        $records = StoreWebsiteGoal::find($id);

        if (!$records) {
            $records = new StoreWebsiteGoal;
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

    public function edit(Request $request, $storeWebsiteId, $goalId)
    {
        $storeWebsiteGoal = StoreWebsiteGoal::where("id", $goalId)->first();

        if ($storeWebsiteGoal) {
            return response()->json(["code" => 200, "data" => $storeWebsiteGoal]);
        }

        return response()->json(["code" => 500, "error" => "Wrong goal id!"]);
    }

    /**
     * delete Page
     * @param  Request $request [description]
     * @return
     */

    public function delete(Request $request, $storeWebsiteId, $goalId)
    {
        $storeWebsiteGoal = StoreWebsiteGoal::where("id", $goalId)->first();

        if ($storeWebsiteGoal) {
            $storeWebsiteGoal->remarks()->delete();
            $storeWebsiteGoal->delete();
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "error" => "Wrong goal id!"]);
    }

    public function remarks(Request $request, $storeWebsiteId, $goalId)
    {

        $data["remarks"] = StoreWebsiteGoalRemark::where("store_website_goal_id", $goalId)->latest()->get();
        $data["goal_id"] = $goalId;

        return response()->json(["code" => 200, "data" => $data]);

    }

    public function storeRemarks(Request $request, $storeWebsiteId, $goalId)
    {
        $remark                        = new StoreWebsiteGoalRemark;
        $remark->remark                = $request->get("remark");
        $remark->store_website_goal_id = $goalId;
        $remark->save();

        return response()->json(["code" => 200, "data" => $remark]);

    }

}

<?php

namespace App\Http\Controllers;

use App\DailyActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyActivityController extends Controller
{
    function store(Request $request){

    	$data = json_decode(urldecode($request->input('activity_table_data')),true);

    	foreach ($data as $item){

    		if(!empty($item['id']))
    		    DailyActivity::updateOrCreate(['id' => $item['id']],$item);
    		else{
    			$item['for_date'] = date('Y-m-d');
    			$item['user_id'] = \Auth::id();
			    DailyActivity::create($item);
		    }
	    }
    }

    public function quickStore(Request $request)
    {
      $data = $request->except('_token');

      // check first we need to add general categories first or not 
      $generalCat = $request->get("general_category_id",null);

      if(!is_numeric($generalCat) && $generalCat != "") {
         $gc = \App\GeneralCategory::updateOrCreate(["name" => $generalCat],["name" => $generalCat]);
         if(!empty($gc)) {
            $data["general_category_id"] = $gc->id;
         }
      }
      // general category store end

      $activity = DailyActivity::create($data);

      return response()->json([
        'activity'  => $activity
      ]);
    }

    public function complete(Request $request, $id)
    {
      $activity = DailyActivity::find($id);
      $activity->is_completed = Carbon::now();
      $activity->save();

      return response('success', 200);
    }

    public function start(Request $request, $id)
    {
      $activity = DailyActivity::find($id);
      $activity->actual_start_date = Carbon::now();
      $activity->save();

      return response('success', 200);
    }

    function get(Request $request){

    	$selected_user = $request->input('selected_user');
    	$user_id = $selected_user ??  \Auth::id();

    	$activities = DailyActivity::where('user_id',$user_id)
	                               ->where('for_date',$request->daily_activity_date)->get()->toArray();

    	return $activities;
    }
}

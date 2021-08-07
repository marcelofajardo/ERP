<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pagination;

class JobController extends Controller
{
    public function index(Request $request){
        
        $validator = \Validator::make($request->all(),
            [
                'queue' => 'nullable|exists:jobs,queue',
                'reserved_date' => 'nullable|date_format:Y-m-d',
                'available_date' => 'nullable|date_format:Y-m-d',
            ],[
                'exists'=>'Sorry! no results found for this queue'
            ]
        );

        if($validator->fails()){
            return \Redirect::Back()->withInput($request->all())->withErrors($validator);
        }

        $jobs = \App\Job::whereNotNull('id');
        
        $filters = $request->except('page');
        if($request->queue!=''){
            $jobs->where('queue','=',$request->queue);
        }

        if($request->payload!=''){
            $jobs->Where('payload', 'LIKE', '%'.$request->payload.'%');
        }

        if($request->reserved_date!=''){
            $reserved_start=\Carbon\Carbon::Parse($request->reserved_date)->startOfDay()->getTimeStamp();
            $reserved_end=\Carbon\Carbon::Parse($request->reserved_date)->endOfDay()->getTimeStamp();

            $jobs->where('reserved_at','>=',$reserved_start)
                    ->where('reserved_at','<',$reserved_end);
        }

        if($request->available_date!=''){
            $available_start=\Carbon\Carbon::Parse($request->available_date)->startOfDay()->getTimeStamp();
            $available_end=\Carbon\Carbon::Parse($request->available_date)->endOfDay()->getTimeStamp();
            $jobs->where('available_at','>=',$request->available_start);
            $jobs->where('available_at','<=',$request->available_end);
        }

        $checkbox = $jobs->pluck('id');
        $jobs = $jobs->paginate();
        $count = $jobs->count();
        $listQueues = \App\Job::JOBS_LIST;



        return view('job.list',compact('jobs','filters','count','checkbox','listQueues'))
                            ->withInput($request->all());
    }

    public function delete(Request $request, $id)
    {
        $jobs = \App\Job::find($id);

        if(!empty($jobs)) {
            $jobs->delete();
        }

        return back()->withInput();
    }

    public function deleteMultiple(Request $request)
    {
        $jobs = \App\Job::whereIn("id",$request->get("jobIds"))->delete();

        return response()->json(["code" => 200, "data" => []]);
    }

    public function alldelete(Request $request,$id)
    {
       $trim=trim($id,"[]");
       $myArray = explode(',', $trim);
       $jobs = \App\Job::whereIn("id",$myArray)->delete();
        return response()->json(["code" => 200, "data" => []]);
      
    }
}

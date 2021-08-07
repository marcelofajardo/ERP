<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pagination;

class FailedJobController extends Controller
{
    public function index(Request $request){
        
        $validator = \Validator::make($request->all(),
            [
                'queue' => 'nullable|exists:jobs,queue',
            ],[
                'exists'=>'Sorry! no results found for this queue'
            ]
        );
        if($validator->fails()){
            return \Redirect::Back()
                    ->withInput($request->all())
                    ->withErrors($validator);
        }

        $jobs=\App\FailedJob::whereNotNull('id')->orderBy('id','desc');
            
        $filters=$request->except('page');
        if($request->queue!=''){
            $jobs->where('queue','=',$request->queue);
        }
        if($request->payload!=''){
        $jobs->Where('payload', 'LIKE', '%'.$request->payload.'%');
        }

        if($request->failed_at!=''){
            $available_start=\Carbon\Carbon::Parse($request->failed_at)->startOfDay()->getTimeStamp();
            $available_end=\Carbon\Carbon::Parse($request->failed_at)->endOfDay()->getTimeStamp();
            $jobs->where('failed_at','>=',$request->failed_at);
            $jobs->where('failed_at','<=',$request->failed_at);
        }
        $checkbox =$jobs->pluck('id');
        $jobs=$jobs->paginate();
        $count = $jobs->total();
        return view('failedjob.list',compact('jobs','filters','count','checkbox'))
                            ->withInput($request->all());
    }

    public function delete(Request $request, $id)
    {
        $jobs = \App\FailedJob::find($id);

        if(!empty($jobs)) {
            $jobs->delete();
        }

        return back()->withInput();
    }

    public function deleteMultiple(Request $request)
    {
        $jobs = \App\FailedJob::whereIn("id",$request->get("jobIds"))->delete();

        return response()->json(["code" => 200, "data" => []]);
    }

    public function alldelete(Request $request,$id)
    {
       $trim=trim($id,"[]");
       $myArray = explode(',', $trim);
       $jobs = \App\FailedJob::whereIn("id",$myArray)->delete();
        return response()->json(["code" => 200, "data" => []]);
      
    }
}

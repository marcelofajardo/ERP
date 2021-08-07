<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StoreWebsiteAnalytic;
use App\StoreWebsite;
use App\Plan;
use App\PlanBasisStatus;
use App\PlanTypes;
use App\PlanCategories;
use Illuminate\Support\Facades\Validator;
use Storage;
use File;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{

    public function __construct()
    {

    }

    public function index()
    {
        $query = Plan::whereNull('parent_id');
        $basisList = PlanBasisStatus::all();

        $typeList = PlanTypes::all();
        $categoryList = PlanCategories::all();

        if(request('status')){
            $query->where('status',request('status'));
        }

        if(request('priority')){
            $query->where('priority',request('priority'));
        }
        if(request('typefilter')){
            $query->where('type',request('typefilter'));
        }
        if(request('categoryfilter')){
            $query->where('type',request('categoryfilter'));
        }

        if(request('date')){
            $query->whereDate('date',request('date'));
        }

        if(request('term')){
            $query->where('subject', 'LIKE', '%' . request('term') . '%');
            $query->orwhere('sub_subject', 'LIKE', '%' . request('term') . '%');
            $query->orwhere('basis', 'LIKE', '%' . request('term') . '%');
            $query->orwhere('implications', 'LIKE', '%' . request('term') . '%');
        }

        $planList = $query->orderBy('id','DESC')->paginate(10);
        return view('plan-page.index', compact('planList','basisList','typeList','categoryList'));
    }

    public function store(Request $request)
    {   
        //dd( $request->all() );
            $rules = [
                'priority' => 'required',
                //'date' => 'required',
                'status' => 'required',
            ];

            $validation = validator(
               $request->all(),
               $rules
            );
            $type = PlanTypes::find($request->type);
            if(!$type){
                $data = array(
                    'type' => $request->type,
                );

                PlanTypes::insert($data);
            }

            $category = PlanCategories::find($request->category);
            if(!$category){
                $data = array(
                    'category' => $request->category,
                );

                PlanCategories::insert($data);
            }

            $basis = PlanBasisStatus::find($request->basis);
            if(!$basis){
                $data = array(
                    'status' => $request->basis,
                );

                PlanBasisStatus::insert($data);
            }
            $typeList = PlanTypes::all();
            $categoryList = PlanCategories::all();
            //If validation fail send back the Input with errors
            if($validation->fails()) {
                //withInput keep the users info
                return redirect()->back()->withErrors($validation)->withInput();
            } else {
                $data = array(
                    'subject' => $request->subject,
                    'sub_subject' => $request->sub_subject,
                    'description' => $request->description,
                    'priority' => $request->priority,
                    'date' => $request->date,
                    'status' => $request->status,
                    'budget' => $request->budget,
                    'deadline' => $request->deadline,
                    'basis' => $request->basis,
                    'type' => $request->type,
                    'category' => $request->category,
                    'implications' => $request->implications,
                );
                if( $request->parent_id ){
                    $data['parent_id'] = $request->parent_id;
                }
                if( $request->remark ){
                    $data['remark'] = $request->remark;
                }
                if($request->id){
                    Plan::whereId($request->id)->update($data);
                    return redirect()->back()->with('success','Plan updated successfully.');
                }else{
                    Plan::insert($data);
                    return redirect()->back()->with('success','Plan saved successfully.');
                }
            }
        
    }

    public function newBasis(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        $validation = validator(
           $request->all(),
           $rules
        );
        if($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $data = array(
            'status' => $request->name,
        );

        PlanBasisStatus::insert($data);

        return redirect()->back()->with('success','New status created successfully.');

    }
    public function newType(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        $validation = validator(
           $request->all(),
           $rules
        );
        if($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $data = array(
            'type' => $request->name,
        );

        PlanTypes::insert($data);

        return redirect()->back()->with('success','New type created successfully.');

    }

    public function newCategory(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        $validation = validator(
           $request->all(),
           $rules
        );
        if($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $data = array(
            'category' => $request->name,
        );

        PlanCategories::insert($data);

        return redirect()->back()->with('success','New category created successfully.');

    }

    public function edit(Request $request)
    {
        $data = Plan::where('id' ,$request->id)->first();
        if($data){
            return response()->json([
                "code" => 200,
                "object" => $data,
            ]);

        }
        return response()->json([
                "code" => 500,
                "object" => null,
         ]);

    }

    public function delete($id = null)
    {
        StoreWebsiteAnalytic::whereId($id)->delete();
        return redirect()->to('/store-website-analytics/index')->with('success','Record deleted successfully.');
    }

    public function report($id = null) 
    {
        $reports = \App\ErpLog::where('model',\App\StoreWebsiteAnalytic::class)->orderBy("id","desc")->where("model_id",$id)->get();
        return view("store-website-analytics.reports",compact('reports'));
    }
    public function planAction(Request $request,$id){
        $data = Plan::where('id' ,$id)->first();
        return $data;
        //return response()->json(["code" => 200,"message" => 'Your data saved sucessfully.']);
    }
    public function planActionStore(Request $request){
        $data = Plan::where('id' ,$request->id)->first();
        if($data){
            $data->strength = $request->strength."\n";
            $data->weakness = $request->weakness."\n";
            $data->opportunity = $request->opportunity."\n";
            $data->threat = $request->threat."\n";
            $data->save();
            return response()->json(["code" => 200,"message" => 'Your data saved sucessfully.']);
        }
        return response()->json(["code" => 500,"message" => 'Data not found!']);
    }

    public function planSolutionsStore(Request $request){
        if($request->solution && $request->id){
            $data = array(
                'solution' => $request->solution,
                'plan_id' => $request->id,
            );
            DB::table('plan_solutions')->insert($data);
            return response()->json(["code" => 200,"message" => 'Your data saved sucessfully.']);
        }
        return response()->json(["code" => 500,"message" => 'Data not found!']);
    }
    public function planSolutionsGet(Request $request,$id){
        if($id){
            $data = DB::table('plan_solutions')->where('plan_id',$id)->get();
            return $data;
        }
        return response()->json(["code" => 500,"message" => 'Data not found!']);
    }
}

<?php

namespace App\Http\Controllers;

use App\SimplyDutyCategory;
use Illuminate\Http\Request;
use App\Setting;
use Response;

class SimplyDutyCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->code || $request->description){
           $query = SimplyDutyCategory::query();

            if(request('code') != null){
                $query->where('code', request('code'));
            }
            if(request('description') != null){
                $query->where('description','LIKE', "%{$request->description}%");
            }
            $categories = $query->paginate(Setting::get('pagination'));
        }else{
            $categories = SimplyDutyCategory::paginate(Setting::get('pagination'));
        }
        
         if ($request->ajax()) {
            return response()->json([
                'tbody' => view('simplyduty.category.partials.data', compact('categories'))->render(),
                'links' => (string)$categories->render()
            ], 200);
            }

        return view('simplyduty.category.index',compact('categories'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SimplyDutyCategory  $simplyDutyCategory
     * @return \Illuminate\Http\Response
     */
    public function show(SimplyDutyCategory $simplyDutyCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SimplyDutyCategory  $simplyDutyCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(SimplyDutyCategory $simplyDutyCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SimplyDutyCategory  $simplyDutyCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SimplyDutyCategory $simplyDutyCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SimplyDutyCategory  $simplyDutyCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SimplyDutyCategory $simplyDutyCategory)
    {
        //
    }

    public function getCategoryFromApi(){
        

        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "https://www.api.simplyduty.com/api/Supporting/categories");

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch); 

        $categories = json_decode($output);
        foreach($categories as $category){
            $code = $category->Code;
            $description = $category->Description;
            $cat =  SimplyDutyCategory::where('code',$code)->where('description',$description)->first();
            if($cat != '' && $cat != null){
                $cat->touch();
            }else{
                $category = new SimplyDutyCategory;
                $category->code = $code;
                $category->description = $description;
                $category->save();
                
            }
        }

        return Response::json(array('success' => true)); 
        
        
    }
}

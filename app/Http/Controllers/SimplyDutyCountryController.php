<?php

namespace App\Http\Controllers;

use App\SimplyDutyCountry;
use Illuminate\Http\Request;
use App\Setting;
use Response;

class SimplyDutyCountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->code || $request->country){
           $query = SimplyDutyCountry::query();

            if(request('code') != null){
                $query->where('country_code','LIKE', "%{$request->code}%");
            }
            if(request('country') != null){
                $query->where('country_name','LIKE', "%{$request->country}%");
            }
            $countries = $query->paginate(Setting::get('pagination'));
        }else{
            $countries = SimplyDutyCountry::paginate(Setting::get('pagination'));
        }
        
         if ($request->ajax()) {
            return response()->json([
                'tbody' => view('simplyduty.country.partials.data', compact('countries'))->render(),
                'links' => (string)$countries->render()
            ], 200);
            }

        return view('simplyduty.country.index',compact('countries'));
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
     * @param  \App\SimplyDutyCountry  $simplyDutyCountry
     * @return \Illuminate\Http\Response
     */
    public function show(SimplyDutyCountry $simplyDutyCountry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SimplyDutyCountry  $simplyDutyCountry
     * @return \Illuminate\Http\Response
     */
    public function edit(SimplyDutyCountry $simplyDutyCountry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SimplyDutyCountry  $simplyDutyCountry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SimplyDutyCountry $simplyDutyCountry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SimplyDutyCountry  $simplyDutyCountry
     * @return \Illuminate\Http\Response
     */
    public function destroy(SimplyDutyCountry $simplyDutyCountry)
    {
        //
    }

    public function getCountryFromApi(){
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "https://www.api.simplyduty.com/api/Supporting/supported-countries");

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch); 

        $countries = json_decode($output);
       
        foreach($countries as $country){
            $countryDetail = $country->Country;
            $countryCode = $country->CountryCode;
            //Country Code wtih Details
            $cat =  SimplyDutyCountry::where('country_code',$countryCode)->where('country_name',$countryDetail)->first();
            if($cat != '' && $cat != null){
                $cat->touch();
            }else{
                $category = new SimplyDutyCountry;
                $category->country_code = $countryCode;
                $category->country_name = $countryDetail;
                $category->save();
            }
        }

        return Response::json(array('success' => true)); 
    }

     /**
     * @SWG\Get(
     *   path="/duty/v1/get-countries",
     *   tags={"Duty"},
     *   summary="Get Countries",
     *   operationId="get-countries",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    public function sendCountryJson(){
        $countries = SimplyDutyCountry::all();
        foreach($countries as $country){
            $countryArray[] = array('Country'=> $country->country_name , 'CountryCode' => $country->country_code);
        }
        return json_encode($countryArray);
    }
    public function updateduty(Request $request){
        if (!$request->ajax()) {
            return response()->json(['success' => false, 'message' => "Something went wrong!"]);
        }
        $country = SimplyDutyCountry::find($request->input('id'));
        $country->default_duty = $request->input('duty');
        if ($country->save()) {
            return response()->json(['success' => true, 'message' => "Default duty update successfully"]);
        }
        return response()->json(['success' => false, 'message' => "Something went wrong!"]);
    }
}

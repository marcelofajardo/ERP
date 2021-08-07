<?php

namespace App\Http\Controllers;

use App\SimplyDutyCalculation;
use App\SimplyDutyCountry;
use Illuminate\Http\Request;
use App\Setting;
use Validator;

class SimplyDutyCalculationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         if($request->code || $request->country){
           $query = SimplyDutyCalculation::query();

            if(request('code') != null){
                $query->where('country_code','LIKE', "%{$request->code}%");
            }
            if(request('country') != null){
                $query->where('country_name','LIKE', "%{$request->country}%");
            }
            $calculations = $query->paginate(Setting::get('pagination'));
        }else{
            $calculations = SimplyDutyCalculation::paginate(Setting::get('pagination'));
            $countries = SimplyDutyCountry::all();
        }
        
         if ($request->ajax()) {
            return response()->json([
                'tbody' => view('simplyduty.calculation.partials.data', compact('calculations'))->render(),
                'links' => (string)$calculations->render()
            ], 200);
            }

        return view('simplyduty.calculation.index',compact('calculations','countries'));
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
     * @param  \App\SimplyDutyCalculation  $simplyDutyCalculation
     * @return \Illuminate\Http\Response
     */
    public function show(SimplyDutyCalculation $simplyDutyCalculation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SimplyDutyCalculation  $simplyDutyCalculation
     * @return \Illuminate\Http\Response
     */
    public function edit(SimplyDutyCalculation $simplyDutyCalculation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SimplyDutyCalculation  $simplyDutyCalculation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SimplyDutyCalculation $simplyDutyCalculation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SimplyDutyCalculation  $simplyDutyCalculation
     * @return \Illuminate\Http\Response
     */
    public function destroy(SimplyDutyCalculation $simplyDutyCalculation)
    {
        //
    }

    /**
     * @SWG\Post(
     *   path="/duty/v1/calculate",
     *   tags={"Duty"},
     *   summary="post calculate",
     *   operationId="post-countries",
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
    public function calculate(Request $request){
        
        $receivedJson = json_decode($request->getContent());
        
        $originCountryCode = $receivedJson->OriginCountryCode;
        if($originCountryCode == null && $originCountryCode == ''){
            $message = ['error' => 'OriginCountryCode is required'];
            return json_encode($message, 400);
        }

        $destinationCountryCode = $receivedJson->DestinationCountryCode;
        if($destinationCountryCode == null && $destinationCountryCode == ''){
            $message = ['error' => 'Destination Country Code is required'];
            return json_encode($message, 400);
        }
        
        $items = $receivedJson->Items;
        if($items == null && $items == ''){
            $message = ['error' => 'Items is required'];
            return json_encode($message, 400);
        }

        $shipping = $receivedJson->Shipping;
        if($shipping == null && $shipping == '' && $shipping != 0){
            $shipping = 0;
        }

        $insurance = $receivedJson->Insurance;
        if($insurance == null && $insurance == ''){
            $insurance = 0;
        }

        if(isset($receivedJson->DestinationStateCode)){
            $destinationStateCode = $receivedJson->DestinationStateCode;
        }else{
            $destinationStateCode = '';
        }

        if(isset($receivedJson->OriginCurrencyCode)){
            $originCurrencyCode = $receivedJson->OriginCurrencyCode;
        }else{
            $originCurrencyCode = '';
        }

        if(isset($receivedJson->DestinationCurrencyCode)){
            $destinationCurrencyCode = $receivedJson->DestinationCurrencyCode;
        }else{
            $destinationCurrencyCode = '';
        }

        if(isset($receivedJson->ContractInsuranceType)){
            $contractInsuranceType = $receivedJson->ContractInsuranceType;
        }else{
            $contractInsuranceType = '';
        }
      
        //Looping over items
        foreach($items as $item){
             
            $hsCode = $item->HSCode;
            if($hsCode == null){
                $message = ['error' => 'HSCode is required'];
                return json_encode($message, 400);
            }
            $quantity = $item->Quantity;
            if($quantity == null){
                $message = ['error' => 'Quantity is required'];
                return json_encode($message, 400);
            }
            $value = $item->Value;
            if($value == null){
                $message = ['error' => 'Value is required'];
                return json_encode($message, 400);
            }

         $itemsArray[]  = array('HSCode' => $hsCode,'Quantity' => $quantity,'Value' => $value);  
        }

        $output =  array('OriginCountryCode' => $originCountryCode ,'DestinationCountryCode' => $destinationCountryCode ,'Items' => $itemsArray , 'Shipping' => $shipping , 'Insurance' => $insurance , 'ContractInsuranceType' => $contractInsuranceType);    
        $post = json_encode($output);
        
			
			$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://www.api.simplyduty.com/api/duty/calculatemultiple",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "$post",
			CURLOPT_HTTPHEADER => array(
				 "Content-Type: application/json",
                 "Accept: application/json",
                 "x-api-key: 7a44e06e-eb82-4c09-b197-0419b950f98f",
			),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

            curl_close($curl);
            if($response){
                $req = json_decode($response);
                foreach($req->Items as $item){
                $calculation = new SimplyDutyCalculation;
                $calculation->value = $item->Value;
                $calculation->duty = $item->Duty;
                $calculation->duty_rate = $item->DutyRate;
                $calculation->duty_hscode = $item->DutyHSCode;
                $calculation->duty_type = $item->DutyType;
                $calculation->shipping = $req->Shipping;
                $calculation->insurance = $req->Insurance;
                $calculation->total = $req->Total;
                $calculation->exchange_rate = $req->ExchangeRate;
                $calculation->currency_type_origin = $req->CurrencyTypeOrigin;
                $calculation->currency_type_destination = $req->CurrencyTypeDestination;
                $calculation->duty_minimis = $req->DutyMinimis;
                $calculation->vat_minimis = $req->VatMinimis;
                $calculation->vat_rate = $req->VatRate;
                $calculation->vat = $req->VAT;
                $calculation->save();
                }
            }

            return $response;
            
        }

    
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Affiliates;
use App\Http\Controllers\Controller;
use App\StoreWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AffiliateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @SWG\Post(
     *   path="/affiliate/add",
     *   tags={"Affiliate"},
     *   summary="store affiliate",
     *   operationId="store-affiliate",
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
    /**
     * @SWG\Post(
     *   path="/influencer/add",
     *   tags={"Influencer"},
     *   summary="store influencer",
     *   operationId="store-influencer",
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
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'website' => 'required|exists:store_websites,website',
        ]);

        $type = isset($request->type) ? $request->type : "affiliates";

        $storeweb = StoreWebsite::where('website', $request->website)->first();
        if ($validator->fails()) {
            $message = $this->generate_erp_response("$type.failed.validation",isset($storeweb) ? $storeweb->id : null, $default = 'please check validation errors !', request('lang_code') );
            return response()->json(['status' => 'failed', 'message' => $message, 'errors' => $validator->errors()], 400);
        }
        $affiliates                            = new Affiliates;
        $affiliates->store_website_id          = ($storeweb) ? $storeweb->id : null;
        $affiliates->first_name                = isset($request->first_name) ? $request->first_name : '';
        $affiliates->last_name                 = isset($request->last_name) ? $request->last_name : '';
        $affiliates->phone                     = isset($request->phone) ? $request->phone : '';
        $affiliates->emailaddress              = isset($request->email) ? $request->email : '';
        $affiliates->website_name              = isset($request->website_name) ? $request->website_name : '';
        $affiliates->url                       = isset($request->url) ? $request->url : '';
        $affiliates->unique_visitors_per_month = isset($request->unique_visitors_per_month) ? $request->unique_visitors_per_month : '';
        $affiliates->page_views_per_month      = isset($request->page_views_per_month) ? $request->page_views_per_month : '';
        $affiliates->address                   = isset($request->street_address) ? $request->street_address : '';
        $affiliates->city                      = isset($request->city) ? $request->city : '';
        $affiliates->postcode                  = isset($request->postcode) ? $request->postcode : '';
        $affiliates->country                   = isset($request->country) ? $request->country : '';
        $affiliates->location                  = isset($request->location) ? $request->location : '';
        $affiliates->title                     = isset($request->title) ? $request->title : '';
        $affiliates->caption                   = isset($request->caption) ? $request->caption : '';
        $affiliates->posted_at                 = isset($request->posted_at) ? $request->posted_at : '';
        $affiliates->facebook                  = isset($request->facebook) ? $request->facebook : '';
        $affiliates->facebook_followers        = isset($request->facebook_followers) ? $request->facebook_followers : '';
        $affiliates->instagram                 = isset($request->instagram) ? $request->instagram : '';
        $affiliates->instagram_followers       = isset($request->instagram_followers) ? $request->instagram_followers : '';
        $affiliates->twitter                   = isset($request->twitter) ? $request->twitter : '';
        $affiliates->twitter_followers         = isset($request->twitter_followers) ? $request->twitter_followers : '';
        $affiliates->youtube                   = isset($request->youtube) ? $request->youtube : '';
        $affiliates->youtube_followers         = isset($request->youtube_followers) ? $request->youtube_followers : '';
        $affiliates->linkedin                  = isset($request->linkedin) ? $request->linkedin : '';
        $affiliates->linkedin_followers        = isset($request->linkedin_followers) ? $request->linkedin_followers : '';
        $affiliates->pinterest                 = isset($request->pinterest) ? $request->pinterest : '';
        $affiliates->pinterest_followers       = isset($request->pinterest_followers) ? $request->pinterest_followers : '';
        $affiliates->worked_on                 = isset($request->worked_on) ? $request->worked_on : null;
        $affiliates->type                      = isset($request->type) ? $request->type : "affiliate";
        $affiliates->source                    = isset($request->source) ? $request->source : '';

        if ($affiliates->save()) {
            $message = $this->generate_erp_response("$type.success",($storeweb) ? $storeweb->id : null, $default = ucwords($affiliates->type).' added successfully !', request('lang_code'));
            return response()->json([
                'status'  => 'success',
                'message' => $message,
            ], 200);
        }
        $message = $this->generate_erp_response("$type.failed",($storeweb) ? $storeweb->id : null, $default = 'Unable to add '.ucwords($affiliates->type)."!", request('lang_code'));
        return response()->json([
            'status'  => 'failed',
            'message' => $message,
        ], 500);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $affiliates = Affiliates::find($id);

        return response()->json(["code" => 200 , "data" => $affiliates]);

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
}

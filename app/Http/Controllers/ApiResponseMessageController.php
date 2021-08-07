<?php

namespace App\Http\Controllers;

use App\ApiResponseMessage;
use App\StoreWebsite;
use Illuminate\Http\Request;

class ApiResponseMessageController extends Controller
{

    public function index(){
        $api_response = ApiResponseMessage::with(['storeWebsite'])->orderBy('created_at','desc')->get();
        $store_websites = StoreWebsite::orderBy('created_at','desc')->get();
        return view('apiResponse/index',compact('api_response','store_websites'));
    }

    public function store(Request $request){
        
        $duplicate = ApiResponseMessage::where('store_website_id',$request->store_website_id)->where('key',$request->res_key)->first();
        if(!empty($duplicate)){
            \Session::flash('message', 'Key already exists for the selected store website'); 
            \Session::flash('alert-class', 'alert-danger'); 
            return redirect()->route('api-response-message');
        }

        $response = new ApiResponseMessage();
        $response->store_website_id = $request->store_website_id;
        $response->key = $request->res_key;
        $response->value = $request->res_value;
        if($response->save()){
            \Session::flash('message', 'Added successfully'); 
            \Session::flash('alert-class', 'alert-success'); 
            return redirect()->route('api-response-message');
        }else{
            \Session::flash('message', 'Something went wrong'); 
            \Session::flash('alert-class', 'alert-danger'); 
            return redirect()->route('api-response-message');
        }
    }

    public function getEditModal(Request $request){
        $id = $request->id;
        $store_websites = StoreWebsite::orderBy('created_at','desc')->get();
        $data = ApiResponseMessage::where('id',$id)->first();
        $returnHTML = view('apiResponse/ajaxEdit')->with('data', $data)->with('store_websites',$store_websites)->render();

        return response()->json(['data' => $returnHTML,'type' => 'success'],200);
    }

    public function update(Request $request){
        $response =  ApiResponseMessage::where('id',$request->id)->first();
        $response->store_website_id = $request->store_website_id;
        $response->key = $request->key;
        $response->value = $request->value;
        if($response->save()){
            \Session::flash('message', 'Updated successfully'); 
            \Session::flash('alert-class', 'alert-success'); 
            return response()->json(['type' => 'success'],200);
        }else{
            \Session::flash('message', 'Something went wrong'); 
            \Session::flash('alert-class', 'alert-danger'); 
            return redirect()->route('api-response-message');
        }
    }

    public function destroy($id){
        if(ApiResponseMessage::where('id',$id)->delete()){
            \Session::flash('message', 'Deleted successfully'); 
            \Session::flash('alert-class', 'alert-success'); 
            return redirect()->route('api-response-message');
        }else{
            \Session::flash('message', 'Something went wrong'); 
            \Session::flash('alert-class', 'alert-danger'); 
            return redirect()->route('api-response-message');
        }
    }
}

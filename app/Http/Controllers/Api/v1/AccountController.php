<?php

namespace App\Http\Controllers\Api\v1;

use App\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{   
    
    /**
     * @SWG\Post(
     *   path="/v1/account/create",
     *   tags={"Account"},
     *   summary="Create Account",
     *   operationId="create-account",
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
    public function create(Request $request)
    {

        $customer = Customer::where('email', $request->get("email"))->where("store_website_id", $request->get("store_website_id"))->first();

        // Create a customer if doesn't exists
        if (!$customer) {
            $customer = new Customer;
        }

        $customer->name             = trim($request->get("firstname") . " " . $request->get("lastname"));
        $customer->email            = $request->get("email");
        $customer->store_website_id = $request->get("store_website_id");
        $customer->save();

        return response()->json(["code" => 200, "message" => "Customer has been account created", "data" => $customer]);
    }
}

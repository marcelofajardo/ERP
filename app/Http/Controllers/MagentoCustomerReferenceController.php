<?php

namespace App\Http\Controllers;

use App\MagentoCustomerReference;
use Illuminate\Http\Request;
use App\Setting;
use App\Customer;
use App\StoreWebsite;
use App\Helpers\InstantMessagingHelper;
use App\Helpers\MagentoOrderHandleHelper;

class MagentoCustomerReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
    }

    /**
     * Create magento order
     *
     * @return \Illuminate\Http\Response
     */
    public function createOrder( Request $request )
    {   
        $bodyContent = $request->getContent();
        $order       = json_decode( $bodyContent );
        $lang_code   = $order->lang_code ?? null;
        if( empty( $bodyContent )  ){
            $message = $this->generate_erp_response("magento.order.failed.validation",0, $default = 'Invalid data',$lang_code);
            return response()->json([
                'status'  => false,
                'message' => $message,
            ]);
        }
        $order = json_decode( $bodyContent );
    
        $newArray            = [];
        $newArray['items'][] = $order;
        $order            = json_decode(json_encode( $newArray) );
        
        if( isset( $order->items[0]->website ) ){
            $website = StoreWebsite::where('website',$order->items[0]->website)->first();
            if( $website ){
                
                $orderCreate = MagentoOrderHandleHelper::createOrder( $order, $website );
                if( $orderCreate == true ){
                    $message = $this->generate_erp_response("magento.order.success",0, $default = 'Order create successfully',$lang_code);
                    return response()->json([
                        'status'  => true,
                        'message' => $message,
                    ]);
                }
            }else{
                \Log::error("Magento website not found");
            }
        }

        $message = $this->generate_erp_response("magento.order.failed",0, $default = 'Something went wrong, Please try again', $lang_code);
        return response()->json([
            'status'  => false,
            'message' => $message,
        ]);
    }

    /**
    * @SWG\Post(
    *   path="/magento/customer-reference",
    *   tags={"Magento"},
    *   summary="store magento customer reference",
    *   operationId="store-magento-customer-reference",
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
       
        if (empty($request->name)) {
            $message = $this->generate_erp_response("customer_reference.403",0, $default = 'Name is required',request('lang_code'));
            return response()->json(['message' => $message], 403);
        }

        // if (empty($request->phone)) {
        //     return response()->json(['error' => 'Phone is required'], 403);
        // }

        if (empty($request->email)) {
            $message = $this->generate_erp_response("customer_reference.403",0, $default = 'Email is required', request('lang_code'));
            return response()->json(['message' => $message], 403);
        }

        if (empty($request->website)) {
            $message = $this->generate_erp_response("customer_reference.403",0, $default = 'website is required', request('lang_code'));
            return response()->json(['message' => $message], 403);
        }
        
        // if (empty($request->social)) {
        //     return response()->json(['error' => 'Social is required'], 403);
        // }
        $name = $request->name;
        $email = $request->email;
        $website = $request->website;
        $phone = null;
        $dob = null;
        $store_website_id = null;
        $wedding_anniversery = null;
        if($request->phone) {
            $phone = $request->phone;
        }
        if($request->dob) {
            $dob = $request->dob;
        }
        if($request->wedding_anniversery) {
            $wedding_anniversery = $request->wedding_anniversery;
        }

         //getting reference
         
        $store_website = StoreWebsite::where('website',"like", $website)->first();
        if($store_website) {
             $store_website_id = $store_website->id;
        }

        $reference = Customer::where('email',$email)->where("store_website_id",$store_website_id)->first();
        if(empty($reference)){

            $reference = new Customer();
            $reference->name = $name;
            $reference->phone = $phone;
            $reference->email = $email;
            $reference->store_website_id = $store_website_id;
            $reference->dob = $dob;
            $reference->wedding_anniversery = $wedding_anniversery;
            $reference->save();

            if($reference->phone) {
                //get welcome message
                $welcomeMessage = InstantMessagingHelper::replaceTags($reference, Setting::get('welcome_message'));
                //sending message
                app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($reference->phone, '', $welcomeMessage, '', '');
            }
        
        } else {
            $reference->name = $name;
            $reference->phone = $phone;
            $reference->email = $email;
            $reference->store_website_id = $store_website_id;
            $reference->dob = $dob;
            $reference->wedding_anniversery = $wedding_anniversery;
            $reference->save();
        }

        $message = $this->generate_erp_response("customer_reference.success",$store_website_id, $default = 'Saved successfully !', request('lang_code'));
        return response()->json(['message' => 'Saved SucessFully'], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MagentoCustomerReference  $magentoCustomerReference
     * @return \Illuminate\Http\Response
     */
    public function show(MagentoCustomerReference $magentoCustomerReference)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MagentoCustomerReference  $magentoCustomerReference
     * @return \Illuminate\Http\Response
     */
    public function edit(MagentoCustomerReference $magentoCustomerReference)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MagentoCustomerReference  $magentoCustomerReference
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MagentoCustomerReference $magentoCustomerReference)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MagentoCustomerReference  $magentoCustomerReference
     * @return \Illuminate\Http\Response
     */
    public function destroy(MagentoCustomerReference $magentoCustomerReference)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use App\ShopifyHelper;
use App\StoreWebsite;
use Illuminate\Http\Request;

/**
 * @author Sukwhinder Singh
 */
class ShopifyController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

     /**
     * @SWG\Post(
     *   path="/shopify/order/create",
     *   tags={"Shopify"},
     *   summary="Create Shopify Order",
     *   operationId="shopify-create-order",
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
     * Get a webhook event and create orders out of it
     *
     * @param Request $request
     * @return void
     */
    public function setShopifyOrders(Request $request)
    {
        $store_id = $request->query('store_website_id');

        if (!$store_id) {
            return response()->json(['error' => 'Store website id missing'], 400);
        }

        // Validate the webhook request and authenticity
        // https://shopify.dev/tutorials/manage-webhooks#verifying-webhooks
        // Get the secret key from store_websites
        $shopify_secret = StoreWebsite::find($store_id)->api_token;
        $hmac_header = $request->header('x-shopify-hmac-sha256');

        if (!ShopifyHelper::validateShopifyWebhook($request->getContent(), $shopify_secret, $hmac_header)) {

            // Log into general log channel
            \Log::channel('customer')->debug("Order webhook failed ");
            return response()->json(['error' => 'Couldnot verify webhook'], 400);

        }

        $order = $request->all();

        // \Log::info($orders);
        ShopifyHelper::syncShopifyOrders($store_id, $order);
        return response()->json(['success'], 200);
    }

     /**
     * @SWG\Post(
     *   path="/shopify/customer/create",
     *   tags={"Shopify"},
     *   summary="Shopify create customer",
     *   operationId="shopify-create-customer",
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
     * Get a webhook event and create customers out of it
     *
     * @param Request $request
     * @return void
     */
    public function setShopifyCustomers(Request $request)
    {
        $store_id = $request->query('store_website_id');

        if (!$store_id) {
            return response()->json(['error' => 'Store website id missing'], 400);
        }

        // Validate the webhook request and authenticity
        // https://shopify.dev/tutorials/manage-webhooks#verifying-webhooks
        // Get the secret key from store_websites
        $shopify_secret = StoreWebsite::find($store_id)->api_token;
        $hmac_header = $request->header('x-shopify-hmac-sha256');

        if (!ShopifyHelper::validateShopifyWebhook($request->getContent(), $shopify_secret, $hmac_header)) {
            \Log::channel('customer')->debug("Customer webhook failed ");
            return response()->json(['error' => 'Couldnot verify webhook'], 400);
        }

        $customer = $request->all();
        ShopifyHelper::syncShopifyCustomers($store_id, $customer);
        return response()->json(['success'], 200);
    }

}

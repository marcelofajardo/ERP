<?php

namespace App\Http\Controllers;

use App\ReturnExchange;
use Carbon\Carbon;
use Twilio\Jwt\ClientToken;
use Twilio\Twiml;
use Twilio\Rest\Client;
use App\Category;
use App\Notification;
use App\Leads;
use App\Customer;
use App\Order;
use App\Status;
use App\Agent;
use App\Supplier;
use App\Vendor;
use App\Setting;
use App\User;
use App\Brand;
use App\Product;
use App\Message;
use App\Purchase;
use App\Contact;
use App\Dubbizle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers;
use App\ChatMessage;


class FindByNumberController extends Controller
{
    protected function findLeadByNumber($number)
    {
        return Leads::where('contactno', '=', $number)->first();
    }

    protected function findDubbizleByNumber($number)
    {
        return Dubbizle::where('phone_number', $number)->first();
    }

    protected function findCustomerByNumber($number)
    {
        return Customer::where('phone', '=', $number)->first();
    }

    protected function findOrderByNumber($number)
    {
        return Order::where('contact_detail', '=', $number)->first();
    }

    protected function findSupplierByNumber($number)
    {
        if ($agent = Agent::where('phone', $number)->first()) {
            if ($agent->purchase && $agent->purchase->purchase_supplier) {
                return $agent->purchase->purchase_supplier;
            }

            if (preg_match("/supplier/i", $agent->model_type)) {
                return Supplier::find($agent->model_id);
            }
        }

        return Supplier::where('phone', $number)->first();
    }

    protected function findVendorByNumber($number)
    {
        if ($agent = Agent::where('phone', $number)->where('model_type', 'LIKE', "%Vendor%")->first()) {
            if (preg_match("/vendor/i", $agent->model_type)) {
                return Vendor::find($agent->model_id);
            }
        }

        return Vendor::where('phone', $number)->first();
    }

    protected function findUserByNumber($number)
    {
        return User::where('phone', '=', $number)->first();
    }

    protected function findContactByNumber($number)
    {
        return Contact::where('phone', '=', $number)->first();
    }

    protected function findLeadOrOrderByNumber($number)
    {
        $lead = $this->findLeadByNumber($number);
        if ($lead) {
            return array("leads", $lead);
        }
        $order = $this->findOrderByNumber($number);
        if ($order) {
            return array("orders", $order);
        }
        return array(FALSE, FALSE);
    }

    protected function findCustomerOrLeadOrOrderByNumber($number)
    {
        $customer = $this->findCustomerByNumber($number);
        if ($customer) {
            return array("customers", $customer);
        }
        $lead = $this->findLeadByNumber($number);
        if ($lead) {
            return array("leads", $lead);
        }
        $order = $this->findOrderByNumber($number);
        if ($order) {
            return array("orders", $order);
        }
        return array(FALSE, FALSE);
    }

    protected function findCustomerAndRelationsByNumber($number)
    {
        $customer = $this->findCustomerByNumber($number);
        if ($customer){
                $orders = (new \App\Order())->newQuery()->with('customer')->leftJoin("store_website_orders as swo","swo.order_id","orders.id")
                    ->leftJoin("order_products as op","op.order_id","orders.id")
                    ->leftJoin("products as p","p.id","op.product_id")
                    ->leftJoin("brands as b","b.id","p.brand")->groupBy("orders.id")
                    ->where('customer_id',$customer->id)
                    ->select(["orders.*",\DB::raw("group_concat(b.name) as brand_name_list"),"swo.website_id"])->orderBy('created_at','desc')->get();
                list($leads_total,$leads) = $this->getLeadsInformation($customer->id);
                $exchanges_return = $customer->return_exchanges;
                if ($orders->count()){
                    foreach ($orders as &$value){
                        $value->storeWebsite = $value->storeWebsiteOrder ? ($value->storeWebsiteOrder->storeWebsite??'N/A') : 'N/A';
                        $value->order_date =  Carbon::parse($value->order_date)->format('d-m-y');
                        $totalBrands = explode(",",$value->brand_name_list);
                        $value->brand_name_list = (count($totalBrands) > 1) ? "Multi" : $value->brand_name_list;
                        $value->status = \App\Helpers\OrderHelper::getStatusNameById($value->order_status_id);
                    }
                }
            return [
                true,
                [
                    'orders_total'=>$orders->count(),
                    'leads_total'=>$leads_total,
                    'exchanges_return_total'=>$exchanges_return->count(),
                    'exchanges_return'=>$exchanges_return,
                    'leads'=>$leads,
                    'orders'=>$orders,
                    'customer'=>$customer
                ]
            ];
        }
        return array(FALSE, FALSE);
    }


    private function getLeadsInformation($id){
        $source = \App\ErpLeads::leftJoin('products', 'products.id', '=', 'erp_leads.product_id')
            ->leftJoin("customers as c","c.id","erp_leads.customer_id")
            ->leftJoin("erp_lead_status as els","els.id","erp_leads.lead_status_id")
            ->leftJoin("categories as cat","cat.id","erp_leads.category_id")
            ->leftJoin("brands as br","br.id","erp_leads.brand_id")
            ->where('erp_leads.customer_id',$id)
            ->orderBy("erp_leads.id","desc")
            ->select(["erp_leads.*","products.name as product_name","cat.title as cat_title","br.name as brand_name","els.name as status_name","c.name as customer_name","c.id as customer_id"]);


        $total = $source->count();
        $source = $source->get();

        foreach ($source as $key => $value) {
            $source[$key]->media_url = null;
            $media = $value->getMedia(config('constants.media_tags'))->first();
            if ($media) {
                $source[$key]->media_url = $media->getUrl();
            }

            if (empty($source[$key]->media_url) && $value->product_id) {
                $product = \App\Product::find($value->product_id);
                $media = $product->getMedia(config('constants.media_tags'))->first();
                if ($media) {
                    $source[$key]->media_url = $media->getUrl();
                }
            }
        }

        return [$total,$source];
    }
}

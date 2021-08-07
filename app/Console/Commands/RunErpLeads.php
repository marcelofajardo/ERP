<?php

namespace App\Console\Commands;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\ErpLeadSendingHistory;
use Carbon\Carbon;

class RunErpLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'erpleads:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Erp Leads';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        try {


            $lead_product_limit = \App\Setting::where("name", "send_leads_product")->value('val');

            if ($lead_product_limit == 0) {
                return false;
            }
            $leads = \App\ErpLeads::join("customers as c", "c.id", "erp_leads.customer_id")
                ->where("is_blocked_lead", 0)
                ->where("c.do_not_disturb", 0)
                ->select(["erp_leads.*", "c.lead_product_freq"])->get();

            \Log::channel('customer')->info("Found leads" . $leads->count());
            if (!$leads->isEmpty()) {
                foreach ($leads as $lead) {
                    $limitLead = $lead_product_limit;
                    if ($lead->lead_product_freq > 0) {
                        $limitLead = $lead->lead_product_freq;
                    }

                    $products = \App\Product::where(function ($q) {
                        $q->where("stock", ">", 0)->orWhere("supplier", "in-stock");
                    });

                    $products = $products->join("brands as b", "b.id", "products.brand");
                    $products = $products->join("categories as c", "c.id", "products.category");

                    $products = $products->where('products.created_at', '>', Carbon::now()->subDays(30))
                        ->where("products.name", "!=", "")
                        ->where("products.sku", "!=", "")
                        ->where("products.price", "!=", "");

                    $products = $products->where(function ($q) use ($lead) {
                        $q->orWhere("b.id", $lead->brand_id)->orWhere("c.id", $lead->category_id);
                    });
                    $allProduts = $products->select(["products.*"])->limit($lead_product_limit)->get()->pluck("id")->toArray();
                    if (!empty($products)) {

                        $allProdCounts = count($allProduts);
                        $newProdArr = [];
                        for ($i = 0; $i < $allProdCounts; $i++) {
                            //add data to erp_lead_sending_histories tables
                            $ErpLeadSendingHistory = new ErpLeadSendingHistory;
                            $checkCustomerExist = $ErpLeadSendingHistory::where('customer_id', '=', $lead->customer_id)
                                ->where('product_id', '=', $allProduts[$i])
                                ->where('lead_id', '=', $lead->id)
                                ->count();
                            if ($checkCustomerExist == 0) {
                                $ErpLeadSendingHistory->product_id = $allProduts[$i];
                                $ErpLeadSendingHistory->customer_id = $lead->customer_id;
                                $ErpLeadSendingHistory->lead_id = $lead->id;
                                $ErpLeadSendingHistory->save();
                                $newProdArr[$i] = $allProduts[$i];
                            }
                        }
                        if (count($newProdArr) > 0) {
                            $requestData = new Request();
                            $requestData->setMethod('POST');
                            $requestData->request->add(['lead_id'=>$lead->id,'customer_id' => $lead->customer_id, 'selected_product' =>   $newProdArr]);
                            $res = app('App\Http\Controllers\LeadsController')->sendPrices($requestData, new GuzzleClient);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}

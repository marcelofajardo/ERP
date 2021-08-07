<?php

namespace App\Console\Commands;

use App\ErpEvents;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class RunErpEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'erpevents:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Erp Events';

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
            // disable all events which is past if still active
            $startDate = date("Y-m-d H:i:s");
            $endDate   = date("Y-m-d H:i:s", strtotime($startDate . "+5 minutes"));

            $events = \App\ErpEvents::where("next_run_date", ">=", $startDate)->where("next_run_date", "<=", $endDate)->get();
            //$events = \App\ErpEvents::where("id", "=", 2)->get();

            if (!$events->isEmpty()) {
                foreach ($events as $event) {
                    $brandIds    = explode(",", $event->brand_id);
                    $categoryIds = explode(",", $event->category_id);

                    if (!empty($brandIds) || !empty($categoryIds)) {

                        $leads    = new \App\ErpLeads;
                        $products = \App\Product::where(function ($q) {
                            $q->where("stock", ">", 0)->orWhere("supplier", "in-stock");
                        });

                        // check all realted brands
                        if (!empty($brandIds)) {
                            $leads    = $leads->whereIn("brand_id", $brandIds);
                            $products = $products->whereIn("brand", $brandIds);
                        }
                        // check all related categories
                        if (!empty($categoryIds)) {
                            $leads    = $leads->whereIn("category_id", $categoryIds);
                            $products = $products->join("categories as c", "c.title", "products.category")->whereIn("c.id", $categoryIds);
                        }

                        if ($event->product_start_date != "0000-00-00 00:00:00" && !empty($event->product_start_date)) {
                            $products = $products->where("created_at", ">=", $event->product_start_date);
                        }

                        if ($event->product_end_date != "0000-00-00 00:00:00" && !empty($event->product_end_date)) {
                            $products = $products->where("created_at", "<=", $event->product_end_date);
                        }

                        $allProduts = $products->limit(5)->get()->pluck("id")->toArray();

                        if (!empty($allProduts)) {
                            $leadRecords = $leads->limit(2)->get();
                            if (!empty($leadRecords)) {
                                foreach ($leadRecords as $lead) {
                                    $requestData = new Request();
                                    $requestData->setMethod('POST');
                                    $requestData->request->add(['customer_id' => $lead->customer_id, 'lead_id' => $lead->id, 'selected_product' => $allProduts]);

                                    $res = app('App\Http\Controllers\LeadsController')->sendPrices($requestData, new GuzzleClient);
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}

<?php

namespace App\Jobs;

use App\ScrapedProducts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProductCategoryFromErp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $params;
    public $from;
    public $to;
    public $user_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->from    = $params["from"];
        $this->to      = $params["to"];
        $this->user_id = isset($params["user_id"]) ? $params["user_id"] : 6;
        $this->params  = $params;
    }

    public static function putLog($message)
    {
        \Log::channel('update_category_job')->info($message);
        return true;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        self::putLog("Job update product category from erp start time : " . date("Y-m-d H:i:s"));

        $affectedProducts = ScrapedProducts::matchedCategory($this->from);

        //$sku = [];

        if (!empty($affectedProducts)) {
            foreach ($affectedProducts as $affectedProduct) {
                $oldCat = $affectedProduct->category;
                $affectedProduct->category = $this->to;
                $affectedProduct->save();

                //$sku[] = $affectedProduct->sku;
                // do entry for the history as well
                $productCatHis                  = new \App\ProductCategoryHistory;
                $productCatHis->user_id         = ($this->user_id) ? $this->user_id : 6;
                $productCatHis->category_id     = !empty($this->to) ? $this->to : "";
                $productCatHis->old_category_id = !empty($oldCat) ? $oldCat : "";
                $productCatHis->product_id      = $affectedProduct->id;
                $productCatHis->save();
            }
        }

        //\Log::info(print_r($sku,true));

        self::putLog("Job update product category from erp end time : " . date("Y-m-d H:i:s"));

        return true;
    }
}

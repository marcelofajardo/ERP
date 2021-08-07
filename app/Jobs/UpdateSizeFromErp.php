<?php

namespace App\Jobs;

use App\Product;
use App\ScrapedProducts;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateSizeFromErp implements ShouldQueue
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
        $this->params  = $params;
        $this->from    = $params["from"];
        $this->to      = $params["to"];
        $this->user_id = isset($params["user_id"]) ? $params["user_id"] : 6;
    }

    public static function putLog($message)
    {
        \Log::channel('update_color_job')->info($message);
        return true;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        self::putLog("Job update product sizes from erp start time : " . date("Y-m-d H:i:s"));

        $affectedProducts = ScrapedProducts::matchedSizes($this->from);
        //getting sku array 
        $scrapedProductSkuArray = [];
        foreach ($affectedProducts as $scraped) {
           $scrapedProductSkuArray[] = $scraped->sku;
        }

        //$sku = [];
        if (count($scrapedProductSkuArray) != 0) {
            foreach ($scrapedProductSkuArray as $productSku) {
                self::putLog("Scrapeed Product {$productSku} update start time : ". date("Y-m-d H:i:s"));
                $oldProduct = Product::where('sku', $productSku)->first();
                if($oldProduct->size){
                    //$sizes = explode(',', $oldProduct->size);
                    //$newArray = [];
                    // foreach ($sizes as $size) {
                    //     if($size == $this->from){
                    //         //$newArray[] = $this->to;
                    //     }else{
                    //        $newArray[] = $size; 
                    //     }
                    // }
                    $newSize = $oldProduct->size.','.$this->to;
                }elseif(empty($oldProduct->size)){
                    $newSize = $this->to;
                }
                
                if(isset($newSize)){
                    $oldProduct->size = $newSize;
                    $oldProduct->save();  
                }

                
            }
        }

        //\Log::info(print_r($sku,true));

        self::putLog("Job update product sizes from erp end time : " . date("Y-m-d H:i:s"));

        return true;
    }
}

<?php

namespace App\Jobs;

use App\Category;
use App\Product;
use App\ScrapedProducts;
use App\Supplier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateScrapedCategory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $params;
    public $product_id;
    public $category_id;
    public $user_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->product_id  = $params["product_id"];
        $this->category_id = $params["category_id"];
        $this->user_id = isset($params["user_id"]) ? $params["user_id"] : 6;
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

        self::putLog("Job start time : ". date("Y-m-d H:i:s"));
        self::putLog("Params : " . print_r([$this->product_id,$this->category_id],true));
        //\Log::info("this is called");

        $product      = Product::find($this->product_id);
        $cat          = $this->category_id;
        $lastcategory = false;
        if($product) {
            $scrapedProductSkuArray[] = $product->id; 
        }

        if ($product->scraped_products) {
            if (isset($product->scraped_products->properties) && isset($product->scraped_products->properties['category']) != null) {
                $category           = $product->scraped_products->properties['category'];
                if(is_array($category)) {
                    $referencesCategory = implode(' ', $category);
                    $lastcategory       = end($category);
                }
            }
        } else {
            return;
        }


        if (isset($referencesCategory)) {
             
            self::putLog("referencesCategory : " . $referencesCategory . " ||  category : ".json_encode($category));

            $productSupplier = $product->supplier;
            $supplier        = Supplier::where('supplier', $productSupplier)->first();
            if($supplier && $supplier->scraper) {
                $scrapedProducts = ScrapedProducts::where('website', $supplier->scraper->scraper_name)->get();

                self::putLog("Scrapeed Product Query time : ". date("Y-m-d H:i:s"));
                self::putLog("supplier : " . $productSupplier . " ||  Scraped Product Found : ".$scrapedProducts->count());

                foreach ($scrapedProducts as $scrapedProduct) {
                    if (isset($scrapedProduct->properties['category'])) {
                        $products = $scrapedProduct->properties['category'];
                        if (is_array($products)) {
                            $list = implode(' ', $products);
                            if (strtolower($referencesCategory) == strtolower($list)) {
                                $scrapedProductSkuArray[] = $scrapedProduct->product_id;
                            }
                        }
                    }
                }
            }

            if (!isset($scrapedProductSkuArray)) {
                $scrapedProductSkuArray = [];
            }
            self::putLog("Matched Product ID : " . json_encode($scrapedProductSkuArray));

            //Add reference to category
            $category = Category::find($cat);
            if ($lastcategory) {
                // find the current category and move its
                $refCat    = explode(",", $category->references);
                $refCat[]  = $lastcategory;
                $reference = implode(",", array_unique($refCat));

                // refrences updated
                $category->references = $reference;
                $category->save();
            }
        }

        //Update products with sku
        $totalUpdated = 0;
        if (count($scrapedProductSkuArray) != 0) {
            $scrapedProductSkuArray = array_unique($scrapedProductSkuArray);
            foreach ($scrapedProductSkuArray as $productSku) {
                self::putLog("Scrapeed Product {$productSku} update start time : ". date("Y-m-d H:i:s"));
                $oldProduct = Product::where('id', $productSku)->first();
                if ($oldProduct != null) {
                    //\Log::info("this is called");
                    if(!empty($this->user_id)) {
                        $productCatHis = new \App\ProductCategoryHistory;
                        $productCatHis->user_id = ($this->user_id) ? $this->user_id : 6; 
                        $productCatHis->category_id = $cat; 
                        $productCatHis->old_category_id = $oldProduct->category;
                        $productCatHis->product_id = $oldProduct->id;
                        $productCatHis->save(); 
                    }

                    $oldProduct->category = $cat;
                    $oldProduct->save();

                    \App\ProductStatus::pushRecord($oldProduct->id,"MANUAL_CATEGORY");

                    $totalUpdated++;
                    self::putLog("Scrapeed Product {$productSku} update end time : ". date("Y-m-d H:i:s"));
                }
            }
        }

        \App\Notification::create([
            "role" => "Admin",
            "message" => $totalUpdated." product has been affected while update category",
            "product_id" => $product->id,
            "user_id" => 6
        ]);

        return response()->json(['success', 'Product Got Updated']);
    }
}

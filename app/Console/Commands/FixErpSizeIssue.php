<?php

namespace App\Console\Commands;

use App\Helpers\ProductHelper;
use App\Helpers\StatusHelper;
use App\Product;
use App\ProductSizes;
use App\ProductSupplier;
use App\ScrapedProducts;
use Illuminate\Console\Command;

class FixErpSizeIssue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix-erp-size-issue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix Erp size issue';

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
        //
        $products = Product::join("scraped_products as sp","sp.product_id","products.id")->where("products.status_id", StatusHelper::$sizeVerifyCron)->where("products.supplier_id",">",0)
        ->where(function($q) {
            $q->where("sp.size","!=","")->where("sp.size","!=","0");
        })->where(function($q) {
            $q->orWhereNull("products.size")->orWhere("products.size","=","");
        })
        ->select("products.*")->get();

        if (!$products->isEmpty()) {
            foreach ($products as $product) {
                $this->info("Started for product id :" . $product->id);
                $scrapedProduct = ScrapedProducts::where("product_id", $product->id)->where(function($q) {
                    $q->orWhereNotNull("size")->orWhere("size","!=","");
                })->first();
                if ($scrapedProduct) {
                    $this->info("Product being updated for {$product->sku} with {$scrapedProduct->size_system} and {$scrapedProduct->size}"); 
                    if (!empty($scrapedProduct->size)) {
                        $sizes  = explode(",", $scrapedProduct->size);
                        $euSize = [];
                        // Loop over sizes and redactText
                        $allSize = [];
                        if (is_array($sizes) && $sizes > 0) {
                            foreach ($sizes as $size) {
                                $allSize[]  = ProductHelper::getRedactedText($size, 'composition');
                            }
                        }

                        $product->size = implode(',', $allSize);
                        // get size system
                        $supplierSizeSystem = ProductSupplier::getSizeSystem($product->id, $product->supplier_id);
                        $euSize             = ProductHelper::getEuSize($product, $allSize, !empty($supplierSizeSystem) ? $supplierSizeSystem : $scrapedProduct->size_system);
                        $product->size_eu   = implode(',', $euSize);
                        ProductSizes::where('product_id', $product->id)->where('supplier_id', $product->supplier_id)->delete();
                        if (empty($euSize)) {
                            $product->status_id = StatusHelper::$unknownSize;
                        } else {
                            foreach ($euSize as $es) {
                                ProductSizes::updateOrCreate([
                                    'product_id' => $product->id, 'supplier_id' => $product->supplier_id, 'size' => $es,
                                ], [
                                    'product_id' => $product->id, 'quantity' => 1, 'supplier_id' => $product->supplier_id, 'size' => $es,
                                ]);
                            }
                            $product->status_id = StatusHelper::$autoCrop;
                        }

                        $product->save();

                        $product->checkExternalScraperNeed();
                        $this->info("Saved product id :" . $product->id);

                        // check for the auto crop
                        $needToCheckStatus = [
                            StatusHelper::$requestForExternalScraper,
                            StatusHelper::$unknownComposition,
                            StatusHelper::$unknownColor,
                            StatusHelper::$unknownCategory,
                            StatusHelper::$unknownMeasurement,
                            StatusHelper::$unknownSize,
                        ];

                        if (!in_array($product->status_id, $needToCheckStatus)) {
                            $product->status_id = StatusHelper::$autoCrop;
                        }

                        $product->save();
                    }else{
                        $product->status_id = StatusHelper::$unknownSize;
                        $product->save();
                    }
                }else{
                    $product->status_id = StatusHelper::$unknownSize;
                    $product->save();
                }
            }
        }

    }
}

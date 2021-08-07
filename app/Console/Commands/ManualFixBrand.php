<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ManualFixBrand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manual-fix:brand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is using for manual fix brand';

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
        // start to get brands first
        $skuBrands = \App\SkuFormat::leftJoin("brands as b", "b.id", "sku_formats.brand_id")->select(["sku_formats.*", "b.name as brand_name"])->get();
        foreach ($skuBrands as $sb) {
            $brands = \App\Brand::where("name", $sb->brand_name)->whereNotIn("id", [$sb->brand_id])->get();
            if (!$brands->isEmpty()) {
                foreach ($brands as $brand) {
                    $products        = \App\Product::where("brand", $brand->id)->update(array('brand' => $sb->brand_id));
                    $scrapedProducts = \App\ScrapedProducts::where("brand_id", $brand->id)->update(array('brand_id' => $sb->brand_id));
                    $brand->delete();
                    \Log::channel('productUpdates')->info(sprintf("Brand id %s updated with brand id %s",$brand->id,$sb->brand_id));
                }
            }else{
                \Log::channel('productUpdates')->info(sprintf("Not matched brand found : %s",$sb->brand_name));
            }
        }
    }
}   
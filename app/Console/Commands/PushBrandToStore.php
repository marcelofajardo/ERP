<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use seo2websites\MagentoHelper\MagentoHelper;

class PushBrandToStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push-to-magento:brand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push Brand to magento';

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
        $limit      = $this->ask('Limit of brands need to push ?');
        $webLimit   = $this->ask('Which website you need to push ?');

        $brands = \App\Product::join("brands as b","b.id","products.brand")->groupBy("b.id")->select(["b.*"])->limit($limit)->get();

        if(!empty($webLimit)) {
            $webLimit = explode(",", $webLimit);
            $storeWebsites = \App\StoreWebsite::whereIn("id",$webLimit)->where("api_token", "!=", "")->where("website_source", "magento")->get();
        }else{
            $storeWebsites = \App\StoreWebsite::where("api_token","!=","")->where("website_source","magento")->get();
        }

        if(!$brands->isEmpty()) {
            foreach($brands as $brand) {
                echo "$brand->name started to push\n";
                if(!$storeWebsites->isEmpty()) {
                    foreach($storeWebsites as $storeWeb) {
                        echo "$storeWeb->website started to push\n";
                        $magentoBrandId = MagentoHelper::addBrand($brand,$storeWeb);
                        if(!empty($magentoBrandId)){
                            echo "$magentoBrandId result has been found\n";
                            $brandStore = \App\StoreWebsiteBrand::where("brand_id", $brand->id)->where("store_website_id", $storeWeb->id)->first();
                            if(!$brandStore) {
                               $brandStore =  new \App\StoreWebsiteBrand;
                               $brandStore->brand_id = $brand->id;
                               $brandStore->store_website_id = $storeWeb->id;
                            }
                            $brandStore->magento_value = $magentoBrandId;
                            $brandStore->save();
                        }
                    }
                }
            }
        }
    }
}

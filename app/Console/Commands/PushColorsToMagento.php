<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PushColorsToMagento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'colors:push-to-mangento';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push colors to magento';

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
        $website    = \App\StoreWebsite::where("website_source", "magento")->where("api_token", "!=", "")->get();
        $colorsData = \App\ColorNamesReference::groupBy('erp_name')->get();
        if (!$colorsData->isEmpty()) {
            foreach ($colorsData as $cd) {
                echo "Color Started  : " . $cd->erp_name;
                foreach ($website as $web) {
                    echo "Store Started  : " . $web->website;
                    $checkSite = \App\StoreWebsiteColor::where("erp_color", $cd->erp_name)->where("store_website_id", $web->id)->where("platform_id", ">", 0)->first();
                    if (!$checkSite) {
                        $id = \seo2websites\MagentoHelper\MagentoHelper::addColor($cd->erp_name, $web);
                        if (!empty($id)) {
                            \App\StoreWebsiteColor::where("erp_color", $cd->erp_name)->where("store_website_id", $web->id)->delete();
                            $swc                   = new \App\StoreWebsiteColor;
                            $swc->erp_color        = $cd->erp_name;
                            $swc->store_website_id = $web->id;
                            $swc->platform_id      = $id;
                            $swc->save();
                        }
                    }
                }
            }
        }
    }
}

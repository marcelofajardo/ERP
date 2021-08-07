<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PushSizeToMagento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'size:push-to-mangento';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push Size to magento';

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
        $website = \App\StoreWebsite::where("website_source", "magento")->where("api_token", "!=", "")->get();
        $sizes   = \App\Size::all();
        foreach ($sizes as $s) {
            echo "Size Started  : " . $s->name;
            foreach($website as $web) {
                echo "Store Started  : " . $web->website;
                $checkSite = \App\StoreWebsiteSize::where("size_id", $s->id)->where("store_website_id", $web->id)->where("platform_id",">",0)->first();
                if (!$checkSite) {
                    $id = \seo2websites\MagentoHelper\MagentoHelper::addSize($s, $web);
                    if (!empty($id)) {
                        \App\StoreWebsiteSize::where("size_id", $s->id)->where("store_website_id", $web->id)->delete();
                        $sws                   = new \App\StoreWebsiteSize;
                        $sws->size_id          = $s->id;
                        $sws->store_website_id = $web->id;
                        $sws->platform_id      = $id;
                        $sws->save();
                    }
                }
            }
        }
    }
}

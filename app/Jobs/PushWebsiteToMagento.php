<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PushWebsiteToMagento implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $_website;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($website)
    {
        // Set product and website
        $this->_website = $website;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Set time limit
        set_time_limit(0);

        $website = $this->_website;

        if ($website) {

            \Log::channel('productUpdates')->info("Website pushed start" . $website->id);

            $id = \seo2websites\MagentoHelper\MagentoHelper::pushWebsite([
                "type" => "website",
                "name" => $website->name,
                "code" => replace_dash(strtolower($website->code)),
            ], $website->storeWebsite);

            if (!empty($id) && is_numeric($id)) {

                \Log::channel('productUpdates')->info("Website pushed with id : " . $id);
                $website->platform_id = $id;

                if ($website->save()) {
                    // start uploading
                    $stores = $website->stores;
                    if (!$stores->isEmpty()) {
                        \Log::channel('productUpdates')->info("Website Store pushed start");
                        foreach ($stores as $store) {

                            $id = \seo2websites\MagentoHelper\MagentoHelper::pushWebsiteStore([
                                "type"       => "store",
                                "name"       => $store->name,
                                "code"       => replace_dash(strtolower($store->code)),
                                "website_id" => $website->platform_id,
                            ], $website->storeWebsite);

                            if (!empty($id) && is_numeric($id)) {

                                \Log::channel('productUpdates')->info("Website Store pushed =>" . $id);

                                $store->platform_id = $id;
                                if ($store->save()) {
                                    $storeView = $store->storeView;
                                    if (!$storeView->isEmpty()) {

                                        \Log::channel('productUpdates')->info("Website Store view start");

                                        foreach ($storeView as $sView) {
                                            $id = \seo2websites\MagentoHelper\MagentoHelper::pushWebsiteStoreView([
                                                "type"     => "store_view",
                                                "name"     => $sView->name,
                                                "code"     => replace_dash(strtolower($sView->code)),
                                                "website_id" => $website->platform_id,
                                                "group_id" => $store->platform_id,
                                            ], $website->storeWebsite);

                                            if (!empty($id) && is_numeric($id)) {

                                                \Log::channel('productUpdates')->info("Website Store view pushed =>" . $id);

                                                $sView->platform_id = $id;
                                                $sView->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

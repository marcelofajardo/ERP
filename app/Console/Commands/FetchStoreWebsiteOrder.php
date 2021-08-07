<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchStoreWebsiteOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch-store-website:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Store website orders';

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
        $storeWebsite = \App\StoreWebsite::all();
        foreach($storeWebsite as $sW) {
            // if site is in magento the fetch orders
            if($sW->website_source == "magento") {
                if (class_exists('\\seo2websites\\MagentoHelper\\MagentoHelper')) {
                    \seo2websites\MagentoHelper\MagentoHelper::fetchOrder($sW);
                }
            }
        }

    }
}

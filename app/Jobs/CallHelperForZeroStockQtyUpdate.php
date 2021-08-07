<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use seo2websites\MagentoHelper\MagentoHelper;
use App\Helpers\ProductHelper;

class CallHelperForZeroStockQtyUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $products;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($products)
    {
        $this->products = $products;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $zeroStock = [];
        if (!empty($this->products)) {
            foreach ($this->products as $item) {
                $websiteArrays = ProductHelper::getStoreWebsiteNameFromPushed($item['id']);
                if (count($websiteArrays) > 0) {
                    foreach ($websiteArrays as $websiteArray) {
                        $zeroStock[$websiteArray]['stock'][] = array('sku' => $item['sku'], 'qty' => 0);
                    }
                }
            }
        }

        if(!empty($zeroStock)) {
            if (class_exists('\\seo2websites\\MagentoHelper\\MagentoHelper')) {
                MagentoHelper::callHelperForZeroStockQtyUpdate($zeroStock);
                \Log::info('inventory:update Jobs Run');
            }
        }

    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Category;
use App\Language;
use App\StoreWebsite;
use App\StoreWebsiteCategory;
use Illuminate\Http\Response;
use App\StoreWebsiteCategorySeo;
use seo2websites\MagentoHelper\MagentoHelper;

class PushCategorySeoToMagento implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $category;
    protected $stores;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($categories, $stores)
    {
        $this->category = $categories;
        $this->stores = $stores;
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
        Category::pushStoreWebsiteCategory($this->category,$this->stores);
    } 
}

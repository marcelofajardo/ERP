<?php

namespace App\Console\Commands\Manual;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Modules\StoreWebsite\Http\Controllers\CategoryController;

class CategoryPushStoreWebsite extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store-website:push-category-in-live';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store website send push category';

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
        $storeWebsite = \App\StoreWebsite::where(function ($q) {
            $q->where("api_token", "!=", "")->orWhere(function ($q) {
                $q->where("magento_url", "!=", "")->where("magento_username", "!=", "")->where("magento_password", "");
            });
        })->get();

        foreach ($storeWebsite as $sw) {

            $category = \DB::table("categories")->leftJoin('store_website_categories as swc', function ($join) use ($sw) {
                $join->on('categories.id', '=', 'swc.category_id');
                $join->where('swc.store_website_id', '=', $sw->id);
            })->whereNull("swc.remote_id")->select(["categories.*"])->pluck('id')->toArray();

            $myRequest = new Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add([
                'categories' => $category,
                'website_id' => $sw->id,
            ]);

            app(CategoryController::class)->storeMultipleCategories($myRequest);
        }
    }
}

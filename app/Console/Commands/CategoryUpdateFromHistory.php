<?php

namespace App\Console\Commands;

use DB;
use Exception;
use Illuminate\Console\Command;

class CategoryUpdateFromHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category-update:from-history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Category from history';

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
        $allProducts = \App\ProductCategoryHistory::where("product_id","!=",0)
        ->groupBy("product_id")
        ->orderBy("created_at","desc")
        ->select(["product_id","category_id"])->get();

        if(!$allProducts->isEmpty()) {
            foreach($allProducts as $allProduct) {
                $product = $allProduct->product;
                if($product) {
                   $product->category = $allProduct->category_id;
                   $product->save();
                   echo $product->id." DONE".PHP_EOL;
                   // save to product status history 
                   \App\ProductStatus::pushRecord($allProduct->product_id,"MANUAL_CATEGORY");
                }
            }
        }

    }
}

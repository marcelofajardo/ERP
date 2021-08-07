<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Category;

class DeleteCategoriesWithNoProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete-categories:with-no-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete categories with no products';

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
        set_time_limit(0);
        
        ini_set("memory_limit", "-1");
        
        $unKnownCategory  = Category::where('title', 'LIKE', '%Unknown Category%')->first();
        if ($unKnownCategory) {
            $unKnownCatArr   = array_unique(explode(',', $unKnownCategory->references));
            $fixedCategories = array_unique(explode(',', $unKnownCategory->ignore_category));
            $deltaCategories = $fixedCategories;
            if (!empty($unKnownCatArr)) {
                $storeUnUserCategory = [];
                foreach ($unKnownCatArr as $key => $unKnownC) {
                    $this->info("Started for category :" . $unKnownC);
                    $deltaCategories[] = $unKnownC;
                    $unKnownCategory->ignore_category = implode(',',array_filter($deltaCategories));
                    $unKnownCategory->save();
                    if(!in_array($unKnownC,$fixedCategories)) {
                       $count = \App\Category::ScrapedProducts($unKnownC);
                       $this->info("Product count match ({$count}) :" . $unKnownC);
                       if ($count <= 0) {
                           $storeUnUserCategory[] = $unKnownC;
                           unset($unKnownCatArr[$key]);
                           $unKnownCategory->references      = implode(',',array_filter($unKnownCatArr));
                           $unKnownCategory->save();
                       } 
                    }else{
                        $this->info("Already fetched record :" . $unKnownC);
                    }
                }
                
            }
        }
    }
}

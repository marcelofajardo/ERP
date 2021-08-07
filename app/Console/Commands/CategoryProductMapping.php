<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ScrappedCategoryMapping;
use App\ScrapedProducts;
use App\ScrappedProductCategoryMapping;

class CategoryProductMapping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapping:product-category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scraped Products Category Mapping';

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
        $all_category = ScrappedCategoryMapping::where('is_mapped', 0)->get()->pluck('name', 'id')->toArray();

        dump('Total Category: ' . count($all_category));

        foreach ($all_category as $k => $v) {

            $products = \App\ScrapedProducts::where("properties", "like", '%' . $v . '%')
                ->select('website', 'id')
                ->distinct()
                ->get()
                ->pluck('website', 'id')
                ->toArray();

            foreach ($products as $kk => $vv) {

                $web_name = $vv;
                if ($web_name) {

                    $exist = ScrappedProductCategoryMapping::where('category_mapping_id', $k)
                        ->where('product_id', $kk)
                        ->exists();

                    if (!$exist) {
                        ScrappedProductCategoryMapping::insert([
                            'category_mapping_id' => $k,
                            'product_id' => $kk,
                        ]);
                    }
                }
            }
            
            ScrappedCategoryMapping::where('id', $k)->update(['is_mapped' => 1]);
            dump('Category processed: => ' . $v);

        }
    }
}

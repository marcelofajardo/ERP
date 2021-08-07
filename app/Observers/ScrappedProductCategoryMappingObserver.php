<?php

namespace App\Observers;

use App\ScrapedProducts;
use App\Category;
use App\ScrappedCategoryMapping;
use App\ScrappedProductCategoryMapping;

class ScrappedProductCategoryMappingObserver
{
    /**
     * Handle the ScrapedProducts "created" event.
     *
     * @param  \App\ScrapedProducts  $scrapedproducts
     * @return void
     */
    public function created(ScrapedProducts $scrapedproducts)
    {
        //
        $this->create($scrapedproducts);
    }

    /**
     * Handle the ScrapedProducts "updated" event.
     *
     * @param  \App\ScrapedProducts  $scrapedproducts
     * @return void
     */
    public function updated(ScrapedProducts $scrapedproducts)
    {
        $this->create($scrapedproducts);
    }

    /**
     * Handle the ScrapedProducts "deleted" event.
     *
     * @param  \App\ScrapedProducts  $scrapedproducts
     * @return void
     */
    public function deleted(ScrapedProducts $scrapedproducts)
    {
        //
    }

    /**
     * Handle the ScrapedProducts "restored" event.
     *
     * @param  \App\ScrapedProducts  $scrapedproducts
     * @return void
     */
    public function restored(ScrapedProducts $scrapedproducts)
    {
        //
    }

    /**
     * Handle the ScrapedProducts "force deleted" event.
     *
     * @param  \App\ScrapedProducts  $scrapedproducts
     * @return void
     */
    public function forceDeleted(ScrapedProducts $scrapedproducts)
    {
        //
    }


    protected function create($scrapedproducts)
    {
        /*
        $all_category = ScrappedCategoryMapping::get()->pluck('name','id')->toArray();

        if($scrapedproducts->properties != null)
        {
            $pro_arr = [];

            $all_categ = ScrappedProductCategoryMapping::where('product_id',$scrapedproducts->id)->get()->pluck('category_id')->toArray();

            foreach ($all_category as $key => $val)
            {
                if(is_string($val)) {
                    if(strpos($scrapedproducts->properties, $val) !== false && !in_array($key, $all_categ)){
                        $pro_arr[] = ['category_mapping_id' => $key,
                            'product_id' => $scrapedproducts->id
                        ];
                    } 
                }
            }

            if(!empty($pro_arr)) {
                ScrappedProductCategoryMapping::insert($pro_arr);
            }
        }
        */
        
    }
}

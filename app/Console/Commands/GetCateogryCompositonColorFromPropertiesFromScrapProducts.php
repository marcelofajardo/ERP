<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ScrapedProducts;

class GetCateogryCompositonColorFromPropertiesFromScrapProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'details-from:properties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Getting category , color and composition from properties and save it to base column';

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
        $products = ScrapedProducts::select('id','sku','properties')->whereNull('categories')->whereNull('color')->whereNull('composition')->orderBy('id','desc')->get();

        foreach ($products as $product) {
            $properties = $product->properties;

            dump('Start with Scraped Product with sku '.$product->sku);


            $categoryForScrapedProducts = '';
            $colorForScrapedProducts = '';
            $compositionForScrapedProducts = '';

            try{
                if(isset($properties['category'])){
                    if(is_array($properties['category'])){
                        $categoryForScrapedProducts = implode(',',$properties['category']);
                    }else{
                        $categoryForScrapedProducts = $properties['category'];
                    }
                }else{
                   dump('Category not present in this properties'); 
                }
            }catch(\Exception $e){
                dump('Facing issue in category');
            }
            
            try{
                //color for scraperProducts for
                if(isset($properties['color'])){
                    if(is_array($properties['color'])){
                        $colorForScrapedProducts = implode(',',$properties['color']);
                    }else{
                        $colorForScrapedProducts = $properties['color'];
                    }
                }else{
                    dump('Color not present in this properties'); 
                }

            }catch(\Exception $e){
                dump('Facing issue in color');
            }
            
            try{
                //compostion for scraped Products
                if(isset($properties['material_used'])){
                    if(is_array($properties['material_used'])){
                        $compositionForScrapedProducts = implode(',',$properties['material_used']);
                    }else{
                        $compositionForScrapedProducts = $properties['material_used'];
                    }
                }else{
                    dump('Composition not present in this properties'); 
                }
            }catch(\Exception $e){
                dump('Facing issue in composition');
            }

            $product->categories = $categoryForScrapedProducts;
            $product->color = $colorForScrapedProducts;
            $product->composition = $compositionForScrapedProducts;
            $product->save();
            dump('Details saved from properties to base field for id '.$product->id);
        }

    }
}

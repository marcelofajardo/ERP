<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Product;

class ProductMoveBrand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product-move:brand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Product move brand';

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
        $products  = Product::where("updated_at",">=","2021-01-18 00:00:00")->where('last_brand',">",0)->get();
        foreach($products as $product) {
            try{
                $oldBrand = $product->brand;
                if($product->brand != (int)$product->last_brand) {
                    $product->brand = (int)$product->last_brand;
                    $product->save();
                    echo "Product {$product->id} updated {$oldBrand} to {$product->last_brand}";
                    echo PHP_EOL;
                }
            }catch(\Exeception $e) {
                echo "Product {$product->id} having issue {$oldBrand} to {$product->last_brand}";
                echo PHP_EOL;
            }   
        }
    }
}

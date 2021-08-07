<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemoveProductWithNoImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove-product-images:no-path';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove images with no path exist';

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
        $products = \App\Product::where("is_barcode_check",1)->limit(10000)->get();
        if(!$products->isEmpty()) {
            foreach($products as $product) {
                $medias = $product->getAllMediaByTag();
                if(!$medias->isEmpty()) {
                    foreach($medias as $t => $media) {
                        if(!$media->isEmpty()) {
                            foreach($media as $med) {
                                $path = $med->getAbsolutePath();
                                if(!file_exists($path)) {
                                    $med->delete();
                                    echo $product->id." > $t > $path deleted";
                                    echo PHP_EOL;
                                }
                            }
                        }
                    }
                }
                $product->is_barcode_check = 0;
                $product->save();
            }
        }
    }
}

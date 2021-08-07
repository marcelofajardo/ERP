<?php

namespace App\Console\Commands\Manual;

use App\CronJobReport;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttachProductIdOnScraperProductTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attach-product-id:scraper-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attach Product id in scraper product table';

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
        // fetch first all order products and then attach the product id into that table
        $scraperProduct = \App\ScrapedProducts::whereNull("product_id")->get();
        
        if(!$scraperProduct->isEmpty()) {
            foreach($scraperProduct as $sp) {
                $product = Product::where("sku",$sp->sku)->first();
                if($product) {
                    $sp->product_id = $product->id;
                }else{
                    $sp->product_id = 0;
                    echo $sp->sku. " can not found in list".PHP_EOL;
                }
                $sp->save();
            }
        }else {
            echo "All product has been updated now from given table";
        }

    }
}

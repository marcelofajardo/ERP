<?php

namespace App\Console\Commands\Manual;

use App\CronJobReport;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttachProductIdOnOrderProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attach-product-id:order-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attach Product id in order product table';

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
        $orderProducts = \App\OrderProduct::whereNull("product_id")->limit(1000)->get();
        
        if(!$orderProducts->isEmpty()) {
            foreach($orderProducts as $orderProduct) {
                $product = Product::where("sku",$orderProduct->sku)->first();
                if($product) {
                    $orderProduct->product_id = $product->id;
                }else{
                    $orderProduct->product_id = 0;
                    echo $orderProduct->sku. " can not found in list".PHP_EOL;
                }
                $orderProduct->save();
            }
        }else {
            echo "All product has been updated now from given table";
        }

    }
}

<?php

namespace App\Console\Commands\Manual;

use App\Brand;
use App\Product;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ProductSpecialPriceEur extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'price:special';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get the price from price table and convert price to price_eur_special';

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
        try {
            $report = \App\CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);
            //Getting all products and convert to special price
            $products = Product::select('id', 'price', 'price_eur_special', 'brand')->where('price_eur_special', 0)->where('price', '!=', 0)->get();

            foreach ($products as $product) {
                $finalPrice = $product->price;
                if ($product->brand == 0 || $product->brand == '' || $product->brand == null) {
                    continue;
                }

                $brand = Brand::find($product->brand);

                if ($brand == null && $brand == '') {
                    continue;
                }

                if ($brand->deduction_percentage == null || $brand->deduction_percentage == 0) {
                    continue;
                }

                $priceEurSpecial = $finalPrice - ($finalPrice * $brand->deduction_percentage) / 100;
                if ($priceEurSpecial == 0) {
                    continue;
                }
                $product->price_eur_special = $priceEurSpecial;
                $product->update();

            }
            dump('All Prices Updated');
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}

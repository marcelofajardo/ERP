<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteKidsProductsFromProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:kids-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            Product::where('name', 'LIKE', '%kids%')->orWhere('short_description', 'LIKE', '%kids%')->orWhere('name', 'LIKE', '%Little boy%')->orWhere('short_description', 'LIKE', '%little boy%')->orWhere('name', 'LIKE', '%Little girl%')->orWhere('short_description', 'LIKE', '%little girl%')->chunk(1000, function ($products) {
                foreach ($products as $product) {
                    DB::table('log_scraper_vs_ai')->where('product_id', $product->id)->delete();
                    DB::table('product_suppliers')->where('product_id', $product->id)->delete();
                    DB::table('scraped_products')->where('sku', $product->sku)->delete();
                    DB::table('product_references')->where('product_id', $product->id)->delete();
                    DB::table('user_products')->where('product_id', $product->id)->delete();
                    DB::table('suggestion_products')->where('product_id', $product->id)->delete();
                    $product->forceDelete();
                    dump('deleted');
                }
            });

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}

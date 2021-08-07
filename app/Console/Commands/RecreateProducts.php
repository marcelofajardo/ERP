<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\ScrapedProducts;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecreateProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recreate:products-scraped';

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

            $products = ScrapedProducts::where('website', 'angelominetti')->get();
            // dd(count($products));
            foreach ($products as $key => $product) {
                app('App\Services\Products\ProductsCreator')->createProduct($product);

                dump("$key - created product");
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}

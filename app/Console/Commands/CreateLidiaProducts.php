<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\ScrapedProducts;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CreateLidiaProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:lidia-products';

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

            $scraped_products = ScrapedProducts::where('website', 'lidiashopping')->get();

            foreach ($scraped_products as $scraped_product) {
                app('App\Services\Products\LidiaProductsCreator')->createProduct($scraped_product);
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}

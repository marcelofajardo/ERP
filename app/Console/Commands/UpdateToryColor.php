<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Product;
use App\ScrapedProducts;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateToryColor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:tory-color';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $scraper;

    /**
     * Create a new command instance.
     *
     * @param GebnegozionlineProductDetailsScraper $scraper
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

            $products = ScrapedProducts::where('has_sku', 1)->where('website', 'Tory')->get();

            foreach ($products as $product) {
                if ($old_product = Product::where('sku', $product->sku)->first()) {
                    $properties_array = $product->properties;

                    if (array_key_exists('color', $properties_array)) {
                        $old_product->color = $properties_array['color'];
                        $old_product->save();
                    }
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}

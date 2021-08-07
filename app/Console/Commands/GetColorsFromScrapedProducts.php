<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetColorsFromScrapedProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:fetch-color-from-scraped-products';

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

            Product::where('is_approved', 0)->orderBy('id', 'DESC')->where('color', '')->chunk(1000, function ($products) {
                foreach ($products as $product) {
                    $scrapedProducts = $product->many_scraped_products;
//                dump($product->name);
                    //                dump(count($scrapedProducts));
                    if (!$scrapedProducts) {
                        continue;
                    }

                    foreach ($scrapedProducts as $scrapedProduct) {
                        $properties = $scrapedProduct->properties;
                        $color1     = $properties['color'] ?? null;
                        $color2     = $properties['colors'] ?? null;
                        if ($color1 !== 'null' && $color1 !== null && $color1 !== '') {
                            $product->color = $color1;
                            $product->save();
                            continue;
                        }
                        if ($color2 !== 'null' && $color2 !== null && $color2 !== '') {
                            $product->color = $color2;
                            $product->save();
                            continue;
                        }
                    }
                }
            });

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}

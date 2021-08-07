<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetCompositionFromScrapedProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:fetch-composition-from-scraped-products';

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

            Product::where('is_approved', 0)->orderBy('id', 'DESC')->where(function ($query) {
                $query->where('composition', '')->whereRaw('`short_description` = `composition`');
            })->chunk(1000, function ($products) {
                foreach ($products as $product) {
                    $scrapedProducts = $product->many_scraped_products;
                    if (!$scrapedProducts) {
                        continue;
                    }

                    foreach ($scrapedProducts as $scrapedProduct) {
                        $properties  = $scrapedProduct->properties;
                        $composition = $properties['material_used'] ?? null;
                        if ($composition !== 'null' && $composition !== null && $composition !== '') {
                            dump($composition);
                            $product->composition = title_case($composition);
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

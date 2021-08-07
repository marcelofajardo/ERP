<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Product;
use Illuminate\Console\Command;
use Carbon\Carbon;

class FetchMeasurementsIfScraped extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'measurements:get-from-scraped';

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

            Product::where(function ($query) {
                $query->where('lmeasurement', '')->orWhereNull('lmeasurement');
            })
                ->where(function ($query) {
                    $query->where('hmeasurement', '')->orWhereNull('hmeasurement');
                })
                ->where(function ($query) {
                    $query->where('dmeasurement', '')->orWhereNull('dmeasurement');
                })
                ->orderBy('created_at', 'DESC')->chunk(1000, function ($products) {
                foreach ($products as $product) {
                    dump($product->id);
                    $scrapedProducts = $product->many_scraped_products;
                    foreach ($scrapedProducts as $scrapedProduct) {
                        $property = $scrapedProduct->properties['dimension'] ?? [];
                        if ($property !== [] && $property !== [null, null, null]) {
                            preg_match('/\d+/', $property[0] ?? '', $lmeasurement);
                            preg_match('/\d+/', $property[1] ?? '', $hmeasurement);
                            preg_match('/\d+/', $property[2] ?? '', $dmeasurement);
                            dump($lmeasurement, $hmeasurement, $dmeasurement);
                            $product->lmeasurement = $lmeasurement[0] ?? null;
                            $product->hmeasurement = $hmeasurement[0] ?? null;
                            $product->dmeasurement = $dmeasurement[0] ?? null;
                            $product->save();
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

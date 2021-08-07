<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Product;
use App\ScrapedProducts;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FixDoubleFSku extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sku:fix-doublef';

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

            $products = ScrapedProducts::where('website', 'doubleF')->get();
            foreach ($products as $product) {
                $sku        = $product->sku;
                $properties = $product->properties;
                $colorCode  = null;
                if (isset($properties['Color code'])) {
                    $colorCode = explode('-', $properties['Color code']);
                    if (count($colorCode) !== 2) {
                        continue;
                    }
                }

                if (isset($properties['Codice colore'])) {
                    $colorCode = explode('-', $properties['Codice colore']);
                    if (count($colorCode) !== 2) {
                        continue;
                    }
                }

                if ($colorCode === null) {
                    return;
                }

                $colorCode = $colorCode[1];
                $sku2      = $sku . $colorCode;

                Product::where('sku', $sku)->update([
                    'sku' => $sku2,
                ]);

                $product->sku = $sku2;
                $product->save();
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}

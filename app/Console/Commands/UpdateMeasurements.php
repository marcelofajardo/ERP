<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\ScrapedProducts;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateMeasurements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:products-measurements';

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

            $scraped_products = ScrapedProducts::whereIn('website', ['alducadaosta', 'biffi', 'brunarosso', 'coltorti', 'leam', 'nugnes1920', 'montiboutique', 'mimmaninnishop', 'linoricci'])->get();

            // dd(count($scraped_products));

            foreach ($scraped_products as $key => $scrap) {
                dump("$key - Scraped Product");

                if ($scrap->product) {
                    dump("$key - Has Product");

                    $properties_array = $scrap->properties;

                    if (array_key_exists('dimension', $properties_array)) {
                        dump("$key - Has Dimension");

                        $lmeasurement          = null;
                        $hmeasurement          = null;
                        $dmeasurement          = null;
                        $measurement_size_type = null;

                        if (!is_array($properties_array['dimension'])) {
                            if (strpos($properties_array['dimension'], 'Width') !== false || strpos($properties_array['dimension'], 'W') !== false) {
                                if (preg_match_all('/Width ([\d]+)/', $properties_array['dimension'], $match)) {
                                    $lmeasurement          = (int) $match[1][0];
                                    $measurement_size_type = 'measurement';
                                }

                                if (preg_match_all('/W ([\d]+)/', $properties_array['dimension'], $match)) {
                                    $lmeasurement          = (int) $match[1][0];
                                    $measurement_size_type = 'measurement';
                                }
                            }

                            if (strpos($properties_array['dimension'], 'Height') !== false || strpos($properties_array['dimension'], 'H') !== false) {
                                if (preg_match_all('/Height ([\d]+)/', $properties_array['dimension'], $match)) {
                                    $hmeasurement = (int) $match[1][0];
                                }

                                if (preg_match_all('/H ([\d]+)/', $properties_array['dimension'], $match)) {
                                    $hmeasurement = (int) $match[1][0];
                                }
                            }

                            if (strpos($properties_array['dimension'], 'Depth') !== false || strpos($properties_array['dimension'], 'D') !== false) {
                                if (preg_match_all('/Depth ([\d]+)/', $properties_array['dimension'], $match)) {
                                    $dmeasurement = (int) $match[1][0];
                                }

                                if (preg_match_all('/D ([\d]+)/', $properties_array['dimension'], $match)) {
                                    $dmeasurement = (int) $match[1][0];
                                }
                            }

                            if (strpos($properties_array['dimension'], 'x') !== false) {
                                $formatted = str_replace('cm', '', $properties_array['dimension']);
                                $formatted = str_replace(' ', '', $formatted);
                                $exploded  = explode('x', $formatted);

                                if (array_key_exists('0', $exploded)) {
                                    $lmeasurement          = (int) $exploded[0];
                                    $measurement_size_type = 'measurement';
                                }

                                if (array_key_exists('1', $exploded)) {
                                    $hmeasurement = (int) $exploded[1];
                                }

                                if (array_key_exists('2', $exploded)) {
                                    $dmeasurement = (int) $exploded[2];
                                }
                            }
                        }

                        $scrap->product->lmeasurement          = isset($lmeasurement) ? (int) $lmeasurement : null;
                        $scrap->product->hmeasurement          = isset($hmeasurement) ? (int) $hmeasurement : null;
                        $scrap->product->dmeasurement          = isset($dmeasurement) ? (int) $dmeasurement : null;
                        $scrap->product->measurement_size_type = isset($measurement_size_type) ? $measurement_size_type : null;
                        $scrap->product->save();
                    }
                }
            }
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}

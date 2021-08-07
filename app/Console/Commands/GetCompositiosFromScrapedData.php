<?php

namespace App\Console\Commands;

use App\Compositions;
use App\CronJobReport;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetCompositiosFromScrapedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'composition:extract-from-raw-data';

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

    public function handle(): void
    {
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            Product::where('composition', '')->orWhereNull('composition')->orderBy('created_at', 'DESC')->chunk(1000, function ($products) {
                foreach ($products as $product) {
                    $scrapedProducts = $product->many_scraped_products;
                    $found           = false;
                    foreach ($scrapedProducts as $scrapedProduct) {
                        $property    = $scrapedProduct->properties;
                        $composition = $property['composition'] ?? '';
                        if ($composition) {
                            dump($composition);
                            $product->composition = $composition;
                            $product->save();
                            $found = true;
                            break;
                        }
                        $composition = $property['material_used'] ?? '';
                        if ($composition) {
                            dump($composition);
                            $product->composition = $composition;
                            $product->save();
                            $found = true;
                            break;
                        }
                        $composition = $property['Details'] ?? '';
                        if ($composition) {
                            dump($composition);
                            $product->composition = $composition;
                            $product->save();
                            $found = true;
                            break;
                        }
                    }

                    if ($found) {
                        continue;
                    }

                    foreach ($scrapedProducts as $scrapedProduct) {
                        $composition = $this->getCompositionValuesFromRawData($scrapedProduct);

                        if ($composition) {
                            $product->composition = $composition;
                            $product->save();
                            dump($composition);
                            break;
                        }
                    }

                }
            });

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    private function getCompositionValuesFromRawData($scrapedProduct)
    {
        $properties  = json_encode($scrapedProduct->properties);
        $description = $scrapedProduct->description;

        $hasExtracted = preg_match_all('/(\d+)% (\w+)/', $properties, $extractedData);

        if (!$hasExtracted) {
            $compositions = $this->getCompositionFromList($properties, $description);
            return $compositions;
        }

        $compositions = implode(', ', $extractedData[0]);
        return $compositions;

    }

    private function getCompositionFromList($properties, $description)
    {

        $hasExtracted = preg_match_all('/(\d+)% (\w+)/', $description, $extractedData);

        if ($hasExtracted) {
            dump('frommm desc..');
            return implode(', ', $extractedData[0]);
        }

        $compositionList = Compositions::pluck('name')->toArray();

        $allCompositions = [];

        foreach ($compositionList as $comp) {
            if (stripos($properties, $comp) !== false) {
                $allCompositions[] = $comp;
            }
        }

        if ($allCompositions !== []) {
            return implode(', ', $allCompositions);
        }

        foreach ($compositionList as $comp) {
            if (stripos($description, $comp) !== false) {
                $allCompositions[] = $comp;
            }
        }

        if ($allCompositions !== []) {
            return implode(', ', $allCompositions);
        }

        return implode(', ', $allCompositions);

    }

}

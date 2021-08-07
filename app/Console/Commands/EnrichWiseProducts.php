<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\ScrapedProducts;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EnrichWiseProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enrich:wiseboutique';

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

            $products = ScrapedProducts::where('website', 'Wiseboutique')->where('is_enriched', 0)->take(500)->get();

            $newProperties = [];

            foreach ($products as $product) {
                $properties = $product->properties;
                foreach ($properties as $key => $property) {
                    if (!is_array($property)) {
                        $property = $this->sanitize($property);
                        $key      = $this->getAppropriateKey($key, $property);

                        if ($this->getColors($property) !== '') {
                            $newProperties['colors'] = $this->getColors($property);
                        }
                    }
                    $newProperties[$key] = $property;
                }
                $product->description = $this->sanitize($product->description);
                $product->properties  = $newProperties;
                $product->is_enriched = 1;
                $product->save();

            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    private function getAppropriateKey($key, $value)
    {
        $value = strtoupper($value);

        if (strpos($value, 'SHOULDERS') !== false || strpos($value, 'Chest') !== false || strpos($value, 'SHOULDER') !== false) {
            return 'sizes';
        }

        return $key;
    }

    private function getColors($value)
    {
        $value          = strtoupper($value);
        $detectedColors = [];
        $colors         = [
            'WHITE',
            'RED ',
            ' RED',
            ' RED ',
            'GREEN',
            'BLUE',
            'WHITE',
            'BLACK',
            'PINK',
            'LIGHTBLUE',
            'DARK GREEN',
            'MAGENTO',
        ];

        foreach ($colors as $color) {
            if (strpos($value, $color) !== false) {
                $detectedColors[] = $color;
            }
        }

        return implode(', ', $detectedColors);

    }

    private function sanitize($value)
    {
        $value = preg_replace('/\s+/', ' ', $value);
        $value = trim($value);
        $value = str_replace('\n', ' ', $value);
        $value = str_replace(' cm', ' cm,', $value);
        $value = str_replace('size: L', 'size: L,', $value);
        $value = str_replace('size: XL', 'size: XL,', $value);
        $value = str_replace('size: XXL', 'size: XXL,', $value);
        $value = str_replace('size: XXXL', 'size: XXL,', $value);
        $value = str_replace('size: S', 'size: S,', $value);
        $value = str_replace('size: M', 'size: M,', $value);

        return $value;
    }
}

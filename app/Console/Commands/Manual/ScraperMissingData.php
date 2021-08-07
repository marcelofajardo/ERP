<?php

namespace App\Console\Commands\Manual;

use App\CronJobReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ScraperMissingData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:missing-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all products from log_scraper which are missing data';

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
        /*try {*/
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);
            // Get one product per supplier
            $sql = "
            SELECT
                MAX(id),
                website,
                url,
                sku,
                brand_id,
                category,
                title,
                description,
                properties,
                images,
                size_system,
                currency,
                price,
                discounted_price,
                is_sale
            FROM
                scraped_products
            WHERE
                validated=1 AND
                description LIKE '%shoe%'
            GROUP BY
                website
        ";
            $results = DB::select(DB::raw($sql));

            if ($results !== null) {
                echo "website;SKU;URL;Brand;Gender;Category;Title;Description;Color;Sizes;Dimension;Images;Size System;Currency;Price;Discounted Price;Is Sale\n";
                foreach ($results as $result) {
                    // Get properties
                    $properties = !empty($result->properties) ? unserialize($result->properties) : [];

                    echo '"' . $result->website . '";' .
                    '"' . $result->sku . '";' .
                    '"' . $result->url . '";' .
                    '"' . $result->brand_id . '";' .
                    '"' . (isset($properties['gender']) ? $properties['gender'] : '') . '";' .
                    '"' . (!empty($properties['category']) && is_array($properties['category']) ? implode(',', $properties['category']) : '') . '";' .
                    '"' . $result->title . '";' .
                    '"' . str_replace('"', "'", $result->description) . '";' .
                    '"' . (!empty($properties['color']) ? $properties['color'] : '') . '";' .
                    '"' . (!empty($properties['sizes']) && is_array($properties['sizes']) ? implode('.', $properties['sizes']) : '') . '";' .
                    '"' . (!empty($properties['dimension']) && is_array($properties['dimension']) ? implode(',', $properties['dimension']) : '') . '";' .
                    '"' . (!empty($properties['images']) && is_array($properties['images']) ? implode(',', $properties['images']) : '') . '";' .
                    '"' . $result->size_system . '";' .
                    '"' . $result->currency . '";' .
                    $result->price . ';' .
                    $result->discounted_price . ';' .
                    $result->is_sale . ';' .
                        "\n";
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        /*} catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }*/
    }
}

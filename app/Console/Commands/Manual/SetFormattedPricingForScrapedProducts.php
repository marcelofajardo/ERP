<?php

namespace App\Console\Commands\Manual;

use App\CronJobReport;
use App\ScrapedProducts;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetFormattedPricingForScrapedProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrapedProducts:setFormattedPricing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the formatted pricing for all scraped products where the formatted pricing is not set.';

    // Today's euro values (16-08-2019)
    protected $euroInCny = 7.7978;
    protected $euroInGbp = 0.91033;
    protected $euroInUsd = 1.1076;

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
            // Get all scraped products without formatted pricing
            ScrapedProducts::chunk(100, function ($scrapedProducts) {
                //ScrapedProducts::whereNotNull( 'price_eur' )->chunk( 100, function ( $scrapedProducts ) {
                foreach ($scrapedProducts as $scrapedProduct) {
                    // Check for price
                    $currency = $this->_getCurrencyFromPrice($scrapedProduct->price);

                    if (!empty($currency) && !empty($scrapedProduct->price)) {
                        // Update scraped product
                        if ($currency == 'EUR') {
                            $scrapedProduct->price_eur = $this->_getFormattedPrice($scrapedProduct->price);
                            $scrapedProduct->save();
                        } else {
                            // Set multiplier
                            $multiplier                = 'euroIn' . ucfirst(strtolower($currency));
                            $scrapedProduct->price_eur = round($this->$multiplier * $this->_getFormattedPrice($scrapedProduct->price), 2);
                            $scrapedProduct->save();
                        }
                    } else {
                        dump("Unable to detect currency and/or price: " . $scrapedProduct->price);
                        $scrapedProduct->price_eur = 0;
                        $scrapedProduct->save();

                    }
                }
            });

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    private function _getCurrencyFromPrice($price)
    {
        // Check for long strings
        if (strlen($price) > 20 && strstr($price, '%')) {
            $price = trim(substr($price, 0, 20));
        }

        // Check for wrong prices
        if (empty(preg_replace('#[^0-9\.,]#', '', $price))) {
            return false;
        }

        if (stristr($price, '%')) {
            return false;
        }

        // Check for CNY
        if (stristr($price, 'CN¥2')) {
            return 'CNY';
        }

        // Check for EUR
        if (stristr($price, '&euro;')) {
            return 'EUR';
        }

        if (stristr($price, '€')) {
            return 'EUR';
        }

        if (stristr($price, 'EUR')) {
            return 'EUR';
        }

        // Check for GBP
        if (stristr($price, '£')) {
            return 'GBP';
        }

        // Check for USD
        if (stristr($price, '$')) {
            return 'USD';
        }

        // Return NULL by default
        return 'EUR';
    }

    private function _getFormattedPrice($price)
    {
        // Remove all characters except dots and commas
        $price = preg_replace('#[^0-9\.,]#', '', $price);

        // Check for prices with comma and dot
        if (stristr($price, ',') && stristr($price, ',')) {
            // Check for dot as thousand separator
            if (strpos($price, '.') < strpos($price, ',')) {
                $price = str_replace('.', '', $price);
                $price = str_replace(',', '.', $price);
            } else {
                $price = str_replace(',', '', $price);
            }

            // Return the price
            return trim($price);
        }

        // Two dots
        if (substr_count($price, '.') > 1) {
            // Take price until second dot
            $price = substr($price, 0, strpos($price, '.', 2));

            // Remove dot
            $price = str_replace('.', '', $price);
        }

        // One dot, but as thousands separator
        if (substr_count($price, '.') > 0 && (int) $price < 2) {
            $price = str_replace('.', '', $price);
        }

        // Return NULL by default
        return round(trim($price));
    }
}

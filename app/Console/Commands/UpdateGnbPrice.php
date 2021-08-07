<?php

namespace App\Console\Commands;

use App\Brand;
use App\CronJobReport;
use App\Product;
use App\ScrapedProducts;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateGnbPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:gnb-price';

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

            // $products = ScrapedProducts::where('has_sku', 1)->where('website', 'G&B')->get();
            // $products = ScrapedProducts::where('updated_at', '>', '2019-06-05 00:00')->get();
            // $products = ScrapedProducts::where('sku', '182400abs000058025pi')->get();
            $products = ScrapedProducts::where('website', 'DoubleF')->get();

            // dd(count($products));
            foreach ($products as $key => $product) {
                dump("$key - Scraped Product - $product->sku");

                if ($old_product = Product::where('sku', $product->sku)->first()) {
                    dump("$key - Product Found");

                    $brand = Brand::find($product->brand_id);

                    if ($brand) {
                        if (strpos($product->price, ',') !== false) {
                            dump("$key - comma found");

                            if (strpos($product->price, '.') !== false) {
                                dump("$key - dot found");

                                if (strpos($product->price, ',') < strpos($product->price, '.')) {
                                    dump("$key - comma first than dot");

                                    $final_price = str_replace(',', '', $product->price);
                                } else {
                                    // $final_price = $product->price;
                                    $final_price = str_replace(',', '|', $product->price);
                                    $final_price = str_replace('.', ',', $final_price);
                                    $final_price = str_replace('|', '.', $final_price);
                                    $final_price = str_replace(',', '', $final_price);
                                }
                            } else {
                                dump("$key - no dot found");
                                $final_price = str_replace(',', '.', $product->price);
                            }
                        } else {
                            dump("$key - no changes");
                            $final_price = $product->price;
                        }

                        $final_price = trim(preg_replace('/[^0-9\.]/i', '', $final_price));

                        if (strpos($final_price, '.') !== false) {
                            dump($final_price);

                            $exploded = explode('.', $final_price);
                            dump(json_encode($exploded));

                            dump(strlen($exploded[1]));
                            if (strlen($exploded[1]) > 2) {
                                if (count($exploded) > 2) {
                                    $sliced = array_slice($exploded, 0, 2);
                                } else {
                                    $sliced = $exploded;
                                }

                                dump("$key - has more than 2 digits after dot");
                                $final_price = implode('', $sliced);
                            }
                        }

                        // dd($final_price);

                        $price = round($final_price);

                        dump("FINAL PRICE - $price");

                        $old_product->price = $price;

                        if (!empty($brand->euro_to_inr)) {
                            $old_product->price_inr = $brand->euro_to_inr * $old_product->price;
                        } else {
                            $old_product->price_inr = Setting::get('euro_to_inr') * $old_product->price;
                        }

                        $old_product->price_inr         = round($old_product->price_inr, -3);
                        $old_product->price_inr_special = $old_product->price_inr - ($old_product->price_inr * $brand->deduction_percentage) / 100;

                        $old_product->price_inr_special = round($old_product->price_inr_special, -3);

                        $old_product->save();
                    } else {
                        dump("NO BRAND");
                    }
                }
            }
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}

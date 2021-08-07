<?php

namespace App\Console\Commands;

use App\Brand;
use App\Category;
use App\CronJobReport;
use App\Product;
use App\ScrapedProducts;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateDoubleProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:double-products';

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

            $products = ScrapedProducts::where('has_sku', 1)->where('website', 'DoubleF')->get();

            foreach ($products as $product) {
                if ($old_product = Product::where('sku', $product->sku)->first()) {
                    $old_product->sku               = str_replace(' ', '', $product->sku);
                    $old_product->brand             = $product->brand_id;
                    $old_product->supplier          = 'Double F';
                    $old_product->name              = $product->title;
                    $old_product->short_description = $product->description;
                    $old_product->supplier_link     = $product->url;
                    $old_product->stage             = 3;

                    $properties_array = $product->properties ?? [];

                    if (array_key_exists('Composition', $properties_array)) {
                        $old_product->composition = (string) $properties_array['Composition'];
                    }

                    if (array_key_exists('Color code', $properties_array)) {
                        $old_product->color = $properties_array['Color code'];
                    }

                    if (array_key_exists('Made In', $properties_array)) {
                        $old_product->made_in = $properties_array['Made In'];
                    }

                    if (array_key_exists('category', $properties_array)) {
                        $categories  = Category::all();
                        $category_id = 1;

                        foreach ($properties_array['category'] as $cat) {
                            if ($cat == 'WOMAN') {
                                $cat = 'WOMEN';
                            }

                            foreach ($categories as $category) {
                                if (strtoupper($category->title) == $cat) {
                                    $category_id = $category->id;
                                }
                            }
                        }

                        $old_product->category = $category_id;
                    }

                    $brand = Brand::find($product->brand_id);

                    if (strpos($product->price, ',') !== false) {
                        if (strpos($product->price, '.') !== false) {
                            if (strpos($product->price, ',') < strpos($product->price, '.')) {
                                $final_price = str_replace(',', '', $product->price);
                            }
                        } else {
                            $final_price = str_replace(',', '.', $product->price);
                        }
                    } else {
                        $final_price = $product->price;
                    }

                    $price = round(preg_replace('/[\€,]/', '', $final_price));

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

                    // $images = $product->images;
                    // $old_product->detachMediaTags(config('constants.media_tags'));
                    //
                    // foreach ($images as $image_name) {
                    //   $path = public_path('uploads') . '/social-media/' . $image_name;
                    //   $media = MediaUploader::fromSource($path)->upload();
                    //   $old_product->attachMedia($media,config('constants.media_tags'));
                    // }
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}

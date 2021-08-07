<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateMagentoProductStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:magento-product-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Magento Product status on ERP';

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

            $options = array(
                'trace'              => true,
                'connection_timeout' => 120,
                'wsdl_cache'         => WSDL_CACHE_NONE,
            );

            $proxy     = new \SoapClient(config('magentoapi.url'), $options);
            $sessionId = $proxy->login(config('magentoapi.user'), config('magentoapi.password'));

            // $magento_products = $proxy->catalogProductList($sessionId);

            // $products = Product::skip(909)->take(18435)->get();
            $products = Product::all();

            // $product = Product::where('sku', 'RR3MJ00GNXU0N0')->first();
            // $key = 0;

            foreach ($products as $key => $product) {
                // $product = Product::where('sku', 'RW0B0312VIT0RO')->first();

                $error_message        = '';
                $second_error_message = '';
                $sku                  = $product->sku . $product->color;

                try {
                    $magento_product = json_decode(json_encode($proxy->catalogProductInfo($sessionId, $sku)), true);
                } catch (\Exception $e) {
                    $error_message = $e->getMessage();
                }

                // CONFIGURABLE PRODUCT DOESNT EXIST
                if ($error_message == 'Product not exists.') {
                    $product->isUploaded = 0;
                    $product->isFinal    = 0;

                    dump("$key Product Doesnt Exist - $product->sku - status ($product->isUploaded)");

                    // CHECKS FOR SKU WITHOUT COLOR
                    try {
                        $without_color_product = json_decode(json_encode($proxy->catalogProductInfo($sessionId, $product->sku)), true);
                    } catch (\Exception $e) {
                        $second_error_message = $e->getMessage();
                        dump("$key Product Without Color Doesnt Exists");
                    }

                    if ($second_error_message != 'Product not exists.') {
                        $product->isUploaded = 1;
                        dump("$key Product Without Color Exists - status($product->isUploaded)");

                        $status = $without_color_product['status'];
                        // 1 = Enabled, 2 = Disabled

                        if ($status == 2) {
                            $product->isFinal = 0;

                            dump("$key- Not Enabled Without Color Product");
                        } else {
                            $product->isFinal = 1;

                            dump("$key- Enabled Without Color Product");
                        }
                    }

                    // END OF NO CONFIGURABLE PRODUCT
                } else {
                    // THERE IS A CONFIGURABLE PRODUCT

                    $product->isUploaded = 1;
                    dump("$key CONFIGURABLE PRODUCT - $product->sku - status($product->isUploaded)");

                    if (!empty($product->size)) {
                        // THERE ARE SIZES

                        $associated_skus = [];
                        $new_variations  = 0;
                        $sizes_array     = explode(',', $product->size);
                        $categories      = CategoryController::getCategoryTreeMagentoIds($product->category);

                        foreach ($sizes_array as $key2 => $size) {
                            $error_message = '';

                            try {
                                $simple_product = json_decode(json_encode($proxy->catalogProductInfo($sessionId, $sku . '-' . $size)), true);
                            } catch (\Exception $e) {
                                $error_message = $e->getMessage();
                            }

                            if ($error_message == 'Product not exists.') {
                                // NO SIMPLE PRODUCT FOUND
                                dump("$key-$key2 Simple Product Doesnt Exist");

                                // CREATE VARIATION

                                $productData = array(
                                    'categories'            => $categories,
                                    'name'                  => $product->name,
                                    'description'           => '<p></p>',
                                    'short_description'     => $product->short_description,
                                    'website_ids'           => array(1),
                                    // Id or code of website
                                    'status'                => $magento_product['status'],
                                    // 1 = Enabled, 2 = Disabled
                                    'visibility'            => 1,
                                    // 1 = Not visible, 2 = Catalog, 3 = Search, 4 = Catalog/Search
                                    'tax_class_id'          => 2,
                                    // Default VAT
                                    'weight'                => 0,
                                    'stock_data'            => array(
                                        'use_config_manage_stock' => 1,
                                        'manage_stock'            => 1,
                                    ),
                                    'price'                 => $product->price_eur_special,
                                    // Same price than configurable product, no price change
                                    'special_price'         => $product->price_eur_discounted,
                                    'additional_attributes' => array(
                                        'single_data' => array(
                                            array('key' => 'msrp', 'value' => $product->price),
                                            array('key' => 'composition', 'value' => $product->composition),
                                            array('key' => 'color', 'value' => $product->color),
                                            array('key' => 'sizes', 'value' => $size),
                                            array('key' => 'country_of_manufacture', 'value' => $product->made_in),
                                            array('key' => 'brands', 'value' => BrandController::getBrandName($product->brand)),
                                        ),
                                    ),
                                );
                                // Creation of product simple
                                $result         = $proxy->catalogProductCreate($sessionId, 'simple', 14, $sku . '-' . $size, $productData);
                                $new_variations = 1;

                            } else {
                                // SIMPLE PRODUCT EXISTS

                                $status = $simple_product['status'];
                                // 1 = Enabled, 2 = Disabled

                                if ($status == 2) {
                                    // $product->isFinal = 0;

                                    dump("$key-$key2 Not Enabled Simple Product");
                                } else {
                                    // $product->isFinal = 1;

                                    dump("$key-$key2 Enabled Simple Product");
                                }
                            }

                            $associated_skus[] = $sku . '-' . $size;
                        }

                        if ($new_variations == 1) {
                            // IF THERE WAS NEW VARIATION CREATED, UPDATED THE MAIN PRODUCT
                            /**
                             * Configurable product
                             */
                            $productData = array(
                                'associated_skus' => $associated_skus,
                            );
                            // Creation of configurable product
                            $result = $proxy->catalogProductUpdate($sessionId, $sku, $productData);
                        }
                    }
                    $status = $magento_product['status'];
                    // 1 = Enabled, 2 = Disabled

                    if ($status == 2) {
                        $product->isFinal = 0;

                        dump("$key Not Enabled");
                    } else {
                        $product->isFinal = 1;

                        dump("$key Enabled");
                    }
                }

                $product->save();

                // dd('stap');
            }

            // foreach ($products as $key => $product) {
            //   $error_message = '';
            //
            //   try {
            //     $magento_product = json_decode(json_encode($proxy->catalogProductInfo($sessionId, $product->sku)), true);
            //   } catch (\Exception $e) {
            //     $error_message = $e->getMessage();
            //   }
            //
            //   if ($error_message == 'Product not exists.') {
            //     $product->isUploaded = 0;
            //     $product->isFinal = 0;
            //
            //     dump("$key Does not Exist");
            //   } else {
            //     $product->isUploaded = 1;
            //
            //     $visibility = $magento_product['visibility'];
            //     // 1 = Not visible, 2 = Catalog, 3 = Search, 4 = Catalog/Search
            //
            //     if ($visibility == 1) {
            //       $product->isFinal = 0;
            //
            //       dump("$key Not Visible");
            //     } elseif ($visibility == 2 || $visibility == 3 || $visibility == 4) {
            //       $product->isFinal = 1;
            //
            //       dump("$key Visible");
            //     }
            //   }
            //
            //   $product->save();
            // }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}

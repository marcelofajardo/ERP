<?php

namespace App\Console\Commands;

use App\LandingPageProduct;
use App\Library\Shopify\Client as ShopifyClient;
use Illuminate\Console\Command;

class CheckLandingProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:landing-page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate landing products from shopify after end time';

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
        $client          = new ShopifyClient();
        $landingProducts = LandingPageProduct::whereRaw('timestamp(end_date) < NOW()')->orWhere("status",0)->get();
        foreach ($landingProducts as $product) {
            $productData = [
                'product' => [
                    'published'       => false,
                    'published_scope' => false,
                ],
            ];
            if ($product->shopify_id) {
                $response = $client->updateProduct($product->shopify_id, $productData, $product->store_website_id);
            }
        }

        $landingProducts = LandingPageProduct::whereRaw('timestamp(start_date) < NOW() AND timestamp(end_date) > NOW()')->get();
        foreach ($landingProducts as $landingPage) {
            // Set data for Shopify
            $landingPageProduct = $landingPage->product;

            $productData = $landingPage->getShopifyPushData();
            if ($productData == false) {
                continue;
            }

            if ($landingPage->shopify_id) {
                $response = $client->updateProduct($landingPage->shopify_id, $productData, $landingPage->store_website_id);
            } else {
                $response = $client->addProduct($productData, $landingPage->store_website_id);
            }
        }

    }
}

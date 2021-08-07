<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MoveAllShippingDataToStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move-all-shipping:push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move all shipping to push store website';

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
        //
        $shipping = \App\StoreWebsitesCountryShipping::all();
        if (!$shipping->isEmpty()) {
            foreach ($shipping as $s) {
                $storeWebsite = $s->storeWebsiteDetails;
                if ($storeWebsite) {

                    $url     = $storeWebsite->magento_url . '/default/rest/all/V1/shippingcost/';
                    $api_key = $storeWebsite->api_token;

                    $headers = [
                        'Authorization' => 'Bearer ' . $api_key,
                        'Content-Type'  => 'application/json',
                    ];

                    $pushMagentoArr = array(
                        'shippingCountryCode' => $s->country_code,
                        'shippingCountryName' => $s->country_name,
                        'shippingPrice'       => $s->price,
                        'shippingCurrency'    => $s->currency,
                    );

                    if ($s->ship_id) {
                        $url .= 'update';
                        $pushMagentoArr['ship_id']              = $s->ship_id;
                        $pushMagentoArr['updatedShippingPrice'] = $s->price;
                        $response                               = \App\Helpers\GuzzleHelper::post($url, $pushMagentoArr, $headers);
                        if (isset($response[0]->status)) {
                            echo "{$s->country_name} updated for {$storeWebsite->website} Success";
                        } else {
                            echo "{$s->country_name} updated for {$storeWebsite->website} Failed";
                        }
                    } else {
                        $url .= 'add';
                        $response = \App\Helpers\GuzzleHelper::post($url, $pushMagentoArr, $headers);
                        if (isset($response[0]->status)) {
                            $s->ship_id = $response[0]->ship_id;
                            $s->save();
                            echo "{$s->country_name} added for {$storeWebsite->website} Success";
                        } else {
                            echo "{$s->country_name} added for {$storeWebsite->website} Failed";
                        }
                    }
                }
            }
        }
    }
}

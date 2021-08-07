<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InsertStoreWebsiteShipping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store-website-shipping:insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert store website shipping';

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
        $simplycountry = \App\SimplyDutyCountry::all();
        $storeWebsites = \App\StoreWebsite::where("api_token","!=","")->where("website_source","magento")->get();

        if(!$storeWebsites->isEmpty()) {
            foreach($storeWebsites as $sW) {
                foreach($simplycountry as $sc) {
                    \App\StoreWebsitesCountryShipping::updateOrCreate(
                        ['country_code' => $sc->country_code, 'store_website_id' => $sW->id],
                        ['country_name' => $sc->country_name, 'price' => "25", 'currency' => 'EUR']
                    );
                }
            }
        }

    }
}

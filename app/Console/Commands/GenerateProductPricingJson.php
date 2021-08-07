<?php

namespace App\Console\Commands;

use App\Helpers\StatusHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateProductPricingJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:product-pricing-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate product pricing json';

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
        //try {
        $report = \App\CronJobReport::create([
            'signature'  => $this->signature,
            'start_time' => Carbon::now(),
        ]);

        $storeWebsite  = \App\StoreWebsite::where("is_published", 1)->get();
        $countryGroups = \App\CountryGroup::all();
        $dutyCountries = \App\CountryDuty::groupBy('destination')->get()->pluck('destination');

        // start pricing
        $products    = \App\Product::where("status_id", StatusHelper::$finalApproval)->get();
        $priceReturn = [];
        if (!$products->isEmpty()) {
            foreach ($products as $product) {
                foreach ($storeWebsite as $website) {
                    foreach ($countryGroups as $cg) {
                        $price = $product->getPrice($website->id, $cg->id);
                        foreach ($cg->groupItems as $item) {
                            $priceReturn[$website->website][$product->sku][$item->country_code]['price']          = $price;
                            $dutyPrice                                                                            = $product->getDuty($item->country_code);
                            $priceReturn[$website->website][$product->sku][$item->country_code]['price']['duty']  = $dutyPrice;
                            $priceReturn[$website->website][$product->sku][$item->country_code]['price']['total'] = (float) $price['total'] + $dutyPrice;
                        }
                    }
                }
            }
        }

        if (!\Storage::disk('uploads')->put("pricing-" . date("Y-m-d") . ".json", json_encode($priceReturn))) {
            return false;
        }

        $report->update(['end_time' => Carbon::now()]);

        /*} catch (\Exception $e) {
    \App\CronJob::insertLastError($this->signature, $e->getMessage());
    }*/
    }
}

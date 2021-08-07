<?php

namespace App\Console\Commands;

use App\Brand;
use App\BrandCategoryPriceRange;
use App\CronJobReport;
use App\ListingHistory;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoRejectProductIfAttributesAreMissing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:reject-if-attribute-is-missing';

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
        return true;
        /*try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            // Get all products with missing details
            $products = Product::where([['is_farfetched', 0], ['is_listing_rejected', 0], ['is_listing_rejected_automatically', 0]])->where(function ($query) {
                $query->where('name', '=', '')
                    ->orWhere('short_description', '=', '')
                    ->orWhere('composition', '=', '')
                    ->orWhere('size', '=', '');
            })->get();

            // Loop over products
            if ($products->count() > 0) {
                foreach ($products as $product) {
                    // Set to auto rejected
                    $product->is_listing_rejected               = 1;
                    $product->is_listing_rejected_automatically = 1;
                    $product->save();

                    // Update listing history
                    $listingHistory             = new ListingHistory();
                    $listingHistory->user_id    = null;
                    $listingHistory->product_id = $product->id;
                    $listingHistory->action     = 'AUTO_REJECTED_ATTRIBUTE_MISSING';
                    $listingHistory->content    = ['action' => 'AUTO_REJECTED_ATTRIBUTE_MISSING'];
                    $listingHistory->save();
                }
            }

            // Auto reject by price
            $brands = Brand::where('brand_segment', '!=', '')->get();

            // Build array with brands
            $arrBrands = [];
            foreach ($brands as $brand) {
                $arrBrands[] = $brand->id;
            }

            // Build array with brand segments
            $arrBrandSegments = [];
            foreach ($brands as $brand) {
                $arrBrandSegments[$brand->id] = $brand->brand_segment;
            }

            // Get all brand category pricing ranges
            $brandCategoryPriceRange = BrandCategoryPriceRange::all();

            // Build array with brand segments and categories
            $arrBrandCategoryPriceRange = [];
            foreach ($brandCategoryPriceRange as $item) {
                if ($item->min_price > 0) {
                    $arrBrandCategoryPriceRange[$item->brand_segment][$item->category_id]['min'] = $item->min_price;
                }

                if ($item->max_price > 0) {
                    $arrBrandCategoryPriceRange[$item->brand_segment][$item->category_id]['max'] = $item->max_price;
                }
            }

            // Get all products with brands
            $products = Product::whereIn('brand', $arrBrands)->get();

            // Loop over products
            foreach ($products as $product) {
                if (isset($product->brand) && isset($arrBrandSegments[$product->brand]) && isset($arrBrandCategoryPriceRange[$arrBrandSegments[$product->brand]]) && isset($arrBrandCategoryPriceRange[$arrBrandSegments[$product->brand]][$product->category])) {
                    // Minimum price
                    if (isset($arrBrandCategoryPriceRange[$arrBrandSegments[$product->brand]][$product->category]['min']) && $product->price < $arrBrandCategoryPriceRange[$arrBrandSegments[$product->brand]][$product->category]['min']) {
                        // Set to auto rejected
                        $product->is_listing_rejected               = 1;
                        $product->is_listing_rejected_automatically = 1;
                        $product->save();

                        // Update listing history
                        $listingHistory             = new ListingHistory();
                        $listingHistory->user_id    = null;
                        $listingHistory->product_id = $product->id;
                        $listingHistory->action     = 'AUTO_REJECTED_MINIMUM_PRICE';
                        $listingHistory->content    = ['action' => 'AUTO_REJECTED_MINIMUM_PRICE', 'price' => $product->price, 'minimum_price' => $arrBrandCategoryPriceRange[$arrBrandSegments[$product->brand]][$product->category]['min']];
                        $listingHistory->save();
                    }

                    // Maximum price
                    if (isset($arrBrandCategoryPriceRange[$arrBrandSegments[$product->brand]][$product->category]['max']) && $product->price > $arrBrandCategoryPriceRange[$arrBrandSegments[$product->brand]][$product->category]['max']) {
                        // Set to auto rejected
                        $product->is_listing_rejected               = 1;
                        $product->is_listing_rejected_automatically = 1;
                        $product->save();

                        // Update listing history
                        $listingHistory             = new ListingHistory();
                        $listingHistory->user_id    = null;
                        $listingHistory->product_id = $product->id;
                        $listingHistory->action     = 'AUTO_REJECTED_MAXIMUM_PRICE';
                        $listingHistory->content    = ['action' => 'AUTO_REJECTED_MAXIMUM_PRICE', 'price' => $product->price, 'maximum_price' => $arrBrandCategoryPriceRange[$arrBrandSegments[$product->brand]][$product->category]['max']];
                        $listingHistory->save();
                    }
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }*/
    }
}

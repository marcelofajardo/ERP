<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\ListingHistory;
use App\Product;
use App\Services\Listing\Main;
use App\Services\Listing\Scrapper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoApproveProductAttribute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'approve:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var Main $listing
     */
    private $listing;
    private $scrapper;

    /**
     * Create a new command instance.
     *
     * @param Main $listing
     * @param Scrapper $scrapper
     */
    public function __construct(Main $listing, Scrapper $scrapper)
    {
        parent::__construct();
        $this->listing  = $listing;
        $this->scrapper = $scrapper;
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

            $count = 0;
//        $cats = (Category::where('parent_id',5)->orWhere('parent_id', 41)->pluck('id')->toArray());
            $products = Product::where('is_approved', 0)
                ->where('is_listing_rejected', 1)
                ->where('is_crop_approved', 1)
//            ->whereIn('category', [
            //                $cats
            //            ])
                ->where('is_crop_ordered', 1)
//            ->where('composition', '!=', '')
            //            ->where('color', '!=', '')
            //            ->whereNotNull('composition')
            //            ->where(function ($q) {
            //                $q->where('size', '!=', '')
            //                    ->orWhere(function ($qq) {
            //                        $qq->where('lmeasurement', '!=', '')
            //                           ->where('hmeasurement', '!=', '')
            //                           ->where('dmeasurement', '!=', '');
            //                    })
            //                ;
            //            })
                ->inRandomOrder()
                ->get();

            foreach ($products as $product) {

                $this->scrapper->getFromFarfetch($product);

                dump('Farfetched...');

                $status = $this->listing->validate($product);

                if (!$status) {
                    $this->error($product->id);
                    $product->is_auto_processing_failed = 1;
                    $product->save();
                    continue;
                }

                $count++;

                $this->info('Approved....' . $count);

                $listingHistory             = new ListingHistory();
                $listingHistory->user_id    = 109;
                $listingHistory->product_id = $product->id;
                $listingHistory->action     = 'LISTING_APPROVAL';
                $listingHistory->content    = ['action' => 'LISTING_APPROVAL', 'message' => 'Listing approved by ERP!'];
                $listingHistory->save();

                $product->is_approved         = 1;
                $product->is_listing_rejected = 0;
                $product->approved_by         = 109;
                $product->listing_approved_at = Carbon::now()->toDateTimeString();
                $product->save();

            }
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}

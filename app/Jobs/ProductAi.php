<?php

namespace App\Jobs;

use App\Helpers\StatusHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\LogScraperVsAi;
use seo2websites\GoogleVision\GoogleVisionHelper;

class ProductAi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $_product;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product)
    {
        // Set product
        $this->_product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Set time limit
        set_time_limit(0);

        // Set memory limit
        ini_set('memory_limit','1024M');

        // Load product
        $product = $this->_product;

        // ------ 2020-FEB-05 SKIP ALL GOOGLE AI, IMMEDIATELY TO CROP

        // Update product status to auto crop
        $product->status_id = StatusHelper::$autoCrop;
        $product->save();

        // Log info
        Log::channel('productUpdates')->info("[Queued job result] Successfully handled AI");

        // Log alert if there is no product
        if ($product == null || !isset($product->id)) {
            // Log alert
            Log::channel('productUpdates')->alert("[Queued job result] Failed to handle AI - product is not set");

            // Return
            return;
        }

        // Get array product images
        $arrMedia = $product->getMedia(config('constants.media_tags'));

        // Set empty array for image URLs
        $arrImages = [];

        // Loop over media to get URLs
        foreach ($arrMedia as $media) {
            $arrImages[] = $media->getUrl();//'https://erp.theluxuryunlimited.com/' . $media->disk . '/' . $media->filename . '.' . $media->extension;
        }

        // Log alert if there are no images
        if (count($arrImages) == 0) {
            // Log alert
            Log::channel('productUpdates')->alert("[Queued job result] Failed to handle AI - images are not set for product ID " . $product->id);

            // Return
            return;
        }

        // Set json with original data
        $resultScraper = [
            'category' => (int) $product->category > 0 ? $product->product_category->title : '',
            'color' => $product->color,
            'composite' => $product->composition,
            'gender' => ''
        ];

        // Run AI
        $resultAI = GoogleVisionHelper::getPropertiesFromImageSet($arrImages);

        // Log result
        $logScraperVsAi = new LogScraperVsAi();
        $logScraperVsAi->product_id = $product->id;
        $logScraperVsAi->ai_name = 'Google Vision';
        $logScraperVsAi->media_input = json_encode($arrImages);
        $logScraperVsAi->result_scraper = json_encode($resultScraper);
        $logScraperVsAi->result_ai = json_encode($resultAI);
        $logScraperVsAi->save();

        // Update product color if not set
        if ( empty($product->color) ) {
            $product->color = $resultAI->color;
        }

        // Update product status to auto crop
        $product->status_id = StatusHelper::$autoCrop;
        $product->save();

        // Log info
        Log::channel('productUpdates')->info("[Queued job result] Successfully handled AI");
    }
}
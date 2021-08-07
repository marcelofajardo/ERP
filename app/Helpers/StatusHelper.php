<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProductAi;

class StatusHelper extends Model
{
    public static $import = 1;
    public static $scrape = 2;
    public static $AI = 3;
    public static $autoCrop = 4;
    public static $cropApproval = 5;
    public static $cropSequencing = 6;
    public static $imageEnhancement = 7;
    public static $cropApprovalConfirmation = 8;
    public static $finalApproval = 9;
    public static $manualAttribute = 10;
    public static $pushToMagento = 11;
    public static $inMagento = 12;
    public static $unableToScrape = 13;
    public static $unableToScrapeImages = 14;
    public static $isBeingCropped = 15;
    public static $cropSkipped = 16;
    public static $isBeingEnhanced = 17;
    public static $cropRejected = 18;
    public static $isBeingSequenced = 19;
    public static $isBeingScraped = 20;
    public static $manualCropping = 21;
    public static $manualImageUpload = 22;
    public static $queuedForGoogleImageSearch = 23;
    public static $googleImageSearchFailed = 24;
    public static $isBeingScrapedWithGoogleImageSearch = 25;
    public static $pendingVerificationGoogleImageSearch = 26;
    public static $googleImageSearchManuallyRejected = 27;
    public static $queuedForGoogleTextSearch = 28;
    public static $googleTextSearchFailed = 29;
    public static $isBeingScrapedWithGoogleTextSearch = 30;
    public static $pendingVerificationGoogleTextSearch = 31;
    public static $googleTextSearchManuallyRejected = 32;
    public static $attributeRejectCategory = 33;
    public static $submitForApproval = 34;
    public static $requestForExternalScraper = 35;
    public static $unknownCategory = 36;
    public static $unknownColor = 37;
    public static $unknownSize = 38;
    public static $unknownComposition = 39;
    public static $unknownMeasurement = 40;
    public static $priceCheck = 41;
    public static $autoReject = 42;
    public static $unknownTitle = 43;
    public static $unknownDescription = 44;
    public static $unknownPrice = 45;
    public static $sendtoExternalScraper = 46;
    public static $externalScraperFinished = 47;
    public static $sizeVerifyCron = 48;

    public static function getStatus()
    {
        return [
            1 => 'import',
            2 => 'scrape',
            3 => 'ai',
            4 => 'auto crop',
            5 => 'crop approval',
            6 => 'crop sequencing',
            7 => 'image enhancement',
            8 => 'crop approval confirmation',
            9 => 'final approval',
            10 => 'manual attribute',
            11 => 'push to magento',
            12 => 'in magento',
            13 => 'unable to scrape',
            14 => 'unable to scrape images',
            15 => 'is being cropped',
            16 => 'crop skipped',
            17 => 'is being enhanced',
            18 => 'crop rejected',
            19 => 'is being sequenced',
            20 => 'is being scraped',
            21 => 'manual cropping',
            22 => 'manual image upload',
            23 => 'queued for google image search',
            24 => 'google image search failed',
            25 => 'is being scraped with google image search',
            26 => 'pending verification google image search',
            27 => 'manually rejected google image search',
            28 => 'queued for google text search',
            29 => 'google text search failed',
            30 => 'is being scraped with google text search',
            31 => 'pending verification google text search',
            32 => 'manually rejected google text search',
            33 => 'pending products without category',
            34 => 'Submit For approval',
            35 => 'Request For external Scraper',
            36 => 'Unknown Category',
            37 => 'Unknown Color',
            38 => 'Unknown Size',
            39 => 'Unknown Composition',
            40 => 'Unknown Measurement',
            41 => 'Price Check',
            42 => 'Auto rejected',
            43 => 'Unknown Title',
            44 => 'Unknown Description',
            45 => 'Unknown Price',
            46 => 'Send to External Scraper',
            47 => 'External Scraper Finished',
            48 => 'Size Verify Cron'
        ];
    }

    public static function subStatus()
    {
        $status = self::getStatus();
        $r = [];
        foreach($status as $k => $s) {
            if(in_array($k, [36,37,38,39,40,43,44,45])) {
                $r[$k] = $s;
            }
        }
        return $r;
    }

    public static function updateStatus(\App\Product $product, $newStatus = 0)
    {
        // Update status to AI
        if ($newStatus == self::$AI) {
            // Queue for AI
            ProductAi::dispatch($product)->onQueue('product');
        }

        // Set status and save product
        $product->status_id = $newStatus;
        $product->save();

        // Return
        return;
    }

    public static function getStatusCount($inStockOnly = 1)
    {
        // Get summary
        $productStats = DB::table('products')
            ->select('status_id', DB::raw('COUNT(id) as total'))
            ->where('stock', '>=', $inStockOnly)
            ->groupBy('status_id')
            ->pluck('total', 'status_id')->all();

        // Return array with stats
        return $productStats;
    }

    public static function getStatusCountByDateRange($startDate = '1900-01-01', $endDate = '2100-01-01', $inStockOnly = 1)
    {
        // Get summary
        $productStats = DB::table('products')
            ->select('status_id', DB::raw('COUNT(id) as total'))
            ->where('stock', '>=', $inStockOnly)
            ->whereBetween('created_at', [$startDate . ' 00:00', $endDate . ' 23:59'])
            ->groupBy('status_id')
            ->pluck('total', 'status_id')->all();

        // Return array with stats
        return $productStats;
    }

    public static function getCroppedCount($inStockOnly = 1)
    {
        // Get status
        $status = self::getStatusCount($inStockOnly);

        // Return count for all statused beyond crop
        return array_sum($status) -
            (isset($status[ self::$import ]) ? $status[ self::$import ] : 0) -
            (isset($status[ self::$scrape ]) ? $status[ self::$scrape ] : 0) -
            (isset($status[ self::$AI ]) ? $status[ self::$AI ] : 0) -
            (isset($status[ self::$autoCrop ]) ? $status[ self::$autoCrop ] : 0) -
            (isset($status[ self::$cropRejected ]) ? $status[ self::$cropRejected ] : 0) -
            (isset($status[ self::$cropSkipped ]) ? $status[ self::$cropSkipped ] : 0) -
            (isset($status[ self::$unableToScrape ]) ? $status[ self::$unableToScrape ] : 0) -
            (isset($status[ self::$unableToScrapeImages ]) ? $status[ self::$unableToScrapeImages ] : 0);
    }

    public static function getCropApprovedCount($inStockOnly = 1)
    {
        // Get status
        $status = self::getStatusCount($inStockOnly);

        // Return count
        return (isset($status[ self::$cropSequencing ]) ? $status[ self::$cropSequencing ] : 0) +
            (isset($status[ self::$cropApprovalConfirmation ]) ? $status[ self::$cropApprovalConfirmation ] : 0) +
            (isset($status[ self::$isBeingSequenced ]) ? $status[ self::$isBeingSequenced ] : 0) +
            (isset($status[ self::$imageEnhancement ]) ? $status[ self::$imageEnhancement ] : 0) +
            (isset($status[ self::$isBeingEnhanced ]) ? $status[ self::$isBeingEnhanced ] : 0) +
            (isset($status[ self::$cropApprovalConfirmation ]) ? $status[ self::$cropApprovalConfirmation ] : 0) +
            (isset($status[ self::$finalApproval ]) ? $status[ self::$finalApproval ] : 0) +
            (isset($status[ self::$pushToMagento ]) ? $status[ self::$pushToMagento ] : 0) +
            (isset($status[ self::$inMagento ]) ? $status[ self::$inMagento ] : 0);
    }

    public static function getCropRejectedCount($inStockOnly = 1)
    {
        // Get status
        $status = self::getStatusCount($inStockOnly);

        // Return count
        return (isset($status[ self::$cropRejected ]) ? $status[ self::$cropRejected ] : 0);
    }

    public static function getTotalProductsScraped($inStockOnly = 1)
    {
        // Get status
        $status = self::getStatusCount($inStockOnly);

        // Return count
        return array_sum($status) -
            (isset($status[ self::$import ]) ? $status[ self::$import ] : 0) -
            (isset($status[ self::$scrape ]) ? $status[ self::$scrape ] : 0) -
            (isset($status[ self::$unableToScrape ]) ? $status[ self::$unableToScrape ] : 0) -
            (isset($status[ self::$unableToScrapeImages ]) ? $status[ self::$unableToScrapeImages ] : 0);
    }

    public static function isApproved($statusId)
    {
        // Check if status ID is matching approved product statuses

        switch ($statusId) {
            case self::$pushToMagento;
            case self::$inMagento;
                return true;
                break; // just to be sure
            default:
                return false;
        }

        // Return false by default
        return false;
    }
}

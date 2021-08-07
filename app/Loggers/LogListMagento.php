<?php

namespace App\Loggers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class LogListMagento extends Model
{
    public static function log($productId, $message, $severity = 'info',$storeWebsiteId =  null, $syncStatus = null, $languages = null)
    {
        // Write to log file
        Log::channel('listMagento')->$severity($message);

        // Write to database
        $logListMagento = new LogListMagento();
        $logListMagento->product_id = $productId;
        $logListMagento->message = $message;
        $logListMagento->store_website_id = $storeWebsiteId;
        $logListMagento->sync_status = $syncStatus;
        $logListMagento->languages = $languages;
        $logListMagento->save();

        // Return
        return $logListMagento;
    }

    public static function updateMagentoStatus($id, $status)
    {
        return self::where('id', $id)->update([
            'magento_status' => $status
        ]);
    }
}

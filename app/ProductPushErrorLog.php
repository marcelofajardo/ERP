<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ProductPushErrorLog extends Model
{
     /**
     * @var string
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="url",type="string")
     * @SWG\Property(property="message",type="string")
     * @SWG\Property(property="request_data",type="string")
     * @SWG\Property(property="response_data",type="string")
     * @SWG\Property(property="response_status",type="string")
     * @SWG\Property(property="store_website_id",type="interger")
     */
    protected $fillable = [
        'url',
        'product_id',
        'message',
        'request_data',
        'response_data',
        'response_status',
        'store_website_id',
        'url',
    ];

    public static function log($url = null, $productId, $message, $status = null, $storeWebsiteId = null, $request_data = null, $response_data = null, $logId = null)
    {
        // Write to database
        $logListMagento                   = new ProductPushErrorLog();
        $logListMagento->url              = $url;
        $logListMagento->product_id       = $productId;
        $logListMagento->message          = $message;
        $logListMagento->store_website_id = $storeWebsiteId;
        $logListMagento->response_status  = $status;
        $logListMagento->request_data     = json_encode($request_data);
        $logListMagento->response_data    = json_encode($response_data);
        if ($logId) {
            $logListMagento->log_list_magento_id = $logId;
        }
        $logListMagento->save();
        return;
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
    public function store_website()
    {
        return $this->belongsTo('App\StoreWebsite');
    }
}

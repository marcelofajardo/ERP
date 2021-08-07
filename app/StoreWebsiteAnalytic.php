<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreWebsiteAnalytic extends Model {

/**
     * @var string
      * @SWG\Property(property="id",type="integer")
     * @SWG\Property(property="store_website_analytics",type="string")
     * @SWG\Property(property="website",type="string")
     * @SWG\Property(property="account_id",type="integer")
     * @SWG\Property(property="view_id",type="integer")
     * @SWG\Property(property="store_website_id",type="integer")
     * @SWG\Property(property="google_service_account_json",type="text")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */

    protected $table = 'store_website_analytics';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'website','email', 'account_id', 'view_id', 'store_website_id', 'google_service_account_json', 'created_at', 'updated_at'];

    public function storeWebsiteDetails() {
        return $this->belongsTo(StoreWebsite::class, 'store_website_id', 'id');
    }

}

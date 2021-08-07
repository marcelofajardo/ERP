<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;


class WebsiteStoreView extends Model
{
       /**
     * @var string
      * @SWG\Property(property="name",type="string")
      * @SWG\Property(property="code",type="string")
      * @SWG\Property(property="status",type="string")
      * @SWG\Property(property="platform_id",type="integer")
      * @SWG\Property(property="website_store_id",type="integer")
      * @SWG\Property(property="sort_order",type="boolean")
     */
    protected $fillable = [
        'name', 
        'code', 
        'status', 
        'sort_order', 
        'platform_id', 
        'website_store_id', 
    ];

    public function websiteStore()
    {
        return $this->hasOne(\App\WebsiteStore::class, 'id','website_store_id');
    }

    public function scrapperImage()
    {
        return $this->hasMany(\App\scraperImags::class, 'website_id', 'code');
    }

    public function websiteStoreHasOne()
    {
        return $this->belongsTo(\App\WebsiteStore::class);
    }
}

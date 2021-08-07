<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreWebsiteBrand extends Model
{
	/**
     * @var string
      * @SWG\Property(property="brand_id",type="integer")
     * @SWG\Property(property="markup",type="string")
     * @SWG\Property(property="store_website_id",type="integer")
     * @SWG\Property(property="magento_value",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    protected $fillable = [
        'brand_id', 'markup', 'store_website_id', 'created_at', 'updated_at', 'magento_value'
    ];
}

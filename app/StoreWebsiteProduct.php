<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreWebsiteProduct extends Model
{
       /**
     * @var string
    
      * @SWG\Property(property="store_website_id",type="integer")
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="platform_id",type="integer")
     */

    protected $fillable = [
        'store_website_id',
        'product_id',
        'platform_id'
    ];

}

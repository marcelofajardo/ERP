<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreWebsiteProductAttribute extends Model
{
	  /**
     * @var string
    
      * @SWG\Property(property="price",type="float")
      * @SWG\Property(property="discount",type="float")
     * @SWG\Property(property="discount_type",type="string")
     * @SWG\Property(property="description",type="string")
      * @SWG\Property(property="store_website_id",type="integer")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    protected $fillable = [
        'product_id', 'price', 'discount', 'discount_type', 'description', 'store_website_id', 'stock' , 'created_at', 'updated_at',
    ];

    public function storeWebsite()
    {
        return $this->belongsTo(\App\StoreWebsite::class);
    }
}

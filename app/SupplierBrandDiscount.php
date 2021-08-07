<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SupplierBrandDiscount extends Model
{
	      /**
     * @var string
      * @SWG\Property(property="supplier_id",type="integer")
      * @SWG\Property(property="product_id",type="integer")
      * @SWG\Property(property="discount",type="float")
      * @SWG\Property(property="fixed_price",type="float")
 
     */
    protected $guarded = [];

    public function supplier()
    {
        return $this->hasOne('App\Supplier', 'id', 'supplier_id');
    }

    public function brand()
    {
        return $this->hasOne('App\Brand', 'id', 'brand_id');
    }
}

<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SupplierDiscountInfo extends Model
{
	      /**
     * @var string
      * @SWG\Property(property="supplier_id",type="integer")
      * @SWG\Property(property="product_id",type="integer")
      * @SWG\Property(property="discount",type="float")
      * @SWG\Property(property="fixed_price",type="float")
 
     */
    protected $fillable = [
        'product_id','discount','fixed_price','supplier_id'
    ];
}

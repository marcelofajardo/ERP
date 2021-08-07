<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SupplierBrandCountHistory extends Model
{
	        /**
     * @var string
      * @SWG\Property(property="supplier_brand_count_id",type="integer")
      * @SWG\Property(property="supplier_id",type="integer")
      * @SWG\Property(property="brand_id",type="integer")
      * @SWG\Property(property="cnt",type="string")
      * @SWG\Property(property="url",type="string")
      * @SWG\Property(property="category_id",type="integer")
      * @SWG\Property(property="supplier_brand_count_histories",type="integer")
     */
    protected $table = 'supplier_brand_count_histories';

    protected $fillable = [ 'supplier_id', 'brand_id', 'cnt','url','category_id' , 'supplier_brand_count_id'];
}

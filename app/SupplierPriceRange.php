<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SupplierPriceRange extends Model
{
	    /**
     * @var string
     
      * @SWG\Property(property="supplier_price_range",type="string")
      * @SWG\Property(property="timestamps",type="datetime")
      * @SWG\Property(property="price_from",type="string")
      * @SWG\Property(property="price_to",type="string")
     */
    protected $table = 'supplier_price_range';
    public $timestamps = false;
    protected $fillable = [
        'price_from',
		'price_to',
    ];
}
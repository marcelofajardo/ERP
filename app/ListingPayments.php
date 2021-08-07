<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ListingPayments extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="product_ids",type="string")
     */
    protected $casts = [
        'product_ids' => 'array'
    ];
}

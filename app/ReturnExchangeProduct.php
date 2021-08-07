<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ReturnExchangeProduct extends Model
{
		 /**
     * @var string
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="order_product_id",type="integer")
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="status_id",type="integer")
     */
    protected $fillable = [
        'product_id',
        'order_product_id',
        'name',
        'status_id',
    ];
}

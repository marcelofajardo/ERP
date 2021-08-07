<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="CustomerBasketProduct"))
 */
use Illuminate\Database\Eloquent\Model;

class CustomerBasketProduct extends Model
{
    //

    /**
     * @var string

     * @SWG\Property(property="customer_basket_id",type="integer")
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="product_sku",type="string")
     * @SWG\Property(property="product_name",type="string")
     * @SWG\Property(property="product_price",type="decimal")
     * @SWG\Property(property="product_currency",type="string")
     */
    protected $fillable = [
        'customer_basket_id',
        'product_id',
        'product_sku',
        'product_name',
        'product_price',
        'product_currency',
    ];

}

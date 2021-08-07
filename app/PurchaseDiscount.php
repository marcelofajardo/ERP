<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class PurchaseDiscount extends Model
{
	  /**
     * @var string
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="purchase_id",type="integer")
     * @SWG\Property(property="percentage",type="float")
     * @SWG\Property(property="amount",type="float")
     * @SWG\Property(property="type",type="string")
     */
  protected $fillable = [
    'purchase_id', 'product_id', 'percentage', 'amount', 'type'
  ];
}

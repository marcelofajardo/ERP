<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class CustomerOrderCharities extends Model
{
         /**
     * @var string
   * @SWG\Property(property="customer_id",type="integer")
   * @SWG\Property(property="order_id",type="integer")
   * @SWG\Property(property="charity_id",type="integer")
   * @SWG\Property(property="amount",type="integer")
   * @SWG\Property(property="customer_contribution",type="string")

     */
	protected $fillable = ['customer_id', 'order_id', 'charity_id', 'amount', 'customer_contribution', 'our_contribution', 'status'];
	
	public function user()
    {
        return $this->belongsTo('App\Customer');
    }
}

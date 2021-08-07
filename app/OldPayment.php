<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class OldPayment extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="old_id",type="integer")
     * @SWG\Property(property="currency",type="string")
     * @SWG\Property(property="payment_date",type="datetime")
     * @SWG\Property(property="pending_amount",type="float")
     * @SWG\Property(property="paid_amount",type="float")
     * @SWG\Property(property="service_provided",type="string")
     * @SWG\Property(property="module",type="string")
     * @SWG\Property(property="description",type="string")
     * @SWG\Property(property="other",type="string")
     * @SWG\Property(property="paid_date",type="datetime")
     * @SWG\Property(property="payable_amount",type="float")
     */
    protected $fillable = array(
        'old_id', 'currency', 'payment_date', 'pending_amount', 'paid_amount', 'service_provided','module','description','other','paid_date','work_hour','payable_amount' 
    );
}

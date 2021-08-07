<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

class CreditHistory extends Model
{

	    /**
     * @var string
      * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="model_id",type="integer")
     * @SWG\Property(property="credit_history",type="string")
     * @SWG\Property(property="model_type",type="string")
     * @SWG\Property(property="used_credit",type="string")
     * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="used_in",type="integer")
     */
    protected $table='credit_history';
    protected $fillable=['customer_id','model_id','model_type','used_credit','used_in','type'];
}

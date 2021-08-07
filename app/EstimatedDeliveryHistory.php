<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class EstimatedDeliveryHistory extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="field",type="string")
     * @SWG\Property(property="updated_by",type="integer")
     * @SWG\Property(property="order_id",type="integer")
     * @SWG\Property(property="old_value",type="string")
     * @SWG\Property(property="new_value",type="string")
     */
    protected $fillable = array(
        'order_id', 'field', 'updated_by','old_value','new_value'
    );
}

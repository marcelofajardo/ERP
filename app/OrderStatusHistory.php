<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="old_id",type="integer")
     * @SWG\Property(property="old_status",type="string")
     * @SWG\Property(property="new_status",type="string")
     * @SWG\Property(property="user_id",type="integer")
     */
    protected $fillable = [
        'order_id',
        'old_status',
        'new_status',
        'user_id'
    ];
}

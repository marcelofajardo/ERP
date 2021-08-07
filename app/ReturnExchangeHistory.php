<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ReturnExchangeHistory extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="return_exchange_id",type="integer")
     * @SWG\Property(property="status_id",type="integer")
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="comment",type="string")
     * @SWG\Property(property="new_value",type="string")
     * @SWG\Property(property="history_type",type="string")
     * @SWG\Property(property="old_value",type="string")
     */
    protected $fillable = [
        'return_exchange_id',
        'status_id',
        'user_id',
        'comment',
        'history_type',
        'new_value',
        'old_value'
    ];
}

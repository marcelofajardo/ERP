<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ReturnExchangeStatus extends Model
{
	     /**
     * @var string
    
     * @SWG\Property(property="status_name",type="string")
     * @SWG\Property(property="message",type="string")

     */
    const STATUS_TEMPLATE = 'Greetings from Solo Luxury Ref: number #{id} we have updated with status : #{status}.';

    protected $fillable = [
		'status_name',
        'message'
    ];
}

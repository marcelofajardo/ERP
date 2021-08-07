<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model{
	 /**
     * @var string
     * @SWG\Property(property="name",type="strng")
     */
    protected $fillable = [
        'name'
    ];

        
}
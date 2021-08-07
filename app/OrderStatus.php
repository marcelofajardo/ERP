<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    //
    public function order(){
    	return $this->hasMany('App\Order');
    }
}
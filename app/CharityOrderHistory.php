<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class CharityOrderHistory extends Model
{
    //
    /**
     * @var string
       * @SWG\Property(property="charity_order_history",type="string")
     */
	protected $table="charity_order_history";

}

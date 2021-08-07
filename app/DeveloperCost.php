<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class DeveloperCost extends Model
{
			     /**
     * @var string
   * @SWG\Property(property="user_id",type="integer")
   * @SWG\Property(property="amount",type="float")
   * @SWG\Property(property="paid_date",type="datetime")
        */
  protected $fillable = [
    'user_id', 'amount', 'paid_date'
  ];
}

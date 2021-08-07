<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class DailyCashFlow extends Model
{
	     /**
     * @var string
   * @SWG\Property(property="received_from",type="datetime")
   * @SWG\Property(property="paid_to",type="string")
    * @SWG\Property(property="date",type="datetime")
   * @SWG\Property(property="expected",type="datetime")
   * @SWG\Property(property="received",type="datetime")

  
     */
  protected $fillable = [
    'received_from', 'paid_to', 'date', 'expected', 'received'
  ];
}

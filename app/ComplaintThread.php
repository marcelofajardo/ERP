<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

class ComplaintThread extends Model
{
	/**
     * @var string
     * @SWG\Property(property="complaint_id",type="integer")
     * @SWG\Property(property="account_id",type="integer")
	 * @SWG\Property(property="thread",type="string")
     */
  protected $fillable = [
    'complaint_id', 'account_idaccount_id', 'thread'
  ];

  public function account()
  {
    return $this->belongsTo('App\Account');
  }
}

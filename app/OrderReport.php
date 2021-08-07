<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class OrderReport extends Model
{
   /**
     * @var string
     * @SWG\Property(property="status",type="string")
     */
  protected $appends = ['status'];

  public function status()
  {
    return $this->belongsTo('App\OrderStatus', 'status_id');
  }

  public function statusName()
  {
    return $this->belongsTo('App\OrderStatus', 'status_id')->first()->status;
  }

  public function getStatusAttribute()
	{
		return $this->statusName();
	}
}

<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class DeliveryApproval extends Model
{
  use Mediable;

  public function voucher()
  {
    return $this->hasOne('App\Voucher');
  }

  public function user()
  {
    return $this->belongsTo('App\User', 'assigned_user_id');
  }

  public function order()
  {
    return $this->belongsTo('App\Order');
  }

  public function status_changes()
	{
		return $this->hasMany('App\StatusChange', 'model_id')->where('model_type', 'App\DeliveryApproval')->latest();
	}

  public function private_view()
  {
    return $this->belongsTo('App\PrivateView');
  }
}

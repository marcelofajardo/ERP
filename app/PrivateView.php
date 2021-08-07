<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class PrivateView extends Model
{
  use Mediable;

  public function customer()
  {
    return $this->belongsTo('App\Customer');
  }

  public function delivery_approval()
  {
    return $this->hasOne('App\DeliveryApproval');
  }

  public function order_product()
  {
    return $this->belongsTo('App\OrderProduct');
  }

  public function products()
  {
    return $this->belongsToMany('App\Product', 'private_view_products', 'private_view_id', 'product_id');
  }

  public function status_changes()
	{
		return $this->hasMany('App\StatusChange', 'model_id')->where('model_type', 'App\PrivateView')->latest();
	}
}

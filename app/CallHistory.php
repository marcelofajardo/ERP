<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class CallHistory extends Model
{
	/**
     * @var string
   * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="status",type="string")
     */
  protected $fillable = ['customer_id', 'status'];

  public function customer() {
    return $this->belongsTo('App\Customer');
  }
  public function store_website(){
      return $this->belongsTo(StoreWebsite::class);
  }
}

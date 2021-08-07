<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
  /**
     * @var string
     * @SWG\Property(property="account_id",type="integer")
     * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="posted_date",type="datetime")
     * @SWG\Property(property="review_link",type="string")
     * @SWG\Property(property="review",type="string")
     * @SWG\Property(property="serial_number",type="string")
     * @SWG\Property(property="platform",type="string")
     * @SWG\Property(property="title",type="string")
     */
  protected $fillable = [
    'account_id', 'customer_id', 'posted_date', 'review_link', 'review', 'serial_number', 'platform', 'title'
  ];

  public function review_schedule()
  {
    return $this->belongsTo('App\ReviewSchedule');
  }

  public function account()
  {
    return $this->belongsTo('App\Account');
  }

  public function customer()
  {
    return $this->belongsTo('App\Customer');
  }

  public function status_changes()
	{
		return $this->hasMany('App\StatusChange', 'model_id')->where('model_type', 'App\Review')->latest();
	}
}

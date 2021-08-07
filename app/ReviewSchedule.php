<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ReviewSchedule extends Model
{
    /**
     * @var string
     * @SWG\Property(property="account_id",type="integer")
     * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="date",type="datetime")
     * @SWG\Property(property="posted_date",type="datetime")
     * @SWG\Property(property="platform",type="string")
     * @SWG\Property(property="review_count",type="string")
     * @SWG\Property(property="review_link",type="string")
     * @SWG\Property(property="status",type="string")
     */
  protected $fillable = [
    'account_id', 'customer_id', 'date', 'posted_date', 'platform', 'review_count', 'review_link', 'status'
  ];

  public function reviews()
  {
    return $this->hasMany('App\Review', 'review_schedule_id');
  }

  public function account()
  {
    return $this->belongsTo('App\Account');
  }

  public function customer()
  {
    return $this->belongsTo('App\Customer');
  }
}

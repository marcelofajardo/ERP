<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ImQueue;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Plank\Mediable\Mediable;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class Account extends Model
{
  /**
     * @var string
     * @SWG\Property(property="first_name",type="string")
     * @SWG\Property(property="last_name",type="string")
     * @SWG\Property(property="email",type="email")
     * @SWG\Property(property="password",type="password")
     * @SWG\Property(property="dob",type="datetime")
     * @SWG\Property(property="platform",type="string")
     * @SWG\Property(property="followers_count",type="integer")
     * @SWG\Property(property="posts_count",type="integer")
     * @SWG\Property(property="dp_count",type="integer")
     * @SWG\Property(property="broadcast",type="string")
     * @SWG\Property(property="country",type="string")
     * @SWG\Property(property="gender",type="string")
     * @SWG\Property(property="proxy",type="string")
     */

  use SoftDeletes;
  use Mediable;
  protected $fillable = [
    'first_name', 'last_name', 'email', 'password', 'dob', 'platform', 'followers_count', 'posts_count', 'dp_count', 'broadcast', 'country', 'gender','proxy','last_cron_time'
  ];

  public function reviews()
  {
    return $this->hasMany('App\Review');
  }

  public function thread()
  {
    return $this->hasMany('App\InstagramThread','account_id','id')->whereNotNull('instagram_user_id');
  }

  public function has_posted_reviews()
  {
    $count = $this->hasMany('App\Review')->where('status', 'posted')->count();

    return $count > 0;
  }

  public function imQueueBroadcast()
  {
    return $this->hasMany(ImQueue::class,'number_from','last_name');
  }

  public function storeWebsite()
  {
      return $this->hasOne('\App\StoreWebsite','id','store_website_id');
  }
}

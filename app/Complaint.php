<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

class Complaint extends Model
{
  use Mediable;

        /**
     * @var string
       * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="platform",type="string")
     * @SWG\Property(property="complaint",type="string")
     * @SWG\Property(property="status",type="string")
     * @SWG\Property(property="link",type="string")
     * @SWG\Property(property="where",type="string")
     * @SWG\Property(property="username",type="string")
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="plan_of_action",type="string")
     * @SWG\Property(property="thread_type",type="string")
     * @SWG\Property(property="date",type="datetime")
   
     */

  protected $fillable = [
    'customer_id', 'platform', 'complaint', 'status', 'link', 'where', 'username', 'name', 'plan_of_action', 'thread_type', 'date'
  ];

  public function customer()
  {
    return $this->belongsTo('App\Customer');
  }

  public function threads()
  {
    return $this->hasMany('App\ComplaintThread');
  }

  public function internal_messages()
  {
    return $this->hasMany('App\Remark', 'taskid')->where('module_type', 'internal-complaint')->latest();
  }

  public function plan_messages()
  {
    return $this->hasMany('App\Remark', 'taskid')->where('module_type', 'complaint-plan-comment')->latest();
  }

  public function remarks()
  {
    return $this->hasMany('App\Remark', 'taskid')->where('module_type', 'complaint')->latest();
  }

  public function status_changes()
	{
		return $this->hasMany('App\StatusChange', 'model_id')->where('model_type', 'App\Complaint')->latest();
	}
}

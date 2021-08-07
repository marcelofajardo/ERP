<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ScheduledMessage extends Model
{
	/**
     * @var string
     * @SWG\Property(property="category",type="string")
     * @SWG\Property(property="assign_from",type="strintergering")
     * @SWG\Property(property="assign_to",type="interger")
     * @SWG\Property(property="task_details",type="string")
     * @SWG\Property(property="task_subject",type="interger")
     * @SWG\Property(property="remark",type="string")
     * @SWG\Property(property="recurring_type",type="string")
     * @SWG\Property(property="recurring_day",type="string")
     */
  protected $fillable = [
    'user_id', 'customer_id', 'message', 'type', 'data', 'sending_time'
  ];
}

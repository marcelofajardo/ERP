<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class MeetingAndOtherTime extends Model
{
	/**
     * @var string
  
     * @SWG\Property(property="model",type="integer")
     * @SWG\Property(property="model_id",type="integer")
     * @SWG\Property(property="user_id",type="integer")
   * @SWG\Property(property="time",type="string")
     * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="note",type="string")
     * @SWG\Property(property="old_time",type="string")
     * @SWG\Property(property="approve",type="string")
     * @SWG\Property(property="updated_by",type="integer")
     */
    protected $fillable = [
        'model','model_id','user_id','time','type','note','old_time','approve','updated_by'];
}

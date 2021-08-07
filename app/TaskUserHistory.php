<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class TaskUserHistory extends Model
{
	 /**
     * @var string
      * @SWG\Property(property="model",type="string")
      * @SWG\Property(property="model_id",type="integer")
      * @SWG\Property(property="old_id",type="integer")
      * @SWG\Property(property="new_id",type="integer")
      * @SWG\Property(property="user_type",type="string")
      * @SWG\Property(property="updated_by",type="integer")
      * @SWG\Property(property="master_user_hubstaff_task_id",type="integer")
     */
    protected $fillable = [
        'model','model_id','old_id','new_id','user_type','updated_by', 'master_user_hubstaff_task_id'];
}

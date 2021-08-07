<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class TaskAttachment extends Model
{
	  /**
     * @var string
     
      * @SWG\Property(property="task_id",type="integer")
      * @SWG\Property(property="name",type="string")
     */
  protected $fillable = [
    'task_id','name'
  ];
}

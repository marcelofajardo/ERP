<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class TasksHistory extends Model
{
	  /**
     * @var string
      * @SWG\Property(property="tasks_history",type="string")
      * @SWG\Property(property="name",type="string")
  
     */
    protected $table = 'tasks_history';

    protected $fillable = [
        'name'
    ];
}

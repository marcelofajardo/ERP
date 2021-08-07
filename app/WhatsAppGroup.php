<?php

namespace App;
use App\Task;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class WhatsAppGroup extends Model
{
	  /**
     * @var string
     
      * @SWG\Property(property="task_id",type="integer")
      * @SWG\Property(property="group_id",type="integer")
     
     */
    protected $fillable = [
		'task_id', 'group_id'
	];


	public function task()
	{
		return $this->belongsTo(Task::class);
	}
}

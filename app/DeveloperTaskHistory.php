<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class DeveloperTaskHistory extends Model
{
			     /**
 
   * @SWG\Property(property="user_id",type="integer")
   * @SWG\Property(property="developer_task_id",type="integer")
   * @SWG\Property(property="attribute",type="string")
   * @SWG\Property(property="old_value",type="string")
   * @SWG\Property(property="new_value",type="string")
   * @SWG\Property(property="model",type="string")
   * @SWG\Property(property="is_approved",type="boolean")
        */
    protected $table = "developer_tasks_history";
    protected $fillable = [
        'user_id', 'developer_task_id', 'attribute', 'old_value', 'new_value','model','is_approved'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

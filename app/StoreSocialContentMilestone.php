<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreSocialContentMilestone extends Model
{
	/**
     * @var string
      * @SWG\Property(property="store_social_content_id",type="integer")
      * @SWG\Property(property="task_id",type="integer")
     * @SWG\Property(property="ono_of_content",type="string")
     * @SWG\Property(property="status",type="string")
   
     */
    protected $fillable = [
        'task_id','ono_of_content','store_social_content_id','status'
    ];

    public function task() {
        return $this->belongsTo('App\Task','task_id','id');
    }
}

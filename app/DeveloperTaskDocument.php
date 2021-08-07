<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class DeveloperTaskDocument extends Model
{
			     /**
     * @var string
   * @SWG\Property(property="subject",type="string")
   * @SWG\Property(property="description",type="string")
   * @SWG\Property(property="created_by",type="integer")
   * @SWG\Property(property="created_at",type="datetime")
   * @SWG\Property(property="developer_task_id",type="integer")
 
   
        */
    use Mediable;
    protected $fillable = ['subject', 'description', 'created_by', 'created_at', 'developer_task_id'];

    public function creator()
    {
        return $this->belongsTo("\App\User", "created_by", "id");
    }

}

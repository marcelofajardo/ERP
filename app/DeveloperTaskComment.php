<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class DeveloperTaskComment extends Model
{
			     /**
     * @var string
   * @SWG\Property(property="task_id",type="integer")
   * @SWG\Property(property="user_id",type="integer")
   * @SWG\Property(property="comment",type="string")
   
        */
  protected $fillable = [
    'task_id','user_id', 'comment'
  ];
}

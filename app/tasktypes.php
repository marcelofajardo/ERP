<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class TaskTypes extends Model
{
 /**
     * @var string
      * @SWG\Property(property="task_types",type="string")
      * @SWG\Property(property="name",type="integer")
     */
    protected $table = 'task_types';

    protected $fillable = [
        'name'
    ];
}

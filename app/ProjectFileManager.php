<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ProjectFileManager extends Model
{
    //

    protected $fillable = [
        'name',
        'project_name',
        'size',
        'parent'
    ];

}

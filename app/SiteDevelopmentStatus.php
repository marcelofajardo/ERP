<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SiteDevelopmentStatus extends Model
{
	    /**
     * @var string
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    protected $fillable = [
        'name', 'created_at', 'updated_at',
    ];
}

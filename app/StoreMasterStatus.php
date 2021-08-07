<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreMasterStatus extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="value",type="string")
     * @SWG\Property(property="label",type="string")
     * @SWG\Property(property="store_website_id",type="integer")
     */
    protected $fillable = [
        'store_website_id','value','label'
    ];
}

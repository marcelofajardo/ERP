<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SystemSizeRelation extends Model
{
			    /**
     * @var string
      * @SWG\Property(property="system_size",type="string")
      * @SWG\Property(property="system_size_manager_id",type="integer")
      * @SWG\Property(property="size",type="string")
     */
    protected $fillable = [
        'system_size_manager_id',
        'system_size',
        'size',
    ];
}

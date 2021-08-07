<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class DatabaseHistoricalRecord extends Model
{
		     /**
     * @var string
   * @SWG\Property(property="database_name",type="string")
   * @SWG\Property(property="size",type="string")
   * @SWG\Property(property="created_at",type="datetime")
   * @SWG\Property(property="updated_at",type="datetime")

  
     */
    protected $fillable = [
        'database_name', 'size', 'created_at', 'updated_at',
    ];
}

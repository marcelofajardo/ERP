<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class GoogleServer extends Model
{
		   /**
     * @var string
     * @SWG\Property(property="google_server",type="string")
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="key",type="string")
     * @SWG\Property(property="description",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    public $table = 'google_server';
    
    protected $fillable = [
        'name',
        'key',
        'description',
        'created_at',
        'updated_at',
    ];

}

<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class FailedJob extends Model
{
     /**
     * @var string
     * @SWG\Property(property="failed_jobs",type="string")
     * @SWG\Property(property="queue",type="string")
     * @SWG\Property(property="payload",type="string")
     * @SWG\Property(property="exception",type="string")
     */
    protected $table = 'failed_jobs'; 
	
    protected $fillable = [
        'queue','payload','exception'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public $timestamps = false;

    protected $hidden = [
    ];
}

<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SearchQueue extends Model
{
    /**
     * @var string
     * @SWG\Property(property="search_queues",type="string")
     */
    protected $table = 'search_queues'; 
	
    protected $fillable = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}

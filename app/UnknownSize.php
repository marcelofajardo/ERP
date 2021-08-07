<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class UnknownSize extends Model
{
	       /**
     * @var string
      * @SWG\Property(property="size",type="string")
    
     */
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'size'
    ];
}

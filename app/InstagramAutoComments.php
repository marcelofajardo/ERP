<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class InstagramAutoComments extends Model
{
		   /**
     * @var string
     * @SWG\Property(property="options",type="string")
 
     */
    protected $casts = [
        'options' => 'array'
    ];
}

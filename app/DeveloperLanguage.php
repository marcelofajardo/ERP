<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */


use Illuminate\Database\Eloquent\Model;

class DeveloperLanguage extends Model
{
		     /**
     * @var string
   * @SWG\Property(property="name",type="string")

     */
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}

<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */


use Illuminate\Database\Eloquent\Model;

class GoogleWebMasters extends Model
{
		   /**
     * @var string
     * @SWG\Property(property="sites",type="string")
     * @SWG\Property(property="crawls",type="string")
     */
    protected $fillable = ['sites','crawls'];
}

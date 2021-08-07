<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreSocialAccount extends Model
{
	 /**
     * @var string
      * @SWG\Property(property="store_website_id",type="integer")
     * @SWG\Property(property="platform",type="string")
     * @SWG\Property(property="url",type="string")
     * @SWG\Property(property="username",type="string")
     * @SWG\Property(property="password",type="string")
    
     */
    protected $fillable = [
        'store_website_id','platform','url','username','password'
    ];
}

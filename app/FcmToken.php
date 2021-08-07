<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class FcmToken extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="token",type="string")
     * @SWG\Property(property="store_website_id",type="integer")

     */
    protected $fillable = ['token','store_website_id'];
}

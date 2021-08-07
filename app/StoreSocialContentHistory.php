<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreSocialContentHistory extends Model
{
	/**
     * @var string
      * @SWG\Property(property="store_social_content_id",type="integer")
     * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="message",type="string")
     * @SWG\Property(property="username",type="string")
     */
    protected $fillable = [
        'type','store_social_content_id','message','username'
    ];
}

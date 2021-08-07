<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class PushFcmNotification extends Model
{
	   /**
     * @var string
     * @SWG\Property(property="token",type="string")
     * @SWG\Property(property="title",type="string")
     * @SWG\Property(property="body",type="string")
     * @SWG\Property(property="url",type="string")
     * @SWG\Property(property="sent_at",type="string")
     * @SWG\Property(property="sent_on",type="string")
     * @SWG\Property(property="created_by",type="interger")
     */
    protected $fillable=['title','token','body','url','store_website_id','sent_at','sent_on','created_by'];
}

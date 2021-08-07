<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class BloggerEmailTemplate extends Model
{
	    /**
     * @var string
     * @SWG\Property(property="from",type="string")
     * @SWG\Property(property="subject",type="string")
     * @SWG\Property(property="message",type="string")
     * @SWG\Property(property="cc",type="string")
     * @SWG\Property(property="bcc",type="string")
     * @SWG\Property(property="other",type="string")
     * @SWG\Property(property="type",type="integer")
     */
    protected $fillable = ['from','subject','message','cc','bcc','other','type'];
}

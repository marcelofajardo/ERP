<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

class ContactBlogger extends Model
{


	/**
     * @var string
   
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="email",type="string")
     * @SWG\Property(property="instagram_handle",type="string")
     * @SWG\Property(property="quote",type="string")
     * @SWG\Property(property="status",type="string")
     * @SWG\Property(property="other",type="string")

     */
    protected $fillable = ['name','email','instagram_handle','quote','status','other'];

}

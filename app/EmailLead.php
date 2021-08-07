<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Redirect;
	

class EmailLead extends Model
{
	   /**
     * @var string
     * @SWG\Property(property="email",type="string")
     * @SWG\Property(property="source",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
	protected $fillable = ['email','source','created_at','updated_at'];
	
}

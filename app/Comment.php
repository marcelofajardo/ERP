<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

class Comment extends Model
{

     /**
     * @var string
   * @SWG\Property(property="content",type="string")
   * @SWG\Property(property="subject_type",type="string")
     * @SWG\Property(property="subject_id",type="integer")
     * @SWG\Property(property="user_id",type="integer")
     */
    protected  $fillable = [
    	'content',
	    'subject_type',
	    'subject_id',
	    'user_id'
    ];

    public function user(){
    	return $this->belongsTo(User::class,'user_id','id');
    }
}

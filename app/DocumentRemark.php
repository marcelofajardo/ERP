<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */


use Illuminate\Database\Eloquent\Model;

class DocumentRemark extends Model
{
    /**
   * @SWG\Property(property="document_id",type="integer")
   * @SWG\Property(property="remark",type="string")
   * @SWG\Property(property="module_type",type="string")
   * @SWG\Property(property="user_name",type="string")

        */

    protected $fillable = [
    	'remark',
      'document_id',
	    'module_type',
      'user_name'
    ];

   /* public function subnotes()
  	{
  		return $this->hasMany('App\Remark', 'taskid')->where('module_type', 'task-note-subnote')->latest();
  	}*/
}

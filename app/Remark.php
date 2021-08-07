<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Remark extends Model
{
  /**
     * @var string
     * @SWG\Property(property="taskid",type="integer")
     * @SWG\Property(property="remark",type="string")
     * @SWG\Property(property="module_type",type="string")
     * @SWG\Property(property="user_name",type="string")
     */
    protected $fillable = [
    	'remark',
      'taskid',
	    'module_type',
      'user_name'
    ];

    public function subnotes()
  	{
  		return $this->hasMany('App\Remark', 'taskid')->where('module_type', 'task-note-subnote')->whereNull('delete_at');
    }
    
    public function singleSubnotes()
  	{
  		return $this->hasOne('App\Remark', 'taskid')->where('module_type', 'task-note-subnote')->whereNull('delete_at')->latest();
  	}

    public function archiveSubnotes()
    {
      return $this->hasMany('App\Remark', 'taskid')->where('module_type', 'task-note-subnote')->whereNotNull('delete_at');
    }
}

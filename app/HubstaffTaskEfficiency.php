<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class HubstaffTaskEfficiency extends Model
{
 	   /**
     * @var string
     * @SWG\Property(property="hubstaff_task_efficiency",type="string")
     * @SWG\Property(property="admin_input",type="string")
     * @SWG\Property(property="user_input",type="string")
     * @SWG\Property(property="date",type="datetime")
     * @SWG\Property(property="string",type="datetime")
     * @SWG\Property(property="user_id",type="integer")
  
     */
  protected $table = 'hubstaff_task_efficiency';	
  protected $fillable = ['user_id','admin_input','user_input','date','time'];
  
}

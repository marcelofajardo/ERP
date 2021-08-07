<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class LeadHubstaffDetail extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="id",type="integer")
     * @SWG\Property(property="hubstaff_task_id",type="integer")
     * @SWG\Property(property="task_id",type="integer")
     * @SWG\Property(property="team_lead_id",type="integer")
     * @SWG\Property(property="lead_hubstaff_detail",type="string")
     * @SWG\Property(property="current",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    public $table = 'lead_hubstaff_detail';

    protected $fillable = ['id','hubstaff_task_id','task_id','team_lead_id','current','created_at', 'updated_at'];
}

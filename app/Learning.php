<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\WhatsAppGroup;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Plank\Mediable\Mediable;

class Learning extends Model {
	    /**
     * @var string
      * @SWG\Property(property="category",type="string")
      * @SWG\Property(property="task_details",type="string")
      * @SWG\Property(property="task_subject",type="string")
      * @SWG\Property(property="completion_date",type="datetime")
      * @SWG\Property(property="assign_from",type="datetime")
      * @SWG\Property(property="assign_to",type="datetime")
      * @SWG\Property(property="is_statutory",type="boolean")
      * @SWG\Property(property="sending_time",type="string")
      * @SWG\Property(property="recurring_type",type="string")
      * @SWG\Property(property="statutory_id",type="integer")
      * @SWG\Property(property="model_type",type="string")
      * @SWG\Property(property="model_id",type="integer")
      * @SWG\Property(property="general_category_id",type="integer")
     
      * @SWG\Property(property="cost",type="string")
      * @SWG\Property(property="is_milestone",type="boolean")
      * @SWG\Property(property="no_of_milestone",type="string")
      * @SWG\Property(property="milestone_completed",type="string")
       * @SWG\Property(property="customer_id",type="integer")
       * @SWG\Property(property="hubstaff_task_id",type="integer")
       * @SWG\Property(property="master_user_id",type="integer")
       * @SWG\Property(property="lead_hubstaff_task_id",type="integer")
       * @SWG\Property(property="due_date",type="datetime")
       * @SWG\Property(property="site_developement_id",type="integer")
       * @SWG\Property(property="priority_no",type="integer")
     */

	use SoftDeletes;
	use Mediable;
	protected $fillable = [
		'category',
		'task_details',
		'task_subject',
		'completion_date',
		'assign_from',
		'assign_to',
		'is_statutory',
		'actual_start_date',
		'is_completed',
		'sending_time',
		'recurring_type',
		'statutory_id',
		'model_type',
		'model_id',
		'general_category_id',
		'cost',
		'is_milestone',
		'no_of_milestone',
		'milestone_completed',
		'customer_id',
		'hubstaff_task_id',
		'master_user_id',
		'lead_hubstaff_task_id',
		'due_date',
		'site_developement_id',
		'priority_no',
		'learning_user',
		'learning_vendor',
		'learning_subject',
		'learning_module',
		'learning_submodule',
		'learning_assignment',
		'learning_duedate',
		'learning_status',
	];

	const TASK_TYPES = [
		"Other Task",
		"Statutory Task",
		"Calendar Task",
		"Discussion Task",
		"Developer Task",
		"Developer Issue",
	];

	protected $dates = ['deleted_at'];

	public static function hasremark( $id ) {
		$task = Task::find( $id );
		if ( ! empty( $task->remark ) ) {
			return true;
		} else {
			return false;
		}
	}

	// getting remarks
	public static function getremarks($taskid)
	{
			$results = DB::select('select * from remarks where taskid = :taskid order by created_at DESC', ['taskid' => $taskid]);
			return json_decode(json_encode($results),true);
	}

    public function learningUser()
    {
        return $this->hasOne(\App\User::class,'id','learning_user');
    }

    public function learningVendor()
    {
        return $this->hasOne(\App\User::class,'id','learning_vendor');
    }

	public function remarks()
	{
		return $this->hasMany('App\Remark', 'taskid')->where('module_type', 'task')->latest();
	}

	public function notes()
	{
		return $this->hasMany('App\Remark', 'taskid')->where('module_type', 'task-note')->latest();
	}
	
	public function users()
	{
		return $this->belongsToMany('App\User', 'task_users', 'task_id', 'user_id')->where('type', 'App\User');
	}

	public function assignedTo()
	{
		return $this->belongsTo('App\User', 'assign_to', 'id');
	}

	public function contacts()
	{
		return $this->belongsToMany('App\Contact', 'task_users', 'task_id', 'user_id')->where('type', 'App\Contact');
	}

	public function whatsappgroup()
	{
		return $this->hasOne(WhatsAppGroup::class);
	}

	public function whatsappAll($needBroadCast = false)
    {
    	if($needBroadCast) {
            return $this->hasMany('App\ChatMessage', 'learning_id')->whereIn('status', ['7', '8', '9', '10'])->latest(); //Purpose - Replace from task_id to learning_id - DEVTASK-4020
        }

        return $this->hasMany('App\ChatMessage', 'learning_id')->whereNotIn('status', ['7', '8', '9', '10'])->latest(); //Purpose - Replace from task_id to learning_id - DEVTASK-4020
	}
	
	public function allMessages()
    {
        return $this->hasMany(ChatMessage::class, 'task_id', 'id')->orderBy('id','desc');
    }
	public function customer()
	{
		return $this->belongsTo('App\Customer', 'customer_id', 'id');
	}

	public function timeSpent(){
        return $this->hasOne(
            'App\Hubstaff\HubstaffActivity',
            'task_id',
            'hubstaff_task_id'
        )
        ->selectRaw('task_id, SUM(tracked) as tracked')
        ->groupBy('task_id');
    }

    public function taskStatus()
    {
    	return $this->hasOne(
            'App\taskStatus',
            'id',
            'status'
        );
    }
}

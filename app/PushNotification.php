<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\Helpers;
use App\Leads;
use App\Order;
use App\Task;
use App\StatutoryTask;

class PushNotification extends Model
{

	 /**
     * @var string
     * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="message",type="string")
     * @SWG\Property(property="role",type="string")
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="sent_to",type="string")
     * @SWG\Property(property="model_type",type="string")
     * @SWG\Property(property="model_id",type="interger")
     * @SWG\Property(property="message_id",type="interger")
     * @SWG\Property(property="reminder",type="string")
     */
	protected $fillable = [
		"type",
		"message",
		"role",
		"user_id",
		'sent_to',
		'model_type',
		'model_id',
		'message_id',
		'reminder'
	];

	protected $user_name = '';
	protected $client_name = '';
	protected $subject = '';
	protected $appends = ['user_name', 'client_name', 'subject'];

	public function getUserNameAttribute() {
		return $this->user_name;
	}

	public function setUserNameAttribute($id = null) {
		if ($id == null) {
			$this->user_name = '';
		} else {
			$this->user_name = Helpers::getUserNameById($id);
		}
	}

	public function getClientNameAttribute() {
		return $this->client_name;
	}

	public function setClientNameAttribute($model_type, $model_id) {
		if ($model_type == 'leads') {
			if ($lead = \App\ErpLeads::find($model_id)) {
				$this->client_name = ($lead->customer) ? $lead->customer->name : "";
			}
		} else if ($model_type == 'order') {
			if ($order = Order::find($model_id)) {
				$this->client_name = $order->client_name;
			}
		}
	}

	public function getSubjectAttribute() {
		return $this->subject;
	}

	public function setSubjectAttribute($model_type, $model_id) {
		switch($model_type) {
			case 'App\\Task':
				if ($task = Task::find($model_id)) {
					$this->subject = $task->task_subject ? $task->task_subject : 'Task Subject';
				}

				break;
			case 'App\\SatutoryTask':
				if ($task = SatutoryTask::find($model_id)) {
					$this->subject = $task->task_subject ? $task->task_subject : 'Task Subject';
				}

				break;
			case 'App\\Http\\Controllers\\Task':
				if ($task = Task::find($model_id)) {
					$this->subject = $task->task_subject ? $task->task_subject : 'Task Subject';
				}

				break;
			case 'User':
				$this->subject = 'Input Activity';

				break;
		}
	}
}

<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class NotificationQueue extends Model {
	 /**
     * @var string
	 * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="message",type="string")
     * @SWG\Property(property="message_id",type="integer")
     * @SWG\Property(property="reminder",type="string")
     * @SWG\Property(property="time_to_add",type="string")
     * @SWG\Property(property="model_type",type="string")
     * @SWG\Property(property="model_id",type="integer")
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="sent_to",type="integer")
     * @SWG\Property(property="role",type="string")
     */
	protected $fillable = [
		"type",
		'message',
		
		'reminder',
		'time_to_add',
		'model_type',
		'model_id',
		'user_id',
		'message_id',
	
		'sent_to',
		'role',
	];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LearningStatusHistory extends Model {
	 

	protected $table = 'learning_status_history';

	protected $fillable = [
		'learning_id',
		'old_status',
		'new_status',
		'update_by',
	];

	public function learning()
	{
		return $this->belongsTo(Learning::class, 'learning_id', 'id');
	}

	public function oldstatus()
	{
		return $this->belongsTo(TaskStatus::class, 'old_status', 'id');
	}

	public function newstatus()
	{
		return $this->belongsTo(TaskStatus::class, 'new_status', 'id');
	}

	public function user()
    {
        return $this->belongsTo('App\User','update_by','id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LearningDueDateHistory extends Model {
	 

	protected $table = 'learning_duedate_history';

	protected $fillable = [
		'learning_id',
		'old_duedate',
		'new_duedate',
		'update_by',
	];

	public function learning()
	{
		return $this->belongsTo(Learning::class, 'learning_id', 'id');
	}

	public function user()
    {
        return $this->belongsTo('App\User','update_by','id');
    }
}

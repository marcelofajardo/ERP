<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyActivitiesHistories extends Model
{
    protected $fillable = [
		'daily_activities_id',
		'title',
		'description'
	];
}

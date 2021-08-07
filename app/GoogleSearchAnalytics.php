<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleSearchAnalytics extends Model
{
    protected $fillable=['clicks','impressions','site_id','ctr','position','country','device','query','page','search_apperiance','date'];

    public function site()
    {
    	return $this->belongsTo('App\Site','site_id','id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleAnalyticsHistories extends Model
{
    protected $table = 'google_analytics_histories';
    

    protected $fillable = [ 
        'website_id',
        'account_id',
        'avg_time_page',
        'title',
        'description'
    ];
}

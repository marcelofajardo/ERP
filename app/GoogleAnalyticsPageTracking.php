<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleAnalyticsPageTracking extends Model
{
    protected $table = 'google_analytics_page_tracking';
    

    protected $fillable = [ 
        'website_analytics_id',
        'page',
        'avg_time_page',
        'page_views',
        'unique_page_views',
        'exit_rate',
        'entrances',
        'entrance_rate'
    ];
}

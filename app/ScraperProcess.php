<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScraperProcess extends Model
{

    protected $fillable = [
        'scraper_id',
        'scraper_name',
        'server_id',
        'started_at',
        'ended_at',
        'ended_at',
    ];

    

}

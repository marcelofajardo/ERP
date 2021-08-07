<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScraperPositionHistory extends Model
{
    //
    protected $fillable = [
        'scraper_id', 'scraper_name', 'comment',
    ];

}

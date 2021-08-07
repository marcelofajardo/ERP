<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScrapApiLog extends Model
{
    protected $fillable = [
        'scraper_id', 'server_id', 'log_messages'
    ];
}

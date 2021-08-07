<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Plank\Mediable\Mediable;

class ScraperScreenshotHistory extends Model
{
    use Mediable;
    
    protected $fillable = [
        'scraper_id', 
        'scraper_name',
    ];
}

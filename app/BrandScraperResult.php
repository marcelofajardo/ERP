<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrandScraperResult extends Model
{
    protected $fillable = ['date', 'brand_id', 'scraper_name', 'total_urls'];
}

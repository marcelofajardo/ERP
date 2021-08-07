<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class scraperImags extends Model
{
    protected $fillable = [
        'website_id', 
        'img_name', 
        'store_website',
        'img_url', 
    ];

    // public function stores()
    // {
    //     return $this->hasMany(\App\WebsiteStore::class, 'website_id', 'id');
    // }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreWebsiteAttributes extends Model
{
    //
    protected $fillable = [
        'attribute_key', 
        'attribute_val', 
        'store_website_id',
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreWebsiteUsers extends Model
{
    protected $table = 'store_website_users';
    protected $fillable = [
        'store_website_id', 'email','password','username', 'first_name','last_name','website_mode'
    ];
}

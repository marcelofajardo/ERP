<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiResponseMessage extends Model
{
    protected $table = "api_response_messages";

    public function storeWebsite(){
        return $this->hasOne('App\StoreWebsite','id','store_website_id');
    }
}

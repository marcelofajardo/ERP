<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailContentHistory extends Model
{
    protected $table= "email_content_history";

    public function addedBy(){
        return $this->hasOne('App\User','id','updated_by');
    }
}

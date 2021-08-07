<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailNotificationEmailDetails extends Model
{
    //
    protected $fillable = [
        'user_id',
        'emails',
    ];
}

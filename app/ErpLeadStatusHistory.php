<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErpLeadStatusHistory extends Model
{
    protected $fillable = [
        'lead_id',
        'old_status',
        'new_status',
        'user_id'
    ];
}

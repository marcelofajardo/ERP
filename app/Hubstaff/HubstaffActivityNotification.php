<?php

namespace App\Hubstaff;
use App\UserAvaibility;

use Illuminate\Database\Eloquent\Model;

class HubstaffActivityNotification extends Model
{
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'min_percentage',
        'actual_percentage',
        'reason',
        'status',
        'hubstaff_user_id',
        'total_track',

    ];
}
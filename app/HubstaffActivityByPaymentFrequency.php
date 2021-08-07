<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HubstaffActivityByPaymentFrequency extends Model
{
    protected $fillable = [
        'user_id',
        'activity_excel_file',
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DigitalMarketingPlatformFile extends Model
{
    protected $fillable = [
        'digital_marketing_platform_id',
        'user_id',
        'file_name'
    ];
}
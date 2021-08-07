<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErpLog extends Model
{
    protected $fillable = [
        'model_id',
        'url',
        'model',
        'request',
        'type',
        'response',
        'created_at',
        'updated_at'
    ];

}

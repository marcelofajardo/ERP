<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatabaseTableHistoricalRecord extends Model
{
    //
    protected $fillable = [
        'database_name', 'size', 'database_id', 'created_at', 'updated_at',
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DigitalMarketingSolutionFile extends Model
{
    protected $fillable = [
        'digital_marketing_solution_id',
        'user_id',
        'file_name'
    ];
}
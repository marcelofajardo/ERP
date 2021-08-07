<?php

namespace App\Hubstaff;

use Illuminate\Database\Eloquent\Model;

class HubstaffTask extends Model
{
    protected $fillable = [
        'hubstaff_task_id',
        'project_id',
        'hubstaff_project_id',
        'summary'
    ];
}
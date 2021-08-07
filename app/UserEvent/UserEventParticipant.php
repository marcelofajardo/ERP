<?php

namespace App\UserEvent;

use Illuminate\Database\Eloquent\Model;

class UserEventParticipant extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'object',
        'object_id',
        'user_event_id',
    ];

    public function event()
    {
        return $this->belongsTo(
            'App\UserEvent\UserEvent',
            'user_event_id',
            'id'
        );
    }
}

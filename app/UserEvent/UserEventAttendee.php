<?php

namespace App\UserEvent;

use Illuminate\Database\Eloquent\Model;

class UserEventAttendee extends Model
{
    protected $fillable = [
        'user_event_id',
        'contact',
        'suggested_time'
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

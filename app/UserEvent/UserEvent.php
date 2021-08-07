<?php

namespace App\UserEvent;

use Illuminate\Database\Eloquent\Model;


class UserEvent extends Model
{
    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'date',  // in case of a date only entry (time will be chosen by attendees) 
        'start', // date time to determine the start of event
        'end',
        'daily_activity_id'
    ];

    public function attendees()
    {
        return $this->hasMany(
            'App\UserEvent\UserEventAttendee'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            'App\User'
        );
    }
}

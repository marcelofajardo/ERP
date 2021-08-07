<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushFcmNotificationHistory extends Model
{

    protected $table      = 'push_fcm_notification_histories';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'token', 'notification_id', 'success', 'error_message'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSysyemIp extends Model
{

    protected $table = 'user_system_ip';

    protected $fillable = ['index_txt','ip','user_id','other_user_name','is_active','notes'];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}

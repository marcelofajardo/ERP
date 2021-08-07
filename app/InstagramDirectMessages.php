<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class InstagramDirectMessages extends Model
{
    public function getSenderUsername()
    {
    	return $this->hasOne('App\InstagramUsersList','user_id','sender_id');
    }
    

    public function getRecieverUsername()
    {
    	return $this->hasOne('App\InstagramUsersList','user_id','receiver_id');
    }
}

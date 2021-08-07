<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="UserDatabase"))
 */

use Illuminate\Database\Eloquent\Model;

class UserDatabase extends Model
{
	  protected $fillable = [
        'database',
        'username',
        'password',
        'user_id'
    ];

    public function user()
    {
      return $this->hasOne(\App\User::class,'id','user_id');
    }

    public function userDatabaseTables()
    {
        return $this->hasMany(\App\UserDatabaseTable::class,'user_database_id','id');
    }
}

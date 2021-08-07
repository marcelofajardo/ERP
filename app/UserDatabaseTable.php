<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="UserDatabase"))
 */

use Illuminate\Database\Eloquent\Model;

class UserDatabaseTable extends Model
{
	  protected $fillable = [
        'name',
        'user_database_id'
    ];

    public function userDatabase()
    {
      return $this->hasOne(\App\UserDatabase::class,'id','user_database_id');
    }
}

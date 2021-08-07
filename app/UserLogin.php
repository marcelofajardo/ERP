<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
		     /**
     * @var string
      * @SWG\Property(property="user_id",type="integer")
      * @SWG\Property(property="login_at",type="string")
      * @SWG\Property(property="logout_at",type="string")
     
     */
  protected $fillable = [
    'user_id', 'login_at', 'logout_at'
  ];

  public function user() {
    return $this->belongsTo('App\User');
  }
}

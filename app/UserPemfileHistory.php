<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class UserPemfileHistory extends Model
{
	protected $table = 'user_pemfile_history';

  protected $fillable = [
    'user_id',
    'server_name',
    'username',
    'action',
    'created_by',
    'extra',
  ];

  public function user() {
    return $this->belongsTo('App\User','user_id','id');
  }

  public function createby() {
    return $this->belongsTo('App\User','created_by','id');
  }
}

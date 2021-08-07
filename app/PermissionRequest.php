<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use App\User;
use Illuminate\Database\Eloquent\Model;

class PermissionRequest extends Model
{   
    protected $table = 'permission_request';

     protected $fillable = [ 
        'user_id',
        'permission_id',
        'request_date',
        'permission_name',
    ];

  	public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}


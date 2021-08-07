<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\User;
use Carbon\Carbon;

class UserLog extends Model
{
		     /**
     * @var string
      * @SWG\Property(property="user_id",type="integer")
      * @SWG\Property(property="url",type="string")
      * @SWG\Property(property="user_name",type="string")
          */
    protected $fillable = ['user_id','url','user_name'];

    public function users(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function scopeBetween($query, Carbon $from, Carbon $to)
    {
        $query->whereBetween('created_at', [$from, $to]);
    }

}

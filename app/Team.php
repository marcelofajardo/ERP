<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
	 /**
     * @var string
      * @SWG\Property(property="name",type="string")
      * @SWG\Property(property="user_id",type="integer")
 
     */
    protected $fillable = ['name','user_id'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

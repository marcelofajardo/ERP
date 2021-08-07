<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="TeamUser"))
 */
use Illuminate\Database\Eloquent\Model;

class TeamUser extends Model
{
   protected $table = 'team_user';
	 /**
     * @var string
      * @SWG\Property(property="team_id",type="integer")
      * @SWG\Property(property="user_id",type="integer")
 
     */
    protected $fillable = ['team_id','user_id'];

    public function team()
    {
        return $this->hasOne(\App\Team::class,'id','team_id');
    }
}

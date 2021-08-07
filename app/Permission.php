<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use App\Role;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getRoleIds()
    {
    	return $this->roles()->allRelatedIds();
    }

    public function getRoleIdsInArray()
    {
    	return $this->roles()->allRelatedIds()->toArray();
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

  
}


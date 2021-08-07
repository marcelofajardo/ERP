<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\Permission;
use App\User;

class Role extends Model
{
     public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
    	return $this->belongsToMany(User::class);
    }

    public function getPermissionsIds()
    {
    	return $this->permissions()->allRelatedIds();
    }

    public function getIdFromName($name)
    {
        return $this->where('name', $name)->first()->id;
    }
}

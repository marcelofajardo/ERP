<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class UserLoginIp extends Model
{
	/**
     * @var string
     * @SWG\Property(property="ip",type="text")
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="is_active",type="boolean")
     * @SWG\Property(property="notes",type="text")
     */
    protected $fillable = ['ip','user_id','is_active','notes'];
}

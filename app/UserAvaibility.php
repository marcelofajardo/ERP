<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class UserAvaibility extends Model
{
    protected $fillable = ['user_id','from','to','status','note','date','day','minute'];

}

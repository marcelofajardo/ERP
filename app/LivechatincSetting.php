<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class LivechatincSetting extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="username",type="string")
     * @SWG\Property(property="key",type="string")
     */
    protected $fillable = ['username','key'];
}

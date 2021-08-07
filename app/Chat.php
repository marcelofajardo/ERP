<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class Chat extends Model
{
    //
    /**
     * @var string
   * @SWG\Property(property="userid",type="integer")
     * @SWG\Property(property="sourceid",type="integer")
     * @SWG\Property(property="messages",type="string")
     */
    protected $fillable = ['userid','sourceid','messages'];
}

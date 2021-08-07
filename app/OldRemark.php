<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class OldRemark extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="old_id",type="integer")
     * @SWG\Property(property="remark",type="string")
     * @SWG\Property(property="user_name",type="string")
     */
    protected $fillable = ['old_id','remark','user_name'];
}

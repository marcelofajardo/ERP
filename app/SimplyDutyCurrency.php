<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SimplyDutyCurrency extends Model
{
     /**
     * @var string
     * @SWG\Property(property="currency",type="string")
     */
    protected $fillable = ['currency'];
}

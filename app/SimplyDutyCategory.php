<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SimplyDutyCategory extends Model
{
   /**
     * @var string
     * @SWG\Property(property="description",type="string")
     * @SWG\Property(property="code",type="string")
     */
    protected $fillable = ['code','description'];
}

<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class GoogleFiletranslatorFile extends Model
{
		   /**
     * @var string
     * @SWG\Property(property="googlefiletranslatorfiles",type="string")
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="tolanguage",type="string")
     */
    protected $table = 'googlefiletranslatorfiles';
    protected $fillable = ['name','tolanguage'];
}

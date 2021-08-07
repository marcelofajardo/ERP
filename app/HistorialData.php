<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class HistorialData extends Model
{
		   /**
     * @var string
     * @SWG\Property(property="historical_datas",type="string")
     */
    protected $table = 'historical_datas';
}

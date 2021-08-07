<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */


use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
	  /**
     * @var string
   * @SWG\Property(property="code",type="string")
   * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="rate",type="float")

     */

    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'code';

    protected $fillable = [
        'code',
        'name',
        'rate'
    ];
}

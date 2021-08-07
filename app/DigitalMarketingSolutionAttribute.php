<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class DigitalMarketingSolutionAttribute extends Model
{

  /**
   * @SWG\Property(property="digital_marketing_platform_id",type="integer")
   * @SWG\Property(property="key",type="string")
   * @SWG\Property(property="value",type="string")

        */
    public $timestamps = false;

    protected $fillable = [
        'digital_marketing_solution_id',
        'key',
        'value',
    ];

}

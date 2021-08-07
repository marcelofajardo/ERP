<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */


use Illuminate\Database\Eloquent\Model;

class DigitalMarketingSolutionResearch extends Model
{
      /**
   * @SWG\Property(property="digital_marketing_platform_id",type="integer")
   * @SWG\Property(property="subject",type="string")
   * @SWG\Property(property="description",type="string")
   * @SWG\Property(property="remarks",type="string")
   * @SWG\Property(property="priority",type="string")
        */


    public $timestamps = false;

    const PRIORITY = [
        0 => "Low",
        1 => "Normal",
        2 => "High",
    ];

    protected $fillable = [
        'subject',
        'description',
        'remarks',
        'priority',
        'digital_marketing_solution_id',
    ];

}

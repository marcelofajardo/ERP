<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class DeveloperMessagesAlertSchedules extends Model
{
		     /**
     * @var string
   * @SWG\Property(property="time",type="string")
   
     */
    protected $casts = [
        'time' => 'array'
    ];
}

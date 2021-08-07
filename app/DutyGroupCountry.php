<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class DutyGroupCountry extends Model
{
	  /**
   * @SWG\Property(property="duty_group_id",type="integer")
   * @SWG\Property(property="country_duty_id",type="integer")
   * @SWG\Property(property="created_at",type="datetime")
   * @SWG\Property(property="updated_at",type="datetime")

        */
    protected $fillable = [
        'duty_group_id',
        'country_duty_id',
        'created_at',
        'updated_at'
    ];
}

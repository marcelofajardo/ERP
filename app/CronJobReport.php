<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

class CronJobReport extends Model
{
	 /**
     * @var string
   * @SWG\Property(property="signature",type="string")
     * @SWG\Property(property="start_time",type="datetime")
     * @SWG\Property(property="end_time",type="datetime")

     */
  protected $fillable = [
    'signature', 'start_time', 'end_time'
  ];
}

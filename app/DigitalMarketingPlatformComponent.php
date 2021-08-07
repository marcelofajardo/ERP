<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class DigitalMarketingPlatformComponent extends Model
{
	/**
   * @SWG\Property(property="digital_marketing_platform_id",type="integer")
   * @SWG\Property(property="name",type="string")
        */
    public $timestamps = false;

    protected $fillable = [
        'digital_marketing_platform_id',
        'name'
    ];
}

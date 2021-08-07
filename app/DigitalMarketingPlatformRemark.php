<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class DigitalMarketingPlatformRemark extends Model
{
	 /**
   * @SWG\Property(property="digital_marketing_platform_id",type="integer")
   * @SWG\Property(property="remarks",type="string")
   * @SWG\Property(property="created_by",type="integer")
   * @SWG\Property(property="created_at",type="datetime")
   * @SWG\Property(property="updated_at",type="datetime")
        */
    protected $fillable = [
        'digital_marketing_platform_id',
        'remarks',
        'created_by',
        'created_at',
        'updated_at',
    ];
}

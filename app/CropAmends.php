<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

class CropAmends extends Model
{
	 /**
     * @var string
   * @SWG\Property(property="settings",type="string")


     */
    protected $casts = [
        'settings' => 'array'
    ];
}

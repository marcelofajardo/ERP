<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

class CountryGroupItem extends Model
{
	  /**
     * @var string
    * @SWG\Property(property="country_code",type="string")
    * @SWG\Property(property="country_group_id",type="integer")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    protected $fillable = [
        'country_code',
        'country_group_id',
        'created_at',
        'updated_at',
    ];
}

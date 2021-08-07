<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

class CountryDuty extends Model
{

    /**
     * @var string
   
     * @SWG\Property(property="hs_code",type="string")
     * @SWG\Property(property="origin",type="string")
     * @SWG\Property(property="destination",type="string")
     * @SWG\Property(property="currency",type="string")
     * @SWG\Property(property="price",type="integer")
     * @SWG\Property(property="duty",type="string")
     * @SWG\Property(property="vat",type="string")
     * @SWG\Property(property="duty_percentage",type="string")
     * @SWG\Property(property="vat_percentage",type="string")
     * @SWG\Property(property="duty_group_id",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")

     */
    protected $fillable = [
        'hs_code',
        'origin',
        'destination',
        'currency',
        'price',
        'duty',
        'vat',
        'duty_percentage',
        'vat_percentage',
        'duty_group_id',
        'created_at',
        'updated_at'
    ];
}

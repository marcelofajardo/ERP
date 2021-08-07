<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SimplyDutyCalculation extends Model
{

	/**
     * @var string
     * @SWG\Property(property="hscode",type="string")
     * @SWG\Property(property="value",type="string")
     * @SWG\Property(property="duty",type="string")
     * @SWG\Property(property="duty_rate",type="float")
     * @SWG\Property(property="duty_hscode",type="string")
     * @SWG\Property(property="duty_type",type="string")
     * @SWG\Property(property="insurance",type="interger")
     * @SWG\Property(property="total",type="string")
     * @SWG\Property(property="exchange_rate",type="float")
     * @SWG\Property(property="currency_type_origin",type="string")

     * @SWG\Property(property="currency_type_destination",type="string")
     * @SWG\Property(property="duty_minimis",type="string")
     * @SWG\Property(property="vat_minimis",type="string")
     * @SWG\Property(property="vat_rate",type="float")
     * @SWG\Property(property="vat",type="string")
     * @SWG\Property(property="composition",type="string")
     */
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['hscode','value','duty','duty_rate','duty_hscode','duty_type','shipping','insurance','total',
    'exchange_rate','currency_type_origin','currency_type_destination','duty_minimis','vat_minimis','vat_rate','vat'];
}

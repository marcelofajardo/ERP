<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class Analytics extends Model
{
    
    /**
     * Fillables for the database
     *
     * @access protected
     *
     * @var array $fillable
     */
    /**
     * @var string
     * @SWG\Property(property="operatingSystem",type="string")
     * @SWG\Property(property="user_type",type="string")
     * @SWG\Property(property="time",type="string")
     * @SWG\Property(property="page_path",type="string")
     * @SWG\Property(property="country",type="string")
     * @SWG\Property(property="city",type="string")
     * @SWG\Property(property="social_network",type="string")
     * @SWG\Property(property="date",type="date")
     * @SWG\Property(property="device_info",type="text")
     * @SWG\Property(property="sessions",type="string")
     * @SWG\Property(property="pageviews",type="string")
     * @SWG\Property(property="bounceRate",type="string")
     * @SWG\Property(property="avgSessionDuration",type="string")
     * @SWG\Property(property="timeOnPage",type="string")

     */
    protected $fillable = array(
        'operatingSystem', 'user_type', 'time', 'page_path',
        'country', 'city', 'social_network', 'date' ,'device_info',
        'sessions', 'pageviews', 'bounceRate', 'avgSessionDuration',
        'timeOnPage'
    );
}

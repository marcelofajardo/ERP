<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class Ad extends Model
{
    /**
     * @var string
     * @SWG\Property(property="campaign_id",type="integer")
     * @SWG\Property(property="adgroup_id",type="integer")
     * @SWG\Property(property="finalurl",type="string")
     * @SWG\Property(property="displayurl",type="string")
     * @SWG\Property(property="headlines",type="string")
     * @SWG\Property(property="descriptions",type="text")
     * @SWG\Property(property="tracking_tamplate",type="string")
     * @SWG\Property(property="final_url_suffix",type="string")
     * @SWG\Property(property="customparam",type="string")
     * @SWG\Property(property="different_url_mobile",type="string")
     * @SWG\Property(property="mobile_final_url",type="string")
     * @SWG\Property(property="ad_id",type="integer")
     * @SWG\Property(property="ad_response",type="strung")
     */
    protected $fillable = [
        'campaign_id',
        'adgroup_id',
        'finalurl',
        'displayurl',
        'headlines',
        'descriptions',
        'tracking_tamplate',
        'final_url_suffix',
        'customparam',
        'different_url_mobile',
        'mobile_final_url',
        'ad_id',
        'ad_response'

    ];
}

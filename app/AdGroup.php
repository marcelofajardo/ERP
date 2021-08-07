<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class AdGroup extends Model
{
	/**
     * @var string
     * @SWG\Property(property="campaign_id",type="integer")
     * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="group_name",type="string")
     * @SWG\Property(property="url",type="string")
     * @SWG\Property(property="keywords",type="string")
     * @SWG\Property(property="budget",type="string")
     * @SWG\Property(property="google_campaign_id",type="integer")
     * @SWG\Property(property="google_ad_group_id",type="integer")
     * @SWG\Property(property="google_ad_group_response",type="string")
     */
    protected $fillable = [
        'campaign_id', 'type', 'group_name', 'url', 'keywords','budget','google_campaign_id','google_ad_group_id','google_ad_group_response',
    ];
}
